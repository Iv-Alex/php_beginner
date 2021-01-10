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

//Версия 1. Рекурсивная функция

error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
$len = 2;

$digits = count($letters);
$counter = 0;
generator($len, '', $letters, $digits, $counter);

function generator($rank, $base, &$letters, $digits, &$counter)
{
    for ($i = 0; $i < $digits; $i++) {
        $word = $base . $letters[$i];
        if ($rank > 1)
            generator($rank - 1, $word, $letters, $digits, $counter);
        else {
            echo $counter . ': ' . $word . '<br>';
            $counter++;
        }
    }
}
