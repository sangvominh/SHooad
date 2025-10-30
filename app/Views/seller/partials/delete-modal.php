<?php
// app/Views/seller/partials/delete-modal.php
?>
<div id="delete-modal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/50 p-4">
  <div class="w-full max-w-md rounded bg-white p-6 shadow-lg" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
    <h3 id="delete-modal-title" class="mb-2 text-lg font-semibold">Confirm Delete</h3>
    <p class="mb-4 text-sm text-gray-700">Type the product name exactly to confirm deletion:</p>
    <div class="mb-4">
      <div class="mb-1 text-sm text-gray-500">Product Name</div>
      <div id="product-name-display" class="truncate rounded border bg-gray-50 p-2 text-gray-800"></div>
    </div>
    <form id="delete-form" method="post" action="#">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
      <input id="delete-confirm-input" name="confirm_name" type="text" class="mb-4 w-full rounded border p-2" placeholder="Type product name to confirm" />
      <div class="flex justify-end gap-2">
        <button type="button" class="rounded border px-4 py-2" onclick="hideDeleteModal()">Cancel</button>
        <button id="delete-confirm-button" type="submit" class="rounded bg-red-600 px-4 py-2 text-white disabled:opacity-50" disabled>Confirm Delete</button>
      </div>
    </form>
  </div>
</div>
