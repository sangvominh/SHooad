<?php
require_once __DIR__ . '/../Services/User/AuthUserService.php';
require_once __DIR__ . '/../Services/User/UserPageService.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Services/FlashMessageService.php';
require_once __DIR__ . '/../Services/ReviewService.php';

class UserController {
    private $authService;
    private $pageService;
    private $reviewService;

    public function __construct() {
        $this->authService = new AuthUserService();
        $this->pageService = new UserPageService();
        $this->reviewService = new ReviewService();
    }

    private function redirectTo(string $url): void {
        header("Location: $url");
        exit;
    }

    private function renderWithLayout(string $view, array $data, string $pageTitle = 'SHooad') {
        $bodyClass = $data['bodyClass'] ?? 'bg-white';
        $additionalScripts = $data['additionalScripts'] ?? '';
        $additionalStyles = $data['additionalStyles'] ?? '';
        
        ob_start();
        include __DIR__ . '/../Views/customer/' . $view . '.php';
        $content = ob_get_clean();
        
        include __DIR__ . '/../Views/customer/layout.php';
    }

    public function home() {
        $data = $this->pageService->getHomePageData();
        include __DIR__ . '/../Views/customer/home.php';
    }

    public function cart() {
        AuthMiddleware::checkUserAuth();
        $data = $this->pageService->getCartData();
        $this->renderWithLayout('cart', $data, 'Shopping Cart - SHooad');
    }

    public function products() {
        $data = $this->pageService->getProductsPageData();
        $this->renderWithLayout('products', $data, 'Products - SHooad');
    }

    public function productDetail() {
        $product_id = $_GET['id'] ?? null;
        
        if (!$product_id) {
            $this->redirectTo('/SHooad/public/customer');
        }

        $data = $this->pageService->getProductDetailPageData((int)$product_id);
        
        if (!$data || !isset($data['product'])) {
            $this->redirectTo('/SHooad/public/customer');
        }

        $this->renderWithLayout('product-detail', $data, ($data['product']['name'] ?? 'Product') . ' - SHooad');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->authService->register($name, $email, $password)) {
                $this->redirectTo('/SHooad/public/customer/login');
            }
        }
        
        include __DIR__ . '/../Views/customer/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->authService->login($email, $password)) {
                $this->redirectTo('/SHooad/public/customer');
            }
        }
        
        include __DIR__ . '/../Views/customer/login.php';
    }

    public function logout() {
        $this->authService->logout();
        include __DIR__ . '/../Views/customer/logout.php';
    }
    
    public function checkout() {
        AuthMiddleware::checkUserAuth();
        $data = $this->pageService->getCheckoutData();
        $this->renderWithLayout('checkout', $data, 'Checkout - SHooad');
    }
    
    public function shopDetail() {
        $shop_id = $_GET['shop_id'] ?? null;
        
        if (!$shop_id) {
            $this->redirectTo('/SHooad/public/customer');
        }

        $data = $this->pageService->getShopDetailData((int)$shop_id);
        
        if (!$data || !isset($data['shop'])) {
            $this->redirectTo('/SHooad/public/customer');
        }

        $this->renderWithLayout('shop-detail', $data, ($data['shop']['name'] ?? 'Shop') . ' - SHooad');
    }
    
    public function orderSuccess() {
        AuthMiddleware::checkUserAuth();
        include __DIR__ . '/../Views/customer/order-success.php';
    }

    public function profile() {
        AuthMiddleware::checkUserAuth();
        $data = $this->pageService->getProfileData();
        $this->renderWithLayout('profile', $data, 'My Profile - SHooad');
    }

    public function updateProfile() {
        AuthMiddleware::checkUserAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../Models/Customer.php';
            $customerModel = new Customer();
            
            $customerId = $_SESSION['customer_id'];
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            
            if (!empty($name) && !empty($email)) {
                $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
                
                // Check if email already exists for another user
                $checkStmt = $mysqli->prepare("SELECT id FROM customers WHERE email = ? AND id != ?");
                $checkStmt->bind_param("si", $email, $customerId);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                
                if ($result->num_rows > 0) {
                    FlashMessageService::setFlashMessage('error', 'Email already exists!');
                } else {
                    $stmt = $mysqli->prepare("UPDATE customers SET name = ?, email = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $name, $email, $customerId);
                    
                    if ($stmt->execute()) {
                        $_SESSION['customer_email'] = $email;
                        FlashMessageService::setFlashMessage('success', 'Profile updated successfully!');
                    } else {
                        FlashMessageService::setFlashMessage('error', 'Failed to update profile!');
                    }
                }
                
                $mysqli->close();
            }
        }
        
        $this->redirectTo('/SHooad/public/customer/profile');
    }

    public function addAddress() {
        AuthMiddleware::checkUserAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $_SESSION['customer_id'];
            $fullName = $_POST['full_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $isDefault = isset($_POST['is_default']) ? 1 : 0;
            
            if (!empty($fullName) && !empty($phone) && !empty($address)) {
                $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
                
                // If setting as default, unset other default addresses
                if ($isDefault) {
                    $mysqli->query("UPDATE customer_addresses SET is_default = 0 WHERE customer_id = " . $customerId);
                }
                
                $stmt = $mysqli->prepare("INSERT INTO customer_addresses (customer_id, full_name, phone, address, is_default) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("isssi", $customerId, $fullName, $phone, $address, $isDefault);
                
                if ($stmt->execute()) {
                    FlashMessageService::setFlashMessage('success', 'Address added successfully!');
                } else {
                    FlashMessageService::setFlashMessage('error', 'Failed to add address!');
                }
                
                $mysqli->close();
            }
        }
        
        $this->redirectTo('/SHooad/public/customer/profile');
    }

    public function changePassword() {
        AuthMiddleware::checkUserAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $_SESSION['customer_id'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmPassword)) {
                if ($newPassword !== $confirmPassword) {
                    FlashMessageService::setFlashMessage('error', 'New passwords do not match!');
                    $this->redirectTo('/SHooad/public/customer/profile');
                    return;
                }
                
                require_once __DIR__ . '/../Models/Customer.php';
                $customerModel = new Customer();
                $customer = $customerModel->findById($customerId);
                
                if ($customer && password_verify($currentPassword, $customer['password'])) {
                    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
                    
                    $stmt = $mysqli->prepare("UPDATE customers SET password = ? WHERE id = ?");
                    $stmt->bind_param("si", $newPasswordHash, $customerId);
                    
                    if ($stmt->execute()) {
                        FlashMessageService::setFlashMessage('success', 'Password changed successfully!');
                    } else {
                        FlashMessageService::setFlashMessage('error', 'Failed to change password!');
                    }
                    
                    $mysqli->close();
                } else {
                    FlashMessageService::setFlashMessage('error', 'Current password is incorrect!');
                }
            }
        }
        
        $this->redirectTo('/SHooad/public/customer/profile');
    }

    public function editAddress() {
        AuthMiddleware::checkUserAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $addressId = $_POST['address_id'] ?? '';
            $customerId = $_SESSION['customer_id'];
            $fullName = $_POST['full_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $isDefault = isset($_POST['is_default']) ? 1 : 0;
            
            if (!empty($addressId) && !empty($fullName) && !empty($phone) && !empty($address)) {
                $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
                
                // Verify address belongs to customer
                $checkStmt = $mysqli->prepare("SELECT id FROM customer_addresses WHERE id = ? AND customer_id = ?");
                $checkStmt->bind_param("ii", $addressId, $customerId);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                
                if ($result->num_rows > 0) {
                    // If setting as default, unset other default addresses
                    if ($isDefault) {
                        $mysqli->query("UPDATE customer_addresses SET is_default = 0 WHERE customer_id = " . $customerId);
                    }
                    
                    $stmt = $mysqli->prepare("UPDATE customer_addresses SET full_name = ?, phone = ?, address = ?, is_default = ? WHERE id = ? AND customer_id = ?");
                    $stmt->bind_param("sssiii", $fullName, $phone, $address, $isDefault, $addressId, $customerId);
                    
                    if ($stmt->execute()) {
                        FlashMessageService::setFlashMessage('success', 'Address updated successfully!');
                    } else {
                        FlashMessageService::setFlashMessage('error', 'Failed to update address!');
                    }
                } else {
                    FlashMessageService::setFlashMessage('error', 'Address not found!');
                }
                
                $mysqli->close();
            }
        }
        
        $this->redirectTo('/SHooad/public/customer/profile');
    }

    public function deleteAddress() {
        AuthMiddleware::checkUserAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $addressId = $_POST['address_id'] ?? '';
            $customerId = $_SESSION['customer_id'];
            
            if (!empty($addressId)) {
                $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
                
                $stmt = $mysqli->prepare("DELETE FROM customer_addresses WHERE id = ? AND customer_id = ?");
                $stmt->bind_param("ii", $addressId, $customerId);
                
                if ($stmt->execute()) {
                    FlashMessageService::setFlashMessage('success', 'Address deleted successfully!');
                } else {
                    FlashMessageService::setFlashMessage('error', 'Failed to delete address!');
                }
                
                $mysqli->close();
            }
        }
        
        $this->redirectTo('/SHooad/public/customer/profile');
    }

    public function orders() {
        AuthMiddleware::checkUserAuth();
        $data = $this->pageService->getOrdersPageData();
        $this->renderWithLayout('orders', $data, 'My Orders - SHooad');
    }

    public function orderDetail() {
        AuthMiddleware::checkUserAuth();
        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$orderId) {
            header('Location: /SHooad/public/customer/orders');
            exit;
        }

        require_once __DIR__ . '/../Services/OrderService.php';
        require_once __DIR__ . '/../Models/Order.php';
        require_once __DIR__ . '/../Models/OrderItem.php';
        
        $orderService = new OrderService();
        $orderModel = new Order();
        $orderItemModel = new OrderItem();
        
        $order = $orderModel->getOrder($orderId);
        
        // Verify order belongs to logged in customer
        if (!$order || $order['customer_id'] != $_SESSION['customer_id']) {
            header('Location: /SHooad/public/customer/orders');
            exit;
        }
        
        $orderItems = $orderItemModel->getOrderItemByOrderId($orderId);
        
        // Check review status for each product
        $reviewStatuses = [];
        foreach ($orderItems as $item) {
            $reviewStatuses[$item['product_id']] = $this->reviewService->canUserReviewProduct($_SESSION['customer_id'], $item['product_id']);
        }
        
        $data = [
            'order' => $order,
            'order_items' => $orderItems,
            'review_statuses' => $reviewStatuses
        ];
        
        $this->renderWithLayout('order-detail', $data, 'Order #' . $orderId . ' - SHooad');
    }

    public function cancelOrder() {
        AuthMiddleware::checkUserAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /SHooad/public/customer/orders');
            exit;
        }
        
        $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
        
        if (!$orderId) {
            FlashMessageService::setFlashMessage('error', 'Invalid order ID');
            header('Location: /SHooad/public/customer/orders');
            exit;
        }
        
        require_once __DIR__ . '/../Services/OrderService.php';
        require_once __DIR__ . '/../Models/Order.php';
        
        $orderService = new OrderService();
        $orderModel = new Order();
        $order = $orderModel->getOrder($orderId);
        
        // Verify order belongs to logged in customer
        if (!$order || $order['customer_id'] != $_SESSION['customer_id']) {
            FlashMessageService::setFlashMessage('error', 'Order not found');
            header('Location: /SHooad/public/customer/orders');
            exit;
        }
        
        // Only allow cancellation from pending or processing status
        if (!in_array($order['status'], ['pending', 'processing'])) {
            FlashMessageService::setFlashMessage('error', 'Cannot cancel order in ' . $order['status'] . ' status');
            header('Location: /SHooad/public/customer/order-detail?id=' . $orderId);
            exit;
        }
        
        // Update order status to cancelled
        if ($orderService->updateOrderStatus($orderId, 'cancelled')) {
            // Check if order was paid online - set refund flag for notification
            if (isset($order['payment_method']) && $order['payment_method'] === 'online' && 
                isset($order['payment_status']) && $order['payment_status'] === 'paid') {
                $_SESSION['show_refund_notice'] = true;
                $_SESSION['refund_order_id'] = $orderId;
            }
            FlashMessageService::setFlashMessage('success', 'Order cancelled successfully');
        } else {
            FlashMessageService::setFlashMessage('error', 'Failed to cancel order');
        }
        
        header('Location: /SHooad/public/customer/orders');
        exit;
    }

    // Realtime product search API (JSON)
    public function searchProducts() {
        header('Content-Type: application/json');
        $q = trim($_GET['q'] ?? '');
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
        if ($limit < 1 || $limit > 20) { $limit = 8; }

        if ($q === '' || mb_strlen($q) < 2) {
            echo json_encode([ 'items' => [] ]);
            return;
        }

        require_once __DIR__ . '/../Services/ProductService.php';
        $productService = new ProductService();
        $items = $productService->searchByName($q, $limit);
        echo json_encode([ 'items' => $items ]);
    }

    // Forgot Password - Step 1: Enter Email
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            
            if (empty($email)) {
                FlashMessageService::setFlashMessage('error', 'Please enter your email');
                header('Location: /SHooad/public/customer/forgot-password');
                exit;
            }
            
            // Check if email exists
            require_once __DIR__ . '/../Models/Customer.php';
            $customerModel = new Customer();
            $customer = $customerModel->findByEmail($email);
            
            if (!$customer) {
                FlashMessageService::setFlashMessage('error', 'Email not found in our system');
                header('Location: /SHooad/public/customer/forgot-password');
                exit;
            }
            
            // Email exists - redirect to reset password page
            header('Location: /SHooad/public/customer/reset-password?email=' . urlencode($email));
            exit;
        }
        
        // Show forgot password form
        require_once __DIR__ . '/../Views/customer/forgot-password.php';
    }

    // Reset Password - Step 2: Enter New Password
    public function resetPassword() {
        $email = $_GET['email'] ?? $_POST['email'] ?? '';
        
        if (empty($email)) {
            FlashMessageService::setFlashMessage('error', 'Invalid request');
            header('Location: /SHooad/public/customer/forgot-password');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate passwords
            if (empty($newPassword) || empty($confirmPassword)) {
                FlashMessageService::setFlashMessage('error', 'Please fill in all fields');
                header('Location: /SHooad/public/customer/reset-password?email=' . urlencode($email));
                exit;
            }
            
            if (strlen($newPassword) < 6) {
                FlashMessageService::setFlashMessage('error', 'Password must be at least 6 characters');
                header('Location: /SHooad/public/customer/reset-password?email=' . urlencode($email));
                exit;
            }
            
            if ($newPassword !== $confirmPassword) {
                FlashMessageService::setFlashMessage('error', 'Passwords do not match');
                header('Location: /SHooad/public/customer/reset-password?email=' . urlencode($email));
                exit;
            }
            
            // Update password
            require_once __DIR__ . '/../Models/Customer.php';
            require_once __DIR__ . '/../Core/Database.php';
            
            $db = (new Database())->getConnection();
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare("UPDATE customers SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            
            if ($stmt->execute()) {
                FlashMessageService::setFlashMessage('success', 'Password reset successfully! Please login with your new password');
                header('Location: /SHooad/public/customer/login');
            } else {
                FlashMessageService::setFlashMessage('error', 'Failed to reset password. Please try again');
                header('Location: /SHooad/public/customer/reset-password?email=' . urlencode($email));
            }
            exit;
        }
        
        // Show reset password form
        $data = ['email' => $email];
        require_once __DIR__ . '/../Views/customer/reset-password.php';
    }

    public function submitReview() {
        AuthMiddleware::checkUserAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $customerId = $_SESSION['customer_id'];
        $productId = $_POST['product_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = $_POST['comment'] ?? null;

        if (!$productId || !$rating) {
            FlashMessageService::setFlashMessage('error', 'Thiếu thông tin cần thiết.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $success = $this->reviewService->submitReview($customerId, $productId, $rating, $comment);
        
        if ($success) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        exit;
    }
}
