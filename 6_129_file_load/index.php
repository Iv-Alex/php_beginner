<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$root_dir = '/6_129_file_load/';
$max_file_name_length = 50;  //максимальная длина имени файла при генерации

//обработка загрузки файла
if (isset($_POST['submit'])) {

    if ($_FILES['path']['error'] == 0) {                            //если нет ошибок, загружаем файл

        //формируем имя файла
        $file_name = '_no_name';
        switch ($_POST['file_name']) {
            case 'ymd':
                $file_name = date('Y-m-d');
                break;
            case 'ymdhm':
                $file_name = date('Y-m-d_H-i');
                break;
            case 'string':
                $num = +$_POST['symbols_count'];
                //если не указано количество символов, то берем по максимуму
                if (($num < 1) || ($num > $max_file_name_length)) {
                    $warning_symbols = true; //сообщить о выборе количества символов по умолчанию
                    $num = $max_file_name_length;
                } else {
                    //ничего не делаем
                }
                //генерируем случайную последовательность букв английского алфавита
                for ($file_name = '', $letters = range('a', 'z'), $i = 0; $i < $num; $i++) {
                    $file_name .= $letters[mt_rand(0, 25)];
                }
                break;
        }
        $file_name .= '.' . pathinfo($_FILES['path']['name'])['extension']; //

        // копируем файл из временной папки в постоянную
        move_uploaded_file($_FILES['path']['tmp_name'], $file_name);

        // обновляем страницу, сообщаем об успешной загрузке
        header('Location: ' . $root_dir . '?success=' . $file_name . (isset($warning_symbols) ? '&warning_symbols=true' : ''));
    } elseif ($_FILES['path']['error'] == 4) {                      // файл для загрузки не выбран, сообщаем об этом
        header('Location: ' . $root_dir . '?error=empty');
    } elseif ($_FILES['path']['error'] == 2) {                      // слишком большой файл, сообщаем об этом
        header('Location: ' . $root_dir . '?error=large&max_size=' . round(($_POST['MAX_FILE_SIZE'] / 1024 / 1024), 2));
    } else {                                                        // другие ошибки - сообщаем код
        header('Location: ' . $root_dir . '?error=' . $_FILES['path']['error']);
    }
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка файла</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        #article-form,
        .err-message,
        .ok-message {
            width: 25em;
            margin: 2em auto;
        }

        .err-message {
            border: red solid 1px;
            background: salmon;
            color: white;
            padding: 1em 2em;
            box-shadow: 0 0 .5em red;
            text-align: center;
        }

        .ok-message {
            border: green solid 1px;
            background: lightgreen;
            color: green;
            padding: 1em 2em;
            box-shadow: 0 0 .5em green;
            text-align: center;
        }

        .ok-message .warning {
            color: black;
        }

        #article-form {
            border: grey solid 1px;
            padding: 0 2em 2em;
            box-shadow: 0 0 1em grey;
        }

        #article-form input.submit {
            margin-top: 1em;
        }

        #symbols-count {
            width: 2em;
        }

        .h-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <?php
    if (isset($_GET['success'])) {
        //Показываем блок об успешно загруженном файле 
    ?>
        <section id="success">
            <div class="ok-message">
                <h4>Файл успешно загружен.</h4>
                <?php
                if (isset($_GET['warning_symbols'])) {
                    echo '<span class="warning">Была установлена длина имени файла по умолчанию: '
                        . $max_file_name_length . '</span><br>';
                } else {
                }
                ?>
                <span>
                    Имя файла: <?= $_GET['success'] ?>
                    <a href="./<?= $_GET['success'] ?>">Скачать</a>
                </span>
            </div>
        </section>
    <?php
    } elseif (isset($_GET['error'])) {
        //Показываем блок с ошибками 
    ?>
        <section id="errors">
            <div class="err-message">
                <h4>
                    Ошибка во время загрузки файла.<br>
                    <?php
                    // обработка ошибок заполнения формы
                    if ($_GET['error'] == 'empty') {
                        $err_msg = 'Не выбран файл для загрузки';
                    } elseif ($_GET['error'] == 'large') {
                        $err_msg = 'Максимальный размер файла ' . $_GET['max_size'] . 'Mb';
                    } else {
                        $err_msg = 'Ошибка загруки файла. Код: '
                            . (isset($_GET['error']) ? $_GET['error'] : 'unknown');
                    }
                    echo $err_msg;
                    ?>
                </h4>
            </div>
        </section>
    <?php
    } else {
        //ничего дополнительно не выводим 
    }
    ?>
    <section id="add_file">
        <form name="article_form" id="article-form" action="" enctype="multipart/form-data" method="post">
            <h2 class="h-center">Загрузить файл</h2>
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
            <input type="file" name="path" class="input" title="Выберите файл">
            <h2 class="h-center">Имя файла:</h2>
            <label>
                <input type="radio" name="file_name" value="ymd" checked>
                <span> текущая дата в формате ГГГГ-ММ-ДД</span>
            </label><br>
            <label>
                <input type="radio" name="file_name" value="ymdhm">
                <span> текущая дата в формате ГГГГ-ММ-ДД_ЧЧ-ММ</span>
            </label><br>
            <label>
                <input type="radio" name="file_name" value="string">
                <span>
                    случайная строка&nbsp;
                    <input id="symbols-count" type="text" name="symbols_count" placeholder="<?= $max_file_name_length ?>">
                    &nbsp;символов (от 1 до <?= $max_file_name_length ?>)
                </span>
            </label><br>
            <input type="submit" name="submit" class="submit" value="Сохранить">
        </form>
    </section>

</body>

</html>