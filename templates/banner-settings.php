<?php if (!defined('ABSPATH')) exit; 
$settings = get_option('cookierus_settings');
$banner = $settings['banner'] ?? [];
$sections = $settings['sections'] ?? [];
?>
<style>
.cookierus-settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 25px;
    margin-top: 20px;
}
.cookierus-settings-card {
    background: #fff;
    border: 1px solid #e1e9f5;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(7, 96, 210, 0.05);
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
.cookierus-switch {
    position: relative;
    display: inline-block;
    width: 56px;
    height: 30px;
}
.cookierus-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.cookierus-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255,255,255,0.3);
    transition: .3s;
    border-radius: 30px;
}
.cookierus-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.cookierus-switch input:checked + .cookierus-slider {
    background-color: #10b981;
}
.cookierus-switch input:checked + .cookierus-slider:before {
    transform: translateX(26px);
}
.cookierus-preview-container {
    display: none;
    margin-bottom: 25px;
    padding: 20px;
    background: #f0f4f9;
    border-radius: 12px;
    border: 2px dashed #0760D2;
    position: relative;
}
.cookierus-preview-container.active {
    display: block;
    animation: fadeInPreview 0.3s ease;
}
@keyframes fadeInPreview {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.cookierus-preview-label {
    position: absolute;
    top: -12px;
    left: 20px;
    background: #0760D2;
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.cookierus-preview-wrapper {
    background: #2a2a2a;
    border-radius: 8px;
    padding: 40px 20px;
    min-height: 120px;
    position: relative;
    overflow: hidden;
}
#cookierus-preview-banner {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    padding: 20px 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    position: relative;
    max-width: 100%;
    box-sizing: border-box;
}
#cookierus-preview-banner .preview-content {
    flex: 1;
    min-width: 200px;
}
#cookierus-preview-banner .preview-title {
    margin: 0 0 8px 0;
    font-weight: 600;
}
#cookierus-preview-banner .preview-text {
    margin: 0;
    line-height: 1.5;
}
#cookierus-preview-banner .preview-link {
    text-decoration: underline;
    cursor: pointer;
}
#cookierus-preview-banner .preview-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
#cookierus-preview-banner .preview-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: transform 0.2s, box-shadow 0.2s;
}
#cookierus-preview-banner .preview-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>

<div class="cookierus-preview-container" id="cookierus-preview-area">
    <span class="cookierus-preview-label"><span class="dashicons dashicons-visibility" style="font-size: 14px; margin-right: 5px;"></span> Предпросмотр</span>
    <div class="cookierus-preview-wrapper">
        <div id="cookierus-preview-banner">
            <div class="preview-content">
                <h4 class="preview-title"></h4>
                <p class="preview-text"></p>
            </div>
            <div class="preview-buttons">
                <button type="button" class="preview-btn preview-btn-accept"></button>
                <button type="button" class="preview-btn preview-btn-decline"></button>
                <button type="button" class="preview-btn preview-btn-settings"></button>
            </div>
        </div>
    </div>
</div>

<form method="post" action="options.php">
    <?php settings_fields('cookierus_settings_group'); ?>
    
    <div class="cookierus-settings-grid">
        <!-- Группа 1: Контент -->
        <div class="cookierus-settings-card">
            <h4><span class="dashicons dashicons-editor-textcolor"></span> Контент баннера</h4>
            
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

        <!-- Группа 2: Дизайн кнопок -->
        <div class="cookierus-settings-card">
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

        <!-- Группа 3: Общий дизайн -->
        <div class="cookierus-settings-card">
            <h4><span class="dashicons dashicons-art"></span> Внешний вид баннера</h4>
            
            <div class="cookierus-form-group">
                <label>Цвета баннера</label>
                <div class="cookierus-color-flex">
                    <div class="cookierus-color-item">Фон: <input type="color" name="cookierus_settings[banner][bg_color]" value="<?php echo esc_attr($banner['bg_color'] ?? '#ffffff'); ?>"></div>
                    <div class="cookierus-color-item">Текст: <input type="color" name="cookierus_settings[banner][text_color]" value="<?php echo esc_attr($banner['text_color'] ?? '#333333'); ?>"></div>
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
                    </select>
                    <span>Скругление: <input type="number" name="cookierus_settings[banner][radius]" value="<?php echo esc_attr($banner['radius'] ?? 8); ?>" style="width:60px;"> px</span>
                    <span>Макс. ширина: <input type="number" name="cookierus_settings[banner][max_width]" value="<?php echo esc_attr($banner['max_width'] ?? 455); ?>" style="width:70px;"> px</span>
                </div>
            </div>

            <div class="cookierus-form-group">
                <label>Анимация появления</label>
                <select name="cookierus_settings[banner][animation]" class="regular-text" style="width:100%;">
                    <option value="slide" <?php selected('slide', $banner['animation'] ?? ''); ?>>Слайд (Снизу вверх)</option>
                    <option value="fade" <?php selected('fade', $banner['animation'] ?? ''); ?>>Затухание (Fade In)</option>
                    <option value="bounce" <?php selected('bounce', $banner['animation'] ?? ''); ?>>Прыжок (Эластично)</option>
                </select>
            </div>

            <div class="cookierus-form-group">
                <label>Пользовательский CSS</label>
                <textarea name="cookierus_settings[banner][custom_css]" rows="5" class="large-text" style="font-family:monospace; font-size:12px;"><?php echo esc_textarea($banner['custom_css'] ?? ''); ?></textarea>
            </div>
        </div>

        <!-- Группа 4: Категории -->
        <div class="cookierus-settings-card">
            <h4><span class="dashicons dashicons-admin-generic"></span> Категории согласия</h4>
            <p class="description" style="margin-bottom:15px;">Выберите категории, которые пользователь может настроить, и укажите используемые сервисы.</p>
            
            <div class="cookierus-sections-settings">
                <?php 
                $sects = [
                    'functional' => 'Функциональные',
                    'analytics' => 'Аналитика',
                    'performance' => 'Производительность',
                    'advertising' => 'Реклама'
                ];
                foreach ($sects as $key => $label):
                ?>
                <div class="section-row" style="margin-bottom:15px; padding:10px; background:#f9f9f9; border-radius:8px;">
                    <label style="margin-bottom:0;">
                        <input type="checkbox" name="cookierus_settings[sections][<?php echo $key; ?>]" value="1" <?php checked(1, $sections[$key] ?? 0); ?> class="section-toggle"> 
                        <strong><?php echo $label; ?></strong>
                    </label>
                    <div class="section-desc" style="<?php echo empty($sections[$key]) ? 'display:none;' : ''; ?>">
                        <div style="font-size:12px; margin-bottom:5px; color:#666;">Используемые сервисы (например: Яндекс Метрика, Google Analytics):</div>
                        <input type="text" name="cookierus_settings[sections][<?php echo $key; ?>_desc]" value="<?php echo esc_attr($sections[$key.'_desc'] ?? ''); ?>" class="large-text" placeholder="Введите список сервисов">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; text-align: center; background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e1e9f5;">
        <?php submit_button('Сохранить все изменения', 'button-primary button-hero', 'submit', false); ?>
    </div>
</form>

<script>
jQuery(document).ready(function($) {
    $('.section-toggle').on('change', function() {
        $(this).closest('.section-row').find('.section-desc').slideToggle(200);
    });
    
    var $previewToggle = $('#cookierus-preview-toggle');
    var $previewArea = $('#cookierus-preview-area');
    var $previewBanner = $('#cookierus-preview-banner');
    
    $previewToggle.on('change', function() {
        if ($(this).is(':checked')) {
            $previewArea.addClass('active');
            updatePreview();
        } else {
            $previewArea.removeClass('active');
        }
    });
    
    function updatePreview() {
        var title = $('input[name="cookierus_settings[banner][title]"]').val();
        var titleSize = $('input[name="cookierus_settings[banner][title_size]"]').val() || 18;
        var text = $('textarea[name="cookierus_settings[banner][text]"]').val();
        var textSize = $('input[name="cookierus_settings[banner][text_size]"]').val() || 14;
        var linkText = $('input[name="cookierus_settings[banner][link_text]"]').val();
        var linkUrl = $('input[name="cookierus_settings[banner][link_url]"]').val();
        
        var bgColor = $('input[name="cookierus_settings[banner][bg_color]"]').val() || '#ffffff';
        var textColor = $('input[name="cookierus_settings[banner][text_color]"]').val() || '#333333';
        var radius = $('input[name="cookierus_settings[banner][radius]"]').val() || 8;
        
        var btnAccept = $('input[name="cookierus_settings[banner][btn_accept]"]').val() || 'Принять все';
        var btnAcceptBg = $('input[name="cookierus_settings[banner][btn_bg]"]').val() || '#0760D2';
        var btnAcceptText = $('input[name="cookierus_settings[banner][btn_text]"]').val() || '#ffffff';
        var btnAcceptBorder = $('input[name="cookierus_settings[banner][btn_border]"]').is(':checked');
        var btnAcceptBorderColor = $('input[name="cookierus_settings[banner][btn_border_color]"]').val() || '#0760D2';
        
        var btnDecline = $('input[name="cookierus_settings[banner][btn_decline]"]').val() || 'Отклонить';
        var btnDeclineBg = $('input[name="cookierus_settings[banner][btn_decline_bg]"]').val() || '#f0f0f1';
        var btnDeclineText = $('input[name="cookierus_settings[banner][btn_decline_text]"]').val() || '#333333';
        var btnDeclineBorder = $('input[name="cookierus_settings[banner][btn_decline_border]"]').is(':checked');
        var btnDeclineBorderColor = $('input[name="cookierus_settings[banner][btn_decline_border_color]"]').val() || '#c3c4c7';
        
        var btnSettings = $('input[name="cookierus_settings[banner][btn_settings]"]').val() || 'Настроить';
        var btnSettingsBg = $('input[name="cookierus_settings[banner][btn_settings_bg]"]').val() || '#ffffff';
        var btnSettingsText = $('input[name="cookierus_settings[banner][btn_settings_text]"]').val() || '#333333';
        var btnSettingsBorder = $('input[name="cookierus_settings[banner][btn_settings_border]"]').is(':checked');
        var btnSettingsBorderColor = $('input[name="cookierus_settings[banner][btn_settings_border_color]"]').val() || '#c3c4c7';
        
        $previewBanner.css({
            'background-color': bgColor,
            'color': textColor,
            'border-radius': radius + 'px'
        });
        
        $previewBanner.find('.preview-title').text(title).css('font-size', titleSize + 'px');
        
        var textHtml = text;
        if (linkUrl && linkText) {
            textHtml += ' <a href="' + linkUrl + '" class="preview-link" style="color:' + textColor + ';">' + linkText + '</a>';
        }
        $previewBanner.find('.preview-text').html(textHtml).css('font-size', textSize + 'px');
        
        $previewBanner.find('.preview-btn-accept').text(btnAccept).css({
            'background-color': btnAcceptBg,
            'color': btnAcceptText,
            'border': btnAcceptBorder ? '2px solid ' + btnAcceptBorderColor : 'none'
        });
        
        $previewBanner.find('.preview-btn-decline').text(btnDecline).css({
            'background-color': btnDeclineBg,
            'color': btnDeclineText,
            'border': btnDeclineBorder ? '2px solid ' + btnDeclineBorderColor : 'none'
        });
        
        $previewBanner.find('.preview-btn-settings').text(btnSettings).css({
            'background-color': btnSettingsBg,
            'color': btnSettingsText,
            'border': btnSettingsBorder ? '2px solid ' + btnSettingsBorderColor : '1px solid rgba(0,0,0,0.1)'
        });
    }
    
    $('input, textarea, select').on('input change', function() {
        if ($previewToggle.is(':checked')) {
            updatePreview();
        }
    });
    
    updatePreview();
});
</script>
