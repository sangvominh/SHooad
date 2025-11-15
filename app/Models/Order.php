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
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: null;
    }

    public function getOrderByShop(int $shop_id): array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE shop_id = ? ORDER BY date DESC");
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateOrderStatus(int $order_id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        return $stmt->execute();
    }
}
?>
