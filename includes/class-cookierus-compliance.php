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
        if ($errors instanceof WP_Error && !$errors->get_error(self::ERROR_CODE)) {
            $errors->add(
                self::ERROR_CODE,
                'Используйте email российского почтового сервиса или домен .ru, .su, .рф (например, mail.ru, yandex.ru, rambler.ru или bk.ru).'
            );
        }
    }
}