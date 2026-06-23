<?php
class KitchenController extends Controller {
    
    public function index() {
        $orderModel = $this->model('Order');
        
        $data = [
            'title' => translate('kitchen_display'),
            'orders' => $orderModel->getKitchenOrders()
        ];
        
        $this->view('kitchen/index', $data);
    }

    // Button: Mark entire order as ready
    public function markAsReady($order_id = null) {
        if ($order_id) {
            $orderModel = $this->model('Order');
            // 1. Update main order status
            $orderModel->updateOrderStatus($order_id, 'ready');
            
            // 2. Also update all items inside to ready (keep in sync)
            $stmt = $orderModel->conn->prepare("UPDATE order_items SET item_status = 'ready' WHERE order_id = :id");
            $stmt->execute([':id' => $order_id]);
        }
        header('Location: ' . BASEURL . '/public/kitchen');
        exit;
    }

    // Button: Mark individual item as ready
    public function markItemReady($item_id = null, $order_id = null) {
        if ($item_id && $order_id) {
            $orderModel = $this->model('Order');
            // 1. Update specific item status to ready
            $orderModel->updateOrderItemStatus($item_id, 'ready');
            
            // 2. Check if this was the last item; if so, auto-complete order
            $orderModel->checkAndUpdateOrderStatus($order_id);
        }
        header('Location: ' . BASEURL . '/public/kitchen');
        exit;
    }
}