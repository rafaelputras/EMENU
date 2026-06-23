<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Auto-refresh every 10 seconds -->
    <meta http-equiv="refresh" content="10">
    <title><?= $data['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #281C59; --secondary: #4E8D9C; --accent: #85C79A; --light: #EDF7BD; }
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #0f0a1f; color: #fff; }
        .kitchen-header { background: linear-gradient(135deg, var(--primary) 0%, #3d2d7a 100%); padding: 15px 20px; box-shadow: 0 4px 16px rgba(40,28,89,0.4); }
        .ticket-card { background-color: #1a1230; border: 2px solid #2d2155; border-radius: 16px; overflow: hidden; height: 100%; display: flex; flex-direction: column; }
        .ticket-header { background-color: #221845; padding: 15px; border-bottom: 2px dashed #3d2d7a; }
        .ticket-body { padding: 15px; flex-grow: 1; overflow-y: auto; }
        .ticket-footer { padding: 15px; background-color: #150f28; border-top: 1px solid #2d2155; }
        .item-list { list-style: none; padding: 0; margin: 0; }
        .item-list li { border-bottom: 1px solid #2d2155; padding: 10px 0; }
        .item-list li:last-child { border-bottom: none; }
        .qty-badge { background: linear-gradient(135deg, var(--secondary), var(--accent)); color: #fff; padding: 5px 10px; border-radius: 10px; font-weight: bold; font-size: 1.1rem; }
        
        .lang-switcher a { font-size: 1.2rem; text-decoration: none; transition: all 0.2s; }
        .lang-switcher .active-lang { background: rgba(255,255,255,0.2); border-radius: 8px; padding: 3px 6px; }
        
        .btn-done { background: linear-gradient(135deg, var(--accent), #6db87f); border: none; }
        .btn-done:hover { box-shadow: 0 6px 16px rgba(133,199,154,0.4); }

        @media (max-width: 768px) {
            .ticket-card { margin-bottom: 16px; }
        }
    </style>
</head>
<body>

    <div class="kitchen-header d-flex justify-content-between align-items-center sticky-top">
        <h3 class="m-0 fw-bold"><?= translate('kitchen_header') ?></h3>
        <div class="d-flex align-items-center gap-3">
            <div class="lang-switcher d-flex gap-1">
                <a href="<?= BASEURL ?>/public/language/switch/id" class="<?= ($_SESSION['lang'] ?? 'id') == 'id' ? 'active-lang' : '' ?>">🇮🇩</a>
                <a href="<?= BASEURL ?>/public/language/switch/en" class="<?= ($_SESSION['lang'] ?? 'id') == 'en' ? 'active-lang' : '' ?>">🇬🇧</a>
                <a href="<?= BASEURL ?>/public/language/switch/vi" class="<?= ($_SESSION['lang'] ?? 'id') == 'vi' ? 'active-lang' : '' ?>">🇻🇳</a>
            </div>
            <span class="badge fs-6" style="background: var(--light); color: var(--primary);" id="clock">00:00:00</span>
            <span class="text-white small opacity-75"><?= translate('auto_refresh') ?></span>
        </div>
    </div>

    <div class="container-fluid p-4">
        <?php if(empty($data['orders'])): ?>
            <div class="text-center mt-5 pt-5">
                <h1 class="display-1 opacity-50">🍽️</h1>
                <h3 class="fw-bold mt-3" style="color: var(--secondary);"><?= translate('no_queue_kitchen') ?></h3>
                <p class="text-secondary"><?= translate('kitchen_idle') ?></p>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-3 row-cols-xl-4 g-4 align-items-stretch">
                <?php foreach($data['orders'] as $order): 
                    // Calculate minutes since order
                    $orderTime = strtotime($order['created_at']);
                    $minutesAgo = floor((time() - $orderTime) / 60);
                    
                    // If over 15 minutes, header turns red as warning
                    $headerColor = ($minutesAgo >= 15) ? 'bg-danger' : 'ticket-header';
                ?>
                    <div class="col">
                        <div class="ticket-card shadow-lg">
                            <div class="<?= $headerColor ?> d-flex justify-content-between align-items-start" style="<?= $minutesAgo < 15 ? '' : 'padding: 15px;' ?>">
                                <div>
                                    <h2 class="fw-extrabold text-white m-0"><?= translate('table') ?> <?= $order['table_number'] ?></h2>
                                    <span class="text-light opacity-75 small">ORD: <?= substr($order['order_number'], -6) ?></span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold fs-5" style="color: var(--light);"><?= $minutesAgo ?> <?= translate('minutes') ?></div>
                                    <span class="text-light opacity-75 small"><?= date('H:i', $orderTime) ?></span>
                                </div>
                            </div>
                            
                            <div class="ticket-body">
                                <ul class="item-list">
                                    <?php foreach($order['items'] as $item): 
                                        // Check if item is already done
                                        $isReady = ($item['item_status'] == 'ready');
                                        $textClass = $isReady ? 'text-muted text-decoration-line-through opacity-50' : 'text-light';
                                        $badgeClass = $isReady ? 'opacity-50' : '';
                                    ?>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="qty-badge <?= $badgeClass ?>"><?= $item['quantity'] ?>x</div>
                                                <div>
                                                    <h5 class="fw-bold m-0 <?= $textClass ?>"><?= translate(strtolower($item['menu_name']), $item['menu_name']) ?></h5>
                                                    <?php if(!empty($item['notes'])): ?>
                                                        <div class="small fst-italic mt-1 <?= $isReady ? 'text-muted' : '' ?>" style="<?= !$isReady ? 'color: var(--light);' : '' ?> white-space: pre-wrap;">"<?= $item['notes'] ?>"</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <?php if(!$isReady): ?>
                                                <a href="<?= BASEURL ?>/public/kitchen/markItemReady/<?= $item['id'] ?>/<?= $order['id'] ?>" class="btn btn-sm btn-outline-success fw-bold rounded-circle ms-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;" title="<?= translate('mark_item_done') ?>">
                                                    ✓
                                                </a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="ticket-footer text-center">
                                <a href="<?= BASEURL ?>/public/kitchen/markAsReady/<?= $order['id'] ?>" class="btn btn-done w-100 py-3 fw-bold fs-5 text-uppercase shadow-sm text-white rounded-3" onclick="return confirm('<?= sprintf(translate('confirm_done'), $order['table_number']) ?>')">
                                    <?= translate('done_cooking') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Digital clock
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
        }, 1000);
    </script>
</body>
</html>