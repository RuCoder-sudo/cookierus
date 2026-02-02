<?php if (!defined('ABSPATH')) exit; 
$settings = get_option('cookierus_settings');
$policy = $settings['policy'] ?? [];
$metrika_id = $policy['metrika_id'] ?? '';
$email = $policy['email'] ?? '';
$phone = $policy['phone'] ?? '';
$hosting = $policy['hosting_url'] ?? '';
$company = $policy['company_name'] ?? '';
$address = $policy['address'] ?? '';
$policy_url = $policy['policy_url'] ?? '';
$domain = $policy['domain'] ?? $_SERVER['HTTP_HOST'];
$date = date('d.m.Y');

// Ссылки на связанные документы
$doc_privacy = $policy['doc_privacy'] ?? '';
$doc_terms = $policy['doc_terms'] ?? '';
$doc_offer = $policy['doc_offer'] ?? '';
$doc_refund = $policy['doc_refund'] ?? '';

// Дополнительные аналитики и сервисы
$analytics_calibri = $policy['analytics_calibri'] ?? '';
$analytics_google = $policy['analytics_google'] ?? '';
$analytics_custom = $policy['analytics_custom'] ?? '';

$policy_html = "
<h1>ПОЛИТИКА ИСПОЛЬЗОВАНИЯ ФАЙЛОВ COOKIE</h1>

<p>Дата последнего обновления: <b>$date</b></p>

<p>Настоящая Политика использования файлов cookie (далее — «Политика») разработана в соответствии с законодательством Российской Федерации, в том числе:</p>

<ul>
<li>Федеральным законом № 152-ФЗ «О персональных данных»;</li>
<li>Федеральным законом № 149-ФЗ «Об информации, информационных технологиях и о защите информации»;</li>
<li>разъяснениями и требованиями Роскомнадзора.</li>
</ul>

<p>Политика определяет порядок использования файлов cookie и иных аналогичных технологий на сайте <b>$domain</b>.</p>

<hr>

<h2>1. Что такое файлы cookie</h2>
<p>Файлы cookie — это небольшие текстовые файлы, которые сохраняются на вашем устройстве (компьютере, смартфоне, планшете) при посещении сайта. Они помогают сайту:</p>
<ul>
<li>запоминать ваши действия и настройки;</li>
<li>обеспечивать корректную работу функционала сайта;</li>
<li>анализировать поведение пользователей;</li>
<li>показывать персонализированный контент и рекламу (при наличии согласия).</li>
</ul>

<hr>

<h2>2. Какие данные автоматически передаются при посещении сайта</h2>
<p>При посещении сайта в автоматическом режиме могут обрабатываться следующие данные:</p>
<ul>
<li>IP-адрес (в обезличенном или сокращённом виде);</li>
<li>информация из файлов cookie;</li>
<li>тип и версия браузера, операционная система;</li>
<li>разрешение экрана;</li>
<li>дата и время доступа;</li>
<li>адрес предыдущей страницы (referer);</li>
<li>сведения о действиях пользователя на сайте.</li>
</ul>

<h3>Меры защиты данных</h3>
<p>Для защиты информации применяются следующие меры:</p>
<ul>
<li>шифрование соединения по протоколу HTTPS (SSL/TLS);</li>
<li>разграничение доступа к персональным данным;</li>
<li>использование cookie-флагов Secure, HttpOnly, SameSite;</li>
<li>ротация Session ID после аутентификации;</li>
<li>привязка сессии к User-Agent;</li>
<li>использование CSRF-токенов;</li>
<li>антивирусная защита и межсетевые экраны.</li>
</ul>

<hr>

<h2>3. Какие файлы cookie мы используем</h2>
<h3>3.1. Строго необходимые (обязательные)</h3>
<p><b>Назначение:</b> обеспечение корректной работы сайта, безопасности и авторизации.</p>
<p><b>Примеры:</b> session ID, cookie авторизации, защитные токены.</p>
<p><b>Срок хранения:</b> до окончания сессии или не более 24 часов.</p>
<p><b>Отключение:</b> невозможно (сайт не будет работать корректно).</p>
<p><b>Персональные данные:</b> не используются для идентификации пользователя.</p>

<hr>
<h3>3.2. Аналитические и статистические</h3>
" . (!empty($metrika_id) ? "
<p><b>Инструмент:</b> Яндекс Метрика (включая Вебвизор, карту кликов).</p>
<p><b>Оператор:</b> ООО «ЯНДЕКС», Россия, 119021, г. Москва, ул. Льва Толстого, д. 16.</p>
<p><b>ID счетчика:</b> $metrika_id</p>
" : "") . "
" . (!empty($analytics_calibri) ? "
<p><b>Инструмент:</b> Callibri (коллтрекинг и аналитика).</p>
<p><b>Цель:</b> отслеживание источников звонков и обращений.</p>
" : "") . "
" . (!empty($analytics_google) ? "
<p><b>Инструмент:</b> Google Analytics.</p>
<p><b>Цель:</b> сбор статистических данных о посещении сайта.</p>
" : "") . "
" . (!empty($analytics_custom) ? "
<p><b>Дополнительные инструменты:</b> $analytics_custom</p>
" : "") . "

<hr>
<h3>3.3. Маркетинговые и рекламные</h3>
<p><b>Инструменты:</b> Яндекс Директ, технологии ретаргетинга.</p>
<p><b>Цель:</b> показ релевантной рекламы и оценка эффективности рекламных кампаний.</p>
<p><b>Основание обработки:</b> согласие пользователя.</p>
<p><b>Отказ:</b> возможен через cookie-баннер или инструменты Яндекса.</p>

<hr>
<h3>3.4. Функциональные</h3>
<p><b>Цель:</b> запоминание пользовательских настроек (язык, регион, параметры интерфейса).</p>
<p><b>Срок хранения:</b> до 1 года.</p>

<hr>
<h2>4. Цели обработки персональных данных</h2>
<p>Персональные данные обрабатываются в целях:</p>
<ul>
<li>обеспечения функционирования сайта;</li>
<li>оказания услуг пользователю;</li>
<li>аналитики и статистического учёта;</li>
<li>обеспечения информационной безопасности;</li>
<li>выполнения требований законодательства РФ.</li>
</ul>

<hr>
<h2>5. Обработка данных третьими лицами</h2>
<p>Обработка данных может осуществляться третьими лицами на основании договоров поручения обработки персональных данных.</p>
<p>Передача данных допускается:</p>
<ul>
<li>при наличии согласия пользователя;</li>
<li>для исполнения договора;</li>
<li>по требованию уполномоченных государственных органов;</li>
<li>в иных случаях, предусмотренных законодательством РФ.</li>
</ul>

<hr>
<h2>6. Управление cookie и отзыв согласия</h2>
<h3>6.1. Cookie-баннер</h3>
<p>При первом посещении сайта пользователь может:</p>
<ul>
<li>принять все cookie;</li>
<li>отказаться от всех, кроме обязательных;</li>
<li>настроить отдельные категории.</li>
</ul>
<h3>6.2. Отзыв согласия</h3>
<p>Согласие может быть отозвано:</p>
<ul>
<li>по email: <b><a href='mailto:$email'>$email</a></b>;</li>
<li>по телефону: <b>$phone</b>;</li>
<li>через личный кабинет (при наличии);</li>
<li>путём письменного обращения оператору.</li>
</ul>
<p>Срок рассмотрения обращения — до 30 календарных дней.</p>

<hr>
<h2>7. Фиксация и хранение согласия</h2>
<p>Факт согласия фиксируется с помощью нашего плагина и включает:</p>
<ul>
<li>обезличенный IP-адрес;</li>
<li>дату и время;</li>
<li>выбранные категории cookie;</li>
<li>идентификатор сессии.</li>
</ul>
<p><b>Срок хранения логов:</b> 3 года.</p>

<hr>
<h2>8. Локализация и хранение данных</h2>
<p>Персональные данные пользователей из РФ обрабатываются и хранятся на серверах, расположенных на территории Российской Федерации, в соответствии с ч. 5 ст. 18 152-ФЗ.
" . (!empty($hosting) ? " Хостинг-провайдер: <b>$hosting</b>." : "") . "</p>

<hr>
<h2>9. Сведения об операторе персональных данных</h2>
<p><b>Наименование:</b> $company<br>
<b>Адрес:</b> $address<br>
<b>Телефон:</b> $phone<br>
<b>Email:</b> <b><a href='mailto:$email'>$email</a></b><br>
<b>Сайт:</b> <a href='http://$domain' target='_blank'>$domain</a></p>

<hr>
<h2>10. Сроки хранения персональных данных</h2>
<p>Персональные данные хранятся:</p>
<ul>
<li>до достижения целей обработки;</li>
<li>либо до отзыва согласия;</li>
<li>либо в сроки, установленные законодательством РФ.</li>
</ul>

<hr>
<h2>11. Связанные документы</h2>
<p>На сайте также размещены:</p>
<ul>
" . (!empty($doc_privacy) ? "<li><a href='$doc_privacy' target='_blank'>Политика конфиденциальности</a></li>" : "") . "
" . (!empty($doc_terms) ? "<li><a href='$doc_terms' target='_blank'>Пользовательское соглашение</a></li>" : "") . "
" . (!empty($doc_offer) ? "<li><a href='$doc_offer' target='_blank'>Публичная оферта</a></li>" : "") . "
" . (!empty($doc_refund) ? "<li><a href='$doc_refund' target='_blank'>Политика возврата</a></li>" : "") . "
</ul>

<hr>
<h2>12. Изменения политики</h2>
<p>Оператор вправе вносить изменения в Политику. Актуальная версия всегда доступна по адресу: <b><a href='$policy_url' target='_blank'>$policy_url</a></b>. О существенных изменениях пользователи уведомляются дополнительно.</p>
";
?>

<div class="cookierus-card">
    <h3>Генератор политики использования cookie (152-ФЗ)</h3>
    <div style="display: flex; gap: 30px;">
        <div style="flex: 1;">
            <form method="post" action="options.php">
                <?php settings_fields('cookierus_settings_group'); ?>
                
                <h4>Основные данные</h4>
                <table class="form-table">
                    <tr>
                        <th scope="row">Домен сайта</th>
                        <td><input type="text" name="cookierus_settings[policy][domain]" value="<?php echo esc_attr($domain); ?>" class="regular-text" placeholder="example.ru"></td>
                    </tr>
                    <tr>
                        <th scope="row">Название компании/ИП</th>
                        <td><input type="text" name="cookierus_settings[policy][company_name]" value="<?php echo esc_attr($company); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">Юридический адрес</th>
                        <td><input type="text" name="cookierus_settings[policy][address]" value="<?php echo esc_attr($address); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">Email для связи</th>
                        <td><input type="email" name="cookierus_settings[policy][email]" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">Телефон</th>
                        <td><input type="text" name="cookierus_settings[policy][phone]" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">Ссылка на хостинг (провайдер)</th>
                        <td><input type="text" name="cookierus_settings[policy][hosting_url]" value="<?php echo esc_attr($hosting); ?>" class="regular-text" placeholder="ООО 'Таймвэб'"></td>
                    </tr>
                    <tr>
                        <th scope="row">URL этой политики</th>
                        <td><input type="text" name="cookierus_settings[policy][policy_url]" value="<?php echo esc_attr($policy_url); ?>" class="regular-text"></td>
                    </tr>
                </table>

                <h4>Аналитические инструменты</h4>
                <table class="form-table">
                    <tr>
                        <th scope="row">ID Яндекс Метрики</th>
                        <td><input type="text" name="cookierus_settings[policy][metrika_id]" value="<?php echo esc_attr($metrika_id); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"> Callibri (ссылка/ID)</th>
                        <td><input type="text" name="cookierus_settings[policy][analytics_calibri]" value="<?php echo esc_attr($analytics_calibri); ?>" class="regular-text" placeholder="Вставьте данные Calibri"></td>
                    </tr>
                    <tr>
                        <th scope="row">Google Analytics (ID)</th>
                        <td><input type="text" name="cookierus_settings[policy][analytics_google]" value="<?php echo esc_attr($analytics_google); ?>" class="regular-text" placeholder="UA-XXXXX-Y или G-XXXXX"></td>
                    </tr>
                    <tr>
                        <th scope="row">Другие сервисы</th>
                        <td><textarea name="cookierus_settings[policy][analytics_custom]" rows="3" class="large-text" placeholder="Перечислите другие сервисы через запятую"><?php echo esc_textarea($analytics_custom); ?></textarea></td>
                    </tr>
                </table>

                <h4>Связанные документы (ссылки)</h4>
                <table class="form-table">
                    <tr>
                        <th scope="row">Политика конфиденциальности</th>
                        <td><input type="text" name="cookierus_settings[policy][doc_privacy]" value="<?php echo esc_attr($doc_privacy); ?>" class="regular-text" placeholder="https://..."></td>
                    </tr>
                    <tr>
                        <th scope="row">Пользовательское соглашение</th>
                        <td><input type="text" name="cookierus_settings[policy][doc_terms]" value="<?php echo esc_attr($doc_terms); ?>" class="regular-text" placeholder="https://..."></td>
                    </tr>
                    <tr>
                        <th scope="row">Публичная оферта</th>
                        <td><input type="text" name="cookierus_settings[policy][doc_offer]" value="<?php echo esc_attr($doc_offer); ?>" class="regular-text" placeholder="https://..."></td>
                    </tr>
                    <tr>
                        <th scope="row">Политика возврата</th>
                        <td><input type="text" name="cookierus_settings[policy][doc_refund]" value="<?php echo esc_attr($doc_refund); ?>" class="regular-text" placeholder="https://..."></td>
                    </tr>
                </table>

                <?php submit_button('Сохранить и обновить текст', 'button-primary'); ?>
            </form>
        </div>

        <div style="flex: 1; background: #fff; padding: 25px; border: 1px solid #e1e9f5; border-radius: 10px;">
            <h4 style="margin-top: 0;">Готовый HTML-код:</h4>
            <p class="description">Скопируйте этот код и вставьте его на страницу вашего сайта.</p>
            <textarea style="width: 100%; height: 250px; font-family: monospace; font-size: 11px; padding: 10px; border-radius: 6px; background: #f8fafc;" readonly><?php echo esc_textarea($policy_html); ?></textarea>
            
            <h4 style="margin-top: 25px;">Предпросмотр текста:</h4>
            <div style="background: #fff; border: 1px solid #eee; padding: 20px; border-radius: 6px; max-height: 400px; overflow-y: auto; font-size: 13px; line-height: 1.6;">
                <?php echo $policy_html; ?>
            </div>
        </div>
    </div>
</div>
