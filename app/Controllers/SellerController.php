<?php
require_once __DIR__ . '/../models/Seller.php';
require_once __DIR__ . '/../models/Shop.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../services/FlashMessageService.php';

class SellerController {
    private $sellerModel;
    private $shopModel;
    private $orderModel;
    private $orderItemModel;

    private $productModel;

    public function __construct() {
        $this->sellerModel = new Seller();
        $this->shopModel = new Shop();
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->productModel = new Product();
    }
    
    public function redirect() {
        AuthMiddleware::checkSellerAuth();
        $SellerAuth = AuthService::getSellerAuth(); // seller_id, shop_id

        $data = $this->fetchCommonData($SellerAuth);

        $page = $_GET['page'] ?? 'dashboard';
        $this->handlePage($page, $data);
    }
    
    public function handlePage($page, $data) {
        switch($page) {
            case 'order-detail':
                $order_id = $_GET['order_id'];
                $order = $this->orderModel->getOrder($order_id);
                $order_items = $this->orderItemModel->getOrderItemByOrderId($order_id);

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status_order'])) {
                    $newStatus = $_POST['status'];
                    $update = $this->orderModel->updateOrderStatus($order_id, $newStatus);
                    header("Location: /SHooad/public/seller/dashboard?page=order-detail&order_id=" . $order_id);
                    exit;
                }

                include __DIR__ . '/../views/seller/pages/order-detail.php';
                break;
            case 'product-detail':
                $product_id = $_GET['product_id'];
                $product = $this->productModel->getProductById($product_id);

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
                    $newStatus = $_POST['status'];
                    $update = $this->productModel->updateProduct($product_id, $newStatus);
                    header("Location: /SHooad/public/seller/dashboard?page=product-detail&product_id=" . $product_id);
                    exit;
                }

                include __DIR__ . '/../views/seller/pages/product-detail.php';
                break;
            default:
                include __DIR__ . '/../views/seller/dashboard.php';
                break;
        }   
    }

    public function fetchCommonData($SellerAuth) {
           return [
            'seller' => $this->sellerModel->getInfo($SellerAuth['seller_id']),
            'shop' => $this->shopModel->getInfo($SellerAuth['shop_id']),
            'orders' => $this->orderModel->getOrderByShop($SellerAuth['shop_id']),
            'products' => $this->productModel->getProductByShop($SellerAuth['shop_id'])
        ];
    }


    public function login() {
        if(isset($_SESSION["seller_id"]) && isset($_SESSION["shop_id"])) {
            header('Location: /SHooad/public/seller/dashboard');
            exit();
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // TODO: validate input 
            $sellerEmail = $this->sellerModel->findEmail($_POST["email"]);
            $shop = $this->shopModel->findShopBySellerId($sellerEmail['id']);
            if($sellerEmail && password_verify($_POST["password"], $sellerEmail['password'])) {
                AuthService::setSellerAuth($sellerEmail['id'], $shop['id']);
                header('Location: /SHooad/public/seller/dashboard');
            } else {
                FlashMessageService::setFlashMessage('error', 'Invalid email or password.');
                $error = FlashMessageService::getFlashMessage('error');
            }
        }

        include __DIR__ . '/../Views/seller/login.php';
    }

    public function signup() {
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // TODO: validate input 
            $email = $_POST["seller_signup_email"];
            $password = password_hash($_POST["seller_signup_password"], PASSWORD_BCRYPT);
            $name = $_POST["seller_signup_name"];

            // Create new seller account
            $newSellerAccount = $this->sellerModel->insertSeller($email, $password, $name);

            if($newSellerAccount) {
                $newSellerShop = $this->shopModel->insertShop($newSellerAccount['id'], $_POST["shop_name"], $_POST["shop_description"]);
                AuthService::setSellerAuth($newSellerAccount['id'], $newSellerShop['id']);
                header('Location: /SHooad/public/seller/dashboard');
            } else {
                FlashMessageService::setFlashMessage('error', 'Signup failed. Please try again.');
                $error = FlashMessageService::getFlashMessage('error');
            }
        }
        
        include __DIR__ . '/../Views/seller/signup.php';
    }

    public function logout() {
       AuthService::logout();
       AuthMiddleware::checkSellerAuth();
    }
}