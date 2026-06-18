<?php
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $val] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($val);
        putenv(trim($key) . '=' . trim($val));
    }

}

define('APP_ENV',    $_ENV['APP_ENV']    ?? getenv('APP_ENV')    ?? 'prod');
define('DB_HOST',    $_ENV['DB_HOST']    ?? getenv('DB_HOST')    ?? 'localhost');
define('DB_PORT',    $_ENV['DB_PORT']    ?? getenv('DB_PORT')    ?? '3306');
define('DB_NAME',    $_ENV['DB_NAME']    ?? getenv('DB_NAME')    ?? 'pharmafefo');
define('DB_USER',    $_ENV['DB_USER']    ?? getenv('DB_USER')    ?? 'root');
define('DB_PASS',    $_ENV['DB_PASS']    ?? getenv('DB_PASS')    ?? '');
define('APP_SECRET', $_ENV['APP_SECRET'] ?? getenv('APP_SECRET') ?? 'secret');

// Mode miroir Jour 5
if (APP_ENV === 'dev') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}
