<?php
require_once dirname(__DIR__, 2) . '/database/database.php';

class AuthService {
    public static function login(string $username, string $password): bool {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            return true;
        }
        return false;
    }

    public static function logout(): void {
        session_start();
        session_destroy();
    }

    public static function isLoggedIn(): bool {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['user_id']);
    }

    public static function getRole(): ?string {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['role'] ?? null;
    }

    public static function requireRole(string ...$roles): void {
        if (!self::isLoggedIn()) {
            http_response_code(401);
            if (Router::isApiRoute($_SERVER['REQUEST_URI'])) {
                die(json_encode(['error' => '401 Non authentifié']));
            }
            header('Location: /login'); exit;
        }
        if (!in_array(self::getRole(), $roles)) {
            http_response_code(403);
            if (Router::isApiRoute($_SERVER['REQUEST_URI'])) {
                die(json_encode(['error' => '403 Accès refusé']));
            }
            require_once dirname(__DIR__, 2) . '/templates/errors/403.php'; exit;
        }
    }
}
