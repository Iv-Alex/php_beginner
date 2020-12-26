<?php
/*
Первый вариант функции возвращает строку с замененной килиллицей,
второй вариант функции возвращает строку для URL
(подсказка: в URL могут присутствовать далеко не все символы, особенно пробел),
то есть нужно конвертировать русский текст в латиницу, а потом заменять пробелы
на _ и удалять все кроме букв и цифр.
Пример: название новости "Голландский архитектор Барт Голдхоорн: в России плохой
градостроительный опыт" конвертировалось в 
"gollandskiy_arkhitektor_bart_goldkhoorn_v_rossii_plokhoy_gradostroitelnyy_opyt"
Функции логично назвать: rus_to_lat и rus_to_url
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

//правила транслитерации ГОСТ Р 52535.1-2006
define('ru_letters', array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ',  'ъ', 'ы', 'ь', 'э', 'ю', 'я'));
define('en_letters', array('a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'kh', 'tc', 'ch', 'sh', 'shch', '', 'y', '', 'e', 'iu', 'ia'));
define('del_symbols', array('`','~','!','@','#','\$','%','^','&','*','(',')',':',';','\'','"','[',']','{','}','|','\\','-','_','+','=','/','?',',','.'));
# функция транслитерации с помощью строковой функции
# text - преобразуемый текст
function rus_to_lat($rus, $lat, $text)
{
    //преобразуем текст в строчный
    $text = mb_strtolower($text);
    //заменяем кириллицу на латиницу
    return str_replace($rus, $lat, $text);
}

# функция преобразует пробелы и удаляет лишние символы в соответствии с массивом $del
# есть вероятность ошибки при использовании символов вне $del
# в примере из задания - символ между словами "плохой градостроительный"
function rus_to_url($text, $del, $space = '_')
{
    return str_replace(' ', $space, str_replace($del, '', $text));
}

# универсальная функция преобразования символов с помощью функций работы с массивами
# работает медленней, от обратного:
# с ключом $is_url = true функция оставит только символы из переданных массивов
# translit_it(что_менять, на_что_менять, изменяемый_текст, замена_пробела, если_это_url)
function translit_it($rus, $lat, $text, $space = ' ', $is_url = false)
{
    //преобразуем текст в строчный
    $text = mb_strtolower($text);
    //добавляем символ замены пробела и цифры - можно сделать опциональным
    $letters_array = range(0, 9) + array(' ' => $space) + array_combine($rus, $lat);
    //преобразуем текст, если это url, то все символы, кроме цифр, букв и пробела, удаляются
    $new_text = '';
    $text_length = mb_strlen($text);
    for ($i = 0; $i < $text_length; $i++) {
        $new_text .= (array_key_exists($index = mb_substr($text, $i, 1), $letters_array) ? $letters_array[$index] : ($is_url ? '' : $index));
    }
    return $new_text;
}

echo 'Исходный текст: ' . ($text = 'Голландский архитектор Барт Голдхоорн: в России плохой
градостроительный опыт / \\ \' @ " 55 5 5555 551 0 ') . '<br><br>';
echo 'Транслитерация строковой функцией.<br>';
$start_time = microtime(true); // посмотрим время выполнения функции
echo rus_to_lat(ru_letters, en_letters, $text) . '<br>';
echo 'Время транслитерации: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br>';
echo '<br>';
echo 'Транслитерации с помощью функций работы с массивами.<br>';
$start_time = microtime(true); // посмотрим время выполнения функции
echo translit_it(ru_letters, en_letters, $text) . '<br>';
echo 'Время транслитерации: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br>';
echo '<br>';
echo 'Преобразование в URL строковой функцией.<br>';
$start_time = microtime(true); // посмотрим время выполнения функции
echo rus_to_url(rus_to_lat(ru_letters, en_letters, $text), del_symbols) . '<br>';
echo 'Время транслитерации: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br>';
echo '<br>';
echo 'Преобразование в URL с помощью функций работы с массивами.<br>';
$start_time = microtime(true); // посмотрим время выполнения функции
echo translit_it(ru_letters, en_letters, $text, '_', true) . '<br>';
echo 'Время транслитерации: ' . number_format(microtime(true) - $start_time, 7, '.', '') . '<br>';

