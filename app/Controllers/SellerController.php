<?php
require_once __DIR__ . '/../services/Seller/AuthSellerService.php';
require_once __DIR__ . '/../services/Seller/SellerService.php';
require_once __DIR__ . '/../services/Seller/SellerAnalysisService.php';
require_once __DIR__ . '/../services/ProductService.php';
require_once __DIR__ . '/../services/OrderService.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../services/FlashMessageService.php';

class SellerController {
    private $authService;
    private $sellerService;
    private $analysisService;
    private $productService;
    private $orderService;

    public function __construct() {
        $this->authService = new AuthSellerService();
        $this->sellerService = new SellerService();
        $this->analysisService = new SellerAnalysisService();
        $this->productService = new ProductService();
        $this->orderService = new OrderService();
    }

    private function redirectTo(string $url): void {
        header("Location: $url");
        exit;
    }

    private function getSessionData(): array {
        $seller_id = $_SESSION['seller_id'] ?? null;
        $shop_id = $_SESSION['shop_id'] ?? null;

        if (!$seller_id || !$shop_id) {
            $this->redirectTo('/SHooad/public/seller/login');
        }

        return ['seller_id' => $seller_id, 'shop_id' => $shop_id];
    }

    private function loadDashboardData(bool $limit_for_dashboard = false): array {
        $session = $this->getSessionData();
        return $this->sellerService->getDashboardData($session['seller_id'], $session['shop_id'], $limit_for_dashboard);
    }

    private function renderDashboard(string $page = 'dashboard'): void {
        $limit_for_dashboard = ($page === 'dashboard');
        $data = $this->loadDashboardData($limit_for_dashboard);
        $current_page = $page;
        include __DIR__ . '/../Views/seller/dashboard.php';
    }
    
    public function dashboard() {
        AuthMiddleware::checkSellerAuth();
        $this->renderDashboard('dashboard');
    }

    public function orderDetail() {
        AuthMiddleware::checkSellerAuth();

        $order_id = $_GET['order_id'] ?? null;
        if (!$order_id) {
            $this->redirectTo('/SHooad/public/seller/dashboard');
        }

        // Handle POST - update order status
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status_order'])) {
            $this->orderService->updateOrderStatus($order_id, $_POST['status']);
            $this->redirectTo("/SHooad/public/seller/order-detail?order_id=$order_id");
        }

        $data = $this->loadDashboardData();
        $orderData = $this->orderService->getOrderWithItems($order_id);
        $order = $orderData['order'];
        $order_items = $orderData['items'];

        include __DIR__ . '/../Views/seller/pages/order-detail.php';
    }

    public function productDetail() {
        AuthMiddleware::checkSellerAuth();

        $product_id = $_GET['product_id'] ?? null;
        if (!$product_id) {
            $this->redirectTo('/SHooad/public/seller/dashboard');
        }

        // Handle POST - delete image
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image_id'])) {
            $this->productService->deleteProductImage($_POST['delete_image_id']);
            $this->redirectTo("/SHooad/public/seller/product-detail?product_id=$product_id");
        }

        // Handle POST - update product
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
            $this->productService->updateProduct($product_id, $_POST);

            if (!empty($_FILES['new_images']['name'][0])) {
                $this->productService->uploadProductImages($product_id, $_FILES['new_images']);
            }

            $this->redirectTo("/SHooad/public/seller/product-detail?product_id=$product_id");
        }

        $data = $this->loadDashboardData();
        $product = $this->productService->getProductById($product_id);
        $product['images'] = $this->productService->getProductImages($product_id);

        include __DIR__ . '/../Views/seller/pages/product-detail.php';
    }

    public function orders() {
        AuthMiddleware::checkSellerAuth();
        $this->renderDashboard('orders');
    }

    public function products() {
        AuthMiddleware::checkSellerAuth();
        $this->renderDashboard('products');
    }

    public function addProduct() {
        AuthMiddleware::checkSellerAuth();

        // Handle POST - create product
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $session = $this->getSessionData();
            
            $product_id = $this->productService->createProductWithImages(
                $session['shop_id'],
                $_POST,
                $_FILES['images'] ?? []
            );

            $this->redirectTo('/SHooad/public/seller/products');
        }

        $data = $this->loadDashboardData();
        include __DIR__ . '/../Views/seller/pages/add-product.php';
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

    public function analysis() {
        AuthMiddleware::checkSellerAuth();
        
        $session = $this->getSessionData();
        $type = $_GET['type'] ?? 'orders'; // Default to orders
        
        // Get analysis data based on type
        if ($type === 'products') {
            $analysisData = $this->analysisService->getProductsAnalysis($session['shop_id']);
        } else {
            $analysisData = $this->analysisService->getOrdersAnalysis($session['shop_id']);
        }
        
        // Load dashboard data for sidebar
        $data = $this->loadDashboardData();
        $current_page = 'analysis';
        
        // Return JSON if requested via AJAX
        if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
            header('Content-Type: application/json');
            echo json_encode($analysisData);
            exit;
        }
        
        // Otherwise render the view
        include __DIR__ . '/../Views/seller/pages/analysis.php';
    }

    public function comingSoon(string $page) {
        AuthMiddleware::checkSellerAuth();
        
        // Load dashboard data for sidebar
        $data = $this->loadDashboardData();
        $current_page = $page;
        
        // Render coming soon view
        include __DIR__ . '/../Views/seller/coming-soon.php';
    }
}