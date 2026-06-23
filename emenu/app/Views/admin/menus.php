<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? translate('menu_management') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #281C59; --secondary: #4E8D9C; --accent: #85C79A; --light: #EDF7BD; }
        * { font-family: 'Inter', sans-serif; }
        html, body { height: 100%; margin: 0; overflow: hidden; }
        .sidebar { height: 100vh; overflow-y: auto; background: linear-gradient(180deg, var(--primary) 0%, #1a1240 100%); box-shadow: 4px 0 16px rgba(40,28,89,0.2); }
        .main-content { height: 100vh; overflow-y: auto; background: linear-gradient(135deg, #f0f4f8 0%, #e8f5e9 100%); }
        .sidebar .nav-link { color: rgba(255,255,255,0.6); transition: all 0.3s; font-weight: 500; border-radius: 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #ffffff; background: rgba(255,255,255,0.12); }
        .table-dark { background: var(--primary) !important; }
        .table-dark th { background: var(--primary) !important; border-color: rgba(255,255,255,0.1) !important; }
        .lang-switcher { background: rgba(255,255,255,0.1); padding: 4px; border-radius: 12px; white-space: nowrap; display: inline-flex; }
        .lang-switcher a { font-size: 0.9rem; font-weight: 700; color: rgba(255,255,255,0.6); text-decoration: none; transition: all 0.2s; padding: 4px 10px; border-radius: 8px; }
        .lang-switcher a:hover { color: white; background: rgba(255,255,255,0.1); }
        .lang-switcher .active-lang { background: white !important; color: var(--primary) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        /* Page transition */
        .main-content { animation: fadeSlideIn 0.4s ease-out; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .sidebar .nav-link { position: relative; overflow: hidden; }
        .sidebar .nav-link::after { content: ''; position: absolute; top: 50%; left: 50%; width: 0; height: 0; background: rgba(255,255,255,0.15); border-radius: 50%; transform: translate(-50%, -50%); transition: width 0.4s, height 0.4s; }
        .sidebar .nav-link:active::after { width: 300px; height: 300px; }

        @media (max-width: 768px) {
            html, body { overflow: auto; }
            .sidebar { height: auto; }
            .main-content { height: auto; }
        }
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
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/menus" class="nav-link active py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">🍔</span> <?= translate('menu_management') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/categories" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">📂</span> <?= translate('category_mgmt') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/master_variants" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">⚙️</span> <?= translate('master_variant') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/dashboard" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">📊</span> <?= translate('dashboard') ?></a></li>
                </ul>
                
                <div class="mt-auto pt-3 border-top border-secondary">
                    <a href="<?= BASEURL ?>/public/" class="btn btn-sm w-100 py-2 fw-bold shadow-sm" style="background: rgba(255,255,255,0.1); color: var(--light);">👋 <?= translate('exit_main_menu') ?></a>
                </div>
            </div>
            
            <!-- MAIN -->
            <div class="col-md-9 col-lg-10 p-4 main-content">
                
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold m-0" style="color: var(--primary);">🍔 <?= translate('menu_management') ?></h2>
                        <a href="<?= BASEURL ?>/public/admin/menuForm" class="btn fw-bold shadow-sm text-white rounded-3" style="background: var(--secondary);"><?= translate('add_new_menu') ?></a>
                    </div>

                    <div class="table-responsive">  
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th width="8%"><?= translate('image') ?></th>
                                    <th width="15%">Menu</th>
                                    <th width="25%"><?= translate('menu_desc') ?></th>
                                    <th width="12%"><?= translate('category') ?></th>
                                    <th width="13%"><?= translate('price') ?></th>
                                    <th width="15%"><?= translate('status') ?></th>
                                    <th width="12%"><?= translate('actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['menus'])): ?>
                                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= translate('no_data') ?></td></tr>
                                <?php else: ?>
                                    <?php foreach($data['menus'] as $m): 
                                        $isPromoActive = false;
                                        if (!empty($m['promo_price']) && $m['promo_price'] > 0) {
                                            date_default_timezone_set('Asia/Jakarta');
                                            $now = date('Y-m-d H:i:s');
                                            $validStart = empty($m['promo_start']) || $m['promo_start'] <= $now;
                                            $validEnd = empty($m['promo_end']) || $m['promo_end'] >= $now;
                                            $validQuota = (!isset($m['promo_quota']) || $m['promo_quota'] > 0 || $m['promo_quota'] == null); 
                                            if ($validStart && $validEnd && $validQuota) $isPromoActive = true;
                                        }
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <div class="position-relative d-inline-block">
                                                <?php if($isPromoActive): ?>
                                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="Promo Active" style="z-index: 5;"></span>
                                                <?php endif; ?>
                                                <?php if($m['image']): ?>
                                                    <img src="<?= BASEURL ?>/public/assets/images/<?= $m['image'] ?>" class="rounded shadow-sm" width="60" height="60" style="object-fit:cover;">
                                                <?php else: ?>
                                                    <div class="text-white rounded d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px; font-size: 10px; background: var(--secondary);"><?= translate('no_image') ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><strong class="fs-6" style="color: var(--primary);"><?= translate(strtolower($m['name']), $m['name']) ?></strong></td>
                                        <td><small class="text-muted"><?= !empty($m['description']) ? translate(strtolower($m['description']), $m['description']) : translate('no_desc') ?></small></td>
                                        <td class="text-center"><span class="badge px-2 py-2" style="background: var(--secondary);"><?= translate(strtolower($m['category_name']), $m['category_name']) ?></span></td>
                                        <td class="text-end pe-3">
                                            <?php if($isPromoActive): ?>
                                                <small class="text-muted text-decoration-line-through d-block" style="font-size: 11px;"><?= format_currency($m['price']) ?></small>
                                                <span class="fw-bold text-danger"><?= format_currency($m['promo_price']) ?></span>
                                            <?php else: ?>
                                                <span class="fw-bold" style="color: var(--accent);"><?= format_currency($m['price']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                                       id="status_<?= $m['id'] ?>" data-id="<?= $m['id'] ?>"
                                                       <?= $m['is_available'] ? 'checked' : '' ?> 
                                                       style="transform: scale(1.3); cursor: pointer;">
                                                <label class="form-check-label ms-2 small fw-bold <?= $m['is_available'] ? '' : 'text-danger' ?>" for="status_<?= $m['id'] ?>" style="<?= $m['is_available'] ? 'color: var(--accent);' : '' ?>">
                                                    <?= $m['is_available'] ? translate('available') : translate('not_available') ?>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= BASEURL ?>/public/admin/editMenu/<?= $m['id'] ?>" class="btn btn-sm px-3 fw-semibold shadow-sm text-white rounded-3" style="background: var(--secondary);" title="Edit Menu">
                                                ✏️ <?= translate('edit') ?>
                                            </a>
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
    <script>
        const availableText = '<?= translate('available') ?>';
        const notAvailableText = '<?= translate('not_available') ?>';

        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const menuId = this.getAttribute('data-id');
                const label = this.nextElementSibling;
                
                if(this.checked) {
                    label.innerText = availableText;
                    label.classList.remove('text-danger');
                    label.style.color = 'var(--accent)';
                } else {
                    label.innerText = notAvailableText;
                    label.style.color = '';
                    label.classList.add('text-danger');
                }

                window.location.href = `<?= BASEURL ?>/public/admin/toggleMenuStatus/${menuId}`;
            });
        });
    </script>
</body>
</html>