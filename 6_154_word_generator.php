<?php
/*
Задание к уроку #154 "Генератор слов из букв"
дано: $m - массив, где элементы это буквы, например а и б.
$len - длина слова, например 2.
Нужно сгенерировать все возможные слова из этих букв заданной длины $len
То есть из букв а и б у нас должно получиться:
аа, бб, аб, ба.
если букв и длина больше, то естественно варинтов должно быть куда больше.
*/

error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
$len = 4;

for ($counter = 0, $digits = count($letters), $max = pow($digits, $len)-1; $counter <= $max; $counter++) {
    $word = '';
    $decimal = $counter;
    for ($rank = $len - 1; $rank >= 1; $rank--) {
        $factor = pow($digits, $rank);
        $factors = intdiv($decimal, $factor);
        $word .= $letters[$factors];
        $decimal -= $factor*$factors;
    }
    $word .= $letters[$decimal];
    echo $counter . ': ' . $word . '<br>';
}
