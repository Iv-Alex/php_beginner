<?php
/*
Задание к уроку #148 "Задача про груши"
выводить в цикле 10раз случайное число и добавлять груша, груш, груши
в зависимости от количества груш. В первом числе случайное число
от 0 до 10, во втором от 10 до 100, в третьем от 100 до 1000 и т д.
*/

error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

for ($i = 0; $i < 10; $i++) {
    $count = rand(pow(10, $i), pow(10, $i+1));
    echo $replaced = preg_replace(
        [
            '/(\d*(1[1-4]|0|[5-9])\z)/',
            '/\d*1\z/',
            '/\d*[2-4]\z/'
        ],
        [
            '$0 груш',
            '$0 груша',
            '$0 груши'
        ],
        $count
    ) . '<br>';
}
