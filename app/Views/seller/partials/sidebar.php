<?php
// Sidebar partial - Navigation menu
$current_page = $current_page ?? 'dashboard';
$menu_items = [
  ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'grid'],
  ['id' => 'orders', 'label' => 'My Orders', 'icon' => 'shopping-bag'],
  ['id' => 'products', 'label' => 'Products', 'icon' => 'box'],
  ['id' => 'analytics', 'label' => 'Analytics', 'icon' => 'chart-bar'],
  ['id' => 'messages', 'label' => 'Messages', 'icon' => 'mail'],
];

$management_items = [
  ['id' => 'inventory', 'label' => 'Inventory', 'icon' => 'package'],
  ['id' => 'pricing', 'label' => 'Pricing', 'icon' => 'tag'],
  ['id' => 'promotions', 'label' => 'Promotions', 'icon' => 'gift'],
  ['id' => 'settings', 'label' => 'Settings', 'icon' => 'cog'],
];
?>
<aside class="w-60 h-full flex flex-col">
  <div class="p-3 flex-1 overflow-y-auto">
    <!-- Logo -->
    <div class="flex items-center gap-2 mb-8">
      <div class="bg-teal-600 text-white px-2 py-1 rounded font-bold text-sm">"logo of shop"</div>
      <span class="font-semibold text-gray-900">"name of shop"</span>
    </div>

    <!-- Main Menu -->
    <nav class="space-y-1 mb-8">
      <?php foreach ($menu_items as $item): ?>
        <a href="?page=<?php echo $item['id']; ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                  <?php echo $current_page === $item['id'] ? 'bg-teal-50 text-teal-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <?php if ($item['icon'] === 'grid'): ?>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
            <?php elseif ($item['icon'] === 'shopping-bag'): ?>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            <?php elseif ($item['icon'] === 'box'): ?>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m0 0L4 7m8 4v10l8-4v-10L12 11zm0 0L4 7" />
            <?php elseif ($item['icon'] === 'chart-bar'): ?>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            <?php elseif ($item['icon'] === 'mail'): ?>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            <?php endif; ?>
          </svg>
          <span><?php echo $item['label']; ?></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- Management Section -->
    <div class="mb-6">
      <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 mb-3">MANAGEMENT</p>
      <nav class="space-y-1">
        <?php foreach ($management_items as $item): ?>
          <a href="?page=<?php echo $item['id']; ?>" 
             class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                    <?php echo $current_page === $item['id'] ? 'bg-teal-50 text-teal-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <?php if ($item['icon'] === 'package'): ?>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m0 0L4 7m8 4v10l8-4v-10L12 11zm0 0L4 7" />
              <?php elseif ($item['icon'] === 'tag'): ?>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              <?php elseif ($item['icon'] === 'gift'): ?>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v-1m0 0H8m4 0h4M4 10a1 1 0 011-1h14a1 1 0 011 1v10a1 1 0 01-1 1H5a1 1 0 01-1-1V10z" />
              <?php elseif ($item['icon'] === 'cog'): ?>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <?php endif; ?>
            </svg>
            <span><?php echo $item['label']; ?></span>
          </a>
        <?php endforeach; ?>
      </nav>
    </div>
  </div>

  <!-- Footer -->
  <div class="border-t border-gray-200 p-2">
    <button class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg w-full mb-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.172l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
      Help Center
    </button>
    <button class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg w-full">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
      </svg>
      Settings
    </button>
  </div>
</aside>
