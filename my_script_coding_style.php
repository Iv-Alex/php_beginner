<?php
$a = 5;
$best_name = 'user';
$c = $a + 3;
echo $best_name . $c;
if ($a) {
	$message = 'Письмо было успешно отправлено.';
} else {
	$message = 'Письмо не удалось отправить. Ошибка #3';
}
?>
<html>
<?= $message; ?>
</html>