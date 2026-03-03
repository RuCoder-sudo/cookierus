<?php if (!defined('ABSPATH')) exit; 
global $wpdb;
$table_name = $wpdb->prefix . 'cookierus_logs';
$logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 100");
?>
<h3>Менеджер логов согласия</h3>

<?php if (isset($_GET['cleared']) && $_GET['cleared'] == '1'): ?>
    <div class="notice notice-success is-dismissible">
        <p>Все логи успешно очищены!</p>
    </div>
<?php endif; ?>

<p>Здесь отображаются последние 100 записей о действиях пользователей с файлами cookie.</p>

<div style="background: #fff; border: 1px solid #ccd0d4; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
    <p style="margin: 0; font-size: 14px;">
        <strong>Место хранения логов:</strong> <code style="background: #f0f0f1; padding: 3px 6px; border-radius: 3px;">База данных WordPress (таблица: <?php echo esc_html($table_name); ?>)</code>
    </p>
    <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">
        Все данные о согласии пользователей записываются напрямую в базу данных вашего сайта для обеспечения юридической значимости и безопасности.
    </p>
</div>

<div style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
    <a href="<?php echo admin_url('admin.php?page=cookierus&action=cookierus_export_csv'); ?>" class="button button-primary">Выгрузить логи (CSV)</a>
    
    <?php $clear_url = wp_nonce_url(admin_url('admin.php?page=cookierus&action=cookierus_clear_logs'), 'cookierus_clear_logs_nonce'); ?>
    <a href="<?php echo esc_url($clear_url); ?>" class="button button-secondary" style="color: #b32d2e; border-color: #b32d2e;" onclick="return confirm('Вы уверены, что хотите безвозвратно удалить все логи? Это действие нельзя отменить.');">Очистить все логи</a>
    
    <span class="description" style="margin-left: 10px;">Скачать полную историю согласий в формате CSV или полностью очистить базу логов.</span>
</div>

<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th>Дата и время</th>
            <th>IP-адрес</th>
            <th>Страна</th>
            <th>UID</th>
            <th>Статус</th>
            <th>Категории</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($logs): foreach ($logs as $log): ?>
        <tr>
            <td><?php echo esc_html($log->created_at); ?></td>
            <td><?php echo esc_html($log->ip); ?></td>
            <td><?php echo esc_html($log->country); ?></td>
            <td><code><?php echo esc_html($log->uid); ?></code></td>
            <td>
                <span style="padding: 2px 8px; border-radius: 4px; background: <?php echo $log->status === 'accepted' ? '#dcfce7' : '#fee2e2'; ?>; color: <?php echo $log->status === 'accepted' ? '#166534' : '#991b1b'; ?>;">
                    <?php echo $log->status === 'accepted' ? 'Принял' : 'Отклонил'; ?>
                </span>
            </td>
            <td><?php echo esc_html($log->categories); ?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="6">Логов пока нет.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
