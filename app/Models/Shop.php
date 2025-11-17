<?php
class Shop {
    private $db;
    protected $id;
    // TODO: bo xung thuoc tinh con lai
    private $productModel;
    private $orderModel;
    public function __construct() {
        $conn = new Database();
        $this->db = $conn->getConnection();
    }

    public function insertShop($seller_id, $shop_name, $shop_description) {
        $stmt = $this->db->prepare("INSERT INTO shops (id, name, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $seller_id, $shop_name, $shop_description);
        if ($stmt->execute()) {
            return [
                'id' => $stmt->insert_id,
                'seller_id' => $seller_id,
                'name' => $shop_name,
                'description' => $shop_description
            ];
        } else {
            return false;
        }
    }

    public function getInfo ($shop_id) {
        return [
            'id'=> $shop_id,
            'name' => $this->getName($shop_id),
            'description'=> $this->getDescription($shop_id),
        ];
    }

    public function findShopBySellerId( $seller_id ) {
        $stmt = $this->db->prepare("SELECT * FROM shops WHERE sellers_id = ?");
        $stmt->bind_param("i", $seller_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getName($shop_id) {
        $stmt = $this->db->prepare('SELECT name FROM shops WHERE id = ?');
        $stmt->bind_param('i', $shop_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getDescription($shop_id) {
        $stmt = $this->db->prepare("SELECT description FROM shops WHERE id = ?");
        $stmt->bind_param('i', $shop_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}