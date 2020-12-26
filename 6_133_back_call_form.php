<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$root_dir = './6_133_back_call_form.php';

//Значения полей формы по умолчанию
$tmp_name = '';
$tmp_email = '';
$tmp_phone = '';
$tmp_message = '';
//вид страницы по умолчанию (варианты обработки страницы: default, err, sent, etc.)
$form_skin = 'default';


//Для обработки множества вариантов ошибок множества полей введем кодировку
// - коды полей
$name_err = '1';
$email_err = '2';
$phone_err = '3';
$message_err = '4';
// - массив ошибок
$err_codes = array(
    "01" => "Поле обязательно к заполнению",
    "02" => "Поле должно содержать только цифры",
    "03" => "E-mail адрес не корректен",
    "04" => "Не соответствует шаблону (проверьте количество символов)",
);
//Массив ошибок
//заполняется [код_поля, код_ошибки]
$errors = array();

//Функция вывода ошибок в форму (массив_кодов_ошибок, массив_ошибок([код_поля, код_ошибки]), код_поля, стиль_CSS)
function get_errors($err_codes, $errors, $field_code, $style = 'error')
{
    $err_html = '';
    foreach ($errors as list($ff, $ee)) {
        if ($ff == $field_code) {
            $err_html .= '<span class="' . $style . '">' . $err_codes[$ee] . '</span>';
        } else {
            // ничего не делаем
        }
    }
    return $err_html;
}

//обработка полей формы и попытка отправки
if (isset($_POST['submit'])) {
    if ($_POST['user_name'] !== '') {
        $tmp_name = $_POST['user_name'];
    } else {
        $errors[] = [$name_err, '01'];
    }
    if ($_POST['user_email'] !== '') {
        $tmp_email = $_POST['user_email'];
        //проверяем количество и позицию @ в email
        if ((($a_pos = stripos($tmp_email, '@')) === false) ||
            !($a_pos == strrpos($tmp_email, '@')) ||
            ($a_pos == 0)
        ) {
            $errors[] = [$email_err, '03'];
        } else {
            //ничего не делаем
        }
    } else {
        $errors[] = [$email_err, '01'];
    }
    if ($_POST['user_phone'] !== '') {
        $tmp_phone = $_POST['user_phone'];
        if ($tmp_phone !== '' . intval($tmp_phone)) $errors[] = [$phone_err, '02'];
        if (strlen($tmp_phone) !== 10)          $errors[] = [$phone_err, '04'];
    } else {
        $errors[] = [$phone_err, '01'];
    }
    if ($_POST['user_message'] !== '') {
        $tmp_message = $_POST['user_message'];
    } else {
        $errors[] = [$message_err, '01'];
    }
    if (count($errors) == 0) {
        //если нет ошибок, отправка формы
        $headers = 'From: temp@logycon.ru' . "\r\n" . 'Content-Type: text/plain; charset=utf-8' . "\r\n";
        mail(
            'temp@logycon.ru',
            '=?utf-8?B?' . base64_encode('Заявка с сайта www.logycon.ru') . '?=',
            'Имя: ' . $tmp_name . "\r\n" .
                'E-mail: ' . $tmp_email . "\r\n" .
                'Телефон: ' . $tmp_phone . "\r\n" .
                'Сообщение: ' . $tmp_message,
            $headers
        );
        // вызов headers (ошибки/успешно)
        header('Location: ' . $root_dir . '?result=ok&name=' . base64_encode($tmp_name));
    } else {
        //сообщаем об ошибках
        $form_skin = 'err';
    }
} else {
    //ничего не делаем
}

//Проверка $_GET
if ((isset($_GET['result'])) && (($_GET['result']) == 'ok')) {
    $form_skin = 'sent';
} else {
    //ничего не делаем
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail form</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        #my {
            min-width: 500px;
            width: 50%;
            padding: 30px 40px 30px 30px;
            background-color: #F5F5F5;
            outline: solid 1px silver;
            color: #555;
        }

        #my input,
        #my textarea {
            width: 100%;
            display: block;
            border: none;
            outline: solid 1px grey;
            padding: 5px;
            font-size: 2vh;
        }

        #my input:hover,
        #my input:active {
            outline-width: 2px;
        }

        input.submit {
            background-color: #CCC;
            height: 3em;
        }

        input.submit:focus {
            background-color: #AAA;
        }

        #my label {
            display: block;
            padding: 1em 0 0 0;
        }

        .comment {
            margin: 1em 0 2em 0;
        }

        .error {
            color: red;
        }

        span.error {
            display: block;
        }

        div.message {
            min-width: 500px;
            width: 50%;
        }

        div.error {
            margin: 1em 0;
            padding: .5em 2em;
            outline: solid 1px red;
            background-color: #FDD;
        }

        div.ok {
            margin: .5em 0;
            text-align: center;
            padding: 2em 2em;
            outline: solid 1px green;
            background-color: #DFD;
            font-size: large;
        }

        .div-center {
            margin: 2em auto;
        }

        div.text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <?php
    if ($form_skin == 'sent') {
    ?>
        <div class="message div-center">
            <div class="ok">
                <?= (isset($_GET['name'])) ? base64_decode($_GET['name']) . ', ': '' ?>
                Ваше сообщение успешно отправлено.
            </div>
            <div class="text-right">
                <a href="<?= $root_dir ?>">Отправить еще раз</a>
            </div>
        </div>
    <?php
    } else {
    ?>
        <?= ($form_skin == 'err') ? '<div class="error message">В форме обнаружены ошибки</div>' : '' ?>
        <form action="" enctype="multipart/form-data" method="post" id="my">
            <label>
                Ваше имя *
                <input type="text" name="user_name" id="user_name" title="Ваше имя" value="<?= $tmp_name ?>">
                <?= ($form_skin == 'err') ? get_errors($err_codes, $errors, $name_err) : '' ?>
            </label>
            <label>
                Ваш e-mail *
                <input name="user_email" id="user_email" title="Ваш e-mail" value="<?= $tmp_email ?>">
                <?= ($form_skin == 'err') ? get_errors($err_codes, $errors, $email_err) : '' ?>
            </label>
            <label>
                Ваш телефон (10 цифр без международного кода)<br>
                <input id="user_phone" name="user_phone" value="<?= $tmp_phone ?>">
                <?= ($form_skin == 'err') ? get_errors($err_codes, $errors, $phone_err) : '' ?>
            </label>
            <label>
                Сообщение *
                <textarea name="user_message" id="user_message" cols="70" rows="6"><?= $tmp_message ?></textarea>
                <?= ($form_skin == 'err') ? get_errors($err_codes, $errors, $message_err) : '' ?>
            </label>
            <div class="comment">
                Поля, отмеченные звездочкой (*), обязательны для заполнения
            </div>
            <input type="submit" name="submit" class="submit" value="Отправить">
        </form>
    <?php
    }
    ?>

</body>

</html>