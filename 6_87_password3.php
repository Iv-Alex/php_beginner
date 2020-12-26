<?php
/*
Дано
$lenght - длина пароля.
$a1 - массив из 10 элементов со значениями от 0 до 9 (все цифры)
$a2 - массив со значениями всех латинских букв от a до z
$a3 - массив со значениями произвольных символов, включая знаки препинания (на ваш выбор)
Написать код, который будет генерировать случайный пароль из символов данных массивов заданной длины $lenght

пароль всегда должен содержать как минимум один символ из всех трех массивов.
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

$length = 4;

$a1 = range(0, 9);
$a2 = range('a', 'z');
$a3 = range('`', ':');
echo implode($a1) . '<br>';
echo implode($a2) . '<br>';
echo implode($a3) . '<br>';

// генератор паролей password_generate(длина_пароля, массив_массивов(наборов)_символов)
// возвращает строку используемых при генерации символов и пароль
function password_generate($len, $password_symbols_arrays)
{
    $password_symbols = array_merge(...$password_symbols_arrays);
    $str_password_symbols = implode($password_symbols);
    $password_symbols_max_index = count($password_symbols) - 1;
    do {
        $password = '';
        $pass = true; //использованы символы из всех наборов?
        for ($i = 0; $i < $len; $i++) {
            $password .= $password_symbols[rand(0, $password_symbols_max_index)];
        }
        foreach ($password_symbols_arrays as $symbols_array) {
            $inspection = array_intersect(str_split($password), $symbols_array);
            $pass = $pass && !empty($inspection);
        }
//        echo ($pass ? '<br>ok<br>' : '<br>not ok<br>');
    } while (!$pass);

    return [$str_password_symbols, $password];
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password 1</title>
    <style>
        h3 {
            background: #0088bd;
            color: white;
        }

        h4 {
            color: #0088bd;
        }
    </style>
</head>

<body>

    <h3>Генератор паролей 3</h3>
    <h4>
        Длина пароля <?= ($length = 5); ?><br>
        Символы: <?= ($password = password_generate($length, [$a1]))[0]; ?> <br>
        Пароль: <?= htmlspecialchars($password[1]); ?>
    </h4>
    <h4>
        Длина пароля <?= ($length = 8); ?><br>
        Символы: <?= ($password = password_generate($length, [$a1, $a2]))[0]; ?> <br>
        Пароль: <?= htmlspecialchars($password[1]); ?>
    </h4>
    <h4>
        Длина пароля <?= ($length = 12); ?><br>
        Символы: <?= ($password = password_generate($length, [$a1, $a2, $a3]))[0]; ?> <br>
        Пароль: <?= htmlspecialchars($password[1]); ?>
    </h4>
    <h4>
        Длина пароля <?= ($length = 3); ?><br>
        Символы: <?= ($password = password_generate($length, [$a1, $a2, $a3]))[0]; ?> <br>
        Пароль: <?= htmlspecialchars($password[1]); ?>
    </h4>

</body>

</html>