<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Please login first'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Validate POST parameters
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $color = isset($_POST['color']) ? trim($_POST['color']) : '';
    $size = isset($_POST['size']) ? trim($_POST['size']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $user_id = $_SESSION['user_id'];

    // Debug log
    error_log('Add to cart attempt - User: ' . $user_id . ', Product: ' . $product_id . ', Color: ' . $color . ', Size: ' . $size . ', Qty: ' . $quantity);

    // Validation
    if (!$product_id || $quantity < 1) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid data (product_id or quantity)'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    if (!$color || !$size) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please select color and size'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check product exists and get stock
    $productStmt = $pdo->prepare('SELECT id, stock FROM products WHERE id = :pid AND status = "active"');
    $productStmt->execute([':pid' => $product_id]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Check stock
    if ($quantity > $product['stock']) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds stock. Available: ' . $product['stock']], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Try to insert or update cart (default selected = 0)
    $sql = "INSERT INTO carts (user_id, product_id, color, size, quantity, selected)
        VALUES (:user_id, :product_id, :color, :size, :quantity, 0)
        ON DUPLICATE KEY UPDATE 
        quantity = quantity + :quantity_dup,
        updated_at = CURRENT_TIMESTAMP";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':product_id' => $product_id,
        ':color' => $color,
        ':size' => $size,
        ':quantity' => $quantity,
        ':quantity_dup' => $quantity
    ]);

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Product added to cart successfully'], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log('Add to cart error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
