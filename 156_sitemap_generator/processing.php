<?php
$output = 'Здравствуйте, пользователь! ';
if ($_SERVER['REMOTE_ADDR']) {
  $output .= 'Ваш IP адрес: '. $_SERVER['REMOTE_ADDR'];
}
else {
 $output .= 'Ваш IP адрес неизвестен.';
}
echo $output;