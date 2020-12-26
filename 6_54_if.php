<?php

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

echo 'Сдучайное число (0..9): ' . ($a = rand(0, 9)) . '<br>';
if ($a < 4) {
    $assessment = 'Плохо';
} elseif ($a > 7) {
    $assessment = 'Отлично';
} else {
    $assessment = 'Хорошо';
}
echo 'Оценка по числу: ' . $assessment . '<br>';

?>