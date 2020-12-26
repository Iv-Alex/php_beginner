<?php

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

echo 'Сдучайный год (1..9999): ' . ($x = rand(1, 9999)) . '<br>';
#echo ($x = 1700) . '<br>';
echo 'Вариант 1 <br>';
$start_time = microtime(true); // посмотрим время выполнения проверки
$year_is = (($x % 400 == 0) || (($x % 4 == 0) and ($x % 100 > 0))) ? 'Високосный' : 'Не високосный';
echo $year_is . '<br>';
echo 'Время проверки: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br>';
echo '<br>Вариант 2<br>';
$start_time = microtime(true); // посмотрим время выполнения проверки
$year_is = (date('L', mktime(0, 0, 0, 1, 1, $x)) == '1') ? 'Високосный' : 'Не високосный';
echo $year_is . '<br>';
echo 'Время проверки: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br>';

?>