<?php
require_once dirname(__DIR__, 3) . '/src/Core/Router.php';
require_once dirname(__DIR__, 3) . '/src/Service/AuthService.php';
require_once dirname(__DIR__, 3) . '/src/Service/StockService.php';

class ApiStockController {
    private StockService $service;

    public function __construct() {
        header('Content-Type: application/json');
        $this->service = new StockService();
    }

    /** POST /api/v1/stock/add  (US 1.1) */
    public function add(): void {
        AuthService::requireRole('PREPARATEUR', 'ADMIN');
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name']) || empty($data['lot_number']) || empty($data['expiry_date']) || empty($data['quantity'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
            return;
        }
        $result = $this->service->addBatch($data);
        echo json_encode(['success' => true, 'message' => 'Lot ajouté avec succès.', 'data' => $result]);
    }

    /** GET /api/v1/batches  (US 2.1) */
    public function batches(): void {
        AuthService::requireRole('PHARMACIEN', 'ADMIN');
        $criteria = $_GET['criteria'] ?? null;
        $batches  = $this->service->getAllBatches($criteria);
        echo json_encode(['success' => true, 'data' => $batches]);
    }

    /** GET /api/v1/stock/expiring-count  (US 2.2) */
    public function expiringCount(): void {
        AuthService::requireRole('PHARMACIEN', 'ADMIN');
        $count = $this->service->countExpiringThisMonth();
        echo json_encode(['success' => true, 'count' => $count]);
    }

    /** POST /api/v1/stock/deliver  (US 3.1) */
    public function deliver(): void {
        AuthService::requireRole('PHARMACIEN', 'ADMIN');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['medication_name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Nom du médicament requis.']);
            return;
        }
        $result = $this->service->deliverOne($data['medication_name']);
        echo json_encode(['success' => true, 'message' => '1 boîte délivrée (FEFO).', 'data' => $result]);
    }

    /** PATCH /api/v1/stock/destroy/{id}  (US 4.1) */
    public function destroy(string $id): void {
        AuthService::requireRole('PHARMACIEN', 'ADMIN');
        $this->service->destroyBatch((int)$id);
        echo json_encode(['success' => true, 'message' => 'Lot marqué comme détruit.']);
    }
}
