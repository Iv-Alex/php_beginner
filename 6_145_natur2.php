<?php
/*
Задача аналогична предыдущей, только нужно вывести в таком виде:
11=1
1^1 + 2^2=5
...
 1^1 + 2^2+ .. + n^n=?. где n - случайное число от 3 до 12
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numeric 2</title>
</head>
<body>
    <h3>Значение n (3..12): <?=($n = rand(3, 12));?></h3>
    <?php
    $items = array();
    for ($z = 0, $i = 1; $i <= $n; $i++) {
        $items[] = ($i . '<sup>' . $i . '</sup>');
        $z += pow($i, $i);
        echo '<div>' . implode('+', $items) . '=' . $z . '</div>';
    }
    ?>
</body>
</html>
