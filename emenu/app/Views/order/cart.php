<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? translate('checkout_title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #281C59; --secondary: #4E8D9C; --accent: #85C79A; --light: #EDF7BD; }
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #f0f4f8 0%, #e8f5e9 100%); min-height: 100vh; }
        .checkout-container { max-width: 600px; margin: auto; }
        .payment-tab { cursor: pointer; border: 2px solid #e9ecef; border-radius: 14px; text-align: center; padding: 15px; font-weight: bold; color: #6c757d; transition: 0.3s; }
        .payment-tab.active { border-color: var(--secondary); color: var(--secondary); background-color: rgba(78,141,156,0.08); }
        .ewallet-option { cursor: pointer; border: 1px solid #dee2e6; border-radius: 14px; transition: 0.2s; }
        .ewallet-option:hover { background-color: #f8f9fa; }
        .ewallet-option input:checked + .ewallet-label { font-weight: 800; color: var(--secondary); }
        .btn-checkout { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border-radius: 14px; border: none; transition: all 0.3s; }
        .btn-checkout:hover { box-shadow: 0 8px 20px rgba(40,28,89,0.3); color: white; transform: translateY(-1px); }
        .card { border: none; border-radius: 18px; }
        .back-btn { background: white; color: var(--primary); border: 2px solid var(--primary); }
        .back-btn:hover { background: var(--primary); color: white; }
    </style>
</head>
<body>

    <div class="container checkout-container my-4 pb-5">
        <div class="d-flex align-items-center mb-4">
            <a href="<?= BASEURL ?>/public/order" class="btn back-btn rounded-circle shadow-sm fw-bold fs-5 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">←</a>
            <h4 class="fw-bold m-0 ms-3" style="color: var(--primary);"><?= translate('checkout_title') ?></h4>
        </div>

        <?php if(empty($_SESSION['cart'])): ?>
            <div class="card shadow-sm text-center py-5 rounded-4">
                <div class="card-body">
                    <h1 class="display-1 text-muted mb-3">🍽️</h1>
                    <h4 class="fw-bold" style="color: var(--primary);"><?= translate('cart_empty') ?></h4>
                    <p class="text-muted"><?= translate('cart_empty_msg') ?></p>
                    <a href="<?= BASEURL ?>/public/order" class="btn btn-checkout px-4 py-2 fw-bold mt-2"><?= translate('view_menu') ?></a>
                </div>
            </div>
        <?php else: ?>
            
            <form action="<?= BASEURL ?>/public/order/checkout" method="POST" id="checkoutForm">
                
                <div class="card shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary);"><?= translate('order_details') ?></h6>
                        <ul class="list-unstyled m-0">
                            <?php 
                            $grandTotal = 0;
                            foreach($_SESSION['cart'] as $item): 
                                $grandTotal += $item['subtotal'];
                            ?>
                                <li class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <div class="fw-bold" style="color: var(--primary);"><?= translate(strtolower($item['name']), $item['name']) ?></div>
                                        <?php if(!empty($item['notes'])): ?>
                                            <small class="text-muted d-block lh-sm mt-1" style="font-size: 12px; white-space: pre-wrap;"><?= $item['notes'] ?></small>
                                        <?php endif; ?>
                                        <div class="text-muted small mt-1"><?= format_currency($item['price']) ?> x <?= $item['qty'] ?></div>
                                    </div>
                                    <div class="fw-bold" style="color: var(--primary);">
                                        <?= format_currency($item['subtotal']) ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="fw-bold text-muted"><?= translate('total_payment') ?></span>
                            <span class="fs-4 fw-extrabold" style="color: var(--secondary);" id="displayGrandTotal"><?= format_currency($grandTotal) ?></span>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary);"><?= translate('customer_info') ?></h6>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold mb-1"><?= translate('full_name') ?> *</label>
                            <input type="text" name="customer_name" class="form-control bg-light border-0 rounded-3" required placeholder="<?= translate('enter_name') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold mb-1"><?= translate('phone_number') ?></label>
                            <input type="tel" name="phone" class="form-control bg-light border-0 rounded-3" placeholder="0812xxxxxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold mb-1"><?= translate('send_receipt') ?></label>
                            <input type="email" name="email" class="form-control bg-light border-0 rounded-3" placeholder="email@example.com">
                        </div>
                        <div>
                            <label class="form-label text-muted small fw-semibold mb-1"><?= translate('table_number') ?> *</label>
                            <input type="text" class="form-control bg-light border-0 rounded-3 fw-bold" style="color: var(--primary);" value="<?= $_SESSION['table_number'] ?? 'Testing' ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary);"><?= translate('payment_method') ?></h6>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="payment-tab active" id="tabCash" onclick="selectPayment('cash')">
                                    💵 <?= translate('cash') ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-tab" id="tabOnline" onclick="selectPayment('online')">
                                    📱 <?= translate('online_payment') ?>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="payment_method" id="paymentMethodInput" value="cash">

                        <div id="cashArea" class="text-center py-3 rounded-3 mt-3 border" style="background: var(--light);">
                            <p class="m-0 small fw-semibold" style="color: var(--primary);">
                                ℹ️ <?= translate('cash_info') ?>
                            </p>
                        </div>

                        <div id="onlineArea" class="d-none mt-3">
                            <p class="fw-bold mb-2 small text-muted"><?= translate('complete_payment') ?></p>
                            <div class="d-flex flex-column gap-2">
                                <?php 
                                    $ewallets = ['DANA', 'LinkAja', 'OVO', 'ShopeePay', 'GoPay', 'QRIS'];
                                    foreach($ewallets as $idx => $wallet):
                                ?>
                                <label class="ewallet-option d-flex align-items-center p-3 mb-0">
                                    <div class="text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width:35px; height:35px; font-size:12px; background: var(--secondary);">
                                        <?= substr($wallet, 0, 1) ?>
                                    </div>
                                    <input class="form-check-input m-0 me-2" type="radio" name="ewallet_provider" value="<?= $wallet ?>" <?= $idx == 0 ? 'checked' : '' ?>>
                                    <span class="ewallet-label m-0"><?= $wallet ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn btn-checkout w-100 py-3 fw-bold fs-5 shadow-sm" id="btnSubmit">
                    <?= translate('pay_at_cashier') ?> - <?= format_currency($grandTotal) ?>
                </button>
            </form>

        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const grandTotalText = document.getElementById('displayGrandTotal')?.innerText || '';
        const payAtCashier = '<?= translate('pay_at_cashier') ?>';
        const payNow = '<?= translate('pay_now') ?>';

        function selectPayment(type) {
            document.getElementById('tabCash').classList.remove('active');
            document.getElementById('tabOnline').classList.remove('active');
            
            const cashArea = document.getElementById('cashArea');
            const onlineArea = document.getElementById('onlineArea');
            const btnSubmit = document.getElementById('btnSubmit');
            const paymentInput = document.getElementById('paymentMethodInput');

            if (type === 'cash') {
                document.getElementById('tabCash').classList.add('active');
                cashArea.classList.remove('d-none');
                onlineArea.classList.add('d-none');
                paymentInput.value = 'cash';
                btnSubmit.innerText = `${payAtCashier} - ${grandTotalText}`;
            } else {
                document.getElementById('tabOnline').classList.add('active');
                cashArea.classList.add('d-none');
                onlineArea.classList.remove('d-none');
                paymentInput.value = 'online';
                btnSubmit.innerText = `${payNow} - ${grandTotalText}`;
            }
        }
    </script>
</body>
</html>