<?php
class CashierController extends Controller {
    
    public function index() {
        $menuModel = $this->model('Menu');
        $categoryModel = $this->model('Category');
        
        $data = [
            'title' => translate('title_pos'),
            'menus' => $menuModel->getActiveMenus(),
            'categories' => $categoryModel->getAllCategories()
        ];
        
        $this->view('cashier/index', $data);
    }

    public function scanOrder() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_number = $_POST['order_number'];
            $orderModel = $this->model('Order');
            
            $order = $orderModel->getOrderByNumber($order_number);
            
            if ($order && $order['payment_status'] == 'unpaid') {
                $items = $orderModel->getOrderItems($order['id']);
                echo json_encode(['status' => 'success', 'order' => $order, 'items' => $items]);
            } else {
                echo json_encode(['status' => 'error', 'message' => translate('order_not_found')]);
            }
        }
    }

    // Process Payment Confirmation and Change
    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_id = $_POST['order_id'] ?? '';
            $cash_amount = $_POST['cash_amount'];
            $final_total = $_POST['final_total'] ?? 0;
            
            $orderModel = $this->model('Order');

            if (empty($order_id)) {
                // MANUAL PATH: Create a new receipt for walk-in customers
                $order_id = $orderModel->createOrder(1, 'Walk-in Customer', $final_total, [], 'cash');
                $orderModel->markAsPaid($order_id);
            } else {
                // QR SCAN PATH: Update total (if cashier added new items) then mark as paid
                $orderModel->updateOrderTotalAndPay($order_id, $final_total);
            }
            
            $_SESSION['swal_success'] = translate('payment_ok');
            header('Location: ' . BASEURL . '/public/cashier');
            exit;
        }
    }
}