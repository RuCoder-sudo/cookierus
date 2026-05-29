<?php
/**
 * Plugin Name: CookieRus
 * Plugin URI: https://github.com/RuCoder-sudo/cookierus
 * Description: Простой способ убедиться, что ваш сайт соответствует Закону России о файлах cookie.
 * Version: 1.0.6
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

define('COOKIERUS_VERSION', '1.0.6');
define('COOKIERUS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('COOKIERUS_PLUGIN_DIR', plugin_dir_path(__FILE__));

if (file_exists(plugin_dir_path(__FILE__) . 'includes/class-cookierus-updater.php')) {
    require_once plugin_dir_path(__FILE__) . 'includes/class-cookierus-updater.php';
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
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_footer', [$this, 'render_banner']);
        add_action('template_redirect', [$this, 'start_output_buffer']);
        add_action('wp_ajax_cookierus_log_consent', [$this, 'ajax_log_consent']);
        add_action('wp_ajax_nopriv_cookierus_log_consent', [$this, 'ajax_log_consent']);
        add_action('admin_init', [$this, 'handle_csv_export']);
        add_action('admin_init', [$this, 'handle_clear_logs']);
        add_action('before_woocommerce_init', [$this, 'declare_woo_compatibility']);
        register_activation_hook(__FILE__, [$this, 'activate']);

        // Инициализация автообновления через GitHub
        if (class_exists('CookieRus_Updater')) {
            new CookieRus_Updater(__FILE__, COOKIERUS_VERSION);
        }
    }

    public function start_output_buffer() {
        if (is_admin()) return;

        $settings = get_option('cookierus_settings');
        if (empty($settings['banner']['enabled'])) return;

        // When repeat_show=always, show banner on every page load (session cookie is cleared between visits)
        $repeat_show = $settings['banner']['repeat_show'] ?? 'never';
        if ( $repeat_show !== 'always' && isset($_COOKIE['cookierus_consent'])) return;

        // Capture banner HTML now (safe to use ob_start here, outside the callback)
        ob_start();
        include plugin_dir_path(__FILE__) . 'templates/banner-template.php';
        $this->banner_html_cache = ob_get_clean();

        ob_start([$this, 'inject_banner_before_body_close']);
    }

    private $banner_html_cache = '';

    public function inject_banner_before_body_close($html) {
        if (empty($this->banner_html_cache)) return $html;

        // Avoid duplicate if wp_footer already injected it
        if (strpos($html, 'id="cookierus-banner"') !== false) return $html;

        $pos = strrpos($html, '</body>');
        if ($pos !== false) {
            return substr($html, 0, $pos) . $this->banner_html_cache . substr($html, $pos);
        }

        return $html . $this->banner_html_cache;
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

    public function ajax_log_consent() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cookierus_logs';
        
        $status = sanitize_text_field($_POST['status'] ?? '');
        $categories = sanitize_text_field($_POST['categories'] ?? '');
        $uid = sanitize_text_field($_POST['uid'] ?? '');
        
        if (empty($status) || empty($uid)) {
            wp_send_json_error(['message' => 'Missing data', 'post' => $_POST]);
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        $result = $wpdb->insert($table_name, [
            'uid' => $uid,
            'ip' => $ip,
            'status' => $status,
            'categories' => $categories,
            'user_id' => get_current_user_id(),
            'country' => 'Russia',
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
        register_setting('cookierus_settings_group', 'cookierus_settings');
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
                'animation'            => 'slide',
            ],
            'trackers' => [
                'ga4_id'   => '',
                'ym_id'    => '',
                'meta_id'  => '',
                'vk_id'    => '',
                'gtm_id'   => '',
            ],
            'goals' => [
                'storage'      => 1,
                'personalized' => 1,
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
