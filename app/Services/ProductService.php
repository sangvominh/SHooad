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

    public function updateProductStatus(int $productId, string $newStatus): bool {
        return $this->productModel->updateProduct($productId, ['status' => $newStatus]);
    }
}
