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

    $del = $pdo->prepare('DELETE FROM carts WHERE id = :cid AND user_id = :uid');
    $del->execute([':cid' => $cart_id, ':uid' => $user_id]);

    $totalStmt = $pdo->prepare('SELECT SUM(quantity) AS total FROM carts WHERE user_id = :uid');
    $totalStmt->execute([':uid' => $user_id]);
    $totalRow = $totalStmt->fetch(PDO::FETCH_ASSOC);
    $newTotal = $totalRow ? intval($totalRow['total']) : 0;

    echo json_encode(['success' => true, 'message' => 'Removed', 'cart_total' => $newTotal]);

} catch (Exception $e) {
    error_log('remove-from-cart error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
