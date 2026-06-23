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
        body { background: linear-gradient(135deg, #f0f4f8 0%, #e8f5e9 100%); }
        .receipt-card { 
            max-width: 450px; margin: auto; background: #fff; border-radius: 20px;
            box-shadow: 0 12px 40px rgba(40,28,89,0.1); overflow: hidden;
        }
        .receipt-divider { border-top: 2px dashed #dee2e6; margin: 20px 0; }
    </style>
</head>
<body class="py-5 px-3">

    <div class="receipt-card p-4 pb-5">
        
        <div class="text-center mb-4">
            <div class="d-inline-block text-white rounded-circle p-3 mb-3 shadow-sm" style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
                <h2 class="m-0 px-2">🍕</h2>
            </div>
            <h4 class="fw-bold m-0" style="color: var(--primary);"><?= translate('app_name') ?></h4>
            <p class="text-muted small"><?= translate('e_receipt') ?></p>
        </div>

        <?php if($data['order']['payment_status'] == 'paid'): ?>
            <div class="text-center p-3 rounded-4 mb-4 border" style="background: rgba(133,199,154,0.1); border-color: var(--accent) !important;">
                <h1 class="m-0 mb-2" style="color: var(--accent);">✅</h1>
                <p class="fw-bold mb-1" style="color: var(--accent);"><?= translate('payment_success') ?></p>
                <p class="mt-1 mb-0 fw-bold fs-5" style="color: var(--primary);"><?= $data['order']['order_number'] ?></p>
            </div>
        <?php else: ?>
            <div class="text-center p-3 rounded-4 mb-4 border border-warning" style="background: rgba(237,247,189,0.3);">
                <h1 class="text-warning m-0 mb-2">⏳</h1>
                <p class="fw-bold text-warning-emphasis mb-1"><?= translate('unpaid') ?></p>
                <p class="mt-1 mb-3 fw-bold fs-5" style="color: var(--primary);"><?= $data['order']['order_number'] ?></p>
                
                <div class="bg-white p-2 rounded-4 shadow-sm border d-inline-block mb-2">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=<?= $data['order']['order_number'] ?>" alt="QR Code">
                </div>
                <p class="small text-muted m-0 fw-semibold"><?= translate('show_qr_cashier') ?></p>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted"><?= translate('name') ?></span>
            <span class="fw-bold" style="color: var(--primary);"><?= $data['order']['customer_name'] ?></span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted"><?= translate('table') ?></span>
            <span class="fw-bold" style="color: var(--primary);"><?= $data['order']['table_number'] ?></span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted"><?= translate('date') ?></span>
            <span class="fw-bold small" style="color: var(--primary);"><?= date('d M Y, H:i', strtotime($data['order']['created_at'])) ?></span>
        </div>

        <div class="receipt-divider"></div>

        <h6 class="fw-bold mb-3" style="color: var(--primary);"><?= translate('order_details') ?>:</h6>
        <?php foreach($data['items'] as $item): ?>
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="fw-semibold" style="color: var(--primary);"><?= translate(strtolower($item['menu_name']), $item['menu_name']) ?></span>
                    <div class="text-muted small lh-1 mt-1">
                        <?= $item['quantity'] ?> x <?= format_currency($item['price_per_item']) ?>
                    </div>
                    <?php if(!empty($item['notes'])): ?>
                        <div class="text-muted mt-1 fst-italic" style="font-size: 11px; white-space: pre-wrap;"><?= $item['notes'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="fw-bold" style="color: var(--primary);"><?= format_currency($item['subtotal']) ?></div>
            </div>
        <?php endforeach; ?>

        <div class="receipt-divider"></div>

        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-6" style="color: var(--primary);"><?= translate('total') ?></span>
            <span class="fw-extrabold fs-4" style="color: var(--secondary);"><?= format_currency($data['order']['total_amount']) ?></span>
        </div>

        <div class="mt-5 text-center">
            <a href="<?= BASEURL ?>/public/order" class="btn border rounded-pill w-100 fw-bold py-3 shadow-sm" style="background: var(--light); color: var(--primary);">
                <?= translate('order_again') ?>
            </a>
        </div>
    </div>

</body>
</html>