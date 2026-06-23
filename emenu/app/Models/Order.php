<?php
class Order extends Database {
    public function createOrder($table_id, $customer_name, $total_amount, $cart_items, $payment_method = 'cash') {
        try {
            $this->conn->beginTransaction();
            $order_number = "ORD-" . date('YmdHis') . "-" . rand(100, 999);

            // Jika bayar online (dummy), kita anggap langsung lunas ('paid'). Jika tunai, 'unpaid'
            $payment_status = ($payment_method == 'online') ? 'paid' : 'unpaid';

            $sqlOrder = "INSERT INTO orders (table_id, customer_name, order_number, total_amount, order_status, payment_status) 
                         VALUES (:table_id, :customer_name, :order_number, :total_amount, 'pending', :payment_status)";
            
            $stmt = $this->conn->prepare($sqlOrder);
            $stmt->execute([
                ':table_id'       => $table_id,
                ':customer_name'  => $customer_name,
                ':order_number'   => $order_number,
                ':total_amount'   => $total_amount,
                ':payment_status' => $payment_status
            ]);
            
            $order_id = $this->conn->lastInsertId();

            $sqlItem = "INSERT INTO order_items (order_id, menu_id, quantity, price_per_item, subtotal, notes) 
                        VALUES (:order_id, :menu_id, :quantity, :price_per_item, :subtotal, :notes)";
            $stmtItem = $this->conn->prepare($sqlItem);

            foreach ($cart_items as $item) {
                $stmtItem->execute([
                    ':order_id'       => $order_id,
                    ':menu_id'        => $item['menu_id'],
                    ':quantity'       => $item['qty'],
                    ':price_per_item' => $item['price'], 
                    ':subtotal'       => $item['price'] * $item['qty'], 
                    ':notes'          => $item['notes'] ?? ''
                ]);
            }

            $this->conn->commit();
            return $order_id; // KEMBALIKAN ID PESANAN AGAR BISA DIBUKA DI HALAMAN STRUK

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // FUNGSI BARU: Ambil Data Pesanan
    public function getOrderById($order_id) {
        $stmt = $this->conn->prepare("
            SELECT o.*, t.table_number 
            FROM orders o 
            JOIN tables t ON o.table_id = t.id 
            WHERE o.id = :id
        ");
        $stmt->bindParam(':id', $order_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // FUNGSI BARU: Ambil Item Pesanan
    public function getOrderItems($order_id) {
        $stmt = $this->conn->prepare("
            SELECT oi.*, m.name as menu_name 
            FROM order_items oi
            JOIN menus m ON oi.menu_id = m.id
            WHERE oi.order_id = :id
        ");
        $stmt->bindParam(':id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActiveOrders() {
        $stmt = $this->conn->prepare("
            SELECT o.*, t.table_number 
            FROM orders o 
            JOIN tables t ON o.table_id = t.id 
            WHERE o.order_status != 'completed' AND o.order_status != 'cancelled'
            ORDER BY o.created_at ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($order_id, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET order_status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $order_id);
        return $stmt->execute();
    }
    // 3. Update status per item/menu
    public function updateOrderItemStatus($item_id, $status) {
        $stmt = $this->conn->prepare("UPDATE order_items SET item_status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $item_id);
        return $stmt->execute();
    }

    // 4. Cek apakah semua menu dalam pesanan ini sudah selesai?
    public function checkAndUpdateOrderStatus($order_id) {
        // Hitung berapa menu yang masih 'pending'
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM order_items WHERE order_id = :order_id AND (item_status = 'pending' OR item_status IS NULL)");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        $pendingCount = $stmt->fetchColumn();

        // Jika tidak ada lagi yang pending (semua sudah ready), ubah status nota utamanya jadi ready!
        if ($pendingCount == 0) {
            $this->updateOrderStatus($order_id, 'ready');
        }
    }

    public function updatePaymentStatus($order_id, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET payment_status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $order_id);
        return $stmt->execute();
    }
    // Menghitung Total Pendapatan Seluruh Penjualan
    public function getTotalSales() {
        try {
            // Jika kamu punya kolom status (misal: 'lunas'/'selesai'), tambahkan WHERE status = 'selesai'
            $stmt = $this->conn->prepare("SELECT SUM(total_amount) as total FROM orders");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    // Mengambil 5 Menu & Varian Paling Laris
    public function getFavoriteMenus() {
        try {
            // Menggabungkan tabel order_items dan menus, lalu dihitung total porsinya
            $query = "SELECT m.name as menu_name, oi.notes as variant, SUM(oi.qty) as total_sold
                      FROM order_items oi
                      JOIN menus m ON oi.menu_id = m.id
                      GROUP BY oi.menu_id, oi.notes
                      ORDER BY total_sold DESC
                      LIMIT 5";
                      
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    // Menarik data pendapatan harian berdasarkan rentang tanggal
    public function getRevenueByDate($startDate, $endDate) {
        try {
            // Sesuaikan 'created_at' dengan nama kolom tanggal di tabel orders kamu
            $query = "SELECT DATE(created_at) as order_date, SUM(total_amount) as daily_total
                      FROM orders
                      WHERE DATE(created_at) BETWEEN :start AND :end
                      GROUP BY DATE(created_at)
                      ORDER BY order_date ASC";
                      
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':start' => $startDate, ':end' => $endDate]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Mengambil 5 Varian Paling Laris
    public function getFavoriteVariants() {
        try {
            $query = "SELECT notes as variant_name, SUM(qty) as total_sold
                      FROM order_items
                      WHERE notes IS NOT NULL AND notes != ''
                      GROUP BY notes
                      ORDER BY total_sold DESC
                      LIMIT 5";
                      
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    public function updateOrderTotalAndPay($order_id, $total) {
        $stmt = $this->conn->prepare("UPDATE orders SET total_amount = :total, payment_status = 'paid' WHERE id = :id");
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':id', $order_id);
        return $stmt->execute();
    }

    public function getKitchenOrders() {
        // Ambil data order beserta nomor mejanya
        $stmt = $this->conn->prepare("
            SELECT o.*, t.table_number 
            FROM orders o 
            JOIN tables t ON o.table_id = t.id 
            WHERE o.order_status IN ('pending', 'cooking') AND o.payment_status = 'paid'
            ORDER BY o.created_at ASC
        ");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ambil detail item makanannya untuk masing-masing order
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $orders;
    }

    // --- FUNGSI KHUSUS KASIR (CASHIER) ---

    // 1. Ambil daftar pesanan untuk kasir (Prioritaskan yang belum lunas)
    public function getCashierOrders() {
        $stmt = $this->conn->prepare("
            SELECT o.*, t.table_number 
            FROM orders o 
            JOIN tables t ON o.table_id = t.id 
            ORDER BY 
                CASE WHEN o.payment_status = 'unpaid' THEN 1 ELSE 2 END, 
                o.created_at DESC
            LIMIT 100
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Konfirmasi Pembayaran (Ubah jadi lunas)
    public function markAsPaid($order_id) {
        $stmt = $this->conn->prepare("UPDATE orders SET payment_status = 'paid' WHERE id = :id");
        $stmt->bindParam(':id', $order_id);
        return $stmt->execute();
    }
    // Cari order berdasarkan Nomor Pesanan (Hasil Scan QR)
    public function getOrderByNumber($order_number) {
        $stmt = $this->conn->prepare("
            SELECT o.*, t.table_number 
            FROM orders o 
            JOIN tables t ON o.table_id = t.id 
            WHERE o.order_number = :order_number
        ");
        $stmt->bindParam(':order_number', $order_number);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // FUNGSI KHUSUS ADMIN (Tarik Semua Riwayat Transaksi)
    public function getAllOrders() {
        $stmt = $this->conn->prepare("
            SELECT o.*, t.table_number 
            FROM orders o 
            JOIN tables t ON o.table_id = t.id 
            ORDER BY o.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}