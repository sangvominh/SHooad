<?php
if (!session_id()) session_start();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Detail</title>
</head>
<body>
    <h1>Order #<?php echo htmlspecialchars($order['id'] ?? ''); ?></h1>
    <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name'] ?? ''); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone'] ?? ''); ?></p>
    <p><strong>Total:</strong> <?php echo htmlspecialchars($order['total_amount'] ?? ''); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status'] ?? ''); ?></p>
    <p><strong>Shipping Status:</strong> <?php echo htmlspecialchars($order['shipping_status'] ?? ''); ?></p>

    <h3>Items</h3>
    <?php if (!empty($order_items)): ?>
        <ul>
            <?php foreach ($order_items as $it): ?>
                <li><?php echo htmlspecialchars($it['product_name']) . ' x ' . htmlspecialchars($it['quantity']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h3>Update status</h3>
    <form method="post" action="/SHooad/public/shipper/update-order-status">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
        <label>Trạng thái:
            <select name="status">
                <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Processing" <?php echo ($order['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                <option value="Completed" <?php echo ($order['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="Cancelled" <?php echo ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </label>
        <button type="submit">Cập nhật</button>
    </form>

    <p><a href="/SHooad/public/shipper/dashboard">Back</a></p>
</body>
</html>