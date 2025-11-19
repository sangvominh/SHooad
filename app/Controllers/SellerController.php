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

            // Handle variants
            $submittedVariantIds = [];
            if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                foreach ($_POST['variants'] as $variantData) {
                    $variantId = $variantData['id'] ?? null;
                    $stock = $variantData['stock'] ?? 0;
                    $price = !empty($variantData['price']) ? $variantData['price'] : null;
                    $sku = $variantData['sku'] ?? '';
                    $colorId = !empty($variantData['color_id']) ? $variantData['color_id'] : null;
                    $sizeId = !empty($variantData['size_id']) ? $variantData['size_id'] : null;

                    if ($variantId) {
                        // Update existing variant
                        $this->productService->updateProductVariant($variantId, [
                            'color_id' => $colorId,
                            'size_id' => $sizeId,
                            'stock' => $stock,
                            'price' => $price,
                            'sku' => $sku
                        ]);
                        $submittedVariantIds[] = $variantId;
                    } else {
                        // Create new variant
                        $this->productService->createProductVariant([
                            'product_id' => $product_id,
                            'color_id' => $colorId,
                            'size_id' => $sizeId,
                            'stock' => $stock,
                            'price' => $price,
                            'sku' => $sku
                        ]);
                    }
                }
            }

            // Delete variants that are no longer in the form
            $currentVariants = $this->productService->getProductVariants($product_id);
            foreach ($currentVariants as $variant) {
                if (!in_array($variant['id'], $submittedVariantIds)) {
                    $this->productService->deleteProductVariant($variant['id']);
                }
            }

            if (!empty($_FILES['new_images']['name'][0])) {
                $this->productService->uploadProductImages($product_id, $_FILES['new_images']);
            }

            $this->redirectTo("/SHooad/public/seller/product-detail?product_id=$product_id");
        }

        $data = $this->loadDashboardData();
        $product = $this->productService->getProductById($product_id);
        $product['images'] = $this->productService->getProductImages($product_id);
        $product['variants'] = $this->productService->getProductVariants($product_id);
        $allColors = $this->productService->getAllColors();
        $allSizes = $this->productService->getAllSizes();
        $allCategories = $this->productService->getAllCategories();

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
        $allColors = $this->productService->getAllColors();
        $allSizes = $this->productService->getAllSizes();
        $allCategories = $this->productService->getAllCategories();
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
        $type = $_GET['type'] ?? 'products'; // Default to products
        $days = $_GET['days'] ?? 30; // Default to 30 days
        
        // Convert 'all' to string, otherwise ensure it's an integer
        if ($days !== 'all') {
            $days = (int)$days;
        }
        
        // Get analysis data based on type
        if ($type === 'products') {
            $analysisData = $this->analysisService->getProductsAnalysis($session['shop_id'], $days);
        } else {
            $analysisData = $this->analysisService->getOrdersAnalysis($session['shop_id'], $days);
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

    // Forgot Password - Step 1: Enter Email
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            
            if (empty($email)) {
                FlashMessageService::setFlashMessage('error', 'Please enter your email');
                header('Location: /SHooad/public/seller/forgot-password');
                exit;
            }
            
            // Check if email exists
            require_once __DIR__ . '/../Models/Seller.php';
            $sellerModel = new Seller();
            $seller = $sellerModel->findEmail($email);
            
            if (!$seller) {
                FlashMessageService::setFlashMessage('error', 'Email not found in our system');
                header('Location: /SHooad/public/seller/forgot-password');
                exit;
            }
            
            // Email exists - redirect to reset password page
            header('Location: /SHooad/public/seller/reset-password?email=' . urlencode($email));
            exit;
        }
        
        // Show forgot password form
        include __DIR__ . '/../Views/seller/forgot-password.php';
    }

    // Reset Password - Step 2: Enter New Password
    public function resetPassword() {
        $email = $_GET['email'] ?? $_POST['email'] ?? '';
        
        if (empty($email)) {
            FlashMessageService::setFlashMessage('error', 'Invalid request');
            header('Location: /SHooad/public/seller/forgot-password');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate passwords
            if (empty($newPassword) || empty($confirmPassword)) {
                FlashMessageService::setFlashMessage('error', 'Please fill in all fields');
                header('Location: /SHooad/public/seller/reset-password?email=' . urlencode($email));
                exit;
            }
            
            if (strlen($newPassword) < 6) {
                FlashMessageService::setFlashMessage('error', 'Password must be at least 6 characters');
                header('Location: /SHooad/public/seller/reset-password?email=' . urlencode($email));
                exit;
            }
            
            if ($newPassword !== $confirmPassword) {
                FlashMessageService::setFlashMessage('error', 'Passwords do not match');
                header('Location: /SHooad/public/seller/reset-password?email=' . urlencode($email));
                exit;
            }
            
            // Update password
            require_once __DIR__ . '/../Core/Database.php';
            
            $db = (new Database())->getConnection();
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare("UPDATE sellers SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            
            if ($stmt->execute()) {
                FlashMessageService::setFlashMessage('success', 'Password reset successfully! Please login with your new password');
                header('Location: /SHooad/public/seller/login');
            } else {
                FlashMessageService::setFlashMessage('error', 'Failed to reset password. Please try again');
                header('Location: /SHooad/public/seller/reset-password?email=' . urlencode($email));
            }
            exit;
        }
        
        // Show reset password form
        $data = ['email' => $email];
        include __DIR__ . '/../Views/seller/reset-password.php';
    }

    public function checkSKU() {
        AuthMiddleware::checkSellerAuth();
        header('Content-Type: application/json');
        
        $sku = $_GET['sku'] ?? '';
        
        if (empty($sku)) {
            echo json_encode(['exists' => false]);
            exit;
        }
        
        $result = $this->productService->checkSKUExists($sku);
        echo json_encode($result);
        exit;
    }
}