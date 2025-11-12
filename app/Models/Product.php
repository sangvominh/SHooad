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
        $sql = "UPDATE products SET 
                    sku = ?, 
                    name = ?, 
                    description = ?, 
                    thumbnail_url = ?, 
                    colors = ?, 
                    sizes = ?, 
                    price = ?, 
                    original_price = ?, 
                    stock = ?, 
                    sold_quantity = ?, 
                    status = ? 
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        $sku = $data['sku'] ?? null;
        $name = $data['name'] ?? null;
        $description = $data['description'] ?? null;
        $thumbnail_url = $data['thumbnail_url'] ?? null;
        $colors = $data['colors'] ?? null;
        $sizes = $data['sizes'] ?? null;
        $price = $data['price'] ?? null;
        $original_price = $data['original_price'] ?? null;
        $stock = $data['stock'] ?? null;
        $sold_quantity = $data['sold_quantity'] ?? null;
        $status = $data['status'] ?? null;

        $stmt->bind_param(
            "sssssssddiis", 
            $sku, $name, $description, $thumbnail_url, $colors, $sizes, 
            $price, $original_price, $stock, $sold_quantity, $status, $product_id
        );

        return $stmt->execute();
    }

    public function insertProduct($data) {
        $stmt = $this->db->prepare("INSERT INTO products (shop_id, category_id, sku, name, description, thumbnail_url, colors, sizes, price, original_price, stock, status, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param(
            "iissssssddss",
            $data['shop_id'],
            $data['category_id'],
            $data['sku'],
            $data['name'],
            $data['description'],
            $data['thumbnail_url'],
            $data['colors'],
            $data['sizes'],
            $data['price'],
            $data['original_price'],
            $data['stock'],
            $data['status']
        );
        return $stmt->execute();
    }

}