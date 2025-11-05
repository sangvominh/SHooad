<?php
require_once __DIR__ . '/../models/Shipper.php';
require_once __DIR__ . '/../models/Order.php';

class ShipperController {
    private $shipperModel;
    private $orderModel;

    public function __construct() {
        $this->shipperModel = new Shipper();
        $this->orderModel = new Order();
    }

    public function dashboard() {
        if (!isset($_SESSION["shipper_id"])) {
            header('Location: /SHooad/public/shipper/login');
            exit;
        } else {
            $shipper = $this->shipperModel->getShipper($_SESSION["shipper_id"]);

            include __DIR__ . '/../views/shipper/dashboard.php';
        }
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            $conn = new Database();
            $db = $conn->getConnection();
            $stmt = $db->prepare("SELECT * FROM shipper_account WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row["password"])) {
                    $_SESSION["shipper_id"] = $row["id"];
                    $_SESSION["shipper_email"] = $row["email"];
                    header('Location: /SHooad/public/shipper/dashboard');
                    exit;
                } else {
                    echo "Password incorrect!";
                }
            } else {
                echo "Email not found!";
            }
        }

        include '../app/views/shipper/login.php';
    }

    public function signup() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["shipper_email"];
            $password = password_hash($_POST["shipper_password"], PASSWORD_BCRYPT);
            $name = $_POST["shipper_name"];
            $phone = $_POST["shipper_phone"] ?? null;

            $conn = new Database();
            $db = $conn->getConnection();

            // Insert shipper
            $stmt = $db->prepare("
                INSERT INTO shipper_account (email, password, name, phone)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("ssss", $email, $password, $name, $phone);

            if ($stmt->execute()) {
                $_SESSION["shipper_id"] = $db->insert_id;
                $_SESSION["shipper_email"] = $email;

                header('Location: /SHooad/public/shipper/dashboard');
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        include '../app/views/shipper/signup.php';
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /SHooad/public/shipper/login');
        exit;
    }

    public function orderDetail() {
        $orderId = $_GET['order_id'];
        $order = $this->orderModel->getOrder($orderId);
        $order_items = $this->orderModel->getOrderItems($orderId);

        include __DIR__ . '/../views/shipper/pages/order-detail.php';
    }
}