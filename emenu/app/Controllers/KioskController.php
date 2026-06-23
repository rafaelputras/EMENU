<?php
class KioskController extends Controller {

    public function index() {
        $menuModel = $this->model('Menu');
        
        $data = [
            'title' => translate('kiosk_title'),
            'menus' => $menuModel->getAllMenus()
        ];
        
        // Display menu directly without QR table check
        $this->view('kiosk/index', $data);
    }

    public function detail($id = null) {
        if(!$id) {
            header('Location: /public/kiosk');
            exit;
        }

        $menuModel = $this->model('Menu');
        $data = [
            'title' => translate('kiosk_detail'),
            'menu' => $menuModel->getMenuById($id),
            'recommendations' => $menuModel->getRecommendationsByMenu($id)
        ];

        $this->view('kiosk/detail', $data);
    }

    public function cart() {
        $data = [
            'title' => translate('kiosk_cart'),
            'cart' => $_SESSION['kiosk_cart'] ?? []
        ];
        $this->view('kiosk/cart', $data);
    }

    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item = [
                'menu_id' => $_POST['menu_id'],
                'name' => $_POST['name'],
                'qty' => $_POST['qty'],
                'price' => $_POST['price'],
                'subtotal' => $_POST['qty'] * $_POST['price']
            ];
            
            // Use separate session to avoid conflicts with regular table orders
            $_SESSION['kiosk_cart'][] = $item;
            header('Location: /public/kiosk');
            exit;
        }
    }

    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['kiosk_cart'])) {
            $orderModel = $this->model('Order');
            
            // Assign to shadow table ID 99 (Kiosk)
            $table_id = 99; 
            
            $customer_name = $_POST['customer_name'] ?? translate('kiosk_guest');
            $cart_items = $_SESSION['kiosk_cart'];

            $total_amount = 0;
            foreach ($cart_items as $item) {
                $total_amount += $item['subtotal'];
            }

            $success = $orderModel->createOrder($table_id, $customer_name, $total_amount, $cart_items);

            if ($success) {
                unset($_SESSION['kiosk_cart']);
                echo "<script>alert('" . translate('kiosk_success') . "'); window.location='/public/kiosk';</script>";
            } else {
                echo "<script>alert('" . translate('kiosk_failed') . "'); window.history.back();</script>";
            }
        }
    }
}