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
            'colors' => $postData['colors'] ?? '',
            'sizes' => $postData['sizes'] ?? '',
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
            'colors' => $postData['colors'] ?? '',
            'sizes' => $postData['sizes'] ?? '',
            'price' => $postData['price'] ?? 0,
            'original_price' => $postData['original_price'] ?? 0,
            'stock' => $postData['stock'] ?? 0,
            'category_id' => $postData['category_id'] ?? null,
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
}
