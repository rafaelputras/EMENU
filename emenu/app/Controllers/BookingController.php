<?php
class BookingController extends Controller {
    public function index() {
        $tableModel = $this->model('Table');
        
        $data = [
            'title' => translate('booking_title'),
            'tables' => $tableModel->getAllTables()
        ];

        $this->view('booking/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer_name = $_POST['customer_name'];
            $customer_phone = $_POST['customer_phone'];
            $table_id = $_POST['table_id'];
            $reservation_date = $_POST['reservation_date'];

            $reservationModel = $this->model('Reservation');
            $success = $reservationModel->createReservation($customer_name, $customer_phone, $table_id, $reservation_date);

            if ($success) {
                echo "<script>alert('" . translate('reservation_ok') . "'); window.location='/public/booking';</script>";
            } else {
                echo "<script>alert('" . translate('reservation_fail') . "'); window.history.back();</script>";
            }
        }
    }
}