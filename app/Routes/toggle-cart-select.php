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
    $selected = isset($_POST['selected']) ? intval($_POST['selected']) : 0;
    $user_id = intval($_SESSION['user_id']);

    if (!$cart_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing cart id']);
        exit();
    }

    $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('SELECT id FROM carts WHERE id = :cid AND user_id = :uid');
    $stmt->execute([':cid' => $cart_id, ':uid' => $user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cart) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }

    $upd = $pdo->prepare('UPDATE carts SET selected = :sel, updated_at = CURRENT_TIMESTAMP WHERE id = :cid AND user_id = :uid');
    $upd->execute([':sel' => $selected ? 1 : 0, ':cid' => $cart_id, ':uid' => $user_id]);

    echo json_encode(['success' => true, 'message' => 'Selection updated']);

} catch (Exception $e) {
    error_log('toggle-cart-select error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
