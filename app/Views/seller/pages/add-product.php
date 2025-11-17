
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Page Header -->
            <div class="mb-6">
                <a href="/SHooad/public/seller/products" class="text-teal-600 hover:text-teal-700 text-sm font-medium">&larr; Back to Products</a>
                <h1 class="text-3xl font-bold text-gray-900 mt-2">Add New Product</h1>
            </div>

            <!-- Add Product Form -->
            <div class="bg-white rounded-lg border border-gray-200 p-8">
                <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
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

                     <div>
                        <label for="images" class="block text-sm font-medium text-gray-900 mb-2">Product Images</label>
                        <div id="imagePreview" class="flex flex-wrap gap-4 mb-4"></div>
                        <input type="file" id="images" name="images[]" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required onchange="previewImages(event)">
                        <p class="text-sm text-gray-500 mt-1">You can select multiple images</p>
                    </div>

                    <!-- Colors and Sizes -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="colors" class="block text-sm font-medium text-gray-900 mb-2">Colors (comma-separated)</label>
                            <input type="text" id="colors" name="colors" value="<?php echo htmlspecialchars($_POST['colors'] ?? ''); ?>" placeholder="e.g., Red, Blue, Green" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                        </div>

                        <div>
                            <label for="sizes" class="block text-sm font-medium text-gray-900 mb-2">Sizes (comma-separated)</label>
                            <input type="text" id="sizes" name="sizes" value="<?php echo htmlspecialchars($_POST['sizes'] ?? ''); ?>" placeholder="e.g., S, M, L, XL" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                        </div>
                    </div>

                    <!-- Price and Original Price -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-900 mb-2">Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-gray-600">$</span>
                                <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($_POST['price'] ?? '0'); ?>" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                            </div>
                        </div>

                        <div>
                            <label for="original_price" class="block text-sm font-medium text-gray-900 mb-2">Original Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-gray-600">$</span>
                                <input type="number" id="original_price" name="original_price" value="<?php echo htmlspecialchars($_POST['original_price'] ?? '0'); ?>" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Quantity and Category -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-900 mb-2">Stock Quantity</label>
                            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($_POST['stock'] ?? '0'); ?>" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-900 mb-2">Category</label>
                            <input type="number" id="category_id" name="category_id" value="<?php echo htmlspecialchars($_POST['category_id'] ?? '1'); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                        <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                            <option value="active" <?php echo ($_POST['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="paused" <?php echo ($_POST['status'] ?? '') === 'paused' ? 'selected' : ''; ?>>Paused</option>
                            <option value="deleted" <?php echo ($_POST['status'] ?? '') === 'deleted' ? 'selected' : ''; ?>>Deleted</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition-colors">
                            Add Product
                        </button>
                        <a href="/SHooad/public/seller/products" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
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
    </script>
</body>
</html>
