<?php
class Category extends Database {
    
    // Ambil semua kategori
    public function getAllCategories() {
        $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah kategori baru
    public function addCategory($name) {
        $stmt = $this->conn->prepare("INSERT INTO categories (name, is_active) VALUES (:name, 1)");
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    // Ubah status (Sembunyikan/Tampilkan)
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE categories SET is_active = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function insertCategory($name) {
    // Default is_active diisi 1 (aktif) saat pertama kali dibuat
    $stmt = $this->conn->prepare("INSERT INTO categories (name, is_active) VALUES (?, 1)");
    return $stmt->execute([$name]);
}
// Mengeksekusi perubahan nama kategori ke Database
    public function updateCategory($id, $name) {
        // Siapkan query UPDATE
        $stmt = $this->conn->prepare("UPDATE categories SET name = :name WHERE id = :id");
        
        // Hubungkan data dari controller ke query
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id);
        
        // Eksekusi query
        return $stmt->execute();
    }

// Fungsi untuk menghapus kategori dari Database
    public function deleteCategory($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Jika gagal karena Foreign Key Constraint (Kategori masih dipakai di tabel Menus)
            return false; 
        }
    }
}