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
    $selected = isset($_POST['selected']) ? intval($_POST['selected']) : 0;
    $customer_id = intval($_SESSION['customer_id']);

    if (!$cart_item_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing cart item id']);
        exit();
    }

    require_once __DIR__ . '/../Core/Database.php';
    $pdo = (new Database())->getPDO();

    // Verify ownership
    $stmt = $pdo->prepare('SELECT ci.id FROM cart_items ci JOIN carts c ON ci.cart_id = c.id WHERE ci.id = :item_id AND c.customer_id = :customer_id');
    $stmt->execute([':item_id' => $cart_item_id, ':customer_id' => $customer_id]);
    $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cartItem) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }

    // Update selected status
    $updateStmt = $pdo->prepare('UPDATE cart_items SET selected = :selected WHERE id = :item_id');
    $updateStmt->execute([':selected' => $selected, ':item_id' => $cart_item_id]);
    
    echo json_encode(['success' => true, 'message' => 'Cart item selection updated']);

} catch (Exception $e) {
    error_log('toggle-cart-select error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
