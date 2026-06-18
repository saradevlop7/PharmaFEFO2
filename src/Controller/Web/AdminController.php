<?php
require_once dirname(__DIR__, 3) . '/src/Service/AuthService.php';
require_once dirname(__DIR__, 3) . '/src/Service/StockService.php';

class AdminController {
    public function reports(): void {
        AuthService::requireRole('ADMIN'); // 403 si pas ADMIN
        $service  = new StockService();
        $movements = $service->getAllMovements();
        require dirname(__DIR__, 3) . '/templates/admin/reports.php';
    }
}
