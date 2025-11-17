<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Core/Database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
        exit;
    }

    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $colorId   = isset($_POST['color_id']) ? intval($_POST['color_id']) : null;
    $sizeId    = isset($_POST['size_id']) ? intval($_POST['size_id']) : null;

    if ($productId <= 0 || (!$colorId && !$sizeId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing product_id and (color_id or size_id)']);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT id, stock, price FROM product_variants WHERE product_id = ? AND ";
    $params = [$productId];
    $types  = 'i';

    if ($colorId !== null) {
        $sql .= " color_id = ? ";
        $params[] = $colorId;
        $types   .= 'i';
    } else {
        $sql .= " color_id IS NULL ";
    }

    $sql .= " AND ";

    if ($sizeId !== null) {
        $sql .= " size_id = ? ";
        $params[] = $sizeId;
        $types   .= 'i';
    } else {
        $sql .= " size_id IS NULL ";
    }

    $sql .= " LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'variant' => [
                'id' => (int)$row['id'],
                'stock' => (int)$row['stock'],
                'price' => isset($row['price']) ? (float)$row['price'] : null
            ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Variant not found']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error', 'error' => $e->getMessage()]);
}
