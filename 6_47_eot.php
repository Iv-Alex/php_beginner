<?php
error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

$doc_title = 'Colored Color';
$colored_text = 'Это мой первый код';

$tc_r = rand(0, 255);
$tc_g = rand(0, 255);
$tc_b = rand(0, 255);
//во избежание сливания в среднем диапазоне смещение делаем на половину всего диапазона
$bc_r = ($tc_r + 128) % 256;
$bc_g = ($tc_g + 128) % 256;
$bc_b = ($tc_b + 128) % 256;

$padding = rand(10, 50) . 'px';

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    echo <<<COLORED
    <title><$doc_title></title>
    <style>
        .colored {
            color: rgb($tc_r, $tc_g, $tc_b);
            background: rgb($bc_r, $bc_g, $bc_b);
            padding: $padding;
        }
    </style>
COLORED;
    ?>

</head>

<body>
    <p class="colored"><?= $colored_text ?></p>
</body>

</html>