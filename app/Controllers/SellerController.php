<?php
require_once __DIR__ . '/../services/Seller/AuthSellerService.php';
require_once __DIR__ . '/../services/Seller/SellerPageService.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../services/FlashMessageService.php';

class SellerController {
    private $authService;
    private $pageService;

    public function __construct() {
        $this->authService = new AuthSellerService();
        $this->pageService = new SellerPageService();
    }

    private function redirectTo(string $url): void {
        header("Location: $url");
        exit;
    }
    
    public function dashboard() {
        AuthMiddleware::checkSellerAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        }

        $this->pageService->renderDashboard();
    }

    private function handlePostRequest(): void {
        if (isset($_POST['update_status_order']) && isset($_GET['order_id'])) {
            $url = $this->pageService->handleOrderUpdate($_GET['order_id'], $_POST['status']);
            $this->redirectTo($url);
        }

        if (isset($_POST['update_product']) && isset($_GET['product_id'])) {
            $url = $this->pageService->handleProductUpdate($_GET['product_id'], $_POST['status']);
            $this->redirectTo($url);
        }
    }

    public function login() {
        if (isset($_SESSION["seller_id"]) && isset($_SESSION["shop_id"])) {
            $this->redirectTo('/SHooad/public/seller/dashboard');
        }
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if ($this->authService->login($_POST['email'], $_POST['password'])) {
                $this->redirectTo('/SHooad/public/seller/dashboard');
            }
        }

        $error = FlashMessageService::getFlashMessage('error');
        include __DIR__ . '/../Views/seller/login.php';
    }

    public function signup() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if ($this->authService->signup(
                $_POST['seller_signup_email'],
                $_POST['seller_signup_password'],
                $_POST['seller_signup_name'],
                $_POST['shop_name'],
                $_POST['shop_description']
            )) {
                $this->redirectTo('/SHooad/public/seller/dashboard');
            }
        }
        
        $error = FlashMessageService::getFlashMessage('error');
        include __DIR__ . '/../Views/seller/signup.php';
    }

    public function logout() {
        $this->authService->logout();
        $this->redirectTo('/SHooad/public/seller/login');
    }
}