<?php
require_once __DIR__ . '/../../Models/Seller.php';
require_once __DIR__ . '/../../Models/Shop.php';
require_once __DIR__ . '/../../Models/Order.php';
require_once __DIR__ . '/../../Models/Product.php';

class SellerService {
    private $sellerModel;
    private $shopModel;
    private $orderModel;
    private $productModel;

    public function __construct() {
        $this->sellerModel = new Seller();
        $this->shopModel = new Shop();
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }

    /**
     * Get dashboard data for seller
     */
    public function getDashboardData(int $seller_id, int $shop_id): array {
        return [
            'seller' => $this->sellerModel->getInfo($seller_id),
            'shop' => $this->shopModel->getInfo($shop_id),
            'orders' => $this->orderModel->getOrderByShop($shop_id),
            'products' => $this->productModel->getProductByShop($shop_id)
        ];
    }

    /**
     * Get seller info
     */
    public function getSellerInfo(int $seller_id): ?array {
        return $this->sellerModel->getInfo($seller_id);
    }

    /**
     * Get shop info
     */
    public function getShopInfo(int $shop_id): ?array {
        return $this->shopModel->getInfo($shop_id);
    }
}
