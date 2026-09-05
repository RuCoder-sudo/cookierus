<?php
/**
 * Plugin Name: CookieRus
 * Plugin URI: https://github.com/RuCoder-sudo/cookierus
 * Description: Простой способ убедиться, что ваш сайт соответствует Закону России о файлах cookie.
 * Version: 1.1.3
 * Author: Сергей Солошенко (RuCoder)
 * Author URI: https://рукодер.рф
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cookierus
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.7
 * WC requires at least: 4.0
 * WC tested up to: 8.5
 * Network: false
 * 
 * Разработчик: Сергей Солошенко | РуКодер
 * Специализация: Веб-разработка с 2018 года | WordPress / Full Stack
 * Принцип работы: "Сайт как для себя"
 * Контакты: 
 * - Телефон/WhatsApp: +7 (985) 985-53-97
 * - Email: support@рукодер.рф
 * - Telegram: @RussCoder
 * - Портфолио: https://рукодер.рф
 * - GitHub: https://github.com/RuCoder-sudo
 */

if (!defined('ABSPATH')) exit;

define('COOKIERUS_VERSION', '1.1.3');
define('COOKIERUS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('COOKIERUS_PLUGIN_DIR', plugin_dir_path(__FILE__));

if (file_exists(plugin_dir_path(__FILE__) . 'includes/class-cookierus-updater.php')) {
    require_once plugin_dir_path(__FILE__) . 'includes/class-cookierus-updater.php';
}
if (file_exists(plugin_dir_path(__FILE__) . 'includes/class-cookierus-compliance.php')) {
    require_once plugin_dir_path(__FILE__) . 'includes/class-cookierus-compliance.php';
}

class CookieRus {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_init', [$this, 'migrate_settings'], 20);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_head', [$this, 'print_consent_guard'], 0);
        add_action('wp_footer', [$this, 'render_banner']);
        add_action('template_redirect', [$this, 'start_output_buffer']);
        add_action('wp_ajax_cookierus_log_consent', [$this, 'ajax_log_consent']);
        add_action('wp_ajax_nopriv_cookierus_log_consent', [$this, 'ajax_log_consent']);
        add_action('admin_init', [$this, 'handle_csv_export']);
        add_action('admin_init', [$this, 'handle_clear_logs']);
        add_action('admin_init', [$this, 'handle_clear_old_logs']);
        add_action('before_woocommerce_init', [$this, 'declare_woo_compatibility']);
        register_activation_hook(__FILE__, [$this, 'activate']);

        if (class_exists('CookieRus_Compliance')) {
            CookieRus_Compliance::boot();
        }

        // Инициализация автообновления через GitHub
        if (class_exists('CookieRus_Updater')) {
            new CookieRus_Updater(__FILE__, COOKIERUS_VERSION);
        }
    }

    public function start_output_buffer() {
        if (is_admin()) return;
        if ($this->is_technical_request()) return;

        $settings = get_option('cookierus_settings', []);
        $settings = is_array($settings) ? $settings : [];
        /*
         * The blocker must remain active even if an administrator temporarily
         * hides the banner. Otherwise a script in a theme/footer can collect
         * data while the consent UI is disabled.
         */
        $strict_blocking = !isset($settings['security']['strict_blocking'])
            || !empty($settings['security']['strict_blocking']);
        if (!$strict_blocking && empty($settings['banner']['enabled'])) return;

        // When repeat_show=always, show banner on every page load (session cookie is cleared between visits)
        $repeat_show = $settings['banner']['repeat_show'] ?? 'never';
        $needs_banner_markup = !empty($settings['banner']['enabled'])
            && ($repeat_show === 'always'
                || !isset($_COOKIE['cookierus_consent'])
                || !isset($settings['banner']['show_revoke_button'])
                || !empty($settings['banner']['show_revoke_button']));
        if ($needs_banner_markup) {
            ob_start();
            include plugin_dir_path(__FILE__) . 'templates/banner-template.php';
            $this->banner_html_cache = ob_get_clean();
        }

        ob_start([$this, 'inject_banner_before_body_close']);
    }

    private $banner_html_cache = '';

    /**
     * Technical WordPress responses must never be modified with HTML.
     *
     * @return bool
     */
    private function is_technical_request() {
        if (function_exists('is_robots') && is_robots()) return true;
        if (function_exists('is_feed') && is_feed()) return true;
        if (defined('REST_REQUEST') && REST_REQUEST) return true;
        if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) return true;

        $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
        $request_path = (string) wp_parse_url($request_uri, PHP_URL_PATH);

        return (bool) preg_match(
            '#/(?:robots\.txt$|wp-json(?:/|$)|xmlrpc\.php$|(?:[a-z0-9_-]+[-_])?sitemap(?:[-_][a-z0-9_-]+|[0-9]+)?\.xml$)#i',
            $request_path
        );
    }

    public function inject_banner_before_body_close($html) {
        $html = $this->protect_html_resources($html);
        $html = $this->inject_guard_into_html($html);

        if (empty($this->banner_html_cache)) return $html;

        // Avoid duplicate if wp_footer already injected it
        if (strpos($html, 'id="cookierus-banner"') !== false) return $html;

        $pos = strrpos($html, '</body>');
        if ($pos !== false) {
            return substr($html, 0, $pos) . $this->banner_html_cache . substr($html, $pos);
        }

        // Do not append HTML to responses without a closing body tag.
        return $html;
    }

    /**
     * Return the consent categories sent by the browser.
     *
     * @return array
     */
    public static function get_consent_categories() {
        if (empty($_COOKIE['cookierus_consent']) || $_COOKIE['cookierus_consent'] === 'declined') {
            return [];
        }

        $raw = isset($_COOKIE['cookierus_categories'])
            ? sanitize_text_field(wp_unslash($_COOKIE['cookierus_categories']))
            : 'all';
        $categories = array_filter(array_map('sanitize_key', explode(',', $raw)));

        if (in_array('all', $categories, true) || in_array('accepted', $categories, true)) {
            return array_values(array_unique(array_merge(
                ['necessary', 'functional', 'analytics', 'performance', 'advertising'],
                self::get_custom_category_ids()
            )));
        }

        return array_values(array_unique(array_merge(['necessary'], $categories)));
    }

    /**
     * Return enabled custom consent-category IDs for the "accept all" state.
     *
     * @return array
     */
    private static function get_custom_category_ids() {
        $settings = get_option('cookierus_settings', []);
        $custom_categories = is_array($settings['custom_categories'] ?? null)
            ? $settings['custom_categories']
            : [];
        $ids = [];

        foreach ($custom_categories as $category) {
            if (!empty($category['enabled']) && !empty($category['id'])) {
                $ids[] = sanitize_key($category['id']);
            }
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * Public bridge for integrations that need to defer their own scripts.
     *
     * @param string|null $category
     * @return bool
     */
    public static function has_consent($category = null) {
        $categories = self::get_consent_categories();
        if ($category === null) {
            return !empty($categories);
        }

        return in_array(sanitize_key($category), $categories, true);
    }

    public static function is_service_enabled($service) {
        $service = sanitize_key($service);
        $settings = get_option('cookierus_settings', []);
        $services = [
            'yandex_metrika' => $settings['sections']['analytics_services']['yandex_metrika'] ?? 1,
            'mailru_counters' => $settings['sections']['analytics_services']['mailru_counters'] ?? 0,
            'callibri' => $settings['sections']['analytics_services']['callibri'] ?? 0,
            'vk_ads' => $settings['sections']['advertising_services']['vk_ads'] ?? 0,
            'yandex_ads' => $settings['sections']['advertising_services']['yandex_ads'] ?? 1,
        ];

        return !empty($services[$service]);
    }

    private function blocked_domains() {
        $settings = get_option('cookierus_settings', []);
        $configured = $settings['security']['blocked_domains'] ?? '';
        $defaults = [
            'mc.yandex.ru',
            'yandex.ru',
            'yastatic.net',
            'callibri.ru',
            'callibri.com',
            'top.mail.ru',
            'top-fwz1.mail.ru',
            'mail.ru',
            'vk.com',
            'vk.ru',
            'ads.yandex.ru',
            'an.yandex.ru',
            'connect.facebook.net',
            'facebook.net',
            'google-analytics.com',
            'analytics.google.com',
            'googletagmanager.com',
            'doubleclick.net',
            'googlesyndication.com',
            'googleadservices.com',
            'hotjar.com',
            'clarity.ms',
            'matomo.cloud',
            'bat.bing.com',
            'counter.yadro.ru',
            'cnt.rambler.ru',
            'pixel.wp.com',
            'stats.wp.com',
        ];

        $custom = preg_split('/[\s,;]+/', (string) $configured, -1, PREG_SPLIT_NO_EMPTY);
        $custom = array_map(function ($domain) {
            return strtolower(trim(preg_replace('#^https?://#i', '', $domain), " \t\n\r\0\x0B./"));
        }, $custom ?: []);

        return array_values(array_unique(array_filter(array_merge($defaults, $custom))));
    }

    private function tracker_category_for_url($url) {
        $url = strtolower((string) $url);
        if (preg_match('/vk\.com|vk\.ru|facebook|doubleclick|googlesyndication|googleadservices|ads\.yandex|an\.yandex/', $url)) {
            return 'advertising';
        }
        if (preg_match('/yandex|yastatic|callibri|mail\.ru|top\.mail\.ru|matomo|google-analytics|googletagmanager|hotjar|clarity\.ms/', $url)) {
            return 'analytics';
        }

        return 'analytics';
    }

    private function service_is_allowed($url) {
        $settings = get_option('cookierus_settings', []);
        $analytics = $settings['sections']['analytics_services'] ?? [
            'yandex_metrika' => 1,
            'mailru_counters' => 0,
            'callibri' => 0,
        ];
        $advertising = $settings['sections']['advertising_services'] ?? [
            'vk_ads' => 0,
            'yandex_ads' => 1,
        ];
        $url = strtolower((string) $url);

        if (preg_match('/mc\.yandex|metrika|yastatic/', $url)) {
            return !empty($analytics['yandex_metrika']);
        }
        if (preg_match('/callibri/', $url)) {
            return !empty($analytics['callibri']);
        }
        if (preg_match('/top\.mail\.ru|mail\.ru/', $url)) {
            return !empty($analytics['mailru_counters']);
        }
        if (preg_match('/ads\.yandex|an\.yandex/', $url)) {
            return !empty($advertising['yandex_ads']);
        }
        if (preg_match('/vk\.com|vk\.ru/', $url)) {
            return !empty($advertising['vk_ads']);
        }
        if (preg_match('/facebook|google-analytics|analytics\.google|googletagmanager|doubleclick|googlesyndication|googleadservices|hotjar|clarity\.ms|matomo|bat\.bing|yadro\.ru|rambler|pixel\.wp\.com|stats\.wp\.com/', $url)) {
            return false;
        }

        // Other known collectors remain category-controlled, but are not
        // offered as selectable CookieRus services.
        return true;
    }

    private function should_block_url($url) {
        $url = (string) $url;
        if ($url === '' || strpos($url, 'data:') === 0 || strpos($url, 'blob:') === 0) {
            return false;
        }

        $normalized_url = strtolower($url);
        $local_tracker = (bool) preg_match(
            '/wp[-_]yandex[-_]metrika|yandex[-_]metrika|callibri|top[-.]fwz1[-.]mail[-.]ru/',
            $normalized_url
        );
        $host = strtolower((string) wp_parse_url($url, PHP_URL_HOST));
        if ($host === '' && !$local_tracker) {
            return false;
        }

        $matches = $local_tracker;
        foreach ($this->blocked_domains() as $domain) {
            if ($host === $domain || substr($host, -strlen('.' . $domain)) === '.' . $domain) {
                $matches = true;
                break;
            }
        }
        if (!$matches) return false;

        $categories = self::get_consent_categories();
        return !in_array($this->tracker_category_for_url($url), $categories, true)
            || !$this->service_is_allowed($url);
    }

    private function is_blocked_inline_script($script) {
        return (bool) preg_match(
            '/mc\.yandex\.ru|yastatic\.net|callibri|top\.mail\.ru|vk\.com\/js|connect\.facebook\.net|google-analytics|googletagmanager|doubleclick|googlesyndication|hotjar|clarity\.ms|yaCounter\d*|_ym[a-z_]*|\bym\s*\(/i',
            (string) $script
        );
    }

    private function get_setting_value($path, $default = null) {
        $value = get_option('cookierus_settings', []);
        foreach (explode('.', (string) $path) as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $default;
            }
            $value = $value[$part];
        }

        return $value;
    }

    private function replace_tag_attribute($attributes, $attribute, $value) {
        $pattern = '/\s' . preg_quote($attribute, '/') . '\s*=\s*(["\'])(.*?)\1/i';
        $attributes = preg_replace($pattern, '', (string) $attributes);
        return rtrim($attributes) . ' data-cookierus-blocked-src="' . esc_attr($value) . '"';
    }

    /**
     * Rewrite known tracking resources before the browser can execute them.
     * This covers scripts injected by themes, headers, footers and plugins.
     */
    private function protect_html_resources($html) {
        if (!is_string($html) || $html === '') return $html;

        $html = preg_replace_callback('/<script\b([^>]*)>(.*?)<\/script\s*>/is', function ($match) {
            $attributes = $match[1];
            $body = $match[2];
            if (preg_match('/id\s*=\s*["\'](?:cookierus-preconsent-guard|cookierus-banner-script)["\']/i', $attributes)) {
                return $match[0];
            }

            $src = '';
            if (preg_match('/\ssrc\s*=\s*(["\'])(.*?)\1/i', $attributes, $src_match)) {
                $src = html_entity_decode($src_match[2], ENT_QUOTES, 'UTF-8');
            }

            if (($src && $this->should_block_url($src)) || (!$src && $this->is_blocked_inline_script($body))) {
                $category = $this->tracker_category_for_url($src ?: $body);
                $attributes = preg_replace('/\s+type\s*=\s*(["\'])(.*?)\1/i', '', $attributes);
                $attributes .= ' type="application/x-cookierus-blocked" data-cookierus-blocked-category="' . esc_attr($category) . '"';
                if ($src) {
                    $attributes = $this->replace_tag_attribute($attributes, 'src', $src);
                } else {
                    $attributes .= ' data-cookierus-blocked-inline="1"';
                }
                return '<script' . $attributes . '>' . $body . '</script>';
            }

            return $match[0];
        }, $html);

        $html = preg_replace_callback('/<(img|iframe|video|audio)\b([^>]*?)\s(src|poster)\s*=\s*(["\'])(.*?)\4([^>]*)>/is', function ($match) {
            $url = html_entity_decode($match[5], ENT_QUOTES, 'UTF-8');
            if (!$this->should_block_url($url)) return $match[0];

            $attributes = $match[2] . $match[6];
            $attributes = $this->replace_tag_attribute($attributes, $match[3], $url);
            return '<' . $match[1] . $attributes . '>';
        }, $html);

        return $html;
    }

    private function inject_guard_into_html($html) {
        if (strpos($html, 'id="cookierus-preconsent-guard"') !== false) return $html;
        $guard = $this->get_consent_guard_markup();
        $head_pos = stripos($html, '</head>');
        if ($head_pos !== false) {
            return substr($html, 0, $head_pos) . $guard . substr($html, $head_pos);
        }

        return $guard . $html;
    }

    public function print_consent_guard() {
        if (is_admin() || $this->is_technical_request()) return;
        echo $this->get_consent_guard_markup();
    }

    private function get_consent_guard_markup() {
        $categories = self::get_consent_categories();
        $blocked_domains = $this->blocked_domains();
        $state = [
            'categories' => array_values($categories),
            'blockedDomains' => array_values($blocked_domains),
            'services' => [
                'yandex_metrika' => (bool) $this->get_setting_value('sections.analytics_services.yandex_metrika', 1),
                'mailru_counters' => (bool) $this->get_setting_value('sections.analytics_services.mailru_counters', 0),
                'callibri' => (bool) $this->get_setting_value('sections.analytics_services.callibri', 0),
                'vk_ads' => (bool) $this->get_setting_value('sections.advertising_services.vk_ads', 0),
                'yandex_ads' => (bool) $this->get_setting_value('sections.advertising_services.yandex_ads', 1),
            ],
        ];

        $json = wp_json_encode($state);
        return '<script id="cookierus-preconsent-guard">/* CookieRus consent firewall */' .
            '(function(w,d,n,s){' .
            'var st=' . $json . ';' .
            'w.__cookierusConsentState=st;' .
            'function host(u){try{return new URL(u,w.location.href).hostname.toLowerCase()}catch(e){return""}}' .
            'function cat(u){u=String(u||"").toLowerCase();return /vk\\.com|vk\\.ru|facebook|doubleclick|googlesyndication|googleadservices|ads\\.yandex|an\\.yandex/.test(u)?"advertising":"analytics"}' .
            'function serviceAllowed(u){u=String(u||"").toLowerCase();var s=st.services||{};' .
            'if(/mc\\.yandex|metrika|yastatic/.test(u))return !!s.yandex_metrika;' .
            'if(/callibri/.test(u))return !!s.callibri;' .
            'if(/top\\.mail\\.ru|mail\\.ru/.test(u))return !!s.mailru_counters;' .
            'if(/ads\\.yandex|an\\.yandex/.test(u))return !!s.yandex_ads;' .
            'if(/vk\\.com|vk\\.ru/.test(u))return !!s.vk_ads;' .
            'if(/facebook|google-analytics|analytics\\.google|googletagmanager|doubleclick|googlesyndication|googleadservices|hotjar|clarity\\.ms|matomo|bat\\.bing|yadro\\.ru|rambler|pixel\\.wp\\.com|stats\\.wp\\.com/.test(u))return false;' .
            'return true;}' .
             'function inlineCategory(v){return /vk\\.com|vk\\.ru|facebook|doubleclick|googlesyndication|googleadservices|ads\\.yandex|an\\.yandex/.test(String(v||"").toLowerCase())?"advertising":"analytics"}' .
             'function inlineBlocked(v){return /mc\\.yandex\\.ru|yastatic\\.net|callibri|top\\.mail\\.ru|vk\\.com\\/js|connect\\.facebook\\.net|google-analytics|googletagmanager|doubleclick|googlesyndication|googleadservices|hotjar|clarity\\.ms|yaCounter\\d*|_ym[a-z_]*|\\bym\\s*\\(/i.test(String(v||""))}' .
             'function blocked(u){var raw=String(u||"").toLowerCase(),h=host(u),local=/wp[-_]yandex[-_]metrika|yandex[-_]metrika|callibri|top[-.]fwz1[-.]mail[-.]ru/.test(raw);if(!h&&!local)return false;var hit=local||st.blockedDomains.some(function(x){return h===x||h.slice(-(x.length+1))==="."+x});return hit&&(st.categories.indexOf(cat(u))<0||!serviceAllowed(u))}' .
            'w.CookieRusIsBlocked=blocked;' .
            'var callibriAllowed=st.categories.indexOf("analytics")>=0&&!!(st.services&&st.services.callibri);' .
            'function callibriNode(el){if(!el||(el.nodeType!==1&&el.nodeType!==11))return false;var id=String(el.id||""),cn=typeof el.className==="string"?el.className:"";return /^cbw-/.test(id)||/(^|\\s)cbw-/.test(cn)||(el.querySelector&&!!el.querySelector("[id^=cbw-],[class*=cbw-]"))}' .
            'function callibriBlocked(el){return !callibriAllowed&&callibriNode(el)}' .
            'var widgetStyle=d.createElement("style");widgetStyle.id="cookierus-callibri-firewall-style";widgetStyle.textContent="#cbw-buttonContainer,#cbw-popupContainer,[id^=cbw-],[class*=cbw-]{display:none!important;visibility:hidden!important;pointer-events:none!important}";(d.head||d.documentElement).appendChild(widgetStyle);' .
            'function release(){callibriAllowed=st.categories.indexOf("analytics")>=0&&!!(st.services&&st.services.callibri);d.querySelectorAll("[data-cookierus-blocked-src]").forEach(function(el){var u=el.getAttribute("data-cookierus-blocked-src"),svc=el.getAttribute("data-cookierus-blocked-service");if(!u||blocked(u)||(svc&&!st.services[svc]))return;el.removeAttribute("data-cookierus-blocked-src");if(el.tagName==="SCRIPT"&&el.type==="application/x-cookierus-blocked")el.removeAttribute("type");el.setAttribute("src",u)});d.querySelectorAll("script[data-cookierus-blocked-inline]").forEach(function(el){var svc=el.getAttribute("data-cookierus-blocked-service");if(st.categories.indexOf(el.getAttribute("data-cookierus-blocked-category"))<0||!serviceAllowed(el.textContent||"")||(svc&&!st.services[svc]))return;var replacement=d.createElement("script");for(var i=0;i<el.attributes.length;i++){var attr=el.attributes[i];if(attr.name!=="type"&&attr.name!=="data-cookierus-blocked-inline"&&attr.name!=="data-cookierus-blocked-category"&&attr.name!=="data-cookierus-blocked-service")replacement.setAttribute(attr.name,attr.value)}replacement.text=el.textContent||"";if(el.parentNode)el.parentNode.replaceChild(replacement,el)});if(callibriAllowed){var ws=d.getElementById("cookierus-callibri-firewall-style");if(ws)ws.remove()}}' .
            'w.CookieRusReleaseBlockedResources=release;' .
             'function guardNode(el){if(callibriBlocked(el))return null;if(!el||!el.getAttribute)return el;var u=el.getAttribute("src")||el.getAttribute("poster");if(u&&blocked(u)){el.setAttribute("data-cookierus-blocked-src",u);el.removeAttribute("src");el.removeAttribute("poster");if(el.tagName==="SCRIPT")el.type="application/x-cookierus-blocked"}if(el.tagName==="SCRIPT"&&!u&&inlineBlocked(el.textContent||"")&&(st.categories.indexOf(inlineCategory(el.textContent||""))<0||!serviceAllowed(el.textContent||""))){el.setAttribute("data-cookierus-blocked-inline","1");el.setAttribute("data-cookierus-blocked-category",inlineCategory(el.textContent||""));el.type="application/x-cookierus-blocked"}return el}' .
            'var ap=Node.prototype.appendChild,ib=Node.prototype.insertBefore;' .
            'Node.prototype.appendChild=function(el){var guarded=guardNode(el);return guarded?ap.call(this,guarded):el};' .
            'Node.prototype.insertBefore=function(el,ref){var guarded=guardNode(el);return guarded?ib.call(this,guarded,ref):el};' .
            'if(Element.prototype.append){var ea=Element.prototype.append;Element.prototype.append=function(){for(var i=0;i<arguments.length;i++)if(callibriBlocked(arguments[i]))return;return ea.apply(this,arguments)}}' .
            'if(Element.prototype.prepend){var ep=Element.prototype.prepend;Element.prototype.prepend=function(){for(var i=0;i<arguments.length;i++)if(callibriBlocked(arguments[i]))return;return ep.apply(this,arguments)}}' .
            'if(Element.prototype.insertAdjacentHTML){var iah=Element.prototype.insertAdjacentHTML;Element.prototype.insertAdjacentHTML=function(p,h){if(!callibriAllowed&&/cbw-/.test(String(h)))return;return iah.call(this,p,h)}}' .
            'var ih=Object.getOwnPropertyDescriptor(Element.prototype,"innerHTML");if(ih&&ih.set){Object.defineProperty(Element.prototype,"innerHTML",{configurable:ih.configurable,enumerable:ih.enumerable,get:ih.get,set:function(v){if(!callibriAllowed&&/cbw-/.test(String(v)))return;return ih.set.call(this,v)}})}' .
            'var osa=Element.prototype.setAttribute;' .
            'Element.prototype.setAttribute=function(k,v){if((k==="src"||k==="poster")&&blocked(v)){osa.call(this,"data-cookierus-blocked-src",v);return}return osa.call(this,k,v)};' .
             'if(d.write){var dw=d.write;d.write=function(v){if(inlineBlocked(v)&&st.categories.indexOf(inlineCategory(v))<0)return;return dw.call(d,v)}}' .
            'if(w.fetch){var f=w.fetch;w.fetch=function(u,o){if(blocked(typeof u==="string"?u:u&&u.url))return Promise.reject(new Error("CookieRus blocked tracking request"));return f.apply(this,arguments)}}' .
            'if(w.XMLHttpRequest){var xo=w.XMLHttpRequest.prototype.open,xs=w.XMLHttpRequest.prototype.send;w.XMLHttpRequest.prototype.open=function(m,u){this.__cookierusBlocked=blocked(u);if(this.__cookierusBlocked)return;return xo.apply(this,arguments)};w.XMLHttpRequest.prototype.send=function(){if(this.__cookierusBlocked)return;return xs.apply(this,arguments)}}' .
            'if(n&&n.sendBeacon){var sb=n.sendBeacon.bind(n);n.sendBeacon=function(u){if(blocked(u))return true;return sb.apply(n,arguments)}}' .
            'if(w.HTMLImageElement){var ip=Object.getOwnPropertyDescriptor(w.HTMLImageElement.prototype,"src");if(ip&&ip.set){Object.defineProperty(w.HTMLImageElement.prototype,"src",{get:ip.get,set:function(v){if(blocked(v)){osa.call(this,"data-cookierus-blocked-src",v);return}ip.set.call(this,v)}})}}' .
            'if(w.HTMLIFrameElement){var fp=Object.getOwnPropertyDescriptor(w.HTMLIFrameElement.prototype,"src");if(fp&&fp.set){Object.defineProperty(w.HTMLIFrameElement.prototype,"src",{get:fp.get,set:function(v){if(blocked(v)){osa.call(this,"data-cookierus-blocked-src",v);return}fp.set.call(this,v)}})}}' .
            '})(window,document,navigator);</script>';
    }

    public function declare_woo_compatibility() {
        if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        }
    }

    public function handle_csv_export() {
        if (isset($_GET['page']) && $_GET['page'] === 'cookierus' && isset($_GET['action']) && $_GET['action'] === 'cookierus_export_csv') {
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized');
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'cookierus_logs';
            $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=cookierus-logs-' . date('Y-m-d') . '.csv');

            $output = fopen('php://output', 'w');
            fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // BOM for Excel

            if (!empty($results)) {
                fputcsv($output, array_keys($results[0]));
                foreach ($results as $row) {
                    fputcsv($output, $row);
                }
            }
            fclose($output);
            exit;
        }
    }

    public function handle_clear_logs() {
        if (isset($_GET['page']) && $_GET['page'] === 'cookierus' && isset($_GET['action']) && $_GET['action'] === 'cookierus_clear_logs') {
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized');
            }
            
            check_admin_referer('cookierus_clear_logs_action', 'cookierus_nonce');

            global $wpdb;
            $table_name = $wpdb->prefix . 'cookierus_logs';
            $wpdb->query("TRUNCATE TABLE $table_name");

            wp_redirect(admin_url('admin.php?page=cookierus&tab=logs&cleared=1'));
            exit;
        }
    }

    public function handle_clear_old_logs() {
        if (isset($_GET['page']) && $_GET['page'] === 'cookierus'
            && isset($_GET['action']) && $_GET['action'] === 'cookierus_clear_old_logs') {
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized');
            }

            check_admin_referer('cookierus_clear_old_logs_action', 'cookierus_nonce');

            global $wpdb;
            $table_name = $wpdb->prefix . 'cookierus_logs';
            $cutoff = gmdate('Y-m-d H:i:s', strtotime('-1 year'));
            $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE created_at < %s", $cutoff));

            wp_redirect(admin_url('admin.php?page=cookierus&tab=logs&cleared_old=1'));
            exit;
        }
    }

    public function ajax_log_consent() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cookierus_logs';

        check_ajax_referer('cookierus_log_consent', 'nonce');
        
        $status = sanitize_text_field($_POST['status'] ?? '');
        $categories = sanitize_text_field($_POST['categories'] ?? '');
        $uid = sanitize_text_field($_POST['uid'] ?? '');
        
        if (empty($status) || empty($uid)) {
            wp_send_json_error(['message' => 'Missing data', 'post' => $_POST]);
        }

        $ip = $this->anonymize_ip($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        
        $result = $wpdb->insert($table_name, [
            'uid' => $uid,
            'ip' => $ip,
            'status' => $status,
            'categories' => $categories,
            'user_id' => get_current_user_id(),
            'country' => '',
        ]);

        if ($result === false) {
            wp_send_json_error(['message' => 'DB Error', 'error' => $wpdb->last_error]);
        }

        wp_send_json_success();
    }

    public function activate() {
        $this->create_log_table();
        if (!get_option('cookierus_settings')) {
            update_option('cookierus_settings', $this->get_default_settings());
        }
    }

    private function create_log_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cookierus_logs';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) DEFAULT 0,
            uid varchar(50) NOT NULL,
            ip varchar(45) NOT NULL,
            country varchar(100),
            status varchar(20) NOT NULL,
            categories text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    private function anonymize_ip($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = '0';
            return implode('.', $parts);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ip);
            $parts = array_slice($parts, 0, 4);
            return implode(':', $parts) . ':0:0:0:0';
        }

        return '0.0.0.0';
    }

    public function add_admin_menu() {
        $page = add_menu_page(
            'CookieRus',
            'CookieRus',
            'manage_options',
            'cookierus',
            [$this, 'render_admin_page'],
            'dashicons-shield-alt',
            100
        );
        add_action('admin_print_styles-' . $page, [$this, 'enqueue_admin_assets']);
    }

    public function enqueue_admin_assets() {
        wp_enqueue_style('cookierus-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
    }

    public function register_settings() {
        register_setting('cookierus_settings_group', 'cookierus_settings', [
            'sanitize_callback' => [$this, 'sanitize_settings'],
        ]);
    }

    /**
     * Нормализует настройки и оставляет только поддерживаемые значения.
     *
     * @param mixed $value
     * @return array
     */
    public function sanitize_settings($value) {
        if (!is_array($value)) {
            return [];
        }

        $value['banner'] = is_array($value['banner'] ?? null) ? $value['banner'] : [];
        $value['sections'] = is_array($value['sections'] ?? null) ? $value['sections'] : [];
        $value['security'] = is_array($value['security'] ?? null) ? $value['security'] : [];

        $allowed_trackers = ['ym_id', 'vk_id'];
        $trackers = is_array($value['trackers'] ?? null) ? $value['trackers'] : [];
        $value['trackers'] = [];
        foreach ($allowed_trackers as $tracker_key) {
            $value['trackers'][$tracker_key] = sanitize_text_field($trackers[$tracker_key] ?? '');
        }
        $callibri_code = is_string($trackers['callibri_code'] ?? null)
            ? wp_unslash($trackers['callibri_code'])
            : '';
        $value['trackers']['callibri_code'] = substr(str_replace("\0", '', $callibri_code), 0, 30000);

        $value['banner']['show_revoke_button'] = !empty($value['banner']['show_revoke_button']) ? 1 : 0;
        $value['security']['strict_blocking'] = 1;
        $value['security']['foreign_auth_block'] = !empty($value['security']['foreign_auth_block']) ? 1 : 0;
        $value['security']['blocked_domains'] = sanitize_textarea_field($value['security']['blocked_domains'] ?? '');

        $allowed_analytics_services = ['yandex_metrika', 'mailru_counters', 'callibri'];
        $allowed_advertising_services = ['vk_ads', 'yandex_ads'];
        $value['sections']['analytics_services'] = array_fill_keys(
            array_intersect($allowed_analytics_services, array_keys((array) ($value['sections']['analytics_services'] ?? []))),
            1
        );
        $value['sections']['advertising_services'] = array_fill_keys(
            array_intersect($allowed_advertising_services, array_keys((array) ($value['sections']['advertising_services'] ?? []))),
            1
        );
        $value['sections']['functional_retention_days'] = max(
            1,
            min(3650, absint($value['sections']['functional_retention_days'] ?? 365))
        );

        foreach (['functional', 'analytics', 'performance', 'advertising'] as $section) {
            $value['sections'][$section] = !empty($value['sections'][$section]) ? 1 : 0;
            $value['sections'][$section . '_desc'] = sanitize_text_field(
                $value['sections'][$section . '_desc'] ?? ''
            );
            $value['sections'][$section . '_policy_url'] = esc_url_raw(
                $value['sections'][$section . '_policy_url'] ?? ''
            );
        }

        $allowed_goals = [
            'storage',
            'analytics',
            'personalized_content',
            'personalized',
            'retargeting',
            'ad_measure',
            'content_measure',
            'profiling',
            'geolocation',
            'third_party',
            'development',
            'limited_ads',
        ];
        $goals = is_array($value['goals'] ?? null) ? $value['goals'] : [];
        $value['goals'] = [];
        foreach ($allowed_goals as $goal) {
            $value['goals'][$goal] = !empty($goals[$goal]) ? 1 : 0;
        }

        $value['banner']['custom_css'] = sanitize_textarea_field($value['banner']['custom_css'] ?? '');
        $value['custom_categories'] = $this->sanitize_custom_items(
            $value['custom_categories'] ?? [],
            'category'
        );
        $value['custom_goals'] = $this->sanitize_custom_items(
            $value['custom_goals'] ?? [],
            'goal'
        );

        return $value;
    }

    /**
     * Sanitize administrator-defined categories and processing purposes.
     *
     * @param mixed  $items
     * @param string $type
     * @return array
     */
    private function sanitize_custom_items($items, $type) {
        if (!is_array($items)) {
            return [];
        }

        $prefix = $type === 'goal' ? 'custom_goal_' : 'custom_category_';
        $clean = [];
        $used_ids = [];
        $index = 0;

        foreach ($items as $item) {
            if ($index >= 20 || !is_array($item)) {
                break;
            }

            $title = sanitize_text_field($item['title'] ?? '');
            if ($title === '') {
                continue;
            }

            $id = sanitize_key($item['id'] ?? '');
            if ($id === '') {
                $id = $prefix . $index;
            } elseif (strpos($id, $prefix) !== 0) {
                $id = $prefix . $id;
            }

            $base_id = $id;
            $suffix = 2;
            while (in_array($id, $used_ids, true)) {
                $id = $base_id . '_' . $suffix;
                $suffix++;
            }

            $used_ids[] = $id;
            $entry = [
                'id' => $id,
                'title' => $title,
                'description' => sanitize_textarea_field($item['description'] ?? ''),
                'enabled' => !empty($item['enabled']) ? 1 : 0,
            ];

            if ($type === 'category') {
                $entry['policy_url'] = esc_url_raw($item['policy_url'] ?? '');
            }

            $clean[] = $entry;
            $index++;
        }

        return $clean;
    }

    /**
     * Добавляет новые настройки в существующие установки без перезаписи выбора
     * администратора.
     */
    public function migrate_settings() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $settings = get_option('cookierus_settings');
        if (!is_array($settings)) {
            $settings = $this->get_default_settings();
        }

        $clean_settings = $this->sanitize_settings(
            array_replace_recursive($this->get_default_settings(), $settings)
        );
        if ($clean_settings !== $settings) {
            update_option('cookierus_settings', $clean_settings);
        }
    }

    /**
     * Backwards-compatible alias for integrations that called the old method.
     */
    public function remove_legacy_tracker_settings() {
        $this->migrate_settings();
    }

    private function get_default_settings() {
        return [
            'banner' => [
                'enabled'              => true,
                'title'                => 'Мы уважаем вашу конфиденциальность',
                'text'                 => 'Мы используем файлы cookie для обеспечения работы сайта и аналитики. Ваши данные обрабатываются в соответствии с требованиями 152-ФЗ.',
                'link_text'            => 'Политика конфиденциальности',
                'link_url'             => '',
                'btn_accept'           => 'Принять все',
                'btn_decline'          => 'Отклонить',
                'btn_decline_url'      => '',
                'btn_settings'         => 'Настроить',
                'bg_color'             => '#ffffff',
                'text_color'           => '#333333',
                'btn_bg'               => '#0760D2',
                'btn_text'             => '#ffffff',
                'position'             => 'bottom',
                'radius'               => 8,
                'show_icon'            => false,
                'icon_size'            => 'medium',
                'extra_btn_enabled'    => false,
                'extra_btn_text'       => 'Подробнее',
                'extra_btn_url'        => '',
                'extra_btn_bg'         => '#6c757d',
                'extra_btn_text_color' => '#ffffff',
                'btn_radius'           => 6,
                'btn_font_size'        => 14,
                'link_color'           => '#0760D2',
                'font_family'          => 'inherit',
                'banner_shadow'        => 'medium',
                'btn_hover'            => 'lift',
                'repeat_show'          => 'never',
                'allow_minimize'       => false,
                'show_revoke_button'   => true,
                'animation'            => 'slide',
            ],
            'trackers' => [
                'ym_id'         => '',
                'vk_id'         => '',
                'callibri_code' => '',
            ],
            'custom_categories' => [],
            'custom_goals' => [],
            'goals' => [
                'storage'      => 1,
                'personalized' => 1,
                'retargeting'  => 1,
                'profiling'    => 1,
                'third_party'  => 1,
                'ad_measure'   => 1,
                'content_measure' => 0,
                'analytics'    => 1,
                'development'  => 0,
                'limited_ads'  => 0,
            ],
            'sections' => [
                'functional'   => 1,
                'analytics'    => 1,
                'performance'  => 0,
                'advertising'  => 1,
                'functional_retention_days' => 365,
                'functional_policy_url' => '',
                'analytics_policy_url' => '',
                'performance_policy_url' => '',
                'advertising_policy_url' => '',
                'analytics_services' => [
                    'yandex_metrika' => 1,
                    'mailru_counters' => 0,
                    'callibri' => 0,
                ],
                'advertising_services' => [
                    'vk_ads' => 0,
                    'yandex_ads' => 1,
                ],
            ],
            'security' => [
                // This is deliberately not exposed as an off switch: the
                // consent firewall must remain active for the guarantee.
                'strict_blocking' => 1,
                'foreign_auth_block' => 0,
                'blocked_domains' => '',
            ],
            'policy' => [
                'email'       => get_option('admin_email'),
                'phone'       => '',
                'hosting_url' => '',
                'metrika_id'  => '',
            ],
        ];
    }

    public function render_admin_page() {
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
        include plugin_dir_path(__FILE__) . 'templates/admin-page.php';
    }

    public function enqueue_assets() {
        wp_enqueue_style('cookierus-banner', plugin_dir_url(__FILE__) . 'assets/css/banner.css');
        
        $settings = get_option('cookierus_settings');
        wp_localize_script('jquery', 'CookieRusData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'settings' => $settings['banner'] ?? [],
            'plugin_url' => plugin_dir_url(__FILE__),
        ]);
    }

    public function render_banner() {
        $settings = get_option('cookierus_settings');
        if (empty($settings['banner']['enabled'])) return;

        // PHP check: skip only if cookie present AND repeat_show is not 'always'
        $repeat_show = $settings['banner']['repeat_show'] ?? 'never';
        if ( $repeat_show !== 'always' && isset($_COOKIE['cookierus_consent'])) return;
        
        include plugin_dir_path(__FILE__) . 'templates/banner-template.php';
    }
}

CookieRus::get_instance();
