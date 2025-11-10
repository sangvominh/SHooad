<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        
        <div class="flex-1">
            <!-- Header -->
            <?php include __DIR__ . '/../partials/header.php'; ?>
            
            <!-- Main Content -->
            <main class="p-6">
                <div class="">
                    <!-- Page Header -->
                    <div class="mb-6">
                        <a href="?page=products" class="text-teal-600 hover:text-teal-700 text-sm font-medium">&larr; Back to Products</a>
                        <h1 class="text-3xl font-bold text-gray-900 mt-2">Product Details</h1>
                    </div>

                    <!-- Product Detail Form -->
                    <div class="bg-white rounded-lg border border-gray-200 p-8">
                        <form method="POST" class="space-y-6">
                            <!-- Product Name -->
                            <div>
                                <label for="product_name" class="block text-sm font-medium text-gray-900 mb-2">Product Name</label>
                                <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                                <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                            </div>

                            <!-- Grid: Price and Stock -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-900 mb-2">Price</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-2.5 text-gray-600">$</span>
                                        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price'] ?? '0'); ?>" step="0.01" min="0" required class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                                    </div>
                                </div>

                                <div>
                                    <label for="stock" class="block text-sm font-medium text-gray-900 mb-2">Stock Quantity</label>
                                    <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock'] ?? '0'); ?>" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                                </div>
                            </div>

                            <!-- Category and Status -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-900 mb-2">Category</label>
                                    <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-600">
                                        <option value="active" <?php echo ($product['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($product['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="out_of_stock" <?php echo ($product['status'] ?? '') === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-4">Product Information</h3>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Sales:</span>
                                        <span class="font-semibold text-gray-900"><?php echo $product['sales'] ?? '0'; ?> units</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Created:</span>
                                        <span class="font-semibold text-gray-900"><?php echo $product['created_at'] ?? 'N/A'; ?></span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Last Updated:</span>
                                        <span class="font-semibold text-gray-900"><?php echo $product['updated_at'] ?? 'N/A'; ?></span>
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
            </main>
        </div>
    </div>
</body>
</html>
