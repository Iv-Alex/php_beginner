<?php
# http://joxi.ru/vAWQBVqhqzogBm
# http://joxi.ru/4AkNWZDTXL7lJ2
# http://joxi.ru/KAx61gDT10EYym


error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');
# это не работает для удаления заголовка:
# echo ini_set ( 'mail.add_x_header', '0' ) . '<br>';

$headers = 'From: temp@logycon.ru'."\r\n"
.'Content-Type: text/plain; charset=utf-8'."\r\n";

# работает добавление в директорию скрипта .htacess и утановки
# php_flag mail.add_x_header Off
echo ini_get ( 'mail.add_x_header' ) . '<br>';

# не смог победить текстовку письма
echo mail('test-ytfw468k8@srv1.mail-tester.com', 
    '=?utf-8?B?'.base64_encode('Wiki online infomation here').'?=',
    '=?utf-8?B?'.base64_encode('The newsletter is the most common form of serial
    publication.[1] About two thirds of newsletters are publications, aimed towards 
    employees and volunteers, while about one third are publications, aimed towards 
    advocacy or special interest groups. newsletter is a printed or electronic report 
    containing news concerning of the activities of a business or an organization 
    that is sent to its members, customers, employees or other subscribers.
     Newsletters generally contain one main topic of interest to its recipients. 
     A newsletter may be considered grey literature. E-newsletters are delivered 
     electronically via e-mail and can be viewed as spamming if e-mail marketing 
     is sent unsolicited.[2][3][4][5]
    The newsletter is the most common form of serial publication.[6] 
    About two thirds of newsletters are publications, aimed towards employees and 
    volunteers, while about one third are publications, aimed towards advocacy or 
    special interest groups.[6]').'?=',
    $headers) . '<br>';

?>