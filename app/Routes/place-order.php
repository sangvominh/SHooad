<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    // Check if customer is logged in
    if (!isset($_SESSION['customer_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $customer_id = $_SESSION['customer_id'];

    // Validate POST parameters
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $shipping_address = isset($_POST['shipping_address']) ? trim($_POST['shipping_address']) : '';
    $note = isset($_POST['note']) ? trim($_POST['note']) : '';
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'cod';

    // Validation
    if (empty($full_name) || empty($phone) || empty($shipping_address)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    // Get cart and selected items
    $cartStmt = $pdo->prepare('SELECT id FROM carts WHERE customer_id = :customer_id');
    $cartStmt->execute([':customer_id' => $customer_id]);
    $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $cart_id = $cart['id'];

    // Get selected cart items
    $itemsStmt = $pdo->prepare('
        SELECT ci.*, p.name, p.price, p.shop_id, p.stock
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.cart_id = :cart_id AND ci.selected = 1 AND p.status = "active"
    ');
    $itemsStmt->execute([':cart_id' => $cart_id]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($items)) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Bạn chưa chọn sản phẩm nào'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Calculate total
    $total_amount = 0;
    foreach ($items as $item) {
        // Check stock
        if ($item['quantity'] > $item['stock']) {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Sản phẩm ' . $item['name'] . ' không đủ hàng'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        $total_amount += $item['price'] * $item['quantity'];
    }

    // Create order
    $orderStmt = $pdo->prepare('
        INSERT INTO orders (customer_id, full_name, phone, email, shipping_address, note, payment_method, total_amount, status, created_at)
        VALUES (:customer_id, :full_name, :phone, :email, :shipping_address, :note, :payment_method, :total_amount, "pending", NOW())
    ');
    $orderStmt->execute([
        ':customer_id' => $customer_id,
        ':full_name' => $full_name,
        ':phone' => $phone,
        ':email' => $email,
        ':shipping_address' => $shipping_address,
        ':note' => $note,
        ':payment_method' => $payment_method,
        ':total_amount' => $total_amount
    ]);
    $order_id = $pdo->lastInsertId();

    // Create order items
    $orderItemStmt = $pdo->prepare('
        INSERT INTO order_items (order_id, product_id, shop_id, color, size, quantity, price)
        VALUES (:order_id, :product_id, :shop_id, :color, :size, :quantity, :price)
    ');

    $updateStockStmt = $pdo->prepare('
        UPDATE products SET stock = stock - :quantity WHERE id = :product_id
    ');

    foreach ($items as $item) {
        // Create order item
        $orderItemStmt->execute([
            ':order_id' => $order_id,
            ':product_id' => $item['product_id'],
            ':shop_id' => $item['shop_id'],
            ':color' => $item['color'],
            ':size' => $item['size'],
            ':quantity' => $item['quantity'],
            ':price' => $item['price']
        ]);

        // Update product stock
        $updateStockStmt->execute([
            ':quantity' => $item['quantity'],
            ':product_id' => $item['product_id']
        ]);
    }

    // Remove selected items from cart
    $deleteItemsStmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = :cart_id AND selected = 1');
    $deleteItemsStmt->execute([':cart_id' => $cart_id]);

    // Commit transaction
    $pdo->commit();

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Đặt hàng thành công',
        'order_id' => $order_id
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Place order error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>
