<?php

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

echo 'Временная зона: ' . date_default_timezone_get() . '<br>';
echo 'Смещение времени до установки часового пояса, с: ' . date('Z') . '<br>';
$time_zone = 'Europe/Moscow';
echo 'Устанавливаем временную зону \'' . $time_zone . '\'... ';
if (date_default_timezone_set($time_zone)) echo 'Успешно <br>'; else echo 'Проблемы <br>';
echo 'Смещение времени после установки часового пояса, с: ' . date('Z') . '<br>';

$date = '2016-06-07 14:08';
echo '<br>Преобразуем дату \'' . $date . '\' в метку: ' . ($date_label = strtotime($date)) . '<br>';
$date_template = 'd-m-Y';
echo '... и обратно по шаблону \'ДД-ММ-ГГГГ\': ' . date($date_template, $date_label) . '<br>'; 

$date_label = 1452245040;
echo '<br>Преобразуем метку ' . $date_label . ' в дату: ' . date($date_template, $date_label) . '<br>';

$date = '2002-09-01';
echo '<br>Преобразуем дату \'' . $date . '\' в метку: ' . ($date_label = strtotime($date)) . '<br>';
$date_label = strtotime('+1 week 2 days 4 hours', $date_label);
echo 'Получаем дату \'' . $date . '\' +1 неделя +2 дня +4 часа и преобразуем в метку: ' . $date_label . '<br><br>';

$date_label = 1032127200;
echo 'Преобразуем метку ' . $date_label . ' в дату: ' . date($date_template, $date_label) . '<br>';
$date_label = 1031623200;
echo 'Преобразуем метку ' . $date_label . ' в дату: ' . date($date_template, $date_label) . '<br>';
$date_label = 1031540400;
echo 'Преобразуем метку ' . $date_label . ' в дату: ' . date($date_template, $date_label) . '<br>';
$date_label = 1031796000;
echo 'Преобразуем метку ' . $date_label . ' в дату: ' . date($date_template, $date_label) . '<br>';
$date_label = 1031616000;
echo 'Преобразуем метку ' . $date_label . ' в дату: ' . date($date_template, $date_label) . '<br>';

?>