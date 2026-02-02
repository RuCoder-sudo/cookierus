<?php if (!defined('ABSPATH')) exit; 
$settings = get_option('cookierus_settings');
$banner = $settings['banner'] ?? [];
$sections = $settings['sections'] ?? [];
$anim_class = 'cookierus-animate-' . ($banner['animation'] ?? 'fade');
?>
<style>
    <?php echo $banner['custom_css'] ?? ''; ?>
    #cookierus-banner .cookierus-title { font-size: <?php echo esc_attr($banner['title_size'] ?? 18); ?>px !important; }
    #cookierus-banner .cookierus-text { font-size: <?php echo esc_attr($banner['text_size'] ?? 14); ?>px !important; }
    
    .cookierus-btn-accept { 
        background-color: <?php echo esc_attr($banner['btn_bg'] ?? '#0760D2'); ?> !important; 
        color: <?php echo esc_attr($banner['btn_text'] ?? '#ffffff'); ?> !important; 
        <?php echo !empty($banner['btn_border']) ? 'border: 2px solid '.esc_attr($banner['btn_border_color']).' !important;' : ''; ?>
    }
    .cookierus-btn-decline { 
        background-color: <?php echo esc_attr($banner['btn_decline_bg'] ?? '#f0f0f1'); ?> !important; 
        color: <?php echo esc_attr($banner['btn_decline_text'] ?? '#333333'); ?> !important; 
        <?php echo !empty($banner['btn_decline_border']) ? 'border: 2px solid '.esc_attr($banner['btn_decline_border_color']).' !important;' : ''; ?>
    }
    .cookierus-btn-settings { 
        background-color: <?php echo esc_attr($banner['btn_settings_bg'] ?? '#ffffff'); ?> !important; 
        color: <?php echo esc_attr($banner['btn_settings_text'] ?? '#333333'); ?> !important; 
        <?php echo !empty($banner['btn_settings_border']) ? 'border: 2px solid '.esc_attr($banner['btn_settings_border_color']).' !important;' : 'border: 1px solid rgba(0,0,0,0.1) !important;'; ?>
    }
</style>

<div id="cookierus-banner" class="cookierus-banner pos-<?php echo esc_attr($banner['position'] ?? 'bottom'); ?> <?php echo $anim_class; ?>" style="
    background-color: <?php echo esc_attr($banner['bg_color'] ?? '#ffffff'); ?>;
    color: <?php echo esc_attr($banner['text_color'] ?? '#333333'); ?>;
    border-radius: <?php echo esc_attr($banner['radius'] ?? 8); ?>px;
    display: flex !important;
">
    <div class="cookierus-content-wrapper">
        <?php if (!empty($banner['title'])): ?>
            <h4 class="cookierus-title"><?php echo esc_html($banner['title']); ?></h4>
        <?php endif; ?>
        <div class="cookierus-text">
            <?php echo wp_kses_post($banner['text'] ?? ''); ?>
            <?php if (!empty($banner['link_url'])): ?>
                <a href="<?php echo esc_url($banner['link_url']); ?>" class="cookierus-policy-link"><?php echo esc_html($banner['link_text'] ?? 'Политика в отношении файлов cookie'); ?></a>
            <?php endif; ?>
        </div>
    </div>
    <div class="cookierus-buttons">
        <button type="button" class="cookierus-btn cookierus-btn-accept" id="cookierus-accept"><?php echo esc_html($banner['btn_accept'] ?? 'Принять все'); ?></button>
        <button type="button" class="cookierus-btn cookierus-btn-decline" id="cookierus-decline"><?php echo esc_html($banner['btn_decline'] ?? 'Отклонить'); ?></button>
        <?php if (!empty($banner['btn_settings'])): ?>
        <button type="button" class="cookierus-btn cookierus-btn-settings" id="cookierus-open-settings"><?php echo esc_html($banner['btn_settings'] ?? 'Настроить'); ?></button>
        <?php endif; ?>
    </div>
</div>

<div id="cookierus-modal" class="cookierus-modal">
    <div class="cookierus-modal-content">
        <h3>Настройки согласия</h3>
        <p style="font-size: 13px; color: #666; margin-bottom: 20px;">Мы используем файлы cookie, чтобы помочь вам эффективно ориентироваться и выполнять определенные функции. Вы найдете подробную информацию обо всех файлах cookie в каждой категории согласия ниже.<br><br>Файлы cookie, отнесенные к категории «Необходимые», хранятся в вашем браузере, поскольку они необходимы для включения основных функций сайта.<br><br>Мы также используем сторонние файлы cookie, которые помогают нам анализировать, как вы используете этот веб-сайт, сохранять ваши предпочтения и предоставлять контент и рекламу, которые имеют отношение к вам. Эти файлы cookie будут сохраняться в вашем браузере только с вашего предварительного согласия.<br><br>Вы можете включить или отключить некоторые или все эти файлы cookie, но отключение некоторых из них может повлиять на ваш опыт просмотра.</p>
        
        <div class="cookierus-category">
            <div class="cookierus-category-header">
                <span>Необходимые</span>
                <span style="color: #166534; font-size: 12px; background: #dcfce7; padding: 2px 8px; border-radius: 4px;">Всегда активны</span>
            </div>
            <p style="font-size: 12px; margin-top: 8px; color: #666;">Необходимые файлы cookie являются основными функциями веб-сайта, и веб-сайт не будет работать по назначению. Эти куки не хранят какие-либо личные данные.</p>
        </div>

        <?php if (!empty($sections['functional'])): ?>
        <div class="cookierus-category">
            <div class="cookierus-category-header">
                <span>Функциональные</span>
                <input type="checkbox" id="cat-functional" checked>
            </div>
            <p style="font-size: 12px; margin-top: 5px; color: #666;">Платформы социальных сетей, отзывы коллекционеров и другие сторонние функции на веб-сайте совместно используют некоторые функции для поддержки функциональных файлов cookie. <?php echo esc_html($sections['functional_desc'] ?? ''); ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($sections['analytics'])): ?>
        <div class="cookierus-category">
            <div class="cookierus-category-header">
                <span>Аналитика</span>
                <input type="checkbox" id="cat-analytics" checked>
            </div>
            <p style="font-size: 12px; margin-top: 5px; color: #666;">Аналитические куки используются, чтобы понять, как посетители взаимодействуют с сайтом. Эти файлы cookie предоставляют информацию о таких показателях, как количество посетителей, показатель отказов, источник трафика и т. Д. <?php echo esc_html($sections['analytics_desc'] ?? ''); ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($sections['performance'])): ?>
        <div class="cookierus-category">
            <div class="cookierus-category-header">
                <span>Производительность</span>
                <input type="checkbox" id="cat-performance" checked>
            </div>
            <p style="font-size: 12px; margin-top: 5px; color: #666;">Куки-файлы производительности используются для понимания и анализа ключевых показателей эффективности веб-сайта, которые помогают вам повысить качество обслуживания пользователей. <?php echo esc_html($sections['performance_desc'] ?? ''); ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($sections['advertising'])): ?>
        <div class="cookierus-category">
            <div class="cookierus-category-header">
                <span>Реклама</span>
                <input type="checkbox" id="cat-advertising">
            </div>
            <p style="font-size: 12px; margin-top: 5px; color: #666;">Рекламные файлы cookie используются для предоставления персонализированной рекламы на основе посещаемых ими страниц и анализа эффективности рекламной кампании. <?php echo esc_html($sections['advertising_desc'] ?? ''); ?></p>
        </div>
        <?php endif; ?>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <button type="button" class="cookierus-btn cookierus-btn-accept" id="cookierus-save-settings" style="flex: 1;">Сохранить выбор</button>
            <button type="button" class="cookierus-btn cookierus-btn-decline" id="cookierus-close-modal">Закрыть</button>
        </div>
    </div>
</div>

<script>
(function() {
    function init() {
        const banner = document.getElementById('cookierus-banner');
        const modal = document.getElementById('cookierus-modal');
        const acceptBtn = document.getElementById('cookierus-accept');
        const declineBtn = document.getElementById('cookierus-decline');
        const openSettingsBtn = document.getElementById('cookierus-open-settings');
        const saveSettingsBtn = document.getElementById('cookierus-save-settings');
        const closeModalBtn = document.getElementById('cookierus-close-modal');

        if (!banner) return;

        if (localStorage.getItem('cookierus_consent')) {
            banner.style.setProperty('display', 'none', 'important');
            return;
        }

        function logConsent(status, categories = 'all') {
            const uid = localStorage.getItem('cookierus_uid') || 'uid_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('cookierus_uid', uid);
            localStorage.setItem('cookierus_consent', status);

            const formData = new FormData();
            formData.append('action', 'cookierus_log_consent');
            formData.append('status', status);
            formData.append('categories', categories);
            formData.append('uid', uid);

            const ajaxUrl = (typeof CookieRusData !== 'undefined' && CookieRusData.ajax_url) 
                ? CookieRusData.ajax_url 
                : '/wp-admin/admin-ajax.php';

            fetch(ajaxUrl, { 
                method: 'POST', 
                body: formData 
            }).then(response => response.json())
              .then(data => {
                  console.log('CookieRus log success:', data);
                  banner.style.setProperty('display', 'none', 'important');
                  if (modal) modal.style.setProperty('display', 'none', 'important');
              })
              .catch(err => {
                  console.error('CookieRus log error:', err);
                  // Скрываем баннер даже при ошибке логирования, чтобы не раздражать юзера
                  banner.style.setProperty('display', 'none', 'important');
                  if (modal) modal.style.setProperty('display', 'none', 'important');
              });
        }

        if (acceptBtn) acceptBtn.addEventListener('click', (e) => { e.preventDefault(); logConsent('accepted'); });
        if (declineBtn) declineBtn.addEventListener('click', (e) => { e.preventDefault(); logConsent('declined', 'none'); });
        if (openSettingsBtn) openSettingsBtn.addEventListener('click', (e) => { e.preventDefault(); if (modal) modal.style.setProperty('display', 'flex', 'important'); });
        if (closeModalBtn) closeModalBtn.addEventListener('click', (e) => { e.preventDefault(); if (modal) modal.style.setProperty('display', 'none', 'important'); });
        
        if (saveSettingsBtn) {
            saveSettingsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                let cats = ['necessary'];
                if (document.getElementById('cat-functional')?.checked) cats.push('functional');
                if (document.getElementById('cat-analytics')?.checked) cats.push('analytics');
                if (document.getElementById('cat-performance')?.checked) cats.push('performance');
                if (document.getElementById('cat-advertising')?.checked) cats.push('advertising');
                logConsent('accepted', cats.join(','));
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
