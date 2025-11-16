# 🧪 Inventory Management Test Plan

## Test Scenarios

### ✅ Scenario 1: Normal Order Flow
**Mục tiêu**: Verify stock deduction chỉ xảy ra khi seller confirm

```
Initial State:
- Product ID 1: stock = 100
- Product ID 2: stock = 50

Steps:
1. Customer places order:
   - Item 1: Product 1, qty = 5
   - Item 2: Product 2, qty = 3
   - Status: pending
   
   Expected: Stock không đổi
   - Product 1: stock = 100 ✅
   - Product 2: stock = 50 ✅

2. Seller confirms (pending → processing):
   Expected: Stock giảm
   - Product 1: stock = 95 ✅
   - Product 2: stock = 47 ✅

3. Seller ships (processing → delivering):
   Expected: Stock không đổi
   - Product 1: stock = 95 ✅
   - Product 2: stock = 47 ✅

4. Complete delivery (delivering → completed):
   Expected: Stock không đổi
   - Product 1: stock = 95 ✅
   - Product 2: stock = 47 ✅
```

### ✅ Scenario 2: Cancel from Pending
**Mục tiêu**: Verify không restore khi cancel từ pending

```
Initial State:
- Product: stock = 100

Steps:
1. Customer places order (qty = 10)
   Status: pending
   Stock: 100 ✅

2. Customer cancels (pending → cancelled)
   Stock: 100 ✅ (không restore vì chưa trừ)
```

### ✅ Scenario 3: Cancel from Processing
**Mục tiêu**: Verify restore khi cancel từ processing

```
Initial State:
- Product: stock = 100

Steps:
1. Customer places order (qty = 10)
   Status: pending
   Stock: 100 ✅

2. Seller confirms (pending → processing)
   Stock: 90 ✅ (trừ 10)

3. Seller cancels (processing → cancelled)
   Stock: 100 ✅ (restore 10)
```

### ✅ Scenario 4: Cancel from Delivering
**Mục tiêu**: Verify restore khi cancel từ delivering

```
Initial State:
- Product: stock = 100

Steps:
1. Order flow: pending → processing → delivering
   Stock after processing: 90 ✅

2. Cancel while delivering (delivering → cancelled)
   Stock: 100 ✅ (restore 10)
```

### ✅ Scenario 5: Insufficient Stock
**Mục tiêu**: Verify không cho chuyển sang processing nếu không đủ hàng

```
Initial State:
- Product: stock = 5

Steps:
1. Customer places order (qty = 10)
   Status: pending
   Stock: 5 ✅

2. Seller tries to confirm (pending → processing)
   Expected: ❌ FAIL
   - Error: "Insufficient stock"
   - Status: pending (không đổi)
   - Stock: 5 (không đổi)
```

### ✅ Scenario 6: Product with Variants
**Mục tiêu**: Verify stock deduction từ product_variants

```
Initial State:
- Product ID 1 (Áo thun)
  - Variant 1: Red + Size M, stock = 10
  - Variant 2: Blue + Size L, stock = 5

Steps:
1. Customer places order:
   - Product 1, color: Red, size: M, qty = 3
   Status: pending
   
   Expected:
   - products.stock: không đổi
   - product_variants (Red, M): stock = 10 ✅

2. Seller confirms (pending → processing):
   Expected:
   - products.stock: không đổi
   - product_variants (Red, M): stock = 7 ✅ (10 - 3)

3. Cancel:
   Expected:
   - product_variants (Red, M): stock = 10 ✅ (7 + 3)
```

### ✅ Scenario 7: Mixed Products (with and without variants)
```
Order Items:
- Item 1: Product 1 (simple), qty = 5
- Item 2: Product 2 (variant: Red, M), qty = 3

Initial State:
- Product 1: stock = 100
- Product 2 Variant (Red, M): stock = 10

After confirm (pending → processing):
- Product 1: stock = 95 ✅
- Product 2 Variant (Red, M): stock = 7 ✅
```

### ✅ Scenario 8: Failed Delivery
**Mục tiêu**: Verify restore khi delivery fail

```
Initial State:
- Product: stock = 100

Steps:
1. Order flow: pending → processing → delivering
   Stock: 90 ✅

2. Delivery fails (delivering → failed)
   Stock: 100 ✅ (restore)
```

## SQL Queries for Testing

### Check Product Stock
```sql
-- Simple products
SELECT id, name, stock FROM products WHERE id IN (1, 2, 3);

-- Product variants
SELECT 
    pv.product_id,
    p.name as product_name,
    c.name as color,
    s.name as size,
    pv.stock
FROM product_variants pv
JOIN products p ON pv.product_id = p.id
LEFT JOIN colors c ON pv.color_id = c.id
LEFT JOIN sizes s ON pv.size_id = s.id
WHERE pv.product_id IN (1, 2, 3);
```

### Check Order Status & Items
```sql
SELECT 
    o.id as order_id,
    o.status,
    oi.product_id,
    oi.product_name,
    oi.product_color,
    oi.product_size,
    oi.quantity
FROM orders o
JOIN order_items oi ON o.id = oi.order_id
WHERE o.id = ?;
```

### Verify Stock Changes
```sql
-- Before confirm
SELECT stock FROM products WHERE id = 1; -- Should be 100

-- After confirm (pending → processing)
SELECT stock FROM products WHERE id = 1; -- Should be 95

-- After cancel
SELECT stock FROM products WHERE id = 1; -- Should be 100 (restored)
```

## Manual Testing Steps

### Step 1: Setup Test Data
```sql
-- Reset a product for testing
UPDATE products SET stock = 100 WHERE id = 1;

-- Create test order
INSERT INTO orders (customer_id, shop_id, status, total, shipping_fee) 
VALUES (1, 1, 'pending', 100.00, 10.00);

SET @order_id = LAST_INSERT_ID();

INSERT INTO order_items (order_id, product_id, quantity, price, product_name)
VALUES (@order_id, 1, 10, 10.00, 'Test Product');
```

### Step 2: Test Confirm
```php
// In seller dashboard, click "Xác nhận" button
// Or via API:
POST /seller/update-order-status
{
    "order_id": 123,
    "status": "processing"
}

// Check logs:
// ✅ Deducted stock for order #123: Product 1, Qty 10
```

### Step 3: Verify Stock
```sql
SELECT stock FROM products WHERE id = 1;
-- Expected: 90 (100 - 10)
```

### Step 4: Test Cancel
```php
// Click "Hủy đơn" button
POST /seller/update-order-status
{
    "order_id": 123,
    "status": "cancelled"
}

// Check logs:
// 🔄 Restored stock for order #123: Product 1, Qty 10
```

### Step 5: Verify Restore
```sql
SELECT stock FROM products WHERE id = 1;
-- Expected: 100 (90 + 10)
```

## Expected Error Messages

### Insufficient Stock
```
❌ Error: "Không đủ hàng trong kho"
- Hiển thị alert cho seller
- Status không đổi (vẫn pending)
- Đơn hàng cần xử lý (cancel hoặc chờ nhập hàng)
```

### Invalid Status Transition
```
❌ Error: "Không thể chuyển trạng thái từ completed sang processing"
- Chặn ở backend
- Log error: "Invalid status transition: completed -> processing"
```

## Checklist

### Before Testing
- [ ] Backup database: `mysqldump -u root -p SHooad > backup_test.sql`
- [ ] Note down current stock values
- [ ] Clear error logs: `> app/logs/error.log`

### During Testing
- [ ] Check stock after each status change
- [ ] Verify logs show correct messages
- [ ] Test both simple products and variants
- [ ] Test cancel from different states

### After Testing
- [ ] Verify all stock values are correct
- [ ] Check for any orphaned inventory
- [ ] Review error logs for unexpected issues
- [ ] Restore from backup if needed: `mysql -u root -p SHooad < backup_test.sql`

## Known Issues to Watch

### ⚠️ Issue 1: Race Condition
**Problem**: 2 sellers confirm orders at same time
**Symptom**: Negative stock
**Solution**: Add database transaction (TODO)

### ⚠️ Issue 2: Partial Restore
**Problem**: Restore half succeeds, half fails
**Symptom**: Incorrect stock count
**Solution**: Wrap in transaction (TODO)

### ⚠️ Issue 3: Orphaned Stock
**Problem**: Order deleted without restore
**Symptom**: Missing stock
**Solution**: Add ON DELETE CASCADE trigger (TODO)

## Performance Benchmarks

### Target Response Times
- Confirm order (pending → processing): < 500ms
- Cancel order with restore: < 300ms
- Check stock availability: < 100ms

### Database Queries
- Each status update: 3-5 queries
- With variants: 5-8 queries
- Optimize with batch updates if > 10 items

---

**Test Date**: _____________  
**Tester**: _____________  
**Results**: ✅ Pass / ❌ Fail  
**Notes**: _____________
