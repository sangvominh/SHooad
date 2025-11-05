<?php
require_once __DIR__ . '/../Models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->userModel->register($name, $email, $password)) {
                // Hiển thị thông báo thành công
                echo "<script>
                    alert('Đăng ký thành công! Hãy đăng nhập để tiếp tục.');
                    window.location.href = '/user/login';
                </script>";
                exit;
            } else {
                echo "<script>alert('Đăng ký thất bại, vui lòng thử lại!');</script>";
                require_once __DIR__ . '/../Views/user/register.php';
            }
        } else {
            require_once __DIR__ . '/../Views/user/register.php';
        }
    }


    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->userModel->login($email, $password)) {
                session_start();
                $_SESSION['user'] = $email;
                header('Location: /');
                exit;
            } else {
                echo "Sai email hoặc mật khẩu!";
            }
        } else {
            require_once __DIR__ . '/../Views/user/login.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /user/login');
    }
}
