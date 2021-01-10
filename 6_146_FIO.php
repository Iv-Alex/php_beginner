<?php
/*
Задание к уроку #146 "Фамилия И.О."
Написать код, в котором пользователь вводит полностью ФИО: Иванов Иван Петрович , а программа нам выводит: Иванов И. П.
*/

error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$base_uri = $_SERVER['PHP_SELF'];

function modify_it($string)
{
    $replaced = preg_replace(['/(?<=\ \p{Lu})\p{Ll}*/u', '/\s\s+/'], ['.', ' '], $string);
    return $replaced;
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    form {
        outline: black solid 1px;
        padding: 1em;
        max-width: 30%;
    }

    input {
        width: 100%;
    }
</style>

<body>
    <?php
    if (isset($_GET['fio'])) {
        echo $_GET['fio'] . '<br>';
        echo modify_it($_GET['fio']);
    }
    ?>
    <form action="" method="get">
        <label>
            Введите полностью<br>
            Фамилию Имя Отчество<br>
            <input type="text" name="fio" id="fio" value="Иванов     Иван      Петрович"><br><br>
        </label>
        <button type="submit">Отправить</button>
    </form>
</body>

</html>