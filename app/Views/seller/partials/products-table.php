<?php
// Products table partial
// Variables: $products (array of product data)
$products = $products ?? [];
?>
<div class="bg-white rounded-lg border border-gray-200">
  <div class="px-6 py-4 border-b border-gray-200">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-900">Your Products</h2>
      <a href="?page=products&action=add" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700">
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

  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Product Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Price</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Stock</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Sales</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php if (empty($products)): ?>
          <tr>
            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
              No products yet
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($products as $product): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo $product['name'] ?? 'Product'; ?></td>
              <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo $product['price'] ?? '$0.00'; ?></td>
              <td class="px-6 py-4 text-sm">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                  <?php 
                    $stock = $product['stock'] ?? 0;
                    if ($stock > 50) echo 'bg-green-100 text-green-800';
                    elseif ($stock > 10) echo 'bg-yellow-100 text-yellow-800';
                    else echo 'bg-red-100 text-red-800';
                  ?>">
                  <?php echo $stock; ?> units
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700"><?php echo $product['sales'] ?? '0'; ?></td>
              <td class="px-6 py-4 text-sm">
                <button class="text-teal-600 hover:text-teal-700 font-medium">Edit</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
