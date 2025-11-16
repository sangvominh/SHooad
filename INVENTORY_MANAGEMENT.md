# 📦 Inventory Management - Documentation

## 🎯 Overview

Hệ thống quản lý tồn kho tự động khi trạng thái đơn hàng thay đổi, đảm bảo an toàn và chính xác.

## 📊 Quy trình cập nhật tồn kho

### ✅ Khi nào TRỪ kho?

**Thời điểm**: Seller XÁC NHẬN đơn hàng (`pending` → `processing`)

**Lý do**: Ngăn overselling - đảm bảo hàng được "khóa" cho đơn này ngay khi seller confirm

```
Customer đặt hàng
    ↓
[pending] ← Chưa trừ kho (có thể cancel dễ dàng)
    ↓ Seller bấm "Xác nhận"
[processing] ← ✅ TRỪ KHO Ở ĐÂY
    ↓ Seller bấm "Giao hàng"
[delivering] ← Kho đã trừ rồi
    ↓
[completed]
```

### 🔄 Khi nào KHÔI PHỤC kho?

**Trường hợp 1**: Cancel từ `processing` hoặc `delivering`
- Trừ kho rồi → Cần hoàn lại

**Trường hợp 2**: Failed từ `processing` hoặc `delivering`
- Trừ kho rồi → Cần hoàn lại

**Trường hợp 3**: Cancel từ `pending`
- Chưa trừ kho → KHÔNG cần hoàn

```
[processing] → [cancelled] ✅ Hoàn kho
[delivering] → [cancelled] ✅ Hoàn kho
[delivering] → [failed] ✅ Hoàn kho
[pending] → [cancelled] ❌ Không hoàn (chưa trừ)
```

## 🗄️ Database Support

### 1. Simple Products (không có variants)
```sql
-- Trừ kho
UPDATE products SET stock = stock - ? WHERE id = ?;

-- Hoàn kho
UPDATE products SET stock = stock + ? WHERE id = ?;
```

### 2. Product Variants (có color + size)
```sql
-- Trừ kho từ variant cụ thể
UPDATE product_variants 
SET stock = stock - ? 
WHERE product_id = ? AND color_id = ? AND size_id = ?;

-- Hoàn kho về variant cụ thể
UPDATE product_variants 
SET stock = stock + ? 
WHERE product_id = ? AND color_id = ? AND size_id = ?;
```

## 💻 Code Implementation

### OrderService.php

```php
public function updateOrderStatus(int $orderId, string $newStatus): bool {
    $order = $this->orderModel->getOrder($orderId);
    $currentStatus = $order['status'];

    // Validate transition
    if (!$this->isValidStatusTransition($currentStatus, $newStatus)) {
        return false;
    }

    // ✅ TRỪ KHO: Khi seller xác nhận
    if ($currentStatus === 'pending' && $newStatus === 'processing') {
        if (!$this->deductInventoryForOrder($orderId)) {
            return false; // Không đủ hàng → Không cho chuyển trạng thái
        }
    }

    // 🔄 HOÀN KHO: Khi cancel/fail từ processing/delivering
    if (in_array($newStatus, ['cancelled', 'failed'])) {
        if (in_array($currentStatus, ['processing', 'delivering'])) {
            $this->restoreInventoryForOrder($orderId);
        }
    }

    return $this->orderModel->updateOrderStatus($orderId, $newStatus);
}
```

### Deduct Inventory Logic

```php
private function deductInventoryForOrder(int $orderId): bool {
    $orderItems = $this->orderItemModel->getOrderItemByOrderId($orderId);

    foreach ($orderItems as $item) {
        $productId = $item['product_id'];
        $quantity = $item['quantity'];
        $color = $item['product_color'];
        $size = $item['product_size'];

        // Nếu có color/size → Trừ từ product_variants
        if ($color && $size) {
            $variant = getVariant($productId, $color, $size);
            
            // Check stock
            if ($variant['stock'] < $quantity) {
                return false; // ❌ Không đủ hàng
            }
            
            // Deduct
            UPDATE product_variants 
            SET stock = stock - $quantity
            WHERE product_id = $productId AND color = $color AND size = $size;
        }
        // Không có variants → Trừ từ products
        else {
            $product = getProduct($productId);
            
            // Check stock
            if ($product['stock'] < $quantity) {
                return false; // ❌ Không đủ hàng
            }
            
            // Deduct
            UPDATE products SET stock = stock - $quantity WHERE id = $productId;
        }
    }

    return true; // ✅ Trừ thành công
}
```

## 🧪 Test Cases

### Test 1: Đơn hàng bình thường
```
1. Customer đặt hàng → status = 'pending', stock không đổi
2. Seller xác nhận → status = 'processing', stock giảm
3. Seller giao hàng → status = 'delivering', stock không đổi
4. Hoàn thành → status = 'completed', stock không đổi

Kết quả: Stock giảm đúng số lượng đặt
```

### Test 2: Cancel từ pending
```
1. Customer đặt hàng → status = 'pending', stock không đổi
2. Customer cancel → status = 'cancelled', stock không đổi

Kết quả: Stock không thay đổi (vì chưa trừ)
```

### Test 3: Cancel từ processing
```
1. Customer đặt hàng → status = 'pending', stock không đổi
2. Seller xác nhận → status = 'processing', stock giảm
3. Seller cancel → status = 'cancelled', stock tăng lại

Kết quả: Stock về lại như ban đầu
```

### Test 4: Failed khi đang giao
```
1. Customer đặt hàng → status = 'pending', stock không đổi
2. Seller xác nhận → status = 'processing', stock giảm
3. Seller giao hàng → status = 'delivering', stock không đổi
4. Giao thất bại → status = 'failed', stock tăng lại

Kết quả: Stock về lại như ban đầu
```

### Test 5: Product với variants
```
Product: Áo thun (ID=1)
- Màu đỏ, Size M: stock = 10
- Màu xanh, Size L: stock = 5

Customer đặt: Áo thun đỏ, Size M, số lượng 3

1. Pending → processing:
   - product_variants.stock (đỏ, M) = 10 - 3 = 7 ✅
   - products.stock không đổi

2. Cancel:
   - product_variants.stock (đỏ, M) = 7 + 3 = 10 ✅
```

### Test 6: Không đủ hàng
```
Product stock = 5
Customer đặt 10 items

Seller bấm "Xác nhận":
- deductInventory() check stock < quantity
- Return false
- Status KHÔNG chuyển sang processing
- Hiển thị lỗi: "Không đủ hàng trong kho"

Kết quả: Đơn vẫn ở pending, seller phải cancel hoặc chờ nhập thêm hàng
```

## 🚨 Edge Cases & Safety

### 1. Race Condition
**Vấn đề**: 2 đơn cùng lúc xác nhận, cùng check stock = 5, cả 2 đều pass
**Giải pháp**: Dùng database transaction

```php
$db->begin_transaction();
try {
    // Check stock
    $stmt = $db->prepare("SELECT stock FROM products WHERE id = ? FOR UPDATE");
    // ^ FOR UPDATE lock row
    
    if ($stock < $quantity) {
        throw new Exception("Insufficient stock");
    }
    
    // Deduct stock
    $db->query("UPDATE products SET stock = stock - $quantity WHERE id = $id");
    
    $db->commit();
} catch (Exception $e) {
    $db->rollback();
    return false;
}
```

### 2. Restore nhiều lần
**Vấn đề**: User spam nút cancel, gọi restoreInventory() nhiều lần
**Giải pháp**: Check status transition - không cho cancel từ cancelled

```php
private function isValidStatusTransition($from, $to): bool {
    $validTransitions = [
        'cancelled' => [], // ❌ Không cho transition từ cancelled
        'failed' => [], // ❌ Không cho transition từ failed
    ];
}
```

### 3. Partial deduction
**Vấn đề**: Order có 3 items, deduct được 2 items rồi fail ở item 3
**Giải pháp**: Rollback trong deductInventoryForOrder()

```php
private function deductInventoryForOrder(int $orderId): bool {
    $db->begin_transaction();
    
    foreach ($orderItems as $item) {
        if (!deductStock($item)) {
            $db->rollback(); // ❌ Rollback tất cả
            return false;
        }
    }
    
    $db->commit(); // ✅ All or nothing
    return true;
}
```

## 📈 Monitoring & Logging

### Log Events
```php
// Successful deduction
error_log("✅ Deducted stock for order #{$orderId}: Product {$productId}, Qty {$quantity}");

// Failed deduction
error_log("❌ Insufficient stock for order #{$orderId}: Product {$productId}, Required {$quantity}, Available {$stock}");

// Restoration
error_log("🔄 Restored stock for order #{$orderId}: Product {$productId}, Qty {$quantity}");
```

### Database Audit
```sql
-- Track inventory changes
CREATE TABLE inventory_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    variant_id INT NULL,
    quantity INT,
    action ENUM('deduct', 'restore'),
    old_stock INT,
    new_stock INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🔗 Related Files

- `app/Services/OrderService.php` - Main inventory logic
- `app/Models/Order.php` - Order data access
- `app/Models/OrderItem.php` - Order items data access
- `app/Controllers/SellerController.php` - Status update endpoint
- `database/db.sql` - Schema definition

---

**Last Updated**: November 16, 2025  
**Version**: 2.1 (With variant support)
