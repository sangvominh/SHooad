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
    $colorId   = isset($_POST['color_id']) ? intval($_POST['color_id']) : 0;

    if ($productId <= 0 || $colorId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing product_id or color_id']);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    // Get sizes and stock for this product and color
    $sql = "
        SELECT s.id, s.name, COALESCE(SUM(pv.stock), 0) AS stock
        FROM sizes s
        LEFT JOIN product_variants pv ON pv.size_id = s.id AND pv.product_id = ? AND pv.color_id = ?
        GROUP BY s.id, s.name
        HAVING stock >= 0
        ORDER BY s.sort_order ASC, s.name ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $productId, $colorId);
    $stmt->execute();
    $result = $stmt->get_result();

    $sizes = [];
    while ($row = $result->fetch_assoc()) {
        $sizes[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'stock' => (int)$row['stock'],
            'available' => ((int)$row['stock']) > 0
        ];
    }

    echo json_encode(['success' => true, 'sizes' => $sizes]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error', 'error' => $e->getMessage()]);
}
