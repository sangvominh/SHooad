<?php
require_once __DIR__ . '/../Services/User/AuthUserService.php';
require_once __DIR__ . '/../Services/User/UserPageService.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Services/FlashMessageService.php';

class UserController {
    private $authService;
    private $pageService;

    public function __construct() {
        $this->authService = new AuthUserService();
        $this->pageService = new UserPageService();
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
}
