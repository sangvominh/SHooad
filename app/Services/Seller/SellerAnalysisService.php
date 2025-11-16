<?php
require_once __DIR__ . '/../../Models/Order.php';
require_once __DIR__ . '/../../Models/Product.php';
require_once __DIR__ . '/../../Core/Database.php';

class SellerAnalysisService {
    private $db;
    private $orderModel;
    private $productModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }

    /**
     * Get orders analysis data for a shop
     */
    public function getOrdersAnalysis(int $shop_id): array {
        return [
            'stats' => $this->getOrderStats($shop_id),
            'charts' => [
                'revenue_by_date' => $this->getRevenueByDate($shop_id),
                'top_products' => $this->getTopSellingProducts($shop_id, 5)
            ],
            'orders' => $this->getOrdersList($shop_id)
        ];
    }

    /**
     * Get products analysis data for a shop
     */
    public function getProductsAnalysis(int $shop_id): array {
        return [
            'stats' => $this->getProductStats($shop_id),
            'charts' => [
                'top_products' => $this->getTopSellingProducts($shop_id, 5),
                'revenue_by_category' => $this->getRevenueByCategory($shop_id)
            ],
            'products' => $this->getProductsList($shop_id)
        ];
    }

    /**
     * Get order statistics
     */
    private function getOrderStats(int $shop_id): array {
        $sql = "
            SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status NOT IN ('cancelled', 'failed') THEN 
                    (SELECT SUM(price * quantity) FROM order_items WHERE order_id = orders.id)
                ELSE 0 END) as total_revenue,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = 'delivering' THEN 1 ELSE 0 END) as delivering,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
            FROM orders 
            WHERE shop_id = ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return [
            'total_orders' => (int)$result['total_orders'],
            'total_revenue' => (float)($result['total_revenue'] ?? 0),
            'by_status' => [
                'pending' => (int)$result['pending'],
                'processing' => (int)$result['processing'],
                'delivering' => (int)$result['delivering'],
                'completed' => (int)$result['completed'],
                'cancelled' => (int)$result['cancelled'],
                'failed' => (int)$result['failed']
            ]
        ];
    }

    /**
     * Get product statistics
     */
    private function getProductStats(int $shop_id): array {
        $sql = "
            SELECT 
                COUNT(*) as total_products,
                SUM(stock < 10) as low_stock_count,
                COALESCE(SUM(
                    (SELECT SUM(oi.price * oi.quantity) 
                     FROM order_items oi 
                     JOIN orders o ON oi.order_id = o.id 
                     WHERE oi.product_id = products.id 
                     AND o.status NOT IN ('cancelled', 'failed'))
                ), 0) as total_revenue
            FROM products 
            WHERE shop_id = ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return [
            'total_products' => (int)$result['total_products'],
            'total_revenue' => (float)$result['total_revenue'],
            'low_stock' => (int)$result['low_stock_count']
        ];
    }

    /**
     * Get revenue by date (last 30 days)
     */
    private function getRevenueByDate(int $shop_id): array {
        $sql = "
            SELECT 
                DATE(o.date) as order_date,
                SUM(oi.price * oi.quantity) as daily_revenue
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            WHERE o.shop_id = ? 
            AND o.status NOT IN ('cancelled', 'failed')
            AND o.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY DATE(o.date)
            ORDER BY order_date ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'date' => $row['order_date'],
                'revenue' => (float)$row['daily_revenue']
            ];
        }
        
        return $data;
    }

    /**
     * Get top selling products
     */
    private function getTopSellingProducts(int $shop_id, int $limit = 5): array {
        $sql = "
            SELECT 
                p.id,
                p.name,
                SUM(oi.quantity) as total_quantity,
                SUM(oi.price * oi.quantity) as total_revenue
            FROM products p
            JOIN order_items oi ON p.id = oi.product_id
            JOIN orders o ON oi.order_id = o.id
            WHERE p.shop_id = ? 
            AND o.status NOT IN ('cancelled', 'failed')
            GROUP BY p.id, p.name
            ORDER BY total_quantity DESC
            LIMIT ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $shop_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'quantity' => (int)$row['total_quantity'],
                'revenue' => (float)$row['total_revenue']
            ];
        }
        
        return $data;
    }

    /**
     * Get revenue by category
     */
    private function getRevenueByCategory(int $shop_id): array {
        $sql = "
            SELECT 
                c.name as category_name,
                SUM(oi.price * oi.quantity) as total_revenue
            FROM products p
            JOIN categories c ON p.category_id = c.id
            JOIN order_items oi ON p.id = oi.product_id
            JOIN orders o ON oi.order_id = o.id
            WHERE p.shop_id = ? 
            AND o.status NOT IN ('cancelled', 'failed')
            GROUP BY c.id, c.name
            ORDER BY total_revenue DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'category' => $row['category_name'],
                'revenue' => (float)$row['total_revenue']
            ];
        }
        
        return $data;
    }

    /**
     * Get orders list with customer info
     */
    private function getOrdersList(int $shop_id): array {
        $sql = "
            SELECT 
                o.id as order_id,
                c.name as customer_name,
                o.date,
                o.status,
                COALESCE((SELECT SUM(price * quantity) FROM order_items WHERE order_id = o.id), 0) as total
            FROM orders o
            JOIN customers c ON o.customer_id = c.id
            WHERE o.shop_id = ?
            ORDER BY o.date DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'order_id' => (int)$row['order_id'],
                'customer' => $row['customer_name'],
                'date' => $row['date'],
                'status' => $row['status'],
                'total' => (float)$row['total']
            ];
        }
        
        return $data;
    }

    /**
     * Get products list with stats
     */
    private function getProductsList(int $shop_id): array {
        $sql = "
            SELECT 
                p.id as product_id,
                p.name,
                c.name as category,
                COALESCE((SELECT SUM(oi.quantity) 
                         FROM order_items oi 
                         JOIN orders o ON oi.order_id = o.id 
                         WHERE oi.product_id = p.id 
                         AND o.status NOT IN ('cancelled', 'failed')), 0) as sold,
                COALESCE((SELECT SUM(pv.stock) FROM product_variants pv WHERE pv.product_id = p.id), p.stock) as total_stock,
                COALESCE((SELECT AVG(rating) FROM reviews WHERE product_id = p.id), 0) as avg_rating
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.shop_id = ?
            ORDER BY sold DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'product_id' => (int)$row['product_id'],
                'name' => $row['name'],
                'category' => $row['category'] ?? 'Uncategorized',
                'sold' => (int)$row['sold'],
                'stock' => (int)$row['total_stock'],
                'avg_rating' => round((float)$row['avg_rating'], 2)
            ];
        }
        
        return $data;
    }
}
