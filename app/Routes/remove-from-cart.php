<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($_SESSION['customer_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Please login first']);
        exit();
    }

    $cart_item_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    $customer_id = intval($_SESSION['customer_id']);

    if (!$cart_item_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing cart item id']);
        exit();
    }

    $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verify cart item belongs to customer
    $stmt = $pdo->prepare('SELECT ci.id FROM cart_items ci JOIN carts c ON ci.cart_id = c.id WHERE ci.id = :item_id AND c.customer_id = :customer_id');
    $stmt->execute([':item_id' => $cart_item_id, ':customer_id' => $customer_id]);
    $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cartItem) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }

    // Delete cart item
    $del = $pdo->prepare('DELETE FROM cart_items WHERE id = :item_id');
    $del->execute([':item_id' => $cart_item_id]);

    // Get new cart total
    $totalStmt = $pdo->prepare('SELECT SUM(ci.quantity) AS total FROM cart_items ci JOIN carts c ON ci.cart_id = c.id WHERE c.customer_id = :customer_id');
    $totalStmt->execute([':customer_id' => $customer_id]);
    $totalRow = $totalStmt->fetch(PDO::FETCH_ASSOC);
    $newTotal = $totalRow ? intval($totalRow['total']) : 0;

    echo json_encode(['success' => true, 'message' => 'Removed', 'cart_total' => $newTotal]);

} catch (Exception $e) {
    error_log('remove-from-cart error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
