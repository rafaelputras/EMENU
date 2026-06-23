<?php
class Table extends Database {
    // Cek apakah nomor meja valid dan ambil data mejanya
    public function getTableByNumber($tableNumber) {
        $stmt = $this->conn->prepare("SELECT * FROM tables WHERE table_number = :table_number LIMIT 1");
        $stmt->bindParam(':table_number', $tableNumber);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update status meja (misal: dari 'empty' jadi 'occupied')
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE tables SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function getAllTables() {
        $stmt = $this->conn->prepare("SELECT * FROM tables");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}