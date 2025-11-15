<?php
// Main dashboard file
// $current_page is set by SellerPageService
$page_title = 'Dashboard';

// Get real statistics from database
require_once __DIR__ . '/../../Services/Seller/SellerService.php';
$sellerService = new SellerService();
$shopId = $_SESSION['shop_id'] ?? null;

if ($shopId) {
    $dashboardStats = $sellerService->getDashboardStats($shopId);
    
    $stats = [
      [
        'title' => 'Total Revenue',
        'value' => '$' . number_format($dashboardStats['total_revenue'], 2),
        'change' => '',
        'change_type' => 'neutral',
        'icon' => 'chart'
      ],
      [
        'title' => 'Total Orders',
        'value' => number_format($dashboardStats['total_orders']),
        'change' => '',
        'change_type' => 'neutral',
        'icon' => 'shopping'
      ],
      [
        'title' => 'Total Products',
        'value' => number_format($dashboardStats['total_products']),
        'change' => '',
        'change_type' => 'neutral',
        'icon' => 'users'
      ],
      [
        'title' => 'Pending Orders',
        'value' => number_format($dashboardStats['pending_orders']),
        'change' => '',
        'change_type' => 'warning',
        'icon' => 'trending'
      ]
    ];
} else {
    // Fallback if no shop_id
    $stats = [
      ['title' => 'Total Revenue', 'value' => '$0.00', 'change' => '', 'change_type' => 'neutral', 'icon' => 'chart'],
      ['title' => 'Total Orders', 'value' => '0', 'change' => '', 'change_type' => 'neutral', 'icon' => 'shopping'],
      ['title' => 'Total Products', 'value' => '0', 'change' => '', 'change_type' => 'neutral', 'icon' => 'users'],
      ['title' => 'Pending Orders', 'value' => '0', 'change' => '', 'change_type' => 'neutral', 'icon' => 'trending']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="/SHooad/app/Views/seller/js/orders-filter.js" defer></script>
  <script src="/SHooad/app/Views/seller/js/products-filter.js" defer></script>
</head>
<body class="bg-gray-50">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <?php include 'partials/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header (only for dashboard) -->
      <?php if ($current_page === 'dashboard'): ?>
        <?php include 'partials/header.php'; ?>
      <?php endif; ?>
      
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
          <?php 
          // Coming soon pages
          $coming_soon_pages = ['messages', 'inventory', 'pricing', 'promotions', 'settings'];
          if (in_array($current_page, $coming_soon_pages)): 
          ?>
            <?php include 'coming-soon.php'; ?>
          <?php else: ?>
            <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
              <h1 class="text-gray-600">404 Not Found</h1>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </main>
    </div>
  </div>
</body>
</html>
