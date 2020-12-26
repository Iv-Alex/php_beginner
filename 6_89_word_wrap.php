<?php
/*
Дано
$lenght - длина текста в элементах, изначально задаем 13
$count - количество элементов, изначально задаем 5
Написать код, который будет генерировать массив, состоящий их $count элементов которые
содержат случайный текст заданной длины $lenght
После генерации полученный массив выводим на экран используя функцию print_r
Далее сортируем все элементы массива в алфавитном порядке и выводим на экран
Далее обрезаем первую букву во всех элементах и выводим на экран элементы массива без первой буквы
Далее опять сортируем все элементы массива но в обратном алфавитном порядке и выводим на экран
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

$length = 13;
$count = 5;
$symbols = range('a', 'z');
$symbols_max_index = count($symbols) - 1;

for ($i = 0; $i < $count; $i++ ) {
    $text = '';
    for ($j = 0; $j < $length; $j++) {
        $text .= $symbols[rand(0, $symbols_max_index)];
    }
    $array[] = $text;
}
echo '<pre>';

// BEGIN
// 1
print_r($array);
// 2
sort($array, SORT_STRING);
echo '<br>';
print_r($array);
// 3
for ($i = 0; $i < $count; $i++ ) {
    $array[$i] = substr($array[$i], 1);
}
echo '<br>';
print_r($array);
// 4
rsort($array, SORT_STRING);
echo '<br>';
print_r($array);
// THE END

echo '</pre>';

?>