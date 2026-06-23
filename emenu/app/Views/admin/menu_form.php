<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $data['title'] ?? translate('add_new_menu') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container bg-white p-4 rounded shadow-sm w-50 mx-auto mt-4">
        <h2 class="fw-bold mb-4">🍔 <?= translate('add_new_menu') ?></h2>
        
        <form action="<?= BASEURL ?>/public/admin/saveMenu" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-bold"><?= translate('name') ?></label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Ayam Geprek" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold"><?= translate('category') ?></label>
                <select name="category_id" class="form-select" required>
                    <option value="" disabled selected>-- Pilih <?= translate('category') ?> --</option>
                    <?php foreach($data['categories'] as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= translate(strtolower($c['name']), $c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold"><?= translate('price') ?> (₫)</label>
                <input type="number" name="price" class="form-control" placeholder="Contoh: 15000" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold"><?= translate('description') ?></label>
                <textarea name="description" class="form-control" rows="3" placeholder="Contoh: Ayam dada tanpa tulang dengan sambal bawang..."></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold"><?= translate('variant_options') ?></label>
                <div class="border p-3 rounded bg-light">
                    <?php if(!empty($data['variants'])): ?>
                        <div class="row">
                            <?php foreach($data['variants'] as $v): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="variant_ids[]" value="<?= $v['id'] ?>" id="variant_<?= $v['id'] ?>">
                                        <label class="form-check-label" for="variant_<?= $v['id'] ?>">
                                            <?= translate(strtolower($v['name']), $v['name']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <small class="text-danger"><?= translate('no_data') ?></small>
                    <?php endif; ?>
                </div>
                <small class="text-muted"></small>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold"><?= translate('image') ?></label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button class="btn btn-warning text-dark w-100 mb-3 text-start fw-bold shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#promoCollapse" aria-expanded="false" aria-controls="promoCollapse">
                🎯 <?= translate('promo') ?> ⬇️
            </button>

            <div class="collapse <?= (!empty($data['menu']['promo_price']) && $data['menu']['promo_price'] > 0) ? 'show' : '' ?>" id="promoCollapse">
                <div class="card mb-4 border-warning shadow-sm">
                    <div class="card-body row g-3 bg-light">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= translate('promo_price') ?> (₫)</label>
                            <input type="number" name="promo_price" class="form-control" placeholder="Misal: 15000" value="<?= $data['menu']['promo_price'] ?? '0' ?>" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= translate('promo_quota') ?></label>
                            <input type="number" name="promo_quota" class="form-control" placeholder="Misal: 50" value="<?= $data['menu']['promo_quota'] ?? '0' ?>" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= translate('promo_start') ?></label>
                            <input type="datetime-local" name="promo_start" class="form-control" value="<?= !empty($data['menu']['promo_start']) ? date('Y-m-d\TH:i', strtotime($data['menu']['promo_start'])) : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= translate('promo_end') ?></label>
                            <input type="datetime-local" name="promo_end" class="form-control" value="<?= !empty($data['menu']['promo_end']) ? date('Y-m-d\TH:i', strtotime($data['menu']['promo_end'])) : '' ?>">
                        </div>
                        <div class="col-12 text-muted small mt-2">
                            <em></em>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm"><?= translate('save') ?></button>
                <a href="<?= BASEURL ?>/public/admin/menus" class="btn btn-secondary px-4 fw-bold"><?= translate('cancel') ?></a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 