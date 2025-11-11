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
                    window.location.href = '/SHooad/app/Views/user/login.php';
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

            $user = $this->userModel->login($email, $password);
            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: /SHooad/public/user');
                exit;
            } else {
                echo "<script>alert('Sai email hoặc mật khẩu!');</script>";
                require_once __DIR__ . '/../Views/user/login.php';
            }
        } else {
            require_once __DIR__ . '/../Views/user/login.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /user/login.php');
    }
}
