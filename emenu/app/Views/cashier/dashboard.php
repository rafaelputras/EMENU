<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $data['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #281C59; --secondary: #4E8D9C; --accent: #85C79A; --light: #EDF7BD; }
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #f0f4f8 0%, #e8f5e9 100%); min-height: 100vh; }
        .card { border: none; border-radius: 18px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0" style="color: var(--primary);">🖥️ <?= translate('pos_dashboard') ?></h2>
                <p class="text-muted m-0 small"><?= translate('pos_subtitle') ?></p>
            </div>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if(empty($data['orders'])): ?>
                <div class="col-12">
                    <div class="alert text-center shadow-sm py-4 border-0 rounded-4" style="background: rgba(133,199,154,0.15);">
                        <h5 class="m-0" style="color: var(--accent);">🎉 <?= translate('no_queue') ?></h5>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach($data['orders'] as $order) : ?>
                    <div class="col">
                        <div class="card shadow-sm h-100 border-top border-4 <?= $order['order_status'] == 'pending' ? '' : '' ?>" style="border-top-color: <?= $order['order_status'] == 'pending' ? '#dc3545' : 'var(--secondary)' ?> !important;">
                            <div class="card-body d-flex flex-column justify-content-between">
                                
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h4 class="card-title fw-bold m-0" style="color: var(--primary);"><?= translate('table') ?> <?= $order['table_number'] ?></h4>
                                        <span class="badge px-2 py-2 <?= $order['order_status'] == 'pending' ? 'bg-danger' : '' ?>" style="<?= $order['order_status'] != 'pending' ? 'background: var(--secondary);' : '' ?>">
                                            <?= strtoupper($order['order_status']) ?>
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-3">ID: <?= $order['order_number'] ?></p>
                                    
                                    <div class="p-3 rounded-3 mb-3" style="background: var(--light);">
                                        <p class="m-0 text-muted small mb-1"><?= translate('total_bill_label') ?></p>
                                        <h3 class="fw-bold m-0" style="color: var(--accent);"><?= format_currency($order['total_amount']) ?></h3>
                                    </div>
                                    
                                    <p class="m-0 text-secondary small mb-3">
                                        <?= translate('payment_status') ?> 
                                        <span class="fw-bold <?= $order['payment_status'] == 'paid' ? '' : 'text-danger' ?>" style="<?= $order['payment_status'] == 'paid' ? 'color: var(--accent);' : '' ?>">
                                            <?= strtoupper($order['payment_status']) ?>
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <?php if($order['order_status'] == 'pending') : ?>
                                        <button class="btn btn-danger w-100 py-2 fw-bold shadow-sm text-white rounded-3" 
                                                onclick="location.href='<?= BASEURL ?>/public/pos/fireOrder/<?= $order['id'] ?>'">
                                            <?= translate('fire_kitchen') ?>
                                        </button>
                                    <?php elseif($order['order_status'] == 'cooking') : ?>
                                        <button class="btn w-100 py-2 fw-bold shadow-sm rounded-3 text-white" style="background: var(--accent);"
                                                onclick="location.href='<?= BASEURL ?>/public/pos/payOrder/<?= $order['id'] ?>'">
                                            <?= translate('process_payment') ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>