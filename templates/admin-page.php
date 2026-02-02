<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap cookierus-admin-wrap">
    <h1>CookieRus — Настройка согласия с файлами cookie</h1>
    
    <nav class="cookierus-nav-tabs">
        <a href="?page=cookierus&tab=dashboard" class="cookierus-nav-tab <?php echo $active_tab == 'dashboard' ? 'active' : ''; ?>">Панель управления</a>
        <a href="?page=cookierus&tab=banner" class="cookierus-nav-tab <?php echo $active_tab == 'banner' ? 'active' : ''; ?>">Настройки баннера</a>
        <a href="?page=cookierus&tab=logs" class="cookierus-nav-tab <?php echo $active_tab == 'logs' ? 'active' : ''; ?>">Менеджер логов</a>
        <a href="?page=cookierus&tab=policy" class="cookierus-nav-tab <?php echo $active_tab == 'policy' ? 'active' : ''; ?>">Генератор политики</a>
        <a href="?page=cookierus&tab=info" class="cookierus-nav-tab <?php echo $active_tab == 'info' ? 'active' : ''; ?>">Информация</a>
    </nav>

    <div class="cookierus-tab-content">
        <?php
        switch ($active_tab) {
            case 'dashboard':
                include plugin_dir_path(__FILE__) . 'dashboard.php';
                break;
            case 'banner':
                include plugin_dir_path(__FILE__) . 'banner-settings.php';
                break;
            case 'logs':
                include plugin_dir_path(__FILE__) . 'logs-manager.php';
                break;
            case 'policy':
                include plugin_dir_path(__FILE__) . 'policy-generator.php';
                break;
            case 'info':
                include plugin_dir_path(__FILE__) . 'info-page.php';
                break;
        }
        ?>
    </div>
</div>
