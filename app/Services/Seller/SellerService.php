<?php
require_once __DIR__ . '/../../Models/Seller.php';
require_once __DIR__ . '/../../Models/Shop.php';
require_once __DIR__ . '/../../Models/Order.php';
require_once __DIR__ . '/../../Models/Product.php';

class SellerService {
    private $sellerModel;
    private $shopModel;
    private $orderModel;
    private $productModel;

    public function __construct() {
        $this->sellerModel = new Seller();
        $this->shopModel = new Shop();
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }

    /**
     * Get dashboard data for seller
     */
    public function getDashboardData(int $seller_id, int $shop_id, bool $limit_for_dashboard = false): array {
        $orders = $limit_for_dashboard 
            ? $this->orderModel->getOrderByShop($shop_id, 5)
            : $this->orderModel->getOrderByShop($shop_id);
            
        $products = $limit_for_dashboard
            ? $this->productModel->getProductByShop($shop_id, 5, 'top_sold')
            : $this->productModel->getProductByShop($shop_id);
            
        return [
            'seller' => $this->sellerModel->getInfo($seller_id),
            'shop' => $this->shopModel->getInfo($shop_id),
            'orders' => $orders,
            'products' => $products
        ];
    }

    /**
     * Get seller info
     */
    public function getSellerInfo(int $seller_id): ?array {
        return $this->sellerModel->getInfo($seller_id);
    }

    /**
     * Get shop info
     */
    public function getShopInfo(int $shop_id): ?array {
        return $this->shopModel->getInfo($shop_id);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(int $shop_id): array {
        require_once __DIR__ . '/../../Core/Database.php';
        $db = (new Database())->getConnection();
        
        // Get total revenue (excluding cancelled and failed orders)
        $stmt = $db->prepare("
            SELECT COALESCE(SUM(oi.price * oi.quantity), 0) as total_revenue
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            WHERE o.shop_id = ? AND o.status NOT IN ('cancelled', 'failed')
        ");
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $revenueResult = $stmt->get_result()->fetch_assoc();
        $totalRevenue = (float)$revenueResult['total_revenue'];
        
        // Get total orders
        $stmt = $db->prepare("SELECT COUNT(*) as total_orders FROM orders WHERE shop_id = ?");
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $ordersResult = $stmt->get_result()->fetch_assoc();
        $totalOrders = (int)$ordersResult['total_orders'];
        
        // Get total products
        $stmt = $db->prepare("SELECT COUNT(*) as total_products FROM products WHERE shop_id = ?");
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $productsResult = $stmt->get_result()->fetch_assoc();
        $totalProducts = (int)$productsResult['total_products'];
        
        // Get pending orders count
        $stmt = $db->prepare("
            SELECT COUNT(*) as pending_orders 
            FROM orders 
            WHERE shop_id = ? AND status = 'pending'
        ");
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $pendingResult = $stmt->get_result()->fetch_assoc();
        $pendingOrders = (int)$pendingResult['pending_orders'];
        
        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'total_products' => $totalProducts,
            'pending_orders' => $pendingOrders
        ];
    }
}
