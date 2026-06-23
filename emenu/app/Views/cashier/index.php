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
        body { background-color: #f4f6f9; height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
        .pos-header { background: linear-gradient(135deg, var(--primary) 0%, #3d2d7a 100%); color: white; box-shadow: 0 4px 16px rgba(40,28,89,0.2); z-index: 10; }
        .pos-content { flex: 1; overflow: hidden; display: flex; }
        
        .menu-area { flex: 5; height: 100%; overflow-y: auto; padding: 20px; background: #f8f9fa; }
        .cart-area { flex: 3; height: 100%; display: flex; flex-direction: column; background: white; border-left: 1px solid #dee2e6; z-index: 5; }
        .payment-area { flex: 3; height: 100%; display: flex; flex-direction: column; background: white; border-left: 1px solid #dee2e6; z-index: 6; position: relative; }
        
        .cart-items { flex: 1; overflow-y: auto; padding: 20px; }
        .menu-card { border: none; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.05); transition: 0.3s; }
        .menu-card:hover { transform: translateY(-3px); box-shadow: 0 8px 16px rgba(40,28,89,0.1); }
        .menu-img { height: 120px; object-fit: cover; }
        
        .locked-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.9); z-index: 10; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(3px); transition: 0.3s; }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        
        .numpad-btn { font-size: 1.5rem; font-weight: bold; border-radius: 12px; transition: 0.1s; border: 1px solid #dee2e6; background: #fff; color: var(--primary); box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .numpad-btn:active { transform: scale(0.95); background: var(--light); }
        .numpad-btn.btn-danger { color: #fff; background: #dc3545; border-color: #dc3545; }
        
        .lang-switcher a { font-size: 1.2rem; text-decoration: none; transition: all 0.2s; }
        .lang-switcher .active-lang { background: rgba(255,255,255,0.25); border-radius: 8px; padding: 3px 6px; }

        @media (max-width: 992px) {
            .pos-content { flex-direction: column; overflow-y: auto; }
            .menu-area, .cart-area, .payment-area { flex: none; height: auto; min-height: 400px; }
            .cart-area, .payment-area { border-left: none; border-top: 1px solid #dee2e6; }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="pos-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0 fw-bold">🏪 <?= translate('title_pos') ?></h4>
        <div class="d-flex gap-2 align-items-center">
            <!-- Language Switcher -->
            <div class="lang-switcher d-flex gap-1 me-2">
                <a href="<?= BASEURL ?>/public/language/switch/id" class="<?= ($_SESSION['lang'] ?? 'id') == 'id' ? 'active-lang' : '' ?>">🇮🇩</a>
                <a href="<?= BASEURL ?>/public/language/switch/en" class="<?= ($_SESSION['lang'] ?? 'id') == 'en' ? 'active-lang' : '' ?>">🇬🇧</a>
                <a href="<?= BASEURL ?>/public/language/switch/vi" class="<?= ($_SESSION['lang'] ?? 'id') == 'vi' ? 'active-lang' : '' ?>">🇻🇳</a>
            </div>
            <button class="btn fw-bold text-dark shadow-sm px-4 rounded-pill" style="background: var(--light);" data-bs-toggle="modal" data-bs-target="#scanModal">
                <?= translate('scan_qr') ?>
            </button>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="pos-content">
        
        <!-- LEFT: MENU GRID -->
        <div class="menu-area">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                <?php foreach($data['menus'] as $menu): 
                    $imgSrc = !empty($menu['image']) ? BASEURL . '/public/assets/images/' . $menu['image'] : '';
                    $price = $menu['price']; 
                ?>
                    <div class="col">
                        <div class="card menu-card h-100" style="cursor: pointer;" onclick="clickMenu(<?= $menu['id'] ?>, '<?= addslashes($menu['name']) ?>', <?= $price ?>)">
                            <?php if($imgSrc): ?>
                                <img src="<?= $imgSrc ?>" class="menu-img">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center menu-img text-white" style="background: linear-gradient(135deg, var(--secondary), var(--accent));"><small class="fw-semibold"><?= translate('no_image') ?></small></div>
                            <?php endif; ?>
                            <div class="card-body p-2 text-center">
                                <h6 class="fw-bold mb-1 text-truncate" style="color: var(--primary);"><?= translate(strtolower($menu['name']), $menu['name']) ?></h6>
                                <p class="fw-bold mb-0 small" style="color: var(--secondary);"><?= format_currency($price) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- CENTER: ORDER DETAILS -->
        <div class="cart-area">
            <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center shadow-sm z-1">
                <h5 class="fw-bold m-0" style="color: var(--primary);"><?= translate('detail_order') ?></h5>
                <span class="badge fs-6" style="background: var(--light); color: var(--primary);" id="displayTable"><?= translate('table') ?>: -</span>
            </div>

            <div class="cart-items bg-light" id="orderItemsContainer">
                <div class="text-center text-muted mt-5 pt-5">
                    <h1 class="opacity-25 mb-3">🛒</h1>
                    <p><?= translate('scan_or_click') ?></p>
                </div>
            </div>

            <div class="p-4 bg-white border-top shadow-sm d-none" id="summaryPanel">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted fw-bold"><?= translate('subtotal') ?></span>
                    <span class="fw-bold" style="color: var(--primary);" id="displaySubtotal">0 ₫</span>
                </div>
                <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm border border-secondary mb-3">
                    <span class="fw-bold text-muted"><?= translate('total_bill') ?></span>
                    <span class="fw-extrabold fs-3" style="color: var(--secondary);" id="displayGrandTotal">0 ₫</span>
                </div>
            </div>
        </div>

        <!-- RIGHT: PAYMENT PANEL -->
        <div class="payment-area">
            <div class="locked-overlay" id="paymentOverlay">
                <div class="text-center text-muted">
                    <h2>🔒</h2>
                    <p class="fw-bold m-0"><?= translate('waiting_order') ?></p>
                </div>
            </div>

            <div class="p-3 border-bottom bg-white shadow-sm z-1 text-center">
                <h5 class="fw-bold m-0" style="color: var(--primary);"><?= translate('payment') ?></h5>
            </div>

            <form action="<?= BASEURL ?>/public/cashier/processPayment" method="POST" class="p-4 d-flex flex-column flex-grow-1" id="paymentForm">
                <input type="hidden" name="order_id" id="inputOrderId">
                <input type="hidden" name="final_total" id="inputFinalTotal" value="0">
                <input type="hidden" id="inputTotalAmount" value="0">

                <div class="mb-3 d-flex flex-column">
                    <label class="form-label fw-bold text-muted small mb-1"><?= translate('cash_received') ?></label>
                    <input type="number" name="cash_amount" id="inputCash" class="form-control form-control-lg fw-bold text-end border-0 border-bottom border-3 rounded-0 mb-1" style="font-size: 2rem; color: var(--accent); border-color: var(--accent) !important; background: #f8f9fa;" placeholder="0" required oninput="calculateChange()">
                    <span class="fw-bold small text-muted text-end" id="displayChange"><?= translate('change') ?>: 0 ₫</span>
                </div>
                
                <div class="d-flex gap-2 mb-3 mt-2">
                    <button type="button" class="btn fw-bold flex-fill py-2 shadow-sm" style="background: var(--accent); color: white;" onclick="setExactAmount()"><?= translate('exact_money') ?></button>
                    <button type="button" class="btn btn-outline-secondary fw-bold flex-fill bg-white py-2" onclick="setQuickCash(50000)">50k</button>
                    <button type="button" class="btn btn-outline-secondary fw-bold flex-fill bg-white py-2" onclick="setQuickCash(100000)">100k</button>
                </div>
                
                <div class="row g-2 mb-3 flex-grow-1 align-content-start">
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('1')">1</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('2')">2</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('3')">3</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('4')">4</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('5')">5</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('6')">6</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('7')">7</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('8')">8</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('9')">9</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn btn-danger" onclick="clearNumpad()">C</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('0')">0</button></div>
                    <div class="col-4"><button type="button" class="w-100 py-3 numpad-btn" onclick="appendNumpad('000')">000</button></div>
                </div>

                <button type="submit" class="btn w-100 py-4 fw-bold fs-4 shadow-lg mt-auto rounded-4 text-uppercase border-0 text-white" style="background: linear-gradient(135deg, var(--primary), var(--secondary));" id="btnConfirmPayment" disabled>
                    <?= translate('confirm_btn') ?>
                </button>
            </form>
        </div>
    </div>

    <!-- SCAN MODAL -->
    <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header text-white border-0 rounded-top-4" style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
                    <h5 class="modal-title fw-bold" id="scanModalLabel">📷 <?= translate('scan_qr_code') ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <form id="scanForm" onsubmit="searchOrder(event)">
                        <input type="text" id="orderNumberInput" class="form-control form-control-lg text-center fw-bold mb-3 bg-light border-0 rounded-3" placeholder="<?= translate('scan_placeholder') ?>" required autocomplete="off">
                        <button type="submit" class="btn w-100 fw-bold py-3 rounded-pill fs-5 text-white" style="background: linear-gradient(135deg, var(--primary), var(--secondary));" id="btnSearch"><?= translate('search_order') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const LANG = {
            table: '<?= translate('table') ?>',
            change: '<?= translate('change') ?>',
            insufficient: '<?= translate('insufficient') ?>',
            scan_or_click: '<?= translate('scan_or_click') ?>',
            searching: '<?= translate('searching') ?>',
            search_order: '<?= translate('search_order') ?>',
            system_error: '<?= translate('system_error') ?>'
        };

        const scanModal = new bootstrap.Modal(document.getElementById('scanModal'));
        document.getElementById('scanModal').addEventListener('shown.bs.modal', function () { document.getElementById('orderNumberInput').focus(); });

        function formatCurrency(angka) { return new Intl.NumberFormat('id-ID').format(angka) + ' ₫'; }

        let activeOrder = { id: null, table_number: 'Manual', total_amount: 0, items: {} };

        function searchOrder(e) {
            e.preventDefault();
            const orderNumber = document.getElementById('orderNumberInput').value.trim();
            const btnSearch = document.getElementById('btnSearch');
            btnSearch.disabled = true; btnSearch.innerHTML = LANG.searching;

            const formData = new FormData();
            formData.append('order_number', orderNumber);

            fetch('<?= BASEURL ?>/public/cashier/scanOrder', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    activeOrder.id = data.order.id;
                    activeOrder.table_number = data.order.table_number;
                    activeOrder.items = {};
                    
                    data.items.forEach(item => {
                        activeOrder.items[item.menu_id] = {
                            menu_id: item.menu_id,
                            menu_name: item.menu_name,
                            price_per_item: parseInt(item.price_per_item),
                            quantity: parseInt(item.quantity),
                            subtotal: parseInt(item.subtotal),
                            notes: item.notes || ''
                        };
                    });

                    document.getElementById('paymentOverlay').classList.add('d-none');
                    updateOrderUI();
                    scanModal.hide();
                    document.getElementById('orderNumberInput').value = ''; 
                } else { alert(data.message); }
            })
            .catch(error => { console.error('Error:', error); alert(LANG.system_error); })
            .finally(() => { btnSearch.disabled = false; btnSearch.innerHTML = LANG.search_order; });
        }

        function clickMenu(menuId, menuName, price) {
            document.getElementById('paymentOverlay').classList.add('d-none');

            if (!activeOrder.items[menuId]) {
                activeOrder.items[menuId] = {
                    menu_id: menuId,
                    menu_name: menuName,
                    price_per_item: parseInt(price),
                    quantity: 1,
                    subtotal: parseInt(price),
                    notes: ''
                };
            } else {
                activeOrder.items[menuId].quantity += 1;
                activeOrder.items[menuId].subtotal = activeOrder.items[menuId].quantity * activeOrder.items[menuId].price_per_item;
            }
            updateOrderUI();
        }

        function changeQty(menuId, delta) {
            if (activeOrder.items[menuId]) {
                activeOrder.items[menuId].quantity += delta;
                if (activeOrder.items[menuId].quantity <= 0) {
                    delete activeOrder.items[menuId];
                } else {
                    activeOrder.items[menuId].subtotal = activeOrder.items[menuId].quantity * activeOrder.items[menuId].price_per_item;
                }
                updateOrderUI();
            }
        }

        function updateOrderUI() {
            document.getElementById('displayTable').innerText = `${LANG.table}: ${activeOrder.table_number}`;
            let itemsContainer = document.getElementById('orderItemsContainer');
            
            if (Object.keys(activeOrder.items).length === 0) {
                itemsContainer.innerHTML = `<div class="text-center text-muted mt-5 pt-5"><h1 class="opacity-25 mb-3">🛒</h1><p>${LANG.scan_or_click}</p></div>`;
                itemsContainer.classList.replace('bg-white', 'bg-light');
                document.getElementById('summaryPanel').classList.add('d-none');
                document.getElementById('paymentOverlay').classList.remove('d-none');
                return;
            }

            let itemsHtml = '<ul class="list-unstyled m-0">';
            let grandTotal = 0;

            Object.values(activeOrder.items).forEach(item => {
                grandTotal += item.subtotal;
                let notesHtml = item.notes ? `<div class="text-muted small fst-italic mt-1">"${item.notes}"</div>` : '';
                itemsHtml += `
                    <li class="mb-3 pb-3 border-bottom border-light">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold m-0" style="color: var(--primary);">${item.menu_name}</h6>
                                <div class="text-muted small mt-1 d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-xs btn-light border py-0 px-2 fw-bold" onclick="changeQty(${item.menu_id}, -1)">-</button>
                                    <span class="fw-bold" style="color: var(--primary);">${item.quantity}</span>
                                    <button type="button" class="btn btn-xs btn-light border py-0 px-2 fw-bold" onclick="changeQty(${item.menu_id}, 1)">+</button>
                                    <span>x ${formatCurrency(item.price_per_item)}</span>
                                </div>
                                ${notesHtml}
                            </div>
                            <div class="fw-bold" style="color: var(--primary);">${formatCurrency(item.subtotal)}</div>
                        </div>
                    </li>
                `;
            });
            itemsHtml += '</ul>';
            
            itemsContainer.innerHTML = itemsHtml;
            itemsContainer.classList.replace('bg-light', 'bg-white');

            document.getElementById('summaryPanel').classList.remove('d-none');
            document.getElementById('displaySubtotal').innerText = `${formatCurrency(grandTotal)}`;
            document.getElementById('displayGrandTotal').innerText = `${formatCurrency(grandTotal)}`;

            document.getElementById('inputOrderId').value = activeOrder.id || '';
            document.getElementById('inputTotalAmount').value = grandTotal;
            document.getElementById('inputFinalTotal').value = grandTotal;
            
            calculateChange();
        }

        const inputCash = document.getElementById('inputCash');
        function appendNumpad(numberStr) { inputCash.value = inputCash.value + numberStr; calculateChange(); }
        function clearNumpad() { inputCash.value = ''; calculateChange(); inputCash.focus(); }
        function setExactAmount() { inputCash.value = document.getElementById('inputTotalAmount').value; calculateChange(); }
        function setQuickCash(amount) { inputCash.value = amount; calculateChange(); }

        function calculateChange() {
            const totalAmount = parseInt(document.getElementById('inputTotalAmount').value) || 0;
            const cashReceived = parseInt(inputCash.value) || 0;
            const changeDisplay = document.getElementById('displayChange');
            const btnConfirm = document.getElementById('btnConfirmPayment');

            let change = cashReceived - totalAmount;

            if (change >= 0) {
                changeDisplay.innerText = `${LANG.change}: ${formatCurrency(change)}`;
                changeDisplay.classList.remove('text-danger');
                changeDisplay.classList.add('text-muted');
                btnConfirm.disabled = false; 
            } else {
                changeDisplay.innerText = `${LANG.change}: 0 ₫ (${LANG.insufficient} ${formatCurrency(Math.abs(change))})`;
                changeDisplay.className = 'fw-bold small text-danger text-end'; 
                changeDisplay.style.color = '';
                btnConfirm.disabled = true; 
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if (isset($_SESSION['swal_success'])): ?>
            Swal.fire({
                title: 'Berhasil!',
                text: '<?= $_SESSION['swal_success'] ?>',
                icon: 'success',
                confirmButtonColor: 'var(--accent)',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['swal_success']); ?>
        <?php endif; ?>
    </script>
</body>
</html>