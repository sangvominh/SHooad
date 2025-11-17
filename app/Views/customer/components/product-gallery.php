<!-- partials/product-gallery.php -->
<div class="flex flex-col gap-4">
  <!-- Main Image Container -->
  <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-center" style="width: 100%; height: 520px; overflow: hidden;">
    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
      <img id="mainImage"
           src="<?php echo htmlspecialchars($product['main_image']); ?>"
           alt="<?php echo htmlspecialchars($product['name']); ?>"
           class="max-w-full max-h-full object-contain transition-opacity duration-200 ease-out"
           style="object-fit:contain; opacity:1;">
    </div>
  </div>

  <!-- Thumbnail Gallery with Navigation -->
  <div class="flex items-center gap-4">
    <button id="prevBtn" class="text-gray-400 hover:text-gray-600 text-2xl transition px-2" aria-label="Previous">
      &#10094;
    </button>

    <div class="flex gap-3 overflow-x-auto flex-1 py-2 scrollbar-hide">
      <?php
        // tất cả ảnh (main + các ảnh khác). Nếu bạn đang dùng $product['images'] là mảng đầy đủ thì dùng trực tiếp
        $allThumbs = $product['images'] ?? (array_merge([$product['main_image']], $product['thumbnail_images'] ?? []));
        foreach ($allThumbs as $index => $thumbImage):
      ?>
        <div class="flex-shrink-0 w-20 h-20 border-2 rounded cursor-pointer transition hover:border-blue-500 <?php echo ($index === 0) ? 'border-blue-500' : 'border-gray-200'; ?>" data-index="<?php echo $index; ?>">
          <img
            src="<?php echo htmlspecialchars($thumbImage); ?>"
            alt="Thumbnail <?php echo $index + 1; ?>"
            class="thumbnail-img w-full h-full object-cover rounded"
          />
        </div>
      <?php endforeach; ?>
    </div>

    <button id="nextBtn" class="text-gray-400 hover:text-gray-600 text-2xl transition px-2" aria-label="Next">
      &#10095;
    </button>
  </div>
</div>
