<?php
require_once __DIR__ . '/../../../Models/Product.php';

// Khởi tạo database connection
$db = new Database();
$conn = $db->getConnection();

// Lấy 10 sản phẩm mới nhất dựa trên created_at
$products = [];
try {
    // Query tối ưu: lấy sản phẩm và ảnh trong 1 query duy nhất
    $query = "
        SELECT 
            p.id,
            p.name,
            p.brand,
            p.price,
            GROUP_CONCAT(pi.filename ORDER BY pi.id LIMIT 2) as images
        FROM products p
        LEFT JOIN product_images pi ON pi.product_id = p.id
        WHERE p.status = 'active'
        GROUP BY p.id
        HAVING images IS NOT NULL
        ORDER BY p.created_at DESC
        LIMIT 10
    ";

    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imageFiles = explode(',', $row['images']);
            $row['image'] = '/SHooad/public/assets/products/' . trim($imageFiles[0]);
            $row['image_hover'] = isset($imageFiles[1]) ? '/SHooad/public/assets/products/' . trim($imageFiles[1]) : $row['image'];
            $products[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Error fetching products: " . $e->getMessage());
}
?>

<section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8 mb-12">
            <div>
                <h2 class="text-4xl md:text-5xl font-bold text-black mb-4">Our popular products</h2>
                <p class="text-gray-600 text-lg max-w-md">Browse our most popular products and make your day more beautiful and glorious.</p>
            </div>
            <a href="#" class="inline-block px-8 py-3 border-2 border-[#001F5D] text-[#001F5D] font-semibold hover:bg-[#001F5D] hover:text-white transition-colors">
                See More
            </a>
        </div>
        
        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            <?php foreach ($products as $product): ?>
                <div class="relative group">
                    <?php include __DIR__ . '/../components/product-card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
(function() {
    if (window.cartHandlerInit) return;
    window.cartHandlerInit = true;
    
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.add-to-cart-btn');
        if (!btn) return;
        
        e.preventDefault();
        const productId = btn.getAttribute('data-product-id');
        if (!productId) return;
        
        if (window.shakeCartIcon) window.shakeCartIcon();
        
        try {
            const fd = new FormData();
            fd.append('product_id', productId);
            fd.append('quantity', 1);
            const res = await fetch('/SHooad/app/Routes/update-cart.php', {
                method: 'POST',
                body: fd
            });
            const json = await res.json();
            if (json?.cart_total !== undefined && window.updateCartBadge) {
                window.updateCartBadge(json.cart_total);
            }
        } catch (err) {
            console.error('Cart error:', err);
        }
    });
})();
</script>
