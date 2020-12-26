<?php

/*
Дано: $x - случайный день текущего или следующего года. (например: 8 мая 2017г)
Необходимо вывести сгенерированную дату в формате: 8 мая 2017г воскресенье выходной.
То есть сначала дату (месяц по русски), далее день недели, далее рабочий день или выходной (сб или вс).
Если выпадает на праздник, то добавлять: праздник.
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

define('rus_month', array (
        'января',
        'февраля',
        'марта',
        'апреля',
        'мая',
        'июня',
        'июля',
        'августа',
        'сентября',
        'октября',
        'ноября',
        'декабря'
    )
);
define('rus_days', array(
        'воскресенье',
        'понедельник',
        'вторник',
        'среда',
        'четверг',
        'пятница',
        'суббота'
    )
);
define('holydays', '.1/1.2/1.3/1.4/1.5/1.6/1.7/1.8/1.23/2.8/3.1/5.9/5.12/6.4/11.');

# Проверка быстродействия текстового варианта
/*
$start_time = microtime(true); // посмотрим время выполнения проверки
echo 'Текущий год: ' . ($year_now = date('Y')) . '<br>';
$rand_timestamp = rand(strtotime('1 January ' . $year_now), strtotime('31 December ' . ($year_now + 1)));
echo 'Timestamp случайного дня текущего или следующего года: ' . date('d.m.Y', $rand_timestamp) . '<br>';
echo 'Время проверки: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br>';
*/

# Числовой вариант
//$start_time = microtime(true); // посмотрим время выполнения проверки
echo 'Текущий год: ' . ($year_now = getdate()['year']) . '<br>';
$rand_timestamp = rand(mktime(0, 0, 0, 1, 1, $year_now), mktime(23, 59, 59, 12, 31, $year_now + 1));
echo 'Cлучайный день текущего или следующего года: ' . date('d.m.Y', $rand_timestamp) . '<br>';
//eсho 'Время проверки: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br>';

$g_date = getdate($rand_timestamp);
$day_n_month = '.' . $g_date['mday'] . '/' . $g_date['mon'] . '.'; //определяем связку .день/месяц. для поиска в списке выходных
echo $g_date['mday'] . ' ' . rus_month[($g_date['mon'] - 1)] . ' ' . $g_date['year'] . 'г. ' .
    rus_days[$g_date['wday']] . ((($g_date['wday'] == 0) || ($g_date['wday'] == 6)) ? ', выходной' : ', рабочий день') .
    ((strpos(holydays, $day_n_month) !== false) ? ', праздник' : '') . '<br>';

?>