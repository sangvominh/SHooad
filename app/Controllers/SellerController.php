<?php
require_once __DIR__ . '/../models/Seller.php';
require_once __DIR__ . '/../models/Shop.php';
class SellerController {
    private $sellerModel;
    private $shopModel;

    public function __construct() {
        $this->sellerModel = new Seller();
        $this->shopModel = new Shop();
    }

    public function dashboard() {
        if (!isset($_SESSION["seller_id"])) {
            echo $_SESSION["seller_id"];
            header('Location: /SHooad/public/seller/login');
        } else {
            $seller = $this->sellerModel->getSellers($_SESSION["seller_id"]);
            $shop = $this->shopModel->getShop($_SESSION["seller_id"]);
            
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
                $stmtShop = $db->prepare("INSERT INTO shop (seller_id) VALUES (?)");
                $stmtShop->bind_param("i", $seller_id);
                
                if ($stmtShop->execute()) {
                    // Shop created successfully
                } else {
                    echo "Error creating shop: " . $stmtShop->error;
                }

                $_SESSION["seller_id"] = $seller_id;
                $_SESSION["seller_email"] = $seller_signup_email;

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

    public function getInfoShop () {
        
    }
}

?>