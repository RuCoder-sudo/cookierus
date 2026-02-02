<?php if (!defined('ABSPATH')) exit; ?>

<style>
.cookierus-info-page {
    max-width: 1000px;
}
.cookierus-info-section {
    background: #fff;
    border-radius: 12px;
    padding: 25px 30px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(7, 96, 210, 0.08);
    border: 1px solid #e1e9f5;
}
.cookierus-info-section h3 {
    margin: 0 0 20px 0;
    color: #0760D2;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f4f9;
}
.cookierus-info-section h3 .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
}
.cookierus-warning-box {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-left: 4px solid #f59e0b;
    padding: 20px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 20px;
}
.cookierus-warning-box h4 {
    margin: 0 0 10px 0;
    color: #92400e;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cookierus-warning-box p {
    margin: 0;
    color: #78350f;
    line-height: 1.6;
}
.cookierus-danger-box {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-left: 4px solid #ef4444;
    padding: 20px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 20px;
}
.cookierus-danger-box h4 {
    margin: 0 0 10px 0;
    color: #991b1b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cookierus-danger-box ul {
    margin: 0;
    padding-left: 20px;
    color: #7f1d1d;
}
.cookierus-danger-box li {
    margin-bottom: 8px;
    line-height: 1.5;
}
.cookierus-law-box {
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    border: 2px solid #6366f1;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 20px;
}
.cookierus-law-box h4 {
    margin: 0 0 5px 0;
    color: #3730a3;
    font-size: 18px;
}
.cookierus-law-box .law-subtitle {
    color: #4338ca;
    font-size: 14px;
    margin-bottom: 15px;
}
.cookierus-law-box .law-important {
    background: #4f46e5;
    color: #fff;
    padding: 10px 15px;
    border-radius: 6px;
    font-size: 13px;
    margin-bottom: 15px;
}
.cookierus-principles {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 15px;
    margin-top: 20px;
}
.cookierus-principle {
    background: #f8faff;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #0760D2;
}
.cookierus-principle p {
    margin: 0;
    font-size: 13px;
    color: #374151;
    line-height: 1.5;
}
.cookierus-fines-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.cookierus-fines-table th {
    background: #0760D2;
    color: #fff;
    padding: 15px;
    text-align: left;
    font-weight: 600;
}
.cookierus-fines-table th:first-child {
    border-radius: 8px 0 0 0;
}
.cookierus-fines-table th:last-child {
    border-radius: 0 8px 0 0;
}
.cookierus-fines-table td {
    padding: 15px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: top;
}
.cookierus-fines-table tr:nth-child(even) {
    background: #f9fafb;
}
.cookierus-fines-table tr:last-child td:first-child {
    border-radius: 0 0 0 8px;
}
.cookierus-fines-table tr:last-child td:last-child {
    border-radius: 0 0 8px 0;
}
.fine-amount {
    font-weight: 700;
    color: #dc2626;
}
.fine-label {
    display: block;
    font-size: 11px;
    color: #6b7280;
    margin-top: 3px;
}
.cookierus-tips {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.cookierus-tip {
    background: #f0fdf4;
    border: 1px solid #86efac;
    padding: 20px;
    border-radius: 10px;
}
.cookierus-tip h5 {
    margin: 0 0 10px 0;
    color: #166534;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cookierus-tip p {
    margin: 0;
    font-size: 13px;
    color: #15803d;
    line-height: 1.6;
}
.cookierus-checklist {
    background: #f8faff;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
}
.cookierus-checklist h5 {
    margin: 0 0 15px 0;
    color: #0760D2;
}
.cookierus-checklist ul {
    margin: 0;
    padding: 0;
    list-style: none;
}
.cookierus-checklist li {
    padding: 8px 0;
    padding-left: 30px;
    position: relative;
    border-bottom: 1px dashed #e5e7eb;
}
.cookierus-checklist li:last-child {
    border-bottom: none;
}
.cookierus-checklist li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #22c55e;
    font-weight: bold;
}
</style>

<div class="cookierus-info-page">
    <div class="cookierus-admin-header" style="margin-bottom: 20px;">
        <h3>Информация и рекомендации</h3>
        <p class="description">Важная правовая информация о работе с файлами cookie и персональными данными в РФ.</p>
    </div>

    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-warning"></span> Можно ли обойтись без логирования?</h3>
        
        <div class="cookierus-warning-box">
            <h4><span class="dashicons dashicons-info"></span> Важно понимать</h4>
            <p>Теоретически можно не вести логи, но это сопряжено с <strong>высокими рисками</strong>. Отсутствие доказательств получения согласия может привести к серьёзным последствиям.</p>
        </div>

        <div class="cookierus-danger-box">
            <h4><span class="dashicons dashicons-dismiss"></span> Риски отсутствия логирования</h4>
            <ul>
                <li><strong>Нарушение законодательства.</strong> Невозможность подтвердить факт получения согласия пользователя на обработку его данных.</li>
                <li><strong>Штрафы.</strong> Роскомнадзор может наложить штраф за несоблюдение требований к обработке персональных данных.</li>
                <li><strong>Проблемы при проверках или судебных разбирательствах.</strong> Без логов сложно доказать, что сайт действовал в рамках закона.</li>
            </ul>
        </div>
    </div>

    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-book"></span> Федеральный закон 152-ФЗ «О персональных данных»</h3>
        
        <div class="cookierus-law-box">
            <h4>152-ФЗ «О персональных данных»</h4>
            <p class="law-subtitle">Основной закон, регулирующий обработку персональных данных в РФ</p>
            <div class="law-important">
                <strong>⚠️ Важно:</strong> Несоблюдение требований 152-ФЗ влечёт серьёзные штрафы и может привести к блокировке сайта
            </div>
        </div>

        <h4 style="margin-top: 25px; color: #374151;">Ключевые принципы закона</h4>
        <div class="cookierus-principles">
            <div class="cookierus-principle">
                <p>📋 Любые данные, позволяющие идентифицировать физическое лицо, являются персональными</p>
            </div>
            <div class="cookierus-principle">
                <p>✋ Обработка ПД без согласия субъекта возможна только в случаях, прямо предусмотренных законом</p>
            </div>
            <div class="cookierus-principle">
                <p>🔒 Операторы ПД обязаны обеспечивать безопасность персональных данных</p>
            </div>
            <div class="cookierus-principle">
                <p>👤 Субъекты ПД имеют право на доступ к своим данным и их исправление</p>
            </div>
            <div class="cookierus-principle">
                <p>⚖️ За нарушение закона предусмотрена административная и уголовная ответственность</p>
            </div>
        </div>
    </div>

    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-money-alt"></span> Штрафы за нарушения</h3>
        
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
                    <td><strong>Обработка ПД без согласия</strong><br><small style="color:#666;">Сбор и использование данных без явного согласия пользователя</small></td>
                    <td>
                        <span class="fine-amount">75 000 - 300 000 ₽</span>
                        <span class="fine-label">для ИП</span>
                    </td>
                    <td>
                        <span class="fine-amount">150 000 - 500 000 ₽</span>
                        <span class="fine-label">для юридических лиц</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Невыполнение требований по обеспечению безопасности</strong><br><small style="color:#666;">Недостаточные меры защиты персональных данных</small></td>
                    <td>
                        <span class="fine-amount">30 000 - 50 000 ₽</span>
                        <span class="fine-label">для ИП</span>
                    </td>
                    <td>
                        <span class="fine-amount">100 000 - 200 000 ₽</span>
                        <span class="fine-label">для юридических лиц</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Неуведомление о нарушении безопасности</strong><br><small style="color:#666;">Несообщение в Роскомнадзор об утечке данных</small></td>
                    <td>
                        <span class="fine-amount">100 000 - 200 000 ₽</span>
                        <span class="fine-label">для ИП</span>
                    </td>
                    <td>
                        <span class="fine-amount">300 000 - 600 000 ₽</span>
                        <span class="fine-label">для юридических лиц</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="cookierus-info-section">
        <h3><span class="dashicons dashicons-lightbulb"></span> Рекомендации и советы</h3>
        
        <div class="cookierus-tips">
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-yes-alt"></span> Всегда ведите логи</h5>
                <p>Храните записи о всех полученных согласиях. Это ваша главная защита при проверках и спорах.</p>
            </div>
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-edit"></span> Понятный текст баннера</h5>
                <p>Пользователь должен чётко понимать, на что он соглашается. Избегайте юридического жаргона.</p>
            </div>
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-admin-page"></span> Политика конфиденциальности</h5>
                <p>Обязательно разместите на сайте актуальную политику обработки персональных данных.</p>
            </div>
            <div class="cookierus-tip">
                <h5><span class="dashicons dashicons-update"></span> Регулярные проверки</h5>
                <p>Периодически проверяйте, что все cookie соответствуют заявленным категориям.</p>
            </div>
        </div>

        <div class="cookierus-checklist">
            <h5>✅ Чек-лист соответствия 152-ФЗ</h5>
            <ul>
                <li>Баннер согласия с cookies активирован и отображается на сайте</li>
                <li>Пользователь может принять, отклонить или настроить согласие</li>
                <li>Ведётся журнал (лог) всех полученных согласий</li>
                <li>На сайте размещена политика обработки персональных данных</li>
                <li>Указаны цели сбора данных и используемые сервисы</li>
                <li>Персональные данные хранятся на серверах в РФ</li>
                <li>Есть возможность отозвать согласие</li>
            </ul>
        </div>
    </div>
</div>
