<!-- Product Information Section -->
<div class="flex flex-col gap-6">
    <!-- Category & Title -->
    <div>
    <p class="text-gray-500 text-sm mb-2"><?php echo htmlspecialchars($product['category'] ?? ''); ?></p>
        <h1 class="text-3xl font-bold text-gray-900"><?php echo htmlspecialchars($product['name']); ?></h1>
    </div>
    
    <!-- Price & Rating -->
    <div class="flex items-center gap-6">
        <div class="flex gap-2">
            <?php 
            $price = $product['price'] ?? '';
            $original = $product['original_price'] ?? '';
            // Nếu có giá gốc và giá gốc khác giá sale thì hiển thị cả hai
            if ($original !== '' && $original != $price) {
            ?>
                <span class="text-xl text-gray-400 line-through">$<?php echo htmlspecialchars($original); ?></span>
                <span class="text-2xl font-bold text-red-500">$<?php echo htmlspecialchars($price); ?></span>
            <?php 
            } else {
            ?>
                <span class="text-2xl font-bold text-red-500">$<?php echo htmlspecialchars($price); ?></span>
            <?php } ?>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-yellow-400">★</span>
            <span class="font-semibold"><?php echo $product['rating']; ?></span>
            <span class="text-gray-500">(<?php echo $product['reviews_count']; ?>)</span>
        </div>
    </div>
    
    <!-- Color Selection -->
    <div>
        <p class="font-semibold mb-3">Color:</p>
        <div class="flex gap-3" id="colorGroup">
            <?php foreach ($product['colors'] as $idx => $color): ?>
            <div class="relative">
                <input type="radio" id="color_<?php echo $idx; ?>" name="color" value="<?php echo htmlspecialchars($color['name']); ?>" 
                       class="sr-only color-radio">
                <label for="color_<?php echo $idx; ?>" class="color-label w-8 h-8 rounded-full cursor-pointer border-2 border-gray-300 hover:border-gray-600 transition block"
                       style="background-color: <?php echo htmlspecialchars($color['code']); ?>;"
                       title="<?php echo htmlspecialchars($color['name']); ?>">
                </label>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Size Selection -->
    <div>
        <p class="font-semibold mb-3">Size:</p>
        <div class="flex gap-2" id="sizeGroup">
            <?php $sizeIdx = 0; foreach ($product['sizes'] as $size): ?>
            <label for="size_<?php echo $sizeIdx; ?>" class="size-label w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded cursor-pointer hover:border-gray-600 transition">
                <input type="radio" id="size_<?php echo $sizeIdx; ?>" name="size" value="<?php echo htmlspecialchars($size); ?>" class="sr-only size-radio">
                <span class="text-sm font-medium"><?php echo htmlspecialchars($size); ?></span>
            </label>
            <?php $sizeIdx++; endforeach; ?>
        </div>
    </div>
    
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
        label.classList.remove('border-gray-600', 'border-4');
        label.classList.add('border-gray-300', 'border-2');
      });
      
      if (checkedRadio) {
        var checkedLabel = document.querySelector('label[for="' + checkedRadio.id + '"]');
        if (checkedLabel) {
          checkedLabel.classList.remove('border-gray-300', 'border-2');
          checkedLabel.classList.add('border-gray-600', 'border-4');
        }
      }
    }

    function updateSizeLabels() {
      var sizeGroup = document.getElementById('sizeGroup');
      if (!sizeGroup) return;
      var labels = sizeGroup.querySelectorAll('.size-label');
      var checkedRadio = document.querySelector('input[name="size"]:checked');
      
      labels.forEach(function(label) {
        label.classList.remove('border-gray-600', 'border-4');
        label.classList.add('border-gray-300', 'border-2');
      });
      
      if (checkedRadio) {
        var checkedLabel = document.querySelector('label[for="' + checkedRadio.id + '"]');
        if (checkedLabel) {
          checkedLabel.classList.remove('border-gray-300', 'border-2');
          checkedLabel.classList.add('border-gray-600', 'border-4');
        }
      }
    }

    function validateFormAndUpdateButtons() {
      // Get selected values
      var selectedColor = document.querySelector('input[name="color"]:checked');
      var selectedSize = document.querySelector('input[name="size"]:checked');
      var qty = parseInt(qtyInput.value, 10) || 0;
      
      var colorVal = selectedColor ? selectedColor.value : null;
      var sizeVal = selectedSize ? selectedSize.value : null;
      
      // Get stock from data attribute on product page
      var stock = parseInt(document.body.getAttribute('data-stock') || '0', 10);
      
      // Validate: color selected, size selected, qty >= 1, qty <= stock
      var isValid = colorVal && sizeVal && qty >= 1 && qty <= stock;
      
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
            // Hiệu ứng rung icon giỏ hàng và cập nhật badge
            if (window.shakeCartIcon) shakeCartIcon();
            // Tăng badge số lượng (giả sử đã có biến cartCount, nếu chưa thì lấy từ badge)
            var badge = document.getElementById('cartBadge');
            var current = badge && !badge.classList.contains('hidden') ? parseInt(badge.textContent, 10) || 0 : 0;
            updateCartBadge(current + parseInt(document.getElementById('selectedQuantity').value, 10));
          } else {
            alert('Error: ' + (data.message || 'Unable to add product to cart'));
          }
        })
        .catch(function(err) {
          console.error('Error:', err);
          alert('An error occurred: ' + err.message);
        });
      });
    }
    
    // Initial validation
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
