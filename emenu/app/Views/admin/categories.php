<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? translate('category_mgmt') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #281C59; --primary-rgb: 40, 28, 89; --secondary: #4E8D9C; --accent: #85C79A; --light: #EDF7BD; }
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        html, body { height: 100%; margin: 0; overflow: hidden; }

        /* Sidebar */
        .sidebar { height: 100vh; overflow-y: auto; background: linear-gradient(180deg, var(--primary) 0%, #1a1240 100%); box-shadow: 4px 0 16px rgba(40,28,89,0.2); }
        .main-content { height: 100vh; overflow-y: auto; background: linear-gradient(135deg, #f0f4f8 0%, #e8f5e9 100%); }
        .sidebar .nav-link { color: rgba(255,255,255,0.6); transition: all 0.3s; font-weight: 500; border-radius: 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #ffffff; background: rgba(255,255,255,0.12); }

        /* Language Switcher - matching dashboard/menus style */
        .lang-switcher { background: rgba(255,255,255,0.1); padding: 4px; border-radius: 12px; white-space: nowrap; display: inline-flex; }
        .lang-switcher a { font-size: 0.9rem; font-weight: 700; color: rgba(255,255,255,0.6); text-decoration: none; transition: all 0.2s; padding: 4px 10px; border-radius: 8px; }
        .lang-switcher a:hover { color: white; background: rgba(255,255,255,0.1); }
        .lang-switcher .active-lang { background: white !important; color: var(--primary) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        /* Page transition */
        .main-content { animation: fadeSlideIn 0.4s ease-out; }
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Sidebar nav link click transition */
        .sidebar .nav-link { position: relative; overflow: hidden; }
        .sidebar .nav-link::after {
            content: ''; position: absolute; top: 50%; left: 50%; width: 0; height: 0;
            background: rgba(255,255,255,0.15); border-radius: 50%;
            transform: translate(-50%, -50%); transition: width 0.4s, height 0.4s;
        }
        .sidebar .nav-link:active::after { width: 300px; height: 300px; }

        /* Add Category Card */
        .add-card {
            background: white;
            border: 2px dashed rgba(var(--primary-rgb), 0.15);
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .add-card:hover { border-color: var(--secondary); box-shadow: 0 8px 24px rgba(78,141,156,0.12); }
        .add-card:focus-within { border-color: var(--secondary); border-style: solid; box-shadow: 0 0 0 4px rgba(78,141,156,0.1); }

        /* Category Cards */
        .cat-card {
            background: white;
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            overflow: hidden;
            animation: cardIn 0.4s ease-out backwards;
        }
        .cat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(40,28,89,0.12); }
        .cat-card.hidden-cat { opacity: 0.6; }
        .cat-card.hidden-cat:hover { opacity: 0.85; }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Category icon circle */
        .cat-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s;
        }
        .cat-card:hover .cat-icon { transform: scale(1.1) rotate(-5deg); }

        /* Action buttons */
        .cat-actions { display: flex; gap: 6px; }
        .cat-actions .btn {
            border-radius: 10px; font-weight: 600; font-size: 0.8rem;
            padding: 6px 14px; transition: all 0.2s;
        }
        .cat-actions .btn:hover { transform: translateY(-1px); }

        .btn-edit { background: var(--secondary); color: white; border: none; }
        .btn-edit:hover { background: #3d7a88; color: white; box-shadow: 0 4px 12px rgba(78,141,156,0.3); }
        .btn-delete { background: transparent; color: #dc3545; border: 1.5px solid #dc3545; }
        .btn-delete:hover { background: #dc3545; color: white; box-shadow: 0 4px 12px rgba(220,53,69,0.3); }
        .btn-hide { background: transparent; color: #6c757d; border: 1.5px solid #d1d3e2; }
        .btn-hide:hover { background: #6c757d; color: white; box-shadow: 0 4px 12px rgba(108,117,125,0.3); }
        .btn-activate { background: var(--accent); color: white; border: none; }
        .btn-activate:hover { background: #6db87f; color: white; box-shadow: 0 4px 12px rgba(133,199,154,0.3); }

        /* Stats bar */
        .stat-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: white; border-radius: 50px; padding: 8px 18px;
            font-weight: 600; font-size: 0.85rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.2s;
        }
        .stat-pill:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .stat-dot { width: 8px; height: 8px; border-radius: 50%; }

        /* Empty state */
        .empty-state {
            padding: 60px 20px; text-align: center;
            background: white; border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .empty-state-icon { font-size: 4rem; margin-bottom: 16px; animation: bounce 2s infinite; }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Modal */
        .modal-content { border: none; border-radius: 20px; overflow: hidden; }
        .modal-backdrop.show { backdrop-filter: blur(4px); }

        /* Table styles matching other pages */
        .table-dark { background: var(--primary) !important; }
        .table-dark th { background: var(--primary) !important; border-color: rgba(255,255,255,0.1) !important; }
        .card { border: none; border-radius: 18px; }

        @media (max-width: 768px) {
            html, body { overflow: auto; }
            .sidebar { height: auto; position: relative; }
            .main-content { height: auto; }
            .cat-actions { flex-wrap: wrap; }
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
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/menus" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">🍔</span> <?= translate('menu_management') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/categories" class="nav-link active py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">📂</span> <?= translate('category_mgmt') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/master_variants" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">⚙️</span> <?= translate('master_variant') ?></a></li>
                    <li class="nav-item"><a href="<?= BASEURL ?>/public/admin/dashboard" class="nav-link py-2 px-3 d-flex align-items-center"><span class="me-3 fs-5">📊</span> <?= translate('dashboard') ?></a></li>
                </ul>
                
                <div class="mt-auto pt-3 border-top border-secondary">
                    <a href="<?= BASEURL ?>/public/" class="btn btn-sm w-100 py-2 fw-bold shadow-sm" style="background: rgba(255,255,255,0.1); color: var(--light);">👋 <?= translate('exit_main_menu') ?></a>
                </div>
            </div>
            
            <!-- MAIN CONTENT -->
            <div class="col-md-9 col-lg-10 p-4 main-content">
                
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <h2 class="fw-bold m-0" style="color: var(--primary);">📂 <?= translate('category_mgmt') ?></h2>
                    <div class="d-flex gap-2 flex-wrap">
                        <?php
                            $totalCats = count($data['categories'] ?? []);
                            $activeCats = 0;
                            $hiddenCats = 0;
                            foreach(($data['categories'] ?? []) as $cat) {
                                if($cat['is_active'] == 1) $activeCats++;
                                else $hiddenCats++;
                            }
                        ?>
                        <span class="stat-pill" style="color: var(--primary);">
                            <span class="stat-dot" style="background: var(--primary);"></span>
                            <?= $totalCats ?> <?= translate('total') ?? 'Total' ?>
                        </span>
                        <span class="stat-pill" style="color: var(--accent);">
                            <span class="stat-dot" style="background: var(--accent);"></span>
                            <?= $activeCats ?> <?= translate('active_label') ?? 'Active' ?>
                        </span>
                        <?php if($hiddenCats > 0): ?>
                        <span class="stat-pill" style="color: #dc3545;">
                            <span class="stat-dot" style="background: #dc3545;"></span>
                            <?= $hiddenCats ?> <?= translate('hidden') ?? 'Hidden' ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Add Category Card -->
                <div class="add-card p-4 mb-4">
                    <form action="<?= BASEURL ?>/public/admin/addCategory" method="POST" class="d-flex gap-3 align-items-center flex-wrap">
                        <div class="flex-grow-1">
                            <label class="form-label small fw-bold text-muted mb-1"><?= translate('add_new_category') ?></label>
                            <input type="text" name="name" class="form-control form-control-lg border-0 bg-light rounded-3 fw-semibold" placeholder="<?= translate('cat_placeholder') ?>" required style="font-size: 1rem;">
                        </div>
                        <button type="submit" class="btn px-4 py-3 fw-bold shadow-sm text-white rounded-3 d-flex align-items-center gap-2" style="background: linear-gradient(135deg, var(--secondary), var(--accent)); border: none; white-space: nowrap;">
                            <span style="font-size: 1.2rem;">+</span> <?= translate('add') ?>
                        </button>
                    </form>
                </div>

                <!-- Category Cards Grid -->
                <?php if(empty($data['categories'])): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📂</div>
                        <h4 class="fw-bold" style="color: var(--primary);"><?= translate('no_categories') ?></h4>
                        <p class="text-muted"><?= translate('add_first_cat') ?? 'Add your first category to get started!' ?></p>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php 
                        $catIcons = ['🍕', '🍔', '🍣', '🥤', '🍰', '🥗', '🍜', '☕', '🧁', '🍩', '🥪', '🌮', '🍱', '🥘', '🍝'];
                        $catColors = [
                            ['bg' => 'rgba(78,141,156,0.12)', 'text' => 'var(--secondary)'],
                            ['bg' => 'rgba(133,199,154,0.12)', 'text' => 'var(--accent)'],
                            ['bg' => 'rgba(40,28,89,0.08)', 'text' => 'var(--primary)'],
                            ['bg' => 'rgba(237,247,189,0.5)', 'text' => '#8a8a2e'],
                        ];
                        $idx = 0;
                        foreach($data['categories'] as $c): 
                            $icon = $catIcons[$idx % count($catIcons)];
                            $color = $catColors[$idx % count($catColors)];
                            $idx++;
                        ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="cat-card p-4 <?= $c['is_active'] == 0 ? 'hidden-cat' : '' ?>" style="animation-delay: <?= $idx * 0.06 ?>s;">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="cat-icon" style="background: <?= $color['bg'] ?>; color: <?= $color['text'] ?>;">
                                        <?= $icon ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-0" style="color: var(--primary);">
                                            <?= translate(strtolower($c['name']), $c['name']) ?>
                                        </h5>
                                        <?php if($c['is_active'] == 0): ?>
                                            <span class="badge mt-1" style="background: rgba(220,53,69,0.1); color: #dc3545; font-size: 0.7rem;"><?= translate('hidden') ?></span>
                                        <?php else: ?>
                                            <span class="badge mt-1" style="background: rgba(133,199,154,0.15); color: var(--accent); font-size: 0.7rem;"><?= translate('active_label') ?? 'Active' ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="cat-actions">
                                    <button type="button" class="btn btn-edit btn-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal" 
                                            data-id="<?= $c['id'] ?>" 
                                            data-name="<?= translate(strtolower($c['name']), $c['name']) ?>">
                                        ✏️ <?= translate('edit') ?>
                                    </button>

                                    <a href="<?= BASEURL ?>/public/admin/deleteCategory/<?= $c['id'] ?>" class="btn btn-delete btn-sm" onclick="return confirm('<?= translate('confirm_delete_cat') ?>')">
                                        🗑️ <?= translate('delete') ?>
                                    </a>

                                    <?php if($c['is_active'] == 1): ?>
                                        <a href="<?= BASEURL ?>/public/admin/hideCategory/<?= $c['id'] ?>" class="btn btn-hide btn-sm" onclick="return confirm('<?= translate('confirm_hide_cat') ?>')">👁️ <?= translate('hide') ?></a>
                                    <?php else: ?>
                                        <a href="<?= BASEURL ?>/public/admin/restoreCategory/<?= $c['id'] ?>" class="btn btn-activate btn-sm">🔄 <?= translate('activate') ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= BASEURL ?>/public/admin/updateCategory" method="POST">
                <div class="modal-content border-0 rounded-4 shadow-lg">
                    <div class="modal-header border-0 py-4" style="background: linear-gradient(135deg, var(--primary), #1a1240); color: white; border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title fw-bold"><?= translate('edit_category') ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="id" id="edit-id">
                        <label class="form-label fw-semibold" style="color: var(--primary);"><?= translate('cat_name') ?></label>
                        <input type="text" name="name" id="edit-name" class="form-control form-control-lg bg-light border-0 rounded-3" required>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-light rounded-3 px-4 fw-semibold" data-bs-dismiss="modal"><?= translate('cancel') ?></button>
                        <button type="submit" class="btn text-white rounded-3 px-4 fw-semibold" style="background: linear-gradient(135deg, var(--secondary), var(--accent));"><?= translate('save_changes') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Populate edit modal with category data
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            
            editModal.querySelector('#edit-id').value = id;
            editModal.querySelector('#edit-name').value = name;
        });
    </script>
</body>
</html>