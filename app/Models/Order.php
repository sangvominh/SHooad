<?php
require_once __DIR__ . '/../../app/Core/Database.php';
class Order {
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAllOrders(): array {
        $stmt = $this->conn->query("SELECT * FROM orders");
        return $stmt->fetch_all(MYSQLI_ASSOC);
    }
}

?>