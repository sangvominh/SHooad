<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$cart_items = [];
if (isset($_SESSION['user_id'])) {
    $userId = intval($_SESSION['user_id']);
    $mysqli = new mysqli('localhost', 'root', '', 'SHooad');
    if (!$mysqli->connect_error) {
        $sql = "SELECT c.id AS cart_id, c.product_id, c.color, c.size, c.quantity, c.selected, p.name, p.price, p.original_price, p.stock, p.colors AS available_colors, p.sizes AS available_sizes, pi.filename AS image_file
                FROM carts c
                JOIN products p ON p.id = c.product_id
                LEFT JOIN product_images pi ON pi.product_id = p.id
                WHERE c.user_id = " . $userId . "
                GROUP BY c.id
                ORDER BY c.created_at DESC";

        $res = $mysqli->query($sql);
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                // Resolve thumbnail path from product images
                $thumb = $row['image_file'];
                if (!empty($thumb)) {
                    $thumb = '/SHooad/public/assets/products/' . $thumb;
                } else {
                    $thumb = '/SHooad/public/assets/logo/default-avatar.png';
                }

                $cart_items[] = [
                    'cart_id' => $row['cart_id'],
                    'id' => $row['product_id'],
                    'name' => $row['name'],
                    'image' => $thumb,
                    'price' => $row['price'],
                    'original_price' => $row['original_price'],
                    'size' => $row['size'],
                    'color' => $row['color'],
                    'quantity' => $row['quantity'],
                    'stock' => $row['stock'],
                    'available_colors' => $row['available_colors'],
                    'available_sizes' => $row['available_sizes'],
                    'selected' => $row['selected']
                ];
            }
            $res->free();
        }
        $mysqli->close();
    }
}
?>

<style>
/* Hide number input spinners for quantity inputs on this page */
input.no-spinner::-webkit-outer-spin-button,
input.no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
input.no-spinner { -moz-appearance: textfield; }
</style>

<div class="space-y-4">
    <h2 class="text-2xl font-bold text-gray-900">Shopping cart (<span id="cart-count"><?php echo count($cart_items); ?></span> items)</h2>
    
    <div class="space-y-4">
        <?php foreach ($cart_items as $index => $item): ?>
            <?php include 'partials/cart-item.php'; ?>
        <?php endforeach; ?>
    </div>
</div>
