<?php
// app/Views/seller/dashboard.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Seller Dashboard - SHooad</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>window.__BASE_PATH__ = "<?= htmlspecialchars($basePath ?? '', ENT_QUOTES, 'UTF-8') ?>";</script>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="flex min-h-screen">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <main class="flex-1 p-6 lg:ml-64">
      <?php include __DIR__ . '/partials/flash-messages.php'; ?>

      <?php include __DIR__ . '/partials/statistics.php'; ?>

      <?php include __DIR__ . '/partials/recent-products.php'; ?>
    </main>
  </div>

  <?php include __DIR__ . '/partials/delete-modal.php'; ?>

  <script src="<?= htmlspecialchars(($basePath ?? '') . '/js/dashboard.js', ENT_QUOTES, 'UTF-8') ?>"></script>
</body>
</html>
