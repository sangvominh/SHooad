<?php
require_once __DIR__ . '/../../Models/Seller.php';
require_once __DIR__ . '/../../Models/Shop.php';
require_once __DIR__ . '/../../Models/Order.php';
require_once __DIR__ . '/../../Models/Product.php';
require_once __DIR__ . '/../AuthService.php';

class SellerDashboardService {
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

    public function getDashboardData(): array {
        $auth = AuthService::getSellerAuth();
        if (!$auth['seller_id'] || !$auth['shop_id']) {
            return [
                'seller' => null,
                'shop' => null,
                'orders' => [],
                'products' => []
            ];
        }

        return [
            'seller' => $this->sellerModel->getInfo($auth['seller_id']),
            'shop' => $this->shopModel->getInfo($auth['shop_id']),
            'orders' => $this->orderModel->getOrderByShop($auth['shop_id']),
            'products' => $this->productModel->getProductByShop($auth['shop_id'])
        ];
    }
}
