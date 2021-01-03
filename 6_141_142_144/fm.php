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
    $table_body = '<tbody>';
    foreach ($file_list as $item) {
        $f_name = $item;
        $f_full_name = $dir . '/' . $f_name;
        $f_size = is_dir($f_full_name) ? '---' : human_filesize(filesize($f_full_name));
        $f_actions = ($item == '..') ? '' : implode(' ', get_f_actions($file_list, 0));
        $table_body .= "<tr><td>$f_name</td><td>$f_size</td><td>$f_actions</td></tr>";
    }
    $table_body .= '</tbody>';
    return $table_body;
}

//Функция возвращает перечень возможных действий с файлом/директорией
//в виде массива ссылок
//(&МассивФайлов, ID(key), ДоступныеДляРедактированияТипыФайлов)
function get_f_actions(&$items, $id, $types = ['TXT', 'PHP', 'PL', 'HTM', 'HTML'])
{
    $actions = array();
    $actions[] = '<a href="">' . 'Переименовать' . '</a>';
    $actions[] = '<a href="">' . 'Удалить' . '</a>';
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
        input[id="fln"] {
            position: relative;
        }

        input[type=file]:before {
            content: "Выбрать файл";
            margin: 0 5px;
            padding: 5px;
            top: 0px;
            display: inline-block;
            border: 1px solid #aaa;
            background-color: #fff;
            background-image: -webkit-linear-gradient(bottom, rgba(85, 85, 85, .1), rgba(255, 255, 255, .1));
            background-image: -moz-linear-gradient(bottom, rgba(85, 85, 85, .1), rgba(255, 255, 255, .1));
            background-image: -o-linear-gradient(bottom, rgba(85, 85, 85, .1), rgba(255, 255, 255, .1));
            background-image: -ms-linear-gradient(bottom, rgba(85, 85, 85, .1), rgba(255, 255, 255, .1));
            background-image: linear-gradient(to top, rgba(85, 85, 85, .1), rgba(255, 255, 255, .1));
            box-shadow: inset 0 0 1px #fff;
            text-shadow: 0 1px 0 #fffcf6;
            border-radius: 3px;
            cursor: pointer;
            visibility: visible;
            position: absolute;
        }
    </style>
</head>

<body>
    <h4>Файловый менеджер</h4>
    <form name="upload_form" id="upload-form" action="" enctype="multipart/form-data" method="post">
        <input id="fln" type="file" name="files[]" class="input" multiple title="Выберите файлы для загрузки" required value="Обзор...">
        <input type="submit" name="submit" class="submit" value="Загрузить в текущую папку">
    </form>
    <table>
        <thead>
            <tr>
                <th>Имя файла или папки</th>
                <th>Размер</th>
                <th>Действия</th>
            </tr>
        </thead>
        <?= print_dir($cur_dir) ?>
    </table>
</body>

</html>