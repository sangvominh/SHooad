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

        // Deduct inventory when seller confirms order (pending -> processing)
        // This prevents overselling by reserving stock immediately after confirmation
        if ($currentStatus === 'pending' && $newStatus === 'processing') {
            if (!$this->deductInventoryForOrder($orderId)) {
                error_log("Failed to deduct inventory for order {$orderId}");
                return false;
            }
        }

        // Restore inventory if order is cancelled or failed
        // Only restore if inventory was already deducted (from processing or delivering states)
        if (in_array($newStatus, ['cancelled', 'failed'])) {
            // Restore if transitioning from processing or delivering (inventory was deducted)
            if (in_array($currentStatus, ['processing', 'delivering'])) {
                if (!$this->restoreInventoryForOrder($orderId)) {
                    error_log("Failed to restore inventory for order {$orderId}");
                    // Continue anyway - status change is more critical
                }
            }
            // No restore needed if cancelling from 'pending' (inventory never deducted)
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
     * Supports both simple products and product variants
     */
    private function deductInventoryForOrder(int $orderId): bool {
        $db = (new Database())->getConnection();
        $orderItems = $this->orderItemModel->getOrderItemByOrderId($orderId);

        foreach ($orderItems as $item) {
            $productId = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];
            $color = $item['product_color'] ?? null;
            $size = $item['product_size'] ?? null;

            // If order has color/size, deduct from product_variants
            if ($color && $size) {
                // Get color_id and size_id
                $stmt = $db->prepare("SELECT id FROM colors WHERE name = ?");
                $stmt->bind_param("s", $color);
                $stmt->execute();
                $colorResult = $stmt->get_result()->fetch_assoc();
                $colorId = $colorResult['id'] ?? null;

                $stmt = $db->prepare("SELECT id FROM sizes WHERE name = ?");
                $stmt->bind_param("s", $size);
                $stmt->execute();
                $sizeResult = $stmt->get_result()->fetch_assoc();
                $sizeId = $sizeResult['id'] ?? null;

                if ($colorId && $sizeId) {
                    // Check variant stock
                    $stmt = $db->prepare("
                        SELECT stock FROM product_variants 
                        WHERE product_id = ? AND color_id = ? AND size_id = ?
                    ");
                    $stmt->bind_param("iii", $productId, $colorId, $sizeId);
                    $stmt->execute();
                    $result = $stmt->get_result()->fetch_assoc();

                    if (!$result || $result['stock'] < $quantity) {
                        error_log("Insufficient variant stock for product {$productId} ({$color}, {$size}). Required: {$quantity}, Available: " . ($result['stock'] ?? 0));
                        return false;
                    }

                    // Deduct variant stock
                    $updateStmt = $db->prepare("
                        UPDATE product_variants 
                        SET stock = stock - ? 
                        WHERE product_id = ? AND color_id = ? AND size_id = ?
                    ");
                    $updateStmt->bind_param("iiii", $quantity, $productId, $colorId, $sizeId);
                    if (!$updateStmt->execute()) {
                        error_log("Failed to deduct variant stock for product {$productId}");
                        return false;
                    }
                    continue; // Move to next item
                } else {
                    error_log("Product {$productId} has no valid variant for deducting stock");
                    return false;
                }
            }

            // If no variant information, cannot deduct stock
            error_log("Cannot deduct stock for product {$productId} without variant information");
            return false;
        }

        return true;
    }

    /**
     * Restore inventory for cancelled/failed orders
     * Supports both simple products and product variants
     */
    private function restoreInventoryForOrder(int $orderId): bool {
        $db = (new Database())->getConnection();
        $orderItems = $this->orderItemModel->getOrderItemByOrderId($orderId);

        foreach ($orderItems as $item) {
            $productId = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];
            $color = $item['product_color'] ?? null;
            $size = $item['product_size'] ?? null;

            // If order has color/size, restore to product_variants
            if ($color && $size) {
                // Get color_id and size_id
                $stmt = $db->prepare("SELECT id FROM colors WHERE name = ?");
                $stmt->bind_param("s", $color);
                $stmt->execute();
                $colorResult = $stmt->get_result()->fetch_assoc();
                $colorId = $colorResult['id'] ?? null;

                $stmt = $db->prepare("SELECT id FROM sizes WHERE name = ?");
                $stmt->bind_param("s", $size);
                $stmt->execute();
                $sizeResult = $stmt->get_result()->fetch_assoc();
                $sizeId = $sizeResult['id'] ?? null;

                if ($colorId && $sizeId) {
                    // Restore variant stock
                    $stmt = $db->prepare("
                        UPDATE product_variants 
                        SET stock = stock + ? 
                        WHERE product_id = ? AND color_id = ? AND size_id = ?
                    ");
                    $stmt->bind_param("iiii", $quantity, $productId, $colorId, $sizeId);
                    $stmt->execute();
                    continue; // Move to next item
                } else {
                    error_log("Cannot restore stock for product {$productId} without variant information");
                }
            }
        }

        return true;
    }
}
