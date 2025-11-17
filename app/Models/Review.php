<?php
require_once __DIR__ . '/../Core/Database.php';

class Review {
    private $db;

    public function __construct() {
        $conn = new Database();
        $this->db = $conn->getConnection();
    }

    public function create($productId, $customerId, $rating, $comment = null) {
        $stmt = $this->db->prepare("INSERT INTO reviews (product_id, customer_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $productId, $customerId, $rating, $comment);
        return $stmt->execute();
    }

    public function getByProductId($productId) {
        $stmt = $this->db->prepare("
            SELECT r.*, c.name as customer_name
            FROM reviews r
            JOIN customers c ON r.customer_id = c.id
            WHERE r.product_id = ? AND c.status = 'active'
            ORDER BY r.created_at DESC
        ");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAverageRating($productId) {
        $stmt = $this->db->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return [
            'avg_rating' => $row['avg_rating'] ? round($row['avg_rating'], 1) : 0,
            'total_reviews' => (int)$row['total_reviews']
        ];
    }

    public function hasUserReviewedProduct($customerId, $productId) {
        $stmt = $this->db->prepare("SELECT id FROM reviews WHERE customer_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $customerId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function canUserReviewProduct($customerId, $productId) {
        // Check if user has completed orders containing this product
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as order_count FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            WHERE o.customer_id = ? AND oi.product_id = ? AND o.status = 'completed'
        ");
        $stmt->bind_param("ii", $customerId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['order_count'] > 0;
    }
}