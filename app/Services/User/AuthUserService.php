<?php
require_once __DIR__ . '/../../Models/Customer.php';
require_once __DIR__ . '/../AuthService.php';
require_once __DIR__ . '/../FlashMessageService.php';

class AuthUserService {
    private $customerModel;

    public function __construct() {
        $this->customerModel = new Customer();
    }

    public function login(string $email, string $password): bool {
        // Validation
        if (empty($email) || empty($password)) {
            FlashMessageService::setFlashMessage('error', 'Vui lòng nhập email và mật khẩu.');
            return false;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            FlashMessageService::setFlashMessage('error', 'Email không hợp lệ.');
            return false;
        }
        
        $customer = $this->customerModel->login($email, $password);
        
        if ($customer) {
            $this->setCustomerSession($customer);
            FlashMessageService::setFlashMessage('success', 'Đăng nhập thành công!');
            return true;
        }

        FlashMessageService::setFlashMessage('error', 'Email hoặc mật khẩu không đúng.');
        return false;
    }

    public function register(string $name, string $email, string $password): bool {
        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            FlashMessageService::setFlashMessage('error', 'Tất cả các trường đều bắt buộc.');
            return false;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            FlashMessageService::setFlashMessage('error', 'Email không hợp lệ.');
            return false;
        }
        
        if (strlen($password) < 6) {
            FlashMessageService::setFlashMessage('error', 'Mật khẩu phải có ít nhất 6 ký tự.');
            return false;
        }
        
        $result = $this->customerModel->register($name, $email, $password);
        
        if ($result) {
            FlashMessageService::setFlashMessage('success', 'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.');
            return true;
        }

        FlashMessageService::setFlashMessage('error', 'Email đã tồn tại. Vui lòng sử dụng email khác.');
        return false;
    }

    public function logout(): void {
        AuthService::logout();
    }

    private function setCustomerSession(array $customer): void {
        $_SESSION['customer_id'] = $customer['id'];
        $_SESSION['customer_email'] = $customer['email'];
        $_SESSION['customer_name'] = $customer['name'];
    }

    public function getCustomerSession(): array {
        return [
            'customer_id' => $_SESSION['customer_id'] ?? null,
            'customer_email' => $_SESSION['customer_email'] ?? null,
            'customer_name' => $_SESSION['customer_name'] ?? null
        ];
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['customer_id']);
    }
}
