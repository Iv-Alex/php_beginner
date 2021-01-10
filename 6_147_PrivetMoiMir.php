<?php
/*
Задание к уроку #147 "ПриветМойМир"
Нужно текст вида: ПриветМойМир отформатировать в такой вид: Привет_Мой_Мир и привет_мой_мир
*/

error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

function modify_it($string)
{
    $replaced = preg_replace('/\B\p{Lu}/u', '_$0', $string);
    $lower_case = mb_strtolower($replaced);

    $modified = array(
        'replaced' => $replaced,
        'lower_case' => $lower_case
    );
    return $modified;
}

echo $string = 'ПриветМойМир';
echo '<pre>';
print_r(modify_it($string));
echo '</pre>';