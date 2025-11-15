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

    public function home() {
        $data = $this->pageService->getHomePageData();
        include __DIR__ . '/../Views/customer/home.php';
    }

    public function cart() {
        AuthMiddleware::checkUserAuth();
        $data = $this->pageService->getCartData();
        include __DIR__ . '/../Views/customer/cart.php';
    }

    public function products() {
        $data = $this->pageService->getProductsPageData();
        include __DIR__ . '/../Views/customer/products.php';
    }

    public function productDetail() {
        $product_id = $_GET['id'] ?? null;
        
        if (!$product_id) {
            $this->redirectTo('/SHooad/public/customer');
        }

        $product = $this->pageService->getProductDetailData((int)$product_id);
        
        if (!$product) {
            $this->redirectTo('/SHooad/public/customer');
        }

        include __DIR__ . '/../Views/customer/product-detail.php';
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
}
