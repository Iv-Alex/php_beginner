<?php

/*
Отмена повторной отправки формы здесь:
https://myrusakov.ru/php-resubmit.html
Обрати внимание, писать надо в начале страницы,ДО отправки заголовков
Присвоение "Массив1 = Массив" работает.
*/

error_reporting(E_ALL);
mb_internal_encoding('utf-8');

//--- сохраняем данные $_POST в сессию и в дальнейшем, после принудительного обновления,
// и, соответственно, очистки $_POST, работаем с сессией
if (count($_POST) > 0) {

    //пробуем копировать массив в массив (работает).
    //   ________ !!!!!!!!!!! в корне не правильно весь $_SESSION заменять на $_POST !!!!!!!! ___________________
    // это для проверки примера. На самом деле надо просто в $_SESSION добавить нужные значения из $_POST
    $my_post = $_POST;
    //-----
    header("Location: " . $_SERVER["REQUEST_URI"]);
    print_r($my_post);

    exit;
}
//---

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регистрации</title>
</head>
<style>
    #container {
        max-width: 200px;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    #submit {
        height: 50px;
        width: 170px;
    }

    .errors {
        padding: 10px;
        background: #ffbcbc;
        color: red;
        border: red solid 1px;
    }

    .ok {
        padding: 10px;
        background: #b2ffb3;
        color: green;
        border: green solid 1px;
    }
</style>

<body>

    <pre>
<?php
/*print_r($_GET);
if (isset($_GET['name'])) {
    echo 'Приветствую, ' . $_GET['name'];
}
*/

echo 'Содержание $_POST:<br>';
print_r($_POST);
echo 'Содержание $my_post:<br>';
print_r($my_post);

$user_name = '';
$show_form = true;
if (count($my_post) > 0) {
    $user_name = isset($my_post['name']) ? $my_post['name'] : '';
    $errors = array();
    if (mb_strlen($my_post['name']) < 2) {
        $errors[] = 'Имя должно содержать хотя бы 2 символа';
    }
    if (empty($my_post['password'])) {
        $errors[] = 'Пароль обязателен';
    }
    if (count($errors) > 0) {
        echo '<div class="errors">' . implode('<br>', $errors) . '</div>';
    } else {
        $show_form = false;
    }
} else {
}
?>
    </pre>

    <div id="container">
        <h3>Регистрация</h3>
        <?php if ($show_form) { ?>
            <form method="post" autocomplete="on">
                <p>
                    Ваше имя:<br>
                    <input name="name" type="text" placeholder="Например: Вася" autofocus size="25" value="<?= $user_name ?>">
                </p>
                <p>
                    Пароль:<br>
                    <input name="password" type="password" size="25">
                </p>
                <p>
                    Ваш пол:<br>
                    <input name="gender" type="radio" value="male" checked>М
                    <input name="gender" type="radio" value="female">Ж<br>
                </p>
                <p>
                    Расскажите немного о себе:<br>
                    <textarea name="about" id="about" cols="27" rows="6"></textarea>
                </p>
                <p>
                    В какой стране проживаете:<br>
                    <input name="country" type="text" placeholder="Например: Россия" size="25">
                </p>
                <p>
                    Ваше образование:<br>
                    <input name="education" type="checkbox">Среднее<br>
                    <input name="education" type="checkbox">Высшее<br>
                    <input name="education" type="checkbox">Неоконченное высшее<br>
                </p>
                <p>
                    Дата рождения:<br>
                    <input name="day" type="number" min="1" max="31" maxlength="2" size="2">
                    <input name="month" type="number" min="1" max="12" maxlength="2" size="2">
                    <select name="year" size="1">
                        <option value="1920">1920</option>
                        <option value="1921">1921</option>
                        <option value="1922">1922</option>
                        <option value="1923">1923</option>
                        <option value="1924">1924</option>
                        <option value="1925">1925</option>
                        <option value="1926">1926</option>
                        <option value="1927">1927</option>
                        <option value="1928">1928</option>
                        <option value="1929">1929</option>
                        <option value="1930">1930</option>
                        <option value="1931">1931</option>
                    </select>
                </p>
                <div>
                    <input id="submit" type="submit" value="Зарегистрироваться" accesskey="s">
                </div>
            </form>
        <?php } else {
            echo '<div class="ok">Всё хорошо, приветствуем тебя,  ' . $_POST['name'] . '</div>';
        }

        ?>
    </div>
</body>
</script>

</html>