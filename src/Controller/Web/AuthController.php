<?php
require_once dirname(__DIR__, 3) . '/src/Service/AuthService.php';

class AuthController {
    public function loginPage(): void {
        if (AuthService::isLoggedIn()) { header('Location: /dashboard'); exit; }
        require dirname(__DIR__, 3) . '/templates/auth/login.php';
    }

    public function logout(): void {
        AuthService::logout();
        header('Location: /login'); exit;
    }
}
