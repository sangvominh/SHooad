<?php
// app/Views/seller/partials/recent-products.php
$recentProducts = $recentProducts ?? [];
$totalProducts = $totalProducts ?? 0;
?>
<section>
  <div class="mb-4 flex items-center justify-between">
    <h2 class="text-2xl font-bold">Recent Active Products</h2>
    <?php $bp = $basePath ?? ''; if ($totalProducts > 20): ?>
      <a href="<?= htmlspecialchars($bp . '/seller/products', ENT_QUOTES, 'UTF-8') ?>" class="text-sm text-blue-600 hover:underline">View All Products</a>
    <?php endif; ?>
  </div>

  <?php if (empty($recentProducts)): ?>
    <div class="rounded bg-white p-6 text-center text-gray-600 shadow">
      You have no products yet. <a class="text-blue-600 hover:underline" href="/seller/products/new">Add Product</a>
    </div>
  <?php else: ?>
    <div class="overflow-hidden rounded bg-white shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          <?php foreach ($recentProducts as $p): ?>
            <tr>
              <td class="whitespace-nowrap px-6 py-4">
                <span class="block max-w-xs truncate" title="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>">
                  <?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>
                </span>
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                $<?= number_format((float)$p['price'], 2) ?>
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                <?= htmlspecialchars($p['status'], ENT_QUOTES, 'UTF-8') ?>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-right space-x-2">
                <a href="<?= htmlspecialchars($bp . '/seller/dashboard/products/' . (string)$p['id'] . '/edit', ENT_QUOTES, 'UTF-8') ?>" class="rounded bg-blue-600 px-3 py-1 text-white hover:bg-blue-700">Edit</a>
                <form method="post" action="<?= htmlspecialchars($bp . '/seller/dashboard/products/' . (string)$p['id'] . '/pause', ENT_QUOTES, 'UTF-8') ?>" class="inline">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                  <button type="submit" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">Pause</button>
                </form>
                <button type="button" class="rounded bg-red-600 px-3 py-1 text-white hover:bg-red-700" onclick="if(window.showDeleteModal){ window.showDeleteModal(<?= (int)$p['id'] ?>, '<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>'); }">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>
