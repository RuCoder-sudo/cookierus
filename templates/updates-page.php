<?php
/**
 * CookieRus Updates Page — v1.1.9
 */
if (!defined('ABSPATH')) exit;
?>

<style>
.cr-updates-page { max-width: 900px; }
.cr-updates-section {
    background: #fff;
    border: 1px solid #e1e9f5;
    border-radius: 12px;
    padding: 28px 32px;
    margin-bottom: 22px;
    box-shadow: 0 4px 15px rgba(7,96,210,.07);
}
.cr-updates-section h3 {
    margin: 0 0 18px;
    color: #0760D2;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 14px;
    border-bottom: 2px solid #f0f4f9;
    font-size: 15px;
}
.cr-version-block {
    margin-bottom: 18px;
    padding-bottom: 18px;
    border-bottom: 1px solid #f3f4f6;
}
.cr-version-block:last-child { border-bottom: 0; margin-bottom: 0; padding-bottom: 0; }
.cr-version-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 20px;
    padding: 3px 12px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 10px;
}
.cr-version-tag.cr-latest { background: #f0fdf4; color: #166534; border-color: #86efac; }
.cr-version-date { font-size: 11px; color: #9ca3af; font-weight: 400; }
.cr-change-list { margin: 0; padding: 0 0 0 18px; list-style: none; }
.cr-change-list li {
    padding: 4px 0 4px 22px;
    font-size: 13px;
    color: #374151;
    line-height: 1.55;
    position: relative;
}
.cr-change-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 11px;
    width: 8px;
    height: 2px;
    background: #0760D2;
}
.cr-change-list li.cr-fix::before { background: #10b981; }
.cr-change-list li.cr-new::before { background: #6366f1; }
.cr-important-warning {
    background: #fff7ed;
    border: 2px solid #f97316;
    border-left-width: 7px;
    border-radius: 10px;
    padding: 18px 20px;
    color: #7c2d12;
    line-height: 1.6;
    font-size: 13px;
}
</style>

<div class="cr-updates-page">
    <div class="cookierus-admin-header" style="margin-bottom:20px;">
        <h3>Обновления и история версий</h3>
        <p class="description">Изменения плагина CookieRus и совместимость с WordPress.</p>
    </div>

    <div style="background:linear-gradient(135deg,#f0fdf4 0%,#eff6ff 100%);border-left:4px solid #10b981;border-radius:12px;padding:24px 28px;margin-bottom:22px;box-shadow:0 4px 15px rgba(16,185,129,.08);">
        <div style="display:flex;align-items:flex-start;gap:20px;flex-wrap:wrap;">
            <div style="font-size:44px;line-height:1;flex-shrink:0;">💬</div>
            <div style="flex:1;min-width:200px;">
                <h3 style="margin:0 0 6px;color:#065f46;font-size:16px;border:0;padding:0;">WP Ru-Max — Уведомления через Max для WordPress</h3>
                <p style="margin:0 0 12px;color:#374151;font-size:13px;line-height:1.6;">Плагин отправляет уведомления администратора и менеджеров в мессенджер Max. Поддерживает WooCommerce, фильтр статусов и защиту от дублей.</p>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a href="https://max-wp.ru/" target="_blank" rel="noopener noreferrer" class="button button-primary" style="background:#10b981;border-color:#059669;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        ⬇ Скачать бесплатно
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="cr-updates-section">
        <h3><span class="dashicons dashicons-backup"></span> История версий CookieRus</h3>

        <div class="cr-version-block">
            <span class="cr-version-tag cr-latest">✅ v1.1.9 <span class="cr-version-date">— текущая версия</span></span>
            <ul class="cr-change-list">
                <li class="cr-fix"><strong>Безопасность:</strong> удалены все ссылки и значения стороннего сайта из исходниках, настройках миграции и документации.</li>
                <li class="cr-new"><strong>Совместимость:</strong> метаданные плагина обновлены для WordPress 7.1.</li>
                <li class="cr-new"><strong>Добавлено:</strong> JivoSite (онлайн-чат) с загрузкой кода только после согласия пользователя на аналитические cookie.</li>
                <li class="cr-fix"><strong>Обновлено:</strong> кнопка WP Ru-Max ведёт на официальный сайт продукта; лишняя кнопка документации удалена.</li>
            </ul>
        </div>

        <div class="cr-version-block">
            <span class="cr-version-tag">v1.1.8 <span class="cr-version-date">— 14 сентября 2026</span></span>
            <ul class="cr-change-list">
                <li class="cr-fix"><strong>Исправлено:</strong> выключенные аналитические и маркетинговые сервисы сохраняются выключенными после обновления страницы.</li>
                <li class="cr-new"><strong>Добавлено:</strong> Mail.ru Top по ID, popup по центру и предупреждение о ранней загрузке аналитики.</li>
            </ul>
        </div>

        <div class="cr-version-block">
            <span class="cr-version-tag">v1.1.7 <span class="cr-version-date">— 7 сентября 2026</span></span>
            <ul class="cr-change-list">
                <li class="cr-fix"><strong>Исправлено:</strong> критическая ошибка фронтенда после обновления до 1.1.6.</li>
                <li class="cr-fix"><strong>Улучшено:</strong> совместимость с частично обновлёнными файлами и OPcache.</li>
            </ul>
        </div>

        <div class="cr-version-block">
            <span class="cr-version-tag">v1.1.6 <span class="cr-version-date">— 7 сентября 2026</span></span>
            <ul class="cr-change-list">
                <li class="cr-new"><strong>Добавлено:</strong> ограничение регистрации и входа российскими email-доменами.</li>
                <li class="cr-fix"><strong>Исправлено:</strong> ограничение по умолчанию выключено для существующих установок.</li>
            </ul>
        </div>

        <div class="cr-version-block">
            <span class="cr-version-tag">v1.1.5 <span class="cr-version-date">— 7 сентября 2026</span></span>
            <ul class="cr-change-list">
                <li class="cr-fix"><strong>Исправлено:</strong> после принятия согласия страница обновляется для корректной загрузки аналитики.</li>
                <li class="cr-new"><strong>Изменено:</strong> кнопка отзыва согласия удалена из интерфейса; ссылка отзыва остаётся доступной для политики сайта.</li>
            </ul>
        </div>
    </div>
</div>
