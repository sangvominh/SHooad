<?php
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/OrderItem.php';

class OrderService {
    private $orderModel;
    private $orderItemModel;

    public function __construct() {
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
    }

    public function getOrderWithItems(int $orderId): ?array {
        $order = $this->orderModel->getOrder($orderId);
        if (!$order) {
            return null;
        }

        $orderItems = $this->orderItemModel->getOrderItemByOrderId($orderId);
        return [
            'order' => $order,
            'items' => $orderItems
        ];
    }

    public function updateOrderStatus(int $orderId, string $newStatus): bool {
        return $this->orderModel->updateOrderStatus($orderId, $newStatus);
    }
}
