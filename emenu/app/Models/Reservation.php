<?php
class Reservation extends Database {
    public function createReservation($name, $phone, $table_id, $date) {
        $stmt = $this->conn->prepare("INSERT INTO reservations (customer_name, customer_phone, table_id, reservation_date) VALUES (:name, :phone, :table_id, :date)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':table_id', $table_id);
        $stmt->bindParam(':date', $date);
        return $stmt->execute();
    }
}