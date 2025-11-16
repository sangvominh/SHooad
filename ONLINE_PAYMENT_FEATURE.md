# Tài liệu: Tính năng Thanh toán Online và Tách đơn tự động

## Ngày triển khai: 16/11/2025

## Tổng quan
Hệ thống đã được nâng cấp với các tính năng sau:
1. **Thanh toán Online qua QR Code**
2. **Tự động tách đơn hàng theo Shop**
3. **Thông báo hoàn tiền khi hủy đơn thanh toán online**

---

## 1. Database Changes

### Migration File
Chạy file: `database/migration_add_payment_fields.sql`

Hoặc chạy SQL sau:
```sql
-- Add payment_method column
ALTER TABLE orders 
ADD COLUMN payment_method ENUM('cod', 'online') DEFAULT 'cod' AFTER status;

-- Add payment_status column
ALTER TABLE orders 
ADD COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending' AFTER payment_method;

-- Update existing orders
UPDATE orders 
SET payment_method = 'cod', 
    payment_status = CASE 
        WHEN status = 'completed' THEN 'paid'
        ELSE 'pending'
    END
WHERE payment_method IS NULL OR payment_method = 'cod';
```

### Schema Updated
Bảng `orders` đã được thêm 2 cột:
- `payment_method`: ENUM('cod', 'online') - Phương thức thanh toán
- `payment_status`: ENUM('pending', 'paid', 'failed', 'refunded') - Trạng thái thanh toán

---

## 2. Tính năng Thanh toán Online

### Flow hoạt động

#### A. Chọn phương thức thanh toán (Checkout Page)
- User chọn giữa 2 phương thức:
  - **COD (Cash on Delivery)**: Thanh toán khi nhận hàng
  - **Online Payment**: Chuyển khoản ngân hàng qua QR Code

#### B. Thanh toán COD
1. User chọn COD → Nhấn "Đặt hàng"
2. Đơn hàng được tạo ngay lập tức với:
   - `status`: 'pending'
   - `payment_method`: 'cod'
   - `payment_status`: 'pending'
3. Redirect đến trang order-success

#### C. Thanh toán Online
1. User chọn Online Payment → Nhấn "Đặt hàng"
2. **Hiển thị popup QR Code** với:
   - QR Code thanh toán
   - Thông tin chuyển khoản (Ngân hàng, STK, Tên TK)
   - Số tiền cần thanh toán
   - 2 nút: "Hủy" và "Đã chuyển khoản"

3. **User chưa thanh toán** (Nhấn "Hủy"):
   - Đóng popup
   - Không tạo đơn hàng
   - Giỏ hàng không thay đổi

4. **User xác nhận đã thanh toán** (Nhấn "Đã chuyển khoản"):
   - Tạo đơn hàng với:
     - `status`: 'pending'
     - `payment_method`: 'online'
     - `payment_status`: 'paid'
   - Xóa sản phẩm đã chọn khỏi giỏ hàng
   - Redirect đến trang order-success

### Logic quan trọng
- **Chỉ ghi nhận đơn hàng khi user xác nhận đã chuyển khoản**
- Đơn online payment có `payment_status = 'paid'` ngay từ đầu
- Đơn COD có `payment_status = 'pending'`, sẽ chuyển thành 'paid' khi completed

---

## 3. Tự động Tách Đơn theo Shop

### Logic trong `place-order.php`

```php
// Group items by shop_id
$itemsByShop = [];
foreach ($items as $it) {
    $shopId = (int)$it['shop_id'];
    if (!isset($itemsByShop[$shopId])) $itemsByShop[$shopId] = [];
    $itemsByShop[$shopId][] = $it;
}

// Create one order per shop
foreach ($itemsByShop as $shopId => $shopItems) {
    // Insert order for this shop
    // Insert order items for this shop
}
```

### Ví dụ
**Giỏ hàng:**
- Sản phẩm A (Shop 1)
- Sản phẩm B (Shop 1)
- Sản phẩm C (Shop 2)
- Sản phẩm D (Shop 3)

**Kết quả:**
- Order #1: Sản phẩm A + B (Shop 1)
- Order #2: Sản phẩm C (Shop 2)
- Order #3: Sản phẩm D (Shop 3)

### Response API
```json
{
  "success": true,
  "message": "Đặt hàng thành công",
  "order_id": 123,
  "order_ids": [123, 124, 125]
}
```

---

## 4. Hủy Đơn với Thông báo Hoàn tiền

### Logic trong `UserController::cancelOrder()`

```php
if ($orderService->updateOrderStatus($orderId, 'cancelled')) {
    // Check if order was paid online
    if ($order['payment_method'] === 'online' && 
        $order['payment_status'] === 'paid') {
        $_SESSION['show_refund_notice'] = true;
        $_SESSION['refund_order_id'] = $orderId;
    }
    FlashMessageService::setFlashMessage('success', 'Order cancelled successfully');
}
```

### Flow hủy đơn

#### A. Đơn COD
1. User nhấn "Cancel Order"
2. Confirm dialog: "Are you sure you want to cancel this order?"
3. Hủy thành công → Flash message: "Order cancelled successfully"

#### B. Đơn Online Payment
1. User nhấn "Cancel Order"
2. Confirm dialog với thông báo đặc biệt:
   ```
   Bạn có chắc chắn muốn hủy đơn hàng này?
   
   Tiền sẽ được hoàn lại vào tài khoản của bạn 
   trong vòng 1-3 ngày làm việc.
   ```
3. Hủy thành công → Redirect đến orders page
4. **Hiển thị popup hoàn tiền:**
   - Icon check xanh
   - Tiêu đề: "Đơn hàng đã được hủy"
   - Nội dung: 
     - Đơn hàng #XXX đã được hủy thành công
     - **Tiền đã được hoàn lại vào tài khoản của bạn**
     - Kiểm tra trong 1-3 ngày làm việc
   - Nút "Đã hiểu"

---

## 5. Files Modified

### Backend
1. `app/Routes/place-order.php`
   - Thêm xử lý `payment_method` và `payment_status`
   - Tự động group items theo `shop_id`

2. `app/Controllers/UserController.php`
   - Cập nhật `cancelOrder()` để set session flag cho refund notice

### Frontend
3. `app/Views/customer/checkout.php`
   - Mở khóa Online Payment option
   - Thêm QR Payment Modal
   - Thêm JavaScript xử lý flow thanh toán online

4. `app/Views/customer/orders.php`
   - Thêm Refund Notice Modal
   - Cập nhật confirm dialog cho online payment cancellation

5. `app/Views/customer/order-detail.php`
   - Cập nhật confirm dialog cho online payment cancellation

### Database
6. `database/db.sql`
   - Thêm `payment_method` và `payment_status` columns

7. `database/migration_add_payment_fields.sql`
   - Migration file mới

---

## 6. UI Components

### QR Payment Modal
**Vị trí:** `checkout.php` - Hiển thị khi chọn Online Payment

**Nội dung:**
- Tiêu đề: "Quét mã QR để thanh toán"
- QR Code image (placeholder: `/SHooad/public/assets/qr-payment.png`)
- Thông tin ngân hàng:
  - Số tiền (động, hiển thị tổng đơn hàng)
  - Ngân hàng: MB Bank
  - Số tài khoản: 0123456789
  - Chủ TK: NGUYEN VAN A
- Nút "Hủy" và "Đã chuyển khoản"

### Refund Notice Modal
**Vị trí:** `orders.php` - Hiển thị sau khi hủy đơn online payment

**Nội dung:**
- Icon check circle xanh
- Tiêu đề: "Đơn hàng đã được hủy"
- Order ID
- Thông báo: "Tiền đã được hoàn lại vào tài khoản của bạn"
- Hướng dẫn: "Kiểm tra tài khoản trong 1-3 ngày làm việc"
- Nút "Đã hiểu"

---

## 7. Payment Status Flow

### COD Orders
```
Order Created:     payment_status = 'pending'
Order Processing:  payment_status = 'pending'
Order Delivering:  payment_status = 'pending'
Order Completed:   payment_status = 'paid'  (User paid on delivery)
Order Cancelled:   payment_status = 'pending' (No refund needed)
```

### Online Payment Orders
```
User Confirms QR:  payment_status = 'paid'  (Immediately)
Order Processing:  payment_status = 'paid'
Order Delivering:  payment_status = 'paid'
Order Completed:   payment_status = 'paid'
Order Cancelled:   payment_status = 'paid' → Show refund notice
```

---

## 8. Important Notes

### Security
- ⚠️ Hiện tại chưa có tích hợp payment gateway thực tế
- User tự xác nhận đã thanh toán (honor system)
- Cần tích hợp VNPay/MoMo/ZaloPay cho production

### QR Code
- Placeholder image: `/SHooad/public/assets/qr-payment.png`
- Production: Generate dynamic QR với API của ngân hàng
- Nên include order_id vào content QR để auto-verify

### Auto-Split Orders
- ✅ Đã tự động split theo shop_id
- Mỗi shop nhận 1 đơn hàng riêng
- Inventory deduction vẫn hoạt động chính xác

### Testing Checklist
- [ ] Chạy migration file
- [ ] Test COD payment flow
- [ ] Test Online payment flow (QR modal)
- [ ] Test online payment cancellation (popup hủy)
- [ ] Test order với nhiều shop (auto-split)
- [ ] Test cancel COD order
- [ ] Test cancel Online order (refund notice)
- [ ] Verify inventory deduction

---

## 9. Next Steps (Optional)

1. **Payment Gateway Integration**
   - Tích hợp VNPay/MoMo/ZaloPay API
   - Auto-verify payment từ webhook
   - Tạo dynamic QR code với order info

2. **Order Status Updates**
   - Update payment_status khi seller confirms
   - Track refund status (pending → refunded)

3. **Seller Dashboard**
   - Hiển thị payment_method và payment_status
   - Filter orders by payment type

4. **Email Notifications**
   - Email xác nhận đơn hàng
   - Email thông báo hoàn tiền

---

## Support
Nếu có vấn đề, kiểm tra:
1. Database migration đã chạy chưa
2. Browser console có lỗi JavaScript không
3. PHP error logs
4. Session storage cho refund notice

**Author:** GitHub Copilot  
**Date:** November 16, 2025
