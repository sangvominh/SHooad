<?php
// Sử dụng lại connection từ products-section nếu có, hoặc tạo mới
if (!isset($conn)) {
    require_once __DIR__ . '/../../../Core/Database.php';
    $db = new Database();
    $conn = $db->getConnection();
}
$categories = [];
$catRes = $conn->query("SELECT id, name FROM categories ORDER BY name ASC LIMIT 10");
if ($catRes) {
  while ($row = $catRes->fetch_assoc()) {
    $categories[] = [
      'id' => $row['id'], 
      'name' => $row['name'],
      'image' => "/SHooad/public/assets/categories/" . strtolower(str_replace(' ', '-', $row['name'])) . ".jpg"
    ];
  }
}
?>
<section class="py-12 px-4 bg-white">
  <div class="max-w-7xl mx-auto">
    <div class="mb-8 text-center">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
        Danh Mục Sản Phẩm
      </h2>
      <p class="text-gray-600">Khám phá các danh mục phổ biến</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-10 gap-3 md:gap-4">
      <?php foreach ($categories as $category): ?>
        <?php 
          // Tạo đường dẫn ảnh từ tên category
          $imagePath = '/SHooad/public/assets/categories/' . $category['name'] . '.jpg';
        ?>
        <a href="/SHooad/public/customer/products?category=<?php echo urlencode($category['name']); ?>" 
           class="group block bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
          <div class="relative aspect-square overflow-hidden bg-gray-50">
            <img 
              src="<?php echo htmlspecialchars($imagePath); ?>"
              alt="<?php echo htmlspecialchars($category['name']); ?>"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
              loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent">
              <div class="absolute bottom-0 left-0 right-0 p-3">
                <h3 class="text-sm md:text-base font-bold text-white text-center">
                  <?php echo htmlspecialchars($category['name']); ?>
                </h3>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>