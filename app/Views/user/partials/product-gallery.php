<!-- partials/product-gallery.php -->
<div class="flex flex-col gap-4">
  <!-- Main Image Container -->
  <div class="bg-yellow-50 rounded-lg p-8 flex items-center justify-center" style="width: 100%; height: 520px; overflow: hidden;">
    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; overflow:hidden;">
      <img id="mainImage"
           src="<?php echo htmlspecialchars($product['main_image']); ?>"
           alt="<?php echo htmlspecialchars($product['name']); ?>"
           class="w-full h-full object-cover transition-opacity duration-200 ease-out"
           style="width:100%; height:100%; object-fit:cover; opacity:1;">
    </div>
  </div>

  <!-- Thumbnail Gallery with Navigation -->
  <div class="flex items-center gap-4">
    <button id="prevBtn" class="text-gray-400 hover:text-gray-600 text-2xl transition px-2" aria-label="Previous">
      &#10094;
    </button>

    <div class="flex gap-3 overflow-x-auto flex-1 py-2">
      <?php
        // tất cả ảnh (main + các ảnh khác). Nếu bạn đang dùng $product['images'] là mảng đầy đủ thì dùng trực tiếp
        $allThumbs = $product['images'] ?? (array_merge([$product['main_image']], $product['thumbnail_images'] ?? []));
        foreach ($allThumbs as $index => $thumbImage):
      ?>
        <img
          src="<?php echo htmlspecialchars($thumbImage); ?>"
          data-index="<?php echo $index; ?>"
          alt="Thumbnail <?php echo $index + 1; ?>"
          class="thumbnail-img w-24 h-24 object-cover rounded cursor-pointer border-2 transition <?php echo ($thumbImage === $product['main_image']) ? 'border-yellow-400' : 'border-gray-200'; ?>"
        />
      <?php endforeach; ?>
    </div>

    <button id="nextBtn" class="text-gray-400 hover:text-gray-600 text-2xl transition px-2" aria-label="Next">
      &#10095;
    </button>
  </div>
</div>
