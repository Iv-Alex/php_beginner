<?php
/*
Задание к уроку "Задача: последний рабочий день 2"
Задача аналогично предыдущей, только нужно вывести не целый год,
а только один месяц (где месяц и год случайны).
В формате:
Случайный месяц - апрель.
Случайных год - 2025.
Последний рабочий день - 29 апреля 2025г.
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

$x = rand(2010, 2030);
$i = rand(0, 12);

echo last_work_day($i, $x);

function last_work_day($month, $year)
{
    global $str_month;
    //смещение по дням, если последний приходится на выходной
    $offset = [-2, 0, 0, 0, 0, 0, -1];
    $timestamp = strtotime("last day of 01-$month-$year");
    $last_month_day = intval(date('j', $timestamp));
    $day_of_week = intval(strftime('%w', $timestamp));
    $day = $last_month_day + $offset[$day_of_week];
    $formatted_day =
        'Случайный месяц - ' . $str_month[$month - 1][0] . '.<br>' .
        'Случайный год - ' . $year . '.<br>' .
        'Последний рабочий день - ' . $day . ' ' . $str_month[$month - 1][1] . ' ' . $year . 'г.<br>';

    return $formatted_day;
}
