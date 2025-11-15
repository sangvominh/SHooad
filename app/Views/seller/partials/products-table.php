<?php
$is_dashboard = $current_page === 'dashboard' ? 'dashboard' : false;
$shop_products = $data['products'] ?? [];
?>

<div class="bg-white rounded-lg border border-gray-200">
  <?php if ($is_dashboard): ?>
  <div class="px-6 py-4 border-b border-gray-200">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-semibold text-gray-900">Top Sold Products</h2>
      <a href="/SHooad/public/seller/products" class="text-sm text-teal-600 hover:text-teal-700 font-medium">View All</a>
    </div>
  </div>
  <?php endif; ?>
  
  <?php if (!$is_dashboard): ?>
  <div class="px-6 py-4 border-b border-gray-200">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-900">Your Products</h2>
      <a href="/SHooad/public/seller/add-product" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700">
        Add Product
      </a>
    </div>
    
    <div class="flex gap-3 mb-3">
      <input type="text" id="products-search" placeholder="Search by product name or brand..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-teal-600">
      
      <div class="relative filter-container">
        <button id="products-filter-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
          </svg>
          Filter
        </button>
        <div id="products-filter-dropdown" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
          <div class="p-2">
            <div class="text-xs font-semibold text-gray-500 uppercase mb-2 px-2">Status</div>
            <button data-filter-product-status="all" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">All Products</button>
            <button data-filter-product-status="active" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Active</button>
            <button data-filter-product-status="paused" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Paused</button>
            <button data-filter-product-status="deleted" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Deleted</button>
            <div class="border-t border-gray-200 my-2"></div>
            <div class="text-xs font-semibold text-gray-500 uppercase mb-2 px-2">Stock Level</div>
            <button data-filter-stock="all" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">All Stock</button>
            <button data-filter-stock="in-stock" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">In Stock</button>
            <button data-filter-stock="low-stock" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Low Stock (≤10)</button>
            <button data-filter-stock="out-of-stock" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Out of Stock</button>
          </div>
        </div>
      </div>
      
      <div class="relative sort-container">
        <button id="products-sort-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
          </svg>
          Sort
        </button>
        <div id="products-sort-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
          <div class="p-2">
            <button data-sort-product="newest" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Newest First</button>
            <button data-sort-product="oldest" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Oldest First</button>
            <button data-sort-product="name-asc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Name: A to Z</button>
            <button data-sort-product="name-desc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Name: Z to A</button>
            <button data-sort-product="price-desc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Price: High to Low</button>
            <button data-sort-product="price-asc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Price: Low to High</button>
            <button data-sort-product="stock-desc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Stock: High to Low</button>
            <button data-sort-product="stock-asc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Stock: Low to High</button>
            <button data-sort-product="sold-desc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Most Sold</button>
            <button data-sort-product="sold-asc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Least Sold</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Applied Filters Display -->
    <div id="products-applied-filters" class="hidden mb-3"></div>
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
      <tbody id="products-table-body" class="divide-y divide-gray-200">
        <?php if (empty($shop_products)): ?>
          <tr>
            <td colspan="<?php echo $is_dashboard ? '5' : '11'; ?>" class="px-6 py-8 text-center text-gray-500">
              No products yet
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($shop_products as $product): ?>
            <tr data-product
                data-product-id="<?php echo $product['id']; ?>"
                data-product-name="<?php echo htmlspecialchars($product['name'] ?? ''); ?>"
                data-product-brand="<?php echo htmlspecialchars($product['brand'] ?? ''); ?>"
                data-product-status="<?php echo htmlspecialchars($product['status'] ?? 'active'); ?>"
                data-product-stock="<?php echo $product['stock'] ?? 0; ?>"
                data-product-price="<?php echo $product['price'] ?? 0; ?>"
                data-product-sold="<?php echo $product['sold'] ?? 0; ?>"
                data-product-created="<?php echo $product['created_at'] ?? date('Y-m-d H:i:s'); ?>"
                class="hover:bg-gray-50 transition-colors">
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
