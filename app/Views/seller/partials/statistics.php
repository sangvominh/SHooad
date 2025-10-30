<?php
// app/Views/seller/partials/statistics.php
?>
<section class="mb-6">
  <h2 class="mb-4 text-2xl font-bold">Overview</h2>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-lg bg-white p-4 shadow">
      <div class="text-sm text-gray-500">Total Products</div>
      <div class="text-3xl font-semibold"><?= htmlspecialchars((string)($statistics['total_products'] ?? 0), ENT_QUOTES, 'UTF-8') ?></div>
    </div>
    <div class="rounded-lg bg-white p-4 shadow">
      <div class="text-sm text-gray-500">Active Listings</div>
      <div class="text-3xl font-semibold"><?= htmlspecialchars((string)($statistics['active_listings'] ?? 0), ENT_QUOTES, 'UTF-8') ?></div>
    </div>
    <div class="rounded-lg bg-white p-4 shadow">
      <div class="text-sm text-gray-500">Monthly Sales</div>
      <div class="text-3xl font-semibold"><?= htmlspecialchars((string)($statistics['monthly_sales'] ?? 0), ENT_QUOTES, 'UTF-8') ?></div>
    </div>
    <div class="rounded-lg bg-white p-4 shadow">
      <div class="text-sm text-gray-500">Monthly Revenue</div>
      <div class="text-3xl font-semibold">$<?= number_format((float)($statistics['monthly_revenue'] ?? 0.0), 2) ?></div>
    </div>
  </div>
</section>
