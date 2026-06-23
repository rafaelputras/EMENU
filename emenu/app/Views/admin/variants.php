<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #281C59; --secondary: #4E8D9C; --accent: #85C79A; --light: #EDF7BD; }
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #f0f4f8 0%, #e8f5e9 100%); min-height: 100vh; }
        .table-dark { background: var(--primary) !important; }
        .table-dark th { background: var(--primary) !important; border-color: rgba(255,255,255,0.1) !important; }
        .card { border: none; border-radius: 18px; }
    </style>
</head>
<body class="p-4">
    
    <div class="container bg-white p-4 rounded-4 shadow-sm" style="max-width: 900px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <span class="text-muted small"><?= translate('variant_group') ?></span>
                <h3 class="fw-bold m-0 d-flex align-items-center gap-2" style="color: var(--primary);">
                    📦 <?= htmlspecialchars($data['group']['name'] ?? '') ?>
                    <button class="btn btn-sm btn-outline-primary border-0 rounded-circle" data-bs-toggle="modal" data-bs-target="#modalEditGroup" title="Edit">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                </h3>
                <span class="badge mt-1" style="background: var(--secondary);"><?= translate('group_type') ?>: <?= strtoupper($data['group']['type'] ?? 'radio') ?></span>
            </div>
            <a href="<?= BASEURL ?>/public/admin/master_variants" class="btn fw-bold rounded-3" style="background: var(--light); color: var(--primary);">
                <i class="bi bi-arrow-left"></i> <?= translate('back') ?>
            </a>
        </div>

        <div class="card card-body mb-4 shadow-sm rounded-4" style="background: var(--light);">
            <h6 class="fw-bold mb-2" style="color: var(--primary);"><i class="bi bi-plus-circle-fill"></i> <?= translate('add_option') ?></h6>
            <form action="<?= BASEURL ?>/public/admin/saveVariantOption" method="POST" class="row g-2">
                <input type="hidden" name="group_id" value="<?= $data['group_id'] ?>">
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control bg-white border-0 rounded-3" placeholder="<?= translate('name') ?>" required>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0">₫</span>
                        <input type="number" name="extra_price" class="form-control bg-white border-0" placeholder="<?= translate('extra_price') ?>" value="0" min="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn w-100 fw-bold text-white rounded-3" style="background: var(--secondary);"><?= translate('add') ?></button>
                </div>
            </form>
        </div>

        <h6 class="fw-bold mb-2" style="color: var(--primary);"><i class="bi bi-list-stars"></i> <?= translate('variant_options') ?></h6>
        <div class="table-responsive">
            <table class="table table-hover table-striped border align-middle m-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 40%;"><?= translate('name') ?></th>
                        <th style="width: 25%;"><?= translate('extra_price') ?></th>
                        <th style="width: 15%; text-align: center;">Order</th>
                        <th style="width: 20%; text-align: center;"><?= translate('actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['options'])): ?>
                        <tr><td colspan="4" class="text-center py-3 text-muted"><?= translate('no_data') ?></td></tr>
                    <?php else: ?>
                        <?php foreach($data['options'] as $index => $o): ?>
                        <tr>
                            <td class="fw-bold" style="color: var(--primary);"><?= htmlspecialchars($o['name']) ?></td>
                            <td class="fw-bold" style="color: var(--accent);">+ <?= format_currency($o['extra_price']) ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?= BASEURL ?>/public/admin/moveVariantOption/<?= $o['id'] ?>/up/<?= $data['group_id'] ?>" class="btn btn-outline-secondary <?= $index === 0 ? 'disabled' : '' ?>"><i class="bi bi-arrow-up"></i></a>
                                    <a href="<?= BASEURL ?>/public/admin/moveVariantOption/<?= $o['id'] ?>/down/<?= $data['group_id'] ?>" class="btn btn-outline-secondary <?= $index === count($data['options'])-1 ? 'disabled' : '' ?>"><i class="bi bi-arrow-down"></i></a>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm text-white fw-bold me-1" style="background: var(--secondary);" onclick="openEditOptionModal('<?= $o['id'] ?>', '<?= htmlspecialchars($o['name']) ?>', '<?= (int)$o['extra_price'] ?>')"><i class="bi bi-pencil"></i></button>
                                <a href="<?= BASEURL ?>/public/admin/deleteVariantOption/<?= $o['id'] ?>/<?= $data['group_id'] ?>" class="btn btn-sm btn-danger fw-bold" onclick="return confirm('<?= translate('confirm_delete_cat') ?>');"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Group Modal -->
    <div class="modal fade" id="modalEditGroup" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= BASEURL ?>/public/admin/updateVariantGroup" method="POST" class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0 text-white" style="background: var(--primary); border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title fw-bold"><?= translate('edit') ?> <?= translate('variant_group') ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" value="<?= $data['group_id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: var(--primary);"><?= translate('group_name') ?></label>
                        <input type="text" name="name" class="form-control bg-light border-0 rounded-3" value="<?= htmlspecialchars($data['group']['name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: var(--primary);"><?= translate('group_type') ?></label>
                        <select name="type" class="form-select bg-light border-0 rounded-3">
                            <option value="radio" <?= ($data['group']['type'] ?? '') == 'radio' ? 'selected' : '' ?>><?= translate('choose_one') ?></option>
                            <option value="checkbox" <?= ($data['group']['type'] ?? '') == 'checkbox' ? 'selected' : '' ?>><?= translate('choose_many') ?></option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal"><?= translate('cancel') ?></button>
                    <button type="submit" class="btn text-white fw-bold rounded-3" style="background: var(--secondary);"><?= translate('save_changes') ?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Option Modal -->
    <div class="modal fade" id="modalEditOption" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= BASEURL ?>/public/admin/updateVariantOption" method="POST" class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0 text-white" style="background: var(--primary); border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title fw-bold"><?= translate('edit') ?> <?= translate('variant_options') ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="edit_option_id">
                    <input type="hidden" name="group_id" value="<?= $data['group_id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: var(--primary);"><?= translate('name') ?></label>
                        <input type="text" name="name" id="edit_option_name" class="form-control bg-light border-0 rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: var(--primary);"><?= translate('extra_price') ?> (₫)</label>
                        <input type="number" name="extra_price" id="edit_option_price" class="form-control bg-light border-0 rounded-3" min="0" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal"><?= translate('cancel') ?></button>
                    <button type="submit" class="btn text-white fw-bold rounded-3" style="background: var(--secondary);"><?= translate('save_changes') ?></button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-populate edit option modal
        function openEditOptionModal(id, name, price) {
            document.getElementById('edit_option_id').value = id;
            document.getElementById('edit_option_name').value = name;
            document.getElementById('edit_option_price').value = price;
            var myModal = new bootstrap.Modal(document.getElementById('modalEditOption'));
            myModal.show();
        }
    </script>
</body>
</html>