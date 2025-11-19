
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let variantIndex = 0;
        let skuCheckResults = {}; // Track SKU validation results

        function addVariant() {
            const container = document.getElementById('variants-container');
            const div = document.createElement('div');
            div.className = 'variant-row flex gap-4 items-end mb-4 p-4 border border-gray-200 rounded-lg';
            div.innerHTML = `
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <select name="variants[${variantIndex}][color_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        <option value="">No Color</option>
                        <?php foreach ($allColors as $color): ?>
                            <option value="<?php echo $color['id']; ?>"><?php echo htmlspecialchars($color['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                    <select name="variants[${variantIndex}][size_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        <option value="">No Size</option>
                        <?php foreach ($allSizes as $size): ?>
                            <option value="<?php echo $size['id']; ?>"><?php echo htmlspecialchars($size['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="variants[${variantIndex}][stock]" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (Optional)</label>
                    <input type="number" name="variants[${variantIndex}][price]" step="1" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" placeholder="Leave empty to use product price">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="variants[${variantIndex}][sku]" value="" class="sku-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required oninput="checkSKUUniqueness(this)" data-index="${variantIndex}">
                    <div class="sku-error text-xs text-red-600 mt-1 hidden"></div>
                </div>
                <button type="button" onclick="removeVariant(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Remove</button>
            `;
            container.appendChild(div);
            variantIndex++;
            updateSubmitButton();
        }

        function removeVariant(button) {
            if (confirm('Are you sure you want to remove this variant?')) {
                const row = button.closest('.variant-row');
                const skuInput = row.querySelector('.sku-input');
                const index = skuInput.getAttribute('data-index');
                
                // Remove from tracking
                delete skuCheckResults[index];
                
                row.remove();
                updateSubmitButton();
            }
        }

        async function checkSKUUniqueness(input) {
            const sku = input.value.trim();
            const errorDiv = input.parentElement.querySelector('.sku-error');
            const index = input.getAttribute('data-index');
            
            if (!sku) {
                errorDiv.classList.add('hidden');
                input.classList.remove('border-red-500');
                skuCheckResults[index] = false; // Invalid - empty
                updateSubmitButton();
                return;
            }

            try {
                const response = await fetch('/SHooad/public/seller/check-sku?sku=' + encodeURIComponent(sku));
                const data = await response.json();
                
                if (data.exists) {
                    errorDiv.textContent = `SKU đã được dùng ở sản phẩm "${data.product_name}"`;
                    errorDiv.classList.remove('hidden');
                    input.classList.add('border-red-500');
                    skuCheckResults[index] = false; // Invalid - duplicate
                } else {
                    errorDiv.classList.add('hidden');
                    input.classList.remove('border-red-500');
                    skuCheckResults[index] = true; // Valid
                }
                updateSubmitButton();
            } catch (error) {
                console.error('Error checking SKU:', error);
                skuCheckResults[index] = false;
                updateSubmitButton();
            }
        }

        function validateForm() {
            // Check if there are any SKU errors visible
            const hasSkuErrors = document.querySelectorAll('.sku-error:not(.hidden)').length > 0;
            
            // Check if there are any SKU inputs with red border
            const hasInvalidSkus = document.querySelectorAll('.sku-input.border-red-500').length > 0;
            
            // Check if all required SKU checks passed
            const allSkusValid = Object.values(skuCheckResults).every(result => result === true);
            
            // Check if all SKU inputs are filled
            const skuInputs = document.querySelectorAll('.sku-input');
            const allSkusFilled = Array.from(skuInputs).every(input => input.value.trim() !== '');
            
            return !hasSkuErrors && !hasInvalidSkus && allSkusValid && allSkusFilled;
        }

        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            const skuInputs = document.querySelectorAll('.sku-input');
            
            if (skuInputs.length === 0) {
                // No variants - disable submit button
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                submitBtn.title = 'Vui lòng thêm ít nhất một biến thể';
            } else if (!validateForm()) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                submitBtn.title = 'Vui lòng kiểm tra lại các SKU và điền đầy đủ thông tin';
            } else {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.title = '';
            }
        }

        function handleFormSubmit(event) {
            if (!validateForm()) {
                event.preventDefault();
                alert('Vui lòng kiểm tra lại:\n- Tất cả SKU phải unique (không trùng lặp)\n- Tất cả biến thể phải có đầy đủ thông tin\n- Cần có ít nhất một biến thể');
                return false;
            }
            return true;
        }

        function previewImages(event) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            const files = event.target.files;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-24 h-24 object-cover rounded-lg border-2 border-teal-500" alt="Preview">
                            <div class="absolute -top-2 -right-2 bg-teal-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">${i + 1}</div>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }

        function formatVND(input) {
            let value = input.value.replace(/\D/g, '');
            input.value = value;
        }

        // Initialize submit button state on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSubmitButton();
        });
    </script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mt-2">Add New Product</h1>
            </div>

            <!-- Add Product Form -->
            <div class="bg-white rounded-lg border border-gray-200 p-8">
                <form method="POST" action="" enctype="multipart/form-data" class="space-y-6" onsubmit="return handleFormSubmit(event)">
                    <input type="hidden" name="shop_id" value="<?php echo $data["shop"]["id"]; ?>">
                    
                    <!-- Product Name and Brand -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-900 mb-2">Product Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                        </div>

                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-900 mb-2">Brand</label>
                            <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($_POST['brand'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>

                    <!-- Product Images -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-900 mb-2">Product Images</label>
                        <div id="imagePreview" class="flex flex-wrap gap-4 mb-4"></div>
                        <input type="file" id="images" name="images[]" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required onchange="previewImages(event)">
                        <p class="text-sm text-gray-500 mt-1">You can select multiple images</p>
                    </div>

                    <!-- Price and Original Price -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-900 mb-2">Price (VND)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-gray-600">₫</span>
                                <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($_POST['price'] ?? '0'); ?>" step="1" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required oninput="formatVND(this)">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter amount in VND (e.g., 199000)</p>
                        </div>

                        <div>
                            <label for="original_price" class="block text-sm font-medium text-gray-900 mb-2">Original Price (VND)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-gray-600">₫</span>
                                <input type="number" id="original_price" name="original_price" value="<?php echo htmlspecialchars($_POST['original_price'] ?? '0'); ?>" step="1" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required oninput="formatVND(this)">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter amount in VND (e.g., 299000)</p>
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-900 mb-2">Category</label>
                        <select id="category_id" name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                            <option value="">Select Category</option>
                            <?php foreach ($allCategories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo ($_POST['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Product Variants -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Product Variants</label>
                        <p class="text-sm text-gray-600 mb-4">Add product variants with color, size, stock, and SKU. Stock is managed at variant level.</p>
                        <div id="variants-container"></div>
                        <button type="button" onclick="addVariant()" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                            Add Variant
                        </button>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-900 mb-2">Product Status</label>
                        <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                            <option value="paused" selected>Paused</option>
                            <option value="active">Active</option>
                            <option value="deleted">Deleted</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" id="submitBtn" class="px-6 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Add Product
                        </button>
                        <a href="/SHooad/public/seller/products" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                    
                    <!-- Validation Warning -->
                    <div id="validationWarning" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 hidden">
                        <p class="text-sm text-yellow-800">
                            <strong>Chú ý:</strong>
                        </p>
                        <ul class="text-sm text-yellow-700 mt-2 ml-4 list-disc">
                            <li>Cần có ít nhất một biến thể sản phẩm</li>
                            <li>Tất cả SKU phải là duy nhất (không trùng lặp)</li>
                            <li>Tất cả trường bắt buộc phải được điền đầy đủ</li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
