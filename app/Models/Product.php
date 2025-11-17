<?php
class Product {
    private $db;

    public function __construct() {
        $conn = new Database();
        $this->db = $conn->getConnection();
    }

    public function getProductByShop($shop_id, $limit = null, $orderBy = null) {
        // Calculate total stock from product_variants if available, fallback to products.stock
        $sql = "SELECT p.*, 
                COALESCE(
                    (SELECT SUM(pv.stock) FROM product_variants pv WHERE pv.product_id = p.id),
                    p.stock
                ) as calculated_stock
                FROM products p 
                WHERE p.shop_id = ?";
        
        if ($orderBy === 'top_sold') {
            $sql .= " ORDER BY p.sold DESC";
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
        $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Replace stock with calculated_stock for display
        foreach ($products as &$product) {
            $product['stock'] = $product['calculated_stock'];
            unset($product['calculated_stock']);
        }
        
        return $products;
    }

    public function getProductById($id) {
        // Get product with calculated stock from variants
        $stmt = $this->db->prepare("
            SELECT p.*, 
            COALESCE(
                (SELECT SUM(pv.stock) FROM product_variants pv WHERE pv.product_id = p.id),
                p.stock
            ) as calculated_stock
            FROM products p 
            WHERE p.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        
        if ($product) {
            $product['stock'] = $product['calculated_stock'];
            unset($product['calculated_stock']);
        }
        
        return $product;
    }

    public function getProductImages(int $productId): array {
        $stmt = $this->db->prepare("SELECT * FROM product_images WHERE product_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createProduct(array $data): ?int {
        $fields = [];
        $placeholders = [];
        $values = [];
        $types = '';
        
        $allFields = ['shop_id', 'name', 'brand', 'description', 'price', 'original_price', 'stock', 'category_id', 'status'];
        
        foreach ($allFields as $field) {
            $value = $data[$field] ?? null;
            if ($value !== null) {
                $fields[] = $field;
                $placeholders[] = '?';
                $values[] = $value;
                
                if ($field === 'shop_id' || $field === 'stock' || $field === 'category_id') {
                    $types .= 'i';
                } elseif ($field === 'price' || $field === 'original_price') {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }
        }
        
        $sql = "INSERT INTO products (" . implode(', ', $fields) . ", sold) VALUES (" . implode(', ', $placeholders) . ", 0)";
        $stmt = $this->db->prepare($sql);
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
            if ($value === null) {
                $fields[] = "$key = NULL";
            } else {
                $fields[] = "$key = ?";
                $values[] = $value;
                $types .= is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
            }
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

    public function searchByName(string $query, int $limit = 8): array {
        $limit = max(1, min(20, (int)$limit));
        // Use prepared LIKE and subquery for the first image
        $sql = "SELECT p.id, p.name, p.price,
                       (SELECT pi.filename FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.id ASC LIMIT 1) AS image
                FROM products p
                WHERE p.status = 'active' AND p.name LIKE CONCAT('%', ?, '%')
                ORDER BY p.sold DESC, p.name ASC
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $query, $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Map to API-friendly payload
        $items = [];
        foreach ($rows as $row) {
            $items[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'price' => (float)$row['price'],
                'image' => $row['image'] ? ('/SHooad/public/assets/products/' . $row['image']) : null,
                'url' => '/SHooad/public/customer/product-detail?id=' . (int)$row['id']
            ];
        }
        return $items;
    }

    public function getProductVariants(int $productId): array {
        $stmt = $this->db->prepare("
            SELECT pv.*, c.name as color_name, c.hex_code, s.name as size_name
            FROM product_variants pv
            LEFT JOIN colors c ON pv.color_id = c.id
            LEFT JOIN sizes s ON pv.size_id = s.id
            WHERE pv.product_id = ?
            ORDER BY pv.id
        ");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createProductVariant(array $data): ?int {
        $fields = [];
        $placeholders = [];
        $values = [];
        $types = '';
        
        $allFields = ['product_id', 'color_id', 'size_id', 'sku', 'stock', 'price'];
        
        foreach ($allFields as $field) {
            $value = $data[$field] ?? null;
            $fields[] = $field;
            $placeholders[] = '?';
            $values[] = $value;
            
            if ($field === 'product_id' || $field === 'color_id' || $field === 'size_id' || $field === 'stock') {
                $types .= 'i';
            } elseif ($field === 'price') {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        
        $sql = "INSERT INTO product_variants (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        
        return null;
    }

    public function updateProductVariant(int $variantId, array $data): bool {
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

        $values[] = $variantId;
        $types .= 'i';

        $sql = "UPDATE product_variants SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }

    public function deleteProductVariant(int $variantId): bool {
        $stmt = $this->db->prepare("DELETE FROM product_variants WHERE id = ?");
        $stmt->bind_param("i", $variantId);
        return $stmt->execute();
    }

    public function getAllColors(): array {
        $stmt = $this->db->prepare("SELECT * FROM colors ORDER BY name");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllSizes(): array {
        $stmt = $this->db->prepare("SELECT * FROM sizes ORDER BY sort_order, name");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllCategories(): array {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}