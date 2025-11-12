
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
                <a href="?page=products" class="text-teal-600 hover:text-teal-700 text-sm font-medium">&larr; Back to Products</a>
                <h1 class="text-3xl font-bold text-gray-900 mt-2">Add New Product</h1>
            </div>

            <!-- Add Product Form -->
            <div class="bg-white rounded-lg border border-gray-200 p-8">
                <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="shop_id" value="<?php echo $data["shop"]["id"]; ?>">
                    <!-- Product Name and SKU -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-900 mb-2">Product Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-900 mb-2">SKU</label>
                            <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars($_POST['sku'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>

                     <div>
                        <label for="thumbnail" class="block text-sm font-medium text-gray-900 mb-2">Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        <?php if(!empty($uploadedFilePath)): ?>
                            <img src="<?php echo htmlspecialchars($uploadedFilePath); ?>" class="mt-2 w-32 h-32 object-cover rounded-lg" alt="Thumbnail Preview">
                        <?php endif; ?>
                    </div>

                    <!-- Thumbnail URL -->
                    <!-- <div>
                        <label for="thumbnail_url" class="block text-sm font-medium text-gray-900 mb-2">Thumbnail URL</label>
                        <input type="text" id="thumbnail_url" name="thumbnail_url" value="<?php echo htmlspecialchars($_POST['thumbnail_url'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600" required>
                    </div> -->

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
                            <option value="inactive" <?php echo ($_POST['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="out_of_stock" <?php echo ($_POST['status'] ?? '') === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition-colors">
                            Add Product
                        </button>
                        <a href="?page=products" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
