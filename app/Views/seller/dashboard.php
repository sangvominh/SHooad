<?php
// Main dashboard file
// $current_page is set by SellerPageService
$page_title = 'Dashboard';

// Sample data (replace with database queries)
$stats = [
  [
    'title' => 'Total Revenue',
    'value' => '$12,458.50',
    'change' => '+23.5%',
    'change_type' => 'positive',
    'icon' => 'chart'
  ],
  [
    'title' => 'Total Orders',
    'value' => '456',
    'change' => '+12.3%',
    'change_type' => 'positive',
    'icon' => 'shopping'
  ],
  [
    'title' => 'New Customers',
    'value' => '89',
    'change' => '+5.2%',
    'change_type' => 'positive',
    'icon' => 'users'
  ],
  [
    'title' => 'Conversion Rate',
    'value' => '3.24%',
    'change' => '-2.1%',
    'change_type' => 'negative',
    'icon' => 'trending'
  ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <div class="flex h-screen">
    <!-- Sidebar now displays inline without toggle -->
    <div class="w-60 h-screen bg-white border-r border-gray-200">
      <?php include 'partials/sidebar.php'; ?>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <?php include 'partials/header.php'; ?>
      
      <!-- Page Content -->
      <main class="flex-1 overflow-auto p-6">
        <?php if ($current_page === 'dashboard'): ?>
          <!-- Stats Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <?php foreach ($stats as $stat): ?>
              <div>
                <?php 
                  $title = $stat['title'];
                  $value = $stat['value'];
                  $change = $stat['change'];
                  $change_type = $stat['change_type'];
                  $icon = $stat['icon'];
                  include 'partials/stats-card.php'; 
                ?>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Tables Row -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
              <?php include 'partials/orders-table.php'; ?>
            </div>
            <div>
              <?php include 'partials/products-table.php'; ?>
            </div>
          </div>
        <?php elseif ($current_page === 'orders'): ?>
          <?php include 'partials/orders-table.php'; ?>
        <?php elseif ($current_page === 'products'): ?>
          <?php include 'partials/products-table.php'; ?>
        <?php else: ?>
          <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <h1 class="text-gray-600">404 Not Found</h1>
          </div>
        <?php endif; ?>
      </main>
    </div>
  </div>
</body>
</html>
