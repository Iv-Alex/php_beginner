<?php
/*
Дан длинный текст, в нём встречаются слова длиннее 6 символов! Если слово длиннее 6 символов, 
то необходимо: оставить первые 5 символов и добавить звёздочку. Остальные символы вырезаются.
Например дано: "я купила терминатора вчера". Результат: "я купила терми* вчера"
P.S. В видео даны подсказки, но цифры там возможно неверные.
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

$text = 'Дан длинный текст, в нём встречаются слова длиннее 6 символов! Если слово длиннее 6 символов, то необходимо: оставить первые 5 символов и добавить звёздочку. Остальные символы вырезаются.
Например дано: "я купила терминатора вчера". Результат: "я купила терми* вчера"
P.S. В видео даны подсказки, но цифры там возможно неверные.';

$words = explode(' ', $text);

foreach($words as $word) {
    if (($word_len = mb_strlen($word)) > 6) {
        $word_to_write = mb_substr($word, 0, 5) . '*';
    } else {
        $word_to_write = $word;
    }
    echo $word_to_write . ' ';
}

?>