<?php
require_once __DIR__ . '/../../app/Core/Database.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function createOrder(array $data): int|false {
        $stmt = $this->db->prepare("INSERT INTO orders (shop_id, customer_id, status, total, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iisd s",
            $data['shop_id'],
            $data['customer_id'],
            $data['status'],
            $data['total'],
            $data['date']
        );
        return $stmt->execute() ? $this->db->insert_id : false;
    }

    public function getOrder(int $order_id): ?array {
        $stmt = $this->db->prepare("
            SELECT o.*, dc.name as delivery_company_name, dc.shipping_fee as delivery_company_fee
            FROM orders o
            LEFT JOIN delivery_companies dc ON o.delivery_company_id = dc.id
            WHERE o.id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: null;
    }

    public function getOrderByShop(int $shop_id, ?int $limit = null): array {
        $sql = "SELECT o.*, c.name as customer_name, c.email as customer_email, 
                       COALESCE(SUM(oi.price * oi.quantity), 0) as total_amount
                FROM orders o
                JOIN customers c ON o.customer_id = c.id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.shop_id = ?
                GROUP BY o.id
                ORDER BY o.date DESC";
        if ($limit !== null) {
            $sql .= " LIMIT ?";
        }
        
        $stmt = $this->db->prepare($sql);
        if ($limit !== null) {
            $stmt->bind_param("ii", $shop_id, $limit);
        } else {
            $stmt->bind_param("i", $shop_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateOrderStatus(int $order_id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        return $stmt->execute();
    }

    public function getOrdersByCustomer(int $customer_id, ?string $status = null): array {
        if ($status) {
            $sql = "SELECT o.*, s.name as shop_name 
                    FROM orders o 
                    LEFT JOIN shops s ON o.shop_id = s.id 
                    WHERE o.customer_id = ? AND o.status = ? 
                    ORDER BY o.date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("is", $customer_id, $status);
        } else {
            $sql = "SELECT o.*, s.name as shop_name 
                    FROM orders o 
                    LEFT JOIN shops s ON o.shop_id = s.id 
                    WHERE o.customer_id = ? 
                    ORDER BY o.date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $customer_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderStatusCounts(int $customer_id): array {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count
                FROM orders 
                WHERE customer_id = ?
                GROUP BY status";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $counts = [];
        while ($row = $result->fetch_assoc()) {
            $counts[$row['status']] = $row['count'];
        }
        
        return $counts;
    }
}
?>
