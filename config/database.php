<?php
require_once dirname(__DIR__) . '/config/environment.php';

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8mb4';
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                if (APP_ENV === 'dev') {
                    die('DB Error: ' . $e->getMessage());
                } else {
                    http_response_code(500);
                    header('Content-Type: application/json');
                    die(json_encode(['error' => 'Erreur 500 — Contactez l\'administrateur.']));
                }
            }
        }
        return self::$instance;
        
    }
}
