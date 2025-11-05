<?php
require_once __DIR__ . '/../../app/Core/Database.php';
class Order {
    public function __construct() {

    }

    public function getAllOrdersShop($shop_id): array {
        $conn = new Database();
        $db = $conn->getConnection();

        $stmt = $db->prepare("SELECT * FROM orders WHERE shop_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllOrders(): array {
        $conn = new Database();
        $db = $conn->getConnection();

        $stmt = $db->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrder($order_id): array {
        $conn = new Database();
        $db = $conn->getConnection();

        $stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result() ;
        return $result->fetch_assoc();
    }

    public function getOrderItems($order_id): array {
        $conn = new Database();
        $db = $conn->getConnection();

        $stmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateOrderStatus($order_id, $status): bool {
        $conn = new Database();
        $db = $conn->getConnection();

        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        return $stmt->execute();
    }
}

?>