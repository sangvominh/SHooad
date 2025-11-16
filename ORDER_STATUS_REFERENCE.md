# Order Status - Quick Reference Card

## 📋 Current Status Values (After Unification)

| Status | When Used | Badge Color | Can Transition To |
|--------|-----------|-------------|-------------------|
| `pending` | Order just placed, awaiting seller confirmation | 🟡 Yellow | processing, cancelled, failed |
| `processing` | Seller confirmed, preparing order (inventory deducted) | 🔵 Blue | delivering, cancelled, failed |
| `delivering` | Order shipped, out for delivery | 🟣 Indigo | completed, failed |
| `completed` | Successfully delivered | 🟢 Green | (final state) |
| `cancelled` | Cancelled by customer/seller | 🔴 Red | (final state) |
| `failed` | Delivery or order failed | 🔴 Red | (final state) |

## 💻 Code Usage

### ✅ Correct Way
```php
// Setting status
$order['status'] = 'pending';

// Checking status
if ($order['status'] === 'completed') {
    // Do something
}

// SQL queries
WHERE status = 'pending'
WHERE status IN ('pending', 'processing')
WHERE status NOT IN ('cancelled', 'failed')

// Displaying to user
echo ucfirst($order['status']); // Output: "Pending"
```

### ❌ Wrong Way
```php
// DON'T use capitalized
$order['status'] = 'Pending';  // ❌
$order['status'] = 'Processing';  // ❌

// DON'T use underscores
$order['status'] = 'Pending_Transfer';  // ❌
$order['status'] = 'pending_transfer';  // ❌

// DON'T use old values
WHERE status = 'Pending_COD'  // ❌
WHERE status = 'Paid'  // ❌
```

## 🎨 Badge Display (Tailwind Classes)

```php
<?php
$badgeClass = match($order['status']) {
    'pending' => 'bg-yellow-100 text-yellow-800',
    'processing' => 'bg-blue-100 text-blue-800',
    'delivering' => 'bg-indigo-100 text-indigo-800',
    'completed' => 'bg-green-100 text-green-800',
    'cancelled', 'failed' => 'bg-red-100 text-red-800',
    default => 'bg-gray-100 text-gray-800'
};
?>

<span class="px-3 py-1 rounded-full text-sm font-semibold <?= $badgeClass ?>">
    <?= ucfirst($order['status']) ?>
</span>
```

## 🔄 Status Transition Flow

```
Customer Creates Order
        ↓
    [pending]
        ↓ seller confirms
  [processing] ← inventory deducted here
        ↓ seller ships
   [delivering]
        ↓ customer receives
   [completed]

Side exits:
• pending/processing → [cancelled] (cancel before ship)
• any state → [failed] (system/delivery failure)
```

## 🗄️ Database Schema

```sql
CREATE TABLE orders (
    -- ... other columns ...
    status ENUM(
        'pending',
        'processing', 
        'delivering',
        'completed',
        'cancelled',
        'failed'
    ) DEFAULT 'pending',
    -- ... other columns ...
);
```

## 📝 Common Queries

### Get pending orders
```sql
SELECT * FROM orders WHERE status = 'pending';
```

### Get active orders (not finished)
```sql
SELECT * FROM orders 
WHERE status IN ('pending', 'processing', 'delivering');
```

### Calculate revenue (exclude cancelled/failed)
```sql
SELECT SUM(total) FROM orders 
WHERE status NOT IN ('cancelled', 'failed');
```

### Count by status
```sql
SELECT status, COUNT(*) as count 
FROM orders 
GROUP BY status;
```

## 🎯 When to Use Each Status

### `pending`
- Right after customer places order
- Before seller confirms
- **No inventory deduction yet**
- Customer can still cancel easily

### `processing`  
- Seller confirmed order
- **Inventory IS deducted**
- Preparing package
- Harder to cancel

### `delivering`
- Package shipped
- Tracking number available
- On the way to customer

### `completed`
- Customer received package
- Can leave review
- Final successful state

### `cancelled`
- Customer or seller cancelled
- Inventory restored if was in processing
- No charges applied

### `failed`
- Delivery failed
- Payment failed
- System error
- Needs investigation

## 🔐 Validation in OrderService

```php
// Valid transitions defined in OrderService.php
private function isValidStatusTransition($from, $to): bool {
    $validTransitions = [
        'pending' => ['processing', 'cancelled', 'failed'],
        'processing' => ['delivering', 'cancelled', 'failed'],
        'delivering' => ['completed', 'failed'],
        'completed' => [],
        'cancelled' => [],
        'failed' => []
    ];
    
    return in_array($to, $validTransitions[$from] ?? []);
}
```

## 🚨 Common Pitfalls

### 1. Case Sensitivity
```php
// ❌ Wrong
if ($status == 'Pending') { }

// ✅ Correct  
if ($status === 'pending') { }
```

### 2. Using Old Values
```php
// ❌ Wrong
$status = 'Pending_COD';

// ✅ Correct
$status = 'pending';
```

### 3. Direct Stock Update
```php
// ❌ Wrong - Update stock when order placed
UPDATE products SET stock = stock - ? WHERE id = ?;

// ✅ Correct - Update stock when seller confirms (pending → processing)
if ($oldStatus === 'pending' && $newStatus === 'processing') {
    UPDATE products SET stock = stock - ? WHERE id = ?;
}
```

## 📱 Frontend Integration

### JavaScript
```javascript
// Status constants
const ORDER_STATUS = {
    PENDING: 'pending',
    PROCESSING: 'processing',
    DELIVERING: 'delivering',
    COMPLETED: 'completed',
    CANCELLED: 'cancelled',
    FAILED: 'failed'
};

// Check status
if (order.status === ORDER_STATUS.PENDING) {
    // Show cancel button
}
```

### Display Labels
```javascript
const STATUS_LABELS = {
    'pending': 'Pending',
    'processing': 'Processing',
    'delivering': 'Shipping',
    'completed': 'Completed',
    'cancelled': 'Cancelled',
    'failed': 'Failed'
};

console.log(STATUS_LABELS[order.status]);
```

## 🧪 Testing Checklist

- [ ] Create order → Status is `pending`
- [ ] Seller confirms → Status changes to `processing`
- [ ] Seller ships → Status changes to `delivering`
- [ ] Complete delivery → Status changes to `completed`
- [ ] Cancel from pending → Status changes to `cancelled`
- [ ] Try invalid transition → Should be blocked
- [ ] Check badge colors are correct
- [ ] Filter by status works
- [ ] Count by status is accurate

## 🔗 Related Files

- `app/Services/OrderService.php` - Status transition logic
- `app/Routes/place-order.php` - Creates orders with `pending`
- `app/Views/customer/orders.php` - Customer order list
- `app/Views/seller/pages/order-detail.php` - Seller order management
- `database/migrate_order_status.sql` - Migration script

---

**Last Updated**: November 16, 2025  
**Version**: 2.0 (Unified Lowercase)
