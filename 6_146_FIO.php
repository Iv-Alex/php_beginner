<?php
/*
Задание к уроку #146 "Фамилия И.О."
Написать код, в котором пользователь вводит полностью ФИО: Иванов Иван Петрович , а программа нам выводит: Иванов И. П.
*/

error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

function modify_it($string)
{
    $replaced = preg_replace(['/(?<=\ \p{Lu})\p{Ll}*/u', '/\s\s+/'], ['.', ' '], $string);
    return $replaced;
}

echo $string = 'Иванов     Иван      Петрович';
echo '<pre>';
print_r(modify_it($string));
echo '</pre>';