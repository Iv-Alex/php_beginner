<?php
error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo 'Document' ?></title>
</head>

<body>

    <?php
    error_reporting(E_ALL);

    $text = 'До последнего дня считалось, ФФФФФ что девайс назовут iPhone 9. Эта цифра была пропущена в модельном ряду, Ф тому же из-за внешнего сходства с «восьмеркой» кажется логичным обозвать ее продолжение «девяткой». Но маркетинговая команда Apple решила иначе, поэтому свежий смартфон называется просто — iPhone SE. Тем самым производители «похоронили» устаревший бюджетник с четырехдюймовым экраном и оставили только актуальную модель.';

    echo 'Количество символов: ' . mb_strlen($text) . '<br>';
    echo 'Количество пробелов: ' . needles_count($text, ' ') . '<br>';
    echo 'Количество букв Ф: ' . needles_count($text, 'Ф') . '<br>';
    echo 'Количество букв ф: ' . needles_count($text, 'ф') . '<br>';
    echo str_replace(array('ф', 'Ф'), array('<span style="background: yellow">ф</span>', '<span style="background: yellow">Ф</span>'), $text);

    function needles_count($source_text, $search_needle)
    {
        $temp_count = mb_substr_count($source_text, $search_needle);
        if ($temp_count == 0) $temp_count = 'нет';
        return $temp_count;
    }

    ?>

</body>

</html>