<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$root_dir = './';
$upload_dir = $root_dir . 'uploads/';
define('UPLOAD_ERRORS', array(
    2 => 'Превышен допустимый размер файла.',
    4 => 'Не выбран файл для загрузки.',
    100 => 'Код ошибки:',
    101 => 'Недопустимый тип файла.',
    102 => 'Ошибка пути',
    103 => 'Ошибка обработки файла на сервере',
));

$file_name_prefix = date('YmdHis');

// проверяем путь для загрузки картинок, создаем рекурсивно при отсутствии
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
} else {
    //ничего не делаем
}

//обработка загрузки файла. Предполагается, что расширения обработаны формой

if (isset($_POST['submit'])) {
    $source = $_FILES['img'];
    $get_params = '?success=' . $file_name_prefix; // строка для передачи результатов обработки файлов (имя группы и количество), по омолчанию - об успехе
    foreach ($source['name'] as $key => $name) {
        $extension = '.' . strtolower(pathinfo($name)['extension']);
        // проверяем на ошибки
        if ($source['error'][$key] == 0) {                            //если нет ошибок
            //формируем имя файла, добавляем путь и расширение
            $file_name = $upload_dir . $file_name_prefix . '_' . $key . $extension;
            // копируем файл из временной папки в постоянную
            if (move_uploaded_file($source['tmp_name'][$key], $file_name)) {
                // успешная загрузка
            } else {
                // сообщаем об ошибке загрузки файла на сервер
                $get_params = '?error=103';
            }
        } elseif ($source['error'][$key] == 4) {
            // файл для загрузки не выбран, сообщаем об этом
            $get_params =  '?error=4';
        } elseif ($source['error'][$key] == 2) {
            // слишком большой файл, сообщаем об этом
            $get_params = '?error=2&max_size=' . round(($_POST['MAX_FILE_SIZE'] / 1024 / 1024), 2);
        } else {
            // другие ошибки - сообщаем код
            $get_params = '?error=100&err_code=' . $source['error'][$key];
        }
    }
    header('Location: ' . $root_dir . $get_params);
} else {
    // ничего не делаем
    // обычная загрузка страницы
}

// * mail+attachment function
function mailto($to, $subject, $message, $attach = array(), $from = "Робот", $fromAddr = "noreply@mail.ru")
{

    $mb_internal_encoding = mb_internal_encoding();
    mb_internal_encoding('UTF-8');

    $headers = "Date: " . date("r") . "\r\n";
    $headers .= "From: =?UTF-8?B?" . base64_encode($from) . "?= <" . $fromAddr . ">\r\n";
    $headers .= "MIME-Version: 1.0\r\n";

    $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
    if (strpos($message, '/>')) $msgType = "text/html";
    else $msgType = "text/plain";
    if (is_string($attach)) $attach = array($attach);
    $files = array();
    foreach ($attach as $path) if (file_exists($path)) $files[] = $path;

    if ($files) {
        $boundary = md5(time());
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        $body  = "\r\n--$boundary\r\n";
        $body .= "Content-Type: $msgType; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n";
        $body .= "\r\n";
        $body .= $message;

        foreach ($files as $path) {
            $filename = mb_substr($path, mb_strrpos($path, '/') + 1);
            $body .= "\r\n--$boundary\r\n";
            $body .= "Content-Type: application/octet-stream\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename*=UTF-8''" . str_replace('+', '%20', urlencode($filename)) . "\r\n";
            $body .= "\r\n";
            $body .= chunk_split(base64_encode(file_get_contents($path)));
        }

        $body .= "\r\n--$boundary--\r\n";
    } else {
        $headers .= "Content-Type: $msgType; charset=UTF-8\r\n";
        $headers .= "Content-Transfer-Encoding: 8bit\r\n";
        $headers .= "\r\n";
        $body = $message;
    }
    mb_internal_encoding($mb_internal_encoding);
    return mail($to, $subject, $body, $headers);
}
// * --
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка файла</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }

        .row {
            display: flex;
            justify-content: space-around;
        }

        .cell {
            width: 60%;
            padding: 1em;
        }

        #article-form,
        .err-message,
        .ok-message {
            margin-bottom: 2em;
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

        #article-form {
            border: grey solid 1px;
            padding: 2em;
            box-shadow: 0 0 1em grey;
        }

        #article-form,
        #article-form input {
            font-family: 'Times New Roman', Times, serif;
            font-size: 18px;
        }

        #article-form label input {
            margin-bottom: 1em;
        }

        #article-form input.submit {
            margin-top: 1em;
        }

        .file-container {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }

        .file-container img {
            object-fit: cover;
            padding: 5px;
        }

        .file-container img.big {
            width: 300px;
            height: 300px;
            margin: 0 50%;
        }

        .file-container img.small {
            width: 150px;
            height: 150px;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="cell">
            <?php
            if (isset($_GET['success'])) {
                //Показываем блок об успешно загруженных файлах 
            ?>
                <section class="success">
                    <div class="ok-message">
                        <h4>Успешно загружены файлы:</h4>
                        <section id="file-list">
                            <!--Отобразить файлы-->
                            <div class="file-container">
                                <?php
                                // * attachment files array
                                $send_files = array();
                                // * --
                                foreach (scandir($upload_dir) as $current_file) {
                                    // разбираем имя файла для определения главного и индексов дополнительных
                                    $file_name_sides = explode('_', pathinfo($current_file)['filename']);
                                    // выводим только файлы, соответствующие имени загруженной группы фвйлов
                                    if ((!is_dir($current_file)) && ($file_name_sides[0] == $_GET['success'])) {
                                        // * add file to attach array
                                        $send_files[] = $upload_dir . $current_file;
                                        // * --
                                ?>
                                        <img class="<?= ((+$file_name_sides[1] == 0) ? 'big' : 'small') ?>" src="<?= $upload_dir . $current_file ?>" alt="<?= $current_file ?>">
                                <?php
                                    } else {
                                        // с директориями пока ничего не делаем
                                    }
                                }
                                // * send email with files
                                mailto('temp@logycon.ru', 'Test attach', "Don't warry", $send_files);
                                // * --
                                ?>
                            </div>
                        </section>
                    </div>
                </section>
            <?php
            } elseif (isset($_GET['error'])) {
                //Показываем блок с ошибками 
            ?>
                <section class="errors">
                    <div class="err-message">
                        <h4>
                            Ошибка загрузки файла.<br><?= UPLOAD_ERRORS[$_GET['error']] ?>
                            <?php
                            // вывод дополнительных данных об ошибке, при наличии
                            // обработка ошибок заполнения формы
                            if (isset($_GET['max_size'])) {
                                echo '<br>Максимальный размер для загрузки ' . $_GET['max_size'] . 'Mb';
                            } elseif (isset($_GET['err_code'])) {
                                echo ' Код: ' . $_GET['err_code'];
                            } elseif (isset($_GET['sh_ext'])) {
                                echo '<br>Разрешены к загрузке: ' . $accepted_files;
                            } else {
                                // ничего не добавляем
                            }
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
                    <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
                    <label>
                        Главное фото<br>
                        <input type="file" name="img[]" class="input" accept="image/*" title="Выберите файл" required><br>
                    </label>
                    <label>
                        Другие фото<br>
                        <input type="file" name="img[]" class="input" accept="image/*" multiple title="Выберите дополнительные файлы" required><br>
                    </label>
                    <input type="submit" name="submit" class="submit" value="Загрузить">
                </form>
            </section>
        </div>
    </div>
</body>

</html>