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
    $sizeId    = isset($_POST['size_id']) ? intval($_POST['size_id']) : 0;

    if ($productId <= 0 || $sizeId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing product_id or size_id']);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    $sql = "
        SELECT c.id, c.name, c.hex_code, COALESCE(SUM(pv.stock), 0) AS stock
        FROM colors c
        LEFT JOIN product_variants pv ON pv.color_id = c.id AND pv.product_id = ? AND pv.size_id = ?
        GROUP BY c.id, c.name, c.hex_code
        HAVING stock >= 0
        ORDER BY c.name ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $productId, $sizeId);
    $stmt->execute();
    $result = $stmt->get_result();

    $colors = [];
    while ($row = $result->fetch_assoc()) {
        $colors[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'hex_code' => $row['hex_code'],
            'stock' => (int)$row['stock'],
            'available' => ((int)$row['stock']) > 0
        ];
    }

    echo json_encode(['success' => true, 'colors' => $colors]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error', 'error' => $e->getMessage()]);
}
