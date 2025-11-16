<?php
class Seller {
    private $db;
    private $name;
    private $email;
    // TODO: them cac thuoc tinh khac o day

    public function __construct() {
        $conn = new Database();
        $this->db = $conn->getConnection();
    }

    public function findEmail( $email ) {
        $stmt = $this->db->prepare("SELECT * FROM sellers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertSeller($email, $password, $name) {
        $stmt = $this->db->prepare("INSERT INTO sellers (email, password, name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $password, $name);
        if ($stmt->execute()) {
            return [
                'id' => $stmt->insert_id,
                'email' => $email,
                'name' => $name
            ];
        } else {
            return false;
        }
    }

    public function getInfo($seller_id) {
        return [
            'name' => $this->getName($seller_id),
            'email'=> $this->getEmail($seller_id),
        ];
    }
    
    public function getName( $seller_id ) {
        $stmt = $this->db->prepare("SELECT name FROM sellers WHERE id = ?");
        $stmt->bind_param('i', $seller_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['name'];
    }


    public function getEmail( $seller_id ) {
        $stmt = $this->db->prepare("SELECT email FROM sellers WHERE id = ?");
        $stmt->bind_param('i', $seller_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['email'];
    }
}