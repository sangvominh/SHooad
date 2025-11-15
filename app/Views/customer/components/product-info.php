<!-- Product Information Section -->
<div class="flex flex-col gap-6">
    <!-- Brand & Title -->
    <div>
        <?php if (!empty($product['brand'])): ?>
        <p class="text-blue-600 text-sm font-medium mb-1"><?php echo htmlspecialchars($product['brand']); ?></p>
        <?php endif; ?>
        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
        <?php if (!empty($product['shop_name'])): ?>
        <div class="flex items-center gap-4 mt-2">
            <p class="text-gray-700 text-sm flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                <i class="fas fa-store text-blue-600"></i>
                <span class="text-gray-600">Shop:</span>
                <a href="/SHooad/public/customer/shop-detail?shop_id=<?php echo $product['shop_id'] ?? ''; ?>" class="text-blue-600 hover:underline font-medium"><?php echo htmlspecialchars($product['shop_name']); ?></a>
            </p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Price & Rating -->
    <div class="border-t border-b border-gray-200 py-4">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <?php 
                $price = $product['price'] ?? '';
                $original = $product['original_price'] ?? '';
                // Nếu có giá gốc và giá gốc khác giá sale thì hiển thị cả hai
                if ($original !== '' && $original != $price && $original > $price) {
                    $discount = round((($original - $price) / $original) * 100);
                ?>
                    <div class="flex items-center gap-2">
                        <span class="text-3xl font-bold text-red-600"><?php echo number_format($price, 0, ',', '.'); ?>₫</span>
                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm font-bold">-<?php echo $discount; ?>%</span>
                    </div>
                    <span class="text-lg text-gray-400 line-through"><?php echo number_format($original, 0, ',', '.'); ?>₫</span>
                <?php 
                } else {
                ?>
                    <span class="text-3xl font-bold text-gray-900"><?php echo number_format($price, 0, ',', '.'); ?>₫</span>
                <?php } ?>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex">
                    <?php
                    $rating = floatval($product['rating'] ?? 0);
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= floor($rating)) {
                            echo '<span class="text-yellow-400 text-lg">★</span>';
                        } else {
                            echo '<span class="text-gray-300 text-lg">★</span>';
                        }
                    }
                    ?>
                </div>
                <span class="font-semibold text-gray-900"><?php echo number_format($product['rating'], 1); ?></span>
                <span class="text-gray-500">(<?php echo $product['reviews_count']; ?> reviews)</span>
            </div>
        </div>
        
        <!-- Stock Info -->
        <div class="mt-3 flex items-center gap-4 text-sm">
            <?php 
            $stock = intval($product['stock'] ?? 0);
            if ($stock > 0):
            ?>
                <span class="text-green-600 font-medium flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> In Stock (<?php echo $stock; ?> available)
                </span>
            <?php else: ?>
                <span class="text-red-600 font-medium flex items-center gap-1">
                    <i class="fas fa-times-circle"></i> Out of Stock
                </span>
            <?php endif; ?>
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
                       class="sr-only color-radio" <?php echo $idx === 0 ? 'checked' : ''; ?>>
                <label for="color_<?php echo $idx; ?>" 
                       class="color-label flex items-center gap-2 px-4 py-2 rounded-lg cursor-pointer border-2 <?php echo $idx === 0 ? 'border-blue-600 bg-blue-50' : 'border-gray-300'; ?> hover:border-blue-600 hover:bg-blue-50 transition">
                    <span class="w-6 h-6 rounded-full border border-gray-300 flex-shrink-0" 
                          style="background-color: <?php echo htmlspecialchars($color['code']); ?>;"></span>
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($color['name']); ?></span>
                    <?php if ($color['stock'] <= 0): ?>
                    <span class="text-xs text-red-500">(Hết hàng)</span>
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
            <label for="size_<?php echo $idx; ?>" 
                   class="size-label min-w-[50px] h-10 px-3 flex items-center justify-center border-2 <?php echo $idx === 0 ? 'border-blue-600 bg-blue-50' : 'border-gray-300'; ?> rounded-lg cursor-pointer hover:border-blue-600 hover:bg-blue-50 transition">
                <input type="radio" id="size_<?php echo $idx; ?>" name="size" 
                       value="<?php echo htmlspecialchars($size['name']); ?>" 
                       data-size-id="<?php echo $size['id']; ?>"
                       data-stock="<?php echo $size['stock']; ?>"
                       class="sr-only size-radio" <?php echo $idx === 0 ? 'checked' : ''; ?>>
                <span class="text-sm font-medium"><?php echo htmlspecialchars($size['name']); ?></span>
                <?php if ($size['stock'] <= 0): ?>
                <span class="ml-1 text-xs text-red-500">✗</span>
                <?php endif; ?>
            </label>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Quantity Selection -->
    <div>
        <p class="font-semibold mb-3">Quantity:</p>
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
    
    <!-- Product Description -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
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
        validateFormAndUpdateButtons();
      });
    });
    
    sizeRadios.forEach(function(radio) {
      radio.addEventListener('change', function() {
        updateSizeLabels();
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

    function validateFormAndUpdateButtons() {
      // Get selected values
      var selectedColor = document.querySelector('input[name="color"]:checked');
      var selectedSize = document.querySelector('input[name="size"]:checked');
      var qty = parseInt(qtyInput.value, 10) || 1;
      
      var colorVal = selectedColor ? selectedColor.value : '';
      var sizeVal = selectedSize ? selectedSize.value : '';
      
      // Get stock from container or body
      var container = document.querySelector('[data-stock]');
      var stock = container ? parseInt(container.getAttribute('data-stock') || '9999', 10) : 9999;
      
      // Check if color/size selection exists
      var hasColorOptions = document.querySelectorAll('input[name="color"]').length > 0;
      var hasSizeOptions = document.querySelectorAll('input[name="size"]').length > 0;
      
      // Validate: color selected (if exists), size selected (if exists), qty >= 1, qty <= stock
      var colorValid = !hasColorOptions || (hasColorOptions && colorVal !== '');
      var sizeValid = !hasSizeOptions || (hasSizeOptions && sizeVal !== '');
      var isValid = colorValid && sizeValid && qty >= 1 && (stock === 0 || qty <= stock);
      
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
    
    // Initial updates
    updateColorLabels();
    updateSizeLabels();
    validateFormAndUpdateButtons();
  });
</script>
    <p class="text-gray-600 leading-relaxed">
        <?php echo htmlspecialchars($product['description']); ?>
    </p>
    
    <!-- Action Buttons -->
    <div class="flex flex-col gap-3">
        <button id="addToCartBtn" type="submit" disabled class="w-full bg-gray-400 text-white py-3 font-semibold cursor-not-allowed transition">
            Add to Cart
        </button>
        <button id="buyNowBtn" type="button" disabled class="w-full bg-gray-400 text-black py-3 font-semibold cursor-not-allowed transition">
            Buy Now
        </button>
    </div>
</div>

<!-- Hidden form for add to cart -->
<form id="addToCartForm" method="POST" style="display:none;">
    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
    <input type="hidden" name="color" id="selectedColor" value="">
    <input type="hidden" name="size" id="selectedSize" value="">
    <input type="hidden" name="quantity" id="selectedQuantity" value="">
</form>
