<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

session_start();

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карта сайта</title>
</head>
<style>
    .information {
        margin: 1em 0;
    }

    label.block {
        display: block;
        margin-bottom: 0.5em;
    }
</style>

<body>
    <div class="information">
        <span id="caption">Ожидание отправки данных.</span>
        <span id="progress"></span>
        <span id="download"></span>
    </div>
    <!--    <form action="" method="get">
-->
    <label class="block">
        Введите адрес сайта с указанием протокола<br>
        <input type="text" name="start_url" id="start-url" value="https://znanieetosila.ru/">
    </label>
    <label class="block">
        Введите максимальное количество страниц для добавления в карту<br>
        <input type="number" name="sitemap_limit" id="sitemap-limit" value="20">
    </label>
    <input type="button" name="submit" id="submit" value="Начать сканирование">
    <input type="button" name="show_progress" id="show-progress" value="Показать прогресс">
    <!--    </form>
-->

    <script>
        //после загрузки DOM-дерева страницы
        document.addEventListener("DOMContentLoaded", function() {
            //
            var sessionID = '<?= session_id() ?>';
            var paramSessionID = 'session_id=' + encodeURIComponent(sessionID);
            var myCaption = document.getElementById('caption');
            var progress = document.getElementById('progress');
            var downloadLink = document.getElementById('download');
            //получить кнопку
            var submitButton = document.getElementById('submit');
            //подписаться на событие click по кнопке и назначить обработчик, который будет выполнять действия, указанные в безымянной функции
            submitButton.addEventListener("click", function() {
                submitButton.setAttribute('disabled', 'disabled');
                myCaption.innerHTML = sessionID;
                //'Идет создание карты сайта. Обработано страниц: ';
                progress.innerHTML = '0 (0%)';
                downloadLink.innerHTML = '';

                //1. Сбор данных, необходимых для выполнения запроса на сервере
                var startUrl = document.getElementById('start-url').value;
                var sitemapLimit = document.getElementById('sitemap-limit').value;
                //Подготовка данных для отправки на сервер
                //т.е. кодирование с помощью метода encodeURIComponent
                startUrl = 'start_url=' + encodeURIComponent(startUrl);
                sitemapLimit = 'sitemap_limit=' + encodeURIComponent(sitemapLimit);
                // 2. Создание переменной request
                var request = new XMLHttpRequest();
                // 3. Настройка запроса
                request.open('GET', '156_sitemap_generator.php?' + paramSessionID +
                    '&' + startUrl + '&' + sitemapLimit, true);
                // 4. Подписка на событие onreadystatechange и обработка его с помощью анонимной функции
                request.addEventListener('readystatechange', function() {
                    //если запрос пришёл и статус запроса 200 (OK)
                    if ((request.readyState == 4) && (request.status == 200)) {
                        // например, выведем объект XHR в консоль браузера
                        console.log(request);
                        // и ответ (текст), пришедший с сервера в окне alert
                        console.log(request.responseText);
                        // заменить содержимое элемента ответом, пришедшим с сервера
                        myCaption.innerHTML = 'Карта сайта создана. Обработано страниц: ';
                        downloadLink.innerHTML = '<a href="' + request.responseText + '" download>Скачать</a>';
                        submitButton.removeAttribute('disabled');
                    }
                });
                // Устанавливаем заголовок Content-Type(обязательно для метода POST). Он предназначен для указания кодировки, с помощью которой зашифрован запрос. Это необходимо для того, чтобы сервер знал как его раскодировать.
                // request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
                // 5. Отправка запроса на сервер. В качестве параметра указываем данные, которые необходимо передать (необходимо для POST)
                request.send();
            });

            var progressButton = document.getElementById('show-progress');
            progressButton.addEventListener("click", function() {
                var request_pr = new XMLHttpRequest();
                request_pr.open('GET', 'processing.php?' + paramSessionID, true);
                request_pr.addEventListener('readystatechange', function() {
                    //если запрос пришёл и статус запроса 200 (OK)
                    if (((request_pr.readyState == 4) && (request_pr.status == 200)) || (request_pr.readyState == 3)) {
                        progress.innerHTML = request_pr.responseText;
                    }
                });
                request_pr.send();
            });

        });
    </script>
</body>

</html>