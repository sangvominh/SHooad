<?php
// Orders table partial
// Variables: $orders (array of order data)
$orders = $orders ?? [];
?>
<div class="bg-white rounded-lg border border-gray-200">
  <div class="px-6 py-4 border-b border-gray-200">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
      <a href="?page=orders" class="text-sm text-teal-600 hover:text-teal-700 font-medium">View All</a>
    </div>
    
    <div class="flex gap-3">
      <input type="text" placeholder="Search orders..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-teal-600">
      <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
        Filter
      </button>
      <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
        Sort
      </button>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Customer</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Amount</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
              No orders yet
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $order): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm text-gray-700"><?php echo $order['buyer_id'] ?? 'Customer'; ?></td>
              <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo $order['quantity'] ?? '$0.00'; ?></td>
              <td class="px-6 py-4 text-sm">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                  <?php 
                    $status = $order['order_status'] ?? 'pending';
                    if ($status === 'completed') echo 'bg-green-100 text-green-800';
                    elseif ($status === 'pending') echo 'bg-yellow-100 text-yellow-800';
                    elseif ($status === 'cancelled') echo 'bg-red-100 text-red-800';
                    else echo 'bg-gray-100 text-gray-800';
                  ?>">
                  <?php echo ucfirst($status); ?>
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700"><?php echo $order['order_date'] ?? 'N/A'; ?></td>
              <td class="px-6 py-4 text-sm">
                <button class="text-teal-600 hover:text-teal-700 font-medium">View</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
