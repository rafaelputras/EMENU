<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $data['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container bg-white p-4 rounded shadow-sm w-50 mx-auto mt-4">
        <h2 class="fw-bold mb-4">✏️ <?= translate('edit_menu') ?? 'Edit Menu' ?>: <?= $data['menu']['name'] ?></h2>
        
        <form action="<?= BASEURL ?>/public/admin/updateMenu" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['menu']['id'] ?>">
            
            <div class="mb-3">
                <label class="form-label fw-bold"><?= translate('name') ?></label>
                <input type="text" name="name" class="form-control" value="<?= $data['menu']['name'] ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold"><?= translate('category') ?></label>
                <select name="category_id" class="form-select" required>
                    <?php foreach($data['categories'] as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $data['menu']['category_id'] ? 'selected' : '' ?>>
                            <?= translate(strtolower($c['name']), $c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold"><?= translate('price') ?> (₫)</label>
                <input type="number" name="price" class="form-control" value="<?= $data['menu']['price'] ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold"><?= translate('description') ?></label>
                <textarea name="description" class="form-control" rows="3"><?= $data['menu']['description'] ?></textarea>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold"><?= translate('image') ?> Saat Ini</label><br>
                <?php if($data['menu']['image']): ?>
                    <img src="<?= BASEURL ?>/public/assets/images/<?= $data['menu']['image'] ?>" class="rounded mb-2 shadow-sm" width="100" height="100" style="object-fit: cover;"><br>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Pilih Grup Varian Terhubung <span class="text-muted fw-normal">(Opsional)</span></label>
                <div class="border rounded p-3 bg-light">
                    <?php if(!empty($data['variants'])): ?>
                        <div class="row">
                            <?php foreach($data['variants'] as $v): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input border-secondary" type="checkbox" name="variant_ids[]" value="<?= $v['id'] ?>" id="var_<?= $v['id'] ?>"
                                            <?= in_array($v['id'], $data['selected_variants']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="var_<?= $v['id'] ?>">
                                            <?= translate(strtolower($v['name']), $v['name']) ?>
                                            <small class="text-muted d-block" style="font-size: 11px;">
                                                (<?= $v['type'] == 'single' ? 'Pilih Satu' : 'Bisa Banyak' ?>)
                                            </small>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <em class="text-muted small">Belum ada grup varian. Buat dulu di menu Master Varian.</em>
                    <?php endif; ?>
                </div>
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
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm w-100 py-2"><?= translate('save') ?> Menu</button>
                <a href="<?= BASEURL ?>/public/admin/menus" class="btn btn-secondary px-4 py-2"><?= translate('cancel') ?></a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>