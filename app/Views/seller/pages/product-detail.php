<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function deleteImage(imageId) {
            if (confirm('Are you sure you want to delete this image?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_image_id';
                input.value = imageId;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function previewNewImages(event) {
            const preview = document.getElementById('newImagePreview');
            preview.innerHTML = '';
            const files = event.target.files;
            
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-24 h-24 object-cover rounded-lg border-2 border-green-500" alt="New Image Preview">
                                <div class="absolute -top-2 -right-2 bg-green-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">New</div>
                            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        }

        let variantIndex = <?php echo count($product['variants'] ?? []); ?>;

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
                    <input type="number" name="variants[${variantIndex}][stock]" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (Optional, VND)</label>
                    <input type="number" name="variants[${variantIndex}][price]" step="1" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" placeholder="Leave empty to use product price">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="variants[${variantIndex}][sku]" value="" class="sku-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" oninput="checkSKUUniqueness(this, '')">
                    <div class="sku-error text-xs text-red-600 mt-1 hidden"></div>
                </div>
                <input type="hidden" name="variants[${variantIndex}][id]" value="">
                <button type="button" onclick="removeVariant(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Remove</button>
            `;
            container.appendChild(div);
            variantIndex++;
        }

        function removeVariant(button) {
            if (confirm('Are you sure you want to remove this variant?')) {
                button.closest('.variant-row').remove();
            }
        }

        async function checkSKUUniqueness(input, currentSKU) {
            const sku = input.value.trim();
            const errorDiv = input.parentElement.querySelector('.sku-error');
            
            if (!sku || sku === currentSKU) {
                errorDiv.classList.add('hidden');
                input.classList.remove('border-red-500');
                return;
            }

            try {
                const response = await fetch('/SHooad/public/seller/check-sku?sku=' + encodeURIComponent(sku));
                const data = await response.json();
                
                if (data.exists) {
                    errorDiv.textContent = `SKU đã được dùng ở sản phẩm "${data.product_name}"`;
                    errorDiv.classList.remove('hidden');
                    input.classList.add('border-red-500');
                } else {
                    errorDiv.classList.add('hidden');
                    input.classList.remove('border-red-500');
                }
            } catch (error) {
                console.error('Error checking SKU:', error);
            }
        }
    </script>
</head>
<body>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <!-- <a href="/SHooad/public/seller/products" class="text-teal-600 hover:text-teal-700 text-sm font-medium">&larr; Back to Products</a> -->
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Product Details</h1>
        </div>

        <!-- Product Detail Form -->
        <div class="bg-white rounded-lg border border-gray-200 p-8">
            <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                <!-- Product Name and Brand -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="product_name" class="block text-sm font-medium text-gray-900 mb-2">Product Name</label>
                        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                    </div>

                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-900 mb-2">Brand</label>
                        <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($product['brand'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                </div>

                <!-- Product Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Product Images</label>
                    <div class="flex flex-wrap gap-4 mb-4">
                        <?php if (!empty($product['images'])): ?>
                            <?php foreach ($product['images'] as $image): ?>
                                <div class="relative">
                                    <img src="/SHooad/public/assets/products/<?php echo htmlspecialchars($image['filename']); ?>" class="w-24 h-24 object-cover rounded-lg" alt="Product Image">
                                    <button type="button" onclick="deleteImage(<?php echo $image['id']; ?>)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">&times;</button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-sm">No images uploaded</p>
                        <?php endif; ?>
                    </div>
                    <div id="newImagePreview" class="flex flex-wrap gap-4 mb-4"></div>
                    <input type="file" id="new_images" name="new_images[]" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" onchange="previewNewImages(event)">
                    <p class="text-sm text-gray-500 mt-1">Upload new images (you can select multiple)</p>
                </div>

                <!-- Product Variants -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Product Variants</label>
                    <div id="variants-container">
                        <?php if (!empty($product['variants'])): ?>
                            <?php foreach ($product['variants'] as $index => $variant): ?>
                                <div class="variant-row flex gap-4 items-end mb-4 p-4 border border-gray-200 rounded-lg">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                                        <select name="variants[<?php echo $index; ?>][color_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                                            <option value="">No Color</option>
                                            <?php foreach ($allColors as $color): ?>
                                                <option value="<?php echo $color['id']; ?>" <?php echo ($variant['color_id'] == $color['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($color['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                                        <select name="variants[<?php echo $index; ?>][size_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                                            <option value="">No Size</option>
                                            <?php foreach ($allSizes as $size): ?>
                                                <option value="<?php echo $size['id']; ?>" <?php echo ($variant['size_id'] == $size['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($size['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                                        <input type="number" name="variants[<?php echo $index; ?>][stock]" value="<?php echo htmlspecialchars($variant['stock']); ?>" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (Optional, VND)</label>
                                        <input type="number" name="variants[<?php echo $index; ?>][price]" value="<?php echo htmlspecialchars($variant['price'] ?? ''); ?>" step="1" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" placeholder="Leave empty to use product price">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                                        <input type="text" name="variants[<?php echo $index; ?>][sku]" value="<?php echo htmlspecialchars($variant['sku']); ?>" class="sku-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" oninput="checkSKUUniqueness(this, '<?php echo htmlspecialchars($variant['sku']); ?>')">
                                        <div class="sku-error text-xs text-red-600 mt-1 hidden"></div>
                                    </div>
                                    <input type="hidden" name="variants[<?php echo $index; ?>][id]" value="<?php echo $variant['id']; ?>">
                                    <button type="button" onclick="removeVariant(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Remove</button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-sm mb-4">No variants configured. Add variants below.</p>
                        <?php endif; ?>
                    </div>
                    <button type="button" onclick="addVariant()" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Add Variant
                    </button>
                </div>

                <!-- Price and Original Price -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-900 mb-2">Price</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-gray-600">₫</span>
                            <!-- <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price'] ?? '0'); ?>" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600"> -->
                             <input type="number" id="price" name="price"
                                value="<?php echo rtrim(rtrim((string)($product['price'] ?? '0'), '0'), '.'); ?>"
                                step="0.01" min="0"
                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        </div>
                    </div>

                    <div>
                        <label for="original_price" class="block text-sm font-medium text-gray-900 mb-2">Original Price</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-gray-600">₫</span>
                            <!-- <input type="number" id="original_price" name="original_price" value="<?php echo htmlspecialchars($product['original_price'] ?? '0'); ?>" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600"> -->
                             <input type="number" id="original_price" name="original_price"
                                value="<?php echo rtrim(rtrim((string)($product['original_price'] ?? '0'), '0'), '.'); ?>"
                                step="0.01" min="0"
                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        </div>
                    </div>
                </div>

                <!-- Stock Quantity and Category -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-900 mb-2">Stock Quantity</label>
                        <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock'] ?? '0'); ?>" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" readonly>
                        <p class="text-xs text-gray-500 mt-1">
                            <?php
                            $hasVariants = false;
                            if (isset($product['id'])) {
                                $db = (new Database())->getConnection();
                                $stmt = $db->prepare("SELECT COUNT(*) as count FROM product_variants WHERE product_id = ?");
                                $stmt->bind_param("i", $product['id']);
                                $stmt->execute();
                                $result = $stmt->get_result()->fetch_assoc();
                                $hasVariants = $result['count'] > 0;
                            }
                            if ($hasVariants): ?>
                                Total stock calculated from product variants. Manage variants to adjust stock.
                            <?php else: ?>
                                This product has no variants. Stock is managed here.
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-900 mb-2">Category</label>
                        <select id="category_id" name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                            <option value="">No Category</option>
                            <?php foreach ($allCategories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        <option value="active" <?php echo ($product['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="paused" <?php echo ($product['status'] ?? '') === 'paused' ? 'selected' : ''; ?>>Paused</option>
                        <option value="deleted" <?php echo ($product['status'] ?? '') === 'deleted' ? 'selected' : ''; ?>>Deleted</option>
                    </select>
                </div>

                            <!-- Product Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-4">Product Information</h3>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Sold:</span>
                                        <span class="font-semibold text-gray-900"><?php echo $product['sold'] ?? '0'; ?> units</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Created:</span>
                                        <span class="font-semibold text-gray-900"><?php echo $product['created_at'] ?? 'N/A'; ?></span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Last Updated:</span>
                                        <span class="font-semibold text-gray-900"><?php echo $product['modified_at'] ?? 'N/A'; ?></span>
                                    </div>
                                </div>
                            </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit" name="update_product" value="1" class="px-6 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition-colors">
                        Update Product
                    </button>
                    <a href="/SHooad/public/seller/products" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
