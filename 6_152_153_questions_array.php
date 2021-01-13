<?php
/*
Задание к уроку #152 "Массив вопросов и ответов"
Дано: $q - многомерный массив с вопросами и ответами. Только один ответ верный.
Необходимо вывести форму для голосования (также как у нас тут после уроков)
с вопросами и ответами, чтобы пользователь мог выбрать ответы и узнать верно
он ответил или нет.
Задание к уроку #153 "Массив вопросов и ответов 2"
Задача аналогична предыдущей, только вопросы и ответы нужно выводить в случайном порядке.
*/

error_reporting(E_ALL);
session_start();
header('Content-Type: text/html; charset=utf-8');
$base_uri = $_SERVER['PHP_SELF'];

if (isset($_POST['submit'])) {
    //считаем количество правильных ответов
    $result = 0;
    foreach ($_SESSION['questions'] as $key => $value) {
        if (isset($_POST[$key])) $result += $value[1][intval($_POST[$key])][1];
    }
    //если количество правильных ответов соответствует ожидаемому, то Ok
    $pass = ($result == $_SESSION['result']);
    header('Location: ' . $base_uri . '?pass=' . $pass);
}

$questions = array();
$questions[] =
    ['Вычислите выражение (вычитание)', [
        ['9 - 8 = 2', 0],
        ['9 - 5 = 6', 0],
        ['10 - 5 = 8', 0],
        ['4 - 2 = 2', 1],
        ['11 - 2 = 11', 0],
        ['2 - 2 = 3', 0],
        ['9 - 7 = 3', 0],
        ['7 - 8 = 1', 0],
        ['4 - 10 = -3', 0]
    ]];
$questions[] =
    ['Вычислите выражение (сложение)', [
        ['7 + 4 = 12', 0],
        ['1 + 6 = 9', 0],
        ['5 + 5 = 13', 0],
        ['4 + 9 = 14', 0],
        ['5 + 3 = 10', 0],
        ['5 + 11 = 19', 0],
        ['9 + 7 = 17', 0],
        ['4 + 7 = 11', 1],
        ['6 + 6 = 15', 0],
        ['10 + 2 = 13', 0],
        ['5 + 8 = 15', 0],
        ['6 + 7 = 16', 0],
        ['6 + 1 = 8', 0]
    ]];
$questions[] =
    ['Вычислите выражение (умножение)', [
        ['2 x 10 = 22', 0],
        ['8 x 7 = 59', 0],
        ['8 x 9 = 73', 0],
        ['4 x 8 = 34', 0],
        ['10 x 10 = 100', 1],
        ['11 x 11 = 122', 0],
        ['10 x 4 = 42', 0],
        ['5 x 5 = 28', 0],
        ['9 x 10 = 91', 0]
    ]];

function show_questions(&$questions, $name_prefix = 'q', $randomise = false)
{
    $_SESSION['questions'] = array();                           //массив вопросов
    $_SESSION['result'] = 0;                                    //количество правильных ответов, на случай нескольких правильных в одном вопросе
    if ($randomise) shuffle($questions);                        //перемешиваем вопросы
    $questions_html = '<div class="questions">';
    for ($i = 0; $i < count($questions); $i++) {
        if ($randomise) shuffle($questions[$i][1]);             //перемешиваем ответы
        $questions_html .= '<label>' . $questions[$i][0] . '<br>';
        //сохраним текущее состояние массива вопросов в сессию
        $_SESSION['questions'][$name_prefix . $i] = [$questions[$i][0], $questions[$i][1]];
        for ($j = 0; $j < count($questions[$i][1]); $j++) {
            $questions_html .=
                '<input type="radio" name="' . $name_prefix . $i . '" value="' . $j . '" required>' .
                $questions[$i][1][$j][0] . '<br>';
            $_SESSION['result'] += $questions[$i][1][$j][1];
        }
        $questions_html .= '</label>';
    }
    $questions_html .= '</div>';
    return $questions_html;
}

function show_result()
{
    if (isset($_GET['pass'])) {
        if (($_GET['pass']) == true) {
            $result = '<div class="ok">Всё правильно!</div>';
        } else {
            $result = '<div class="err">Есть ошибки, попробуйте ещё раз</div>';
        }
    } else {
        $result = '';
    }
    return $result;
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
    #questions {
        outline: black solid 1px;
        padding: 1em;
        max-width: 30%;
    }

    #questions .submit {
        text-align: right;
    }

    .ok {
        color: green;
    }

    .err {
        color: red;
    }
</style>

<body>
    <?= show_result() ?>
    <form id="questions" action="" method="post">
        <?= show_questions($questions, 'que', true) ?>
        <div class="submit">
            <button id="submit" name="submit" type="submit">Отправить</button>
        </div>
    </form>
</body>

</html>