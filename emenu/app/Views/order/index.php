<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#281C59">
    <link rel="manifest" href="<?= BASEURL ?>/public/manifest.json">
    <link rel="apple-touch-icon" href="<?= BASEURL ?>/public/assets/images/icon-192.png">
    
    <title><?= translate('order_title') ?? 'E-Menu' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #281C59;
            --secondary: #4E8D9C;
            --accent: #85C79A;
            --light: #EDF7BD;
            --primary-rgb: 40, 28, 89;
            --secondary-rgb: 78, 141, 156;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #f0f4f8 0%, #e8f5e9 100%); min-height: 100vh; }
        
        .category-scroll { display: flex; overflow-x: auto; white-space: nowrap; padding-bottom: 10px; -ms-overflow-style: none; scrollbar-width: none; }
        .category-scroll::-webkit-scrollbar { display: none; }
        
        .menu-card { transition: transform 0.3s cubic-bezier(.4,0,.2,1), box-shadow 0.3s; border: none; border-radius: 18px; overflow: hidden; background: #fff; }
        .menu-card:hover { transform: translateY(-6px); box-shadow: 0 16px 32px rgba(var(--primary-rgb),0.12) !important; }
        .menu-img { height: 180px; object-fit: cover; }
        
        .header-bar { background: linear-gradient(135deg, var(--primary) 0%, #3d2d7a 100%); border-radius: 20px; }
        
        .btn-category { border: 2px solid var(--secondary); color: var(--secondary); background: white; font-weight: 600; transition: all 0.3s; }
        .btn-category:hover, .btn-category.active { background: var(--secondary); color: white; border-color: var(--secondary); box-shadow: 0 4px 12px rgba(var(--secondary-rgb),0.3); }
        
        .btn-circle-addon { width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 50%; transition: all 0.2s; }
        .btn-minus-addon { border: 2px solid #ccc; color: #999; background: white; }
        .btn-minus-addon:hover { border-color: var(--secondary); color: var(--secondary); }
        .btn-plus-addon { border: 2px solid #accent; color: var(--accent); background: white; }
        .btn-plus-addon:hover { background: var(--accent); color: white; }
        
        .btn-add-cart { background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%); color: white; border: none; border-radius: 12px; font-weight: 700; transition: all 0.3s; }
        .btn-add-cart:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(var(--secondary-rgb),0.35); color: white; }
        
        .btn-submit-order { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border-radius: 14px; transition: all 0.3s; border: none; }
        .btn-submit-order:hover { box-shadow: 0 8px 20px rgba(var(--primary-rgb),0.35); color: white; transform: translateY(-1px); }
        
        .btn-promo { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .btn-promo:hover { box-shadow: 0 6px 16px rgba(231,76,60,0.35); }
        
        .promo-badge { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; font-weight: 700; }
        
        .cart-btn { background: linear-gradient(135deg, var(--accent) 0%, #6db87f 100%); color: white; border: none; font-weight: 700; transition: all 0.3s; }
        .cart-btn:hover { box-shadow: 0 6px 16px rgba(133,199,154,0.4); color: white; transform: translateY(-1px); }
        
        .lang-switcher { background: rgba(255,255,255,0.1); padding: 4px; border-radius: 12px; white-space: nowrap; }
        .lang-switcher a { font-size: 0.95rem; font-weight: 700; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; padding: 6px 10px; border-radius: 8px; }
        .lang-switcher a:hover { color: white; background: rgba(255,255,255,0.1); transform: none; }
        .lang-switcher .active-lang { background: white !important; color: var(--primary) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        
        .price-tag { color: var(--secondary); font-weight: 800; }
        .category-badge { background: var(--light); color: var(--primary); border: 1px solid rgba(var(--primary-rgb),0.15); }

        @media (max-width: 576px) {
            .header-bar { border-radius: 0 0 20px 20px; margin: 0 -12px; padding: 16px !important; }
            .menu-img { height: 140px; }
            .container { padding-left: 12px; padding-right: 12px; }
        }
    </style>
</head>
<body>

    <div id="fullscreenOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-black align-items-center justify-content-center" style="z-index: 1060;">
        <button class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3 fw-bold" onclick="toggleFullscreen(false)" style="width: 40px; height: 40px; z-index: 1061;">✕</button>
        <img id="fullscreenImage" src="" class="w-100 h-auto" style="max-height: 100vh; object-fit: contain;">
    </div>

    <div class="container my-3 my-md-4">
        <!-- HEADER WITH LANGUAGE SWITCHER -->
        <div class="header-bar d-flex justify-content-between align-items-center p-3 p-md-4 shadow-lg text-white">
            <div>
                <h1 class="fw-bold m-0" style="font-size: clamp(1.3rem, 4vw, 1.8rem);">🍕 <?= translate('app_name') ?></h1>
                <p class="m-0 small opacity-75"><?= translate('order_subtitle') ?></p>
            </div>
            <div class="d-flex align-items-center gap-2 gap-md-3">
                <!-- Install PWA Button -->
                <button id="btnInstallPwa" class="btn btn-sm text-white fw-bold d-none shadow-sm" style="background: rgba(255,255,255,0.2); border-radius: 12px; padding: 6px 12px;">
                    ⬇️ Install App
                </button>
                
                <!-- Language Switcher -->
                <div class="lang-switcher d-flex gap-1">
                    <a href="<?= BASEURL ?>/public/language/switch/id" class="<?= ($_SESSION['lang'] ?? 'id') == 'id' ? 'active-lang' : '' ?>" title="Bahasa Indonesia">ID</a>
                    <a href="<?= BASEURL ?>/public/language/switch/en" class="<?= ($_SESSION['lang'] ?? 'id') == 'en' ? 'active-lang' : '' ?>" title="English">EN</a>
                    <a href="<?= BASEURL ?>/public/language/switch/vi" class="<?= ($_SESSION['lang'] ?? 'id') == 'vi' ? 'active-lang' : '' ?>" title="Tiếng Việt">VN</a>
                </div>
                <a href="<?= BASEURL ?>/public/order/cart" class="btn cart-btn px-3 px-md-4 py-2 position-relative rounded-pill shadow-sm" id="cart-btn">
                    🛒 <?= translate('cart') ?>
                    <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white <?= (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) ? '' : 'd-none' ?>">
                        <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                    </span>
                </a>
            </div>
        </div>

        <!-- CATEGORY FILTER -->
        <div class="my-4">
            <h5 class="fw-bold mb-3" style="color: var(--primary);"><?= translate('select_category') ?></h5>
            <div class="category-scroll gap-2">
                <button class="btn btn-category filter-btn px-4 py-2 rounded-pill active" data-filter="all"><?= translate('all_menu') ?></button>
                <?php if(isset($data['categories'])): ?>
                    <?php foreach($data['categories'] as $cat) : 
                        if(isset($cat['is_active']) && $cat['is_active'] == 0) continue; 
                    ?>
                        <button class="btn btn-category filter-btn px-4 py-2 rounded-pill" data-filter="<?= urlencode($cat['name']) ?>">
                            <?= translate(strtolower($cat['name']), $cat['name']) ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- MENU GRID -->
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4" id="menu-container">
            <?php foreach($data['menus'] as $menu) : 
                $menuFilter = !empty($menu['category_name']) ? $menu['category_name'] : 'Other';
                
                // PROMO LOGIC
                $isPromoActive = false;
                $finalPrice = $menu['price']; 
                if (!empty($menu['promo_price']) && $menu['promo_price'] > 0) {
                    date_default_timezone_set('Asia/Jakarta');
                    $now = date('Y-m-d H:i:s');
                    $validStart = empty($menu['promo_start']) || $menu['promo_start'] <= $now;
                    $validEnd = empty($menu['promo_end']) || $menu['promo_end'] >= $now;
                    $validQuota = (!isset($menu['promo_quota']) || $menu['promo_quota'] > 0 || $menu['promo_quota'] == null); 

                    if ($validStart && $validEnd && $validQuota) {
                        $isPromoActive = true;
                        $finalPrice = $menu['promo_price'];
                    }
                }

                $imgSrc = !empty($menu['image']) ? BASEURL . '/public/assets/images/' . $menu['image'] : '';
                $desc = !empty($menu['description']) ? translate(strtolower($menu['description']), $menu['description']) : translate('default_desc');
                $translatedName = translate(strtolower($menu['name']), $menu['name']);
                
                $variantsJson = htmlspecialchars(json_encode($menu['variants_grouped'] ?? []), ENT_QUOTES, 'UTF-8');
                
                // Card Category
                $menuFilter = !empty($menu['category_name']) ? translate(strtolower($menu['category_name']), $menu['category_name']) : 'Other';
                
                echo '<div class="col menu-item-card" data-category="'. urlencode($menuFilter) .'">';
                ?>
                    <div class="card h-100 menu-card shadow-sm position-relative">
                        
                        <?php if($isPromoActive): ?>
                            <div class="position-absolute top-0 end-0 mt-3 promo-badge px-3 py-1 rounded-start shadow-sm" style="z-index: 10;">🔥 <?= translate('promo') ?></div>
                        <?php endif; ?>

                        <?php if($imgSrc) : ?>
                            <img src="<?= $imgSrc ?>" class="card-img-top menu-img" alt="<?= $translatedName ?>">
                        <?php else : ?>
                            <div class="d-flex align-items-center justify-content-center menu-img text-center" style="background: linear-gradient(135deg, var(--secondary), var(--accent));"><small class="text-white fw-semibold"><?= translate('no_image') ?></small></div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column justify-content-between p-3">
                            <div>
                                <span class="badge category-badge mb-2 rounded-pill px-2 py-1 small" style="font-size: 10px;">🏷️ <?= $menuFilter ?></span>
                                <h6 class="card-title fw-bold m-0 mb-1" style="color: var(--primary); font-size: 0.95rem;"><?= $translatedName ?></h6>
                                <p class="card-text text-muted small mb-2" style="font-size: 0.78rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 34px;">
                                    <?= $desc ?>
                                </p>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <span class="text-muted small"><?= translate('price') ?></span>
                                    <div class="text-end">
                                        <?php if($isPromoActive): ?>
                                            <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;"><?= format_currency($menu['price']) ?></small>
                                            <span class="fw-extrabold text-danger" style="font-size: 1.05rem;"><?= format_currency($finalPrice) ?></span>
                                        <?php else: ?>
                                            <span class="price-tag" style="font-size: 1.05rem;"><?= format_currency($finalPrice) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <button type="button" class="btn <?= $isPromoActive ? 'btn-promo' : 'btn-add-cart' ?> w-100 fw-bold rounded-3 py-2 shadow-sm" style="font-size: 0.85rem;"
        onclick="openOrderModal('<?= $menu['id'] ?>', '<?= htmlspecialchars(addslashes($translatedName)) ?>', '<?= $finalPrice ?>', '<?= $imgSrc ?>', '<?= htmlspecialchars(addslashes($desc)) ?>', '<?= $variantsJson ?>')">
    <?= translate('add_to_order') ?>
</button>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ORDER MODAL -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down m-0 m-sm-auto" style="max-width: 500px;">
            
            <form action="<?= BASEURL ?>/public/order/addToCart" method="POST" class="modal-content border-0 shadow-lg rounded-top-4 rounded-bottom-0 rounded-sm-4 ajax-cart-form" id="modalCartForm">
                
                <div class="position-relative w-100 flex-shrink-0" style="height: 250px; background: linear-gradient(135deg, var(--secondary), var(--accent));">
                    <img id="modalMenuImage" src="" class="w-100 h-100 object-fit-cover" alt="Image">
                    <button type="button" class="btn btn-light rounded-circle position-absolute top-0 start-0 m-3 shadow-sm fw-bold btn-circle-addon" data-bs-dismiss="modal" aria-label="Close">✕</button>
                    <button type="button" class="btn btn-light rounded-circle position-absolute bottom-0 end-0 m-3 shadow-sm fw-bold btn-circle-addon" onclick="toggleFullscreen(true)">⤢</button>
                </div>

                <div class="modal-body p-4 bg-white" style="overflow-y: auto;">
                    
                    <div class="mb-4">
                        <h4 class="fw-bold mb-1" style="color: var(--primary);" id="modalMenuName">Menu</h4>
                        <h5 class="fw-bold mb-2 price-tag" id="modalMenuPriceDisplay">0 ₫</h5>
                        <p class="text-muted small m-0" id="modalMenuDesc">Description</p>
                    </div>

                    <hr class="text-muted opacity-25">

                    <input type="hidden" name="menu_id" id="modalMenuId">
                    <input type="hidden" name="price" id="modalMenuPrice">
                    <input type="hidden" name="name" id="modalMenuNameInput">

                    <div class="mb-4 mt-4">
                        <h6 class="fw-bold mb-1" style="color: var(--primary);"><?= translate('add_on_options') ?></h6>
                        <small class="text-muted d-block mb-3" style="font-size: 12px;"><?= translate('optional') ?></small>
                        <div id="dynamicAddonsContainer">
                            </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="mb-4 mt-4">
                        <h6 class="fw-bold mb-1" style="color: var(--primary);"><?= translate('notes') ?></h6>
                        <small class="text-muted d-block mb-2" style="font-size: 12px;"><?= translate('optional') ?></small>
                        <textarea id="realNotesInput" class="form-control bg-light border-0" rows="3" placeholder="<?= translate('notes_placeholder') ?>" style="border-radius: 12px;"></textarea>
                        <input type="hidden" name="notes" id="finalNotesPayload">
                    </div>
                </div>
                
                <div class="modal-footer border-top p-3 d-block bg-white flex-shrink-0">
                    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                        <span class="fw-semibold text-muted small"><?= translate('total_order') ?></span>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" class="btn btn-circle-addon btn-minus-addon fs-5" onclick="updateMainQty(-1)" style="line-height: 0;">-</button>
                            <input type="number" name="qty" id="modalQty" class="form-control text-center fw-bold border-0 p-0" value="1" min="1" readonly style="width: 30px; font-size: 1.1rem; background: transparent;">
                            <button type="button" class="btn btn-circle-addon btn-plus-addon fs-5" onclick="updateMainQty(1)" style="line-height: 0;">+</button>
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 py-3 fw-bold fs-5 btn-submit-order shadow-sm" id="btnSubmitOrder">
                        <?= translate('add_orders') ?> - 0 ₫
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 2000">
        <div id="cartToast" class="toast align-items-center text-white border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2500" style="background: linear-gradient(135deg, var(--accent), var(--secondary));">
            <div class="d-flex">
                <div class="toast-body fw-semibold px-3 py-2" id="toast-message">✓ <?= translate('added_success') ?></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Translation strings for JS
        const LANG = {
            add_orders: '<?= translate('add_orders') ?>',
            added_success: '<?= translate('added_success') ?>',
            no_variant: '<?= translate('no_variant') ?>',
            choose_one: '<?= translate('choose_one') ?>',
            choose_many: '<?= translate('choose_many') ?>'
        };

        let basePrice = 0;
        let totalAddonPrice = 0;
        let mainQty = 1;
        let orderModalInstance = null;
        
        let selectedCounterAddons = {}; 
        let selectedRadioAddons = {};   

        document.addEventListener('DOMContentLoaded', () => {
            orderModalInstance = new bootstrap.Modal(document.getElementById('orderModal'));
        });

        function formatCurrency(angka) {
            return new Intl.NumberFormat('id-ID').format(angka) + ' ₫';
        }

        function toggleFullscreen(show) {
            const overlay = document.getElementById('fullscreenOverlay');
            if(show) {
                document.getElementById('fullscreenImage').src = document.getElementById('modalMenuImage').src;
                overlay.classList.remove('d-none');
                overlay.classList.add('d-flex');
            } else {
                overlay.classList.add('d-none');
                overlay.classList.remove('d-flex');
            }
        }

        function openOrderModal(id, name, price, imgSrc, desc, variantsJson) {
            basePrice = parseInt(price);
            totalAddonPrice = 0;
            mainQty = 1;
            selectedCounterAddons = {};
            selectedRadioAddons = {};

            document.getElementById('modalMenuId').value = id;
            document.getElementById('modalMenuName').innerText = name;
            document.getElementById('modalMenuNameInput').value = name;
            document.getElementById('modalMenuDesc').innerText = desc;
            
            const imgElement = document.getElementById('modalMenuImage');
            if (imgSrc) {
                imgElement.src = imgSrc;
                imgElement.style.display = 'block';
            } else {
                imgElement.style.display = 'none';
            }
            
            document.getElementById('modalQty').value = 1;
            document.getElementById('realNotesInput').value = ''; 
            
            const container = document.getElementById('dynamicAddonsContainer');
            container.innerHTML = '';
            
            if (variantsJson && variantsJson !== '[]') {
                const variantGroups = JSON.parse(variantsJson);
                
                variantGroups.forEach(group => {
                    let isSingle = (group.type === 'single' || group.type === 'radio' || group.type === 'Pilih Satu');
                    
                    let groupHtml = `
                        <div class="mb-4">
                            <h6 class="fw-bold mb-0" style="color: var(--primary);">${group.group_name}</h6>
                            <small class="text-muted d-block mb-3" style="font-size: 12px;">
                                ${isSingle ? LANG.choose_one : LANG.choose_many}
                            </small>
                    `;

                    group.options.forEach(opt => {
                        let priceText = opt.price > 0 ? `<span class="fw-bold small" style="color: var(--secondary);">(+ ${formatCurrency(opt.price)})</span>` : '';
                        
                        if (isSingle) {
                            groupHtml += `
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <label class="m-0 flex-grow-1" for="opt_${opt.id}" style="cursor: pointer;">
                                        <span class="text-dark small d-block">${opt.name}</span>
                                        ${priceText}
                                    </label>
                                    <div class="ms-3">
                                        <input class="form-check-input variant-radio m-0" type="radio" name="var_${group.group_id}" id="opt_${opt.id}" value="${opt.name}" data-price="${opt.price}" data-group="${group.group_name}" onchange="updateRadioAddon()" style="width: 22px; height: 22px; cursor: pointer; border-color: var(--secondary);">
                                    </div>
                                </div>
                            `;
                        } else {
                            groupHtml += `
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <div>
                                        <span class="text-dark small d-block">${opt.name}</span> 
                                        ${priceText}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-circle-addon btn-minus-addon fs-6" onclick="updateCounterAddon('${opt.name}', ${opt.price}, -1, this)">-</button>
                                        <span class="fw-bold addon-qty small" style="width:15px; text-align:center;">0</span>
                                        <button type="button" class="btn btn-circle-addon btn-plus-addon fs-6" onclick="updateCounterAddon('${opt.name}', ${opt.price}, 1, this)">+</button>
                                    </div>
                                </div>
                            `;
                        }
                    });
                    
                    groupHtml += `</div>`;
                    container.innerHTML += groupHtml;
                });
            } else {
                container.innerHTML = '<span class="text-muted small fst-italic">' + LANG.no_variant + '</span>';
            }

            recalculateTotal();
            if (!orderModalInstance) orderModalInstance = new bootstrap.Modal(document.getElementById('orderModal'));
            orderModalInstance.show();
        }

        function updateCounterAddon(addonName, price, change, btnElement) {
            let spanQty = btnElement.parentElement.querySelector('.addon-qty');
            let currentQty = parseInt(spanQty.innerText);
            let newQty = currentQty + change;
            
            if (newQty >= 0) {
                spanQty.innerText = newQty;
                if (newQty === 0) delete selectedCounterAddons[addonName];
                else selectedCounterAddons[addonName] = { qty: newQty, price: parseInt(price) };
                
                recalculateTotal();
            }
        }

        function updateRadioAddon() {
            selectedRadioAddons = {};
            document.querySelectorAll('.variant-radio:checked').forEach(radio => {
                let groupName = radio.getAttribute('data-group');
                let addonName = radio.value;
                let price = parseInt(radio.getAttribute('data-price'));
                
                selectedRadioAddons[groupName] = { name: addonName, price: price };
            });
            recalculateTotal();
        }

        function updateMainQty(change) {
            let qtyInput = document.getElementById('modalQty');
            let currentQty = parseInt(qtyInput.value);
            let newQty = currentQty + change;
            
            if (newQty >= 1) {
                qtyInput.value = newQty;
                mainQty = newQty;
                recalculateTotal();
            }
        }

        function recalculateTotal() {
            totalAddonPrice = 0;
            
            for (let key in selectedCounterAddons) {
                totalAddonPrice += (selectedCounterAddons[key].price * selectedCounterAddons[key].qty);
            }
            for (let key in selectedRadioAddons) {
                totalAddonPrice += selectedRadioAddons[key].price;
            }
            
            let grandTotal = (basePrice + totalAddonPrice) * mainQty;
            document.getElementById('btnSubmitOrder').innerText = `${LANG.add_orders} - ${formatCurrency(grandTotal)}`;
            document.getElementById('modalMenuPrice').value = basePrice + totalAddonPrice; 
            document.getElementById('modalMenuPriceDisplay').innerText = formatCurrency(basePrice + totalAddonPrice);
        }

        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                const filterValue = this.getAttribute('data-filter');
                const cards = document.querySelectorAll('.menu-item-card');

                cards.forEach(card => {
                    if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                        card.style.setProperty('display', 'block', 'important');
                    } else {
                        card.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        });

        document.querySelectorAll('.ajax-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 

                let finalNotesString = document.getElementById('realNotesInput').value;
                let addonStrings = [];
                
                for (const [group, data] of Object.entries(selectedRadioAddons)) {
                    addonStrings.push(`${data.name}`);
                }
                for (const [name, data] of Object.entries(selectedCounterAddons)) {
                    addonStrings.push(`${name} (x${data.qty})`);
                }
                
                if (addonStrings.length > 0) {
                    let addonText = "\nAdd-on: " + addonStrings.join(", ");
                    document.getElementById('finalNotesPayload').value = finalNotesString + addonText;
                } else {
                    document.getElementById('finalNotesPayload').value = finalNotesString;
                }

                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                const menuName = this.querySelector('input[name="name"]').value;

                button.disabled = true;
                button.innerHTML = `<span class="spinner-border spinner-border-sm"></span>`;

                const formData = new FormData(this);

                fetch(this.action, { method: 'POST', body: formData })
                .then(response => response.text()) 
                .then(htmlText => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(htmlText, 'text/html');
                    const newBadge = doc.querySelector('#cart-badge');
                    const currentBadge = document.getElementById('cart-badge');
                    
                    if (newBadge && currentBadge) {
                        currentBadge.innerHTML = newBadge.innerHTML;
                        if (parseInt(newBadge.innerHTML.trim()) > 0) currentBadge.classList.remove('d-none');
                    }

                    document.getElementById('toast-message').innerText = `✓ ${menuName} ${LANG.added_success}`;
                    const toast = new bootstrap.Toast(document.getElementById('cartToast'));
                    toast.show();

                    if (orderModalInstance) orderModalInstance.hide();
                    setTimeout(() => {
                        document.body.classList.remove('modal-open'); 
                        document.body.removeAttribute('style');
                        document.body.removeAttribute('data-bs-padding-right');
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    }, 400); 
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            });
        });

        // PWA Installation & Service Worker Registration
        let deferredPrompt;
        const btnInstallPwa = document.getElementById('btnInstallPwa');

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= BASEURL ?>/public/sw.js')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                    }).catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing on mobile
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI notify the user they can install the PWA
            btnInstallPwa.classList.remove('d-none');
            
            btnInstallPwa.addEventListener('click', () => {
                // Hide the app provided install promotion
                btnInstallPwa.classList.add('d-none');
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                        // Show button again if dismissed
                        btnInstallPwa.classList.remove('d-none');
                    }
                    deferredPrompt = null;
                });
            });
        });
        
        window.addEventListener('appinstalled', () => {
            // Hide the app-provided install promotion
            btnInstallPwa.classList.add('d-none');
            // Clear the deferredPrompt so it can be garbage collected
            deferredPrompt = null;
            console.log('PWA was installed');
        });

    </script>
</body>
</html>