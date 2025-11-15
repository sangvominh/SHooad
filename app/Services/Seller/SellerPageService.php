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

        include __DIR__ . '/../../Views/seller/pages/product-detail.php';
    }

    private function renderMainDashboard(array $data): void {
        $current_page = $_GET['page'] ?? 'dashboard';
        include __DIR__ . '/../../Views/seller/dashboard.php';
    }
}
