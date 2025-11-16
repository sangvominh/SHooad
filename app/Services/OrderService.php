<?php
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/OrderItem.php';
require_once __DIR__ . '/../Core/Database.php';

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
        // Get current order
        $order = $this->orderModel->getOrder($orderId);
        if (!$order) {
            return false;
        }

        $currentStatus = $order['status'];

        // Validate status transitions
        if (!$this->isValidStatusTransition($currentStatus, $newStatus)) {
            error_log("Invalid status transition: {$currentStatus} -> {$newStatus}");
            return false;
        }

        // Deduct inventory when confirming order (pending -> processing)
        if ($currentStatus === 'pending' && $newStatus === 'processing') {
            if (!$this->deductInventoryForOrder($orderId)) {
                error_log("Failed to deduct inventory for order {$orderId}");
                return false;
            }
        }

        // Restore inventory if order is cancelled or failed from pending/processing
        if (in_array($newStatus, ['cancelled', 'failed']) && in_array($currentStatus, ['pending', 'processing'])) {
            // Only restore if we already deducted (i.e., from processing)
            if ($currentStatus === 'processing') {
                $this->restoreInventoryForOrder($orderId);
            }
        }

        return $this->orderModel->updateOrderStatus($orderId, $newStatus);
    }

    /**
     * Validate if status transition is allowed
     */
    private function isValidStatusTransition(string $from, string $to): bool {
        // Define valid transitions
        $validTransitions = [
            'pending' => ['processing', 'cancelled', 'failed'],
            'processing' => ['delivering', 'cancelled', 'failed'],
            'delivering' => ['completed', 'failed'],
            'completed' => [], // No transitions from completed
            'cancelled' => [], // No transitions from cancelled
            'failed' => [], // No transitions from failed
        ];

        // Allow same status (no change)
        if ($from === $to) {
            return true;
        }

        return in_array($to, $validTransitions[$from] ?? []);
    }

    /**
     * Deduct inventory for order items
     */
    private function deductInventoryForOrder(int $orderId): bool {
        $db = (new Database())->getConnection();
        $orderItems = $this->orderItemModel->getOrderItemByOrderId($orderId);

        foreach ($orderItems as $item) {
            $productId = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];

            // Check if enough stock
            $stmt = $db->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            if (!$result || $result['stock'] < $quantity) {
                error_log("Insufficient stock for product {$productId}. Required: {$quantity}, Available: " . ($result['stock'] ?? 0));
                return false;
            }

            // Deduct stock
            $updateStmt = $db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $updateStmt->bind_param("ii", $quantity, $productId);
            if (!$updateStmt->execute()) {
                error_log("Failed to deduct stock for product {$productId}");
                return false;
            }
        }

        return true;
    }

    /**
     * Restore inventory for cancelled/failed orders
     */
    private function restoreInventoryForOrder(int $orderId): bool {
        $db = (new Database())->getConnection();
        $orderItems = $this->orderItemModel->getOrderItemByOrderId($orderId);

        foreach ($orderItems as $item) {
            $productId = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];

            // Restore stock
            $stmt = $db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $stmt->bind_param("ii", $quantity, $productId);
            $stmt->execute();
        }

        return true;
    }
}
