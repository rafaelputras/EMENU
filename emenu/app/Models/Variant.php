<?php
class Variant extends Database {

    // ==========================================
    // MODUL GRUP VARIAN UTAMA
    // ==========================================
    
    public function getAllGroups() {
        $stmt = $this->conn->prepare("SELECT * FROM variant_groups");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // FUNGSI BARU: Mengambil 1 grup varian berdasarkan ID
    public function getGroupById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM variant_groups WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addGroup($name, $type) {
        $stmt = $this->conn->prepare("INSERT INTO variant_groups (name, type) VALUES (:name, :type)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type', $type);
        return $stmt->execute();
    }

    // FUNGSI BARU: Update Grup Varian
    public function updateGroup($id, $name, $type) {
        $stmt = $this->conn->prepare("UPDATE variant_groups SET name = :name, type = :type WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }


    // ==========================================
    // MODUL OPSI VARIAN
    // ==========================================

    // DIPERBAIKI: Mengambil opsi dan diurutkan berdasarkan kolom sort_order
    public function getOptionsByGroup($group_id) {
        $stmt = $this->conn->prepare("SELECT * FROM variant_options WHERE group_id = :group_id ORDER BY sort_order ASC, id ASC");
        $stmt->bindParam(':group_id', $group_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // DIPERBAIKI: Menambah opsi sekaligus menaruhnya di urutan paling bawah
    public function addOption($group_id, $name, $extra_price) {
        // Cari nilai sort_order paling besar di grup ini
        $stmtMax = $this->conn->prepare("SELECT MAX(sort_order) as max_sort FROM variant_options WHERE group_id = :group_id");
        $stmtMax->bindParam(':group_id', $group_id);
        $stmtMax->execute();
        $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
        $next_sort = ($row['max_sort'] !== null) ? $row['max_sort'] + 1 : 0;

        // Insert dengan sort_order baru
        $stmt = $this->conn->prepare("INSERT INTO variant_options (group_id, name, extra_price, sort_order) VALUES (:group_id, :name, :extra_price, :sort_order)");
        $stmt->bindParam(':group_id', $group_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':extra_price', $extra_price);
        $stmt->bindParam(':sort_order', $next_sort);
        return $stmt->execute();
    }

    // FUNGSI BARU: Update Opsi Varian
    public function updateOption($id, $name, $extra_price) {
        $stmt = $this->conn->prepare("UPDATE variant_options SET name = :name, extra_price = :extra_price WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':extra_price', $extra_price);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // FUNGSI BARU: Hapus Opsi Varian
    public function deleteOption($id) {
        $stmt = $this->conn->prepare("DELETE FROM variant_options WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // FUNGSI BARU: Logika Tukar Posisi (Swap Urutan Ke Atas / Ke Bawah)
    public function moveOptionOrder($id, $direction, $group_id) {
        // 1. Ambil data opsi yang mau dipindah
        $stmtCurrent = $this->conn->prepare("SELECT id, sort_order FROM variant_options WHERE id = :id");
        $stmtCurrent->bindParam(':id', $id);
        $stmtCurrent->execute();
        $current = $stmtCurrent->fetch(PDO::FETCH_ASSOC);

        if (!$current) return false;
        $current_sort = $current['sort_order'];

        // 2. Cari target opsi yang mau ditukar posisinya
        if ($direction === 'up') {
            // Cari opsi dengan urutan yang TEPAT DI ATASNYA (sort_order lebih kecil)
            $sqlTarget = "SELECT id, sort_order FROM variant_options WHERE group_id = :group_id AND sort_order < :current_sort ORDER BY sort_order DESC LIMIT 1";
        } else {
            // Cari opsi dengan urutan yang TEPAT DI BAWAHNYA (sort_order lebih besar)
            $sqlTarget = "SELECT id, sort_order FROM variant_options WHERE group_id = :group_id AND sort_order > :current_sort ORDER BY sort_order ASC LIMIT 1";
        }

        $stmtTarget = $this->conn->prepare($sqlTarget);
        $stmtTarget->bindParam(':group_id', $group_id);
        $stmtTarget->bindParam(':current_sort', $current_sort);
        $stmtTarget->execute();
        $target = $stmtTarget->fetch(PDO::FETCH_ASSOC);

        // 3. Jika target ketemu, lakukan pertukaran (swap) nilai sort_order
        if ($target) {
            // Update baris pertama
            $stmtUpdate1 = $this->conn->prepare("UPDATE variant_options SET sort_order = :new_sort WHERE id = :id");
            $stmtUpdate1->execute([':new_sort' => $target['sort_order'], ':id' => $current['id']]);

            // Update baris kedua
            $stmtUpdate2 = $this->conn->prepare("UPDATE variant_options SET sort_order = :new_sort WHERE id = :id");
            $stmtUpdate2->execute([':new_sort' => $current_sort, ':id' => $target['id']]);
        }

        return true;
    }
    // Ambil semua daftar master varian untuk ditampilkan di form menu
    public function getAllVariants() {
        try {
            // Mengambil semua data dari tabel variant_groups
            $stmt = $this->conn->prepare("SELECT * FROM variant_groups ORDER BY name ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Jika tabel belum ada atau salah nama, kembalikan array kosong agar tidak fatal error
            return [];
        }
    }
}