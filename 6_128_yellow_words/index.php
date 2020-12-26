<?php
/*
Есть длинный текст и есть форма поиска по этому тексту. При вводе слова в форму поиска необходимо 
найти все упоминания этого слова в тексте и выделить (подсветить желтым фоном). В случае, если 
указываются 2 слова, то каждое должно искаться индивидуально, если словосочетание 
указывается в кавычках, то ищется как единое словосочетание.
Если задача решена, то можно (по желанию) усложнить:
помимо грубого (точного) поиска так же должно находить слова с разными окончаниями 
(с учетом словоформ): пиво, пива, пивом...
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

// текст
$text = 'Есть длинный текст и есть форма поиска по этому тексту. При вводе слова в форму поиска необходимо 
найти все упоминания этого слова в тексте и выделить (подсветить желтым фоном). В случае, если 
указываются 2 слова, то каждое должно искаться индивидуально, если словосочетание 
указывается в кавычках, то ищется как единое словосочетание.
Если задача решена, то можно (по желанию) усложнить:
помимо грубого (точного) поиска так же должно находить слова с разными окончаниями 
(с учетом словоформ): пиво, пива, пивом...';
// слова для поиска (через пробел)

function highlight($needle, $haystack)
{
    $ind = mb_stripos($haystack, $needle);
    $len = mb_strlen($needle);
    if ($ind !== false) {
        return mb_substr($haystack, 0, $ind) . '<span style="background: yellow;">' . mb_substr($haystack, $ind, $len) . '</span>' .
            highlight($needle, mb_substr($haystack, $ind + $len));
    } else return $haystack;
}

if (isset($_GET['words'])) {
    foreach (explode(' ', $_GET['words']) as $value) {
        $text = highlight($value, $text);
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yellow words</title>
    <style>
        .container {
            width: 80%;
            margin: 1em auto;
            outline: 1px solid blue;
            padding: 2em;
        }

        #search-form {
            padding: 15px;
            background: silver;
        }

        #search-text {
            width: 30em;
        }
    </style>
</head>

<body>
    <div class="container">
        <form id="search-form" action="./">
            <label>
                Введите через пробел слова или символы для подсветки<br>
                <input id="search-text" name="words" type="text" placeholder="Например: слов ше">
            </label>
            <input type="submit" value="Подсветить">
        </form>
        <?= $text ?>
    </div>
</body>

</html>