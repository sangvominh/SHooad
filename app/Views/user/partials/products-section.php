<?php
require_once __DIR__ . '/../../../Models/Product.php';

// Khởi tạo database connection
$db = new Database();
$conn = $db->getConnection();

// Lấy 6 sản phẩm có lượng bán cao nhất
try {
    $query = "
        SELECT 
            id,
            'Fashion' as category,
            name,
            CONCAT('/SHooad/public/assets/products/', COALESCE(thumbnail_url, 'placeholder.jpg')) as image,
            price,
            sold_quantity,
            rating
        FROM products
        WHERE status = 'active'
        ORDER BY sold_quantity DESC
        LIMIT 6
    ";


    $result = $conn->query($query);
    $products = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Thêm các trường mặc định cần thiết cho product card
            $row['reviews'] = $row['reviews'] ?? 0;
            $row['wishlisted'] = $row['wishlisted'] ?? false;
            // Nếu muốn hiển thị số bán trong card về sau, trường sold_quantity đã có sẵn
            $products[] = $row;
        }
    }
} catch (Exception $e) {
    // Fallback data nếu có lỗi
    echo "Không lấy được từ db";
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
            <a href="#" class="inline-block px-8 py-3 border-2 border-gray-800 text-gray-800 font-semibold hover:bg-gray-800 hover:text-white transition-colors">
                See More
            </a>
        </div>
        
        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($products as $product): ?>
                <?php include 'product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
