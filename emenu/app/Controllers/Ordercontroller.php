<?php

class OrderController extends Controller {

    // 1. Main Page
    public function index() {
        $tableNumber = $_GET['table'] ?? $_GET['tableNumber'] ?? null;

        if (!empty($tableNumber)) {
            // DEV MODE: Accept any table parameter without DB validation
            // Try to find in DB for proper ID, otherwise use table param directly
            $tableModel = $this->model('Table');
            $table = $tableModel->getTableByNumber($tableNumber);

            if ($table) {
                $_SESSION['table_id'] = $table['id'];
                $_SESSION['table_number'] = $table['table_number'];
                $tableModel->updateStatus($table['id'], 'occupied');
            } else {
                // Table not found in DB but still allow access (dev mode)
                $_SESSION['table_id'] = 1;
                $_SESSION['table_number'] = $tableNumber;
            }
        } elseif (!isset($_SESSION['table_id'])) {
            // No table param and no session — allow direct access (dev mode)
            $_SESSION['table_id'] = 1;
            $_SESSION['table_number'] = 'Direct Access';
        }

        $menuModel = $this->model('Menu');
        $categoryModel = $this->model('Category');

        $data = [
            'title' => translate('order_title'),
            'menus' => $menuModel->getActiveMenus(), 
            'categories' => $categoryModel->getAllCategories() 
        ];

        $this->view('order/index', $data);
    }

    // 2. Menu Detail Page
    public function detail($id = null) {
        if (!isset($_SESSION['table_id'])) {
            // No session — allow direct access with default table (dev mode)
            $_SESSION['table_id'] = 1;
            $_SESSION['table_number'] = 'Direct Access';
        }

        $menuModel = $this->model('Menu');
        $categoryModel = $this->model('Category');

        $menuDetail = $menuModel->getMenuById($id);
        $aiRecommendations = $menuModel->getRecommendationsByMenu($id);

        $data = [
            'title' => 'Detail Menu - ' . ($menuDetail['name'] ?? 'Menu'),
            'menu' => $menuDetail, 
            'recommendations' => $aiRecommendations,
            'menus' => $menuModel->getActiveMenus(), 
            'categories' => $categoryModel->getAllCategories() 
        ];

        $this->view('order/detail', $data);
    }

    // 3. Add to Cart
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $menu_id = $_POST['menu_id'];
            $qty = (int)($_POST['qty'] ?? 1);
            $notes = htmlspecialchars($_POST['notes'] ?? ''); 

            if (!isset($_SESSION['table_id'])) {
                $_SESSION['table_id'] = 1;
                $_SESSION['table_number'] = 'Testing';
            }

            $menuModel = $this->model('Menu');
            $menu = $menuModel->getMenuById($menu_id);

            if ($menu) {
                $finalPrice = $menu['price']; 
                
                if (!empty($menu['promo_price']) && $menu['promo_price'] > 0) {
                    date_default_timezone_set('Asia/Jakarta');
                    $now = date('Y-m-d H:i:s');
                    
                    $validStart = empty($menu['promo_start']) || $menu['promo_start'] <= $now;
                    $validEnd = empty($menu['promo_end']) || $menu['promo_end'] >= $now;
                    $validQuota = (!isset($menu['promo_quota']) || $menu['promo_quota'] > 0 || $menu['promo_quota'] == null); 

                    if ($validStart && $validEnd && $validQuota) {
                        $finalPrice = $menu['promo_price']; 
                    }
                }

                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                $cartItemKey = $menu_id . '_' . md5($notes);

                if (isset($_SESSION['cart'][$cartItemKey])) {
                    $_SESSION['cart'][$cartItemKey]['qty'] += $qty;
                    $_SESSION['cart'][$cartItemKey]['price'] = $finalPrice; 
                    $_SESSION['cart'][$cartItemKey]['subtotal'] = $_SESSION['cart'][$cartItemKey]['qty'] * $finalPrice;
                } else {
                    $_SESSION['cart'][$cartItemKey] = [
                        'id' => $menu['id'],
                        'name' => $menu['name'],
                        'price' => $finalPrice,
                        'image' => $menu['image'],
                        'qty' => $qty,
                        'notes' => $notes, 
                        'subtotal' => $qty * $finalPrice
                    ];
                }

                $totalItems = count($_SESSION['cart']);
                echo "<span id='cart-badge'>$totalItems</span>";
                exit;
            }
        }
    }

    // 4. Cart Page
    public function cart() {
        if (!isset($_SESSION['table_id'])) {
            // No session — allow direct access with default table (dev mode)
            $_SESSION['table_id'] = 1;
            $_SESSION['table_number'] = 'Direct Access';
        }

        $menuModel = $this->model('Menu');
        $categoryModel = $this->model('Category');

        $data = [
            'title' => translate('checkout_title'),
            'menus' => $menuModel->getActiveMenus(), 
            'categories' => $categoryModel->getAllCategories() 
        ];

        $this->view('order/cart', $data);
    }

    // 5. Process Checkout
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
            $customer_name = $_POST['customer_name'] ?? 'Customer';
            $payment_method = $_POST['payment_method'] ?? 'cash';
            $table_id = $_SESSION['table_id'] ?? 1; 
            
            $total_amount = 0;
            $cart_items = [];
            
            foreach ($_SESSION['cart'] as $cartItemKey => $item) {
                $total_amount += $item['subtotal'];
                $cart_items[] = [
                    'menu_id' => $item['id'],
                    'qty'     => $item['qty'],
                    'price'   => $item['price'],
                    'notes'   => $item['notes'] ?? '' 
                ];
            }

            $orderModel = $this->model('Order');
            $order_id = $orderModel->createOrder($table_id, $customer_name, $total_amount, $cart_items, $payment_method);
            
            if ($order_id) {
                unset($_SESSION['cart']); 
                
                // All payment methods redirect to receipt page
                header('Location: ' . BASEURL . '/public/order/receipt/' . $order_id);
                exit;
            } else {
                echo "<script>alert('" . translate('order_failed') . "'); window.history.back();</script>";
            }
        } else {
            header('Location: ' . BASEURL . '/public/order/cart');
            exit;
        }
    }

    // 6. Receipt & QR Code Page
    public function receipt($order_id = null) {
        if (!$order_id) {
            header('Location: ' . BASEURL . '/public/order');
            exit;
        }

        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderById($order_id);
        $items = $orderModel->getOrderItems($order_id);

        if (!$order) {
                header('Location: ' . BASEURL . '/public/order');
            exit;
        }

        $data = [
            'title' => translate('receipt_title') . ' - ' . $order['order_number'],
            'order' => $order,
            'items' => $items
        ];

        $this->view('order/receipt', $data);
    }
}