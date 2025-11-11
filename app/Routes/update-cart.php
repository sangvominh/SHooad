<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        exit();
    }

    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;
    $color = isset($_POST['color']) ? trim($_POST['color']) : null;
    $size = isset($_POST['size']) ? trim($_POST['size']) : null;
    $user_id = intval($_SESSION['user_id']);

    if (!$cart_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing cart id']);
        exit();
    }

    $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verify ownership
    $stmt = $pdo->prepare('SELECT * FROM carts WHERE id = :cid AND user_id = :uid');
    $stmt->execute([':cid' => $cart_id, ':uid' => $user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cart) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }

    // If quantity provided, validate against product stock
    if ($quantity !== null) {
        $prodStmt = $pdo->prepare('SELECT stock FROM products WHERE id = :pid');
        $prodStmt->execute([':pid' => $cart['product_id']]);
        $prod = $prodStmt->fetch(PDO::FETCH_ASSOC);
        $stock = $prod ? intval($prod['stock']) : 0;
        if ($quantity < 1) $quantity = 1;
        if ($stock > 0 && $quantity > $stock) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Quantity exceeds stock', 'available' => $stock]);
            exit();
        }
    }

    $updates = [];
    $params = [':cid' => $cart_id, ':uid' => $user_id];
    if ($quantity !== null) { $updates[] = 'quantity = :quantity'; $params[':quantity'] = $quantity; }
    if ($color !== null) { $updates[] = 'color = :color'; $params[':color'] = $color; }
    if ($size !== null) { $updates[] = 'size = :size'; $params[':size'] = $size; }

    if (!empty($updates)) {
        $sql = 'UPDATE carts SET ' . implode(', ', $updates) . ', updated_at = CURRENT_TIMESTAMP WHERE id = :cid AND user_id = :uid';
        $upd = $pdo->prepare($sql);
        $upd->execute($params);
    }

    // Return new cart total for badge
    $totalStmt = $pdo->prepare('SELECT SUM(quantity) AS total FROM carts WHERE user_id = :uid');
    $totalStmt->execute([':uid' => $user_id]);
    $totalRow = $totalStmt->fetch(PDO::FETCH_ASSOC);
    $newTotal = $totalRow ? intval($totalRow['total']) : 0;

    echo json_encode(['success' => true, 'message' => 'Cart updated', 'cart_total' => $newTotal]);

} catch (Exception $e) {
    error_log('update-cart error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

