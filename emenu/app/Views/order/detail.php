<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #281C59; --secondary: #4E8D9C; --accent: #85C79A; --light: #EDF7BD; }
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #f0f4f8 0%, #e8f5e9 100%); min-height: 100vh; }
        .detail-container { max-width: 600px; margin: auto; }
        .btn-back { background: white; color: var(--primary); border: 2px solid var(--primary); }
        .btn-back:hover { background: var(--primary); color: white; }
        .btn-add { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border: none; border-radius: 14px; }
        .btn-add:hover { box-shadow: 0 8px 20px rgba(40,28,89,0.3); color: white; }
        .rec-card { border: none; border-radius: 14px; transition: all 0.3s; }
        .rec-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(40,28,89,0.1); }
    </style>
</head>
<body class="py-4 px-3">
    <div class="detail-container">
        <a href="<?= BASEURL ?>/public/order" class="btn btn-back rounded-pill px-4 py-2 fw-bold mb-4 shadow-sm">← <?= translate('back') ?></a>
        
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4">
                <h2 class="fw-bold mb-2" style="color: var(--primary);"><?= translate(strtolower($data['menu']['name']), $data['menu']['name']) ?></h2>
                <h4 class="fw-bold mb-3" style="color: var(--secondary);"><?= format_currency($data['menu']['price']) ?></h4>
                <p class="text-muted"><?= translate(strtolower($data['menu']['description']), $data['menu']['description']) ?></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form action="<?= BASEURL ?>/public/order/addToCart" method="POST">
                    <input type="hidden" name="menu_id" value="<?= $data['menu']['id'] ?>">
                    <input type="hidden" name="name" value="<?= translate(strtolower($data['menu']['name']), $data['menu']['name']) ?>">
                    <input type="hidden" name="price" value="<?= $data['menu']['price'] ?>">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <label class="fw-semibold" style="color: var(--primary);"><?= translate('total_order') ?>:</label>
                        <input type="number" name="qty" value="1" min="1" class="form-control bg-light border-0 rounded-3 text-center fw-bold" style="width: 80px;">
                    </div>
                    <button type="submit" class="btn btn-add w-100 py-3 fw-bold fs-5 shadow-sm"><?= translate('add_to_order') ?></button>
                </form>
            </div>
        </div>
    
        <?php if(!empty($data['recommendations'])): ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: var(--primary);">✨ <?= translate('add_on_options') ?></h5>
                <div class="d-flex flex-column gap-2">
                    <?php foreach($data['recommendations'] as $rek) : ?>
                        <div class="card rec-card bg-light p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold" style="color: var(--primary);"><?= translate(strtolower($rek['name']), $rek['name']) ?></span>
                                    <span class="text-muted small ms-2"><?= format_currency($rek['price']) ?></span>
                                </div>
                                <a href="<?= BASEURL ?>/public/order/detail/<?= $rek['id'] ?>" class="btn btn-sm fw-bold rounded-pill px-3" style="background: var(--light); color: var(--primary);">View</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>