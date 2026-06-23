<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $data['title'] ?? 'Riwayat Transaksi' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Kunci layar utama agar tidak melar ke bawah */
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }
        
        /* Set tinggi sidebar pas semonitor dan diam */
        .sidebar {
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        
        /* Set area konten pas semonitor dan BISA DI-SCROLL */
        .main-content {
            height: 100vh;
            overflow-y: auto;
            background-color: #f8f9fa;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #34495e;
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0">
            
            <!-- SIDEBAR KIRI -->
            <div class="col-md-3 col-lg-2 bg-dark sidebar p-3 d-flex flex-column text-white">
                <div class="text-center py-3 mb-4 border-bottom border-secondary">
                    <h4 class="fw-bold m-0 text-warning">👑 Admin Panel</h4>
                    <small class="text-muted">Manajemen E-Menu</small>
                </div>
                
                <ul class="nav nav-pills flex-column mb-auto gap-2">
                    <li class="nav-item">
                        <a href="<?= BASEURL ?>/public/admin/menus" class="nav-link py-2 px-3 rounded d-flex align-items-center">
                            <span class="me-3 fs-5">🍔</span> Manajemen Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASEURL ?>/public/admin/categories" class="nav-link py-2 px-3 rounded d-flex align-items-center">
                            <span class="me-3 fs-5">📂</span> Manajemen Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASEURL ?>/public/admin/master_variants" class="nav-link py-2 px-3 rounded d-flex align-items-center">
                            <span class="me-3 fs-5">⚙️</span> Master Varian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASEURL ?>/public/admin/transactions" class="nav-link active py-2 px-3 rounded d-flex align-items-center">
                            <span class="me-3 fs-5">📜</span> Riwayat Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASEURL ?>/public/admin/dashboard" class="nav-link py-2 px-3 rounded d-flex align-items-center">
                            <span class="me-3 fs-5">📊</span> Dashboard
                        </a>
                    </li>
                </ul>
                
                <div class="mt-auto pt-3 border-top border-secondary">
                    <a href="<?= BASEURL ?>/public/" class="btn btn-sm btn-danger w-100 py-2 fw-bold shadow-sm">
                        👋 Keluar Ke Menu Utama
                    </a>
                </div>
            </div>
            
            <!-- KONTEN UTAMA KANAN -->
            <div class="col-md-9 col-lg-10 p-4 main-content">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold m-0 text-dark">📜 Riwayat Transaksi Keseluruhan</h2>
                    <button onclick="window.location.reload()" class="btn btn-primary fw-bold shadow-sm px-4">
                        🔄 Segarkan Data
                    </button>
                </div>

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0 bg-white">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4 border-bottom-0">No. Pesanan</th>
                                    <th class="py-3 border-bottom-0">Waktu</th>
                                    <th class="py-3 border-bottom-0">Pelanggan</th>
                                    <th class="py-3 border-bottom-0">Meja</th>
                                    <th class="py-3 text-end border-bottom-0">Total Harga</th>
                                    <th class="py-3 text-center border-bottom-0">Status Dapur</th>
                                    <th class="py-3 text-center border-bottom-0 px-4">Status Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['orders'])): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">Belum ada riwayat transaksi yang tercatat.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($data['orders'] as $order): ?>
                                        <tr>
                                            <td class="px-4 py-3 fw-bold text-dark"><?= $order['order_number'] ?></td>
                                            <td class="text-muted small"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
                                            <td class="fw-semibold text-dark"><?= $order['customer_name'] ?></td>
                                            <td><span class="badge bg-secondary border border-secondary bg-opacity-10 text-secondary fw-bold">Meja <?= $order['table_number'] ?></span></td>
                                            <td class="text-end fw-bold text-danger"><?= format_currency($order['total_amount']) ?></td>
                                            
                                            <td class="text-center">
                                                <?php if($order['order_status'] == 'pending'): ?>
                                                    <span class="badge bg-danger rounded-pill px-3">Menunggu</span>
                                                <?php elseif($order['order_status'] == 'cooking'): ?>
                                                    <span class="badge bg-warning text-dark rounded-pill px-3">Dimasak</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success rounded-pill px-3">Selesai Dapur</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center px-4">
                                                <?php if($order['payment_status'] == 'unpaid'): ?>
                                                    <span class="badge bg-warning text-dark px-3 py-2">Belum Lunas</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success px-3 py-2">Lunas</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>