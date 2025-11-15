<?php
$is_dashboard = ($current_page == "dashboard") ? true : false;

$shop_orders = $data['orders'] ?? [];

?>
<div class="bg-white rounded-lg border border-gray-200">
  <div class="px-6 py-4 border-b border-gray-200">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-900">
        <?php echo $is_dashboard ? 'Recent Orders' : 'All Orders'; ?>
      </h2>
      <?php if ($is_dashboard): ?>
        <a href="?page=orders" class="text-sm text-teal-600 hover:text-teal-700 font-medium">View All</a>
      <?php endif; ?>
    </div>
    
    <?php if(!$is_dashboard): ?>
    <div class="flex gap-3">
      <input type="text" placeholder="Search orders..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-teal-600">
      <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
        Filter
      </button>
      <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
        Sort
      </button>
    </div>
    <?php endif; ?>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Customer</th>
          <?php if (!$is_dashboard): ?>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Phone</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Payment Method</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Payment Status</th>
          <?php endif; ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
          <?php if (!$is_dashboard): ?>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total</th>
          <?php endif; ?>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php if (empty($shop_orders)): ?>
          <tr>
            <td colspan="<?php echo $is_dashboard ? '5' : '13'; ?>" class="px-6 py-8 text-center text-gray-500">
              No orders yet
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($shop_orders as $order): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($order['customer_name'] ?? 'Customer'); ?></td>
              <?php if (!$is_dashboard): ?>
                <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($order['customer_email'] ?? 'N/A'); ?></td>
                <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($order['customer_phone'] ?? 'N/A'); ?></td>
                <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?></td>
                <td class="px-6 py-4 text-sm">
                  <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                    <?php 
                      $payment_status = $order['payment_status'] ?? 'Pending';
                      if ($payment_status === 'Paid') echo 'bg-green-100 text-green-800';
                      elseif ($payment_status === 'Pending') echo 'bg-yellow-100 text-yellow-800';
                      elseif ($payment_status === 'Refunded') echo 'bg-red-100 text-red-800';
                      else echo 'bg-gray-100 text-gray-800';
                    ?>">
                    <?php echo htmlspecialchars($payment_status); ?>
                  </span>
                </td>
              <?php endif; ?>
              <td class="px-6 py-4 text-sm">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                  <?php 
                    $status = $order['status'] ?? 'Pending';
                    if ($status === 'Completed') echo 'bg-green-100 text-green-800';
                    elseif ($status === 'Pending') echo 'bg-yellow-100 text-yellow-800';
                    elseif ($status === 'Cancelled') echo 'bg-red-100 text-red-800';
                    else echo 'bg-blue-100 text-blue-800';
                  ?>">
                  <?php echo htmlspecialchars(ucfirst($status)); ?>
                </span>
              </td>
              <?php if (!$is_dashboard): ?>
                <td class="px-6 py-4 text-sm font-semibold text-gray-900">$<?php echo number_format($order['total_amount'] ?? 0, 2); ?></td>
              <?php endif; ?>
              <td class="px-6 py-4 text-sm text-gray-700">
                <?php 
                  $date = new DateTime($order['created_at'] ?? 'now');
                  echo $date->format('M d, Y');
                ?>
              </td>
              <td class="px-6 py-4 text-sm">
                <a href="?page=order-detail&order_id=<?php echo $order['id']; ?>" class="text-teal-600 hover:text-teal-700 font-medium">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
