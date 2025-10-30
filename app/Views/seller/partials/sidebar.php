<?php
// app/Views/seller/partials/sidebar.php
$currentSection = $currentSection ?? 'dashboard';
?>
<!-- Sidebar -->
<aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform -translate-x-full transition-transform lg:translate-x-0">
  <div class="p-4 border-b flex items-center justify-between lg:justify-start">
    <h1 class="text-xl font-bold">SHooad</h1>
    <button id="sidebar-toggle" class="lg:hidden p-2" aria-label="Toggle navigation menu">
      <!-- Hamburger icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
    </button>
  </div>
  <nav class="p-4 space-y-1">
    <?php
      $bp = $basePath ?? '';
      $links = [
        ['href' => $bp . '/seller/dashboard', 'label' => 'Dashboard', 'key' => 'dashboard'],
        ['href' => $bp . '/seller/products/new', 'label' => 'Add Product', 'key' => 'add'],
        ['href' => $bp . '/seller/products', 'label' => 'Product List', 'key' => 'products'],
        ['href' => $bp . '/seller/orders', 'label' => 'Orders', 'key' => 'orders'],
        ['href' => $bp . '/seller/account', 'label' => 'Account', 'key' => 'account'],
      ];
      foreach ($links as $link):
        $isActive = $currentSection === $link['key'];
    ?>
      <a href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>" class="block px-3 py-2 rounded-md <?= $isActive ? 'bg-gray-200 font-semibold' : 'hover:bg-gray-100' ?>">
        <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
