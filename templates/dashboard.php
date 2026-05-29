<?php if (!defined('ABSPATH')) exit;

global $wpdb;
$table_name = $wpdb->prefix . 'cookierus_logs';
$settings = get_option('cookierus_settings');
$banner = $settings['banner'] ?? [];

$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;

$total_users = 0;
$accepted_users = 0;
$declined_users = 0;
$custom_users = 0;
$today_users = 0;
$week_users = 0;

if ($table_exists) {
    $total_users = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $accepted_users = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'accepted'");
    $declined_users = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'declined'");
    $custom_users = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'accepted' AND categories != 'all' AND categories != ''");
    $today_users = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE DATE(created_at) = CURDATE()");
    $week_users = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
}

$banner_enabled = !empty($banner['enabled']);
$acceptance_rate = $total_users > 0 ? round(($accepted_users / $total_users) * 100, 1) : 0;
$decline_rate = $total_users > 0 ? round(($declined_users / $total_users) * 100, 1) : 0;
?>

<style>
.cookierus-dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.cookierus-widget {
    background: #fff;
    border-radius: 12px;
    padding: 20px 25px;
    box-shadow: 0 4px 15px rgba(7, 96, 210, 0.08);
    border: 1px solid #e1e9f5;
    transition: transform 0.2s, box-shadow 0.2s;
}
.cookierus-widget:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(7, 96, 210, 0.12);
}
.cookierus-widget-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}
.cookierus-widget-title {
    font-size: 13px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin: 0;
}
.cookierus-widget-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.cookierus-widget-value {
    font-size: 32px;
    font-weight: 700;
    color: #1d2327;
    margin: 0 0 5px 0;
    line-height: 1.2;
}
.cookierus-widget-desc {
    font-size: 13px;
    color: #888;
    margin: 0;
}
.cookierus-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}
.cookierus-status-active {
    background: #dcfce7;
    color: #166534;
}
.cookierus-status-inactive {
    background: #fee2e2;
    color: #991b1b;
}
.cookierus-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    animation: pulse 2s infinite;
}
.cookierus-status-active .cookierus-status-dot {
    background: #22c55e;
}
.cookierus-status-inactive .cookierus-status-dot {
    background: #ef4444;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
.cookierus-widget-large {
    grid-column: span 2;
}
@media (max-width: 768px) {
    .cookierus-widget-large {
        grid-column: span 1;
    }
}
.cookierus-stats-row {
    display: flex;
    gap: 20px;
    margin-top: 15px;
}
.cookierus-stat-item {
    flex: 1;
    text-align: center;
    padding: 15px;
    background: #f8faff;
    border-radius: 8px;
}
.cookierus-stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #0760D2;
}
.cookierus-stat-label {
    font-size: 11px;
    color: #666;
    text-transform: uppercase;
    margin-top: 5px;
}
.cookierus-progress-bar {
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    margin-top: 10px;
}
.cookierus-progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.5s ease;
}
.cookierus-progress-accept {
    background: linear-gradient(90deg, #22c55e, #16a34a);
}
.cookierus-progress-decline {
    background: linear-gradient(90deg, #ef4444, #dc2626);
}
.cookierus-quick-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    flex-wrap: wrap;
}
.cookierus-quick-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #f0f4f9;
    border: 1px solid #e1e9f5;
    border-radius: 6px;
    color: #0760D2;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
}
.cookierus-quick-btn:hover {
    background: #0760D2;
    color: #fff;
    border-color: #0760D2;
}
.cookierus-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-top: 15px;
}
.cookierus-info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    background: #f8faff;
    border-radius: 6px;
    font-size: 13px;
}
.cookierus-info-item .dashicons {
    color: #0760D2;
}
.cookierus-info-item.success .dashicons {
    color: #22c55e;
}
.cookierus-info-item.warning .dashicons {
    color: #f59e0b;
}
.cookierus-info-item.error .dashicons {
    color: #ef4444;
}
</style>

<div class="cookierus-admin-header" style="margin-bottom: 20px;">
    <h3>Панель управления CookieRus</h3>
    <p class="description">Обзор состояния системы согласия с файлами cookie и статистика пользователей.</p>
</div>

<div class="cookierus-dashboard">
    <div class="cookierus-widget">
        <div class="cookierus-widget-header">
            <h4 class="cookierus-widget-title">Статус баннера</h4>
            <div class="cookierus-widget-icon" style="background: <?php echo $banner_enabled ? '#dcfce7' : '#fee2e2'; ?>;">
                <span class="dashicons dashicons-<?php echo $banner_enabled ? 'yes-alt' : 'dismiss'; ?>" style="color: <?php echo $banner_enabled ? '#22c55e' : '#ef4444'; ?>;"></span>
            </div>
        </div>
        <div class="cookierus-status-badge <?php echo $banner_enabled ? 'cookierus-status-active' : 'cookierus-status-inactive'; ?>">
            <span class="cookierus-status-dot"></span>
            <?php echo $banner_enabled ? 'Активен' : 'Отключён'; ?>
        </div>
        <p class="cookierus-widget-desc" style="margin-top: 10px;">
            <?php echo $banner_enabled ? 'Баннер отображается на сайте' : 'Баннер скрыт от посетителей'; ?>
        </p>
    </div>

    <div class="cookierus-widget">
        <div class="cookierus-widget-header">
            <h4 class="cookierus-widget-title">Сбор данных</h4>
            <div class="cookierus-widget-icon" style="background: <?php echo $table_exists ? '#dcfce7' : '#fee2e2'; ?>;">
                <span class="dashicons dashicons-<?php echo $table_exists ? 'database' : 'warning'; ?>" style="color: <?php echo $table_exists ? '#22c55e' : '#ef4444'; ?>;"></span>
            </div>
        </div>
        <div class="cookierus-status-badge <?php echo $table_exists ? 'cookierus-status-active' : 'cookierus-status-inactive'; ?>">
            <span class="cookierus-status-dot"></span>
            <?php echo $table_exists ? 'Работает' : 'Ошибка'; ?>
        </div>
        <p class="cookierus-widget-desc" style="margin-top: 10px;">
            <?php echo $table_exists ? 'База данных логов настроена' : 'Таблица логов не создана'; ?>
        </p>
    </div>

    <div class="cookierus-widget">
        <div class="cookierus-widget-header">
            <h4 class="cookierus-widget-title">Логирование</h4>
            <div class="cookierus-widget-icon" style="background: #dcfce7;">
                <span class="dashicons dashicons-list-view" style="color: #22c55e;"></span>
            </div>
        </div>
        <div class="cookierus-status-badge cookierus-status-active">
            <span class="cookierus-status-dot"></span>
            Работает
        </div>
        <p class="cookierus-widget-desc" style="margin-top: 10px;">
            Все согласия записываются в лог
        </p>
    </div>

    <div class="cookierus-widget">
        <div class="cookierus-widget-header">
            <h4 class="cookierus-widget-title">Всего пользователей</h4>
            <div class="cookierus-widget-icon" style="background: #e0e7ff;">
                <span class="dashicons dashicons-groups" style="color: #4f46e5;"></span>
            </div>
        </div>
        <p class="cookierus-widget-value"><?php echo number_format($total_users, 0, ',', ' '); ?></p>
        <p class="cookierus-widget-desc">сделали выбор по куки</p>
    </div>

    <div class="cookierus-widget">
        <div class="cookierus-widget-header">
            <h4 class="cookierus-widget-title">Приняли все куки</h4>
            <div class="cookierus-widget-icon" style="background: #dcfce7;">
                <span class="dashicons dashicons-yes" style="color: #22c55e;"></span>
            </div>
        </div>
        <p class="cookierus-widget-value" style="color: #16a34a;"><?php echo number_format($accepted_users, 0, ',', ' '); ?></p>
        <div class="cookierus-progress-bar">
            <div class="cookierus-progress-fill cookierus-progress-accept" style="width: <?php echo $acceptance_rate; ?>%;"></div>
        </div>
        <p class="cookierus-widget-desc" style="margin-top: 8px;"><?php echo $acceptance_rate; ?>% от всех пользователей</p>
    </div>

    <div class="cookierus-widget">
        <div class="cookierus-widget-header">
            <h4 class="cookierus-widget-title">Отклонили куки</h4>
            <div class="cookierus-widget-icon" style="background: #fee2e2;">
                <span class="dashicons dashicons-no" style="color: #ef4444;"></span>
            </div>
        </div>
        <p class="cookierus-widget-value" style="color: #dc2626;"><?php echo number_format($declined_users, 0, ',', ' '); ?></p>
        <div class="cookierus-progress-bar">
            <div class="cookierus-progress-fill cookierus-progress-decline" style="width: <?php echo $decline_rate; ?>%;"></div>
        </div>
        <p class="cookierus-widget-desc" style="margin-top: 8px;"><?php echo $decline_rate; ?>% от всех пользователей</p>
    </div>

    <div class="cookierus-widget cookierus-widget-large">
        <div class="cookierus-widget-header">
            <h4 class="cookierus-widget-title">Статистика за период</h4>
            <div class="cookierus-widget-icon" style="background: #fef3c7;">
                <span class="dashicons dashicons-chart-bar" style="color: #f59e0b;"></span>
            </div>
        </div>
        <div class="cookierus-stats-row">
            <div class="cookierus-stat-item">
                <div class="cookierus-stat-value"><?php echo number_format($today_users, 0, ',', ' '); ?></div>
                <div class="cookierus-stat-label">Сегодня</div>
            </div>
            <div class="cookierus-stat-item">
                <div class="cookierus-stat-value"><?php echo number_format($week_users, 0, ',', ' '); ?></div>
                <div class="cookierus-stat-label">За неделю</div>
            </div>
            <div class="cookierus-stat-item">
                <div class="cookierus-stat-value"><?php echo number_format($custom_users, 0, ',', ' '); ?></div>
                <div class="cookierus-stat-label">Настроили выбор</div>
            </div>
            <div class="cookierus-stat-item">
                <div class="cookierus-stat-value"><?php echo $acceptance_rate; ?>%</div>
                <div class="cookierus-stat-label">Принятие</div>
            </div>
        </div>
    </div>

    <div class="cookierus-widget cookierus-widget-large">
        <div class="cookierus-widget-header">
            <h4 class="cookierus-widget-title">Состояние системы</h4>
            <div class="cookierus-widget-icon" style="background: #e0e7ff;">
                <span class="dashicons dashicons-admin-tools" style="color: #4f46e5;"></span>
            </div>
        </div>
        <div class="cookierus-info-grid">
            <div class="cookierus-info-item <?php echo $banner_enabled ? 'success' : 'warning'; ?>">
                <span class="dashicons dashicons-<?php echo $banner_enabled ? 'yes' : 'minus'; ?>"></span>
                <span>Баннер <?php echo $banner_enabled ? 'включён' : 'выключен'; ?></span>
            </div>
            <div class="cookierus-info-item <?php echo $table_exists ? 'success' : 'error'; ?>">
                <span class="dashicons dashicons-<?php echo $table_exists ? 'yes' : 'no'; ?>"></span>
                <span>База данных <?php echo $table_exists ? 'готова' : 'ошибка'; ?></span>
            </div>
            <div class="cookierus-info-item success">
                <span class="dashicons dashicons-yes"></span>
                <span>AJAX логирование</span>
            </div>
            <div class="cookierus-info-item success">
                <span class="dashicons dashicons-yes"></span>
                <span>Шаблон баннера</span>
            </div>
        </div>
        <div class="cookierus-quick-actions">
            <a href="?page=cookierus&tab=banner" class="cookierus-quick-btn">
                <span class="dashicons dashicons-art"></span> Настроить баннер
            </a>
            <a href="?page=cookierus&tab=logs" class="cookierus-quick-btn">
                <span class="dashicons dashicons-list-view"></span> Просмотр логов
            </a>
            <a href="?page=cookierus&tab=policy" class="cookierus-quick-btn">
                <span class="dashicons dashicons-media-document"></span> Генератор политики
            </a>
        </div>
    </div>
</div>
