<?php

if (php_sapi_name() !== 'cli' or !defined('STDIN')) {
    exit();    
}

$files = glob('cache/*.{bmp,gif,jpg,png}', GLOB_BRACE);
if ($files) {
    $c = count($files);
    array_map('unlink', $files);
    echo "$c file(s) deleted.";
}