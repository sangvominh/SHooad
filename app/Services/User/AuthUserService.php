<?php
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../AuthService.php';
require_once __DIR__ . '/../FlashMessageService.php';

class AuthUserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login(string $email, string $password): bool {
        $user = $this->userModel->login($email, $password);
        
        if ($user) {
            $this->setUserSession($user);
            return true;
        }

        FlashMessageService::setFlashMessage('error', 'Invalid email or password.');
        return false;
    }

    public function register(string $name, string $email, string $password): bool {
        $result = $this->userModel->register($name, $email, $password);
        
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

    private function setUserSession(array $user): void {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
    }

    public function getUserSession(): array {
        return [
            'user_id' => $_SESSION['user_id'] ?? null,
            'user' => $_SESSION['user'] ?? null,
            'user_name' => $_SESSION['user_name'] ?? null
        ];
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }
}
