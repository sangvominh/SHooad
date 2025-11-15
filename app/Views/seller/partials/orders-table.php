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
        <a href="/SHooad/public/seller/orders" class="text-sm text-teal-600 hover:text-teal-700 font-medium">View All</a>
      <?php endif; ?>
    </div>
    
    <?php if(!$is_dashboard): ?>
    <div class="flex gap-3 mb-3">
      <input type="text" id="orders-search" placeholder="Search by customer name, phone, or email..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-teal-600">
      
      <div class="relative filter-container">
        <button id="orders-filter-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
          </svg>
          Filter
        </button>
        <div id="orders-filter-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
          <div class="p-2">
            <div class="text-xs font-semibold text-gray-500 uppercase mb-2 px-2">Order Status</div>
            <button data-filter-status="all" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">All Statuses</button>
            <button data-filter-status="pending_transfer" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Pending Transfer</button>
            <button data-filter-status="paid" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Paid</button>
            <button data-filter-status="processing" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Processing</button>
            <button data-filter-status="delivering" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Delivering</button>
            <button data-filter-status="pending_cod" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Pending COD</button>
            <button data-filter-status="completed" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Completed</button>
            <button data-filter-status="cancelled" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Cancelled</button>
            <button data-filter-status="failed" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Failed</button>

          </div>
        </div>
      </div>
      
      <div class="relative sort-container">
        <button id="orders-sort-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
          </svg>
          Sort
        </button>
        <div id="orders-sort-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
          <div class="p-2">
            <button data-sort="date-desc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Date: Newest First</button>
            <button data-sort="date-asc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Date: Oldest First</button>
            <button data-sort="total-desc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Total: High to Low</button>
            <button data-sort="total-asc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Total: Low to High</button>
            <button data-sort="name-asc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Name: A to Z</button>
            <button data-sort="name-desc" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Name: Z to A</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Applied Filters Display -->
    <div id="orders-applied-filters" class="hidden mb-3"></div>
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
      <tbody id="orders-table-body" class="divide-y divide-gray-200">
        <?php if (empty($shop_orders)): ?>
          <tr>
            <td colspan="<?php echo $is_dashboard ? '5' : '13'; ?>" class="px-6 py-8 text-center text-gray-500">
              No orders yet
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($shop_orders as $order): ?>
            <tr data-order 
                data-order-id="<?php echo $order['id']; ?>"
                data-customer-name="<?php echo htmlspecialchars($order['customer_name'] ?? 'Customer'); ?>"
                data-customer-email="<?php echo htmlspecialchars($order['customer_email'] ?? ''); ?>"
                data-customer-phone="<?php echo htmlspecialchars($order['customer_phone'] ?? ''); ?>"
                data-status="<?php echo htmlspecialchars($order['status'] ?? 'Pending'); ?>"
                data-payment-status="<?php echo htmlspecialchars($order['payment_status'] ?? 'Pending'); ?>"
                data-total="<?php echo $order['total_amount'] ?? 0; ?>"
                data-date="<?php echo $order['created_at'] ?? date('Y-m-d H:i:s'); ?>"
                class="hover:bg-gray-50 transition-colors">
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
                <a href="/SHooad/public/seller/order-detail?order_id=<?php echo $order['id']; ?>" class="text-teal-600 hover:text-teal-700 font-medium">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
