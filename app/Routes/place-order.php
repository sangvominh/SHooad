<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($_SESSION['customer_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $customer_id = (int)$_SESSION['customer_id'];

    // Read POST parameters
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $shipping_address = isset($_POST['shipping_address']) ? trim($_POST['shipping_address']) : '';
    $note = isset($_POST['note']) ? trim($_POST['note']) : '';
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'cod';

    if ($full_name === '' || $phone === '' || $shipping_address === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    // Get customer's cart
    $cartStmt = $pdo->prepare('SELECT id FROM carts WHERE customer_id = :customer_id');
    $cartStmt->execute([':customer_id' => $customer_id]);
    $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);
    if (!$cart) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống'], JSON_UNESCAPED_UNICODE);
        exit();
    }
    $cart_id = (int)$cart['id'];

    // Fetch selected items (active products only)
    $itemsStmt = $pdo->prepare('
        SELECT ci.id AS cart_item_id, ci.product_id, ci.quantity, ci.color, ci.size,
               p.name AS product_name, p.price, p.stock, p.shop_id
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.cart_id = :cart_id AND ci.selected = 1 AND p.status = "active"
    ');
    $itemsStmt->execute([':cart_id' => $cart_id]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$items) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Bạn chưa chọn sản phẩm nào'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Validate stock and group by shop
    $itemsByShop = [];
    foreach ($items as $it) {
        if ((int)$it['quantity'] > (int)$it['stock']) {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Sản phẩm ' . $it['product_name'] . ' không đủ hàng'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        $shopId = (int)$it['shop_id'];
        if (!isset($itemsByShop[$shopId])) $itemsByShop[$shopId] = [];
        $itemsByShop[$shopId][] = $it;
    }

    // Set status to 'pending' - seller must confirm before processing
    $status = 'pending';

    // Determine payment status based on payment method
    // For online payment: payment_status = 'paid' (user confirmed payment)
    // For COD: payment_status = 'pending' (will be paid on delivery)
    $payment_status = ($payment_method === 'online') ? 'paid' : 'pending';

    // Prepare statements aligned with DB schema
    $orderStmt = $pdo->prepare('
        INSERT INTO orders (shop_id, customer_id, customer_phone, shipping_address, status, payment_method, payment_status)
        VALUES (:shop_id, :customer_id, :customer_phone, :shipping_address, :status, :payment_method, :payment_status)
    ');

    $orderItemStmt = $pdo->prepare('
        INSERT INTO order_items (order_id, product_id, quantity, price, product_name, product_color, product_size)
        VALUES (:order_id, :product_id, :quantity, :price, :product_name, :product_color, :product_size)
    ');

    // NOTE: Stock will be deducted when seller confirms order (pending->processing)
    // Not deducting here to allow cancellation without stock issues

    $createdOrderIds = [];

    foreach ($itemsByShop as $shopId => $shopItems) {
        // Create one order per shop (auto-split by shop)
        $orderStmt->execute([
            ':shop_id' => $shopId,
            ':customer_id' => $customer_id,
            ':customer_phone' => $phone,
            ':shipping_address' => $shipping_address,
            ':status' => $status,
            ':payment_method' => $payment_method,
            ':payment_status' => $payment_status,
        ]);
        $orderId = (int)$pdo->lastInsertId();
        $createdOrderIds[] = $orderId;

        // Insert order items (stock deduction happens on seller confirmation)
        foreach ($shopItems as $item) {
            $orderItemStmt->execute([
                ':order_id' => $orderId,
                ':product_id' => (int)$item['product_id'],
                ':quantity' => (int)$item['quantity'],
                ':price' => (float)$item['price'],
                ':product_name' => $item['product_name'],
                ':product_color' => $item['color'],
                ':product_size' => $item['size'],
            ]);
        }
    }

    // Remove selected items from cart
    $deleteItemsStmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = :cart_id AND selected = 1');
    $deleteItemsStmt->execute([':cart_id' => $cart_id]);

    $pdo->commit();

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Đặt hàng thành công',
        'order_id' => $createdOrderIds[0] ?? null,
        'order_ids' => $createdOrderIds,
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
