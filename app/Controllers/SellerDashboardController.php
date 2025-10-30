<?php
// app/Controllers/SellerDashboardController.php

declare(strict_types=1);

class SellerDashboardController
{
    private Seller $sellerModel;
    private Product $productModel;
    private Order $orderModel;

    public function __construct(private PDO $db)
    {
        $this->sellerModel = new Seller($db);
        $this->productModel = new Product($db);
        $this->orderModel = new Order($db);
    }

    public function index(): void
    {
        $start = microtime(true);
        // Authentication check
        if (!isset($_SESSION['seller_id'])) {
            header('Location: /seller/login');
            exit;
        }
        $sellerId = (int)$_SESSION['seller_id'];

        try {
            $seller = $this->sellerModel->findById($sellerId);
            if (!$seller) {
                throw new RuntimeException('Seller not found or inactive');
            }

            // Generate CSRF token for actions
            SecurityHelper::generateCsrfToken();

            $statistics = $this->getStatistics($sellerId);
            $recentProducts = $this->productModel->getRecentActiveProducts($sellerId, 20);
            $totalProducts = $this->productModel->getTotalCount($sellerId);

            $executionTime = microtime(true) - $start;
            error_log(sprintf('Dashboard load time for seller %d: %.3f sec', $sellerId, $executionTime));

            // Pass data to view
            $data = [
                'seller' => $seller,
                'statistics' => $statistics,
                'recentProducts' => $recentProducts,
                'totalProducts' => $totalProducts,
                'currentSection' => 'dashboard',
                'basePath' => $this->getBasePath(),
            ];

            // Render view
            extract($data, EXTR_SKIP);
            require __DIR__ . '/../Views/seller/dashboard.php';
        } catch (Throwable $e) {
            error_log('Dashboard error: ' . $e->getMessage());
            http_response_code(500);
            echo '<h1>Unable to load dashboard. Please try again later.</h1>';
        }
    }

    private function getStatistics(int $sellerId): array
    {
        return [
            'total_products' => $this->productModel->getTotalCount($sellerId),
            'active_listings' => $this->productModel->getActiveCount($sellerId),
            'monthly_sales' => $this->orderModel->getMonthlySalesCount($sellerId),
            'monthly_revenue' => $this->orderModel->getMonthlyRevenue($sellerId),
        ];
    }

    private function getBasePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = str_replace('\\', '/', rtrim(dirname($scriptName), '/\\'));
        // For DocumentRoot = public, $dir will be '/'. We want empty base in that case.
        return ($dir === '/' || $dir === '\\') ? '' : $dir;
    }

    public function pauseProduct($productId): void
    {
        if (!isset($_SESSION['seller_id'])) {
            header('Location: /seller/login');
            exit;
        }
        $sellerId = (int)$_SESSION['seller_id'];
        try {
            if (!SecurityHelper::validateCsrfToken($_POST['csrf_token'] ?? null)) {
                throw new RuntimeException('Invalid CSRF token');
            }
            $pid = filter_var($productId, FILTER_VALIDATE_INT);
            if ($pid === false) {
                throw new InvalidArgumentException('Invalid product ID');
            }
            if ($this->productModel->pause((int)$pid, $sellerId)) {
                $_SESSION['flash_message'] = 'Product paused successfully';
            } else {
                $_SESSION['flash_error'] = 'Failed to pause product';
            }
        } catch (Throwable $e) {
            error_log('Pause product error: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Failed to pause product';
        }
        header('Location: /seller/dashboard');
        exit;
    }

    public function deleteProduct($productId): void
    {
        if (!isset($_SESSION['seller_id'])) {
            header('Location: /seller/login');
            exit;
        }
        $sellerId = (int)$_SESSION['seller_id'];
        try {
            if (!SecurityHelper::validateCsrfToken($_POST['csrf_token'] ?? null)) {
                throw new RuntimeException('Invalid CSRF token');
            }
            $pid = filter_var($productId, FILTER_VALIDATE_INT);
            if ($pid === false) {
                throw new InvalidArgumentException('Invalid product ID');
            }
            $confirmName = isset($_POST['confirm_name']) ? (string)$_POST['confirm_name'] : '';
            if ($this->productModel->delete((int)$pid, $sellerId, $confirmName)) {
                $_SESSION['flash_message'] = 'Product deleted successfully';
            }
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        } catch (Throwable $e) {
            error_log('Delete product error: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Failed to delete product. Please try again.';
        }
        header('Location: /seller/dashboard');
        exit;
    }
}
