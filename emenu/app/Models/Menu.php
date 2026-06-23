<?php
class Menu extends Database {
    
    // 1. Ambil semua menu untuk halaman Admin (Tabel Manajemen Menu)
    public function getAdminMenus() {
        try {
            // Gunakan LEFT JOIN agar nama kategori ikut terambil dari tabel categories
            $query = "SELECT menus.*, categories.name as category_name 
                      FROM menus 
                      LEFT JOIN categories ON menus.category_id = categories.id 
                      ORDER BY menus.id DESC";
                      
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Jika ada error pada query database, kembalikan array kosong agar web tidak crash
            return [];
        }
    }
    // Fungsi Khusus Halaman Pelanggan (Hanya ambil menu yang statusnya Tersedia / 1)

    // 2. Ambil semua menu yang aktif (Untuk halaman kasir/katalog)
    public function getAllMenus() {
        $stmt = $this->conn->prepare("SELECT * FROM menus WHERE is_available = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Ambil 1 menu spesifik berdasarkan ID (Untuk Form Edit)
    public function getMenuById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM menus WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Tambah menu baru
    // 4. Tambah menu baru
    public function addMenu($data) {
        $stmt = $this->conn->prepare("INSERT INTO menus (category_id, name, description, price, image, is_available, promo_price, promo_start, promo_end, promo_quota) VALUES (:category_id, :name, :description, :price, :image, 1, :promo_price, :promo_start, :promo_end, :promo_quota)");

        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':promo_price', $data['promo_price']);
        $stmt->bindParam(':promo_start', $data['promo_start']);
        $stmt->bindParam(':promo_end', $data['promo_end']);
        $stmt->bindParam(':promo_quota', $data['promo_quota']);
        
        $stmt->execute();
        
        // KEMBALIKAN ID MENU YANG BARU DIBUAT
        return $this->conn->lastInsertId();
    }

    // 6. Menyimpan relasi varian ke menu
    public function addMenuVariants($menu_id, $variant_group_ids) {
        // Hapus relasi lama dulu (berguna untuk fungsi update)
        $stmtDelete = $this->conn->prepare("DELETE FROM menu_variant_links WHERE menu_id = :menu_id");
        $stmtDelete->bindParam(':menu_id', $menu_id);
        $stmtDelete->execute();

        // Masukkan relasi baru
        $stmt = $this->conn->prepare("INSERT INTO menu_variant_links (menu_id, variant_group_id) VALUES (:menu_id, :group_id)");
        foreach ($variant_group_ids as $group_id) {
            $stmt->bindParam(':menu_id', $menu_id);
            $stmt->bindParam(':group_id', $group_id);
            $stmt->execute();
        }
    }

    // Fungsi Khusus Halaman Pelanggan
    // Fungsi Khusus Halaman Pelanggan
    public function getActiveMenus() {
        try {
            $query = "SELECT menus.*, categories.name as category_name 
                      FROM menus 
                      LEFT JOIN categories ON menus.category_id = categories.id 
                      WHERE menus.is_available = 1 
                      ORDER BY menus.id DESC";
                      
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // AMBIL DATA VARIAN BESERTA GRUP DAN ATURANNYA
            foreach ($menus as &$menu) {
                // 1. Ambil Grup Varian apa saja yang terhubung ke menu ini
                $stmtGroup = $this->conn->prepare("
                    SELECT vg.id, vg.name, vg.type 
                    FROM variant_groups vg
                    JOIN menu_variant_links mvl ON vg.id = mvl.variant_group_id
                    WHERE mvl.menu_id = :menu_id
                ");
                $stmtGroup->execute([':menu_id' => $menu['id']]);
                $groups = $stmtGroup->fetchAll(PDO::FETCH_ASSOC);

                $groupedVariants = [];
                // 2. Untuk setiap grup, ambil opsi/pilihan di dalamnya
                foreach ($groups as $grp) {
                    $stmtOpt = $this->conn->prepare("SELECT id, name, extra_price as price FROM variant_options WHERE group_id = :group_id");
                    $stmtOpt->execute([':group_id' => $grp['id']]);
                    $options = $stmtOpt->fetchAll(PDO::FETCH_ASSOC);

                    $groupedVariants[] = [
                        'group_id' => $grp['id'],
                        'group_name' => $grp['name'],
                        'type' => $grp['type'], // 'single' atau 'multiple'
                        'options' => $options
                    ];
                }
                // Simpan dalam bentuk struktur grup
                $menu['variants_grouped'] = $groupedVariants;
            }

            return $menus;
        } catch (Exception $e) {
            return [];
        }
    }

    // 5. Update data menu
    // Fungsi Update Menu
    public function updateMenu($data) {
        $stmt = $this->conn->prepare("
            UPDATE menus 
            SET category_id = :category_id, 
                name = :name, 
                description = :description, 
                price = :price, 
                image = :image,
                promo_price = :promo_price,
                promo_start = :promo_start,
                promo_end = :promo_end,
                promo_quota = :promo_quota
            WHERE id = :id
        ");
        
        // --- PASTIKAN JUMLAH bindParam INI ADA 10 BIJI SESUAI QUERY DI ATAS ---
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':promo_price', $data['promo_price']);
        $stmt->bindParam(':promo_start', $data['promo_start']);
        $stmt->bindParam(':promo_end', $data['promo_end']);
        $stmt->bindParam(':promo_quota', $data['promo_quota']);
        $stmt->bindParam(':id', $data['id']); // Ini yang paling sering kelupaan!
        
        return $stmt->execute();
    }

    // 6. Menyimpan relasi varian ke menu

    // 7. Hapus menu
    public function deleteMenu($id) {
        $stmt = $this->conn->prepare("DELETE FROM menus WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 8. Fitur Sakelar On/Off (Status Tersedia)
    public function toggleStatus($id) {
        $stmt = $this->conn->prepare("UPDATE menus SET is_available = NOT is_available WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 9. Fungsi untuk fitur AI Upselling (ESB Order)
    public function getRecommendationsByMenu($current_menu_id) {
        $stmt = $this->conn->prepare("SELECT * FROM menus WHERE id != :id AND is_available = 1 ORDER BY RAND() LIMIT 2");
        $stmt->bindParam(':id', $current_menu_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Mengambil daftar ID varian yang sedang dipakai oleh sebuah menu
    public function getMenuVariantGroupIds($menu_id) {
        try {
            $stmt = $this->conn->prepare("SELECT variant_group_id FROM menu_variant_links WHERE menu_id = :menu_id");
            $stmt->bindParam(':menu_id', $menu_id);
            $stmt->execute();
            // Kembalikan dalam bentuk array simple, contoh: [1, 3, 5]
            return $stmt->fetchAll(PDO::FETCH_COLUMN); 
        } catch (Exception $e) {
            return [];
        }
    }
}