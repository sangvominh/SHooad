<?php
require_once __DIR__ . '/../core/database.php';

class Shipper {
    public function __construct() {}

    public function getShipper($shipper_id){
        $conn = new Database();
        $db = $conn->getConnection();

        $stmt = $db->prepare("SELECT * FROM shipper_account WHERE id = ?");
        $stmt->bind_param("i", $shipper_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function getOrdersByShipper($shipper_id){
        $conn = new Database();
        $db = $conn->getConnection();

        $stmt = $db->prepare("
            SELECT o.*, s.name AS shop_name
            FROM orders o
            LEFT JOIN shops s ON o.shop_id = s.id
            WHERE o.shipper_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $shipper_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
