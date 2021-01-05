<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$base_uri = $_SERVER['PHP_SELF'];

//текущая директория
// TODO save current directory to session variable
$cur_dir = dirname(basename(__FILE__));

//функция выводит список директорий и файлов
function print_dir($dir)
{
    $file_list = array_diff(scandir($dir), ['.']);
    $dir_side = '';
    $file_side = '';
    foreach ($file_list as $key => $item) {
        $f_name = $item;
        $f_full_name = $dir . '/' . $f_name;
        // ! filesize() corrupt for > 2Gb and generates errors
        // ! realFileSize() doesn't work for unreadable files, and returns FALSE
        $f_size = ($isdir = is_dir($f_full_name)) ? '---' : human_filesize(realFileSize($f_full_name));
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
    header('Location: ' . $base_uri . $get_params);
} else {
    // ничего не делаем
}

//обработка удаления
if (isset($_GET['delete'])) {
    $del_file = $cur_dir . '/' . urldecode($_GET['delete']);
    unlink($del_file);
    header('Location: ' . $base_uri);
} else {
    // ничего не делаем
}

//Функция возвращает перечень возможных действий с файлом/директорией
//в виде массива ссылок
//(&МассивФайлов, ID(key), DeleteModul, ДоступныеДляРедактированияТипыФайлов)
function get_f_actions(&$items, $id, $types = ['TXT', 'PHP', 'PL', 'HTM', 'HTML'])
{
    global $base_uri;
    $actions = array();
    $actions[] = '<a href="">' . 'Переименовать' . '</a>';
    $actions[] = '<a href="' . $base_uri . '?delete=' . urlencode($items[$id]) . '">Удалить</a>';
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

// from php.net
/**
 * Return file size (even for file > 2 Gb)
 * For file size over PHP_INT_MAX (2 147 483 647), PHP filesize function loops from -PHP_INT_MAX to PHP_INT_MAX.
 *
 * @param string $path Path of the file
 * @return mixed File size or false if error
 */

function realFileSize($path)
{
    //if (!file_exists($path))
      //  return false;

    $size = filesize($path);

    if (!($file = fopen($path, 'rb')))
        return false;

    if ($size >= 0) { //Check if it really is a small file (< 2 GB)
        if (fseek($file, 0, SEEK_END) === 0) { //It really is a small file
            fclose($file);
            return $size;
        }
    }

    //Quickly jump the first 2 GB with fseek. After that fseek is not working on 32 bit php (it uses int internally)
    $size = PHP_INT_MAX - 1;
    if (fseek($file, PHP_INT_MAX - 1) !== 0) {
        fclose($file);
        return false;
    }

    $length = 1024 * 1024;
    while (!feof($file)) { //Read the file until end
        $read = fread($file, $length);
        $size = bcadd($size, $length);
    }
    $size = bcsub($size, $length);
    $size = bcadd($size, strlen($read));

    fclose($file);
    return $size;
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