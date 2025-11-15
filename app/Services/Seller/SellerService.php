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
    public function getDashboardData(int $seller_id, int $shop_id, bool $limit_for_dashboard = false): array {
        $orders = $limit_for_dashboard 
            ? $this->orderModel->getOrderByShop($shop_id, 5)
            : $this->orderModel->getOrderByShop($shop_id);
            
        $products = $limit_for_dashboard
            ? $this->productModel->getProductByShop($shop_id, 5, 'top_sold')
            : $this->productModel->getProductByShop($shop_id);
            
        return [
            'seller' => $this->sellerModel->getInfo($seller_id),
            'shop' => $this->shopModel->getInfo($shop_id),
            'orders' => $orders,
            'products' => $products
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
