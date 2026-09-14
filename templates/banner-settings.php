<?php if (!defined('ABSPATH')) exit; 
$settings = get_option('cookierus_settings');
$banner = $settings['banner'] ?? [];
$sections = $settings['sections'] ?? [];
$analytics_services = $sections['analytics_services'] ?? [
    'yandex_metrika' => 1,
    'mailru_counters' => 0,
    'callibri' => 0,
];
$advertising_services = $sections['advertising_services'] ?? [
    'vk_ads' => 0,
    'yandex_ads' => 1,
];
?>
<style>
.cookierus-settings-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 25px;
    margin-top: 20px;
    width: 100%;
}
/* Section divider labels spanning full grid width */
.cr-settings-section-label {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #6b7280;
    border-top: 1px solid #e5e7eb;
    padding-top: 18px;
    margin-bottom: -10px;
}
.cr-settings-section-label .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
    color: #9ca3af;
}
.cookierus-settings-card {
    background: #fff;
    border: 1px solid #e1e9f5;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(7, 96, 210, 0.05);
    min-width: 0;
    box-sizing: border-box;
}
.cookierus-settings-card input[type="text"],
.cookierus-settings-card input[type="url"],
.cookierus-settings-card input[type="number"],
.cookierus-settings-card textarea,
.cookierus-settings-card select {
    max-width: 100%;
    box-sizing: border-box;
}
.cr-modal-settings-card,
.cr-tracker-settings-card,
.cr-goals-settings-card {
    grid-column: 1 / -1;
}
.cr-custom-item {
    padding: 14px;
    margin: 0 0 12px;
    background: #f8fafc;
    border: 1px solid #dbe5f0;
    border-radius: 10px;
}
.cr-custom-item:last-child { margin-bottom: 14px; }
.cr-custom-item input[type="text"],
.cr-custom-item input[type="url"],
.cr-custom-item textarea { width: 100%; }
.cr-custom-item > div[style*="grid-template-columns"] {
    min-width: 0;
}
@media (max-width: 900px) {
    .cookierus-settings-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 16px;
    }
    .cr-modal-settings-card,
    .cr-tracker-settings-card,
    .cr-goals-settings-card {
        grid-column: auto;
    }
}
@media (max-width: 600px) {
    .cookierus-settings-card { padding: 18px; }
    .cr-custom-item > div[style*="grid-template-columns"] {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}
.cookierus-settings-card h4 {
    margin-top: 0;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f1;
    color: #0760D2;
    display: flex;
    align-items: center;
    gap: 10px;
}
.cookierus-settings-card h4 i { font-size: 1.2em; }
.cookierus-form-group {
    margin-bottom: 15px;
}
.cookierus-form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #1d2327;
}
.cookierus-btn-settings-row {
    background: #f8faff;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e1e9f5;
    margin-bottom: 15px;
}
.cookierus-btn-settings-row h5 {
    margin: 0 0 10px 0;
    color: #555;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.cookierus-color-flex {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}
.cookierus-color-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
}
.section-desc {
    margin-top: 10px;
    padding: 10px;
    background: #fff;
    border: 1px dashed #0760D2;
    border-radius: 6px;
}
.cookierus-range-row {
    display: flex;
    align-items: center;
    gap: 10px;
}
.cookierus-range-row input[type="range"] {
    flex: 1;
    accent-color: #0760D2;
}
.cookierus-range-row .range-value {
    min-width: 40px;
    text-align: right;
    font-weight: 600;
    color: #0760D2;
    font-size: 13px;
}
.cookierus-toggle-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
}
.cookierus-toggle-row label.toggle-label {
    font-weight: 600;
    color: #1d2327;
    margin: 0;
    cursor: pointer;
}

/* ── Внутренние вкладки настроек баннера ───────────────── */
.cr-settings-subtabs {
    display: flex;
    align-items: stretch;
    gap: 6px;
    margin: 0 0 20px;
    padding: 6px;
    background: #f4f7fb;
    border: 1px solid #e1e9f5;
    border-radius: 12px;
    overflow-x: auto;
}
.cr-settings-subtab {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 9px 15px;
    border: 1px solid transparent;
    border-radius: 8px;
    background: transparent;
    color: #5b6472;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.2;
    white-space: nowrap;
    transition: color .18s ease, background .18s ease, box-shadow .18s ease, border-color .18s ease;
}
.cr-settings-subtab .dashicons {
    width: 17px;
    height: 17px;
    font-size: 17px;
}
.cr-settings-subtab:hover {
    color: #0760D2;
    background: #fff;
}
.cr-settings-subtab.is-active {
    color: #0760D2;
    background: #fff;
    border-color: #d7e4f6;
    box-shadow: 0 3px 10px rgba(7, 96, 210, .10);
}
.cr-settings-subtab:focus-visible {
    outline: 2px solid #0760D2;
    outline-offset: 1px;
}
[data-cr-settings-tab][hidden] { display: none !important; }
@media (max-width: 600px) {
    .cr-settings-subtabs {
        gap: 3px;
        padding: 4px;
    }
    .cr-settings-subtab {
        min-height: 38px;
        padding: 8px 11px;
        font-size: 12px;
    }
    .cr-settings-subtab .dashicons { display: none; }
}
</style>

<div class="cookierus-admin-header" style="margin-bottom: 20px;">
    <h3>Настройки внешнего вида и контента баннера</h3>
    <p class="description">Настройте баннер так, чтобы он идеально вписывался в дизайн вашего сайта.</p>
</div>

<div class="cookierus-preview-toggle-container" style="margin-bottom: 20px; background: linear-gradient(135deg, #0760D2 0%, #0a4a9e 100%); padding: 15px 25px; border-radius: 12px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(7, 96, 210, 0.3);">
    <div style="display: flex; align-items: center; gap: 12px;">
        <span class="dashicons dashicons-visibility" style="color: #fff; font-size: 24px;"></span>
        <div>
            <span style="color: #fff; font-weight: 600; font-size: 15px;">Предварительный просмотр баннера</span>
            <p style="color: rgba(255,255,255,0.8); margin: 2px 0 0 0; font-size: 12px;">Просмотрите баннер прямо в админке перед сохранением</p>
        </div>
    </div>
    <label class="cookierus-switch">
        <input type="checkbox" id="cookierus-preview-toggle">
        <span class="cookierus-slider"></span>
    </label>
</div>

<style>
/* ══════════════════════════════════════════════════════
   ВАЖНО: сброс стилей WP-admin внутри превью
══════════════════════════════════════════════════════ */
#cr-preview-inner,
#cr-preview-inner * {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    box-sizing: border-box !important;
    text-transform: none !important;
    letter-spacing: normal !important;
    line-height: normal !important;
}
#cr-preview-inner .cr-pi-title {
    display: block !important;
    margin: 0 0 4px 0 !important;
    padding: 0 !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;
    color: inherit !important;
    border: none !important;
    border-bottom: none !important;
    background: none !important;
    box-shadow: none !important;
    float: none !important;
    clear: none !important;
    text-align: left !important;
}
#cr-preview-inner .cr-pi-text {
    display: block !important;
    margin: 0 !important;
    padding: 0 !important;
    font-size: 11px !important;
    line-height: 1.45 !important;
    color: inherit !important;
    opacity: 0.8 !important;
    border: none !important;
    background: none !important;
    overflow: hidden !important;
}
#cr-preview-inner .cr-pi-btn {
    display: inline-block !important;
    padding: 5px 10px !important;
    border-width: 0 !important;
    border-radius: 4px !important;
    cursor: default !important;
    font-size: 10px !important;
    font-weight: 600 !important;
    line-height: 1.4 !important;
    white-space: nowrap !important;
    text-decoration: none !important;
    box-shadow: none !important;
    outline: none !important;
    float: none !important;
    width: auto !important;
    height: auto !important;
    vertical-align: middle !important;
}

/* ── Toggle switch ──────────────────────────────────── */
.cookierus-switch {
    position: relative;
    display: inline-block;
    width: 56px;
    height: 30px;
}
.cookierus-switch input { opacity: 0; width: 0; height: 0; }
.cookierus-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: rgba(255,255,255,0.3);
    transition: .3s;
    border-radius: 30px;
}
.cookierus-slider:before {
    position: absolute;
    content: "";
    height: 22px; width: 22px;
    left: 4px; bottom: 4px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.cookierus-switch input:checked + .cookierus-slider { background-color: #10b981; }
.cookierus-switch input:checked + .cookierus-slider:before { transform: translateX(26px); }

/* ── Preview container ──────────────────────────────── */
.cookierus-preview-container {
    display: none;
    margin-bottom: 25px;
    position: relative;
}
.cookierus-preview-container.active {
    display: block;
    animation: crPrevFadeIn 0.35s ease;
}
@keyframes crPrevFadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Browser mock viewport */
.cr-preview-browser {
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,0.28);
    background: #e8ecf0;
    border: 1px solid #c8d0da;
}
.cr-preview-chrome {
    background: #dde2e8;
    padding: 10px 14px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #c8d0da;
}
.cr-preview-dots { display: flex; gap: 6px; }
.cr-preview-dots span {
    width: 12px; height: 12px;
    border-radius: 50%;
    display: block;
}
.cr-preview-dots span:nth-child(1) { background: #ff5f57; }
.cr-preview-dots span:nth-child(2) { background: #febc2e; }
.cr-preview-dots span:nth-child(3) { background: #28c840; }
.cr-preview-urlbar {
    flex: 1;
    background: #fff;
    border-radius: 6px;
    padding: 5px 12px;
    font-size: 12px;
    color: #888;
    border: 1px solid #dde2e8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 8px;
}

/* Page inside viewport */
.cr-preview-page {
    background: #f5f7fa;
    height: 260px;
    position: relative;
    overflow: hidden;
}
.cr-preview-page-lines {
    padding: 20px 28px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.cr-preview-page-lines .cr-line {
    height: 11px;
    border-radius: 6px;
    background: #dde3ec;
}
.cr-preview-page-lines .cr-line.w-full { width: 100%; }
.cr-preview-page-lines .cr-line.w-80  { width: 80%; }
.cr-preview-page-lines .cr-line.w-60  { width: 60%; }
.cr-preview-page-lines .cr-line.w-40  { width: 40%; }
.cr-preview-page-lines .cr-line.cr-title-line { height: 18px; width: 55%; background: #c8d0da; margin-bottom: 4px; }

/* ── Preview banner inside page ─────────────────────── */
#cr-preview-inner {
    position: absolute;
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    box-shadow: 0 -4px 24px rgba(0,0,0,0.14);
    padding: 16px 20px;
    display: flex;
    gap: 14px;
    z-index: 10;
    transition: background-color 0.2s, color 0.2s;
}

/* Positions */
#cr-preview-inner.cr-pos-bottom   { bottom: 0; left: 0; right: 0; flex-direction: row; align-items: center; justify-content: space-between; }
#cr-preview-inner.cr-pos-top      { top: 0;    left: 0; right: 0; flex-direction: row; align-items: center; justify-content: space-between; box-shadow: 0 4px 24px rgba(0,0,0,0.14); }
#cr-preview-inner.cr-pos-bottom-left  { bottom: 10px; left: 10px; width: calc(55% - 10px); max-width: 280px; flex-direction: column; align-items: flex-start; box-shadow: 0 4px 20px rgba(0,0,0,0.18); }
#cr-preview-inner.cr-pos-bottom-right { bottom: 10px; right: 10px; width: calc(55% - 10px); max-width: 280px; flex-direction: column; align-items: flex-start; box-shadow: 0 4px 20px rgba(0,0,0,0.18); }
#cr-preview-inner.cr-pos-center { top: 50%; left: 50%; width: 250px; transform: translate(-50%, -50%); flex-direction: column; border-radius: 10px; }

/* Content */
#cr-preview-inner .cr-pi-content { flex: 1; display: flex; gap: 10px; align-items: flex-start; min-width: 0; }
#cr-preview-inner .cr-pi-icon    { flex-shrink: 0; }
#cr-preview-inner .cr-pi-textwrap { flex: 1; min-width: 0; }
#cr-preview-inner .cr-pi-title  { margin: 0 0 5px; font-weight: 700; font-size: 12px; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
#cr-preview-inner .cr-pi-text   { margin: 0; font-size: 11px; line-height: 1.45; color: inherit; opacity: 0.8; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
#cr-preview-inner .cr-pi-link   { font-size: 11px; text-decoration: underline; }

/* Buttons */
#cr-preview-inner .cr-pi-btns { display: flex; gap: 6px; flex-wrap: wrap; flex-shrink: 0; }
#cr-preview-inner.cr-pos-bottom .cr-pi-btns,
#cr-preview-inner.cr-pos-top   .cr-pi-btns { flex-direction: row; align-items: center; }
#cr-preview-inner.cr-pos-bottom-left  .cr-pi-btns,
#cr-preview-inner.cr-pos-bottom-right .cr-pi-btns { flex-direction: column; width: 100%; margin-top: 4px; }
#cr-preview-inner .cr-pi-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 5px;
    cursor: default;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
    transition: transform 0.15s;
}
#cr-preview-inner.cr-pos-bottom-left  .cr-pi-btn,
#cr-preview-inner.cr-pos-bottom-right .cr-pi-btn { width: 100%; text-align: center; }

/* Minimize pill label in preview */
.cr-preview-label-tag {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #0760D2;
    color: #fff;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 20;
}
</style>

<div class="cookierus-preview-container" id="cookierus-preview-area">
    <div class="cr-preview-browser">
        <div class="cr-preview-chrome">
            <div class="cr-preview-dots">
                <span></span><span></span><span></span>
            </div>
            <div class="cr-preview-urlbar">example.com &mdash; ваш сайт</div>
        </div>
        <div class="cr-preview-page">
            <span class="cr-preview-label-tag">
                <span class="dashicons dashicons-visibility" style="font-size:11px;vertical-align:middle;margin-right:3px;"></span>Предпросмотр
            </span>
            <div class="cr-preview-page-lines">
                <div class="cr-line cr-title-line"></div>
                <div class="cr-line w-full"></div>
                <div class="cr-line w-80"></div>
                <div class="cr-line w-60"></div>
                <div class="cr-line w-full"></div>
                <div class="cr-line w-40"></div>
            </div>

            <div id="cr-preview-inner" class="cr-pos-bottom">
                <div class="cr-pi-content">
                    <div class="cr-pi-icon" id="cr-pi-icon" style="display:none;"></div>
                    <div class="cr-pi-textwrap">
                        <h4 class="cr-pi-title" id="cr-pi-title"></h4>
                        <p class="cr-pi-text" id="cr-pi-text"></p>
                    </div>
                </div>
                <div class="cr-pi-btns" id="cr-pi-btns">
                    <button class="cr-pi-btn cr-btn-accept" id="cr-pi-btn-accept"></button>
                    <button class="cr-pi-btn cr-btn-decline" id="cr-pi-btn-decline"></button>
                    <button class="cr-pi-btn cr-btn-settings" id="cr-pi-btn-settings"></button>
                    <button class="cr-pi-btn cr-btn-extra" id="cr-pi-btn-extra" style="display:none;"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cr-settings-subtabs" role="tablist" aria-label="Разделы настроек баннера">
    <button type="button" class="cr-settings-subtab is-active" role="tab" aria-selected="true" aria-controls="cr-settings-content" data-cr-settings-tab-target="content">
        <span class="dashicons dashicons-edit"></span> Содержимое
    </button>
    <button type="button" class="cr-settings-subtab" role="tab" aria-selected="false" aria-controls="cr-settings-appearance" data-cr-settings-tab-target="appearance">
        <span class="dashicons dashicons-art"></span> Внешний вид
    </button>
    <button type="button" class="cr-settings-subtab" role="tab" aria-selected="false" aria-controls="cr-settings-categories" data-cr-settings-tab-target="categories">
        <span class="dashicons dashicons-admin-generic"></span> Категории
    </button>
    <button type="button" class="cr-settings-subtab" role="tab" aria-selected="false" aria-controls="cr-settings-services" data-cr-settings-tab-target="services">
        <span class="dashicons dashicons-chart-area"></span> Сервисы и цели
    </button>
    <button type="button" class="cr-settings-subtab" role="tab" aria-selected="false" aria-controls="cr-settings-behavior" data-cr-settings-tab-target="behavior">
        <span class="dashicons dashicons-controls-repeat"></span> Поведение
    </button>
</div>

<form method="post" action="options.php">
    <?php settings_fields('cookierus_settings_group'); ?>
    
    <div class="cookierus-settings-grid">
        <!-- ─────────────────────────────────────────────────────
             БЛОК A — СОДЕРЖИМОЕ
        ───────────────────────────────────────────────────── -->
        <div class="cr-settings-section-label" data-cr-settings-tab="content" id="cr-settings-content">
            <span class="dashicons dashicons-edit"></span> Содержимое баннера
        </div>

        <!-- Группа 1: Контент -->
        <div class="cookierus-settings-card" data-cr-settings-tab="content">
            <h4><span class="dashicons dashicons-editor-textcolor"></span> Текст и ссылка</h4>
            
            <div class="cookierus-form-group">
                <label>Включить баннер на сайте</label>
                <input type="checkbox" name="cookierus_settings[banner][enabled]" value="1" <?php checked(1, $banner['enabled'] ?? 0); ?>>
            </div>

            <div class="cookierus-form-group">
                <label>Заголовок баннера</label>
                <input type="text" name="cookierus_settings[banner][title]" value="<?php echo esc_attr($banner['title'] ?? 'Мы уважаем вашу конфиденциальность'); ?>" class="large-text">
                <div style="margin-top:5px; font-size:12px;">Размер шрифта: <input type="number" name="cookierus_settings[banner][title_size]" value="<?php echo esc_attr($banner['title_size'] ?? 18); ?>" style="width: 60px;"> px</div>
            </div>

            <div class="cookierus-form-group">
                <label>Текст сообщения</label>
                <textarea name="cookierus_settings[banner][text]" rows="4" class="large-text"><?php echo esc_textarea($banner['text'] ?? 'Мы используем файлы cookie...'); ?></textarea>
                <div style="margin-top:5px; font-size:12px;">Размер шрифта: <input type="number" name="cookierus_settings[banner][text_size]" value="<?php echo esc_attr($banner['text_size'] ?? 14); ?>" style="width: 60px;"> px</div>
            </div>

            <div class="cookierus-form-group">
                <label>Ссылка на политику</label>
                <div style="display:flex; gap:10px;">
                    <input type="text" name="cookierus_settings[banner][link_text]" value="<?php echo esc_attr($banner['link_text'] ?? 'Политика cookie'); ?>" placeholder="Текст ссылки" style="flex:1;">
                    <input type="text" name="cookierus_settings[banner][link_url]" value="<?php echo esc_attr($banner['link_url'] ?? ''); ?>" placeholder="URL (/policy/)" style="flex:1;">
                </div>
            </div>
        </div>

        <!-- ─────────────────────────────────────────────────────
             БЛОК B — КНОПКИ
        ───────────────────────────────────────────────────── -->
        <div class="cr-settings-section-label" data-cr-settings-tab="content">
            <span class="dashicons dashicons-button"></span> Кнопки баннера
        </div>

        <!-- Группа 2: Дизайн кнопок -->
        <div class="cookierus-settings-card" data-cr-settings-tab="content">
            <h4><span class="dashicons dashicons-admin-appearance"></span> Настройка кнопок</h4>
            
            <!-- Принять -->
            <div class="cookierus-btn-settings-row">
                <h5>Кнопка "Принять все"</h5>
                <input type="text" name="cookierus_settings[banner][btn_accept]" value="<?php echo esc_attr($banner['btn_accept'] ?? 'Принять все'); ?>" class="regular-text" style="margin-bottom:10px; width:100%;">
                <div class="cookierus-color-flex">
                    <div class="cookierus-color-item">Фон: <input type="color" name="cookierus_settings[banner][btn_bg]" value="<?php echo esc_attr($banner['btn_bg'] ?? '#0760D2'); ?>"></div>
                    <div class="cookierus-color-item">Текст: <input type="color" name="cookierus_settings[banner][btn_text]" value="<?php echo esc_attr($banner['btn_text'] ?? '#ffffff'); ?>"></div>
                    <div class="cookierus-color-item">Бордюр: <input type="checkbox" name="cookierus_settings[banner][btn_border]" value="1" <?php checked(1, $banner['btn_border'] ?? 0); ?>></div>
                    <div class="cookierus-color-item">Цвет бордюра: <input type="color" name="cookierus_settings[banner][btn_border_color]" value="<?php echo esc_attr($banner['btn_border_color'] ?? '#0760D2'); ?>"></div>
                </div>
            </div>

            <!-- Отклонить -->
            <div class="cookierus-btn-settings-row">
                <h5>Кнопка "Отклонить"</h5>
                <input type="text" name="cookierus_settings[banner][btn_decline]" value="<?php echo esc_attr($banner['btn_decline'] ?? 'Отклонить'); ?>" class="regular-text" style="margin-bottom:10px; width:100%;">
                <div class="cookierus-color-flex">
                    <div class="cookierus-color-item">Фон: <input type="color" name="cookierus_settings[banner][btn_decline_bg]" value="<?php echo esc_attr($banner['btn_decline_bg'] ?? '#f0f0f1'); ?>"></div>
                    <div class="cookierus-color-item">Текст: <input type="color" name="cookierus_settings[banner][btn_decline_text]" value="<?php echo esc_attr($banner['btn_decline_text'] ?? '#333333'); ?>"></div>
                    <div class="cookierus-color-item">Бордюр: <input type="checkbox" name="cookierus_settings[banner][btn_decline_border]" value="1" <?php checked(1, $banner['btn_decline_border'] ?? 0); ?>></div>
                    <div class="cookierus-color-item">Цвет бордюра: <input type="color" name="cookierus_settings[banner][btn_decline_border_color]" value="<?php echo esc_attr($banner['btn_decline_border_color'] ?? '#c3c4c7'); ?>"></div>
                </div>
                <div style="margin-top:12px;">
                    <label style="display:block;font-weight:600;font-size:12px;margin-bottom:5px;color:#555;">
                        URL для перенаправления при отказе
                        <span style="background:#fef3c7;color:#92400e;padding:2px 6px;border-radius:4px;font-weight:400;font-size:11px;margin-left:5px;">152-ФЗ: рекомендуется</span>
                    </label>
                    <input type="url" name="cookierus_settings[banner][btn_decline_url]" value="<?php echo esc_attr($banner['btn_decline_url'] ?? ''); ?>" class="large-text" placeholder="URL для перенаправления при отказе">
                    <p style="margin:5px 0 0;font-size:11px;color:#888;">Если заполнено — при нажатии «Отклонить» пользователь будет перенаправлен на эту страницу (например, инструкция по настройке cookie в браузере). Если пусто — баннер применит стандартные настройки и перенаправит пользователя на <code>/?cookierus_revoke=1</code>.</p>
                    <p style="margin:5px 0 0;font-size:11px;color:#888;"><code>/?cookierus_revoke=1</code> — вставьте эту ссылку в политику конфиденциальности или на отдельную страницу. При переходе по ней согласие пользователя будет отозвано.</p>
                </div>
            </div>

            <!-- Настроить -->
            <div class="cookierus-btn-settings-row">
                <h5>Кнопка "Настроить"</h5>
                <input type="text" name="cookierus_settings[banner][btn_settings]" value="<?php echo esc_attr($banner['btn_settings'] ?? 'Настроить'); ?>" class="regular-text" style="margin-bottom:10px; width:100%;">
                <div class="cookierus-color-flex">
                    <div class="cookierus-color-item">Фон: <input type="color" name="cookierus_settings[banner][btn_settings_bg]" value="<?php echo esc_attr($banner['btn_settings_bg'] ?? '#ffffff'); ?>"></div>
                    <div class="cookierus-color-item">Текст: <input type="color" name="cookierus_settings[banner][btn_settings_text]" value="<?php echo esc_attr($banner['btn_settings_text'] ?? '#333333'); ?>"></div>
                    <div class="cookierus-color-item">Бордюр: <input type="checkbox" name="cookierus_settings[banner][btn_settings_border]" value="1" <?php checked(1, $banner['btn_settings_border'] ?? 0); ?>></div>
                    <div class="cookierus-color-item">Цвет бордюра: <input type="color" name="cookierus_settings[banner][btn_settings_border_color]" value="<?php echo esc_attr($banner['btn_settings_border_color'] ?? '#c3c4c7'); ?>"></div>
                </div>
            </div>
        </div>

        <!-- ─────────────────────────────────────────────────────
             БЛОК C — ДОПОЛНИТЕЛЬНО
        ───────────────────────────────────────────────────── -->
        <div class="cr-settings-section-label" data-cr-settings-tab="content">
            <span class="dashicons dashicons-plus-alt2"></span> Дополнительные элементы
        </div>

        <!-- Группа 3: Дополнительная кнопка -->
        <div class="cookierus-settings-card" data-cr-settings-tab="content">
            <h4><span class="dashicons dashicons-plus-alt"></span> Дополнительная кнопка</h4>
            <p class="description" style="margin-bottom:15px;">Добавьте произвольную кнопку с кастомным URL (например, «Подробнее» ведущую на страницу политики).</p>

            <div class="cookierus-toggle-row">
                <label class="cookierus-switch" style="display:inline-block; width:46px; height:24px;">
                    <input type="checkbox" name="cookierus_settings[banner][extra_btn_enabled]" value="1" id="extra-btn-toggle" <?php checked(1, $banner['extra_btn_enabled'] ?? 0); ?>>
                    <span class="cookierus-slider" style="background-color:<?php echo !empty($banner['extra_btn_enabled']) ? '#10b981' : '#ccc'; ?>;"></span>
                </label>
                <label class="toggle-label" for="extra-btn-toggle">Включить дополнительную кнопку</label>
            </div>

            <div id="extra-btn-fields" style="<?php echo empty($banner['extra_btn_enabled']) ? 'display:none;' : ''; ?> margin-top:10px;">
                <div class="cookierus-form-group">
                    <label>Текст кнопки</label>
                    <input type="text" name="cookierus_settings[banner][extra_btn_text]" value="<?php echo esc_attr($banner['extra_btn_text'] ?? 'Подробнее'); ?>" class="large-text">
                </div>
                <div class="cookierus-form-group">
                    <label>URL кнопки</label>
                    <input type="text" name="cookierus_settings[banner][extra_btn_url]" value="<?php echo esc_attr($banner['extra_btn_url'] ?? ''); ?>" class="large-text" placeholder="https://example.com/privacy">
                </div>
                <div class="cookierus-form-group">
                    <label>Цвета кнопки</label>
                    <div class="cookierus-color-flex">
                        <div class="cookierus-color-item">Фон: <input type="color" name="cookierus_settings[banner][extra_btn_bg]" value="<?php echo esc_attr($banner['extra_btn_bg'] ?? '#6c757d'); ?>"></div>
                        <div class="cookierus-color-item">Текст: <input type="color" name="cookierus_settings[banner][extra_btn_text_color]" value="<?php echo esc_attr($banner['extra_btn_text_color'] ?? '#ffffff'); ?>"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Группа 4: Иконка cookie -->
        <div class="cookierus-settings-card" data-cr-settings-tab="content">
            <h4><span class="dashicons dashicons-smiley"></span> Иконка cookie в баннере</h4>
            <p class="description" style="margin-bottom:15px;">Отображать иконку cookie слева от текста баннера.</p>

            <div class="cookierus-toggle-row">
                <label class="cookierus-switch" style="display:inline-block; width:46px; height:24px;">
                    <input type="checkbox" name="cookierus_settings[banner][show_icon]" value="1" id="icon-toggle" <?php checked(1, $banner['show_icon'] ?? 0); ?>>
                    <span class="cookierus-slider" style="background-color:<?php echo !empty($banner['show_icon']) ? '#10b981' : '#ccc'; ?>;"></span>
                </label>
                <label class="toggle-label" for="icon-toggle">Показывать иконку cookie</label>
            </div>

            <div id="icon-size-fields" style="<?php echo empty($banner['show_icon']) ? 'display:none;' : ''; ?> margin-top:15px;">
                <div class="cookierus-form-group">
                    <label>Размер иконки</label>
                    <select name="cookierus_settings[banner][icon_size]" class="regular-text">
                        <option value="small" <?php selected('small', $banner['icon_size'] ?? ''); ?>>Маленький (32px)</option>
                        <option value="medium" <?php selected('medium', $banner['icon_size'] ?? 'medium'); ?>>Средний (48px)</option>
                        <option value="large" <?php selected('large', $banner['icon_size'] ?? ''); ?>>Большой (72px)</option>
                    </select>
                </div>
                <div style="padding: 10px; background: #f8faff; border-radius: 8px; text-align: center;">
                    <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/cookie-svgrepo-com.png'); ?>" alt="Cookie icon" style="height: 48px; opacity: 0.8;">
                    <p style="margin: 5px 0 0; font-size: 11px; color: #888;">Предпросмотр иконки</p>
                </div>
            </div>
        </div>

        <!-- Группа 5: Упоминания в окне согласия -->
        <div class="cookierus-settings-card" data-cr-settings-tab="content">
            <h4><span class="dashicons dashicons-megaphone"></span> Упоминания в «Настройках согласия»</h4>
            <p class="description" style="margin-bottom:15px;">Выберите готовые уведомления или добавьте свои. Они появятся у посетителя только во вкладке «Упоминания» и не влияют на согласие cookie.</p>

            <?php
            $mentions_admin = is_array($settings['mentions'] ?? null) ? $settings['mentions'] : [];
            $built_in_mentions_admin = array_replace([
                'russian_email_auth' => 0,
                'foreign_auth' => 0,
                'google_recaptcha' => 0,
                'google_analytics' => 0,
                'google_maps' => 0,
                'google_tag_manager' => 0,
            ], is_array($mentions_admin['built_in'] ?? null) ? $mentions_admin['built_in'] : []);
            $custom_mentions_admin = is_array($mentions_admin['custom'] ?? null) ? $mentions_admin['custom'] : [];
            ?>
            <div style="display:grid;gap:10px;">
                <label style="display:flex;align-items:flex-start;gap:10px;padding:11px 12px;background:#f8faff;border:1px solid #e1e9f5;border-radius:8px;">
                    <input type="checkbox" name="cookierus_settings[mentions][built_in][russian_email_auth]" value="1" <?php checked(1, $built_in_mentions_admin['russian_email_auth'] ?? 0); ?> style="margin-top:3px;">
                    <span>
                        <strong>Правила регистрации и входа</strong><br>
                        <small style="color:#6b7280;">Показывать короткое уведомление о соблюдении правил сайта и требований законодательства.</small>
                    </span>
                </label>
                <label style="display:flex;align-items:flex-start;gap:10px;padding:11px 12px;background:#f8faff;border:1px solid #e1e9f5;border-radius:8px;">
                    <input type="checkbox" name="cookierus_settings[mentions][built_in][foreign_auth]" value="1" <?php checked(1, $built_in_mentions_admin['foreign_auth'] ?? 0); ?> style="margin-top:3px;">
                    <span>
                        <strong>Безопасность и целостность сайта</strong><br>
                        <small style="color:#6b7280;">Показывать короткое уведомление о запрете вмешательства в работу сайта и обхода ограничений.</small>
                    </span>
                </label>
                <label style="display:flex;align-items:flex-start;gap:10px;padding:11px 12px;background:#f8faff;border:1px solid #e1e9f5;border-radius:8px;">
                    <input type="checkbox" name="cookierus_settings[mentions][built_in][google_recaptcha]" value="1" <?php checked(1, $built_in_mentions_admin['google_recaptcha'] ?? 0); ?> style="margin-top:3px;">
                    <span>
                        <strong>Google reCAPTCHA не используется</strong><br>
                        <small style="color:#6b7280;">Показывать пользователю уведомление о том, что Google reCAPTCHA на сайте не подключена.</small>
                    </span>
                </label>
                <label style="display:flex;align-items:flex-start;gap:10px;padding:11px 12px;background:#f8faff;border:1px solid #e1e9f5;border-radius:8px;">
                    <input type="checkbox" name="cookierus_settings[mentions][built_in][google_analytics]" value="1" <?php checked(1, $built_in_mentions_admin['google_analytics'] ?? 0); ?> style="margin-top:3px;">
                    <span>
                        <strong>Google Analytics не используется</strong><br>
                        <small style="color:#6b7280;">Показывать пользователю уведомление о том, что данные о посещаемости в Google Analytics не передаются.</small>
                    </span>
                </label>
                <label style="display:flex;align-items:flex-start;gap:10px;padding:11px 12px;background:#f8faff;border:1px solid #e1e9f5;border-radius:8px;">
                    <input type="checkbox" name="cookierus_settings[mentions][built_in][google_maps]" value="1" <?php checked(1, $built_in_mentions_admin['google_maps'] ?? 0); ?> style="margin-top:3px;">
                    <span>
                        <strong>Google Maps не используется</strong><br>
                        <small style="color:#6b7280;">Показывать пользователю уведомление о том, что карты Google на сайте не подключены.</small>
                    </span>
                </label>
                <label style="display:flex;align-items:flex-start;gap:10px;padding:11px 12px;background:#f8faff;border:1px solid #e1e9f5;border-radius:8px;">
                    <input type="checkbox" name="cookierus_settings[mentions][built_in][google_tag_manager]" value="1" <?php checked(1, $built_in_mentions_admin['google_tag_manager'] ?? 0); ?> style="margin-top:3px;">
                    <span>
                        <strong>Google Tag Manager не используется</strong><br>
                        <small style="color:#6b7280;">Показывать пользователю уведомление о том, что Google Tag Manager на сайте не подключён.</small>
                    </span>
                </label>
            </div>

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid #e5e7eb;">
                <strong style="display:block;margin-bottom:6px;">Свои упоминания</strong>
                <p class="description" style="margin:0 0 12px;">Например: «Целостность сайта» — «Не пытайтесь изменять системные файлы и нарушать работу сайта.»</p>
                <div id="cr-custom-mentions-list">
                    <?php foreach ($custom_mentions_admin as $custom_index => $custom_mention): ?>
                    <div class="cr-custom-item" data-custom-row>
                        <input type="hidden" name="cookierus_settings[mentions][custom][<?php echo esc_attr($custom_index); ?>][id]" value="<?php echo esc_attr($custom_mention['id'] ?? ''); ?>">
                        <input type="text" name="cookierus_settings[mentions][custom][<?php echo esc_attr($custom_index); ?>][title]" value="<?php echo esc_attr($custom_mention['title'] ?? ''); ?>" placeholder="Заголовок упоминания" class="large-text">
                        <textarea name="cookierus_settings[mentions][custom][<?php echo esc_attr($custom_index); ?>][description]" rows="2" placeholder="Описание упоминания" class="large-text" style="margin-top:8px;"><?php echo esc_textarea($custom_mention['description'] ?? ''); ?></textarea>
                        <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                            <label><input type="checkbox" name="cookierus_settings[mentions][custom][<?php echo esc_attr($custom_index); ?>][enabled]" value="1" <?php checked(1, $custom_mention['enabled'] ?? 1); ?>> Показывать посетителям</label>
                            <button type="button" class="button-link-delete cr-remove-custom">Удалить</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button" id="cr-add-custom-mention">+ Добавить упоминание</button>
                <template id="cr-custom-mention-template">
                    <div class="cr-custom-item" data-custom-row>
                        <input type="hidden" name="cookierus_settings[mentions][custom][__INDEX__][id]" value="">
                        <input type="text" name="cookierus_settings[mentions][custom][__INDEX__][title]" value="" placeholder="Заголовок упоминания" class="large-text">
                        <textarea name="cookierus_settings[mentions][custom][__INDEX__][description]" rows="2" placeholder="Описание упоминания" class="large-text" style="margin-top:8px;"></textarea>
                        <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                            <label><input type="checkbox" name="cookierus_settings[mentions][custom][__INDEX__][enabled]" value="1" checked> Показывать посетителям</label>
                            <button type="button" class="button-link-delete cr-remove-custom">Удалить</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- ─────────────────────────────────────────────────────
             БЛОК D — ВНЕШНИЙ ВИД
        ───────────────────────────────────────────────────── -->
        <div class="cr-settings-section-label" data-cr-settings-tab="appearance">
            <span class="dashicons dashicons-art"></span> Внешний вид и позиция
        </div>

        <!-- Группа 5: Общий дизайн -->
        <div class="cookierus-settings-card" data-cr-settings-tab="appearance">
            <h4><span class="dashicons dashicons-art"></span> Внешний вид баннера</h4>
            
            <div class="cookierus-form-group">
                <label>Цвета баннера</label>
                <div class="cookierus-color-flex">
                    <div class="cookierus-color-item">Фон: <input type="color" name="cookierus_settings[banner][bg_color]" value="<?php echo esc_attr($banner['bg_color'] ?? '#ffffff'); ?>"></div>
                    <div class="cookierus-color-item">Текст: <input type="color" name="cookierus_settings[banner][text_color]" value="<?php echo esc_attr($banner['text_color'] ?? '#333333'); ?>"></div>
                    <div class="cookierus-color-item">Ссылка: <input type="color" name="cookierus_settings[banner][link_color]" value="<?php echo esc_attr($banner['link_color'] ?? '#0760D2'); ?>"></div>
                </div>
            </div>

            <div class="cookierus-form-group">
                <label>Положение и форма</label>
                <div style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
                    <select name="cookierus_settings[banner][position]" style="flex:1; min-width:150px;">
                        <option value="bottom" <?php selected('bottom', $banner['position'] ?? ''); ?>>Снизу</option>
                        <option value="top" <?php selected('top', $banner['position'] ?? ''); ?>>Сверху</option>
                        <option value="bottom-left" <?php selected('bottom-left', $banner['position'] ?? ''); ?>>Снизу слева</option>
                        <option value="bottom-right" <?php selected('bottom-right', $banner['position'] ?? ''); ?>>Снизу справа</option>
                        <option value="center" <?php selected('center', $banner['position'] ?? ''); ?>>По центру (popup)</option>
                    </select>
                    <span>Скругление: <input type="number" name="cookierus_settings[banner][radius]" value="<?php echo esc_attr($banner['radius'] ?? 8); ?>" style="width:60px;"> px</span>
                </div>
                <p class="description" style="margin-top:8px;">Режим «По центру» показывает согласие как popup поверх страницы. Затемнение можно включить для любого положения баннера.</p>
            </div>

            <div class="cookierus-form-group">
                <label>
                    <input type="checkbox" name="cookierus_settings[banner][overlay_enabled]" value="1" <?php checked(1, $banner['overlay_enabled'] ?? 1); ?>>
                    Затемнять страницу вокруг баннера
                </label>
                <p class="description" style="margin-top:5px;">Затемняет страницу вокруг баннера при любом положении: снизу, сверху, в углу или по центру. Пользователь сразу видит, что сначала нужно выбрать настройки cookie.</p>
            </div>

            <div class="cookierus-form-group">
                <label>Шрифт баннера</label>
                <select name="cookierus_settings[banner][font_family]" class="regular-text" style="width:100%;">
                    <option value="inherit" <?php selected('inherit', $banner['font_family'] ?? 'inherit'); ?>>Наследовать от темы</option>
                    <option value="sans-serif" <?php selected('sans-serif', $banner['font_family'] ?? ''); ?>>Без засечек (sans-serif)</option>
                    <option value="serif" <?php selected('serif', $banner['font_family'] ?? ''); ?>>С засечками (serif)</option>
                    <option value="monospace" <?php selected('monospace', $banner['font_family'] ?? ''); ?>>Моноширинный (mono)</option>
                </select>
            </div>

            <div class="cookierus-form-group">
                <label>Тень баннера</label>
                <select name="cookierus_settings[banner][banner_shadow]" class="regular-text" style="width:100%;">
                    <option value="none" <?php selected('none', $banner['banner_shadow'] ?? ''); ?>>Нет тени</option>
                    <option value="light" <?php selected('light', $banner['banner_shadow'] ?? ''); ?>>Лёгкая</option>
                    <option value="medium" <?php selected('medium', $banner['banner_shadow'] ?? 'medium'); ?>>Средняя</option>
                    <option value="heavy" <?php selected('heavy', $banner['banner_shadow'] ?? ''); ?>>Тёмная</option>
                </select>
            </div>

            <div class="cookierus-form-group">
                <label>Анимация появления</label>
                <select name="cookierus_settings[banner][animation]" class="regular-text" style="width:100%;">
                    <option value="slide"  <?php selected('slide',  $banner['animation'] ?? ''); ?>>Слайд (снизу вверх)</option>
                    <option value="fade"   <?php selected('fade',   $banner['animation'] ?? ''); ?>>Затухание (Fade In)</option>
                    <option value="bounce" <?php selected('bounce', $banner['animation'] ?? ''); ?>>Прыжок (эластично)</option>
                    <option value="zoom"   <?php selected('zoom',   $banner['animation'] ?? ''); ?>>Масштаб (Zoom In)</option>
                    <option value="flip"   <?php selected('flip',   $banner['animation'] ?? ''); ?>>Переворот (Flip)</option>
                </select>
            </div>

            <div class="cookierus-form-group">
                <label>Внутренние отступы баннера (padding)</label>
                <p style="margin:0 0 10px;font-size:12px;color:#6b7280;">Размер пространства между краем баннера и его содержимым. По умолчанию — 20 px (верх/низ) и 24 px (лево/право).</p>
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="width:80px;font-size:12px;color:#555;padding:5px 0;">Верх</td>
                        <td>
                            <div class="cookierus-range-row">
                                <input type="range" min="0" max="60" name="cookierus_settings[banner][pad_top]" id="range-pad-top" value="<?php echo intval($banner['pad_top'] ?? 20); ?>" oninput="document.getElementById('val-pad-top').textContent = this.value + 'px'">
                                <span class="range-value" id="val-pad-top"><?php echo intval($banner['pad_top'] ?? 20); ?>px</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px;color:#555;padding:5px 0;">Право</td>
                        <td>
                            <div class="cookierus-range-row">
                                <input type="range" min="0" max="60" name="cookierus_settings[banner][pad_right]" id="range-pad-right" value="<?php echo intval($banner['pad_right'] ?? 24); ?>" oninput="document.getElementById('val-pad-right').textContent = this.value + 'px'">
                                <span class="range-value" id="val-pad-right"><?php echo intval($banner['pad_right'] ?? 24); ?>px</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px;color:#555;padding:5px 0;">Низ</td>
                        <td>
                            <div class="cookierus-range-row">
                                <input type="range" min="0" max="60" name="cookierus_settings[banner][pad_bottom]" id="range-pad-bottom" value="<?php echo intval($banner['pad_bottom'] ?? 20); ?>" oninput="document.getElementById('val-pad-bottom').textContent = this.value + 'px'">
                                <span class="range-value" id="val-pad-bottom"><?php echo intval($banner['pad_bottom'] ?? 20); ?>px</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px;color:#555;padding:5px 0;">Лево</td>
                        <td>
                            <div class="cookierus-range-row">
                                <input type="range" min="0" max="60" name="cookierus_settings[banner][pad_left]" id="range-pad-left" value="<?php echo intval($banner['pad_left'] ?? 24); ?>" oninput="document.getElementById('val-pad-left').textContent = this.value + 'px'">
                                <span class="range-value" id="val-pad-left"><?php echo intval($banner['pad_left'] ?? 24); ?>px</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="cookierus-form-group">
                <label>Пользовательский CSS</label>
                <textarea name="cookierus_settings[banner][custom_css]" rows="5" class="large-text" style="font-family:monospace; font-size:12px;"><?php echo esc_textarea($banner['custom_css'] ?? ''); ?></textarea>
            </div>
        </div>

        <!-- Группа 6: Настройки кнопок -->
        <div class="cookierus-settings-card" data-cr-settings-tab="appearance">
            <h4><span class="dashicons dashicons-admin-tools"></span> Стиль кнопок</h4>

            <div class="cookierus-form-group">
                <label>Скругление кнопок</label>
                <div class="cookierus-range-row">
                    <input type="range" min="0" max="30" name="cookierus_settings[banner][btn_radius]" id="range-btn-radius" value="<?php echo esc_attr($banner['btn_radius'] ?? 6); ?>" oninput="document.getElementById('val-btn-radius').textContent = this.value + 'px'">
                    <span class="range-value" id="val-btn-radius"><?php echo esc_html($banner['btn_radius'] ?? 6); ?>px</span>
                </div>
            </div>

            <div class="cookierus-form-group">
                <label>Размер шрифта кнопок</label>
                <div class="cookierus-range-row">
                    <input type="range" min="11" max="20" name="cookierus_settings[banner][btn_font_size]" id="range-btn-font" value="<?php echo esc_attr($banner['btn_font_size'] ?? 14); ?>" oninput="document.getElementById('val-btn-font').textContent = this.value + 'px'">
                    <span class="range-value" id="val-btn-font"><?php echo esc_html($banner['btn_font_size'] ?? 14); ?>px</span>
                </div>
            </div>

            <div class="cookierus-form-group">
                <label>Эффект при наведении на кнопки</label>
                <select name="cookierus_settings[banner][btn_hover]" class="regular-text" style="width:100%;">
                    <option value="lift"    <?php selected('lift',    $banner['btn_hover'] ?? 'lift'); ?>>⬆️ Подъём + тень (рекомендуется)</option>
                    <option value="opacity" <?php selected('opacity', $banner['btn_hover'] ?? '');     ?>>👁 Осветление (opacity)</option>
                    <option value="darken"  <?php selected('darken',  $banner['btn_hover'] ?? '');     ?>>🌑 Затемнение</option>
                    <option value="scale"   <?php selected('scale',   $banner['btn_hover'] ?? '');     ?>>🔍 Масштаб (увеличение)</option>
                    <option value="none"    <?php selected('none',    $banner['btn_hover'] ?? '');     ?>>⛔ Без эффекта</option>
                </select>
            </div>
        </div>

        <div class="cookierus-settings-card" data-cr-settings-tab="appearance">
            <h4><span class="dashicons dashicons-menu"></span> Расположение кнопок</h4>
            <div class="cookierus-form-group">
                <label>Как показывать кнопки в баннере</label>
                <select name="cookierus_settings[banner][btn_layout]" class="regular-text" style="width:100%;">
                    <option value="column" <?php selected('column', $banner['btn_layout'] ?? 'column'); ?>>В столбик (по умолчанию)</option>
                    <option value="row"    <?php selected('row',    $banner['btn_layout'] ?? '');       ?>>В ряд (горизонтально, баннер шире)</option>
                </select>
                <p class="description" style="margin-top:5px;">Горизонтальный вариант делает кнопки компактнее и выравнивает их в одну строку. На очень узких экранах они автоматически переходят в столбик.</p>
            </div>
        </div>

        <!-- ─────────────────────────────────────────────────────
             БЛОК E — ПОВЕДЕНИЕ
        ───────────────────────────────────────────────────── -->
        <div class="cr-settings-section-label" data-cr-settings-tab="behavior">
            <span class="dashicons dashicons-controls-repeat"></span> Поведение
        </div>

        <!-- Группа 7: Поведение баннера -->
        <div class="cookierus-settings-card" data-cr-settings-tab="behavior">
            <h4><span class="dashicons dashicons-schedule"></span> Поведение баннера</h4>

            <div class="cookierus-form-group">
                <label>Повторный показ баннера</label>
                <select name="cookierus_settings[banner][repeat_show]" class="regular-text" style="width:100%;">
                    <option value="never" <?php selected('never', $banner['repeat_show'] ?? 'never'); ?>>Не показывать снова</option>
                    <option value="7days" <?php selected('7days', $banner['repeat_show'] ?? ''); ?>>Через 7 дней</option>
                    <option value="30days" <?php selected('30days', $banner['repeat_show'] ?? ''); ?>>Через 30 дней</option>
                    <option value="always" <?php selected('always', $banner['repeat_show'] ?? ''); ?>>При каждом посещении</option>
                </select>
                <p class="description" style="margin-top:5px;">Определяет, когда пользователю снова показать баннер после того, как он уже видел его.</p>
            </div>

            <div class="cookierus-form-group" style="border-top: 1px solid #f0f0f1; padding-top: 15px;">
                <label>Сворачивание в иконку</label>
                <div class="cookierus-toggle-row">
                    <label class="cookierus-switch" style="display:inline-block; width:46px; height:24px;">
                        <input type="checkbox" name="cookierus_settings[banner][allow_minimize]" value="1" id="minimize-toggle" <?php checked(1, $banner['allow_minimize'] ?? 0); ?>>
                        <span class="cookierus-slider" style="background-color:<?php echo !empty($banner['allow_minimize']) ? '#10b981' : '#ccc'; ?>;"></span>
                    </label>
                    <label class="toggle-label" for="minimize-toggle">Разрешить сворачивать баннер</label>
                </div>
                <p class="description" style="margin-top:5px;">Если включено, пользователь может нажать × и свернуть баннер в маленькую круглую иконку cookie. При клике на иконку баннер возвращается.</p>
            </div>

        </div>

        <!-- ─────────────────────────────────────────────────────
             БЛОК F — МОДАЛЬНОЕ ОКНО «НАСТРОИТЬ»
        ───────────────────────────────────────────────────── -->
        <div class="cr-settings-section-label" data-cr-settings-tab="categories">
            <span class="dashicons dashicons-admin-settings"></span> Модальное окно «Настроить»
        </div>

        <!-- Группа 8: Категории -->
        <div class="cookierus-settings-card cr-modal-settings-card" data-cr-settings-tab="categories">
            <h4><span class="dashicons dashicons-admin-generic"></span> Категории согласия</h4>
            <p class="description" style="margin-bottom:15px;">Выберите категории, которые пользователь может настроить, и укажите используемые сервисы.</p>
            
            <div class="cookierus-sections-settings">
                <?php 
                $sects = [
                    'functional' => 'Функциональные',
                    'analytics' => 'Аналитические',
                    'performance' => 'Производительность',
                    'advertising' => 'Маркетинговые'
                ];
                foreach ($sects as $key => $label):
                ?>
                <div class="section-row" style="margin-bottom:15px; padding:10px; background:#f9f9f9; border-radius:8px;">
                    <label style="margin-bottom:0;">
                        <input type="checkbox" name="cookierus_settings[sections][<?php echo $key; ?>]" value="1" <?php checked(1, $sections[$key] ?? 0); ?> class="section-toggle"> 
                        <strong><?php echo $label; ?></strong>
                    </label>
                    <div class="section-desc" style="<?php echo empty($sections[$key]) ? 'display:none;' : ''; ?>">
                        <?php if ($key === 'functional'): ?>
                            <label style="display:block;font-size:12px;margin-bottom:5px;color:#666;">Срок хранения (дней):</label>
                            <input type="number" min="1" max="3650" name="cookierus_settings[sections][functional_retention_days]" value="<?php echo esc_attr($sections['functional_retention_days'] ?? 365); ?>" class="small-text">
                        <?php elseif ($key === 'analytics' || $key === 'advertising'): ?>
                            <div style="font-size:12px; margin-bottom:5px; color:#666;">Описание категории:</div>
                            <input type="text" name="cookierus_settings[sections][<?php echo esc_attr($key); ?>_desc]" value="<?php echo esc_attr($sections[$key.'_desc'] ?? ''); ?>" class="large-text">
                            <p style="margin:8px 0 0;font-size:11px;color:#777;">Выбор конкретных сервисов находится во вкладке «Сервисы и цели».</p>
                        <?php else: ?>
                            <div style="font-size:12px; margin-bottom:5px; color:#666;">Описание категории:</div>
                            <input type="text" name="cookierus_settings[sections][<?php echo $key; ?>_desc]" value="<?php echo esc_attr($sections[$key.'_desc'] ?? ''); ?>" class="large-text">
                        <?php endif; ?>
                        <?php if ($key !== 'functional'): ?>
                            <label style="display:block;font-size:12px;margin:10px 0 5px;color:#666;">Ссылка на политику конфиденциальности сервиса:</label>
                            <input type="url" name="cookierus_settings[sections][<?php echo esc_attr($key); ?>_policy_url]" value="<?php echo esc_attr($sections[$key.'_policy_url'] ?? ''); ?>" class="large-text" placeholder="https://example.ru/privacy">
                        <?php else: ?>
                            <label style="display:block;font-size:12px;margin:10px 0 5px;color:#666;">Ссылка на политику конфиденциальности сервиса:</label>
                            <input type="url" name="cookierus_settings[sections][functional_policy_url]" value="<?php echo esc_attr($sections['functional_policy_url'] ?? ''); ?>" class="large-text" placeholder="https://example.ru/privacy">
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="margin-top:18px;padding-top:16px;border-top:1px solid #e5e7eb;">
                <strong style="display:block;margin-bottom:6px;">Свои категории</strong>
                <p class="description" style="margin:0 0 12px;">Добавьте свой пункт в модальное окно «Настроить»: укажите название, описание и ссылку на политику сервиса.</p>
                <div id="cr-custom-categories-list">
                    <?php
                    $custom_categories_admin = is_array($settings['custom_categories'] ?? null)
                        ? $settings['custom_categories']
                        : [];
                    foreach ($custom_categories_admin as $custom_index => $custom_category):
                    ?>
                    <div class="cr-custom-item" data-custom-row>
                        <input type="hidden" name="cookierus_settings[custom_categories][<?php echo esc_attr($custom_index); ?>][id]" value="<?php echo esc_attr($custom_category['id'] ?? ''); ?>">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            <input type="text" name="cookierus_settings[custom_categories][<?php echo esc_attr($custom_index); ?>][title]" value="<?php echo esc_attr($custom_category['title'] ?? ''); ?>" placeholder="Название категории" class="large-text">
                            <input type="url" name="cookierus_settings[custom_categories][<?php echo esc_attr($custom_index); ?>][policy_url]" value="<?php echo esc_attr($custom_category['policy_url'] ?? ''); ?>" placeholder="Ссылка на политику" class="large-text">
                        </div>
                        <textarea name="cookierus_settings[custom_categories][<?php echo esc_attr($custom_index); ?>][description]" rows="2" placeholder="Краткое описание категории" class="large-text" style="margin-top:8px;"><?php echo esc_textarea($custom_category['description'] ?? ''); ?></textarea>
                        <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                            <label><input type="checkbox" name="cookierus_settings[custom_categories][<?php echo esc_attr($custom_index); ?>][enabled]" value="1" <?php checked(1, $custom_category['enabled'] ?? 1); ?>> Показывать в настройках</label>
                            <button type="button" class="button-link-delete cr-remove-custom">Удалить</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button" id="cr-add-custom-category">+ Добавить свою категорию</button>
                <template id="cr-custom-category-template">
                    <div class="cr-custom-item" data-custom-row>
                        <input type="hidden" name="cookierus_settings[custom_categories][__INDEX__][id]" value="">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            <input type="text" name="cookierus_settings[custom_categories][__INDEX__][title]" value="" placeholder="Название категории" class="large-text">
                            <input type="url" name="cookierus_settings[custom_categories][__INDEX__][policy_url]" value="" placeholder="Ссылка на политику" class="large-text">
                        </div>
                        <textarea name="cookierus_settings[custom_categories][__INDEX__][description]" rows="2" placeholder="Краткое описание категории" class="large-text" style="margin-top:8px;"></textarea>
                        <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                            <label><input type="checkbox" name="cookierus_settings[custom_categories][__INDEX__][enabled]" value="1" checked> Показывать в настройках</label>
                            <button type="button" class="button-link-delete cr-remove-custom">Удалить</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <!-- ─────────────────────────────────────────────────────
             БЛОК G — ТРЕКЕРЫ И ЦЕЛИ
        ───────────────────────────────────────────────────── -->
        <div class="cr-settings-section-label" data-cr-settings-tab="services">
            <span class="dashicons dashicons-shield"></span> Трекеры и цели обработки
        </div>

        <!-- Группа 9: Блокировка трекеров -->
        <div class="cookierus-settings-card cr-tracker-settings-card" data-cr-settings-tab="services">
            <h4><span class="dashicons dashicons-shield"></span> Блокировка трекеров до согласия</h4>
            <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);border-left:4px solid #f59e0b;padding:12px 16px;border-radius:0 8px 8px 0;margin-bottom:18px;">
                <strong style="color:#92400e;">⚠️ Требование 152-ФЗ и РКН</strong>
                <p style="margin:5px 0 0;font-size:12px;color:#78350f;">Счётчики аналитики и маркетинговые пиксели должны загружаться <strong>только после</strong> нажатия пользователем кнопки «Принять». До согласия сторонние скрипты не загружаются.</p>
            </div>
            <p class="description" style="margin-bottom:15px;">Укажите ID трекеров и включите соответствующий сервис выше. В штатном режиме CookieRus загружает их только после согласия пользователя на «Аналитические» или «Маркетинговые» cookie.</p>

            <?php $trackers = $settings['trackers'] ?? []; ?>

            <div class="cookierus-btn-settings-row" style="margin-bottom:20px;">
                <h5>Что использует сайт</h5>
                <p style="margin:0 0 12px;font-size:12px;color:#666;">Отметьте только те сервисы, которые действительно подключены на сайте. Они начнут работать после согласия пользователя на соответствующую категорию.</p>
                <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px 18px;">
                    <?php foreach (['yandex_metrika' => 'Яндекс.Метрика', 'mailru_counters' => 'Счётчики Mail.ru', 'callibri' => 'Callibri', 'vk_ads' => 'VK Реклама', 'yandex_ads' => 'Яндекс.Реклама'] as $service_key => $service_label): ?>
                        <?php $service_group = in_array($service_key, ['yandex_metrika', 'mailru_counters', 'callibri'], true) ? $analytics_services : $advertising_services; ?>
                        <label style="display:flex;align-items:center;gap:8px;margin:0;padding:8px 10px;background:#fff;border:1px solid #e1e9f5;border-radius:7px;">
                            <input type="checkbox" name="cookierus_settings[sections][<?php echo in_array($service_key, ['yandex_metrika', 'mailru_counters', 'callibri'], true) ? 'analytics_services' : 'advertising_services'; ?>][<?php echo esc_attr($service_key); ?>]" value="1" <?php checked(1, $service_group[$service_key] ?? 0); ?>>
                            <?php echo esc_html($service_label); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="cookierus-form-group">
                <label>Яндекс.Метрика — ID счётчика</label>
                <input type="text" name="cookierus_settings[trackers][ym_id]" value="<?php echo esc_attr($trackers['ym_id'] ?? ''); ?>" class="regular-text" placeholder="12345678">
                <p style="margin:3px 0 0;font-size:11px;color:#888;">Вставьте только число из кабинета Яндекс.Метрики: Метрика → нужный счётчик → его номер. Код начнёт работать после согласия на «Аналитические».</p>
            </div>

            <div class="cookierus-form-group">
                <label>Счётчик Mail.ru — ID</label>
                <input type="text" name="cookierus_settings[trackers][mailru_id]" value="<?php echo esc_attr($trackers['mailru_id'] ?? ''); ?>" class="regular-text" placeholder="1234567">
                <p style="margin:3px 0 0;font-size:11px;color:#888;">Укажите ID счётчика Mail.ru (Top). Код загрузится после согласия на «Аналитические» и включения «Счётчики Mail.ru».</p>
            </div>

            <div class="cookierus-form-group">
                <label>VK Пиксель</label>
                <input type="text" name="cookierus_settings[trackers][vk_id]" value="<?php echo esc_attr($trackers['vk_id'] ?? ''); ?>" class="regular-text" placeholder="VK-RTRG-XXXXXXX-XXXX">
                <p style="margin:3px 0 0;font-size:11px;color:#888;">Формат: VK-RTRG-XXXXXXX-XXXX</p>
            </div>

            <div class="cookierus-form-group">
                <label>Callibri — готовый JavaScript-код</label>
                <textarea name="cookierus_settings[trackers][callibri_code]" rows="6" class="large-text" placeholder="<script>...</script>"><?php echo esc_textarea($trackers['callibri_code'] ?? ''); ?></textarea>
                <p style="margin:3px 0 0;font-size:11px;color:#888;">Скопируйте в кабинете Callibri готовый JavaScript-код целиком, обычно вместе с тегами <code>&lt;script&gt;...&lt;/script&gt;</code>. Не вставляйте только номер проекта. Код не исполняется до согласия на «Аналитические» и включения «Колибри».</p>
            </div>

            <div class="cookierus-form-group">
                <label>Дополнительные домены для блокировки</label>
                <textarea name="cookierus_settings[security][blocked_domains]" rows="3" class="large-text" placeholder="tracker.example.ru&#10;pixel.example.com"><?php echo esc_textarea($settings['security']['blocked_domains'] ?? ''); ?></textarea>
                <p style="margin:3px 0 0;font-size:11px;color:#888;">По одному домену или через запятую. Известные аналитические и рекламные домены уже блокируются автоматически.</p>
            </div>

            <div class="cookierus-toggle-row" style="border-top:1px solid #f0f0f1;padding-top:15px;">
                <label class="cookierus-switch" style="display:inline-block; width:46px; height:24px;">
                    <input type="checkbox" name="cookierus_settings[security][russian_email_auth_block]" value="1" id="russian-email-auth-toggle" <?php checked(1, $settings['security']['russian_email_auth_block'] ?? 0); ?>>
                    <span class="cookierus-slider" style="background-color:<?php echo !empty($settings['security']['russian_email_auth_block']) ? '#10b981' : '#ccc'; ?>;"></span>
                </label>
                <label class="toggle-label" for="russian-email-auth-toggle">Ограничить регистрацию и вход российскими email-доменами</label>
            </div>
            <p class="description">Если включено, регистрация, восстановление доступа и вход по email разрешены только для российских почтовых сервисов и доменов .ru, .su или .рф. Вход по имени пользователя не проверяется по email и остаётся доступен. Администраторы также могут войти по своему email.</p>

            <?php $allow_analytics_before_consent = !empty($settings['security']['allow_analytics_before_consent']); ?>
            <div style="margin-top:18px;padding:14px 16px;border:1px solid <?php echo $allow_analytics_before_consent ? '#ef4444' : '#f59e0b'; ?>;background:<?php echo $allow_analytics_before_consent ? '#fef2f2' : '#fffbeb'; ?>;border-radius:8px;">
                <label style="display:flex;align-items:flex-start;gap:10px;font-weight:600;color:#92400e;">
                    <input type="checkbox" name="cookierus_settings[security][allow_analytics_before_consent]" value="1" <?php checked(1, $allow_analytics_before_consent); ?> style="margin-top:2px;">
                    Разрешить загрузку аналитики до согласия пользователя
                </label>
                <p style="margin:8px 0 0;font-size:12px;color:#7c2d12;">⚠️ Это осознанное исключение: Яндекс.Метрика, Mail.ru и Callibri могут начать загрузку до выбора пользователя и могут обрабатывать данные без согласия. Включайте только после юридической проверки. Маркетинговые сервисы эта настройка не разрешает.</p>
            </div>

            <div class="cookierus-toggle-row" style="border-top:1px solid #f0f0f1;padding-top:15px;">
                <label class="cookierus-switch" style="display:inline-block; width:46px; height:24px;">
                    <input type="checkbox" name="cookierus_settings[security][foreign_auth_block]" value="1" id="foreign-auth-toggle" <?php checked(1, $settings['security']['foreign_auth_block'] ?? 0); ?>>
                    <span class="cookierus-slider" style="background-color:<?php echo !empty($settings['security']['foreign_auth_block']) ? '#10b981' : '#ccc'; ?>;"></span>
                </label>
                <label class="toggle-label" for="foreign-auth-toggle">Запретить авторизацию через иностранные сервисы</label>
            </div>
            <p class="description">Блокирует кнопки и типовые OAuth-маршруты Google, Apple ID, Facebook, Microsoft и других иностранных провайдеров, а также фильтры популярных social-login плагинов.</p>

            <div style="background:#f0fdf4;border:1px solid #86efac;padding:12px 16px;border-radius:8px;margin-top:10px;">
                <p style="margin:0;font-size:12px;color:#166534;">✅ <strong>Как это работает:</strong> CookieRus блокирует известные трекинговые домены в HTML, динамическом DOM и сетевых API. После согласия разрешаются только домены выбранных категорий. Это также покрывает код из head, footer, темы и сторонних плагинов.</p>
            </div>
        </div>

        <!-- Группа 10: Цели обработки -->
        <div class="cookierus-settings-card cr-goals-settings-card" data-cr-settings-tab="services">
            <h4><span class="dashicons dashicons-list-view"></span> Цели обработки данных (вкладка «Цели»)</h4>
            <p class="description" style="margin-bottom:15px;">Выберите, какие цели обработки отображать пользователю во вкладке «Цели» в окне настроек. Состояние каждой цели сохраняется отдельно и используется как исходное значение для пользователя.</p>

            <?php $goals_cfg = $settings['goals'] ?? []; ?>

            <?php
            $goals_list_admin = [
                'storage'              => ['Хранение и доступ к данным на устройстве',  'Основная цель — запись и чтение cookie. Необходима для работы большинства функций сайта (ст. 6 ч. 1 п. 5 152-ФЗ).'],
                'analytics'            => ['Аналитика и статистика',                      'Сбор обезличенной статистики посещаемости и поведения пользователей для улучшения сайта.'],
                'personalized_content' => ['Персонализация контента',                     'Подбор материалов сайта (статей, рекомендаций) с учётом предпочтений пользователя.'],
                'personalized'         => ['Персонализированная реклама',                  'Показ рекламы на основе профиля интересов пользователя.'],
                'retargeting'          => ['Ретаргетинг',                                  'Повторный показ рекламы посетителям сайта на сторонних площадках.'],
                'ad_measure'           => ['Оценка эффективности рекламы',                 'Измерение охвата и результативности рекламных кампаний.'],
                'content_measure'      => ['Оценка эффективности контента',                'Анализ полезности представленного на сайте контента для пользователей.'],
                'profiling'            => ['Создание профиля пользователя',                'Формирование поведенческого профиля на основе истории посещений.'],
                'geolocation'          => ['Геолокация',                                   'Использование приблизительного местоположения для регионального контента или рекламы.'],
                'third_party'          => ['Передача данных третьим лицам',                'Предоставление данных партнёрам и поставщикам услуг (ст. 6 ч. 3, ст. 18 152-ФЗ).'],
                'development'          => ['Разработка и совершенствование сервисов',      'Использование данных для улучшения функционала и создания новых возможностей.'],
                 'limited_ads'          => ['Ограниченная реклама',                          'Показ контекстной рекламы без создания профиля интересов пользователя.'],
            ];
            foreach ($goals_list_admin as $key => [$title, $hint]):
            ?>
            <div style="display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid #f0f0f1;">
                <input type="checkbox" name="cookierus_settings[goals][<?php echo esc_attr($key); ?>]" value="1" id="goal-<?php echo esc_attr($key); ?>" <?php checked(1, $goals_cfg[$key] ?? 0); ?> style="margin-top:3px;">
                <div>
                    <label for="goal-<?php echo esc_attr($key); ?>" style="font-weight:600;cursor:pointer;display:block;"><?php echo esc_html($title); ?></label>
                    <span style="font-size:11px;color:#888;"><?php echo esc_html($hint); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
            <div style="margin-top:18px;padding-top:16px;border-top:1px solid #e5e7eb;">
                <strong style="display:block;margin-bottom:6px;">Свои цели обработки</strong>
                <p class="description" style="margin:0 0 12px;">Добавьте собственную цель: задайте заголовок и описание, а затем включите или выключите её отображение в модальном окне.</p>
                <div id="cr-custom-goals-list">
                    <?php
                    $custom_goals_admin = is_array($settings['custom_goals'] ?? null)
                        ? $settings['custom_goals']
                        : [];
                    foreach ($custom_goals_admin as $custom_index => $custom_goal):
                    ?>
                    <div class="cr-custom-item" data-custom-row>
                        <input type="hidden" name="cookierus_settings[custom_goals][<?php echo esc_attr($custom_index); ?>][id]" value="<?php echo esc_attr($custom_goal['id'] ?? ''); ?>">
                        <input type="text" name="cookierus_settings[custom_goals][<?php echo esc_attr($custom_index); ?>][title]" value="<?php echo esc_attr($custom_goal['title'] ?? ''); ?>" placeholder="Заголовок цели" class="large-text">
                        <textarea name="cookierus_settings[custom_goals][<?php echo esc_attr($custom_index); ?>][description]" rows="2" placeholder="Описание цели" class="large-text" style="margin-top:8px;"><?php echo esc_textarea($custom_goal['description'] ?? ''); ?></textarea>
                        <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                            <label><input type="checkbox" name="cookierus_settings[custom_goals][<?php echo esc_attr($custom_index); ?>][enabled]" value="1" <?php checked(1, $custom_goal['enabled'] ?? 1); ?>> Показывать в настройках</label>
                            <button type="button" class="button-link-delete cr-remove-custom">Удалить</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button" id="cr-add-custom-goal">+ Добавить свою цель</button>
                <template id="cr-custom-goal-template">
                    <div class="cr-custom-item" data-custom-row>
                        <input type="hidden" name="cookierus_settings[custom_goals][__INDEX__][id]" value="">
                        <input type="text" name="cookierus_settings[custom_goals][__INDEX__][title]" value="" placeholder="Заголовок цели" class="large-text">
                        <textarea name="cookierus_settings[custom_goals][__INDEX__][description]" rows="2" placeholder="Описание цели" class="large-text" style="margin-top:8px;"></textarea>
                        <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                            <label><input type="checkbox" name="cookierus_settings[custom_goals][__INDEX__][enabled]" value="1" checked> Показывать в настройках</label>
                            <button type="button" class="button-link-delete cr-remove-custom">Удалить</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; text-align: center; background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e1e9f5;">
        <?php submit_button('Сохранить все изменения', 'button-primary button-hero', 'submit', false); ?>
    </div>
</form>

<script>
jQuery(document).ready(function($) {
    // ── Внутренние вкладки страницы настроек ──────────────
    var $settingsItems = $('[data-cr-settings-tab]');
    var $settingsTabs = $('.cr-settings-subtab');

    function activateSettingsTab(tab) {
        $settingsItems.each(function() {
            var isVisible = $(this).attr('data-cr-settings-tab') === tab;
            this.hidden = !isVisible;
        });
        $settingsTabs.each(function() {
            var isActive = $(this).attr('data-cr-settings-tab-target') === tab;
            $(this).toggleClass('is-active', isActive)
                .attr('aria-selected', isActive ? 'true' : 'false');
        });
    }

    $settingsTabs.on('click', function() {
        activateSettingsTab($(this).attr('data-cr-settings-tab-target'));
    });
    activateSettingsTab('content');

    // ── Прочие тоглы ────────────────────────────────────
    $('.section-toggle').on('change', function() {
        $(this).closest('.section-row').find('.section-desc').slideToggle(200);
    });
    $('#extra-btn-toggle').on('change', function() {
        $('#extra-btn-fields').slideToggle(200);
        $(this).next('.cookierus-slider').css('background-color', this.checked ? '#10b981' : '#ccc');
    });
    $('#icon-toggle').on('change', function() {
        $('#icon-size-fields').slideToggle(200);
        $(this).next('.cookierus-slider').css('background-color', this.checked ? '#10b981' : '#ccc');
    });
    $('#minimize-toggle').on('change', function() {
        $(this).next('.cookierus-slider').css('background-color', this.checked ? '#10b981' : '#ccc');
    });
    function addCustomSettingRow(buttonId, templateId, listId) {
        $('#' + buttonId).on('click', function() {
            var template = document.getElementById(templateId);
            if (!template) return;
            var index = 'new_' + Date.now();
            var html = template.innerHTML.split('__INDEX__').join(index);
            $('#' + listId).append(html);
        });
    }
    addCustomSettingRow('cr-add-custom-category', 'cr-custom-category-template', 'cr-custom-categories-list');
    addCustomSettingRow('cr-add-custom-goal', 'cr-custom-goal-template', 'cr-custom-goals-list');
    addCustomSettingRow('cr-add-custom-mention', 'cr-custom-mention-template', 'cr-custom-mentions-list');
    $(document).on('click', '.cr-remove-custom', function() {
        $(this).closest('[data-custom-row]').remove();
    });

    // ── Предпросмотр ─────────────────────────────────────
    var $toggle  = $('#cookierus-preview-toggle');
    var $area    = $('#cookierus-preview-area');
    var $inner   = $('#cr-preview-inner');

    $toggle.on('change', function() {
        if (this.checked) {
            $area.addClass('active');
            updatePreview();
        } else {
            $area.removeClass('active');
        }
    });

    function val(name)   { return $('[name="cookierus_settings[banner][' + name + ']"]').val() || ''; }
    function chk(name)   { return $('[name="cookierus_settings[banner][' + name + ']"]').is(':checked'); }
    function idVal(id)   { return $('#' + id).val() || ''; }
    function idChk(id)   { return $('#' + id).is(':checked'); }

    // Карта позиций → css-класс превью
    var posMap = {
        'bottom'       : 'cr-pos-bottom',
        'top'          : 'cr-pos-top',
        'bottom-left'  : 'cr-pos-bottom-left',
        'bottom-right' : 'cr-pos-bottom-right',
        'center'       : 'cr-pos-center'
    };

    function updatePreview() {
        // Контент
        var title    = val('title')    || 'Мы уважаем вашу конфиденциальность';
        var text     = val('text')     || 'Мы используем файлы cookie...';
        var linkText = val('link_text');
        var linkUrl  = val('link_url');
        var linkColor = val('link_color') || '#0760D2';

        // Дизайн
        var bgColor   = val('bg_color')   || '#ffffff';
        var textColor = val('text_color') || '#333333';
        var radius    = val('radius')     || '8';
        var pos       = val('position')   || 'bottom';
        var maxW      = parseInt(val('max_width') || '455', 10);

        // Кнопки
        var btnRadius   = idVal('range-btn-radius') || '6';
        var btnFontSize = idVal('range-btn-font')   || '14';

        var btnAccept      = val('btn_accept')    || 'Принять все';
        var btnAcceptBg    = val('btn_bg')         || '#0760D2';
        var btnAcceptTxt   = val('btn_text')       || '#ffffff';

        var btnDecline     = val('btn_decline')    || 'Отклонить';
        var btnDeclineBg   = val('btn_decline_bg') || '#f0f0f1';
        var btnDeclineTxt  = val('btn_decline_text') || '#333333';

        var btnSettings    = val('btn_settings')       || 'Настроить';
        var btnSettingsBg  = val('btn_settings_bg')    || '#ffffff';
        var btnSettingsTxt = val('btn_settings_text')  || '#333333';

        var extraOn  = idChk('extra-btn-toggle');
        var extraTxt = val('extra_btn_text')       || 'Подробнее';
        var extraBg  = val('extra_btn_bg')         || '#6c757d';
        var extraCol = val('extra_btn_text_color') || '#ffffff';

        // Иконка
        var showIcon = idChk('icon-toggle');
        var iconSize = $('select[name="cookierus_settings[banner][icon_size]"]').val() || 'medium';
        var iconPx   = iconSize === 'small' ? 20 : (iconSize === 'large' ? 36 : 28);
        var pluginUrl = (typeof cookierusPluginUrl !== 'undefined') ? cookierusPluginUrl : '';

        // ── Применяем стили баннера ──────────────────────
        $inner.css({
            'background-color' : bgColor,
            'color'            : textColor,
            'border-radius'    : (pos === 'bottom' || pos === 'top') ? '0' : radius + 'px'
        });

        // ── Позиция: меняем класс ────────────────────────
        $inner.removeClass('cr-pos-bottom cr-pos-top cr-pos-bottom-left cr-pos-bottom-right');
        $inner.addClass(posMap[pos] || 'cr-pos-bottom');

        // Для угловых позиций ограничиваем ширину (в масштабе ~55% реального)
        if (pos === 'bottom-left' || pos === 'bottom-right') {
            var scaledMax = Math.min(maxW, 280); // превью ~62% реального
            $inner.css('max-width', scaledMax + 'px');
        } else {
            $inner.css('max-width', '');
        }

        // ── Иконка ───────────────────────────────────────
        var $icon = $('#cr-pi-icon');
        if (showIcon && pluginUrl) {
            $icon.show().html('<img src="' + pluginUrl + 'assets/cookie-svgrepo-com.png" style="width:' + iconPx + 'px;height:' + iconPx + 'px;object-fit:contain;">');
        } else {
            $icon.hide();
        }

        // ── Текст ────────────────────────────────────────
        $('#cr-pi-title').text(title);
        var textHtml = $('<span>').text(text).html();
        if (linkUrl && linkText) {
            textHtml += ' <a href="#" style="color:' + linkColor + ';text-decoration:underline;">' + $('<span>').text(linkText).html() + '</a>';
        }
        $('#cr-pi-text').html(textHtml);

        // ── Кнопки ───────────────────────────────────────
        var bStyle = 'border-radius:' + btnRadius + 'px;font-size:' + Math.max(9, btnFontSize * 0.75) + 'px;';
        $('#cr-pi-btn-accept').text(btnAccept).attr('style', 'background:' + btnAcceptBg + ';color:' + btnAcceptTxt + ';' + bStyle);
        $('#cr-pi-btn-decline').text(btnDecline).attr('style', 'background:' + btnDeclineBg + ';color:' + btnDeclineTxt + ';' + bStyle);
        $('#cr-pi-btn-settings').text(btnSettings).attr('style', 'background:' + btnSettingsBg + ';color:' + btnSettingsTxt + ';border:1px solid rgba(0,0,0,0.1);' + bStyle);

        if (extraOn) {
            $('#cr-pi-btn-extra').show().text(extraTxt).attr('style', 'background:' + extraBg + ';color:' + extraCol + ';' + bStyle);
        } else {
            $('#cr-pi-btn-extra').hide();
        }
    }

    // Живое обновление при изменении любого поля
    $('input, textarea, select').on('input change', function() {
        if ($toggle.is(':checked')) updatePreview();
    });

    // Начальный рендер (даже если превью скрыто, данные готовы)
    updatePreview();
});
</script>
