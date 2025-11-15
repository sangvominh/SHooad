<?php
require_once __DIR__ . '/../Core/Database.php';

class Customer {
    private $db;

    public function __construct() {
        $conn = new Database();
        $this->db = $conn->getConnection();
    }

    public function register($name, $email, $password) {
        // Check if the email already exists
        $checkStmt = $this->db->prepare("SELECT * FROM customers WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        if ($result->num_rows > 0) {
            return false; // Email already exists
        }
        
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO customers (name, email, password, status) VALUES (?, ?, ?, 'active')");
        $stmt->bind_param("sss", $name, $email, $passwordHash);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = ? AND status = 'active'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        if ($customer && password_verify($password, $customer['password'])) {
            return $customer; // Return customer data including id
        }
        return false;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = ? AND status = 'active'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
