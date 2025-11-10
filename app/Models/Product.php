<?php
class Product {
    private $db;

    public function __construct() {
        $conn = new Database();
        $this->db = $conn->getConnection();
    }

    public function getProductByShop($shop_id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE shop_id = ?");
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProduct($product_id, $data) {
    //    TODO: implement product update logic
    }

}