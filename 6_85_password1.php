<?php
/*
Сгенерируйте случайный пароль состоящий из цифр длиной $len
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

$len = 9;
$password = '';

for ($i = 0; $i < $len; $i++) {
    $password .= rand(0, 9);
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

    <h3>Генератор паролей 1. Цифры (0..9), длина пароля <?= $len; ?></h3>
    <h4>Пароль: <?= $password; ?></h4>

</body>

</html>