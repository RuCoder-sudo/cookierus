<?php
/**
 * CookieRus — Вкладка ВАЖНО (Инструкция по уведомлению Роскомнадзора)
 * v1.0.6
 */
if (!defined('ABSPATH')) exit;
?>
<style>
/* ═══════════════════════════════════════════════════════
   IMPORTANT PAGE STYLES
═══════════════════════════════════════════════════════ */
.cr-imp-wrap {
    max-width: 960px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 14px;
    line-height: 1.65;
    color: #1f2937;
}

/* ── TOC ─────────────────────────────────────────── */
.cr-imp-toc {
    background: #fff;
    border: 1px solid #e1e9f5;
    border-left: 4px solid #0760D2;
    border-radius: 0 12px 12px 0;
    padding: 20px 24px;
    margin-bottom: 26px;
    box-shadow: 0 2px 8px rgba(7,96,210,0.06);
}
.cr-imp-toc h3 {
    margin: 0 0 12px 0 !important;
    font-size: 14px !important;
    color: #0760D2 !important;
    border: none !important;
    padding: 0 !important;
}
.cr-imp-toc ol {
    margin: 0; padding-left: 20px;
    columns: 2;
    column-gap: 24px;
}
.cr-imp-toc li {
    margin-bottom: 5px;
    font-size: 13px;
    break-inside: avoid;
}
.cr-imp-toc a {
    color: #0760D2;
    text-decoration: none;
    font-weight: 500;
}
.cr-imp-toc a:hover { text-decoration: underline; }
@media(max-width:700px){ .cr-imp-toc ol { columns: 1; } }

/* ── Section cards ───────────────────────────────── */
.cr-imp-section {
    background: #fff;
    border-radius: 12px;
    padding: 24px 28px;
    margin-bottom: 22px;
    box-shadow: 0 3px 12px rgba(7,96,210,0.07);
    border: 1px solid #e1e9f5;
    scroll-margin-top: 60px;
}
.cr-imp-section h2 {
    margin: 0 0 16px 0 !important;
    font-size: 16px !important;
    font-weight: 700 !important;
    color: #0760D2 !important;
    border-bottom: 2px solid #f0f4f9 !important;
    padding-bottom: 12px !important;
    display: flex;
    align-items: center;
    gap: 9px;
}
.cr-imp-section h3 {
    margin: 18px 0 8px !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #1f2937 !important;
    border: none !important;
    padding: 0 !important;
}
.cr-imp-section h4 {
    margin: 14px 0 6px !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    color: #374151 !important;
    border: none !important;
    padding: 0 !important;
}
.cr-imp-section p { margin: 0 0 10px 0; color: #374151; font-size: 13.5px; }
.cr-imp-section p:last-child { margin-bottom: 0; }
.cr-imp-section ul, .cr-imp-section ol {
    margin: 0 0 12px 0;
    padding-left: 22px;
    color: #374151;
    font-size: 13.5px;
}
.cr-imp-section li { margin-bottom: 5px; }

/* ── Alert boxes ─────────────────────────────────── */
.cr-alert {
    padding: 14px 18px;
    border-radius: 0 9px 9px 0;
    margin: 14px 0;
    font-size: 13px;
    line-height: 1.6;
    border-left: 4px solid;
}
.cr-alert-red    { background:#fff1f2; border-color:#ef4444; color:#7f1d1d; }
.cr-alert-orange { background:#fff7ed; border-color:#f97316; color:#7c2d12; }
.cr-alert-yellow { background:#fefce8; border-color:#eab308; color:#713f12; }
.cr-alert-green  { background:#f0fdf4; border-color:#22c55e; color:#14532d; }
.cr-alert-blue   { background:#eff6ff; border-color:#3b82f6; color:#1e3a8a; }
.cr-alert strong { display: block; margin-bottom: 4px; font-size: 13.5px; }

/* ── Fines table ─────────────────────────────────── */
.cr-imp-table {
    width: 100%;
    border-collapse: collapse;
    margin: 16px 0;
    font-size: 13px;
}
.cr-imp-table th {
    background: #0760D2;
    color: #fff;
    padding: 10px 14px;
    text-align: left;
    font-weight: 600;
}
.cr-imp-table th:first-child { border-radius: 8px 0 0 0; }
.cr-imp-table th:last-child  { border-radius: 0 8px 0 0; }
.cr-imp-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: top;
    color: #374151;
}
.cr-imp-table tr:nth-child(even) { background: #f9fafb; }
.cr-imp-table tr:last-child td { border-bottom: none; }
.cr-imp-table .cr-fine { font-weight: 700; color: #dc2626; }

/* ── Steps ───────────────────────────────────────── */
.cr-steps { counter-reset: cr-step; margin: 0; padding: 0; list-style: none; }
.cr-steps li {
    counter-increment: cr-step;
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 12px 0;
    border-bottom: 1px dashed #e5e7eb;
    font-size: 13.5px;
}
.cr-steps li:last-child { border-bottom: none; }
.cr-step-num {
    flex-shrink: 0;
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #0760D2;
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1px;
}
.cr-step-body { flex: 1; }
.cr-step-body strong { display: block; margin-bottom: 3px; }

/* ── Links ───────────────────────────────────────── */
.cr-imp-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    color: #1d4ed8;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: background 0.15s, border-color 0.15s;
    margin: 3px 3px 3px 0;
}
.cr-imp-link:hover { background: #dbeafe; border-color: #93c5fd; color: #1e40af; }

/* ── Checklist ───────────────────────────────────── */
.cr-checklist { list-style: none; margin: 0; padding: 0; }
.cr-checklist li {
    padding: 7px 0 7px 30px;
    position: relative;
    border-bottom: 1px dashed #e5e7eb;
    font-size: 13.5px;
    color: #374151;
}
.cr-checklist li:last-child { border-bottom: none; }
.cr-checklist li::before {
    content: "✓";
    position: absolute; left: 0;
    color: #22c55e;
    font-weight: 700;
    font-size: 16px;
}

/* ── Services grid ───────────────────────────────── */
.cr-services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    margin: 14px 0;
}
.cr-service-card {
    background: #f8faff;
    border: 1px solid #e1e9f5;
    border-radius: 8px;
    padding: 12px 14px;
    font-size: 13px;
}
.cr-service-card strong { display: block; margin-bottom: 4px; color: #0760D2; font-size: 13px; }
.cr-service-card span { color: #555; font-size: 12px; line-height: 1.5; }

/* ── Quick tip boxes ─────────────────────────────── */
.cr-tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 12px;
    margin-top: 14px;
}
.cr-tip-card {
    background: #f0fdf4;
    border: 1px solid #86efac;
    border-radius: 9px;
    padding: 14px 16px;
    font-size: 13px;
    color: #15803d;
}
.cr-tip-card strong { display: block; margin-bottom: 5px; color: #166534; }

/* ── Source badge ────────────────────────────────── */
.cr-source-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: #888;
    background: #f3f4f6;
    padding: 3px 9px;
    border-radius: 20px;
    margin-top: 14px;
}
</style>

<div class="cr-imp-wrap">

    <!-- HERO HEADER -->
    <div style="background:linear-gradient(135deg,#1e3a8a,#0760D2);border-radius:16px;padding:28px 32px;margin-bottom:22px;color:#fff;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-30px;right:-30px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
        <div style="position:absolute;bottom:-50px;right:60px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.04);"></div>
        <h2 style="margin:0 0 10px;font-size:20px;font-weight:700;color:#fff;">⚠️ Как уведомить Роскомнадзор об обработке персональных данных</h2>
        <p style="margin:0 0 14px;opacity:0.88;font-size:13.5px;line-height:1.6;max-width:700px;">Пошаговая инструкция для ИП, самозанятых и компаний. Уведомление РКН — это не опция, а обязанность. С 30 мая 2025 года штрафы для юридических лиц и ИП достигают <strong>300 000 рублей</strong>.</p>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
            <a href="https://pd.rkn.gov.ru/operators-registry/notification/form/" target="_blank" rel="noopener" style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.35);border-radius:6px;padding:7px 15px;color:#fff;font-size:13px;font-weight:600;text-decoration:none;">🔗 Форма уведомления РКН</a>
            <a href="https://pd.rkn.gov.ru/operators-registry/operators-list/" target="_blank" rel="noopener" style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.35);border-radius:6px;padding:7px 15px;color:#fff;font-size:13px;font-weight:600;text-decoration:none;">🔍 Реестр операторов ПД</a>
            <a href="https://www.gosuslugi.ru/600373" target="_blank" rel="noopener" style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.35);border-radius:6px;padding:7px 15px;color:#fff;font-size:13px;font-weight:600;text-decoration:none;">📱 Госуслуги</a>
        </div>
    </div>

    <!-- CONTENTS -->
    <div class="cr-imp-toc">
        <h3>📋 Содержание</h3>
        <ol>
            <li><a href="#cr-who">Кто обязан подавать уведомление</a></li>
            <li><a href="#cr-fines">Сроки и штрафы</a></li>
            <li><a href="#cr-avoid">Как избежать штрафов</a></li>
            <li><a href="#cr-checklist">Чек-лист подготовки</a></li>
            <li><a href="#cr-how">Способы подачи</a></li>
            <li><a href="#cr-verify">Проверка в реестре</a></li>
            <li><a href="#cr-steps">Пошаговая инструкция заполнения</a></li>
            <li><a href="#cr-errors">Частые ошибки</a></li>
            <li><a href="#cr-after">После подачи</a></li>
            <li><a href="#cr-cross">Трансграничная передача</a></li>
            <li><a href="#cr-ga">Google Analytics и Яндекс.Метрика</a></li>
            <li><a href="#cr-policy">Политика конфиденциальности</a></li>
            <li><a href="#cr-site">Что должно быть на сайте</a></li>
        </ol>
    </div>

    <!-- ══════════════════════════════════════════
         1. КТО ОБЯЗАН
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-who">
        <h2><span class="dashicons dashicons-admin-users"></span> Кто обязан подавать уведомление</h2>
        <p>Согласно <strong>ст. 22 Федерального закона 152-ФЗ «О персональных данных»</strong>, уведомление должны подавать <strong>все операторы ПДн</strong> — организации, ИП и самозанятые, которые собирают, хранят или обрабатывают данные клиентов, сотрудников, пользователей сайтов и приложений, подписчиков рассылок.</p>
        <div class="cr-alert cr-alert-orange">
            <strong>⚠️ Даже небольшой стартап с одним сотрудником обязан уведомить РКН</strong>, если собирает хотя бы имена и email клиентов — это уже персональные данные.
        </div>
        <h3>Уведомление НЕ требуется, если:</h3>
        <ul>
            <li>Данные ведутся <strong>только на бумаге</strong> (исключение практически нереализуемо для цифровых бизнесов)</li>
            <li>Обработка ведётся в рамках государственных систем</li>
            <li>Данные связаны с транспортной безопасностью</li>
        </ul>
        <p>После подачи уведомления РКН вносит вас в <strong>Реестр операторов персональных данных (ОПД)</strong>. При изменении состава данных или способов обработки — необходимо обновить информацию в РКН.</p>
        <div style="margin-top:14px;">
            <a href="https://pd.rkn.gov.ru/operators-registry/operators-list/" target="_blank" rel="noopener" class="cr-imp-link">🔍 Реестр операторов ПД (проверить себя)</a>
            <a href="https://pd.rkn.gov.ru/operators-registry/notification/form/" target="_blank" rel="noopener" class="cr-imp-link">📋 Форма уведомления</a>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         2. ШТРАФЫ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-fines">
        <h2><span class="dashicons dashicons-money-alt"></span> Сроки и размеры штрафов</h2>
        <p>Законодательство требует подать уведомление <strong>до начала обработки персональных данных</strong>. Если вы уже ведёте деятельность — подайте как можно скорее.</p>

        <h3>📅 Штрафы за неподачу уведомления</h3>
        <table class="cr-imp-table">
            <thead>
                <tr>
                    <th>Субъект</th>
                    <th>До 30.05.2025</th>
                    <th>С 30.05.2025 (ФЗ №420-ФЗ)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Граждане (физлица)</td>
                    <td class="cr-fine">—</td>
                    <td class="cr-fine">5 000 – 10 000 ₽</td>
                </tr>
                <tr>
                    <td>Должностные лица</td>
                    <td class="cr-fine">—</td>
                    <td class="cr-fine">30 000 – 50 000 ₽</td>
                </tr>
                <tr>
                    <td>ИП и юридические лица</td>
                    <td class="cr-fine">3 000 – 5 000 ₽<br><small style="color:#888;font-weight:400;">(ст. 19.7 КоАП РФ)</small></td>
                    <td class="cr-fine">100 000 – 300 000 ₽</td>
                </tr>
            </tbody>
        </table>

        <h3>🔓 Штрафы за непредставление уведомления об утечке (п.11 ст.13.11 КоАП)</h3>
        <table class="cr-imp-table">
            <thead>
                <tr>
                    <th>Субъект</th>
                    <th>Штраф</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Физические лица</td>
                    <td class="cr-fine">50 000 – 100 000 ₽</td>
                </tr>
                <tr>
                    <td>Должностные лица</td>
                    <td class="cr-fine">400 000 – 800 000 ₽</td>
                </tr>
                <tr>
                    <td>ИП и организации</td>
                    <td class="cr-fine">1 000 000 – 3 000 000 ₽</td>
                </tr>
            </tbody>
        </table>

        <div class="cr-alert cr-alert-red">
            <strong>🚨 Размер штрафа за утечку пропорционален числу физлиц в базе данных.</strong> Помимо административной ответственности возможна уголовная или гражданско-правовая.
        </div>
        <a href="https://buh.ru/articles/documents/131380/" target="_blank" rel="noopener" class="cr-imp-link">📊 Подробная таблица штрафов на buh.1C.ru</a>
    </div>

    <!-- ══════════════════════════════════════════
         3. КАК ИЗБЕЖАТЬ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-avoid">
        <h2><span class="dashicons dashicons-shield-alt"></span> Что делать, чтобы избежать штрафов</h2>
        <ul class="cr-checklist">
            <li>Подать уведомление в РКН и зарегистрироваться как оператор ПД — <strong>это первое и главное действие</strong></li>
            <li>Отслеживать изменения в работе с данными и своевременно обновлять информацию в РКН</li>
            <li>При утечке — уведомить РКН <strong>в течение 24 часов</strong> с момента обнаружения</li>
            <li>В течение <strong>72 часов</strong> провести внутреннее расследование и направить повторное уведомление (ч.3.1 ст.21 152-ФЗ)</li>
            <li>Регулярно проводить аудит — проверять, что состав обрабатываемых данных соответствует уведомлению</li>
        </ul>
        <div style="margin-top:14px; display:flex; flex-wrap:wrap; gap:8px;">
            <a href="https://pd.rkn.gov.ru/incident-report/" target="_blank" rel="noopener" class="cr-imp-link">🆘 Форма уведомления об утечке (РКН)</a>
            <a href="https://clck.ru/3N8KPK" target="_blank" rel="noopener" class="cr-imp-link">📖 Порядок действий при утечке данных</a>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         4. ЧЕК-ЛИСТ ПОДГОТОВКИ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-checklist">
        <h2><span class="dashicons dashicons-list-view"></span> Как подготовиться: чек-лист из 5 шагов</h2>

        <ul class="cr-steps">
            <li>
                <div class="cr-step-num">1</div>
                <div class="cr-step-body">
                    <strong>Назначить ответственного за обработку персональных данных</strong>
                    Оформите приказ о назначении ответственного лица (ст. 22.1 152-ФЗ). В малых компаниях это может быть директор или IT-специалист. ИП и самозанятые без сотрудников несут обязанности самостоятельно.
                    <div class="cr-alert cr-alert-blue" style="margin-top:8px;">
                        ФИО и контакты ответственного указываются в уведомлении и должны быть актуальны. При смене сотрудника — оперативно уведомите РКН.
                    </div>
                </div>
            </li>
            <li>
                <div class="cr-step-num">2</div>
                <div class="cr-step-body">
                    <strong>Разработать внутренние документы</strong>
                    Минимально необходимый пакет:
                    <ul style="margin:8px 0 0;">
                        <li><strong>Политика конфиденциальности</strong> — публичный документ на сайте</li>
                        <li><strong>Положение об обработке и защите ПДн работников</strong></li>
                        <li><strong>Приказ о назначении ответственного</strong></li>
                        <li><strong>Формы согласий на обработку ПД</strong> — для пользователей сайта, соискателей, рассылок</li>
                        <li><strong>Договоры поручения</strong> — с хостингом, CRM и другими обработчиками</li>
                    </ul>
                    <a href="https://pd.rkn.gov.ru/docs/" target="_blank" rel="noopener" class="cr-imp-link" style="margin-top:8px;">📄 Полный список документов (РКН)</a>
                </div>
            </li>
            <li>
                <div class="cr-step-num">3</div>
                <div class="cr-step-body">
                    <strong>Проанализировать сайт и цифровые каналы</strong>
                    Проведите аудит:
                    <ul style="margin:8px 0 0;">
                        <li>Какие формы на сайте собирают данные</li>
                        <li>Есть ли согласие перед отправкой каждой формы</li>
                        <li>Размещена ли политика на страницах со сбором данных</li>
                        <li>Какие аналитические системы используются (GA, Метрика, Hotjar)</li>
                        <li>Как оформлен сбор cookie (баннер согласия ✅ — этот плагин закрывает данный пункт)</li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="cr-step-num">4</div>
                <div class="cr-step-body">
                    <strong>Проверить места хранения данных</strong>
                    <div class="cr-alert cr-alert-red" style="margin-top:6px;">
                        <strong>Данные граждан РФ обязаны храниться на серверах, физически расположенных в РФ!</strong> Нарушение — штраф до 6 000 000 ₽ (ч.8-9 ст.13.11 КоАП).
                    </div>
                </div>
            </li>
            <li>
                <div class="cr-step-num">5</div>
                <div class="cr-step-body">
                    <strong>Инвентаризировать сторонние сервисы</strong>
                    Для каждого сервиса запросите и запишите: название, ИНН, ОГРН, страну и адрес серверов. Если провайдер не сообщает адрес — указывайте юридический адрес.
                    <div class="cr-services-grid" style="margin-top:10px;">
                        <div class="cr-service-card"><strong>CRM / ERP</strong><span>Системы учёта клиентов</span></div>
                        <div class="cr-service-card"><strong>Хостинг</strong><span>Адрес ЦОД, ИНН, ОГРН</span></div>
                        <div class="cr-service-card"><strong>Почтовые сервисы</strong><span>Unisender, MailChimp и др.</span></div>
                        <div class="cr-service-card"><strong>Аналитика</strong><span>GA, Метрика, Hotjar</span></div>
                        <div class="cr-service-card"><strong>Облака</strong><span>Яндекс.Диск, Bitrix24</span></div>
                        <div class="cr-service-card"><strong>Платёжные системы</strong><span>ЮКасса, Робокасса</span></div>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <!-- ══════════════════════════════════════════
         5. СПОСОБЫ ПОДАЧИ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-how">
        <h2><span class="dashicons dashicons-email-alt"></span> Способы подачи уведомления</h2>

        <div class="cr-services-grid">
            <div class="cr-service-card">
                <strong>📮 Бумажное уведомление</strong>
                <span>Заполните форму на сайте РКН, распечатайте, подпишите и отправьте почтой или лично в территориальный орган РКН по месту регистрации.</span>
            </div>
            <div class="cr-service-card">
                <strong>✍️ Электронная подпись</strong>
                <span>Заполните форму на pd.rkn.gov.ru и подпишите усиленной квалифицированной ЭП (требуется плагин КриптоПро ЭЦП).</span>
            </div>
            <div class="cr-service-card">
                <strong>🏛️ Госуслуги</strong>
                <span>Подтвержденный аккаунт, привязанный к организации. Наиболее удобный способ для большинства ИП и юрлиц.</span>
            </div>
            <div class="cr-service-card">
                <strong>📊 Через 1С</strong>
                <span>1С:Бухгалтерия 8 (v.3.0.175.32+) помогает сформировать формы. Сервис 152DOC помогает подготовить полный пакет документов.</span>
            </div>
        </div>

        <div class="cr-alert cr-alert-blue" style="margin-top:10px;">
            <strong>⏱ Срок обработки:</strong> Роскомнадзор вносит данные в реестр в течение <strong>30 дней</strong> с даты получения уведомления.
        </div>

        <div style="margin-top:12px; display:flex; flex-wrap:wrap; gap:8px;">
            <a href="https://pd.rkn.gov.ru/operators-registry/notification/form/" target="_blank" rel="noopener" class="cr-imp-link">📋 Форма на сайте РКН</a>
            <a href="https://www.gosuslugi.ru/600373" target="_blank" rel="noopener" class="cr-imp-link">🏛️ Через Госуслуги</a>
            <a href="https://pd.rkn.gov.ru/contacts/" target="_blank" rel="noopener" class="cr-imp-link">📍 Территориальные органы РКН</a>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         6. ПРОВЕРКА В РЕЕСТРЕ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-verify">
        <h2><span class="dashicons dashicons-search"></span> Как проверить попадание в реестр</h2>
        <ol>
            <li>Перейдите на <a href="https://pd.rkn.gov.ru/operators-registry/operators-list/" target="_blank" rel="noopener">pd.rkn.gov.ru/operators-registry/operators-list</a></li>
            <li>В строке поиска введите <strong>ИНН, ОГРН или наименование организации</strong></li>
            <li>Если нашли — всё верно, вы в реестре. Нажмите на запись чтобы проверить актуальность данных</li>
            <li>Если не нашли через 30 дней после подачи — обратитесь в РКН с запросом о статусе</li>
        </ol>
        <a href="https://pd.rkn.gov.ru/operators-registry/operators-list/" target="_blank" rel="noopener" class="cr-imp-link">🔍 Реестр операторов персональных данных</a>
    </div>

    <!-- ══════════════════════════════════════════
         7. ПОШАГОВАЯ ИНСТРУКЦИЯ ЗАПОЛНЕНИЯ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-steps">
        <h2><span class="dashicons dashicons-editor-ol"></span> Пошаговая инструкция заполнения уведомления</h2>

        <ul class="cr-steps">
            <li>
                <div class="cr-step-num">1</div>
                <div class="cr-step-body">
                    <strong>Шаг 1: Подготовка</strong>
                    Заготовьте: ИНН, ОГРН, юридический адрес, ФИО ответственного и его контакты, список всех сервисов где хранятся данные, перечень категорий обрабатываемых ПД.
                </div>
            </li>
            <li>
                <div class="cr-step-num">2</div>
                <div class="cr-step-body">
                    <strong>Шаг 2: Начало заполнения</strong>
                    Выберите тип уведомления: первичное (если подаёте впервые) или уведомление об изменении сведений. Укажите тип оператора: юридическое лицо, ИП или физическое лицо.
                </div>
            </li>
            <li>
                <div class="cr-step-num">3</div>
                <div class="cr-step-body">
                    <strong>Шаг 3: Основные сведения</strong>
                    Заполните: полное и сокращённое наименование, ИНН, ОГРН, юридический адрес, контактные данные ответственного лица.
                </div>
            </li>
            <li>
                <div class="cr-step-num">4</div>
                <div class="cr-step-body">
                    <strong>Шаг 4: Цели обработки и категории данных</strong>
                    Для каждой цели указываются:
                    <ul style="margin:8px 0;">
                        <li>Цель обработки (например: «исполнение договора», «сбор аналитики», «рассылка»)</li>
                        <li>Категории ПД: ФИО, e-mail, телефон, IP-адрес, cookie</li>
                        <li>Категории субъектов: пользователи, клиенты, сотрудники</li>
                        <li>Перечень действий: сбор, запись, хранение, передача, удаление</li>
                        <li>Способы обработки: смешанная / без передачи / с передачей через интернет</li>
                    </ul>
                    <div class="cr-alert cr-alert-yellow">
                        <strong>⚠️ Важно:</strong> описания целей не должны полностью дублировать друг друга. Если для одной цели несколько категорий субъектов — объединяйте их в одной цели, а не создавайте дублирующие.
                    </div>
                    <p style="margin-top:8px;"><strong>Пример для Яндекс.Метрики:</strong></p>
                    <ul style="margin:0;">
                        <li>Цель: Статистический учёт посещаемости сайта</li>
                        <li>Категории ПД: cookie-файлы, IP-адрес, сведения о браузере</li>
                        <li>Субъекты: пользователи сайта</li>
                        <li>Действия: сбор, запись, хранение, передача, уничтожение</li>
                        <li>Способ: смешанная, с передачей по сети Интернет</li>
                    </ul>
                    <a href="https://pd.rkn.gov.ru/docs/primer_zapolnenija_uvedomlenija_TPdn.pdf" target="_blank" rel="noopener" class="cr-imp-link" style="margin-top:8px;">📄 Пример заполнения (PDF, РКН)</a>
                </div>
            </li>
            <li>
                <div class="cr-step-num">5</div>
                <div class="cr-step-body">
                    <strong>Шаг 5: Меры защиты</strong>
                    Опишите применяемые меры (только реально используемые):
                    <p style="margin:8px 0 4px;"><em>Организационные:</em></p>
                    <ul style="margin:0 0 8px;">
                        <li>Назначен ответственный за обработку ПД (приказом)</li>
                        <li>Разработана политика конфиденциальности</li>
                        <li>Сотрудники ознакомлены с требованиями законодательства</li>
                        <li>Ведётся учёт носителей ПД, разграничены права доступа</li>
                        <li>Разработан план действий при инцидентах ИБ</li>
                    </ul>
                    <p style="margin:0 0 4px;"><em>Технические:</em></p>
                    <ul style="margin:0;">
                        <li>Шифрование данных при хранении и передаче (HTTPS/TLS)</li>
                        <li>Двухфакторная аутентификация для административных панелей</li>
                        <li>Антивирусное ПО с актуальными базами</li>
                        <li>Межсетевые экраны (Firewall)</li>
                        <li>Регулярное резервное копирование</li>
                        <li>Обновление ПО для устранения уязвимостей</li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="cr-step-num">6</div>
                <div class="cr-step-body">
                    <strong>Шаг 6: Места хранения баз данных</strong>
                    Укажите физический адрес ЦОД для каждого места хранения. Для арендованных серверов или SaaS — запросите у провайдера ИНН, ОГРН и адрес ЦОД.
                    <div class="cr-alert cr-alert-red" style="margin-top:8px;">
                        <strong>Частые ошибки:</strong> указание только юридического адреса вместо адреса ЦОД; «облачное хранилище» без конкретики; пропущены некоторые места хранения; не указаны домашние ПК если там хранятся договоры.
                    </div>
                </div>
            </li>
            <li>
                <div class="cr-step-num">7</div>
                <div class="cr-step-body">
                    <strong>Шаг 7: Отправка</strong>
                    Проверьте все разделы, убедитесь в корректности контактов ответственного лица. Отправьте через выбранный способ. Сохраните подтверждение отправки.
                </div>
            </li>
        </ul>
    </div>

    <!-- ══════════════════════════════════════════
         8. ЧАСТЫЕ ОШИБКИ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-errors">
        <h2><span class="dashicons dashicons-dismiss"></span> Частые ошибки при подаче</h2>
        <ul>
            <li>❌ Указание только юридического адреса вместо фактического адреса ЦОД</li>
            <li>❌ Неполные или ошибочные сведения о провайдере услуг</li>
            <li>❌ Указание не всех мест хранения (забытые CRM, почтовые сервисы)</li>
            <li>❌ Использование общих формулировок «облачное хранилище» без конкретизации</li>
            <li>❌ Дублирующиеся цели обработки с одинаковым описанием</li>
            <li>❌ Нереально применяемые меры защиты — указывать только то, что реально используется</li>
            <li>❌ Устаревшие контакты ответственного лица</li>
            <li>❌ Не уведомили РКН при смене хостинга или добавлении нового сервиса</li>
            <li>❌ Не учтены домашние ПК, на которых хранятся договоры с персональными данными</li>
        </ul>
    </div>

    <!-- ══════════════════════════════════════════
         9. ПОСЛЕ ПОДАЧИ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-after">
        <h2><span class="dashicons dashicons-yes-alt"></span> Что делать после подачи</h2>
        <ul class="cr-checklist">
            <li>Через 30 дней проверьте наличие в реестре на pd.rkn.gov.ru</li>
            <li>Сохраните копию уведомления с подтверждением отправки</li>
            <li>Настройте напоминания об обновлении при изменении состава сервисов</li>
            <li>При добавлении нового сервиса (CRM, аналитика) — подайте уведомление об изменении сведений</li>
            <li>Ежегодно проводите аудит: все ли используемые сервисы отражены в уведомлении</li>
            <li>Убедитесь что политика конфиденциальности актуальна и ссылки рабочие</li>
        </ul>
    </div>

    <!-- ══════════════════════════════════════════
         10. ТРАНСГРАНИЧНАЯ ПЕРЕДАЧА
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-cross">
        <h2><span class="dashicons dashicons-admin-site"></span> Трансграничная передача данных</h2>
        <div class="cr-alert cr-alert-orange">
            <strong>⚠️ Использование Google Analytics, Meta Pixel, Hotjar, Mailchimp и других иностранных сервисов — это трансграничная передача ПД.</strong> Требует отдельного уведомления в РКН.
        </div>
        <h3>Когда нужно уведомление о трансграничной передаче:</h3>
        <ul>
            <li>Данные передаются в страны, не обеспечивающие адекватную защиту ПД</li>
            <li>Используются иностранные SaaS-решения для хранения ПД российских граждан</li>
            <li>Данные передаются иностранным аффилированным компаниям</li>
        </ul>
        <h3>Порядок действий:</h3>
        <ol>
            <li>Подать уведомление о намерении трансграничной передачи в РКН</li>
            <li>Дождаться ответа (до 10 рабочих дней)</li>
            <li>Получить разрешение или устранить замечания</li>
            <li>Только после этого можно начинать передачу данных</li>
        </ol>
        <div class="cr-alert cr-alert-green">
            <strong>✅ Альтернатива:</strong> Использовать российские аналоги — Яндекс.Метрика вместо Google Analytics, Unisender вместо Mailchimp. Это снимает необходимость в трансграничном уведомлении.
        </div>
        <a href="https://pd.rkn.gov.ru/docs/primer_zapolnenija_uvedomlenija_TPdn.pdf" target="_blank" rel="noopener" class="cr-imp-link">📄 Пример уведомления о трансграничной передаче (PDF)</a>
        <a href="https://pd.rkn.gov.ru/cross-border-transfer/form/" target="_blank" rel="noopener" class="cr-imp-link">🌐 Форма уведомления о трансграничной передаче</a>
    </div>

    <!-- ══════════════════════════════════════════
         11. GOOGLE ANALYTICS И МЕТРИКА
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-ga">
        <h2><span class="dashicons dashicons-chart-bar"></span> Google Analytics и Яндекс.Метрика</h2>

        <h3>🔴 Google Analytics</h3>
        <div class="cr-alert cr-alert-red">
            <strong>Google Analytics передаёт данные на серверы Google (США) — это трансграничная передача ПД без явного разрешения РКН.</strong> РКН неоднократно штрафовал организации за использование GA без надлежащего уведомления.
        </div>
        <p>Что нужно сделать:</p>
        <ul>
            <li>Подать уведомление о трансграничной передаче в РКН (GA = передача в США)</li>
            <li>Указать в политике конфиденциальности, что используется GA и данные передаются в Google</li>
            <li>Убедиться что GA загружается <strong>только после согласия пользователя</strong> (плагин CookieRus закрывает этот пункт ✅)</li>
            <li>Рассмотреть переход на GA4 с включённой анонимизацией IP</li>
        </ul>

        <h3>🟢 Яндекс.Метрика</h3>
        <div class="cr-alert cr-alert-green">
            <strong>Яндекс.Метрика — российский сервис, данные хранятся в РФ.</strong> Является предпочтительным вариантом с точки зрения соответствия 152-ФЗ.
        </div>
        <p>Требования для легализации:</p>
        <ul>
            <li>Указать Яндекс.Метрику в уведомлении РКН (цель: статистический учёт посещаемости)</li>
            <li>Отразить в политике конфиденциальности</li>
            <li>Настроить загрузку счётчика только после согласия пользователя (CookieRus ✅)</li>
            <li>В настройках Метрики включить опцию «Не передавать данные третьим лицам»</li>
        </ul>
        <div class="cr-alert cr-alert-blue">
            <strong>💡 Плагин CookieRus</strong> позволяет указать ID счётчика Яндекс.Метрики в настройках «Блокировка трекеров» и автоматически загружает его только после получения согласия. Это один из ключевых технических требований.
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         12. ПОЛИТИКА КОНФИДЕНЦИАЛЬНОСТИ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-policy">
        <h2><span class="dashicons dashicons-admin-page"></span> Политика конфиденциальности: новые требования</h2>
        <p>Политика — это основной публичный документ. Должна быть доступна по постоянному URL (например, /privacy-policy) и обновляться при изменениях.</p>

        <h3>Обязательные разделы по требованиям РКН:</h3>
        <ul>
            <li>Перечень категорий обрабатываемых данных (ФИО, e-mail, IP, cookie и т.д.)</li>
            <li>Цели обработки каждой категории данных</li>
            <li>Правовое основание обработки (согласие / договор / законный интерес)</li>
            <li>Порядок и сроки хранения данных (для cookie — конкретные сроки)</li>
            <li>Перечень третьих лиц, которым передаются данные (Google, Яндекс, хостинг)</li>
            <li>Права субъекта ПД: доступ, исправление, удаление, отзыв согласия</li>
            <li>Контакты ответственного за обработку ПД</li>
            <li>Дата последнего обновления документа</li>
        </ul>

        <div class="cr-alert cr-alert-orange">
            <strong>⚠️ Новое требование (2024–2025):</strong> Ссылка на политику должна быть размещена <strong>в нижней части каждой страницы</strong> (в футере) и непосредственно рядом с каждой формой сбора данных.
        </div>

        <div class="cr-tips-grid">
            <div class="cr-tip-card">
                <strong>✅ Генератор политики</strong>
                Используйте встроенный генератор в плагине CookieRus (вкладка «Генератор политики») — он создаёт документ с учётом всех требований 152-ФЗ на основе ваших настроек.
            </div>
            <div class="cr-tip-card">
                <strong>✅ Обновляйте регулярно</strong>
                Пересматривайте политику не реже раза в год и при каждом добавлении нового сервиса или изменении состава обрабатываемых данных.
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         13. ЧТО ДОЛЖНО БЫТЬ НА САЙТЕ
    ══════════════════════════════════════════ -->
    <div class="cr-imp-section" id="cr-site">
        <h2><span class="dashicons dashicons-admin-home"></span> Что обязательно должно быть на сайте</h2>

        <table class="cr-imp-table">
            <thead>
                <tr>
                    <th>Элемент</th>
                    <th>Требование</th>
                    <th>Статус с CookieRus</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Cookie-баннер</strong></td>
                    <td>Отображается при первом посещении, содержит кнопки «Принять», «Отклонить», «Настроить» и ссылку на политику</td>
                    <td style="color:#16a34a;font-weight:600;">✅ Плагин</td>
                </tr>
                <tr>
                    <td><strong>Блокировка трекеров</strong></td>
                    <td>GA, Метрика, Meta Pixel не должны загружаться до согласия</td>
                    <td style="color:#16a34a;font-weight:600;">✅ Плагин</td>
                </tr>
                <tr>
                    <td><strong>Журнал согласий</strong></td>
                    <td>Хранить не менее 12 месяцев как доказательство согласия</td>
                    <td style="color:#16a34a;font-weight:600;">✅ Плагин</td>
                </tr>
                <tr>
                    <td><strong>Политика конфиденциальности</strong></td>
                    <td>Отдельная страница с постоянным URL, ссылка в каждом футере</td>
                    <td style="color:#d97706;font-weight:600;">⚠️ Настроить</td>
                </tr>
                <tr>
                    <td><strong>Согласие под формами</strong></td>
                    <td>Чекбокс «Я соглашаюсь на обработку ПД» перед каждой формой</td>
                    <td style="color:#d97706;font-weight:600;">⚠️ Настроить</td>
                </tr>
                <tr>
                    <td><strong>Уведомление РКН</strong></td>
                    <td>Зарегистрирован в реестре операторов ПД</td>
                    <td style="color:#d97706;font-weight:600;">⚠️ Ваша задача</td>
                </tr>
                <tr>
                    <td><strong>Трансграничное уведомление</strong></td>
                    <td>При использовании GA, Meta Pixel, иностранных CRM</td>
                    <td style="color:#d97706;font-weight:600;">⚠️ Ваша задача</td>
                </tr>
                <tr>
                    <td><strong>Реквизиты организации</strong></td>
                    <td>ИНН, ОГРН, юридический адрес, контакты в футере</td>
                    <td style="color:#d97706;font-weight:600;">⚠️ Настроить</td>
                </tr>
            </tbody>
        </table>

    </div>

</div>
