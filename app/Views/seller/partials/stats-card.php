<?php
// Stats card partial
// Variables: $title, $value, $change, $change_type, $icon
$title = $title ?? 'Total Sales';
$value = $value ?? '$0.00';
$change = $change ?? '+0%';
$change_type = $change_type ?? 'positive'; // 'positive', 'negative', 'neutral'
$icon = $icon ?? 'chart';
?>
<div class="bg-white rounded-lg border border-gray-200 p-6">
  <div class="flex items-start justify-between mb-4">
    <div>
      <p class="text-sm text-gray-600 font-medium"><?php echo $title; ?></p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2"><?php echo $value; ?></h3>
    </div>
    <div class="bg-teal-100 p-3 rounded-lg">
      <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <?php if ($icon === 'chart'): ?>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        <?php elseif ($icon === 'shopping'): ?>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        <?php elseif ($icon === 'users'): ?>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h.01M13 16H3v-2a6 6 0 0112 0v2zm4-6a2 2 0 11-4 0 2 2 0 014 0z" />
        <?php elseif ($icon === 'trending'): ?>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        <?php endif; ?>
      </svg>
    </div>
  </div>
  
  <?php if (!empty($change)): ?>
  <div class="flex items-center gap-1">
    <span class="text-sm font-semibold 
      <?php echo $change_type === 'positive' ? 'text-green-600' : ($change_type === 'negative' ? 'text-red-600' : 'text-gray-600'); ?>">
      <?php echo $change; ?>
    </span>
    <span class="text-sm text-gray-600">this week</span>
  </div>
  <?php else: ?>
  <!-- <div class="flex items-center gap-1">
    <span class="text-sm text-gray-500">
      <?php 
      if ($change_type === 'warning') {
        echo 'Requires attention';
      } else {
        echo 'Real-time data';
      }
      ?>
    </span>
  </div> -->
  <?php endif; ?>
</div>
