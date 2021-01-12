<?php
/*
Задача аналогично предыдущей, только нужно месяц и год не случайны,
а задаются как два аргумента в функцию (то есть вам нужно написать функцию).
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

if (isset($_GET['month']) && isset($_GET['year'])) {
    if ((($i = intval($_GET['month'])) < 1) || ($i > 12)) $i = 1;
    if (($j = intval($_GET['year'])) < 1) $j = 2020;
    $str_last_work_day = last_work_day($i, $j);
} else {
    $str_last_work_day = '';
}

function last_work_day($month = 1, $year = 2020)
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
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    form {
        outline: black solid 1px;
        padding: 1em;
        max-width: 30%;
    }
</style>

<body>
    <?= $str_last_work_day ?>
    <form action="" method="get">
        <label>
            Введите месяц
            <input type="number" max="12" min="1" name="month" id="month" value="1"><br><br>
        </label>
        <label>
            Введите год
            <input type="number" max="2030" min="2020" name="year" id="year" value="2020"><br><br>
        </label>
        <button type="submit">Отправить</button>
    </form>
</body>

</html>