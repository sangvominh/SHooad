<!-- Product Information Section -->
<div class="flex flex-col gap-4 sm:gap-6 max-w-full">
    <!-- Product Title -->
    <div>
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 break-words"><?php echo htmlspecialchars($product['name']); ?></h1>
    </div>
    
    <!-- Price & Rating -->
    <div class="border-y border-gray-200 py-3 sm:py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <!-- Price -->
            <div id="priceContainer" class="flex flex-wrap items-center gap-2 sm:gap-3 max-w-full">
                <?php 
                $hasPriceRange = isset($product['price_range']) && $product['price_range'];
                $minPrice = $product['min_price'] ?? $product['price'] ?? '';
                $maxPrice = $product['max_price'] ?? $product['price'] ?? '';
                $original = $product['original_price'] ?? '';
                
                if ($hasPriceRange && $minPrice !== $maxPrice) {
                    // Display price range
                    if ($original !== '' && $original > $maxPrice) {
                        $discount = round((($original - $maxPrice) / $original) * 100);
                    ?>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-lg sm:text-2xl lg:text-3xl font-bold text-red-600"><?php echo number_format($minPrice, 0, ',', '.'); ?>₫ - <?php echo number_format($maxPrice, 0, ',', '.'); ?>₫</span>
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs sm:text-sm font-bold whitespace-nowrap">-<?php echo $discount; ?>%</span>
                        </div>
                        <span class="text-sm sm:text-base lg:text-lg text-gray-400 line-through whitespace-nowrap"><?php echo number_format($original, 0, ',', '.'); ?>₫</span>
                    <?php 
                    } else {
                    ?>
                        <span class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900"><?php echo number_format($minPrice, 0, ',', '.'); ?>₫ - <?php echo number_format($maxPrice, 0, ',', '.'); ?>₫</span>
                    <?php 
                    }
                } else {
                    // Single price
                    $price = $product['price'] ?? '';
                    // Nếu có giá gốc và giá gốc khác giá sale thì hiển thị cả hai
                    if ($original !== '' && $original != $price && $original > $price) {
                        $discount = round((($original - $price) / $original) * 100);
                    ?>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-lg sm:text-2xl lg:text-3xl font-bold text-red-600"><?php echo number_format($price, 0, ',', '.'); ?>₫</span>
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs sm:text-sm font-bold whitespace-nowrap">-<?php echo $discount; ?>%</span>
                        </div>
                        <span class="text-sm sm:text-base lg:text-lg text-gray-400 line-through whitespace-nowrap"><?php echo number_format($original, 0, ',', '.'); ?>₫</span>
                    <?php 
                    } else {
                    ?>
                        <span class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900"><?php echo number_format($price, 0, ',', '.'); ?>₫</span>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- Rating -->
            <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                <div class="flex">
                    <?php
                    $rating = floatval($product['rating'] ?? 0);
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= floor($rating)) {
                            echo '<span class="text-yellow-400 text-base sm:text-lg">★</span>';
                        } else {
                            echo '<span class="text-gray-300 text-base sm:text-lg">★</span>';
                        }
                    }
                    ?>
                </div>
                <span class="font-semibold text-gray-900 text-sm sm:text-base"><?php echo number_format($product['rating'], 1); ?></span>
                <span class="text-gray-500 text-xs sm:text-sm">(<?php echo $product['reviews_count']; ?> đánh giá)</span>
            </div>
        </div>
    </div>
    
    <!-- Color Selection -->
    <?php if (!empty($product['colors'])): ?>
    <div>
        <p class="font-semibold mb-3">Màu sắc: <span class="text-red-500">*</span></p>
        <div class="flex gap-3 flex-wrap" id="colorGroup">
            <?php foreach ($product['colors'] as $idx => $color): ?>
            <div class="relative">
                <input type="radio" id="color_<?php echo $idx; ?>" name="color" 
                       value="<?php echo htmlspecialchars($color['name']); ?>" 
                       data-color-id="<?php echo $color['id']; ?>"
                       data-stock="<?php echo $color['stock']; ?>"
                       class="sr-only color-radio" <?php echo $idx === 0 ? 'checked' : ''; ?>
                       <?php echo $color['stock'] <= 0 ? 'disabled' : ''; ?>>
                <label for="color_<?php echo $idx; ?>" 
                       class="color-label flex flex-col gap-1 px-4 py-2 rounded-lg border-2 <?php echo $idx === 0 ? 'border-blue-600 bg-blue-50' : 'border-gray-300'; ?> <?php echo $color['stock'] <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:border-blue-600 hover:bg-blue-50'; ?> transition">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full border border-gray-300 flex-shrink-0" 
                              style="background-color: <?php echo htmlspecialchars($color['code']); ?>;"></span>
                        <span class="text-sm font-medium"><?php echo htmlspecialchars($color['name']); ?></span>
                    </div>
                    <?php if ($color['stock'] <= 0): ?>
                    <span class="text-xs text-red-500 font-semibold">Hết hàng</span>
                    <?php elseif ($color['stock'] <= 10): ?>
                    <span class="text-xs text-orange-500">Còn <?php echo $color['stock']; ?> sản phẩm</span>
                    <?php else: ?>
                    <span class="text-xs text-green-600">Còn <?php echo $color['stock']; ?> sản phẩm</span>
                    <?php endif; ?>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Size Selection -->
    <?php if (!empty($product['sizes'])): ?>
    <div>
        <p class="font-semibold mb-3">Kích thước: <span class="text-red-500">*</span></p>
        <div class="flex gap-2 flex-wrap" id="sizeGroup">
            <?php foreach ($product['sizes'] as $idx => $size): ?>
            <div class="relative group">
                <label for="size_<?php echo $idx; ?>" 
                       class="size-label min-w-[50px] px-3 flex flex-col items-center justify-center border-2 <?php echo $idx === 0 ? 'border-blue-600 bg-blue-50' : 'border-gray-300'; ?> rounded-lg <?php echo $size['stock'] <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:border-blue-600 hover:bg-blue-50'; ?> transition py-2">
                    <input type="radio" id="size_<?php echo $idx; ?>" name="size" 
                           value="<?php echo htmlspecialchars($size['name']); ?>" 
                           data-size-id="<?php echo $size['id']; ?>"
                           data-stock="<?php echo $size['stock']; ?>"
                           class="sr-only size-radio" <?php echo $idx === 0 ? 'checked' : ''; ?>
                           <?php echo $size['stock'] <= 0 ? 'disabled' : ''; ?>>
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($size['name']); ?></span>
                    <?php if ($size['stock'] <= 0): ?>
                    <span class="text-xs text-red-500 font-semibold">Hết</span>
                    <?php elseif ($size['stock'] <= 10): ?>
                    <span class="text-xs text-orange-500">Còn <?php echo $size['stock']; ?></span>
                    <?php else: ?>
                    <span class="text-xs text-green-600">Còn <?php echo $size['stock']; ?></span>
                    <?php endif; ?>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Quantity Selection -->
    <div>
        <p class="font-semibold mb-3">Số lượng:</p>
        <div class="flex items-center gap-2 w-fit">
            <button type="button" id="qtyMinus" class="w-8 h-8 border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition">−</button>
            <input type="number" id="qtyInput" value="1" min="1" class="w-12 h-8 text-center border border-gray-300 rounded" style="appearance: textfield;">
                        <style>
                        /* Ẩn mũi tên lên/xuống của input number cho Chrome, Safari, Edge */
                        input[type=number]::-webkit-inner-spin-button, 
                        input[type=number]::-webkit-outer-spin-button {
                            -webkit-appearance: none;
                            margin: 0;
                        }
                        /* Firefox */
                        input[type=number] {
                            -moz-appearance: textfield;
                        }
                        </style>
            <button type="button" id="qtyPlus" class="w-8 h-8 border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition">+</button>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3">
        <button id="addToCartBtn" type="submit" disabled class="flex-1 bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed transition">
            <i class="fas fa-shopping-cart mr-2"></i>Thêm vào giỏ
        </button>
        <button id="buyNowBtn" type="button" disabled class="flex-1 bg-gray-400 text-gray-700 py-3 rounded-lg font-semibold cursor-not-allowed transition">
            <i class="fas fa-bolt mr-2"></i>Mua ngay
        </button>
    </div>
</div>

<!-- Hidden form for add to cart -->
<form id="addToCartForm" method="POST" style="display:none;">
    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
    <input type="hidden" name="variant_id" id="selectedVariantId" value="">
    <input type="hidden" name="color" id="selectedColor" value="">
    <input type="hidden" name="size" id="selectedSize" value="">
    <input type="hidden" name="quantity" id="selectedQuantity" value="">
</form>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const API_BASE = '/SHooad/app/Routes';
    const PRODUCT_ID = <?php echo (int)$productId; ?>;
    // Quantity input handlers
    var qtyInput = document.getElementById('qtyInput');
    var minusBtn = document.getElementById('qtyMinus');
    var plusBtn = document.getElementById('qtyPlus');
    
    if (qtyInput) {
      qtyInput.addEventListener('wheel', function(e) { e.preventDefault(); });
      qtyInput.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') e.preventDefault();
      });
      qtyInput.style.MozAppearance = 'textfield';
      qtyInput.style.appearance = 'textfield';
    }
    
    if (minusBtn && qtyInput) {
      minusBtn.addEventListener('click', function() {
        var val = parseInt(qtyInput.value, 10) || 1;
        qtyInput.value = Math.max(1, val - 1);
        validateFormAndUpdateButtons();
      });
    }
    
    if (plusBtn && qtyInput) {
      plusBtn.addEventListener('click', function() {
        var val = parseInt(qtyInput.value, 10) || 1;
        qtyInput.value = val + 1;
        validateFormAndUpdateButtons();
      });
    }

    // Listen for color/size/qty changes
    var colorRadios = document.querySelectorAll('input[name="color"]');
    var sizeRadios = document.querySelectorAll('input[name="size"]');
    
    colorRadios.forEach(function(radio) {
      radio.addEventListener('change', function() {
        updateColorLabels();
        const colorId = parseInt(radio.getAttribute('data-color-id') || '0', 10);
        if (colorId > 0) {
          fetchAvailableSizes(PRODUCT_ID, colorId).then(updateSizesUI);
        }
        updatePriceForVariant();
        validateFormAndUpdateButtons();
      });
    });
    
    sizeRadios.forEach(function(radio) {
      radio.addEventListener('change', function() {
        updateSizeLabels();
        const sizeId = parseInt(radio.getAttribute('data-size-id') || '0', 10);
        if (sizeId > 0) {
          fetchAvailableColors(PRODUCT_ID, sizeId).then(updateColorsUI);
        }
        updatePriceForVariant();
        validateFormAndUpdateButtons();
      });
    });
    
    if (qtyInput) {
      qtyInput.addEventListener('change', validateFormAndUpdateButtons);
      qtyInput.addEventListener('input', validateFormAndUpdateButtons);
    }

    function updateColorLabels() {
      var colorGroup = document.getElementById('colorGroup');
      if (!colorGroup) return;
      var labels = colorGroup.querySelectorAll('.color-label');
      var checkedRadio = document.querySelector('input[name="color"]:checked');
      
      labels.forEach(function(label) {
        label.classList.remove('border-blue-600', 'bg-blue-50');
        label.classList.add('border-gray-300');
      });
      
      if (checkedRadio) {
        var checkedLabel = document.querySelector('label[for="' + checkedRadio.id + '"]');
        if (checkedLabel) {
          checkedLabel.classList.remove('border-gray-300');
          checkedLabel.classList.add('border-blue-600', 'bg-blue-50');
        }
      }
    }

    function updateSizeLabels() {
      var sizeGroup = document.getElementById('sizeGroup');
      if (!sizeGroup) return;
      var labels = sizeGroup.querySelectorAll('.size-label');
      var checkedRadio = document.querySelector('input[name="size"]:checked');
      
      labels.forEach(function(label) {
        label.classList.remove('border-blue-600', 'bg-blue-50');
        label.classList.add('border-gray-300');
      });
      
      if (checkedRadio) {
        var checkedLabel = document.querySelector('label[for="' + checkedRadio.id + '"]');
        if (checkedLabel) {
          checkedLabel.classList.remove('border-gray-300');
          checkedLabel.classList.add('border-blue-600', 'bg-blue-50');
        }
      }
    }

    async function validateFormAndUpdateButtons() {
      // Get selected values
      var selectedColor = document.querySelector('input[name="color"]:checked');
      var selectedSize = document.querySelector('input[name="size"]:checked');
      var qty = parseInt(qtyInput.value, 10) || 1;
      
      var colorVal = selectedColor ? selectedColor.value : '';
      var sizeVal = selectedSize ? selectedSize.value : '';
      var colorId  = selectedColor ? parseInt(selectedColor.getAttribute('data-color-id') || '0', 10) : 0;
      var sizeId   = selectedSize ? parseInt(selectedSize.getAttribute('data-size-id') || '0', 10) : 0;
      
      // Get stock from container or body
      var container = document.querySelector('[data-stock]');
      var stock = container ? parseInt(container.getAttribute('data-stock') || '9999', 10) : 9999;
      
      // Check if color/size selection exists
      var hasColorOptions = document.querySelectorAll('input[name="color"]').length > 0;
      var hasSizeOptions = document.querySelectorAll('input[name="size"]').length > 0;
      
      // Validate: color selected (if exists), size selected (if exists), qty >= 1, qty <= stock
      var colorValid = !hasColorOptions || (hasColorOptions && colorVal !== '');
      var sizeValid = !hasSizeOptions || (hasSizeOptions && sizeVal !== '');

      // If both selected, fetch the exact variant to know real stock and id
      var variantOk = true;
      if ((colorValid || !hasColorOptions) && (sizeValid || !hasSizeOptions)) {
        if ((hasColorOptions ? colorId > 0 : true) && (hasSizeOptions ? sizeId > 0 : true)) {
          try {
            const variant = await fetchVariant(PRODUCT_ID, colorId || null, sizeId || null);
            if (variant) {
              stock = variant.stock ?? stock;
              document.getElementById('selectedVariantId').value = variant.id;
              // Store latest stock snapshot for qty validation
              if (container) container.setAttribute('data-stock', String(stock));
            } else {
              variantOk = false;
              document.getElementById('selectedVariantId').value = '';
            }
          } catch (e) {
            variantOk = false;
            document.getElementById('selectedVariantId').value = '';
          }
        }
      }

      var isValid = colorValid && sizeValid && variantOk && qty >= 1 && (stock === 0 || qty <= stock);
      
      var addToCartBtn = document.getElementById('addToCartBtn');
      var buyNowBtn = document.getElementById('buyNowBtn');
      
      if (isValid) {
        addToCartBtn.disabled = false;
        addToCartBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        addToCartBtn.classList.add('bg-teal-700', 'hover:bg-teal-800');
        
        buyNowBtn.disabled = false;
        buyNowBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        buyNowBtn.classList.add('bg-yellow-400', 'hover:bg-yellow-500');
      } else {
        addToCartBtn.disabled = true;
        addToCartBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        addToCartBtn.classList.remove('bg-teal-700', 'hover:bg-teal-800');
        
        buyNowBtn.disabled = true;
        buyNowBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        buyNowBtn.classList.remove('bg-yellow-400', 'hover:bg-yellow-500');
      }
      
      // Update hidden form fields
      document.getElementById('selectedColor').value = colorVal || '';
      document.getElementById('selectedSize').value = sizeVal || '';
      document.getElementById('selectedQuantity').value = qty;
    }

    async function fetchAvailableSizes(productId, colorId) {
      const fd = new FormData();
      fd.append('product_id', String(productId));
      fd.append('color_id', String(colorId));
      const res = await fetch(`${API_BASE}/get-available-sizes.php`, { method: 'POST', body: fd });
      const data = await res.json();
      if (!data.success) throw new Error(data.message || 'fetch sizes failed');
      return data.sizes || [];
    }

    function updateSizesUI(sizes) {
      const sizeInputs = document.querySelectorAll('input.size-radio');
      sizeInputs.forEach(function(input){
        const sizeId = parseInt(input.getAttribute('data-size-id') || '0', 10);
        const info = sizes.find(s => s.id === sizeId);
        const label = document.querySelector('label[for="' + input.id + '"]');
        if (!label) return;
        const stockSpan = label.querySelectorAll('span')[1] || null; // [0]=name, [1]=stock
        if (info) {
          const available = !!info.available;
          input.disabled = !available;
          label.classList.toggle('opacity-50', !available);
          label.classList.toggle('cursor-not-allowed', !available);
          label.classList.toggle('hover:border-blue-600', available);
          label.classList.toggle('hover:bg-blue-50', available);
          if (stockSpan) {
            stockSpan.textContent = available ? (info.stock <= 10 ? `Còn ${info.stock}` : `Còn ${info.stock}`) : 'Hết';
            stockSpan.className = 'text-xs ' + (available ? (info.stock <= 10 ? 'text-orange-500' : 'text-green-600') : 'text-red-500 font-semibold');
          }
          if (!available && input.checked) {
            input.checked = false;
            updateSizeLabels();
          }
        }
      });
    }

    async function fetchAvailableColors(productId, sizeId) {
      const fd = new FormData();
      fd.append('product_id', String(productId));
      fd.append('size_id', String(sizeId));
      const res = await fetch(`${API_BASE}/get-available-colors.php`, { method: 'POST', body: fd });
      const data = await res.json();
      if (!data.success) throw new Error(data.message || 'fetch colors failed');
      return data.colors || [];
    }

    function updateColorsUI(colors) {
      const colorInputs = document.querySelectorAll('input.color-radio');
      colorInputs.forEach(function(input){
        const colorId = parseInt(input.getAttribute('data-color-id') || '0', 10);
        const info = colors.find(c => c.id === colorId);
        const label = document.querySelector('label[for="' + input.id + '"]');
        if (!label) return;
        const spans = label.querySelectorAll('span');
        const stockSpan = spans[2] || null; // [0]=swatch, [1]=name, [2]=stock
        if (info) {
          const available = !!info.available;
          input.disabled = !available;
          label.classList.toggle('opacity-50', !available);
          label.classList.toggle('cursor-not-allowed', !available);
          label.classList.toggle('hover:border-blue-600', available);
          label.classList.toggle('hover:bg-blue-50', available);
          if (stockSpan) {
            stockSpan.textContent = available ? (info.stock <= 10 ? `Còn ${info.stock} sản phẩm` : `Còn ${info.stock} sản phẩm`) : 'Hết hàng';
            stockSpan.className = 'text-xs ' + (available ? (info.stock <= 10 ? 'text-orange-500' : 'text-green-600') : 'text-red-500 font-semibold');
          }
          if (!available && input.checked) {
            input.checked = false;
            updateColorLabels();
          }
        }
      });
    }

    async function fetchVariant(productId, colorId, sizeId) {
      const fd = new FormData();
      fd.append('product_id', String(productId));
      if (colorId) fd.append('color_id', String(colorId));
      if (sizeId) fd.append('size_id', String(sizeId));
      const res = await fetch(`${API_BASE}/get-variant.php`, { method: 'POST', body: fd });
      if (res.status === 404) return null;
      const data = await res.json();
      if (!data.success) return null;
      return data.variant || null;
    }
    
    // Add to cart handler
    var addToCartBtn = document.getElementById('addToCartBtn');
    var addToCartForm = document.getElementById('addToCartForm');
    
    if (addToCartBtn) {
      addToCartBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (addToCartBtn.disabled) return;
        
        // Collect form data
        var formData = new FormData(addToCartForm);
        
        // Debug: log form data
        console.log('Form data:', {
          product_id: formData.get('product_id'),
          color: formData.get('color'),
          size: formData.get('size'),
          quantity: formData.get('quantity')
        });
        
        // Send POST request to add-to-cart endpoint
        fetch('/SHooad/app/Routes/add-to-cart.php', {
          method: 'POST',
          body: formData
        })
        .then(function(response) {
          console.log('Response status:', response.status);
          return response.text().then(function(text) {
            console.log('Response text:', text);
            try {
              return JSON.parse(text);
            } catch (e) {
              throw new Error('Invalid JSON response: ' + text);
            }
          });
        })
        .then(function(data) {
          console.log('Response data:', data);
          if (data.success) {
            // Hiệu ứng rung icon giỏ hàng
            if (window.shakeCartIcon) window.shakeCartIcon();
            
            // Cập nhật badge với tổng số từ server
            if (data.cart_total !== undefined && window.updateCartBadge) {
              window.updateCartBadge(data.cart_total);
            }
            
            // Hiển thị thông báo thành công
            var successMsg = document.createElement('div');
            successMsg.className = 'fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
            successMsg.textContent = 'Đã thêm vào giỏ hàng thành công!';
            document.body.appendChild(successMsg);
            
            setTimeout(function() {
              successMsg.style.opacity = '0';
              successMsg.style.transition = 'opacity 0.5s';
              setTimeout(function() { successMsg.remove(); }, 500);
            }, 2000);
          } else {
            alert('Lỗi: ' + (data.message || 'Không thể thêm sản phẩm vào giỏ hàng'));
          }
        })
        .catch(function(err) {
          console.error('Error:', err);
          alert('An error occurred: ' + err.message);
        });
      });
    }
    
    // Buy Now handler
    var buyNowBtn = document.getElementById('buyNowBtn');
    if (buyNowBtn) {
      buyNowBtn.addEventListener('click', async function(e) {
        e.preventDefault();
        if (buyNowBtn.disabled) return;
        
        // Check if user is logged in
        <?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
        var isLoggedIn = <?php echo isset($_SESSION['customer_id']) ? 'true' : 'false'; ?>;
        if (!isLoggedIn) {
          window.location.href = '/SHooad/public/customer/login';
          return;
        }
        
        // First add to cart
        var formData = new FormData(addToCartForm);
        
        try {
          const response = await fetch('/SHooad/app/Routes/add-to-cart.php', {
            method: 'POST',
            body: formData
          });
          
          const data = await response.json();
          
          if (data.success) {
            // Redirect to cart/checkout
            window.location.href = '/SHooad/public/customer/cart';
          } else {
            alert('Lỗi: ' + (data.message || 'Không thể thêm sản phẩm'));
          }
        } catch (err) {
          console.error('Error:', err);
          alert('Có lỗi xảy ra: ' + err.message);
        }
      });
    }
    
    // Product variants data
    const VARIANTS = <?php echo json_encode($product['variants'] ?? []); ?>;
    const DEFAULT_PRICE = <?php echo $product['price'] ?? 0; ?>;
    const ORIGINAL_PRICE = <?php echo $product['original_price'] ?? 0; ?>;

    // Function to update price based on selected variant
    function updatePriceForVariant() {
      const selectedColor = document.querySelector('input[name="color"]:checked');
      const selectedSize = document.querySelector('input[name="size"]:checked');
      
      const colorId = selectedColor ? parseInt(selectedColor.getAttribute('data-color-id') || '0', 10) : null;
      const sizeId = selectedSize ? parseInt(selectedSize.getAttribute('data-size-id') || '0', 10) : null;
      
      let variantPrice = null;
      let variantOriginalPrice = ORIGINAL_PRICE;
      
      // Find matching variant
      if (VARIANTS && VARIANTS.length > 0) {
        for (const variant of VARIANTS) {
          const variantColorId = variant.color_id ? parseInt(variant.color_id, 10) : null;
          const variantSizeId = variant.size_id ? parseInt(variant.size_id, 10) : null;
          
          // Match based on selected options
          const colorMatch = (colorId === null && variantColorId === null) || (colorId === variantColorId);
          const sizeMatch = (sizeId === null && variantSizeId === null) || (sizeId === variantSizeId);
          
          if (colorMatch && sizeMatch) {
            variantPrice = variant.price ? parseFloat(variant.price) : null;
            break;
          }
        }
      }
      
      // Update price display
      const priceContainer = document.getElementById('priceContainer');
      if (!priceContainer) return;
      
      // Use variant price if available, otherwise use default
      const displayPrice = variantPrice !== null ? variantPrice : DEFAULT_PRICE;
      
      // Always update price display
      let priceHtml = '';
      if (ORIGINAL_PRICE > 0 && ORIGINAL_PRICE > displayPrice) {
        const discount = Math.round(((ORIGINAL_PRICE - displayPrice) / ORIGINAL_PRICE) * 100);
        priceHtml = `
          <div class="flex items-center gap-2">
            <span class="text-3xl font-bold text-red-600">${displayPrice.toLocaleString('vi-VN')}₫</span>
            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm font-bold">-${discount}%</span>
          </div>
          <span class="text-lg text-gray-400 line-through">${ORIGINAL_PRICE.toLocaleString('vi-VN')}₫</span>
        `;
      } else {
        priceHtml = `<span class="text-3xl font-bold text-gray-900">${displayPrice.toLocaleString('vi-VN')}₫</span>`;
      }
      priceContainer.innerHTML = priceHtml;
    }

    // Initial price update
    updatePriceForVariant();
  });
</script>
