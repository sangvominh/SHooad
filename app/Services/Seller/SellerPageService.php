<?php
require_once __DIR__ . '/../OrderService.php';
require_once __DIR__ . '/../ProductService.php';
require_once __DIR__ . '/SellerDashboardService.php';

class SellerPageService {
    private $orderService;
    private $productService;
    private $dashboardService;

    public function __construct() {
        $this->orderService = new OrderService();
        $this->productService = new ProductService();
        $this->dashboardService = new SellerDashboardService();
    }

    public function renderDashboard(): void {
        $data = $this->dashboardService->getDashboardData();
        $page = $_GET['page'] ?? 'dashboard';

        switch ($page) {
            case 'order-detail':
                $this->renderOrderDetail($data);
                break;
            case 'product-detail':
                $this->renderProductDetail($data);
                break;
            case 'add-product':
                $this->renderAddProduct($data);
                break;
            default:
                $this->renderMainDashboard($data);
                break;
        }
    }

    public function handleOrderUpdate(int $orderId, string $status): string {
        $this->orderService->updateOrderStatus($orderId, $status);
        return "/SHooad/public/seller/dashboard?page=order-detail&order_id=$orderId";
    }

    public function handleProductUpdate(int $productId, string $status): string {
        $this->productService->updateProductStatus($productId, $status);
        return "/SHooad/public/seller/dashboard?page=product-detail&product_id=$productId";
    }

    private function renderOrderDetail(array $data): void {
        $order_id = $_GET['order_id'] ?? null;
        if (!$order_id) {
            header('Location: /SHooad/public/seller/dashboard');
            exit;
        }

        $orderData = $this->orderService->getOrderWithItems($order_id);
        $order = $orderData['order'];
        $order_items = $orderData['items'];

        include __DIR__ . '/../../Views/seller/pages/order-detail.php';
    }

    private function renderProductDetail(array $data): void {
        $product_id = $_GET['product_id'] ?? null;
        if (!$product_id) {
            header('Location: /SHooad/public/seller/dashboard');
            exit;
        }

        $product = $this->productService->getProductById($product_id);
        $product['images'] = $this->productService->getProductImages($product_id);

        // Handle product update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
            $this->handleProductUpdateForm($product_id);
        }

        // Handle image deletion
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image_id'])) {
            $this->productService->deleteProductImage($_POST['delete_image_id']);
            header("Location: ?page=product-detail&product_id=$product_id");
            exit;
        }

        include __DIR__ . '/../../Views/seller/pages/product-detail.php';
    }

    private function renderAddProduct(array $data): void {
        // Handle product creation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleProductCreation();
        }

        include __DIR__ . '/../../Views/seller/pages/add-product.php';
    }

    private function handleProductCreation(): void {
        $shop_id = $_POST['shop_id'] ?? $_SESSION['shop_id'];
        
        $productData = [
            'shop_id' => $shop_id,
            'name' => $_POST['name'] ?? '',
            'brand' => $_POST['brand'] ?? null,
            'description' => $_POST['description'] ?? '',
            'colors' => $_POST['colors'] ?? '',
            'sizes' => $_POST['sizes'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'original_price' => $_POST['original_price'] ?? 0,
            'stock' => $_POST['stock'] ?? 0,
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'active'
        ];

        $product_id = $this->productService->createProduct($productData);
        
        if ($product_id && !empty($_FILES['images']['name'][0])) {
            $this->productService->uploadProductImages($product_id, $_FILES['images']);
        }

        header('Location: ?page=products');
        exit;
    }

    private function handleProductUpdateForm(int $product_id): void {
        $updateData = [
            'name' => $_POST['product_name'] ?? '',
            'brand' => $_POST['brand'] ?? null,
            'description' => $_POST['description'] ?? '',
            'colors' => $_POST['colors'] ?? '',
            'sizes' => $_POST['sizes'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'original_price' => $_POST['original_price'] ?? 0,
            'stock' => $_POST['stock'] ?? 0,
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'active'
        ];

        $this->productService->updateProduct($product_id, $updateData);

        if (!empty($_FILES['new_images']['name'][0])) {
            $this->productService->uploadProductImages($product_id, $_FILES['new_images']);
        }

        header("Location: ?page=product-detail&product_id=$product_id");
        exit;
    }

    private function renderMainDashboard(array $data): void {
        $current_page = $_GET['page'] ?? 'dashboard';
        include __DIR__ . '/../../Views/seller/dashboard.php';
    }
}
