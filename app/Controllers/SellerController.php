<?php
require_once __DIR__ . '/../models/Seller.php';
require_once __DIR__ . '/../models/Shop.php';
require_once __DIR__ . '/../models/Order.php';

class SellerController {
    private $sellerModel;
    private $shopModel;
    private $shopOrderModel;

    public function __construct() {
        $this->sellerModel = new Seller();
        $this->shopModel = new Shop();
        $this->shopOrderModel = new Order();
    }

    public function dashboard() {
        if (!isset($_SESSION["seller_id"])) {
            echo $_SESSION["seller_id"];
            header('Location: /SHooad/public/seller/login');
        } else {
            $seller = $this->sellerModel->getSellers($_SESSION["seller_id"]);
            $shop = $this->shopModel->getShop($_SESSION["seller_id"]);
            $shop_orders = $this->shopOrderModel->getAllOrdersShop($_SESSION["shop_id"]);

            include __DIR__ . '/../views/seller/dashboard.php';
        }

    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            $conn = new Database();
            $db = $conn->getConnection();
            $stmt = $db->prepare("SELECT * FROM seller_account WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row["password"])) {
                    $_SESSION["seller_id"] = $row["id"];
                    $_SESSION["seller_email"] = $row["email"];
                    $_SESSION["shop_id"] = $this->shopModel->getShop($row["id"]);
                    header('Location: /SHooad/public/seller/dashboard');
                    exit;
                } else {
                    echo "password incorrect!";
                }
            } else {
                echo "Email not found!";
            }
        }

        include '../app/views/seller/login.php';
    }

    public function signup() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $seller_signup_email = $_POST["seller_signup_email"];
            $seller_signup_password = password_hash($_POST["seller_signup_password"], PASSWORD_BCRYPT);
            $seller_name = $_POST["seller_signup_name"];

            $conn = new Database();
            $db = $conn->getConnection();

            // Insert seller
            $stmtSeller = $db->prepare("INSERT INTO seller_account (email, password, name) VALUES (?, ?, ?)");
            $stmtSeller->bind_param("sss", $seller_signup_email, $seller_signup_password, $seller_name);

            if ($stmtSeller->execute()) {
                $seller_id = $db->insert_id;

                // Create shop for seller
                $stmtShop = $db->prepare("INSERT INTO shops (seller_id) VALUES (?)");
                $stmtShop->bind_param("i", $seller_id);
                
                if ($stmtShop->execute()) {
                    $_SESSION["seller_id"] = $seller_id;
                    $_SESSION["seller_email"] = $seller_signup_email;
                    $_SESSION["shop_id"] = $db->insert_id;
                } else {
                    echo "Error creating shop: " . $stmtShop->error;
                }

                header('Location: /SHooad/public/seller/dashboard');
                exit;
            } else {
                echo "Error: " . $stmtSeller->error;
            }
        }


        include '../app/views/seller/signup.php';
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /SHooad/public/seller/login');
        exit;
    }

    public function orderDetail() {
        $orderId = $_GET['order_id'];
        $order = $this->shopOrderModel->getOrder($orderId);
        $order_items = $this->shopOrderModel->getOrderItems($orderId);

        include __DIR__ . '/../views/seller/pages/order-detail.php';
    }

    public function updateOrderStatus() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $orderId = $_POST['order_id'];
            $newStatus = $_POST['status'];

            $this->shopOrderModel->updateOrderStatus($orderId, $newStatus);

            header("Location: /SHooad/public/seller/dashboard?page=order-detail&order_id=" . $orderId);
            exit;
        }
    }
}

?>