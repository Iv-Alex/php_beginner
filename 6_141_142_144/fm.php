<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$root_dir = './' . basename(__FILE__);

//текущая директория
// TODO save current directory to session variable
$cur_dir = dirname($root_dir);

//функция выводит список директорий и файлов
function print_dir($dir)
{
    $file_list = array_diff(scandir($dir), ['.']);
    $dir_side = '';
    $file_side = '';
    foreach ($file_list as $key => $item) {
        $f_name = $item;
        $f_full_name = $dir . '/' . $f_name;
        $f_size = ($isdir = is_dir($f_full_name)) ? '---' : human_filesize(filesize($f_full_name));
        $f_actions = ($item == '..') ? '' : implode(' ', get_f_actions($file_list, $key));
        $tr = "<tr><td>$f_name</td><td>$f_size</td><td>$f_actions</td></tr>";
        $isdir ? $dir_side .= $tr : $file_side .= $tr;
    }
    $table_body = '<tbody>' . $dir_side . $file_side . '</tbody>';
    return $table_body;
}

//обработка загрузки файла. Предполагается, что расширения обработаны формой
if (isset($_POST['submit'])) {
    $source = $_FILES['files'];
    // TODO можно отправлять в GET флаг о наличии ошибок, а в сессии хранить
    // развернутую информацию по каждому случаю ошибки
    // TODO около сообщения об ошибках сделать ссылку для pop-up инф-ции об ошибках
    $errors = array();
    foreach ($source['name'] as $key => $name) {
        $extension = '.' . strtolower(pathinfo($name)['extension']);
        // проверяем на ошибки
        if ($source['error'][$key] == 0) {
            //если нет ошибок
            //формируем имя файла, добавляем путь и расширение
            $file_name = $cur_dir . '/' . $name;
            // копируем файл из временной папки в постоянную
            if (move_uploaded_file($source['tmp_name'][$key], $file_name)) {
                // успешная загрузка
            } else {
                // сообщаем об ошибке загрузки файла на сервер
                $errors[] = '103';
            }
        } else {
            // другие ошибки - сообщаем код
            $errors[] = $source['error'][$key];
        }
    }
    $get_params = empty($errors) ? '' : '?errors=' . implode(',', $errors);
    header('Location: ' . $root_dir . $get_params);
} else {
    // ничего не делаем
}

//Функция возвращает перечень возможных действий с файлом/директорией
//в виде массива ссылок
//(&МассивФайлов, ID(key), DeleteModul, ДоступныеДляРедактированияТипыФайлов)
function get_f_actions(&$items, $id, $types = ['TXT', 'PHP', 'PL', 'HTM', 'HTML'])
{
    global $root_dir;
    $actions = array();
    $actions[] = '<a href="">' . 'Переименовать' . '</a>';
    $actions[] = '<a href="'. $root_dir . '?delete=' . $items[$id] . '">Удалить</a>';
    is_dir($items[$id]) ? true : $actions[] = '<a href="">' . 'Скачать' . '</a>';
    if (
        isset(pathinfo($items[$id])['extension'])
        && in_array(strtoupper(pathinfo($items[$id])['extension']), $types)
    ) {
        $actions[] = '<a href="">' . 'Редактировать' . '</a>';
    } else {
        //ничего не делаем
    }
    return $actions;
}

//функция возвращает удобочитаемый размер файлов
//основа взята с php.net
function human_filesize($bytes, $decimals = 2)
{
    $sz = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        h1 {
            font-size: 1em;
            text-align: center;
        }

        .main {
            max-width: 800px;
            margin: 0 auto;
        }

        .file-panel {
            width: 100%;
            border-collapse: collapse;
        }

        .file-panel,
        .file-panel th,
        .file-panel td {
            border: 1px solid black;
        }

        #upload-form {
            margin: 30px 0;
            text-align: right;
        }

        span.err {
            display: block;
            color: red;
        }
    </style>
</head>

<body>
    <main>
        <div class="main">
            <h1>Файловый менеджер</h1>
            <form name="upload_form" id="upload-form" action="" enctype="multipart/form-data" method="post">
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                <input id="fln" type="file" name="files[]" class="input" multiple title="Выберите файлы для загрузки" required value="Обзор...">
                <input type="submit" name="submit" class="submit" value="Загрузить в текущую папку">
                <?= isset($_GET['errors']) ? '<span class="err">При попытке загрузки файла(ов) бнаружены ошибки</span>' : '' ?>
            </form>
            <table class="file-panel">
                <thead>
                    <tr>
                        <th>Имя файла или папки</th>
                        <th>Размер</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <?= print_dir($cur_dir) ?>
            </table>
        </div>
    </main>
</body>

</html>