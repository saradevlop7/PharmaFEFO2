<?php


$uri = $_SERVER['REQUEST_URI'];


$file = __DIR__ . '/public' . parse_url($uri, PHP_URL_PATH);
if (is_file($file)) {
    return false;
}

// Tout le reste → index.php
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';
require __DIR__ . '/public/index.php';
