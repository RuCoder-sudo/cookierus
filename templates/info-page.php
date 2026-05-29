<?php
/**
 * CookieRus Info Page — v1.0.6
 */
if (!defined('ABSPATH')) exit; ?>

<style>
.cookierus-info-page { max-width: 1000px; }
.cookierus-info-section {
    background: #fff;
    border-radius: 12px;
    padding: 25px 30px;
    margin-bottom: 22px;
    box-shadow: 0 4px 15px rgba(7,96,210,0.07);
    border: 1px solid #e1e9f5;
}
.cookierus-info-section h3 {
    margin: 0 0 18px 0;
    color: #0760D2;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 14px;
    border-bottom: 2px solid #f0f4f9;
    font-size: 15px;
}
.cookierus-info-section h3 .dashicons { font-size: 22px; width: 22px; height: 22px; }
.cookierus-warning-box {
    background: linear-gradient(135deg,#fef3c7,#fde68a);
    border-left: 4px solid #f59e0b;
    padding: 18px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 18px;
}
.cookierus-warning-box h4 { margin: 0 0 8px 0; color: #92400e; display: flex; align-items: center; gap: 7px; }
.cookierus-warning-box p  { margin: 0; color: #78350f; line-height: 1.6; font-size: 13px; }
.cookierus-danger-box {
    background: linear-gradient(135deg,#fee2e2,#fecaca);
    border-left: 4px solid #ef4444;
    padding: 18px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 18px;
}
.cookierus-danger-box h4 { margin: 0 0 8px 0; color: #991b1b; display: flex; align-items: center; gap: 7px; }
.cookierus-danger-box ul { margin: 0; padding-left: 20px; color: #7f1d1d; }
.cookierus-danger-box li { margin-bottom: 8px; line-height: 1.5; font-size: 13px; }
.cookierus-law-box {
    background: linear-gradient(135deg,#e0e7ff,#c7d2fe);
    border: 2px solid #6366f1;
    padding: 22px;
    border-radius: 12px;
    margin-bottom: 18px;
}
.cookierus-law-box h4       { margin: 0 0 4px 0; color: #3730a3; font-size: 17px; }
.cookierus-law-box .law-subtitle { color: #4338ca; font-size: 13px; margin-bottom: 14px; }
.cookierus-law-box .law-important {
    background: #4f46e5;
    color: #fff;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 12.5px;
    margin-bottom: 14px;
}
.cookierus-principles {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px,1fr));
    gap: 12px;
    margin-top: 18px;
}
.cookierus-principle {
    background: #f8faff;
    padding: 13px;
    border-radius: 8px;
    border-left: 3px solid #0760D2;
}
.cookierus-principle p { margin: 0; font-size: 12.5px; color: #374151; line-height: 1.5; }
.cookierus-fines-table {
    width: 100%; border-collapse: collapse; margin-top: 18px;
}
.cookierus-fines-table th {
    background: #0760D2; color: #fff; padding: 12px 15px;
    text-align: left; font-weight: 600; font-size: 13px;
}
.cookierus-fines-table th:first-child { border-radius: 8px 0 0 0; }
.cookierus-fines-table th:last-child  { border-radius: 0 8px 0 0; }
.cookierus-fines-table td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; vertical-align: top; font-size: 13px; }
.cookierus-fines-table tr:nth-child(even) { background: #f9fafb; }
.fine-amount { font-weight: 700; color: #dc2626; }
.fine-label  { display: block; font-size: 11px; color: #6b7280; margin-top: 2px; }
.cookierus-tips {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(280px,1fr));
    gap: 18px;
    margin-top: 18px;
}
.cookierus-tip {
    background: #f0fdf4;
    border: 1px solid #86efac;
    padding: 18px;
    border-radius: 10px;
}
.cookierus-tip h5 { margin: 0 0 8px 0; color: #166534; display: flex; align-items: center; gap: 7px; font-size: 13px; }
.cookierus-tip p  { margin: 0; font-size: 12.5px; color: #15803d; line-height: 1.55; }
.cookierus-checklist { background: #f8faff; padding: 18px; border-radius: 10px; margin-top: 18px; }
.cookierus-checklist h5 { margin: 0 0 12px 0; color: #0760D2; }
.cookierus-checklist ul { margin: 0; padding: 0; list-style: none; }
.cookierus-checklist li {
    padding: 7px 0 7px 28px;
    position: relative;
    border-bottom: 1px dashed #e5e7eb;
    font-size: 13px;
    line-height: 1.45;
}
.cookierus-checklist li:last-child { border-bottom: none; }
.cookierus-checklist li::before { content: "✓"; position: absolute; left: 0; color: #22c55e; font-weight: bold; }

/* Disclaimer box */
.cr-disclaimer-box {
    background: linear-gradient(135deg,#fff7ed,#ffedd5);
    border: 2px solid #f97316;
    border-radius: 14px;
    padding: 22px 26px;
    margin-bottom: 22px;
    position: relative;
}
.cr-disclaimer-box h3 {
    margin: 0 0 12px 0 !important;
    color: #c2410c !important;
    border-bottom: 1px solid rgba(249,115,22,0.3) !important;
    padding-bottom: 10px !important;
    font-size: 14px !important;
}
.cr-disclaimer-box p { margin: 0 0 10px 0; color: #7c2d12; font-size: 12.5px; line-height: 1.6; }
.cr-disclaimer-box p:last-child { margin-bottom: 0; }
.cr-disclaimer-box ul { margin: 8px 0; padding-left: 18px; color: #7c2d12; font-size: 12.5px; line-height: 1.6; }
.cr-compliance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(280px,1fr));
    gap: 14px;
    margin-top: 16px;
}
.cr-compliance-item {
    padding: 14px 16px;
    border-radius: 10px;
    border: 1px solid;
}
.cr-compliance-item.cr-ok   { background: #f0fdf4; border-color: #86efac; }
.cr-compliance-item.cr-warn { background: #fffbeb; border-color: #fcd34d; }
.cr-compliance-item.cr-bad  { background: #fff1f2; border-color: #fca5a5; }
.cr-compliance-item h5 { margin: 0 0 6px 0; font-size: 12.5px; font-weight: 700; }
.cr-compliance-item.cr-ok   h5 { color: #15803d; }
.cr-compliance-item.cr-warn h5 { color: #92400e; }
.cr-compliance-item.cr-bad  h5 { color: #991b1b; }
.cr-compliance-item p  { margin: 0; font-size: 12px; line-height: 1.5; }
.cr-compliance-item.cr-ok   p { color: #166534; }
.cr-compliance-item.cr-warn p { color: #78350f; }
.cr-compliance-item.cr-bad  p { color: #7f1d1d; }
</style>

<div class="cookierus-info-page">

    <div class="cookierus-admin-header" style="margin-bottom: 20px;">
        <h3>Информация, правовые требования и рекомендации</h3>
        <p class="description">Важная правовая информация о работе с файлами cookie и персональными данными в РФ согласно 152-ФЗ и рекомендациям Роскомнадзора.</p>
    </div>

    <!-- ════════════════════════════════════════
         ДИСКЛЕЙМЕР РАЗРАБОТЧИКА
    ════════════════════════════════════════ -->
    <div class="cr-disclaimer-box">
        <h3>⚖️ Отказ от ответственности разработчика плагина</h3>
        <p><strong>Разработчик плагина CookieRus — Сергей Солошенко (RuCoder) — не несёт никакой юридической ответственности</strong> за соответствие деятельности оператора требованиям Федерального закона № 152-ФЗ «О персональных данных», требованиям Роскомнадзора, а также требованиям любых иных нормативных актов.</p>
        <p>Плагин является <strong>техническим инструментом</strong>, упрощающим реализацию механизма получения cookie-согласия. Вся полнота ответственности за соблюдение законодательства о персональных данных лежит <strong>исключительно на операторе персональных данных</strong> — лице, осуществляющем деятельность на сайте, на котором установлен плагин.</p>
        <p>В частности, оператор самостоятельно несёт ответственность за:</p>
        <ul>
            <li>Соответствие политики конфиденциальности и cookie-политики актуальным требованиям 152-ФЗ;</li>
            <li>Ведение журнала согласий и хранение его не менее 12 месяцев;</li>
            <li>Блокировку сторонних трекеров до получения согласия (обеспечивается настройками плагина);</li>
            <li>Уведомление Роскомнадзора о трансграничной передаче данных (GA, Meta, Hotjar и др.);</li>
            <li>Размещение политики конфиденциальности на отдельной странице с постоянным URL;</li>
            <li>Размещение ссылки на политику в футере и под каждой формой сбора данных;</li>
            <li>Ведение локализации персональных данных на территории РФ.</li>
        </ul>
        <p style="margin-top:10px;padding:10px 12px;background:rgba(249,115,22,0.12);border-radius:6px;font-weight:600;">
            Перед публикацией сайта рекомендуем проконсультироваться с юристом, специализирующимся на законодательстве о персональных данных.
        </p>
    </div>

    <!-- ════════════════════════════════════════
         ТРЕБОВАНИЯ РКН К COOKIE-БАННЕРУ
    ════════════════════════════════════════ -->
    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-shield-alt"></span> 10 требований РКН к cookie-баннеру</h3>

        <p style="color:#555;font-size:13px;margin-bottom:15px;">Согласно актуальной позиции Роскомнадзора и судебной практике (дела № 12-559/2024, № 5-0034/422/2025 и др.), cookie-файлы признаются персональными данными. Ниже — перечень требований с пояснением, что закрывает плагин, а что нужно настроить дополнительно.</p>

        <div class="cr-compliance-grid">
            <div class="cr-compliance-item cr-ok">
                <h5>✅ 1. Видимая кнопка «Отклонить»</h5>
                <p>Согласие должно быть добровольным. Кнопка «Отклонить» обязательна и должна быть видимой — не скрытой и не меньшей кнопки «Принять». Плагин выводит её в баннере.</p>
            </div>
            <div class="cr-compliance-item cr-ok">
                <h5>✅ 2. Кнопка «Настроить cookie»</h5>
                <p>Пользователи должны иметь возможность выбирать категории. Плагин реализует двухвкладочное окно: «Категории» и «Цели обработки».</p>
            </div>
            <div class="cr-compliance-item cr-warn">
                <h5>⚠️ 3. Ссылка на политику в баннере</h5>
                <p>В баннере должна быть кликабельная ссылка на полную политику cookie. Укажите URL в настройках плагина → Контент баннера → Ссылка на политику.</p>
            </div>
            <div class="cr-compliance-item cr-warn">
                <h5>⚠️ 4. Политика на отдельном URL</h5>
                <p>Политика cookie должна размещаться на отдельной странице, например /cookie-policy. Создайте страницу в WordPress и укажите её URL в настройках.</p>
            </div>
            <div class="cr-compliance-item cr-ok">
                <h5>✅ 5. Журнал согласий ≥ 12 месяцев</h5>
                <p>Плагин ведёт лог всех согласий с датой, IP, версией баннера и списком категорий. Данные хранятся в базе сайта. Рекомендуем делать регулярный экспорт в CSV.</p>
            </div>
            <div class="cr-compliance-item cr-warn">
                <h5>⚠️ 6. Описание категорий cookie</h5>
                <p>В политике должно быть подробное описание каждой категории с указанием конкретных сервисов и сроков хранения. Используйте генератор политики в соответствующей вкладке.</p>
            </div>
            <div class="cr-compliance-item cr-warn">
                <h5>⚠️ 7. Инструкция по отказу через браузер</h5>
                <p>Укажите в настройках URL-адрес страницы с инструкцией по отключению cookie в браузерах — он откроется при нажатии «Отклонить».</p>
            </div>
            <div class="cr-compliance-item cr-ok">
                <h5>✅ 8. Блокировка трекинга до согласия</h5>
                <p>Плагин блокирует GA4, GTM, Яндекс.Метрику, Meta Pixel, VK Pixel до нажатия «Принять». Укажите ID трекеров в настройках → «Блокировка трекеров».</p>
            </div>
            <div class="cr-warn" style="padding:14px 16px;border-radius:10px;border:1px solid #fcd34d;background:#fffbeb;">
                <h5 style="margin:0 0 6px 0;font-size:12.5px;font-weight:700;color:#92400e;">⚠️ 9. Сроки хранения cookie</h5>
                <p style="margin:0;font-size:12px;color:#78350f;line-height:1.5;">Для каждой категории укажите сроки хранения: Сессионные — до закрытия браузера; Настройки — 12 мес.; Аналитика — 24 мес.; Маркетинг — 90 дней.</p>
            </div>
            <div class="cr-compliance-item cr-warn">
                <h5>⚠️ 10. Актуальная дата политики</h5>
                <p>В политике cookie должна быть дата последнего обновления. Пересматривайте документ не реже раза в год и при изменении состава используемых сервисов.</p>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
         152-ФЗ
    ════════════════════════════════════════ -->
    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-book"></span> Федеральный закон 152-ФЗ «О персональных данных»</h3>

        <div class="cookierus-law-box">
            <h4>152-ФЗ «О персональных данных»</h4>
            <p class="law-subtitle">Основной закон, регулирующий обработку персональных данных в РФ</p>
            <div class="law-important">
                <strong>⚠️ Cookie — это персональные данные.</strong> Так прямо следует из ч. 1 ст. 3 152-ФЗ и судебной практики (Таганский районный суд, дело № 12-559/2024). РКН проверяет наличие баннера и может возбудить административное дело по результатам инспекции кода сайта.
            </div>
            <p style="margin:0;font-size:13px;color:#4338ca;line-height:1.6;">
                <strong>Стандартная формулировка РКН:</strong> «На нашем сайте используются cookie-файлы, в том числе сервисов веб-аналитики. Используя сайт, вы соглашаетесь на обработку персональных данных при помощи cookie-файлов. Подробнее об обработке персональных данных вы можете узнать в Политике конфиденциальности.» Ссылка на политику должна быть кликабельной.
            </p>
        </div>

        <h4 style="margin-top:22px;color:#374151;font-size:14px;">Ключевые принципы закона</h4>
        <div class="cookierus-principles">
            <div class="cookierus-principle"><p>📋 Любые данные, позволяющие идентифицировать физическое лицо (в т.ч. cookie), являются персональными</p></div>
            <div class="cookierus-principle"><p>✋ Обработка ПД без согласия субъекта возможна только в случаях, прямо предусмотренных законом (ст. 6 ч. 1 п. 5 — «законный интерес» для необходимых cookie)</p></div>
            <div class="cookierus-principle"><p>🔒 Оператор обязан хранить ПД граждан РФ на серверах, расположенных на территории РФ (ч. 5 ст. 18)</p></div>
            <div class="cookierus-principle"><p>🌐 Трансграничная передача ПД (GA, Meta, Hotjar) требует уведомления РКН и ожидания 10 раб. дней</p></div>
            <div class="cookierus-principle"><p>📜 На каждой странице сбора ПД должна быть ссылка на Политику конфиденциальности (ч. 2 ст. 18.1)</p></div>
            <div class="cookierus-principle"><p>⚖️ За нарушение предусмотрена административная ответственность: до 6 млн ₽ за первичное нарушение</p></div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
         ЛОГИРОВАНИЕ
    ════════════════════════════════════════ -->
    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-warning"></span> Почему нельзя обойтись без логирования?</h3>

        <div class="cookierus-warning-box">
            <h4><span class="dashicons dashicons-info"></span> Требование закона</h4>
            <p>Согласно ч. 3 ст. 9 152-ФЗ оператор обязан <strong>доказать факт получения согласия</strong> субъекта персональных данных, если это оспаривается. Без журнала согласий доказать это невозможно.</p>
        </div>

        <div class="cookierus-danger-box">
            <h4><span class="dashicons dashicons-dismiss"></span> Последствия отсутствия журнала</h4>
            <ul>
                <li><strong>Штраф за незаконную обработку ПД</strong> — РКН автоматически признаёт обработку неправомерной при отсутствии доказательства согласия.</li>
                <li><strong>Проигрыш в суде</strong> — без лога невозможно доказать, что согласие было получено до начала сбора данных.</li>
                <li><strong>Блокировка сайта</strong> — по решению суда при повторных нарушениях.</li>
            </ul>
        </div>

        <div style="background:#f0fdf4;border:1px solid #86efac;padding:14px 18px;border-radius:10px;">
            <p style="margin:0;font-size:13px;color:#166534;line-height:1.6;">
                ✅ <strong>CookieRus хранит:</strong> дату/время, IP-адрес, UID пользователя, статус (принято/отклонено/кастомное), список согласованных категорий. Срок хранения — в соответствии с вашими настройками базы данных. Для выполнения требования закона (≥12 мес.) рекомендуем настроить автоматический экспорт CSV и не очищать журнал ранее чем через год.
            </p>
        </div>
    </div>

    <!-- ════════════════════════════════════════
         ШТРАФЫ
    ════════════════════════════════════════ -->
    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-money-alt"></span> Размеры штрафов (КоАП РФ)</h3>

        <table class="cookierus-fines-table">
            <thead>
                <tr>
                    <th>Нарушение</th>
                    <th>Штраф для ИП</th>
                    <th>Штраф для юрлиц</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Обработка ПД без согласия</strong><br><small style="color:#666;">Cookie-трекеры без баннера</small></td>
                    <td><span class="fine-amount">75 000 — 300 000 ₽</span></td>
                    <td><span class="fine-amount">1 000 000 — 15 000 000 ₽</span><span class="fine-label">или 3% от выручки</span></td>
                </tr>
                <tr>
                    <td><strong>Нарушение требований к локализации</strong><br><small style="color:#666;">ПД на серверах за пределами РФ</small></td>
                    <td><span class="fine-amount">до 6 000 000 ₽</span><span class="fine-label">первичное</span></td>
                    <td><span class="fine-amount">до 18 000 000 ₽</span><span class="fine-label">повторное (ч. 8–9 ст. 13.11 КоАП)</span></td>
                </tr>
                <tr>
                    <td><strong>Отсутствие ссылки на политику</strong><br><small style="color:#666;">ч. 2 ст. 18.1 152-ФЗ</small></td>
                    <td><span class="fine-amount">от 75 000 ₽</span></td>
                    <td><span class="fine-amount">от 200 000 ₽</span></td>
                </tr>
                <tr>
                    <td><strong>Трансграничная передача без уведомления РКН</strong><br><small style="color:#666;">GA / Meta Pixel / Hotjar без разрешения</small></td>
                    <td><span class="fine-amount">до 100 000 ₽</span></td>
                    <td><span class="fine-amount">до 1 000 000 ₽</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ════════════════════════════════════════
         СОВЕТЫ И ЧЕК-ЛИСТ
    ════════════════════════════════════════ -->
    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-lightbulb"></span> Рекомендации</h3>

        <div class="cookierus-tips">
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-yes-alt"></span> Ведите журнал согласий</h5>
                <p>Делайте ежемесячный экспорт CSV. Это главное доказательство при проверках и судебных спорах. Не очищайте журнал раньше 12 месяцев.</p>
            </div>
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-shield-alt"></span> Блокируйте трекеры</h5>
                <p>Укажите ID GA, Яндекс.Метрики, Meta Pixel в разделе «Блокировка трекеров». Иначе данные уходят в США ещё до согласия — прямое нарушение 152-ФЗ.</p>
            </div>
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-admin-page"></span> Актуальная политика</h5>
                <p>Обновляйте политику конфиденциальности при изменении состава сервисов. Указывайте конкретные сроки хранения для каждой категории cookie.</p>
            </div>
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-editor-unlink"></span> Страница инструкции</h5>
                <p>Создайте страницу с инструкцией по отключению cookie в Chrome, Firefox, Safari, Edge и укажите её URL в настройках кнопки «Отклонить».</p>
            </div>
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-location-alt"></span> Российские аналоги</h5>
                <p>РКН рекомендует Яндекс.Метрику вместо GA. Если используете GA — оформите уведомление о трансграничной передаче через портал РКН.</p>
            </div>
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-update"></span> Регулярная проверка</h5>
                <p>Проверяйте раз в квартал: все ли установленные плагины и виджеты перечислены в политике и соответствуют заявленным категориям cookie.</p>
            </div>
        </div>

        <div class="cookierus-checklist" style="margin-top:20px;">
            <h5>✅ Чек-лист соответствия 152-ФЗ</h5>
            <ul>
                <li>Баннер согласия активирован и отображается при заходе на сайт</li>
                <li>В баннере есть кнопки «Принять все», «Отклонить» и «Настроить»</li>
                <li>В баннере есть кликабельная ссылка на политику конфиденциальности</li>
                <li>Кнопка «Отклонить» ведёт на страницу с инструкцией по настройке браузера</li>
                <li>Google Analytics, Яндекс.Метрика, Meta Pixel заблокированы до согласия</li>
                <li>Ведётся и экспортируется журнал согласий (хранение ≥ 12 месяцев)</li>
                <li>Политика конфиденциальности размещена на отдельной странице</li>
                <li>Ссылка на политику есть в футере на каждой странице</li>
                <li>В политике указаны все используемые трекеры и сроки хранения cookie</li>
                <li>Персональные данные граждан РФ хранятся на серверах в РФ</li>
                <li>Если используется GA/Meta — подано уведомление о трансграничной передаче в РКН</li>
            </ul>
        </div>
    </div>

    <!-- ════════════════════════════════════════
         КОНТАКТЫ РАЗРАБОТЧИКА
    ════════════════════════════════════════ -->
    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-admin-users"></span> О разработчике</h3>
        <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start;">
            <div style="flex:1;min-width:220px;">
                <p style="margin:0 0 10px;font-size:13px;color:#374151;line-height:1.6;">
                    <strong>Сергей Солошенко (RuCoder)</strong><br>
                    Веб-разработчик с 2018 года<br>
                    Специализация: WordPress / Full Stack
                </p>
                <p style="margin:0;font-size:13px;color:#374151;">
                    📞 <a href="tel:+79859855397">+7 (985) 985-53-97</a><br>
                    📧 <a href="mailto:support@рукодер.рф">support@рукодер.рф</a><br>
                    💬 Telegram: <a href="https://t.me/RussCoder" target="_blank" rel="noopener">@RussCoder</a><br>
                    🌐 <a href="https://рукодер.рф" target="_blank" rel="noopener">рукодер.рф</a>
                </p>
            </div>
            <div style="flex:1;min-width:220px;background:#f8faff;padding:14px;border-radius:10px;border:1px solid #e1e9f5;">
                <p style="margin:0;font-size:12px;color:#555;line-height:1.6;">
                    Плагин CookieRus распространяется <strong>бесплатно</strong> под лицензией GPL v2.<br><br>
                    GitHub: <a href="https://github.com/RuCoder-sudo/cookierus" target="_blank" rel="noopener">github.com/RuCoder-sudo/cookierus</a><br><br>
                    Версия плагина: <strong><?php echo defined('COOKIERUS_VERSION') ? esc_html(COOKIERUS_VERSION) : '1.0.6'; ?></strong>
                </p>
            </div>
        </div>
    </div>

</div>
