<?php
require_once dirname(__DIR__, 3) . '/src/Service/AuthService.php';

class ApiAuthController {
    public function __construct() {
        header('Content-Type: application/json');
    }

    public function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $ok   = AuthService::login($data['username'] ?? '', $data['password'] ?? '');
        if ($ok) {
            echo json_encode(['success' => true, 'role' => AuthService::getRole()]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Identifiants incorrects.']);
        }
    }

    public function logout(): void {
        AuthService::logout();
        echo json_encode(['success' => true]);
    }
}
