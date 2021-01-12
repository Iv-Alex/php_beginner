<?php
/*
Вывести дату последнего рабочего дня в каждом месяце за $x год,
где $x - случайный год от 2010 до 2030г.
В формате:
01 январь - 31 января 2018,
02 февраль - 27 февраля 2018,
03 март - 30 марта 2018,
и т д,
также учитывать праздники!
*/

error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$str_month = [
    ['январь', 'января'],
    ['февраль', 'февраля'],
    ['март', 'марта'],
    ['апрель', 'апреля'],
    ['май', 'мая'],
    ['июнь', 'июня'],
    ['июль', 'июля'],
    ['август', 'августа'],
    ['сентябрь', 'сентября'],
    ['октябрь', 'октября'],
    ['ноябрь', 'ноября'],
    ['декабрь', 'декабря']
];
$len = 2;

$x = rand(2010, 2030);

for ($i = 1; $i < 13; $i++) {
    echo last_work_day($i, $x);
}

function last_work_day($month, $year)
{
    global $str_month;
    //смещение по дням, если последний приходится на выходной
    $offset = [-2, 0, 0, 0, 0, 0, -1];
    $timestamp = strtotime("last day of 01-$month-$year");
    $last_month_day = intval(date('j', $timestamp));
    $day_of_week = intval(strftime('%w', $timestamp));
    $day = $last_month_day + $offset[$day_of_week];
    $formatted_day = sprintf('%02d ', $month) .
        $str_month[$month - 1][0] . ' - ' . $day . ' ' .
        $str_month[$month - 1][1] . ' ' . $year . '<br>';

    return $formatted_day;
}
