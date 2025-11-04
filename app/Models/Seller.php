<?php
class Seller
{
    public function __construct() {}

    public function getSellers($seller_id){
        $conn = new Database();
        $db = $conn->getConnection();

        $stmt = $db->prepare("SELECT * FROM seller_account WHERE id = ?");
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
