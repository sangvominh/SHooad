<?php
$mysqli =   new mysqli('localhost', 'root', '', 'SHooad');
$categories = [];
if (!$mysqli->connect_error) {
  $catRes = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC LIMIT 8");
  if ($catRes) {
    while ($row = $catRes->fetch_assoc()) {
      $categories[] = ['id' => $row['id'], 'name' => $row['name'], 'active' => false];
    }
    $catRes->free();
  }
  $mysqli->close();
}
?>
<section class="py-16 px-4 bg-gray-50">
  <div class="max-w-7xl mx-auto">
    <div class="mb-12">
      <h2 class="text-4xl md:text-5xl font-bold font-serif text-gray-900">
        Explore, find exactly<br />what you need
      </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($categories as $category): ?>
        <div class="group">
          <a href="/SHooad/app/Views/user/products.php?category=<?php echo urlencode($category['name']); ?>" class="block <?php echo $category['active'] ? 'border-4 border-teal-600' : 'border-4 border-transparent'; ?> bg-yellow-100 rounded-lg overflow-hidden transition-transform hover:scale-105 cursor-pointer">
            <div class="h-48 flex items-center justify-center overflow-hidden bg-yellow-50">
              <span class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($category['name']); ?></span>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>