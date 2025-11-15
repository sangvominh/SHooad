<?php
$is_dashboard = $current_page === 'dashboard' ? 'dashboard' : false;
$shop_products = $data['products'] ?? [];
?>

<div class="bg-white rounded-lg border border-gray-200">
  <?php if (!$is_dashboard): ?>
  <div class="px-6 py-4 border-b border-gray-200">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-900">Your Products</h2>
      <a href="/SHooad/public/seller/add-product" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700">
        Add Product
      </a>
    </div>
    
    <div class="flex gap-3">
      <input type="text" placeholder="Search products..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-teal-600">
      <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
        Filter
      </button>
    </div>
  </div>
  <?php endif; ?>

  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <?php if (!$is_dashboard): ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
          <?php endif; ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Product Name</th>
          <?php if (!$is_dashboard): ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Brand</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Category</th>
          <?php endif; ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Price</th>
          <?php if (!$is_dashboard): ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Original Price</th>
          <?php endif; ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Stock</th>
          <?php if (!$is_dashboard): ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Sold</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Created</th>
          <?php endif; ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php if (empty($shop_products)): ?>
          <tr>
            <td colspan="<?php echo $is_dashboard ? '5' : '11'; ?>" class="px-6 py-8 text-center text-gray-500">
              No products yet
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($shop_products as $product): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <?php if (!$is_dashboard): ?>
              <td class="px-6 py-4 text-sm text-gray-700">#<?php echo $product['id']; ?></td>
              <?php endif; ?>
              
              <td class="px-6 py-4 text-sm font-medium text-gray-900">
                <div class="max-w-xs truncate" title="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>">
                  <?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>
                </div>
              </td>
              
              <?php if (!$is_dashboard): ?>
              <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($product['brand'] ?? '-'); ?></td>
              <td class="px-6 py-4 text-sm text-gray-700"><?php echo $product['category_id'] ?? '-'; ?></td>
              <?php endif; ?>
              
              <td class="px-6 py-4 text-sm font-medium text-gray-900">$<?php echo number_format($product['price'] ?? 0, 2); ?></td>
              
              <?php if (!$is_dashboard): ?>
              <td class="px-6 py-4 text-sm text-gray-500 line-through">$<?php echo number_format($product['original_price'] ?? 0, 2); ?></td>
              <?php endif; ?>
              
              <td class="px-6 py-4 text-sm">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                  <?php 
                    $stock = $product['stock'] ?? 0;
                    if ($stock > 50) echo 'bg-green-100 text-green-800';
                    elseif ($stock > 10) echo 'bg-yellow-100 text-yellow-800';
                    else echo 'bg-red-100 text-red-800';
                  ?>">
                  <?php echo $stock; ?>
                </span>
              </td>
              
              <?php if (!$is_dashboard): ?>
              <td class="px-6 py-4 text-sm text-gray-700"><?php echo $product['sold'] ?? '0'; ?></td>
              
              <td class="px-6 py-4 text-sm">
                <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                  <?php 
                    $status = $product['status'] ?? 'active';
                    if ($status === 'active') echo 'bg-green-100 text-green-800';
                    elseif ($status === 'paused') echo 'bg-yellow-100 text-yellow-800';
                    else echo 'bg-red-100 text-red-800';
                  ?>">
                  <?php echo ucfirst($status); ?>
                </span>
              </td>
              
              <td class="px-6 py-4 text-sm text-gray-600">
                <?php 
                  $created = $product['created_at'] ?? '';
                  echo $created ? date('Y-m-d', strtotime($created)) : '-';
                ?>
              </td>
              <?php endif; ?>
              
              <td class="px-6 py-4 text-sm">
                <a href="/SHooad/public/seller/product-detail?product_id=<?php echo $product['id']; ?>" class="text-teal-600 hover:text-teal-700 font-medium">
                  <?php echo $is_dashboard ? 'Edit' : 'View/Edit'; ?>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
