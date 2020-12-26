<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$path = 'C:\Users\Iv\Documents\GoogleDisk\domains/test1';
echo mb_substr($path, mb_strrpos($path, '/'));
?>