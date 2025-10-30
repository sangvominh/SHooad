<?php
// app/Views/seller/partials/flash-messages.php
if (!empty($_SESSION['flash_message'])): ?>
  <div class="mb-4 rounded border border-green-400 bg-green-50 px-4 py-3 text-green-700">
    <?= htmlspecialchars($_SESSION['flash_message'], ENT_QUOTES, 'UTF-8') ?>
  </div>
  <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="mb-4 rounded border border-red-400 bg-red-50 px-4 py-3 text-red-700">
    <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
  </div>
  <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
