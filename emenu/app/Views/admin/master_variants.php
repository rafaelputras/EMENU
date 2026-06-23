<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? translate('variant_group') ?></title>
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
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/master_variants" class="nav-link active py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">⚙️</span> <?= translate('master_variant') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/dashboard" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">📊</span> <?= translate('dashboard') ?></a></li>
                </ul>
                <div class="mt-auto pt-3 border-top border-secondary">
                    <a href="<?= BASEURL ?>/public/" class="btn btn-sm w-100 py-2 fw-bold shadow-sm" style="background: rgba(255,255,255,0.1); color: var(--light);">👋 <?= translate('exit_main_menu') ?></a>
                </div>
            </div>
            
            <!-- MAIN -->
            <div class="col-md-9 col-lg-10 p-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold m-0" style="color: var(--primary);">⚙️ <?= translate('variant_group') ?></h2>
                </div>

                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-body bg-white rounded-4 p-4">
                        <form action="<?= BASEURL ?>/public/admin/saveVariantGroup" method="POST" class="d-flex gap-3 align-items-center">
                            <div class="flex-grow-1">
                                <input type="text" name="name" class="form-control bg-light border-0 rounded-3" placeholder="<?= translate('group_name') ?> (e.g., Spice Level, Topping)" required>
                            </div>
                            <div class="w-auto">
                                <select name="type" class="form-select bg-light border-0 rounded-3">
                                    <option value="radio"><?= translate('choose_one') ?></option>
                                    <option value="checkbox"><?= translate('choose_many') ?></option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn px-4 fw-bold shadow-sm text-white rounded-3" style="background: var(--secondary);">+ <?= translate('add') ?></button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-0 rounded-4 overflow-hidden">
                        <ul class="list-group list-group-flush">
                            <?php if(empty($data['groups'])): ?>
                                <li class="list-group-item text-center py-4 text-muted"><?= translate('no_data') ?></li>
                            <?php else: ?>
                                <?php foreach($data['groups'] as $g): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <div>
                                            <strong class="fs-6" style="color: var(--primary);"><?= translate(strtolower($g['name']), $g['name']) ?></strong>
                                            <span class="badge ms-2 opacity-75" style="background: var(--secondary);">
                                                <?= $g['type'] == 'radio' ? translate('choose_one') : translate('choose_many') ?>
                                            </span>
                                        </div>
                                        <a href="<?= BASEURL ?>/public/admin/variants/<?= $g['id'] ?>" class="btn btn-sm fw-bold shadow-sm text-white rounded-3" style="background: var(--secondary);">
                                            ⚙️ <?= translate('variant_options') ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>