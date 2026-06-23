<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? translate('dashboard_title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --primary: #281C59; --secondary: #4E8D9C; --accent: #85C79A; --light: #EDF7BD; }
        * { font-family: 'Inter', sans-serif; }
        html, body { height: 100%; margin: 0; overflow: hidden; }
        .sidebar { height: 100vh; overflow-y: auto; background: linear-gradient(180deg, var(--primary) 0%, #1a1240 100%); box-shadow: 4px 0 16px rgba(40,28,89,0.2); }
        .main-content { height: 100vh; overflow-y: auto; background: linear-gradient(135deg, #f0f4f8 0%, #e8f5e9 100%); }
        .sidebar .nav-link { color: rgba(255,255,255,0.6); transition: all 0.3s; font-weight: 500; border-radius: 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #ffffff; background: rgba(255,255,255,0.12); }
        .stat-card { transition: transform 0.2s; border: none; border-radius: 18px; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(40,28,89,0.1) !important; }
        .chart-container { position: relative; height: 300px; width: 100%; }
        .lang-switcher { background: rgba(255,255,255,0.1); padding: 4px; border-radius: 12px; white-space: nowrap; display: inline-flex; }
        .lang-switcher a { font-size: 0.9rem; font-weight: 700; color: rgba(255,255,255,0.6); text-decoration: none; transition: all 0.2s; padding: 4px 10px; border-radius: 8px; }
        .lang-switcher a:hover { color: white; background: rgba(255,255,255,0.1); }
        .lang-switcher .active-lang { background: white !important; color: var(--primary) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .carousel-control-prev-icon, .carousel-control-next-icon { filter: invert(100%); }
        .card { border: none; border-radius: 18px; }
        .table-dark { background: var(--primary) !important; }
        .table-dark th { background: var(--primary) !important; border-color: rgba(255,255,255,0.1) !important; }

        /* Page transition */
        .main-content { animation: fadeSlideIn 0.4s ease-out; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .sidebar .nav-link { position: relative; overflow: hidden; }
        .sidebar .nav-link::after { content: ''; position: absolute; top: 50%; left: 50%; width: 0; height: 0; background: rgba(255,255,255,0.15); border-radius: 50%; transform: translate(-50%, -50%); transition: width 0.4s, height 0.4s; }
        .sidebar .nav-link:active::after { width: 300px; height: 300px; }

        @media (max-width: 768px) { html, body { overflow: auto; } .sidebar { height: auto; } .main-content { height: auto; } }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0">
            
            <!-- SIDEBAR -->
            <div class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column text-white">
                <div class="text-center py-3 mb-4 border-bottom border-secondary">
                    <h4 class="fw-bold m-0" style="color: var(--light);">👑 <?= translate('admin_panel') ?></h4>
                    <small class="opacity-50"><?= translate('admin_emenu') ?></small>
                    <div class="lang-switcher d-flex gap-1 justify-content-center mt-2">
                        <a href="<?= BASEURL ?>/public/language/switch/id" class="<?= ($_SESSION['lang'] ?? 'id') == 'id' ? 'active-lang' : '' ?>">ID</a>
                        <a href="<?= BASEURL ?>/public/language/switch/en" class="<?= ($_SESSION['lang'] ?? 'id') == 'en' ? 'active-lang' : '' ?>">EN</a>
                        <a href="<?= BASEURL ?>/public/language/switch/vi" class="<?= ($_SESSION['lang'] ?? 'id') == 'vi' ? 'active-lang' : '' ?>">VN</a>
                    </div>
                </div>
                <ul class="nav nav-pills flex-column mb-auto gap-2">
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/menus" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">🍔</span> <?= translate('menu_management') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/categories" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">📂</span> <?= translate('category_mgmt') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/master_variants" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">⚙️</span> <?= translate('master_variant') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/dashboard" class="nav-link active py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">📊</span> <?= translate('dashboard') ?></a></li>
                </ul>
                <div class="mt-auto pt-3 border-top border-secondary">
                    <a href="<?= BASEURL ?>/public/" class="btn btn-sm w-100 py-2 fw-bold shadow-sm" style="background: rgba(255,255,255,0.1); color: var(--light);">👋 <?= translate('exit_main_menu') ?></a>
                </div>
            </div>
            
            <!-- MAIN CONTENT -->
            <div class="col-md-9 col-lg-10 p-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold m-0" style="color: var(--primary);">📊 <?= translate('dashboard_title') ?></h2>
                    <span class="text-muted bg-white px-3 py-1 rounded-pill shadow-sm border"><?= date('l, d F Y') ?></span>
                </div>

                <div class="row g-4 mb-4 align-items-center">
                    <div class="col-md-5 col-lg-4">
                        <div class="card stat-card text-white shadow-sm h-100 p-4" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
                            <div class="card-body p-0 d-flex flex-column justify-content-center">
                                <h6 class="fw-semibold opacity-75 mb-2 text-uppercase">Total Revenue (All Time)</h6>
                                <h2 class="fw-extrabold mb-0"><?= format_currency($data['total_sales']) ?></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7 col-lg-8">
                        <form action="<?= BASEURL ?>/public/admin/dashboard" method="GET" class="card shadow-sm p-3 h-100 d-flex flex-row align-items-end gap-3 rounded-4">
                            <div class="flex-grow-1">
                                <label class="form-label small fw-bold text-muted mb-1">Start Date</label>
                                <input type="date" name="start_date" class="form-control bg-light border-0 rounded-3" value="<?= $data['start_date'] ?>" required>
                            </div>
                            <div class="flex-grow-1">
                                <label class="form-label small fw-bold text-muted mb-1">End Date</label>
                                <input type="date" name="end_date" class="form-control bg-light border-0 rounded-3" value="<?= $data['end_date'] ?>" required>
                            </div>
                            <div>
                                <button type="submit" class="btn fw-bold px-4 text-white rounded-3" style="background: var(--secondary);">🔍 Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm h-100 rounded-4">
                            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
                                <h6 class="fw-bold m-0" style="color: var(--primary);">📈 Revenue Trend</h6>
                                <small class="text-muted">From: <?= date('d M', strtotime($data['start_date'])) ?> to <?= date('d M Y', strtotime($data['end_date'])) ?></small>
                            </div>
                            <div class="card-body">
                                <div class="chart-container"><canvas id="revenueChart"></canvas></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-sm h-100 rounded-4">
                            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
                                <h6 class="fw-bold m-0" style="color: var(--accent);">🍩 Top Sellers</h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center position-relative">
                                <div id="donutCarousel" class="carousel slide w-100" data-bs-ride="carousel" data-bs-interval="8000">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <h6 class="text-center text-muted fw-bold mb-3">Top Menu</h6>
                                            <div class="chart-container" style="height: 220px;"><canvas id="topMenuChart"></canvas></div>
                                        </div>
                                        <div class="carousel-item">
                                            <h6 class="text-center text-muted fw-bold mb-3">Top Variants</h6>
                                            <div class="chart-container" style="height: 220px;"><canvas id="topVariantChart"></canvas></div>
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#donutCarousel" data-bs-slide="prev" style="width: 20px;"><span class="carousel-control-prev-icon" aria-hidden="true"></span></button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#donutCarousel" data-bs-slide="next" style="width: 20px;"><span class="carousel-control-next-icon" aria-hidden="true"></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-5 rounded-4">
                    <div class="card-header bg-white border-bottom py-3 rounded-top-4">
                        <h6 class="fw-bold m-0" style="color: var(--primary);">🏆 Top 5 Best-Selling Menu</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3" width="10%">Rank</th>
                                        <th class="py-3" width="30%">Menu Name</th>
                                        <th class="py-3" width="40%">Variant / Notes</th>
                                        <th class="text-end pe-4 py-3" width="20%">Total Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['favorite_menus'])): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted"><?= translate('no_data') ?></td></tr>
                                    <?php else: ?>
                                        <?php $rank = 1; foreach($data['favorite_menus'] as $fav): ?>
                                            <tr>
                                                <td class="ps-4 py-3 fw-bold" style="color: var(--secondary);">#<?= $rank++ ?></td>
                                                <td class="py-3 fw-semibold" style="color: var(--primary);"><?= translate(strtolower($fav['menu_name']), $fav['menu_name']) ?></td>
                                                <td class="py-3 text-muted small"><?= !empty($fav['variant']) ? $fav['variant'] : '<i>(No variant)</i>' ?></td>
                                                <td class="text-end pe-4 py-3">
                                                    <span class="badge px-3 py-2 fs-6 rounded-pill text-white" style="background: var(--accent);"><?= $fav['total_sold'] ?></span>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php
        // Revenue chart data
        $revLabels = []; $revData = [];
        if(!empty($data['revenue_data'])) {
            foreach($data['revenue_data'] as $rev) {
                $revLabels[] = date('d M', strtotime($rev['order_date']));
                $revData[] = $rev['daily_total'];
            }
        } else {
            $revLabels = ['No data'];
            $revData = [0];
        }

        // Menu donut data
        $menuLabels = []; $menuData = [];
        if(!empty($data['favorite_menus'])) {
            foreach($data['favorite_menus'] as $fav) {
                $menuLabels[] = $fav['menu_name'];
                $menuData[] = $fav['total_sold'];
            }
        } else {
            $menuLabels = ['No sales yet'];
            $menuData = [1];
        }

        // Variant donut data
        $varLabels = []; $varData = [];
        if(!empty($data['favorite_variants'])) {
            foreach($data['favorite_variants'] as $var) {
                $varLabels[] = $var['variant_name'];
                $varData[] = $var['total_sold'];
            }
        } else {
            $varLabels = ['No sales yet'];
            $varData = [1];
        }
    ?>

    <script>
        const formatRupiah = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);

        // Revenue Line Chart
        new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: <?= json_encode($revLabels) ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?= json_encode($revData) ?>,
                    borderColor: '#4E8D9C',
                    backgroundColor: 'rgba(78, 141, 156, 0.1)',
                    borderWidth: 3, fill: true, tension: 0.4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => ' Revenue: ' + formatRupiah(ctx.raw) } } },
                scales: { y: { beginAtZero: true } }
            }
        });

        const donutColors = ['#85C79A', '#4E8D9C', '#EDF7BD', '#281C59', '#6db87f'];
        const emptyColor = ['#d1d3e2'];

        // Top Menu Donut
        new Chart(document.getElementById('topMenuChart').getContext('2d'), {
            type: 'doughnut',
            data: { labels: <?= json_encode($menuLabels) ?>, datasets: [{ data: <?= json_encode($menuData) ?>, backgroundColor: <?= empty($data['favorite_menus']) ? 'emptyColor' : 'donutColors' ?> }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%' }
        });

        // Top Variant Donut
        new Chart(document.getElementById('topVariantChart').getContext('2d'), {
            type: 'doughnut',
            data: { labels: <?= json_encode($varLabels) ?>, datasets: [{ data: <?= json_encode($varData) ?>, backgroundColor: <?= empty($data['favorite_variants']) ? 'emptyColor' : "['#281C59', '#EDF7BD', '#4E8D9C', '#85C79A', '#6db87f']" ?> }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%' }
        });
    </script>
</body>
</html>