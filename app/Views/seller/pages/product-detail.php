<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <a href="?page=products" class="text-teal-600 hover:text-teal-700 text-sm font-medium">&larr; Back to Products</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Product Details</h1>
        </div>

        <!-- Product Detail Form -->
        <div class="bg-white rounded-lg border border-gray-200 p-8">
            <form method="POST" action="" class="space-y-6">
                <!-- Product Name and SKU -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="product_name" class="block text-sm font-medium text-gray-900 mb-2">Product Name</label>
                        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-900 mb-2">SKU</label>
                        <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                </div>

                <!-- Thumbnail URL -->
                <div>
                    <label for="thumbnail_url" class="block text-sm font-medium text-gray-900 mb-2">Thumbnail URL</label>
                    <input type="text" id="thumbnail_url" name="thumbnail_url" value="<?php echo htmlspecialchars($product['thumbnail_url'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                </div>

                <!-- Colors and Sizes -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="colors" class="block text-sm font-medium text-gray-900 mb-2">Colors (comma-separated)</label>
                        <input type="text" id="colors" name="colors" value="<?php echo htmlspecialchars($product['colors'] ?? ''); ?>" placeholder="e.g., Red, Blue, Green" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                    </div>

                    <div>
                        <label for="sizes" class="block text-sm font-medium text-gray-900 mb-2">Sizes (comma-separated)</label>
                        <input type="text" id="sizes" name="sizes" value="<?php echo htmlspecialchars($product['sizes'] ?? ''); ?>" placeholder="e.g., S, M, L, XL" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                    </div>
                </div>

                <!-- Price and Original Price -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-900 mb-2">Price</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-gray-600">$</span>
                            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price'] ?? '0'); ?>" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        </div>
                    </div>

                    <div>
                        <label for="original_price" class="block text-sm font-medium text-gray-900 mb-2">Original Price</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-gray-600">$</span>
                            <input type="number" id="original_price" name="original_price" value="<?php echo htmlspecialchars($product['original_price'] ?? '0'); ?>" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        </div>
                    </div>
                </div>

                <!-- Stock Quantity and Category -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-900 mb-2">Stock Quantity</label>
                        <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock'] ?? '0'); ?>" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-900 mb-2">Category</label>
                        <input type="number" id="category_id" name="category_id" value="<?php echo htmlspecialchars($product['category_id'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                        <option value="active" <?php echo ($product['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($product['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        <option value="out_of_stock" <?php echo ($product['status'] ?? '') === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                    </select>
                </div>

                <!-- Product Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-4">Product Information</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Sales:</span>
                            <span class="font-semibold text-gray-900"><?php echo $product['sold_quantity'] ?? '0'; ?> units</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Created:</span>
                            <span class="font-semibold text-gray-900"><?php echo $product['created_at'] ?? 'N/A'; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit" name="update_product" value="1" class="px-6 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition-colors">
                        Update Product
                    </button>
                    <a href="?page=products" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
