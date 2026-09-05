<?php
/**
 * CookieRus Banner Template — v1.1.3
 * Рендерится на фронтенде: баннер + модал настроек (3 вкладки) + блокировка трекеров
 */
if (!defined('ABSPATH')) exit;

$settings  = get_option('cookierus_settings', []);
$settings  = is_array($settings) ? $settings : [];
$banner    = $settings['banner']   ?? [];
$sections  = $settings['sections'] ?? [];
$trackers  = $settings['trackers'] ?? [];
$goals_cfg = $settings['goals']    ?? [];
$custom_categories = is_array($settings['custom_categories'] ?? null)
    ? $settings['custom_categories']
    : [];
$custom_goals = is_array($settings['custom_goals'] ?? null)
    ? $settings['custom_goals']
    : [];
$custom_category_ids = [];
foreach ($custom_categories as $custom_category) {
    if (!empty($custom_category['enabled']) && !empty($custom_category['id'])) {
        $custom_category_ids[] = sanitize_key($custom_category['id']);
    }
}
$goals_cfg = array_replace([
    'storage' => 1,
    'analytics' => 1,
    'personalized' => 1,
    'retargeting' => 1,
    'profiling' => 1,
    'third_party' => 1,
], $goals_cfg);
$policy_links = [
    'functional'  => $sections['functional_policy_url'] ?? '',
    'analytics'   => $sections['analytics_policy_url'] ?? '',
    'performance' => $sections['performance_policy_url'] ?? '',
    'advertising' => $sections['advertising_policy_url'] ?? '',
];
$functional_retention_days = max(1, absint($sections['functional_retention_days'] ?? 365));
$analytics_services = $sections['analytics_services'] ?? ['yandex_metrika' => 1];
$advertising_services = $sections['advertising_services'] ?? ['yandex_ads' => 1];
$foreign_auth_block_enabled = class_exists('CookieRus_Compliance')
    && CookieRus_Compliance::is_foreign_auth_block_enabled();

// Animation class
$anim_class = 'cookierus-animate-' . ($banner['animation'] ?? 'slide');

// Icon size
$icon_sizes = ['small' => 32, 'medium' => 48, 'large' => 72];
$icon_px    = $icon_sizes[$banner['icon_size'] ?? 'medium'] ?? 48;

// Shadow
$shadow_map = [
    'none'   => 'none',
    'light'  => '0 4px 12px rgba(0,0,0,0.08)',
    'medium' => '0 10px 30px rgba(0,0,0,0.2)',
    'heavy'  => '0 20px 50px rgba(0,0,0,0.45)',
];
$banner_shadow = $shadow_map[$banner['banner_shadow'] ?? 'medium'] ?? '0 10px 30px rgba(0,0,0,0.2)';

$btn_hover     = $banner['btn_hover']     ?? 'lift';
$btn_layout    = $banner['btn_layout']    ?? 'column';
$repeat_show   = $banner['repeat_show']   ?? 'never';
$allow_minimize = !empty($banner['allow_minimize']);
$decline_url   = $banner['btn_decline_url'] ?? '';

$plugin_url = defined('COOKIERUS_PLUGIN_URL') ? COOKIERUS_PLUGIN_URL : plugin_dir_url(dirname(__FILE__));

// Goals configured to show
$show_goals = [
    'storage'              => !empty($goals_cfg['storage']),
    'analytics'            => !empty($goals_cfg['analytics']),
    'personalized_content' => !empty($goals_cfg['personalized_content']),
    'personalized'         => !empty($goals_cfg['personalized']),
    'retargeting'          => !empty($goals_cfg['retargeting']),
    'ad_measure'           => !empty($goals_cfg['ad_measure']),
    'content_measure'      => !empty($goals_cfg['content_measure']),
    'profiling'            => !empty($goals_cfg['profiling']),
    'geolocation'          => !empty($goals_cfg['geolocation']),
    'third_party'          => !empty($goals_cfg['third_party']),
    'development'          => !empty($goals_cfg['development']),
];
?>
<style>
    /* ── Custom overrides from settings ────────────────────────── */
    <?php echo $banner['custom_css'] ?? ''; ?>
    #cookierus-banner .cookierus-title { font-size: <?php echo esc_attr($banner['title_size'] ?? 18); ?>px !important; }
    #cookierus-banner .cookierus-text  { font-size: <?php echo esc_attr($banner['text_size']  ?? 14); ?>px !important; }
    #cookierus-banner.pos-bottom-left,
    #cookierus-banner.pos-bottom-right,
    #cookierus-banner.pos-top-left,
    #cookierus-banner.pos-top-right { max-width: <?php echo ($btn_layout === 'row') ? '640px' : '460px'; ?> !important; }
    #cookierus-banner {
        font-family: <?php echo esc_attr($banner['font_family'] ?? 'inherit'); ?> !important;
        box-shadow: <?php echo $banner_shadow; ?> !important;
        display: none !important;
        padding: <?php echo intval($banner['pad_top'] ?? 20); ?>px <?php echo intval($banner['pad_right'] ?? 24); ?>px <?php echo intval($banner['pad_bottom'] ?? 20); ?>px <?php echo intval($banner['pad_left'] ?? 24); ?>px !important;
    }
    body.cookierus-show-banner #cookierus-banner { display: flex !important; }
    .cookierus-btn {
        border-radius: <?php echo esc_attr($banner['btn_radius'] ?? 6); ?>px !important;
        font-size:     <?php echo esc_attr($banner['btn_font_size'] ?? 14); ?>px !important;
    }
    .cookierus-btn-accept  { background-color: <?php echo esc_attr($banner['btn_bg']           ?? '#0760D2'); ?> !important; color: <?php echo esc_attr($banner['btn_text']          ?? '#ffffff'); ?> !important; <?php echo !empty($banner['btn_border']) ? 'border:2px solid '.esc_attr($banner['btn_border_color']??'#0760D2').'!important;':'' ?> }
    .cookierus-btn-decline { background-color: <?php echo esc_attr($banner['btn_decline_bg']   ?? '#f0f0f1'); ?> !important; color: <?php echo esc_attr($banner['btn_decline_text']  ?? '#333333'); ?> !important; <?php echo !empty($banner['btn_decline_border']) ? 'border:2px solid '.esc_attr($banner['btn_decline_border_color']??'#c3c4c7').'!important;':'' ?> }
    .cookierus-btn-settings { background-color: <?php echo esc_attr($banner['btn_settings_bg'] ?? '#ffffff'); ?> !important; color: <?php echo esc_attr($banner['btn_settings_text'] ?? '#333333'); ?> !important; border:1px solid rgba(0,0,0,0.12) !important; }
    .cookierus-btn-custom   { background-color: <?php echo esc_attr($banner['extra_btn_bg']    ?? '#6c757d'); ?> !important; color: <?php echo esc_attr($banner['extra_btn_text_color'] ?? '#ffffff'); ?> !important; }
    .cookierus-policy-link  { color: <?php echo !empty($banner['link_color']) ? esc_attr($banner['link_color']) : 'inherit'; ?> !important; }
    <?php if ($btn_hover === 'darken'): ?>
    .cookierus-btn:hover { filter: brightness(0.85) !important; transform: none !important; }
    <?php elseif ($btn_hover === 'scale'): ?>
    .cookierus-btn:hover { transform: scale(1.05) !important; }
    <?php elseif ($btn_hover === 'none'): ?>
    .cookierus-btn:hover { transform: none !important; opacity: 1 !important; }
    <?php else: /* lift */ ?>
    .cookierus-btn:hover { transform: translateY(-2px) !important; box-shadow: 0 4px 12px rgba(0,0,0,0.18) !important; }
    <?php endif; ?>
</style>

<!-- ═══════════════════════════════════════════════════════
     БАННЕР
═══════════════════════════════════════════════════════ -->
<div id="cookierus-banner"
     class="cookierus-banner pos-<?php echo esc_attr($banner['position'] ?? 'bottom'); ?> <?php echo $anim_class; ?><?php echo ($btn_layout === 'row') ? ' cr-btns-row' : ''; ?>"
     style="background-color:<?php echo esc_attr($banner['bg_color'] ?? '#ffffff'); ?>;color:<?php echo esc_attr($banner['text_color'] ?? '#333333'); ?>;border-radius:<?php echo esc_attr($banner['radius'] ?? 8); ?>px;"
     role="dialog" aria-modal="true" aria-label="Настройки cookie">

    <?php if ($allow_minimize): ?>
    <button type="button" class="cookierus-minimize-btn" id="cookierus-minimize" title="Свернуть">
        <span>Свернуть</span>
        <svg width="8" height="2" viewBox="0 0 8 2" fill="currentColor" aria-hidden="true"><rect width="8" height="2" rx="1"/></svg>
    </button>
    <?php endif; ?>

    <div class="cookierus-content-wrapper">
        <?php if (!empty($banner['show_icon'])): ?>
        <div class="cookierus-icon-wrap">
            <img src="<?php echo esc_url($plugin_url . 'assets/cookie-svgrepo-com.png'); ?>"
                 alt="" role="presentation"
                 class="cookierus-icon cookierus-icon-<?php echo esc_attr($banner['icon_size'] ?? 'medium'); ?>"
                 style="width:<?php echo intval($icon_px); ?>px;height:<?php echo intval($icon_px); ?>px;">
        </div>
        <?php endif; ?>
        <div class="cookierus-text-wrap">
            <?php if (!empty($banner['title'])): ?>
                <h4 class="cookierus-title"><?php echo esc_html($banner['title']); ?></h4>
            <?php endif; ?>
            <div class="cookierus-text">
                <?php echo wp_kses_post($banner['text'] ?? ''); ?>
                <?php if (!empty($banner['link_url'])): ?>
                    <a href="<?php echo esc_url($banner['link_url']); ?>"
                       class="cookierus-policy-link"
                       target="_blank" rel="noopener noreferrer">
                        <?php echo esc_html($banner['link_text'] ?? 'Политика конфиденциальности'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="cookierus-buttons">
        <button type="button" class="cookierus-btn cookierus-btn-accept" id="cookierus-accept">
            <?php echo esc_html($banner['btn_accept'] ?? 'Принять все'); ?>
        </button>
        <button type="button" class="cookierus-btn cookierus-btn-decline" id="cookierus-decline">
            <?php echo esc_html($banner['btn_decline'] ?? 'Отклонить'); ?>
        </button>
        <?php if (!empty($banner['btn_settings'])): ?>
        <button type="button" class="cookierus-btn cookierus-btn-settings" id="cookierus-open-settings">
            <?php echo esc_html($banner['btn_settings'] ?? 'Настроить'); ?>
        </button>
        <?php endif; ?>
        <?php if (!empty($banner['extra_btn_enabled']) && !empty($banner['extra_btn_text'])): ?>
        <a href="<?php echo esc_url($banner['extra_btn_url'] ?? '#'); ?>"
           class="cookierus-btn cookierus-btn-custom"
           target="_blank" rel="noopener">
            <?php echo esc_html($banner['extra_btn_text']); ?>
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Свёрнутая кнопка (плавающая) -->
<?php if ($allow_minimize): ?>
<button type="button" id="cookierus-minimized-btn" class="cookierus-minimized-btn"
        style="display:none;" title="Настройки cookie" aria-label="Настройки cookie">
    <img src="<?php echo esc_url($plugin_url . 'assets/cookie-svgrepo-com.png'); ?>"
         alt="" role="presentation" width="28" height="28">
</button>
<?php endif; ?>
<?php if (($banner['show_revoke_button'] ?? true)): ?>
<button type="button" id="cookierus-revoke" class="cookierus-revoke-btn"
        style="display:none;" title="Отозвать согласие" aria-label="Отозвать согласие">
    Отозвать согласие
</button>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════
     МОДАЛЬНОЕ ОКНО "Настроить"
═══════════════════════════════════════════════════════ -->
<div id="cookierus-modal" class="cookierus-modal" role="dialog" aria-modal="true" aria-label="Настройки согласия" style="display:none;">
    <div class="cookierus-modal-backdrop" id="cookierus-modal-backdrop"></div>
    <div class="cookierus-modal-content">

        <!-- Заголовок модала -->
        <div class="cookierus-modal-header">
            <h3>Настройки согласия</h3>
            <button type="button" class="cookierus-modal-close-x" id="cookierus-close-modal" aria-label="Закрыть">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor"><path d="M13 1L1 13M1 1l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>

        <!-- Вкладки -->
        <div class="cr-modal-tabs" role="tablist">
            <button class="cr-modal-tab cr-modal-tab--active"
                    id="cr-tab-btn-categories" role="tab"
                    aria-selected="true" aria-controls="cr-panel-categories"
                    data-tab="categories">
                Категории
            </button>
            <button class="cr-modal-tab"
                    id="cr-tab-btn-goals" role="tab"
                    aria-selected="false" aria-controls="cr-panel-goals"
                    data-tab="goals">
                Цели обработки
            </button>
            <button class="cr-modal-tab"
                    id="cr-tab-btn-mentions" role="tab"
                    aria-selected="false" aria-controls="cr-panel-mentions"
                    data-tab="mentions">
                Упоминания
            </button>
        </div>

        <!-- ── ПАНЕЛЬ: КАТЕГОРИИ ───────────────────────── -->
        <div class="cr-modal-panel" id="cr-panel-categories" role="tabpanel">
            <p class="cr-modal-desc">
                Выберите категории cookie, которые вы разрешаете. Файлы cookie категории «Необходимые» нельзя отключить — они нужны для работы сайта.
                Изменения вступят в силу после нажатия «Сохранить выбор».
            </p>

            <!-- Необходимые (всегда вкл) -->
            <div class="cookierus-category cr-cat-required">
                <div class="cookierus-category-header">
                    <div class="cr-cat-info">
                        <span class="cr-cat-name">Необходимые</span>
                        <span class="cr-cat-meta">Сессия · Всегда активны</span>
                    </div>
                    <span class="cr-cat-always-on">Включены</span>
                </div>
                <p class="cr-cat-desc">Cookie, без которых сайт не может нормально функционировать: сессионные идентификаторы, авторизация, корзина покупок. Согласие не требуется — они относятся к законному интересу оператора (ст. 6 ч. 1 п. 5 152-ФЗ).</p>
            </div>

            <?php if (!empty($sections['functional'])): ?>
            <div class="cookierus-category">
                <div class="cookierus-category-header">
                    <div class="cr-cat-info">
                        <span class="cr-cat-name">Функциональные</span>
                        <span class="cr-cat-meta">До <?php echo esc_html($functional_retention_days); ?> дней</span>
                    </div>
                    <label class="cr-toggle">
                        <input type="checkbox" id="cat-functional" checked>
                        <span class="cr-toggle-track"><span class="cr-toggle-thumb"></span></span>
                    </label>
                </div>
                <p class="cr-cat-desc">Запоминают ваши настройки и предпочтения (язык интерфейса, регион, оформление). Помогают сделать сайт удобнее при повторных визитах. <?php echo esc_html($sections['functional_desc'] ?? ''); ?>
                    <?php if (!empty($policy_links['functional'])): ?>
                        <a href="<?php echo esc_url($policy_links['functional']); ?>" target="_blank" rel="noopener noreferrer">Политика сервиса</a>
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>

            <?php if (!empty($sections['analytics'])): ?>
            <div class="cookierus-category">
                <div class="cookierus-category-header">
                    <div class="cr-cat-info">
                        <span class="cr-cat-name">Аналитические</span>
                        <span class="cr-cat-meta">До <?php echo esc_html($sections['analytics_retention_days'] ?? 730); ?> дней</span>
                    </div>
                    <label class="cr-toggle">
                        <input type="checkbox" id="cat-analytics" checked>
                        <span class="cr-toggle-track"><span class="cr-toggle-thumb"></span></span>
                    </label>
                </div>
                <p class="cr-cat-desc">Позволяют анализировать посещаемость и поведение пользователей. Помогают улучшать сайт. Данные обезличены и агрегированы.
                    <?php
                    $analytics_labels = ['yandex_metrika' => 'Яндекс.Метрика', 'mailru_counters' => 'Счётчики Mail.ru', 'callibri' => 'Колибри'];
                    $enabled_analytics = [];
                    foreach ($analytics_labels as $service_key => $service_label) {
                        if (!empty($analytics_services[$service_key])) $enabled_analytics[] = $service_label;
                    }
                    if ($enabled_analytics) echo ' Сервисы: ' . esc_html(implode(', ', $enabled_analytics)) . '.';
                    ?>
                    <?php if (!empty($policy_links['analytics'])): ?>
                        <a href="<?php echo esc_url($policy_links['analytics']); ?>" target="_blank" rel="noopener noreferrer">Политика сервиса</a>
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>

            <?php if (!empty($sections['performance'])): ?>
            <div class="cookierus-category">
                <div class="cookierus-category-header">
                    <div class="cr-cat-info">
                        <span class="cr-cat-name">Производительность</span>
                        <span class="cr-cat-meta">До 12 мес.</span>
                    </div>
                    <label class="cr-toggle">
                        <input type="checkbox" id="cat-performance">
                        <span class="cr-toggle-track"><span class="cr-toggle-thumb"></span></span>
                    </label>
                </div>
                <p class="cr-cat-desc">Используются для оптимизации скорости загрузки и мониторинга технических показателей сайта. Не содержат личной информации. <?php echo esc_html($sections['performance_desc'] ?? ''); ?>
                    <?php if (!empty($policy_links['performance'])): ?>
                        <a href="<?php echo esc_url($policy_links['performance']); ?>" target="_blank" rel="noopener noreferrer">Политика сервиса</a>
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>

            <?php if (!empty($sections['advertising'])): ?>
            <div class="cookierus-category">
                <div class="cookierus-category-header">
                    <div class="cr-cat-info">
                        <span class="cr-cat-name">Маркетинговые</span>
                        <span class="cr-cat-meta">До <?php echo esc_html($sections['advertising_retention_days'] ?? 90); ?> дней</span>
                    </div>
                    <label class="cr-toggle">
                        <input type="checkbox" id="cat-advertising">
                        <span class="cr-toggle-track"><span class="cr-toggle-thumb"></span></span>
                    </label>
                </div>
                <p class="cr-cat-desc">Используются для показа персонализированной рекламы на основе ваших интересов. Данные могут передаваться рекламным партнёрам.
                    <?php
                    $advertising_labels = ['vk_ads' => 'VK Реклама', 'yandex_ads' => 'Яндекс.Реклама'];
                    $enabled_advertising = [];
                    foreach ($advertising_labels as $service_key => $service_label) {
                        if (!empty($advertising_services[$service_key])) $enabled_advertising[] = $service_label;
                    }
                    if ($enabled_advertising) echo ' Сервисы: ' . esc_html(implode(', ', $enabled_advertising)) . '.';
                    ?>
                    <?php if (!empty($policy_links['advertising'])): ?>
                        <a href="<?php echo esc_url($policy_links['advertising']); ?>" target="_blank" rel="noopener noreferrer">Политика сервиса</a>
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>

            <?php foreach ($custom_categories as $custom_category): ?>
                <?php if (empty($custom_category['enabled']) || empty($custom_category['title']) || empty($custom_category['id'])) continue; ?>
                <?php $custom_category_id = sanitize_key($custom_category['id']); ?>
                <div class="cookierus-category">
                    <div class="cookierus-category-header">
                        <div class="cr-cat-info">
                            <span class="cr-cat-name"><?php echo esc_html($custom_category['title']); ?></span>
                            <span class="cr-cat-meta">Пользовательская категория</span>
                        </div>
                        <label class="cr-toggle">
                            <input type="checkbox"
                                   id="cat-<?php echo esc_attr($custom_category_id); ?>"
                                   data-cookierus-custom-category="<?php echo esc_attr($custom_category_id); ?>"
                                   checked>
                            <span class="cr-toggle-track"><span class="cr-toggle-thumb"></span></span>
                        </label>
                    </div>
                    <?php if (!empty($custom_category['description']) || !empty($custom_category['policy_url'])): ?>
                        <p class="cr-cat-desc">
                            <?php echo esc_html($custom_category['description']); ?>
                            <?php if (!empty($custom_category['policy_url'])): ?>
                                <a href="<?php echo esc_url($custom_category['policy_url']); ?>" target="_blank" rel="noopener noreferrer">Политика сервиса</a>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ── ПАНЕЛЬ: ЦЕЛИ ───────────────────────────── -->
        <div class="cr-modal-panel cr-modal-panel--hidden" id="cr-panel-goals" role="tabpanel">
            <p class="cr-modal-desc">
                Укажите, для каких целей вы разрешаете обработку ваших персональных данных через файлы cookie. Каждая цель описывает конкретный способ использования данных.
            </p>

            <?php
            $goals_list = [
                'storage' => [
                    'title' => 'Хранение и доступ к данным',
                    'desc'  => 'Хранение информации на устройстве или доступ к ней для обеспечения базовой функциональности сайта (ст. 6 ч. 1 п. 5 152-ФЗ).',
                ],
                'analytics' => [
                    'title' => 'Аналитика и статистика',
                    'desc'  => 'Сбор данных о том, как посетители используют сайт, в целях формирования агрегированной статистики и улучшения сервиса.',
                ],
                'personalized_content' => [
                    'title' => 'Персонализация контента',
                    'desc'  => 'Подбор материалов сайта (статей, рекомендаций, разделов) с учётом ваших предпочтений и истории посещений.',
                ],
                'personalized' => [
                    'title' => 'Персонализированная реклама',
                    'desc'  => 'Подбор рекламных материалов, соответствующих вашим интересам, на основе профиля посещений.',
                ],
                'retargeting' => [
                    'title' => 'Ретаргетинг',
                    'desc'  => 'Повторный показ рекламы пользователям, ранее посещавшим сайт, на сторонних площадках.',
                ],
                'ad_measure' => [
                    'title' => 'Оценка эффективности рекламы',
                    'desc'  => 'Измерение охвата и результативности рекламных кампаний без привязки к конкретному профилю.',
                ],
                'content_measure' => [
                    'title' => 'Оценка эффективности контента',
                    'desc'  => 'Анализ того, насколько интересен и полезен для вас представленный на сайте контент.',
                ],
                'profiling' => [
                    'title' => 'Создание профиля пользователя',
                    'desc'  => 'Формирование профиля ваших интересов и поведения на основе данных о посещениях для последующей персонализации.',
                ],
                'geolocation' => [
                    'title' => 'Геолокация',
                    'desc'  => 'Использование данных о приблизительном местоположении для показа регионального контента или рекламы.',
                ],
                'third_party' => [
                    'title' => 'Передача данных третьим лицам',
                    'desc'  => 'Предоставление данных партнёрам и поставщикам услуг в целях, указанных выше (ст. 6 ч. 3, ст. 18 152-ФЗ).',
                ],
                'development' => [
                    'title' => 'Разработка и совершенствование',
                    'desc'  => 'Использование данных для улучшения существующих и создания новых функций и сервисов.',
                ],
            ];
            $goals_off_by_default = ['personalized', 'retargeting', 'ad_measure', 'profiling', 'geolocation', 'third_party'];
            foreach ($goals_list as $key => $g):
                if (empty($show_goals[$key])) continue;
                $checked = !in_array($key, $goals_off_by_default) ? 'checked' : '';
            ?>
            <div class="cookierus-category">
                <div class="cookierus-category-header">
                    <div class="cr-cat-info">
                        <span class="cr-cat-name"><?php echo esc_html($g['title'] ?? ''); ?></span>
                    </div>
                    <label class="cr-toggle">
                        <input type="checkbox" id="goal-<?php echo esc_attr($key); ?>" name="cr-goal-<?php echo esc_attr($key); ?>" <?php echo $checked; ?>>
                        <span class="cr-toggle-track"><span class="cr-toggle-thumb"></span></span>
                    </label>
                </div>
                <p class="cr-cat-desc"><?php echo esc_html($g['desc']); ?></p>
            </div>
            <?php endforeach; ?>
            <?php foreach ($custom_goals as $custom_goal): ?>
                <?php if (empty($custom_goal['enabled']) || empty($custom_goal['title']) || empty($custom_goal['id'])) continue; ?>
                <?php $custom_goal_id = sanitize_key($custom_goal['id']); ?>
                <div class="cookierus-category">
                    <div class="cookierus-category-header">
                        <div class="cr-cat-info">
                            <span class="cr-cat-name"><?php echo esc_html($custom_goal['title']); ?></span>
                            <span class="cr-cat-meta">Пользовательская цель</span>
                        </div>
                        <label class="cr-toggle">
                            <input type="checkbox"
                                   id="goal-<?php echo esc_attr($custom_goal_id); ?>"
                                   name="cr-goal-<?php echo esc_attr($custom_goal_id); ?>"
                                   checked>
                            <span class="cr-toggle-track"><span class="cr-toggle-thumb"></span></span>
                        </label>
                    </div>
                    <?php if (!empty($custom_goal['description'])): ?>
                        <p class="cr-cat-desc"><?php echo esc_html($custom_goal['description']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ── ПАНЕЛЬ: УПОМНАНИЯ ───────────────────────── -->
        <div class="cr-modal-panel cr-modal-panel--hidden" id="cr-panel-mentions" role="tabpanel">
            <p class="cr-modal-desc">
                Важные сведения о регистрации и входе на сайте.
            </p>
            <div class="cookierus-category cr-cat-required">
                <div class="cookierus-category-header">
                    <div class="cr-cat-info">
                        <span class="cr-cat-name">Ограничение почтовых доменов</span>
                        <span class="cr-cat-meta">Сайт, WooCommerce и /wp-admin/</span>
                    </div>
                </div>
                <p class="cr-cat-desc">
                    Регистрация и вход на сайте, включая WooCommerce и раздел /wp-admin/, запрещены с email-адресов иностранных почтовых сервисов. Разрешены только российские почтовые домены: mail.ru, yandex.ru, rambler.ru, bk.ru, а также другие домены в зонах .ru, .su и .рф.
                </p>
                <p class="cr-cat-desc">
                    <?php echo $foreign_auth_block_enabled
                        ? 'Дополнительно включён полный запрет кнопок и OAuth-маршрутов Google, Apple ID и других иностранных систем авторизации.'
                        : 'Полный запрет входа через Google, Apple ID и другие иностранные системы можно включить администратору в настройках CookieRus.'; ?>
                </p>
            </div>
        </div>

        <!-- Кнопки внизу модала -->
        <div class="cookierus-modal-footer">
            <button type="button" class="cookierus-btn cookierus-btn-accept" id="cookierus-accept-all-modal" style="flex:1;">Принять все</button>
            <button type="button" class="cookierus-btn cookierus-btn-settings" id="cookierus-save-settings" style="flex:1;">Сохранить выбор</button>
        </div>

    </div><!-- .cookierus-modal-content -->
</div><!-- #cookierus-modal -->

<script id="cookierus-banner-script">
/* CookieRus v1.1.3 — frontend script */
(function() {
    'use strict';

    var REPEAT_SHOW    = <?php echo json_encode($repeat_show); ?>;
    var ALLOW_MINIMIZE = <?php echo $allow_minimize ? 'true' : 'false'; ?>;
    var REVOKE_ENABLED = <?php echo (($banner['show_revoke_button'] ?? true) ? 'true' : 'false'); ?>;
    var DECLINE_URL    = <?php echo json_encode($decline_url); ?>;
    var LOG_NONCE      = <?php echo json_encode(wp_create_nonce('cookierus_log_consent')); ?>;
    var TRACKERS       = <?php echo json_encode([
        'ym_id'   => $trackers['ym_id']   ?? '',
        'vk_id'   => $trackers['vk_id']   ?? '',
    ]); ?>;
    var CALLIBRI_CODE  = <?php echo wp_json_encode($trackers['callibri_code'] ?? ''); ?>;
    var SERVICES       = <?php echo json_encode([
        'yandex_metrika' => !empty($analytics_services['yandex_metrika']),
        'mailru_counters' => !empty($analytics_services['mailru_counters']),
        'callibri' => !empty($analytics_services['callibri']),
        'vk_ads' => !empty($advertising_services['vk_ads']),
        'yandex_ads' => !empty($advertising_services['yandex_ads']),
    ]); ?>;
    var CUSTOM_CATEGORY_IDS = <?php echo json_encode(array_values(array_unique($custom_category_ids))); ?>;

    /* ── Cookie helpers ──────────────────────────────── */
    function setCookie(name, value, days) {
        var expiry = '';
        if (typeof days === 'number' && days !== 0) {
            var d = new Date();
            d.setTime(d.getTime() + days * 86400000);
            expiry = '; expires=' + d.toUTCString();
        }
        document.cookie = name + '=' + value + expiry + '; path=/; SameSite=Lax';
    }
    function getCookie(name) {
        var m = document.cookie.match('(?:^|;)\\s*' + name + '=([^;]*)');
        return m ? decodeURIComponent(m[1]) : null;
    }

    function normalizeCategories(categories) {
        var cats = Array.isArray(categories) ? categories : (categories || 'all').split(',');
        var allCategories = ['necessary', 'functional', 'analytics', 'performance', 'advertising']
            .concat(CUSTOM_CATEGORY_IDS);
        return (cats.indexOf('all') !== -1 || cats.indexOf('accepted') !== -1)
            ? allCategories
            : cats;
    }

    function updateFirewall(categories) {
        var state = window.__cookierusConsentState || {categories: [], blockedDomains: []};
        state.categories = normalizeCategories(categories);
        window.__cookierusConsentState = state;
        if (typeof window.CookieRusReleaseBlockedResources === 'function') {
            window.CookieRusReleaseBlockedResources();
        }
        document.dispatchEvent(new CustomEvent('cookierus:consent', {
            detail: {categories: state.categories}
        }));
    }

    /* ── Tracker loader ──────────────────────────────── */
    function loadCallibriCode() {
        if (!CALLIBRI_CODE || !SERVICES.callibri || window.__cookierusCallibriLoaded) return;

        var holder = document.createElement('div');
        holder.innerHTML = CALLIBRI_CODE;
        var scripts = holder.querySelectorAll('script');

        if (!scripts.length) {
            var inlineScript = document.createElement('script');
            inlineScript.text = CALLIBRI_CODE;
            document.head.appendChild(inlineScript);
        } else {
            scripts.forEach(function(script) {
                var replacement = document.createElement('script');
                for (var i = 0; i < script.attributes.length; i++) {
                    replacement.setAttribute(script.attributes[i].name, script.attributes[i].value);
                }
                replacement.text = script.textContent || '';
                document.head.appendChild(replacement);
            });
        }

        window.__cookierusCallibriLoaded = true;
    }

    function loadTrackers(categories) {
        var cats = normalizeCategories(categories);
        var all  = cats.indexOf('all') !== -1 || cats.indexOf('accepted') !== -1;
        var analytics  = all || cats.indexOf('analytics')   !== -1;
        var marketing  = all || cats.indexOf('advertising') !== -1;
        if (!getCookie('cookierus_consent') || getCookie('cookierus_consent') === 'declined') return;

        /* Яндекс Метрика */
        if (TRACKERS.ym_id && analytics && SERVICES.yandex_metrika && !window.ym) {
            (function(m,e,t,r,i,k,a){
                m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();
                k=e.createElement(t); a=e.getElementsByTagName(t)[0];
                k.async=1; k.src=r; a.parentNode.insertBefore(k,a);
            })(window,document,'script','https://mc.yandex.ru/metrika/tag.js','ym');
            ym(parseInt(TRACKERS.ym_id,10),'init',{clickmap:true,trackLinks:true,accurateTrackBounce:true,webvisor:true});
        }

        /* VK Пиксель */
        if (TRACKERS.vk_id && marketing && SERVICES.vk_ads && !document.querySelector('script[src*="vk.com/js/api"]')) {
            var vk = document.createElement('script');
            vk.async = true;
            vk.src = 'https://vk.com/js/api/openapi.js?169';
            vk.onload = function() {
                if (window.VK && VK.Retargeting) {
                    VK.Retargeting.Init(TRACKERS.vk_id);
                    VK.Retargeting.Hit();
                }
            };
            document.head.appendChild(vk);
        }

        if (analytics && SERVICES.callibri) {
            loadCallibriCode();
        }
    }

    /* Если согласие уже дано — сразу загружаем трекеры */
    (function() {
        var consent = getCookie('cookierus_consent');
        if (consent && consent !== 'declined') {
            var cats = getCookie('cookierus_categories') || 'all';
            updateFirewall(cats);
            loadTrackers(cats);
        }
    })();

    /* ── Основная логика ─────────────────────────────── */
    function init() {
        var banner       = document.getElementById('cookierus-banner');
        var modal        = document.getElementById('cookierus-modal');
        var backdrop     = document.getElementById('cookierus-modal-backdrop');
        var minBtn       = document.getElementById('cookierus-minimized-btn');
        var minimizeBtn  = document.getElementById('cookierus-minimize');
        var revokeBtn    = document.getElementById('cookierus-revoke');
        var acceptBtn    = document.getElementById('cookierus-accept');
        var declineBtn   = document.getElementById('cookierus-decline');
        var openSettings = document.getElementById('cookierus-open-settings');
        var saveSettings = document.getElementById('cookierus-save-settings');
        var acceptModal  = document.getElementById('cookierus-accept-all-modal');
        var closeModal   = document.getElementById('cookierus-close-modal');
        var tabBtns      = document.querySelectorAll('.cr-modal-tab');

        /* Показывать ли баннер? */
        if (banner && REPEAT_SHOW !== 'always' && getCookie('cookierus_consent')) {
            banner.remove();
        }

        if (banner && !getCookie('cookierus_consent')) {
            document.body.classList.add('cookierus-show-banner');
        }

        /* ── Отправка согласия ───────────────────────── */
        function logConsent(status, categories) {
            categories = categories || 'all';

            var uid  = localStorage.getItem('cookierus_uid') || ('uid_' + Math.random().toString(36).substr(2,9));
            localStorage.setItem('cookierus_uid',  uid);
            localStorage.setItem('cookierus_consent', status);
            localStorage.setItem('cookierus_consent_time', Date.now().toString());

            var days = REPEAT_SHOW === '7days'  ? 7
                     : REPEAT_SHOW === '30days' ? 30
                     : REPEAT_SHOW === 'always' ? 0 : 365;
            setCookie('cookierus_consent',    status,     days);
            setCookie('cookierus_categories', categories, days);

            if (status !== 'declined') {
                updateFirewall(categories);
                loadTrackers(categories);
            }

            var fd = new FormData();
            fd.append('action',     'cookierus_log_consent');
            fd.append('status',     status);
            fd.append('categories', categories);
            fd.append('uid',        uid);
            fd.append('nonce',      LOG_NONCE);
            var ajax = (typeof CookieRusData !== 'undefined') ? CookieRusData.ajax_url : '/wp-admin/admin-ajax.php';
            fetch(ajax, {method:'POST', body:fd}).catch(function(){});

            /* Скрываем баннер и модал */
            document.body.classList.remove('cookierus-show-banner');
            if (modal) modal.style.display = 'none';
            if (minBtn) minBtn.style.display = 'none';
            if (revokeBtn && REVOKE_ENABLED) revokeBtn.style.display = 'block';
        }

        function revokeConsent() {
            ['cookierus_consent', 'cookierus_categories', 'cookierus_goals'].forEach(function(name) {
                setCookie(name, '', -1);
            });
            try {
                localStorage.removeItem('cookierus_consent');
                localStorage.removeItem('cookierus_consent_time');
            } catch (e) {}
            window.location.reload();
        }

        if (revokeBtn && REVOKE_ENABLED) {
            revokeBtn.style.display = getCookie('cookierus_consent') ? 'block' : 'none';
            revokeBtn.addEventListener('click', function() {
                revokeConsent();
            });
        }

        /* ── Свернуть / развернуть ───────────────────── */
        if (ALLOW_MINIMIZE && minimizeBtn && minBtn) {
            minimizeBtn.addEventListener('click', function() {
                document.body.classList.remove('cookierus-show-banner');
                minBtn.style.display = 'flex';
            });
            minBtn.addEventListener('click', function() {
                minBtn.style.display = 'none';
                banner.style.removeProperty('display');
                document.body.classList.add('cookierus-show-banner');
            });
        }

        /* ── Ripple на кнопках ───────────────────────── */
        if (banner) banner.querySelectorAll('.cookierus-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                var r = document.createElement('span');
                r.className = 'cr-ripple';
                var rect = btn.getBoundingClientRect();
                var sz   = Math.max(rect.width, rect.height);
                r.style.cssText = 'width:'+sz+'px;height:'+sz+'px;left:'+(e.clientX-rect.left-sz/2)+'px;top:'+(e.clientY-rect.top-sz/2)+'px;';
                btn.appendChild(r);
                setTimeout(function(){ r.remove(); }, 560);
            });
        });

        /* ── Кнопки баннера ──────────────────────────── */
        if (acceptBtn) acceptBtn.addEventListener('click', function() {
            logConsent('accepted', 'all');
        });

        if (declineBtn) declineBtn.addEventListener('click', function() {
            logConsent('declined', 'none');
            if (DECLINE_URL) {
                window.location.href = DECLINE_URL;
            }
        });

        if (openSettings) openSettings.addEventListener('click', function() {
            if (modal) modal.style.display = 'flex';
        });

        /* ── Закрытие модала ─────────────────────────── */
        function closeModalFn() {
            if (modal) modal.style.display = 'none';
        }
        if (closeModal) closeModal.addEventListener('click', closeModalFn);
        if (backdrop)   backdrop.addEventListener('click', closeModalFn);
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModalFn();
        });

        /* ── Вкладки модала ──────────────────────────── */
        tabBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                tabBtns.forEach(function(b) {
                    b.classList.remove('cr-modal-tab--active');
                    b.setAttribute('aria-selected', 'false');
                });
                btn.classList.add('cr-modal-tab--active');
                btn.setAttribute('aria-selected', 'true');
                var target = btn.dataset.tab;
                document.querySelectorAll('.cr-modal-panel').forEach(function(p) {
                    p.classList.add('cr-modal-panel--hidden');
                });
                var panel = document.getElementById('cr-panel-' + target);
                if (panel) panel.classList.remove('cr-modal-panel--hidden');
            });
        });

        /* ── Кнопки модала (принять / сохранить) ────── */
        if (acceptModal) acceptModal.addEventListener('click', function() {
            logConsent('accepted', 'all');
        });

        if (saveSettings) saveSettings.addEventListener('click', function() {
            var cats = ['necessary'];
            var checkMap = {
                'cat-functional'  : 'functional',
                'cat-analytics'   : 'analytics',
                'cat-performance' : 'performance',
                'cat-advertising' : 'advertising',
            };
            Object.keys(checkMap).forEach(function(id) {
                var el = document.getElementById(id);
                if (el && el.checked) cats.push(checkMap[id]);
            });
            document.querySelectorAll('[data-cookierus-custom-category]:checked').forEach(function(el) {
                cats.push(el.getAttribute('data-cookierus-custom-category'));
            });
            var goals = [];
            document.querySelectorAll('[id^="goal-"]:checked').forEach(function(el) {
                goals.push(el.id.replace('goal-', ''));
            });
            var days = REPEAT_SHOW === '7days' ? 7 : (REPEAT_SHOW === '30days' ? 30 : 365);
            setCookie('cookierus_goals', goals.join(','), days);
            logConsent('accepted', cats.join(','));
        });

        if (getCookie('cookierus_categories')) {
            var selected = normalizeCategories(getCookie('cookierus_categories'));
            ['functional', 'analytics', 'performance', 'advertising'].forEach(function(category) {
                var checkbox = document.getElementById('cat-' + category);
                if (checkbox) checkbox.checked = selected.indexOf(category) !== -1;
            });
            document.querySelectorAll('[data-cookierus-custom-category]').forEach(function(checkbox) {
                checkbox.checked = selected.indexOf(
                    checkbox.getAttribute('data-cookierus-custom-category')
                ) !== -1;
            });
        }
        if (getCookie('cookierus_goals')) {
            var selectedGoals = getCookie('cookierus_goals').split(',');
            document.querySelectorAll('[id^="goal-"]').forEach(function(checkbox) {
                checkbox.checked = selectedGoals.indexOf(checkbox.id.replace('goal-', '')) !== -1;
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
