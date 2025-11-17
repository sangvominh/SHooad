<?php
require_once __DIR__ . '/../Models/Product.php';

class ProductService {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function getProductById(int $productId): ?array {
        return $this->productModel->getProductById($productId);
    }

    public function getProductImages(int $productId): array {
        return $this->productModel->getProductImages($productId);
    }

    public function createProduct(array $data): ?int {
        return $this->productModel->createProduct($data);
    }

    /**
     * Create product with images - Business logic layer
     */
    public function createProductWithImages(int $shop_id, array $postData, array $files): ?int {
        // Prepare product data
        $productData = [
            'shop_id' => $shop_id,
            'name' => $postData['name'] ?? '',
            'brand' => $postData['brand'] ?? null,
            'description' => $postData['description'] ?? '',
            'price' => $postData['price'] ?? 0,
            'original_price' => $postData['original_price'] ?? 0,
            'stock' => $postData['stock'] ?? 0,
            'category_id' => $postData['category_id'] ?? null,
            'status' => $postData['status'] ?? 'active'
        ];

        // Create product
        $product_id = $this->productModel->createProduct($productData);
        
        // Upload images if product created successfully
        if ($product_id && !empty($files['name'][0])) {
            $this->uploadProductImages($product_id, $files);
        }

        return $product_id;
    }

    /**
     * Update product - Business logic layer
     */
    public function updateProduct(int $productId, array $postData): bool {
        $updateData = [
            'name' => $postData['product_name'] ?? '',
            'brand' => $postData['brand'] ?? null,
            'description' => $postData['description'] ?? '',
            'price' => $postData['price'] ?? 0,
            'original_price' => $postData['original_price'] ?? 0,
            'stock' => $postData['stock'] ?? 0,
            'category_id' => !empty($postData['category_id']) ? $postData['category_id'] : null,
            'status' => $postData['status'] ?? 'active'
        ];

        return $this->productModel->updateProduct($productId, $updateData);
    }

    public function updateProductStatus(int $productId, string $newStatus): bool {
        return $this->productModel->updateProduct($productId, ['status' => $newStatus]);
    }

    public function deleteProductImage(int $imageId): bool {
        return $this->productModel->deleteProductImage($imageId);
    }

    public function uploadProductImages(int $productId, array $files): bool {
        // Upload to public assets folder for customer access
        $uploadDir = __DIR__ . '/../../public/assets/products/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $success = true;
        $fileCount = count($files['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$i];
                $originalName = basename($files['name'][$i]);
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                
                // Validate image type
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array(strtolower($extension), $allowedTypes)) {
                    continue;
                }
                
                $filename = uniqid() . '_' . time() . '.' . $extension;
                $destination = $uploadDir . $filename;

                if (move_uploaded_file($tmpName, $destination)) {
                    // Save to database
                    if (!$this->productModel->addProductImage($productId, $filename)) {
                        $success = false;
                    }
                } else {
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * Get product colors from database
     */
    public function getProductColors(int $productId): array {
        $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
        
        if ($mysqli->connect_error) {
            return [];
        }
        
        $stmt = $mysqli->prepare("
        SELECT c.id, c.name, c.hex_code, COALESCE(SUM(pv.stock), 0) AS stock
        FROM product_variants pv
        JOIN colors c ON pv.color_id = c.id
        WHERE pv.product_id = ? AND pv.color_id IS NOT NULL
        GROUP BY c.id, c.name, c.hex_code
        ORDER BY c.name
    ");
        
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $colors = [];
        while ($row = $result->fetch_assoc()) {
            $colors[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'code' => $row['hex_code'],
                'stock' => $row['stock']
            ];
        }
        
        $mysqli->close();
        return $colors;
    }

    /**
     * Get product sizes from database
     */
    public function getProductSizes(int $productId): array {
        $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
        
        if ($mysqli->connect_error) {
            return [];
        }
        
        $stmt = $mysqli->prepare("
        SELECT s.id, s.name, COALESCE(SUM(pv.stock), 0) AS stock
        FROM product_variants pv
        JOIN sizes s ON pv.size_id = s.id
        WHERE pv.product_id = ? AND pv.size_id IS NOT NULL
        GROUP BY s.id, s.name, s.sort_order
        ORDER BY s.sort_order, s.name
    ");
        
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $sizes = [];
        while ($row = $result->fetch_assoc()) {
            $sizes[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'stock' => $row['stock']
            ];
        }
        
        $mysqli->close();
        return $sizes;
    }

    /**
     * Get product variant stock for specific color-size combination
     */
    public function getVariantStock(int $productId, int $colorId, int $sizeId): int {
        $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
        
        if ($mysqli->connect_error) {
            return 0;
        }
        
        $stmt = $mysqli->prepare("
            SELECT stock
            FROM product_variants
            WHERE product_id = ? AND color_id = ? AND size_id = ?
        ");
        
        $stmt->bind_param("iii", $productId, $colorId, $sizeId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stock = 0;
        if ($row = $result->fetch_assoc()) {
            $stock = (int)$row['stock'];
        }
        
        $mysqli->close();
        return $stock;
    }

    public function getProductVariants(int $productId): array {
        return $this->productModel->getProductVariants($productId);
    }

    public function createProductVariant(array $data): ?int {
        return $this->productModel->createProductVariant($data);
    }

    public function updateProductVariant(int $variantId, array $data): bool {
        return $this->productModel->updateProductVariant($variantId, $data);
    }

    public function deleteProductVariant(int $variantId): bool {
        return $this->productModel->deleteProductVariant($variantId);
    }

    public function getAllColors(): array {
        return $this->productModel->getAllColors();
    }

    public function getAllSizes(): array {
        return $this->productModel->getAllSizes();
    }

    public function getAllCategories(): array {
        return $this->productModel->getAllCategories();
    }

    // Search products by name for realtime suggestions
    public function searchByName(string $query, int $limit = 8): array {
        return $this->productModel->searchByName($query, $limit);
    }
}
