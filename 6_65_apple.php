<?php

/*
Вывести с 0 по 101 начиная с 0 яблок и заканчивая 101 яблоко (учитывая падежи!!!),
причем дважды, в первый раз используя цикл FOR, во второй раз используя цикл WHILE.
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

echo 'Вариант for<br>';
$start_time = microtime(true); // посмотрим время выполнения проверки
for ($i = 0; $i < 102; $i++) {
    $lc = substr($i, -1); //последняя цифра
    $plc = substr('0' . $i, -2, 1);; //предпоследняя цифра для чисел *'надцать
    $word = ' яблок';
    // составление окончания
    if ($plc != '1') {
        if ($lc == '1') {
            $word .= 'о';
        } elseif (strstr('234', $lc) !== false) {
            $word .= 'а';
        } else {
            //для количества яблок *0 и >*4 окочание не добавляем
        }
    } else {
        //для количества яблок *'надцать окочание не добавляем
    }
    echo $i . $word . '<br>';
}
echo 'Время вывода: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br><br>';
echo 'Вариант while<br>';
$start_time = microtime(true); // посмотрим время выполнения проверки
$i = 0;
while ($i < 102) {
    $lc = substr($i, -1); //последняя цифра
    $plc = substr('0' . $i, -2, 1);; //предпоследняя цифра для чисел *'надцать
    //короткая запись
    echo $i . ' яблок' . (($plc != '1') ? (($lc == '1')  ? 'о': ((strstr('234', $lc) !== false) ? 'а' : '')) : '') . '<br>';
    $i++;
}
echo 'Время вывода: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br>';


?>