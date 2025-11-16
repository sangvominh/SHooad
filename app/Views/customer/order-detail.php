<!-- Order Detail Page -->
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <?php 
        $order = $data['order'] ?? [];
        $orderItems = $data['order_items'] ?? [];
        
        if (empty($order)) {
            echo '<div class="bg-red-100 text-red-800 p-4 rounded">Order not found</div>';
            return;
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($orderItems as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }
        $shippingFee = $order['shipping_fee'] ?? 0;
        $total = $subtotal + $shippingFee;
        
        // Format currency to Vietnamese Dong
        function formatVND($amount) {
            return number_format($amount, 0, ',', '.') . 'đ';
        }
        ?>
        
        <!-- Back Button and Title -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/SHooad/public/customer/orders" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Orders
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Order #<?= $order['id'] ?></h1>
            </div>
            <span class="px-4 py-2 text-sm font-medium rounded-full <?php
                echo match($order['status']) {
                    'completed' => 'bg-green-100 text-green-800',
                    'cancelled', 'failed' => 'bg-red-100 text-red-800',
                    'delivering' => 'bg-indigo-100 text-indigo-800',
                    'processing' => 'bg-blue-100 text-blue-800',
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    default => 'bg-gray-100 text-gray-800'
                };
            ?>"><?= ucfirst($order['status']) ?></span>
        </div>

        <!-- Flash Messages -->
        <?php
        require_once __DIR__ . '/../../Services/FlashMessageService.php';
        $successMsg = FlashMessageService::getFlashMessage('success');
        $errorMsg = FlashMessageService::getFlashMessage('error');
        
        if ($successMsg): ?>
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i><?= htmlspecialchars($successMsg) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMsg): ?>
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($errorMsg) ?>
            </div>
        <?php endif; ?>

        <!-- Order Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Order Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Order Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Order Date</p>
                        <p class="font-medium"><?= date('d/m/Y H:i', strtotime($order['date'])) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Shop</p>
                        <p class="font-medium"><?= htmlspecialchars($order['shop_name'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>

            <!-- Shipping Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Shipping Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Address</p>
                        <p class="font-medium"><?= htmlspecialchars($order['shipping_address']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium"><?= htmlspecialchars($order['customer_phone']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium"><?= formatVND($subtotal) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Shipping Fee:</span>
                        <span class="font-medium"><?= formatVND($shippingFee) ?></span>
                    </div>
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span class="text-xl font-bold text-blue-600"><?= formatVND($total) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Order Items</h3>
            </div>
            <div class="divide-y divide-gray-200">
                <?php foreach ($orderItems as $item): ?>
                    <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50 transition">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900"><?= htmlspecialchars($item['product_name']) ?></p>
                            <div class="flex gap-4 mt-2 text-sm text-gray-600">
                                <?php if (!empty($item['product_color'])): ?>
                                    <span><i class="fas fa-palette mr-1"></i>Color: <?= htmlspecialchars($item['product_color']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($item['product_size'])): ?>
                                    <span><i class="fas fa-ruler mr-1"></i>Size: <?= htmlspecialchars($item['product_size']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-sm text-gray-600">
                                <?= formatVND($item['price']) ?> × <?= $item['quantity'] ?>
                            </p>
                            <p class="font-bold text-gray-900 mt-1">
                                <?= formatVND($item['price'] * $item['quantity']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <a href="/SHooad/public/customer/orders" 
               class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Orders
            </a>
            
            <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                <form method="POST" action="/SHooad/public/customer/cancel-order" 
                      onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.');">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition">
                        <i class="fas fa-times-circle mr-2"></i>Cancel Order
                    </button>
                </form>
            <?php elseif ($order['status'] === 'completed'): ?>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition cursor-not-allowed" disabled>
                    <i class="fas fa-star mr-2"></i>Leave Review (Coming Soon)
                </button>
            <?php endif; ?>
        </div>

        <!-- Order Status Timeline -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Order Status Timeline</h3>
            <div class="space-y-4">
                <?php
                $statuses = [
                    'pending' => ['label' => 'Pending', 'icon' => 'clock', 'description' => 'Order placed, awaiting seller confirmation'],
                    'processing' => ['label' => 'Processing', 'icon' => 'box', 'description' => 'Seller is preparing your order'],
                    'delivering' => ['label' => 'Delivering', 'icon' => 'truck', 'description' => 'Order is on the way'],
                    'completed' => ['label' => 'Completed', 'icon' => 'check-circle', 'description' => 'Order delivered successfully']
                ];
                
                $currentStatusReached = false;
                $currentStatus = $order['status'];
                
                foreach ($statuses as $status => $info):
                    $isActive = ($status === $currentStatus);
                    $isPassed = !$currentStatusReached && !$isActive;
                    
                    if ($isActive) {
                        $currentStatusReached = true;
                    }
                    
                    // Stop at cancelled or failed
                    if (in_array($currentStatus, ['cancelled', 'failed'])) {
                        if ($status === 'pending') {
                            $isPassed = true;
                            $isActive = false;
                        } else {
                            break;
                        }
                    }
                ?>
                    <div class="flex items-center gap-4 <?= $isActive || $isPassed ? '' : 'opacity-40' ?>">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center <?= $isActive ? 'bg-blue-600 text-white' : ($isPassed ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600') ?>">
                            <i class="fas fa-<?= $info['icon'] ?>"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900"><?= $info['label'] ?></p>
                            <p class="text-sm text-gray-600"><?= $info['description'] ?></p>
                        </div>
                        <?php if ($isActive): ?>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">Current</span>
                        <?php elseif ($isPassed): ?>
                            <i class="fas fa-check text-green-600"></i>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <!-- Show cancelled/failed status if applicable -->
                <?php if ($currentStatus === 'cancelled'): ?>
                    <div class="flex items-center gap-4 border-t border-gray-200 pt-4 mt-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center bg-red-600 text-white">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Cancelled</p>
                            <p class="text-sm text-gray-600">Order has been cancelled</p>
                        </div>
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">Current</span>
                    </div>
                <?php elseif ($currentStatus === 'failed'): ?>
                    <div class="flex items-center gap-4 border-t border-gray-200 pt-4 mt-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center bg-red-600 text-white">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Failed</p>
                            <p class="text-sm text-gray-600">Order delivery failed</p>
                        </div>
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">Current</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
