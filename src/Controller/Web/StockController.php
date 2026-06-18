<?php
require_once dirname(__DIR__, 3) . '/src/Service/AuthService.php';

class StockController {
    public function addPage(): void {
        AuthService::requireRole('PREPARATEUR', 'ADMIN');
        require dirname(__DIR__, 3) . '/templates/stock/add.php';
    }
}
