<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');
session_start();

$base_uri = $_SERVER['PHP_SELF'];

//текущая директория
//если переменная сессии не установлена или передан несуществующий путь,
//то текущей ставим директорию скрипта
if (!isset($_SESSION['cur_dir']) || ($_SESSION['cur_dir'] === false))
    $_SESSION['cur_dir'] = realpath(dirname(basename(__FILE__)));
$cur_dir = $_SESSION['cur_dir'];

//Ошибки
//TODO написать обработчики событий Warning
$error_codes = array(
    //10xxx ошибки загрузки файлов
    '1003' => 'Ошибка обработки файла на сервере',
    '102' => 'Превышен допустимый размер файла.',
    '104' => 'Не выбран файл для загрузки.',
    //20xxx ошибки действий с файлами
    '2001' => 'Файл или папка с таким именем уже существует',
    //9999 неизвестная ошибка, сообщить код
    '9999' => 'Код ошибки '
);

//Примем накопившиеся ошибки сессии и обнулим массив сессии для сбора новых ошибок
if (isset($_SESSION['fm_errors']) && !empty($_SESSION['fm_errors'])) {
    $fm_errors = $_SESSION['fm_errors'];
} else {
    $fm_errors = false;
}
$_SESSION['fm_errors'] = array();

//обработка $_POST
//обработка загрузки файла
if (isset($_POST['submit'])) {
    $source = $_FILES['files'];
    //Массив для ошибок
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
                $errors[] = ['1003', $name];
            }
        } else {
            // другие ошибки - сообщаем код
            $errors[] = [('10' . $source['error'][$key]), $name];
        }
    }
    //Передаем ошибки в сессию
    if (!empty($errors)) $_SESSION['fm_errors'] = array_merge($_SESSION['fm_errors'], $errors);
    header('Location: ' . $base_uri);

    //обработка изменения файла
} elseif (isset($_POST['save_file'])) {
    if (isset($_POST['file_contents'])) {
        file_put_contents($_SESSION['edit_file'], $_POST['file_contents']);
    } else {
        //ничего не делаем
    }
    unset($_SESSION['edit_file']);
} else {
    // ничего не делаем
}

//TODO написать обработчики событий Warning
//обработка $_GET['action']
if (isset($_GET['action'])) {
    $full_file_name = isset($_GET['file']) ? $cur_dir . '/' . urldecode($_GET['file']) : '';
    switch ($_GET['action']) {
            //отмена редактирования файла
        case 'cancel-edit':
            unset($_SESSION['edit_file']);
            break;

            //новая директория
        case 'newfolder':
            new_folder($cur_dir);
            break;

            //обработка удаления
        case 'delete':
            is_dir($full_file_name) ? del_tree($full_file_name) : unlink($full_file_name);
            break;

            //обработка перехода по директориям
        case 'goto':
            $_SESSION['cur_dir'] = realpath($full_file_name);
            break;

            //обработка скачивания файла
        case 'download':
            file_force_download($full_file_name);
            break;

            //обработка переименования
        case 'rename':
            $new_file_name = isset($_GET['newname']) ? urldecode($_GET['newname']) : '';
            $rename_result = safety_rename($full_file_name, $cur_dir, $new_file_name);
            if ($rename_result !== true) {
                $_SESSION['fm_errors'][] = [$rename_result, $new_file_name];
            } else {
                //ничего не делаем
            }
            break;

            //обработка редактирования файла
        case 'edit':
            $_SESSION['edit_file'] = $full_file_name;
            break;
    }
    header('Location: ' . $base_uri);
}

//Вид страницы:
// - 1 - менеджер файлов
// - 2 - редактор
// - NN - иные варианты
//если указан и существует файл редактирования, то редактор
if (isset($_SESSION['edit_file']) && (file_exists($_SESSION['edit_file']))) {
    $edit_file = $_SESSION['edit_file'];
    $page_type = 2;
} else {
    $page_type = 1;
}

//функция вывода ошибок
function print_errors(&$error_codes, &$errors_array)
{
    $error_block = '<div class="err">';
    foreach ($errors_array as $error) {
        if (array_key_exists($error[0], $error_codes)) {
            $error_caption = $error_codes[$error[0]];
        } else {
            $error_caption = $error_codes['9999'] . $error[0];
        }
        $file_name = $error[1];
        $error_block .= "<div><span class=\"error-side\">$error_caption:</span>" .
            "<span class=\"file-side\">$file_name</span></div>";
    }
    $error_block .= '<a class="close-err" href="#">Скрыть блок ошибок</a></div>';
    return $error_block;
}

//функция выводит список директорий и файлов
function print_dir($dir)
{
    global $base_uri;
    $file_list = array_diff(scandir($dir), ['.']);
    $dir_side = '';
    $file_side = '';
    foreach ($file_list as $key => $item) {
        $f_full_name = $dir . '/' . $item;
        if ($isdir = is_dir($f_full_name)) {
            $f_name = "<a href=\"$base_uri?action=goto&file=" . urlencode($item) . "\">$item</a>";
            $f_size = '---';
        } else {
            // ! filesize() corrupt for > 2Gb and generates errors
            // ! realFileSize() doesn't work for unreadable (system, etc.) files, and returns FALSE
            $f_name = $item;
            $f_size = human_filesize(real_filesize($f_full_name));
        }
        $f_actions = implode(' ', get_f_actions($item, $isdir));
        $tr = "<tr><td>$f_name</td><td>$f_size</td><td>$f_actions</td></tr>";
        $isdir ? $dir_side .= $tr : $file_side .= $tr;
    }
    $table_body = '<tbody>' . $dir_side . $file_side . '</tbody>';
    return $table_body;
}

//Функция возвращает перечень возможных действий с файлом/директорией
//в виде массива ссылок
//(Файл, ID(key), DeleteModul, ДоступныеДляРедактированияТипыФайлов)
function get_f_actions($f_name, $isdir, $types = ['TXT', 'PHP', 'PL', 'HTM', 'HTML'])
{
    global $base_uri;
    $actions = array();
    if ($f_name == '..') {
        $actions[] = '<a href="' . $base_uri . '?action=newfolder&file=.">Создать папку</a>';
    } else {
        $actions[] = '<a class="rename" title="' . $f_name . '" ' .
            'href="' . $base_uri . '?action=rename&file=' . urlencode($f_name) . '">Переименовать</a>';
        $actions[] = '<a class="r-u-sure" href="' . $base_uri . '?action=delete&file=' . urlencode($f_name) . '">Удалить</a>';
        if (!$isdir) $actions[] = '<a href="' . $base_uri . '?action=download&file=' . urlencode($f_name) . '">Скачать</a>';
        if (
            isset(pathinfo($f_name)['extension']) &&
            in_array(strtoupper(pathinfo($f_name)['extension']), $types)
        ) {
            $actions[] = '<a href="' . $base_uri . '?action=edit&file=' . urlencode($f_name) . '">Редактировать</a>';
        }
    }
    return $actions;
}

//функция создания директории NewFolder_<NN>
function new_folder($path)
{
    $tmp_name = 'NewFolder_';
    $n = 1;
    while (file_exists($path . '/' . $tmp_name . $n)) $n++;
    return mkdir($path . '/' . $tmp_name . $n);
}

//функция переименования безопасного файла/директории (старое_имя_файла, новое_имя_файла)
//возвращает true в случае успеха, либо код ошибки в случае неудачи
function safety_rename($old_full_name, $path, $new_file_name)
{
    //Удаляем из имени файла запрещенные или частично(в зависимости от ОС) запрещенные символы
    $forbidden = ['/', '\\', '*', ':', '?', '|', '"', '<', '>', '+', '%', '!', '@'];
    $new_file_name = str_replace($forbidden, '', trim($new_file_name));

    $new_full_name = $path . '/' . $new_file_name;
    if (!file_exists($new_full_name)) {
        try {
            $result = rename($old_full_name, $new_full_name);
        } catch (Exception $error) {
            $result = '1003';
        }
    } else {
        $result = '2001';
    }
    return $result;
}

//доработанная функция рекурсивного удаления файлов/дириктории
//из php.net
function del_tree($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? del_tree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
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

function real_filesize($path)
{
    //if (!file_exists($path))
    //  return false;

    $size = @filesize($path);

    if (!($file = @fopen($path, 'rb')))
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

//функция скачивания файла
//источник php.net
// доработка от https://habr.com/ru/post/151795/
// TODO в статье и комментах рассматриваются более применительные варианты
function file_force_download($file)
{
    if (file_exists($file)) {
        // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
        // если этого не сделать файл будет читаться в память полностью!
        if (ob_get_level()) {
            ob_end_clean();
        }
        // заставляем браузер показать окно сохранения файла
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        // читаем файл и отправляем его пользователю
        readfile($file);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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

        div.err {
            margin: 2em 0;
        }

        div.err .error-side {
            color: red;
            margin-right: 1em;
        }

        #editor-form {
            text-align: right;
        }

        #file-contents {
            min-width: 100%;
            max-width: 100%;
        }
    </style>
</head>

<body>

    <?php
    if ($page_type == 1) {
        //файловый менеджер
    ?>
        <main>
            <div class="main">
                <h1>Файловый менеджер</h1>

                <?= ($fm_errors !== false) ? print_errors($error_codes, $fm_errors) : '' ?>
                <form name="upload_form" id="upload-form" action="" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                    <input id="fln" type="file" name="files[]" class="input" multiple title="Выберите файлы для загрузки" required value="Обзор...">
                    <input type="submit" name="submit" class="submit" value="Загрузить в текущую папку">
                </form>
                <div class="current-path"> <?= $cur_dir ?> </div>
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
        <script>
            $('a.rename').click(function() {
                let url = $(this).attr('href');
                let fileName = prompt('Введите имя файла', $(this).attr('title'));
                if (Boolean(fileName) != false) {
                    url += '&newname=' + encodeURI(fileName);
                    window.location.replace(url);
                }
                return false;
            });

            $('a.r-u-sure').click(function() {
                return confirm('Вы уверены?');
            });

            $('a.close-err').click(function() {
                $('div.err').remove();
                return false;
            });
        </script>
    <?php
    } elseif ($page_type == 2) {
    ?>
        <main>
            <div class="editor">
                <h1>Редактор файлов</h1>
                <div class="current-file"> <?= $edit_file ?> </div>
                <form name="editor_form" id="editor-form" method="post">
                    <textarea name="file_contents" id="file-contents" rows="30"><?= file_get_contents($edit_file) ?></textarea>
                    <div>
                        <input type="submit" name="save_file" id="save-file" class="save_file" value="Сохранить изменения">
                        <input type="button" id="cancel-edit" value="Отменить">
                    </div>
                </form>
            </div>
        </main>
        <script>
            $('input#cancel-edit').click(function() {
                let url = window.location.href + '?action=cancel-edit';
                window.location.replace(url);
                return false;
            });
        </script>
    <?php
    }
    ?>
</body>

</html>