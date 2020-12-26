<?php
error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

echo 'Случайный timestamp: ' . ($new_date = rand(0, strtotime("1980-01-01"))) . '<br>';
echo 'Полученная дата: ' . date('Y-m-d H:i:s', $new_date) . '<br>';
echo 'Смещение: ' . date('H:i:s', 0) . '<br>';

?>