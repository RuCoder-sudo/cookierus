<?php
/**
 * CookieRus — ограничения регистрации и входа по домену email.
 *
 * Ограничение применяется к стандартному WordPress, WooCommerce и
 * авторизации в wp-admin. Список разрешённых зон соответствует настройке
 * CookieRus: mail.ru, yandex.ru, rambler.ru, bk.ru и домены .ru/.su/.рф.
 */

if (!defined('ABSPATH')) {
    exit;
}

class CookieRus_Compliance {
    private const ERROR_CODE = 'cookierus_foreign_email';

    public static function boot() {
        add_filter('registration_errors', [__CLASS__, 'validate_registration'], 10, 3);
        add_filter('woocommerce_registration_errors', [__CLASS__, 'validate_registration'], 10, 3);
        add_filter('woocommerce_process_registration_errors', [__CLASS__, 'validate_woocommerce_registration'], 10, 4);
        add_action('woocommerce_after_checkout_validation', [__CLASS__, 'validate_checkout_account'], 10, 2);

        // Проверка до стандартной авторизации, если пользователь вводит email.
        add_filter('authenticate', [__CLASS__, 'reject_email_login'], 5, 3);
        // Проверка после поиска пользователя, если вход выполнен по логину.
        add_filter('authenticate', [__CLASS__, 'reject_authenticated_user'], 30, 3);

        // Не даём создать или сохранить в профиле пользователя запрещённый email.
        add_filter('user_profile_update_errors', [__CLASS__, 'validate_profile_email'], 10, 3);
        // Дополнительная защита для программного создания или изменения пользователя.
        add_filter('wp_pre_insert_user_data', [__CLASS__, 'validate_user_data'], 10, 4);
        // Не отправляем ссылку восстановления доступа на запрещённый адрес.
        add_filter('lostpassword_errors', [__CLASS__, 'validate_lostpassword_email'], 10, 2);

        // Optional hard block for foreign social/OAuth providers.
        add_action('init', [__CLASS__, 'block_foreign_auth_request'], 1);
        add_action('wp_head', [__CLASS__, 'print_foreign_auth_guard'], 1);
        add_action('login_head', [__CLASS__, 'print_foreign_auth_guard'], 1);

        // Common social-login plugins expose their provider list through one
        // of these filters. Unknown filters are harmless in WordPress.
        foreach ([
            'nsl_allowed_providers',
            'nextend_social_login_providers',
            'wsl_providers',
            'wordpress_social_login_providers',
        ] as $provider_filter) {
            add_filter($provider_filter, [__CLASS__, 'filter_foreign_providers'], 10);
        }
    }

    public static function is_foreign_auth_block_enabled() {
        $settings = get_option('cookierus_settings', []);
        return !empty($settings['security']['foreign_auth_block']);
    }

    public static function filter_foreign_providers($providers) {
        if (!self::is_foreign_auth_block_enabled() || !is_array($providers)) {
            return $providers;
        }

        foreach ($providers as $key => $provider) {
            $haystack = strtolower((string) $key . ' ' . (is_string($provider) ? $provider : wp_json_encode($provider)));
            if (preg_match('/google|apple|facebook|microsoft|linkedin|github|twitter|x\.com|yahoo|amazon|auth0|okta|foreign|oauth/i', $haystack)) {
                unset($providers[$key]);
            }
        }

        return $providers;
    }

    public static function block_foreign_auth_request() {
        if (!self::is_foreign_auth_block_enabled()) {
            return;
        }
        if (is_admin() && !(function_exists('wp_doing_ajax') && wp_doing_ajax())) {
            return;
        }

        $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
        $provider_values = [];
        foreach (['action', 'provider', 'auth_provider', 'oauth_provider', 'loginSocial', 'hauth_start', 'social_provider'] as $key) {
            if (isset($_REQUEST[$key])) {
                $provider_values[] = sanitize_text_field(wp_unslash($_REQUEST[$key]));
            }
        }

        $haystack = strtolower($request_uri . ' ' . implode(' ', $provider_values));
        $looks_like_social_auth = (bool) preg_match(
            '/(?:oauth|openid|saml|social[-_]?login|login[-_]?social|auth\/(?:google|apple|facebook|microsoft)|'
            . 'login\/(?:google|apple|facebook|microsoft)|google[-_]?login|apple[-_]?login)/i',
            $haystack
        );
        $foreign_provider = (bool) preg_match(
            '/google|apple|facebook|microsoft|linkedin|github|twitter|x\.com|yahoo|amazon|auth0|okta/i',
            $haystack
        );

        if ($looks_like_social_auth && $foreign_provider) {
            wp_die(
                esc_html__('Авторизация через иностранные сервисы отключена администратором сайта.', 'cookierus'),
                esc_html__('Авторизация отключена', 'cookierus'),
                ['response' => 403]
            );
        }
    }

    public static function print_foreign_auth_guard() {
        if (!self::is_foreign_auth_block_enabled()) {
            return;
        }
        ?>
        <style id="cookierus-foreign-auth-style">
            [data-provider*="google" i], [data-provider*="apple" i],
            [data-provider*="facebook" i], [data-provider*="microsoft" i],
            [data-provider*="linkedin" i], [data-provider*="github" i],
            [data-login-provider*="google" i], [data-login-provider*="apple" i],
            [data-login-provider*="facebook" i], [data-login-provider*="microsoft" i],
            [data-login-provider*="linkedin" i], [data-login-provider*="github" i] {
                display: none !important;
            }
        </style>
        <script id="cookierus-foreign-auth-guard">
        (function () {
            var pattern = /google|apple\s*id|facebook|microsoft|linkedin|github|twitter|x\.com|yahoo|amazon|auth0|okta/i;
            function hideForeignProviders(root) {
                (root || document).querySelectorAll('a,button,[role="button"],input[type="submit"]').forEach(function (element) {
                    var text = (element.textContent || '') + ' ' +
                        (element.getAttribute('aria-label') || '') + ' ' +
                        (element.getAttribute('data-provider') || '') + ' ' +
                        (element.getAttribute('data-login-provider') || '');
                    if (pattern.test(text)) {
                        element.setAttribute('aria-hidden', 'true');
                        element.setAttribute('disabled', 'disabled');
                        element.style.display = 'none';
                    }
                });
            }
            hideForeignProviders(document);
            document.addEventListener('click', function (event) {
                var target = event.target && event.target.closest
                    ? event.target.closest('a,button,[role="button"],input[type="submit"]')
                    : null;
                if (!target) return;
                var text = (target.textContent || '') + ' ' +
                    (target.getAttribute('aria-label') || '') + ' ' +
                    (target.getAttribute('data-provider') || '') + ' ' +
                    (target.getAttribute('data-login-provider') || '');
                if (pattern.test(text)) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            }, true);
            if (window.MutationObserver) {
                new MutationObserver(function () { hideForeignProviders(document); })
                    .observe(document.documentElement, {childList: true, subtree: true});
            }
        }());
        </script>
        <?php
    }

    /**
     * Разрешены российские доменные зоны и явно указанные российские сервисы.
     *
     * @param string $email
     * @return bool
     */
    public static function is_allowed_email($email) {
        if (!is_string($email)) {
            return false;
        }

        $email = trim($email);
        if ($email === '' || substr_count($email, '@') !== 1) {
            return false;
        }

        [$local_part, $domain] = explode('@', $email, 2);
        if ($local_part === '' || $domain === '' || preg_match('/[\s<>()[\],;:]/', $email)) {
            return false;
        }

        $domain = self::normalize_domain($domain);
        if ($domain === '') {
            return false;
        }

        $explicit_domains = ['mail.ru', 'yandex.ru', 'rambler.ru', 'bk.ru'];
        if (in_array($domain, $explicit_domains, true)) {
            return true;
        }

        foreach (['.ru', '.su', '.рф', '.xn--p1ai'] as $suffix) {
            if (substr($domain, -strlen($suffix)) === $suffix && strlen($domain) > strlen($suffix)) {
                return true;
            }
        }

        return false;
    }

    public static function validate_registration($errors, $username = '', $email = '') {
        if (!self::is_allowed_email($email)) {
            self::add_email_error($errors);
        }

        return $errors;
    }

    public static function validate_checkout_account($data, $errors) {
        if (!empty($_POST['createaccount']) && !empty($data['billing_email'])) {
            if (!self::is_allowed_email($data['billing_email'])) {
                self::add_email_error($errors);
            }
        }
    }

    public static function validate_woocommerce_registration($errors, $username = '', $password = '', $email = '') {
        if (!self::is_allowed_email($email)) {
            self::add_email_error($errors);
        }

        return $errors;
    }

    public static function reject_email_login($user, $username, $password) {
        if ($user instanceof WP_Error) {
            return $user;
        }

        if (is_email($username) && !self::is_allowed_email($username)) {
            return self::email_error();
        }

        return $user;
    }

    public static function reject_authenticated_user($user, $username, $password) {
        if ($user instanceof WP_User && !self::is_allowed_email($user->user_email)) {
            return self::email_error();
        }

        return $user;
    }

    public static function validate_profile_email($errors, $update, $user) {
        $email = isset($_POST['email']) ? sanitize_text_field(wp_unslash($_POST['email'])) : '';
        if ($email !== '' && !self::is_allowed_email($email)) {
            self::add_email_error($errors);
        }

        return $errors;
    }

    public static function validate_user_data($data, $update, $user_id, $userdata) {
        if (isset($data['user_email']) && $data['user_email'] !== '' && !self::is_allowed_email($data['user_email'])) {
            // Оставляем стандартному WordPress задачу остановить операцию с невалидным email.
            $data['user_email'] = 'cookierus-invalid-email';
        }

        return $data;
    }

    public static function validate_lostpassword_email($errors, $user_data = null) {
        if ($user_data instanceof WP_User && !self::is_allowed_email($user_data->user_email)) {
            self::add_email_error($errors);
        }

        return $errors;
    }

    private static function normalize_domain($domain) {
        $domain = strtolower(trim($domain));
        $domain = rtrim($domain, '.');

        if (function_exists('idn_to_ascii') && defined('IDNA_DEFAULT')) {
            $ascii_domain = idn_to_ascii($domain, IDNA_DEFAULT, defined('INTL_IDNA_VARIANT_UTS46') ? INTL_IDNA_VARIANT_UTS46 : 0);
            if ($ascii_domain !== false) {
                $domain = strtolower($ascii_domain);
            }
        }

        return $domain;
    }

    private static function email_error() {
        return new WP_Error(
            self::ERROR_CODE,
            'Регистрация и вход разрешены только с адресов российских почтовых сервисов и доменов .ru, .su или .рф.'
        );
    }

    private static function add_email_error($errors) {
        if ($errors instanceof WP_Error && !in_array(self::ERROR_CODE, $errors->get_error_codes(), true)) {
            $errors->add(
                self::ERROR_CODE,
                'Используйте email российского почтового сервиса или домен .ru, .su, .рф (например, mail.ru, yandex.ru, rambler.ru или bk.ru).'
            );
        }
    }
}
