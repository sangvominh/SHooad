<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    // Check if customer is logged in
    if (!isset($_SESSION['customer_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Please login first'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Validate POST parameters
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $variant_id = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
    $color = isset($_POST['color']) ? trim($_POST['color']) : '';
    $size = isset($_POST['size']) ? trim($_POST['size']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $customer_id = $_SESSION['customer_id'];

    // Debug log
    error_log('Add to cart attempt - Customer: ' . $customer_id . ', Product: ' . $product_id . ', Color: ' . $color . ', Size: ' . $size . ', Qty: ' . $quantity);

    // Validation
    if (!$product_id || $quantity < 1) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid data (product_id or quantity)'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    if (!$variant_id && (!$color || !$size)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please select color and size'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check product exists
    $productStmt = $pdo->prepare('SELECT id FROM products WHERE id = :pid AND status = "active"');
    $productStmt->execute([':pid' => $product_id]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // If variant provided, validate variant stock and resolve color/size names
    if ($variant_id > 0) {
        $variantStmt = $pdo->prepare('SELECT pv.id, pv.product_id, pv.stock, pv.price, c.name AS color_name, s.name AS size_name FROM product_variants pv LEFT JOIN colors c ON pv.color_id = c.id LEFT JOIN sizes s ON pv.size_id = s.id WHERE pv.id = :vid AND pv.product_id = :pid');
        $variantStmt->execute([':vid' => $variant_id, ':pid' => $product_id]);
        $variant = $variantStmt->fetch(PDO::FETCH_ASSOC);
        if (!$variant) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Variant not found'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        if ($quantity > intval($variant['stock'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds variant stock. Available: ' . intval($variant['stock'])], JSON_UNESCAPED_UNICODE);
            exit();
        }
        // Ensure color/size names carry to cart snapshot
        $color = $variant['color_name'] ?? $color;
        $size  = $variant['size_name'] ?? $size;
    }

    // Get or create cart for customer
    $cartStmt = $pdo->prepare('SELECT id FROM carts WHERE customer_id = :customer_id');
    $cartStmt->execute([':customer_id' => $customer_id]);
    $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cart) {
        // Create new cart
        $createCartStmt = $pdo->prepare('INSERT INTO carts (customer_id) VALUES (:customer_id)');
        $createCartStmt->execute([':customer_id' => $customer_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['id'];
    }

    // Check if item already exists in cart
    $checkItemStmt = $pdo->prepare('SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id AND color = :color AND size = :size');
    $checkItemStmt->execute([
        ':cart_id' => $cart_id,
        ':product_id' => $product_id,
        ':color' => $color,
        ':size' => $size
    ]);
    $existingItem = $checkItemStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        // Update existing item quantity (keep current selected state)
        $newQuantity = $existingItem['quantity'] + $quantity;
        $updateStmt = $pdo->prepare('UPDATE cart_items SET quantity = :quantity WHERE id = :id');
        $updateStmt->execute([':quantity' => $newQuantity, ':id' => $existingItem['id']]);
    } else {
        // Insert new item with selected=0 by default (user must manually select)
        $insertStmt = $pdo->prepare('INSERT INTO cart_items (cart_id, product_id, color, size, quantity, selected) VALUES (:cart_id, :product_id, :color, :size, :quantity, 0)');
        $insertStmt->execute([
            ':cart_id' => $cart_id,
            ':product_id' => $product_id,
            ':color' => $color,
            ':size' => $size,
            ':quantity' => $quantity
        ]);
    }

    // Get total cart items count (number of items, not quantity)
    $countStmt = $pdo->prepare('SELECT COUNT(*) as total FROM cart_items WHERE cart_id = :cart_id');
    $countStmt->execute([':cart_id' => $cart_id]);
    $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
    $cart_total = intval($countResult['total'] ?? 0);
    
    http_response_code(200);
    echo json_encode([
        'success' => true, 
        'message' => 'Product added to cart successfully',
        'cart_total' => $cart_total
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log('Add to cart error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
