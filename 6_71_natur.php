<?php
/*
Необходимо вычислить: 1^1 + 2^2 + .. + n^n=?.
где n - случайное число от 3 до 12.
Ниже показан пример вывода на экран, в случае если n=3.
n=3;
1^1 + 2^2 + 3^3=1+4+27=32.
пришлите решение задания на phpfiddle
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numeric</title>
</head>
<body>
    <h3>Значение n (3..12): <?=($n = rand(3, 12));?></h3>
    <?php
    for ($z = 1, $fp = '1<sup>1</sup>', $sp = '1', $i = 2; $i <= $n; $i++) {
        $fp .= (' + ' . $i . '<sup>' . $i . '</sup>');
        $t = pow($i, $i);
        $sp .= ('+' . $t);
        $z += $t;
    }
    ?>
    <div>n=<?=$n;?>;</div>
    <div><?=$fp;?>=<?=$sp;?>=<?=$z;?>.</div>
</body>
</html>
