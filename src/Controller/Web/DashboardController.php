<?php
require_once dirname(__DIR__, 3) . '/src/Service/AuthService.php';

class DashboardController {
    public function index(): void {
        AuthService::requireRole('PHARMACIEN', 'ADMIN');
        $role = AuthService::getRole();
        require dirname(__DIR__, 3) . '/templates/dashboard/index.php';
    }
}
