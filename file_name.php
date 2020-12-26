<?php
error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8')
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo 'Document' ?></title>
</head>

<body>

</body>

</html>

<?php
error_reporting(E_ALL);

show_file_name_info(parse_file_path('sdsdfgsdfgsdgsdfg\\f.sdfgsdfgsdgsdfg.g.'));
show_file_name_info(parse_file_path('sdsdfg/sdfgsdgsdfg\\f.sdfgsdfgsdgsdfg.g'));
show_file_name_info(parse_file_path('sdsdfgsdfgsdgsdfg\\f.sdfgsdfgs.dg.sdfg.g'));
show_file_name_info(parse_file_path('sdsdfgsdfgsdgsdfg\\f.sdfgsdfg<< sdgsdfgg'));
show_file_name_info(parse_file_path('sdsdfgsdfgsd/gsd/fgf.sd/fgsd fg s dgsdf.gg'));
show_file_name_info(parse_file_path('sdsdfgsdfgsd/gsd/fgf.sd/.fgsd fg s dgsdf.gg'));
show_file_name_info(parse_file_path('sdsdfgsdfgsd/gsd/fgf.sd/fgsd :fg s dgsdf.gg'));
show_file_name_info(parse_file_path('sdsdfgsdfgsd/gsd/fgf.sd/fgsd |fg s dgsdf.gg'));
show_file_name_info(parse_file_path('s'));
show_file_name_info(parse_file_path('/s'));
show_file_name_info(parse_file_path('.'));
show_file_name_info(parse_file_path('/..'));
show_file_name_info(parse_file_path('\\'));


function parse_file_path($file_path_name)
{
    // значения по умолчанию
    $full_file_name = ''; // имя файла с расширением
    $file_extention = ''; // расширение файла
    $file_name = ''; // имя файла
    $parent_folder_name = ''; // родительская папка

    // с чем будем работать
    echo '<br>' . htmlspecialchars($file_path_name) . '<br>';
    // проверяем слеши
    if (mb_strripos($file_path_name, '/') !== false) {
        $slash_symbol = '/';
    } else {
        $slash_symbol = '';
    }
    if (mb_strripos($file_path_name, '\\') !== false) {
        $slash_symbol = $slash_symbol . '\\';
    }
    if (strlen($slash_symbol) > 1) {
        echo 'Некорректный путь: найдены оба вида слешей <br>';
    } else {
        // если указано имя файла без пути
        if ($slash_symbol == '') {
            $full_file_name = $file_path_name; // полное имя файла
        } else {
            $file_path_parts = explode($slash_symbol, $file_path_name);
            $full_file_name = end($file_path_parts); // полное имя файла
            $parent_folder_name = $file_path_parts[count($file_path_parts)-2]; //родительская папка
            // если путь начинается со слеша
            if ($parent_folder_name == '') {
                $parent_folder_name = 'Служебное: Корень';
            }
        }    
        // проверяем на "." и ".."
        if ($full_file_name == '..' or $full_file_name == '.') {
            $parent_folder_name = $full_file_name; //родительская папка
            $full_file_name = '';
        }
        if ($full_file_name != '') {
            // имя файла не должно начинаться с точки и не может содержать недопустимых символов
            if ((strpbrk($full_file_name, "<>:|") === false) and ($full_file_name{0} != '.')) {
                // если полное имя файла заканчивается на ".", то расширения нет, обрезаем точку        
                $full_file_name_len = mb_strlen($full_file_name);
                if ($full_file_name{$full_file_name_len - 1} == '.') {
                    $last_point_pos = false;
                    $full_file_name = mb_substr($full_file_name, 0, $full_file_name_len - 1);
                } else $last_point_pos = mb_strrpos($full_file_name, '.');
                // если полное имя файла не содержит ".", то расширения нет
                if ($last_point_pos === false) {
                    $file_name = $full_file_name;
                } else {
                    // получение имени файла и расширения в случае наличия обоих
                    $file_name = mb_substr($full_file_name, 0, $last_point_pos);
                    $file_extention = mb_substr($full_file_name, $last_point_pos + 1);
                }
            } else {
                $file_name = ':'; // оставляем недопустимый символ, как индикатор ошибки
            }
        }
    }
    // длина имени файла
    if ($file_name == ':') {
        $file_name_len = -1;
    } else {
        $file_name_len = mb_strlen($file_name);
    }
    return array(
        'full_file_name' => $full_file_name, // имя файла с расширением
        'file_extention' => $file_extention, // расширение файла
        'file_name' => $file_name, // имя файла
        'file_name_len' => $file_name_len, // длина имени файла
        'parent_folder_name' => $parent_folder_name, // родительская папка
    );
}

function show_file_name_info($file_name_info) {
    if ($file_name_info['full_file_name'] != '') {
        echo 'Имя файла с расширением: ' . htmlspecialchars($file_name_info['full_file_name']) . '<br>';
        if ($file_name_info['file_extention'] != '') {
            echo 'Расширение файла: ' . $file_name_info['file_extention'] . '<br>';
        } else {
            echo 'Служебное: файл не имеет расширения<br>';
        }
        if ($file_name_info['file_name'] == ':') {
            echo 'Некорректное имя файла: найдены недопустимые символы из набора "<>:|" или имя файла начинается с точки<br>';
        } else {
            echo 'Имя файла: ' . $file_name_info['file_name'] . '<br>';
            echo 'Длина имени файла: ' . $file_name_info['file_name_len'] . ' знаков<br>'; // можно добавить обработку окончания
        }
    } else {
        echo 'Служебное: имя файла отсутствует<br>';
    }
    if ($file_name_info['parent_folder_name'] != '') {
        echo 'Родительская папка: ' . $file_name_info['parent_folder_name'] . '<br>';
    } else {
        echo 'Служебное: родительская папка не указана<br>';
    }
}

?>