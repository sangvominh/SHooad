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
}
