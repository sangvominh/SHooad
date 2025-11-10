<?php
require_once __DIR__ . '/../../app/Core/Database.php';

class OrderItem {
    private $db;

    public function __construct() {
        $conn = new Database();
        $this->db = $conn->getConnection();
    }

    public function getOrderItemByOrderId(int $order_id): array {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
