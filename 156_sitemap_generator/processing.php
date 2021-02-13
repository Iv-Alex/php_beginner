<?php
if (isset($_GET['session_id'])) {
    $source = $_GET['session_id'];
    $output = file_exists($source) ? file_get_contents($source) : 'END';
} else {
    $output = 'Crash';
}
echo $output;
