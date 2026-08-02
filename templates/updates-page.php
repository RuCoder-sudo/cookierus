<?php
/**
 * CookieRus Updates Page — v1.0.8
 */
if (!defined('ABSPATH')) exit; ?>

<style>
.cr-updates-page { max-width: 900px; }
.cr-updates-section {
    background: #fff;
    border-radius: 12px;
    padding: 28px 32px;
    margin-bottom: 22px;
    box-shadow: 0 4px 15px rgba(7,96,210,0.07);
    border: 1px solid #e1e9f5;
}
.cr-updates-section h3 {
    margin: 0 0 18px 0;
    color: #0760D2;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 14px;
    border-bottom: 2px solid #f0f4f9;
    font-size: 15px;
}
.cr-updates-section h3 .dashicons { font-size: 22px; width: 22px; height: 22px; }
.cr-version-block {
    margin-bottom: 18px;
    padding-bottom: 18px;
    border-bottom: 1px solid #f3f4f6;
}
.cr-version-block:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
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
.cr-version-tag.cr-latest {
    background: #f0fdf4;
    color: #166534;
    border-color: #86efac;
}
.cr-version-date {
    font-size: 11px;
    color: #9ca3af;
    margin-left: 6px;
    font-weight: 400;
}
.cr-change-list {
    margin: 0;
    padding: 0 0 0 18px;
    list-style: none;
}
.cr-change-list li {
    padding: 4px 0;
    font-size: 13px;
    color: #374151;
    line-height: 1.55;
    position: relative;
    padding-left: 22px;
}
.cr-change-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 10px;
    width: 8px;
    height: 2px;
    background: #0760D2;
    border-radius: 1px;
}
.cr-change-list li.cr-fix::before  { background: #10b981; }
.cr-change-list li.cr-new::before  { background: #6366f1; }
.cr-change-list li strong { color: #111827; }
</style>

<div class="cr-updates-page">

    <div class="cookierus-admin-header" style="margin-bottom: 20px;">
        <h3>Обновления и история версий</h3>
        <p class="description">Все изменения плагина CookieRus и наши другие бесплатные инструменты для WordPress.</p>
    </div>

    <!-- ════════════════════════════════════════
         РЕКЛАМНЫЙ БЛОК — WP RU-MAX
    ════════════════════════════════════════ -->
    <div style="background:linear-gradient(135deg,#f0fdf4 0%,#eff6ff 100%);border-left:4px solid #10b981;border-radius:12px;padding:24px 28px;margin-bottom:22px;box-shadow:0 4px 15px rgba(16,185,129,0.08);">
        <div style="display:flex;align-items:flex-start;gap:20px;flex-wrap:wrap;">
            <div style="font-size:44px;line-height:1;flex-shrink:0;">💬</div>
            <div style="flex:1;min-width:200px;">
                <h3 style="margin:0 0 6px;color:#065f46;font-size:16px;border:none;padding:0;border-bottom:none;">WP Ru-Max — Уведомления через Max (Мессенджер) для WordPress</h3>
                <p style="margin:0 0 12px;color:#374151;font-size:13px;line-height:1.6;">Плагин отправляет уведомления администратора и менеджеров прямо в мессенджер <strong>Max</strong> (бывший ICQ). Поддерживает <strong>WooCommerce</strong>: уведомления о новых заказах с фильтром статусов и защитой от дублей. Настраивается из обычной панели WordPress — без сторонних сервисов.</p>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
                    <span style="background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Уведомления о заказах</span>
                    <span style="background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">WooCommerce</span>
                    <span style="background:#dbeafe;color:#1e40af;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Фильтр статусов</span>
                    <span style="background:#dbeafe;color:#1e40af;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Защита от дублей</span>
                    <span style="background:#fce7f3;color:#9d174d;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">Звуковые уведомления</span>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a href="https://github.com/RuCoder-sudo/wp-ru-max" target="_blank" rel="noopener" class="button button-primary" style="background:#10b981;border-color:#059669;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        ⬇ Скачать бесплатно на GitHub
                    </a>
                    <a href="https://rucoder-sudo.github.io/wp-ru-max/" target="_blank" rel="noopener" class="button" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        📖 Документация
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
         ИСТОРИЯ ВЕРСИЙ
    ════════════════════════════════════════ -->
    <div class="cr-updates-section">
        <h3><span class="dashicons dashicons-backup"></span> История версий CookieRus</h3>

        <!-- v1.0.8 -->
        <div class="cr-version-block">
            <div>
                <span class="cr-version-tag cr-latest">✅ v1.0.8 <span class="cr-version-date">— 2 августа 2026</span></span>
            </div>
            <ul class="cr-change-list">
                <li class="cr-fix"><strong>Исправлено:</strong> технические ответы WordPress больше не получают HTML-код баннера CookieRus.</li>
                <li class="cr-new"><strong>Добавлено:</strong> пропуск `robots.txt`, sitemap, RSS/Atom, REST API и XML-RPC до запуска буферизации вывода.</li>
                <li class="cr-fix"><strong>Исправлено:</strong> если ответ не содержит закрывающий тег <code>&lt;/body&gt;</code>, он возвращается без изменений и HTML не дописывается в конец.</li>
            </ul>
        </div>

        <!-- v1.0.7 -->
        <div class="cr-version-block">
            <div>
                <span class="cr-version-tag">v1.0.7 <span class="cr-version-date">— 2 июня 2026</span></span>
            </div>
            <ul class="cr-change-list">
                <li class="cr-fix"><strong>Исправлено:</strong> кнопки в режиме «В ряд» теперь всегда отображаются в одну строку.</li>
                <li class="cr-fix"><strong>Исправлено:</strong> баннер автоматически расширяется до 640 px при горизонтальном расположении кнопок.</li>
                <li class="cr-fix"><strong>Исправлено:</strong> кнопка «Свернуть» всегда отображается как маленький текст + иконка.</li>
                <li class="cr-fix"><strong>Исправлено:</strong> медиа-запрос 480 px больше не ломает горизонтальный режим кнопок на узких экранах.</li>
            </ul>
        </div>

        <!-- v1.0.6 -->
        <div class="cr-version-block">
            <div>
                <span class="cr-version-tag">v1.0.6 <span class="cr-version-date">— 30 мая 2026</span></span>
            </div>
            <ul class="cr-change-list">
                <li>Обновление версии плагина.</li>
            </ul>
        </div>

        <!-- v1.0.5 -->
        <div class="cr-version-block">
            <div>
                <span class="cr-version-tag">v1.0.5 <span class="cr-version-date">— 29 мая 2026</span></span>
            </div>
            <ul class="cr-change-list">
                <li class="cr-new"><strong>Добавлено:</strong> иконка cookie в баннере с выбором размера (маленький / средний / большой).</li>
                <li class="cr-new"><strong>Добавлено:</strong> дополнительная произвольная кнопка с кастомным URL.</li>
                <li class="cr-new"><strong>Добавлено:</strong> расширенные настройки стилизации — шрифт, тень, закругление кнопок, эффекты наведения, цвет ссылки политики.</li>
                <li class="cr-new"><strong>Добавлено:</strong> режим сворачивания баннера в круглую плавающую иконку.</li>
                <li class="cr-new"><strong>Добавлено:</strong> настройка повторного показа (никогда / 7 / 30 дней / каждый визит).</li>
                <li class="cr-new"><strong>Добавлено:</strong> автообновление плагина через GitHub Releases.</li>
                <li class="cr-new"><strong>Добавлено:</strong> бейдж версии в шапке страницы настроек.</li>
            </ul>
        </div>

        <!-- v1.0.4 -->
        <div class="cr-version-block">
            <div>
                <span class="cr-version-tag">v1.0.4 <span class="cr-version-date">— 20 мая 2026</span></span>
            </div>
            <ul class="cr-change-list">
                <li class="cr-fix"><strong>Исправлено:</strong> мерцание баннера при переходах между страницами — PHP-проверка cookie до рендера.</li>
                <li class="cr-new"><strong>Добавлено:</strong> кнопка «Очистить все логи» с защитой nonce.</li>
            </ul>
        </div>

        <!-- v1.0.3 -->
        <div class="cr-version-block">
            <div>
                <span class="cr-version-tag">v1.0.3 <span class="cr-version-date">— 1 июня 2025</span></span>
            </div>
            <ul class="cr-change-list">
                <li class="cr-new"><strong>Добавлено:</strong> предпросмотр баннера в реальном времени в настройках.</li>
                <li class="cr-new"><strong>Добавлено:</strong> настройка анимации появления: Fade In, Slide, Bounce, Zoom, Flip.</li>
                <li class="cr-new"><strong>Добавлено:</strong> поддержка позиции баннера: снизу, сверху, снизу-слева, снизу-справа.</li>
            </ul>
        </div>

        <!-- v1.0.2 -->
        <div class="cr-version-block">
            <div>
                <span class="cr-version-tag">v1.0.2</span>
            </div>
            <ul class="cr-change-list">
                <li class="cr-new"><strong>Добавлено:</strong> логирование согласий с IP и страной, экспорт в CSV, генератор политики cookie.</li>
            </ul>
        </div>

        <!-- v1.0.1 -->
        <div class="cr-version-block">
            <div>
                <span class="cr-version-tag">v1.0.1</span>
            </div>
            <ul class="cr-change-list">
                <li class="cr-new"><strong>Добавлено:</strong> категории согласия (Необходимые, Функциональные, Аналитика, Производительность, Реклама), модальное окно настройки.</li>
            </ul>
        </div>

        <!-- v1.0.0 -->
        <div class="cr-version-block">
            <div>
                <span class="cr-version-tag">v1.0.0</span>
            </div>
            <ul class="cr-change-list">
                <li>🎉 Первый релиз плагина CookieRus.</li>
            </ul>
        </div>

    </div>

</div>
