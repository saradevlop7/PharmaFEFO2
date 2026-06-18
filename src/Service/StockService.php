<?php
require_once dirname(__DIR__, 2) . '/database/database.php';

class StockService {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

  
    public function addBatch(array $data): array {
      
        $stmt = $this->db->prepare('SELECT id FROM medications WHERE name = ?');
        $stmt->execute([$data['name']]);
        $med = $stmt->fetch();
        if (!$med) {
            $ins = $this->db->prepare('INSERT INTO medications (name, category) VALUES (?, ?)');
            $ins->execute([$data['name'], $data['category'] ?? null]);
            $medId = $this->db->lastInsertId();
        } else {
            $medId = $med['id'];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO batches (medication_id, lot_number, quantity, expiry_date, supplier)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$medId, $data['lot_number'], $data['quantity'], $data['expiry_date'], $data['supplier'] ?? null]);
        $batchId = $this->db->lastInsertId();

        $this->logMovement($batchId, $_SESSION['user_id'], 'ADD', $data['quantity']);
        return ['id' => $batchId];
    }

   
    public function getAllBatches(?string $criteria = null): array {
        $where = 'b.status = "active"';
        if ($criteria === 'critical') {
            $where .= ' AND b.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)';
        } elseif ($criteria === 'expiring') {
            $where .= ' AND b.expiry_date BETWEEN DATE_ADD(CURDATE(), INTERVAL 31 DAY) AND DATE_ADD(CURDATE(), INTERVAL 90 DAY)';
        }
        $sql = "SELECT b.*, m.name, m.category
                FROM batches b
                JOIN medications m ON m.id = b.medication_id
                WHERE $where
                ORDER BY b.expiry_date ASC";
        return $this->db->query($sql)->fetchAll();
    }

    
    public function countExpiringThisMonth(): int {
        $stmt = $this->db->query(
            'SELECT COUNT(*) FROM batches
             WHERE status = "active"
               AND expiry_date BETWEEN CURDATE() AND LAST_DAY(CURDATE())'
        );
        return (int) $stmt->fetchColumn();
    }

    
    public function deliverOne(string $medicationName): array {
        $stmt = $this->db->prepare(
            'SELECT b.* FROM batches b
             JOIN medications m ON m.id = b.medication_id
             WHERE m.name = ? AND b.status = "active" AND b.quantity > 0
             ORDER BY b.expiry_date ASC LIMIT 1'
        );
        $stmt->execute([$medicationName]);
        $batch = $stmt->fetch();
        if (!$batch) throw new RuntimeException('Aucun stock disponible pour ce médicament.');

        $newQty = $batch['quantity'] - 1;
        $status = $newQty === 0 ? 'expired' : 'active';
        $this->db->prepare('UPDATE batches SET quantity = ?, status = ? WHERE id = ?')
                 ->execute([$newQty, $status, $batch['id']]);

        $this->logMovement($batch['id'], $_SESSION['user_id'], 'DELIVER', 1);
        return ['batch_id' => $batch['id'], 'lot_number' => $batch['lot_number'], 'new_qty' => $newQty];
    }

    /** Détruire un lot (US 4.1) */
    public function destroyBatch(int $batchId): void {
        $this->db->prepare('UPDATE batches SET status = "destroyed", quantity = 0 WHERE id = ?')
                 ->execute([$batchId]);
        $this->logMovement($batchId, $_SESSION['user_id'], 'DESTROY', 0);
    }

    private function logMovement(int $batchId, int $userId, string $action, int $qty): void {
        $this->db->prepare(
            'INSERT INTO stock_movements (batch_id, user_id, action, quantity) VALUES (?, ?, ?, ?)'
        )->execute([$batchId, $userId, $action, $qty]);
    }

    public function getAllMovements(): array {
        return $this->db->query(
            'SELECT sm.*, b.lot_number, m.name AS medication_name, u.username, u.role
             FROM stock_movements sm
             JOIN batches b ON b.id = sm.batch_id
             JOIN medications m ON m.id = b.medication_id
             JOIN users u ON u.id = sm.user_id
             ORDER BY sm.created_at DESC'
        )->fetchAll();
    }
}
