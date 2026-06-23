<?php
class POSController extends Controller {
    
    public function index() {
        $orderModel = $this->model('Order');
        
        // Get all active (incomplete) orders
        $activeOrders = $orderModel->getActiveOrders();

        $data = [
            'title' => translate('pos_dashboard'),
            'orders' => $activeOrders
        ];

        $this->view('pos/dashboard', $data);
    }

    // FIRE Feature: Send order to kitchen
    public function fireOrder($order_id = null) {
        if ($order_id) {
            $orderModel = $this->model('Order');
            $orderModel->updateOrderStatus($order_id, 'cooking');
            
            echo "<script>
                    alert('" . translate('fire_success') . "'); 
                    window.location.href='" . BASEURL . "/public/pos';
                  </script>";
            exit;
        }
    }

    // Payment Feature: Complete order
    public function payOrder($order_id = null) {
        if ($order_id) {
            $orderModel = $this->model('Order');
            
            // Mark order as paid and completed
            $orderModel->updatePaymentStatus($order_id, 'paid');
            $orderModel->updateOrderStatus($order_id, 'completed');
            
            echo "<script>
                    alert('" . translate('payment_processed') . "'); 
                    window.location.href='" . BASEURL . "/public/pos';
                  </script>";
            exit;
        }
    }
}