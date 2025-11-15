<?php
class Product {
    private $db;

    public function __construct() {
        $conn = new Database();
        $this->db = $conn->getConnection();
    }

    public function getProductByShop($shop_id, $limit = null, $orderBy = null) {
        $sql = "SELECT * FROM products WHERE shop_id = ?";
        
        if ($orderBy === 'top_sold') {
            $sql .= " ORDER BY sold DESC";
        }
        
        if ($limit !== null) {
            $sql .= " LIMIT ?";
        }
        
        $stmt = $this->db->prepare($sql);
        if ($limit !== null) {
            $stmt->bind_param("ii", $shop_id, $limit);
        } else {
            $stmt->bind_param("i", $shop_id);
        }
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getProductImages(int $productId): array {
        $stmt = $this->db->prepare("SELECT * FROM product_images WHERE product_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createProduct(array $data): ?int {
        $fields = ['shop_id', 'name', 'brand', 'description', 'colors', 'sizes', 'price', 'original_price', 'stock', 'category_id', 'status'];
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO products (" . implode(', ', $fields) . ", sold) VALUES (" . implode(', ', $placeholders) . ", 0)";
        $stmt = $this->db->prepare($sql);
        
        $values = [];
        $types = '';
        foreach ($fields as $field) {
            $value = $data[$field] ?? null;
            $values[] = $value;
            
            if ($field === 'shop_id' || $field === 'stock' || $field === 'category_id') {
                $types .= 'i';
            } elseif ($field === 'price' || $field === 'original_price') {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        
        $stmt->bind_param($types, ...$values);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        
        return null;
    }

    public function updateProduct($product_id, $data): bool {
        if (empty($data)) {
            return false;
        }

        $fields = [];
        $values = [];
        $types = '';

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
            $types .= is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
        }

        $values[] = $product_id;
        $types .= 'i';

        $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }

    public function addProductImage(int $productId, string $filename): bool {
        $stmt = $this->db->prepare("INSERT INTO product_images (product_id, filename) VALUES (?, ?)");
        $stmt->bind_param("is", $productId, $filename);
        return $stmt->execute();
    }

    public function deleteProductImage(int $imageId): bool {
        // Get filename first to delete the file
        $stmt = $this->db->prepare("SELECT filename FROM product_images WHERE id = ?");
        $stmt->bind_param("i", $imageId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result) {
            // Delete from seller's private folder
            $filepath = __DIR__ . '/../../database/seller_uploads/products/' . $result['filename'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            
            // Delete from database
            $stmt = $this->db->prepare("DELETE FROM product_images WHERE id = ?");
            $stmt->bind_param("i", $imageId);
            return $stmt->execute();
        }
        
        return false;
    }

}