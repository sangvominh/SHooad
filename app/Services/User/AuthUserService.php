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
        $customer = $this->customerModel->login($email, $password);
        
        if ($customer) {
            $this->setCustomerSession($customer);
            return true;
        }

        FlashMessageService::setFlashMessage('error', 'Invalid email or password.');
        return false;
    }

    public function register(string $name, string $email, string $password): bool {
        $result = $this->customerModel->register($name, $email, $password);
        
        if ($result) {
            FlashMessageService::setFlashMessage('success', 'Registration successful! Please login to continue.');
            return true;
        }

        FlashMessageService::setFlashMessage('error', 'Registration failed. Please try again.');
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
