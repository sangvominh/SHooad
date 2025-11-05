<?php
// Main dashboard file - page routing system
$current_page = $_GET['page'] ?? 'dashboard';
$page_title = 'Dashboard';

// Sample data for dashboard stats
$stats = [
  [
    'label' => 'Active Orders',
    'value' => '12',
    'change' => '3',
    'icon' => '<svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>'
  ],
  [
    'label' => 'Completed Today',
    'value' => '8',
    'change' => '2',
    'icon' => '<svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
  ],
  [
    'label' => 'Earnings Today',
    'value' => '$127.50',
    'change' => '12.5',
    'icon' => '<svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
  ],
  [
    'label' => 'Rating',
    'value' => '4.8',
    'change' => '0.2',
    'icon' => '<svg class="w-6 h-6 text-purple-400" fill="currentColor" viewBox="0 0 24 24"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>'
  ]
];

// Sample active deliveries data
$deliveries = [
  ['id' => 'DEL-001', 'customer' => 'Nguyen Van A', 'pickup' => '123 Main St', 'delivery' => '456 Oak Ave', 'status' => 'in-transit', 'distance' => '2.5 km', 'eta' => '10 mins'],
  ['id' => 'DEL-002', 'customer' => 'Tran Thi B', 'pickup' => '789 Elm St', 'delivery' => '321 Pine St', 'status' => 'at-pickup', 'distance' => '0.3 km', 'eta' => '5 mins'],
  ['id' => 'DEL-003', 'customer' => 'Le Van C', 'pickup' => '654 Maple Dr', 'delivery' => '987 Cedar Ln', 'status' => 'pending', 'distance' => '5.2 km', 'eta' => '25 mins'],
];

// Sample earnings data
$earnings = [
  ['date' => '2024-01-15', 'orders' => 8, 'amount' => '$127.50'],
  ['date' => '2024-01-14', 'orders' => 6, 'amount' => '$98.75'],
  ['date' => '2024-01-13', 'orders' => 10, 'amount' => '$156.25'],
  ['date' => '2024-01-12', 'orders' => 7, 'amount' => '$112.00'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shipper Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-gray-100">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 h-screen bg-slate-900 border-r border-slate-700">
      <?php include 'partials/sidebar.php'; ?>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <?php include 'partials/header.php'; ?>
      
      <!-- Page Content -->
      <main class="flex-1 overflow-auto p-8">
        <?php if ($current_page === 'dashboard'): ?>
          <div class="mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Welcome back, Shipper!</h2>
            <p class="text-gray-400">Here's what's happening with your deliveries today</p>
          </div>

          <!-- Stats Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <?php foreach ($stats as $stat): ?>
              <div>
                <?php 
                  $label = $stat['label'];
                  $value = $stat['value'];
                  $change = $stat['change'];
                  $icon = $stat['icon'];
                  include 'partials/stats-card.php'; 
                ?>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Deliveries Table -->
          <div>
            <?php include 'partials/deliveries-table.php'; ?>
          </div>

        <?php elseif ($current_page === 'active-deliveries'): ?>
          <!-- Page for managing active deliveries with detailed view -->
          <div class="mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Active Deliveries</h2>
            <p class="text-gray-400">Manage and update your ongoing deliveries</p>
          </div>

          <!-- Quick Stats -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
              <p class="text-gray-400 mb-2">Total Active</p>
              <p class="text-3xl font-bold text-blue-400">12</p>
            </div>
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
              <p class="text-gray-400 mb-2">In Transit</p>
              <p class="text-3xl font-bold text-green-400">8</p>
            </div>
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
              <p class="text-gray-400 mb-2">Pending Pickup</p>
              <p class="text-3xl font-bold text-yellow-400">4</p>
            </div>
          </div>

          <!-- Active Deliveries Table -->
          <div class="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden">
            <div class="p-6 border-b border-slate-700">
              <h3 class="text-lg font-semibold text-white">Current Deliveries</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="bg-slate-900 border-b border-slate-700">
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Order ID</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Customer</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Pickup Address</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Delivery Address</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">ETA</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($deliveries as $delivery): ?>
                    <tr class="border-b border-slate-700 hover:bg-slate-700/50 transition">
                      <td class="px-6 py-4 text-sm font-medium text-blue-400"><?php echo $delivery['id']; ?></td>
                      <td class="px-6 py-4 text-sm text-gray-300"><?php echo $delivery['customer']; ?></td>
                      <td class="px-6 py-4 text-sm text-gray-300"><?php echo $delivery['pickup']; ?></td>
                      <td class="px-6 py-4 text-sm text-gray-300"><?php echo $delivery['delivery']; ?></td>
                      <td class="px-6 py-4 text-sm">
                        <?php 
                          $status_class = $delivery['status'] === 'in-transit' ? 'bg-blue-500/20 text-blue-300' : 
                                         ($delivery['status'] === 'at-pickup' ? 'bg-yellow-500/20 text-yellow-300' : 'bg-gray-500/20 text-gray-300');
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $status_class; ?>">
                          <?php echo ucfirst(str_replace('-', ' ', $delivery['status'])); ?>
                        </span>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-300"><?php echo $delivery['eta']; ?></td>
                      <td class="px-6 py-4 text-sm">
                        <button class="text-blue-400 hover:text-blue-300 font-medium">Update</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

        <?php elseif ($current_page === 'earnings'): ?>
          <!-- Page for viewing earnings history -->
          <div class="mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Earnings</h2>
            <p class="text-gray-400">Track your daily and total earnings</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg p-6">
              <p class="text-blue-100 mb-2">This Month</p>
              <p class="text-4xl font-bold text-white">$2,847.50</p>
            </div>
            <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-lg p-6">
              <p class="text-green-100 mb-2">This Week</p>
              <p class="text-4xl font-bold text-white">$687.25</p>
            </div>
            <div class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-lg p-6">
              <p class="text-purple-100 mb-2">Total Earnings</p>
              <p class="text-4xl font-bold text-white">$12,458.00</p>
            </div>
          </div>

          <!-- Earnings Table -->
          <div class="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden">
            <div class="p-6 border-b border-slate-700">
              <h3 class="text-lg font-semibold text-white">Earnings History</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="bg-slate-900 border-b border-slate-700">
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Orders Completed</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Amount Earned</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($earnings as $earning): ?>
                    <tr class="border-b border-slate-700 hover:bg-slate-700/50 transition">
                      <td class="px-6 py-4 text-sm text-gray-300"><?php echo $earning['date']; ?></td>
                      <td class="px-6 py-4 text-sm text-gray-300"><?php echo $earning['orders']; ?></td>
                      <td class="px-6 py-4 text-sm font-semibold text-green-400"><?php echo $earning['amount']; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

        <?php else: ?>
          <div class="bg-slate-800 border border-slate-700 rounded-lg p-12 text-center">
            <p class="text-gray-400">This page hasn't been implemented yet</p>
          </div>
        <?php endif; ?>
      </main>
    </div>
  </div>
</body>
</html>
