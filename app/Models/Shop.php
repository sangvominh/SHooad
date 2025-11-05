<?php

class Shop {
    public $id;
    public $seller_id;
    public $name;
    public $address;
    public function __construct() {
    }

    public static function getShop($seller_id) {
        $conn = new Database();
        $db = $conn->getConnection();

        $stmt = $db->prepare("SELECT * FROM shops WHERE seller_id = ?");
        $stmt->bind_param("i", $seller_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
}