<?php
/**
 * Plugin Name: CookieRus
 * Plugin URI: https://github.com/RuCoder-sudo/
 * Description: Простой способ убедиться, что ваш сайт соответствует Закону России о файлах cookie.
 * Version: 3.1
 * Author: Сергей Солошенко (RuCoder)
 * Author URI: https://рукодер.рф
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cookierus
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.5
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
        add_action('wp_ajax_cookierus_log_consent', [$this, 'ajax_log_consent']);
        add_action('wp_ajax_nopriv_cookierus_log_consent', [$this, 'ajax_log_consent']);
        add_action('admin_init', [$this, 'handle_csv_export']);
        register_activation_hook(__FILE__, [$this, 'activate']);
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
                'enabled' => true,
                'title' => 'Мы уважаем вашу конфиденциальность',
                'text' => 'Мы используем файлы cookie, чтобы обеспечить вам наилучший опыт на нашем сайте.',
                'link_text' => 'Политика в отношении файлов cookie',
                'link_url' => '',
                'btn_accept' => 'Принять все',
                'btn_decline' => 'Отклонить',
                'btn_settings' => 'Настроить',
                'bg_color' => '#ffffff',
                'text_color' => '#333333',
                'btn_bg' => '#2271b1',
                'btn_text' => '#ffffff',
                'position' => 'bottom',
                'radius' => 8,
            ],
            'policy' => [
                'email' => get_option('admin_email'),
                'phone' => '',
                'hosting_url' => '',
                'metrika_id' => '',
            ]
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
            'settings' => $settings['banner'] ?? []
        ]);
    }

    public function render_banner() {
        $settings = get_option('cookierus_settings');
        if (empty($settings['banner']['enabled'])) return;
        include plugin_dir_path(__FILE__) . 'templates/banner-template.php';
    }
}

CookieRus::get_instance();
