<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$root_dir = '/6_131_file_load_1/';
$upload_dir = 'uploads/';
$accepted_files = ".doc, .docx, .odt";  //максимальная длина имени файла при генерации
define('UPLOAD_ERRORS', array(
    2 => 'Превышен допустимый размер файла.',
    4 => 'Не выбран файл для загрузки.',
    100 => 'Код ошибки:',
    101 => 'Недопустимый тип файла.',
    102 => 'Ошибка пути',
    103 => 'Ошибка удаления файла',
));

// проверка директории на наличие
$file_name = './' . $upload_dir . date('Y') . '/' . date('m') .  '/' . date('d');
if (!is_dir($file_name)) {
    mkdir($file_name, 0777, true);
} else {
}

//обработка загрузки файла
if (isset($_POST['submit'])) {
    $get_params = ''; // строка для передачи результатов обработки файла после перезагрузки страницы
    $extension = '.' . strtolower(pathinfo($_FILES['path']['name'])['extension']);
    // проверяем на ошибки $_FILES
    if ($_FILES['path']['error'] == 0) {                            //если нет ошибок
        $accepted = explode(', ', $accepted_files);
        //проверяем расширение
        if (array_search($extension, $accepted, true) !== false) {
            // формируем путь для загрузки файла
            $file_name = './' . $upload_dir . date('Y') . '/' . date('m') .  '/' . date('d');
            // проверяем путь, создаем рекурсивно при отсутствии
            $is_path = is_dir($file_name) ? true : mkdir($file_name, 0777, true);
            if ($is_path) {
                //формируем имя файла, добавляем путь и расширение
                $file_name .= '/' . date('Y-m-d_H-i-s') . $extension;
                // копируем файл из временной папки в постоянную
                if (move_uploaded_file($_FILES['path']['tmp_name'], $file_name)) {
                    // сообщаем об успешной загрузке
                    $get_params = '?success=' . $file_name;
                } else {
                    // сообщаем об ошибке загрузки файла на сервер
                }
            } else {
                // сообщаем об ошибке создания/доступа пути
                $get_params = '?error=102';
            }
        } else {
            // сообщаем о неверном расширении
            $get_params = '?error=101&sh_ext=true';
        }
    } elseif ($_FILES['path']['error'] == 4) {
        // файл для загрузки не выбран, сообщаем об этом
        $get_params =  '?error=4';
    } elseif ($_FILES['path']['error'] == 2) {
        // слишком большой файл, сообщаем об этом
        $get_params = '?error=2&max_size=' . round(($_POST['MAX_FILE_SIZE'] / 1024 / 1024), 2);
    } else {
        // другие ошибки - сообщаем код
        $get_params = '?error=101&err_code=' . $_FILES['path']['error'];
    }
    header('Location: ' . $root_dir . $get_params);
} else {
    // ничего не делаем
    // обычная загрузка страницы
}

//обработка удаления файла
if (isset($_GET['del_file'])) {
    header('Location: ' . $root_dir . '?' . (unlink($_GET['del_file']) ? 'del_success=ok' : 'del_error=103'));
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

        .row {
            display: flex;
            justify-content: space-around;
        }

        .cell {
            width: 45%;
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

        .file-container:hover .delete-button {
            background: no-repeat url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAEQklEQVRYR7WXX2xTVRzHv79z+8eumSSAbKM8Ef8wImBce9uNJwjRYHziRTCQGB2G8mI0xuCLCfFFQ0h8GsSN+MR80xeUaEKyB1257UhcRCFBkX9LBt0eJI51bc855nfXW29vb9vbZfatOef3+37O7985lxDwdzWdfpq0fh1K7ZdC7DGU2q6E6K2ZP9bAX4ZSsxDiiib6LmNZj4O4pk6bCsnkC9IwTpGUhyHEU5322+tKLUOIrzXRZxnLutXOpiXAzNBQjzSMT6HUexDCCCTs3aRUlYi+qIZCn4zkcst+PnwBLNN8HlJ+C8PYuSZhjxFp/Ws1FDo0ksv94fXXBJDLZF6GUj8IYPN6iLt8FEnrV8xC4Re33wYAPrkCfv4fxB3NojSMEXck6gCzu3fHS+Fw3i/sIhaDWvZNYcsgGfE45NJS03otHWmnJuoAlmmeBfCB1yJx/Dg2HzyIGydPojw/Hygr0a1bMXjuHIqXLmFufNzP5kw6n/+IF2wAbjUF/OatdhZPjI7aDlbm5gJBOOKR/n7bbm5iohlCqaoMhwc5FTbA1XT6K9L6LTcqh33X5CTYofPrBBEdGMCO8+cRrYmzXenOHVw/dgyqXG6MhFIX0jMzozQzNLRBEs37DRk+BYcyCEQrcU5dZXGxKQ0KeCLD4X6yTPNNABdbJTcIBJ/YPvnAQN1N6e5d3MhmfcWdTaT1YbKSyQkI8U676vITcNLBdt4ole7dWxVfWGhbtKT1lzRtmgUDSHYq71YQIGpIEYvfzGZR7iBe07M4AosQYmMnAF73g3Dble7fx80TJ4KKs2mRcqZZFkA4CIAN4VPpdrU/eLAqXiwGdQUFlNcXgEP/6FHXAAsC2BTEittxB7elq88bUsBR6A6iGLwIfcQ57DzJotu2/dd+3UHYRdi5DWuz3Rmvds654LJZcBfYbbgGCLsNLdM8AmCyVQqiiQQGx8bQIO5ptciWLWuCIK3fIPuxKSWP4pgXIoi4YxPp61uFSCQCpYNHcamnp8++jCzTvADgbTcA3+d8GTWcnMcrz/YWQ6YVxPWjR5veE5poPGNZ7zq34XMk5e8QIuSGcF/H9mxvI94qEn7XsQIqYSkHk9eu/Vl/kORTqTOa6ENvGhhi04EDq+I+t5pf7dgX2NgYFi5f9n2Q1J7rH7NtHYCf4RXDsATwotepiESa7/MOg0NEo1ArK827pJx90tub2Tc1VWoA4D/Tw8PPGlJOA3gmyGDqeo9SDwXRSKpQuO3YNj3L86nUS5rox3WHUOohhHg1nc/PusF9P0w4EqFq9RtNtKvrU/oZSDkrhDjkPnnLCDgL08PDMUPK01DqfW93BIXiaieis8ux2Gkn517bjh+ndl1UKqeUEEcE0BNEnIcMEV0MVaufc6u1s+kI4Bj/tHdvb6Rcfg3Afk20B8B2BWzgdQH8DeA2ac35vbIUj3+/b2rqnyCw/wL+7xUQybrqggAAAABJRU5ErkJggg==);
            background-size: 16px;
        }

        .file-container {
            display: flex;
            justify-content: space-between;
            margin-left: 30px;
            margin-right: 10px;
            padding-top: 1em;
        }

        .file-container .delete-button {
            margin-left: 10px;
            vertical-align: middle;
            background-position: left center;
        }

        .file-container .delete-button a {
            display: inline-block;
            width: 16px;
            height: 16px;
            text-decoration: none;
            border-radius: 50%;
        }

        .file-container .delete-button a:hover {
            box-shadow: 0 0 15px red;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="cell">
            <?php
            if (isset($_GET['del_success'])) {
                //Показываем блок об успешно загруженном файле 
            ?>
                <section class="success">
                    <div class="ok-message">
                        <h4>Файл успешно удален.</h4>
                    </div>
                </section>
            <?php
            } elseif (isset($_GET['del_error'])) {
                //Показываем блок с ошибками 
            ?>
                <section class="errors">
                    <div class="err-message">
                        <h4><?= UPLOAD_ERRORS[$_GET['del_error']] ?></h4>
                    </div>
                </section>
            <?php
            } else {
                //ничего дополнительно не выводим 
            }
            ?>
            <section id="file-list">
                <h4 id="path"><?= ($current_path = './' . $upload_dir . date('Y') . '/' . date('m') .  '/' . date('d')) ?></h4>
                <div id="files">
                    <?php
                    foreach (scandir($current_path) as $current_file) {
                        if (!is_dir($current_file)) {
                    ?>
                            <div class="file-container">
                                <div class="file-link"><a href="<?= $current_path . '/' . $current_file ?>"><?= $current_file ?></a></div>
                                <div class="delete-button"><a href="./?del_file=<?= $current_path . '/' . $current_file ?>">&nbsp;</a></div>
                            </div>
                    <?php
                        } else {
                            // с директориями пока ничего не делаем
                        }
                    }
                    ?>

                </div>
            </section>
        </div>
        <div class="cell">
            <?php
            if (isset($_GET['success'])) {
                //Показываем блок об успешно загруженном файле 
            ?>
                <section class="success">
                    <div class="ok-message">
                        <h4>Файл успешно загружен.</h4>
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
                    <h2 class="h-center">Загрузить файл</h2>
                    <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
                    <input type="file" name="path" class="input" accepted="<?= $accepted_files ?>" title="Выберите файл"><br>
                    <input type="submit" name="submit" class="submit" value="Загрузить">
                </form>
            </section>
        </div>
    </div>
</body>

</html>