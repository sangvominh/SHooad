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
                    <!-- TODO: BUG -->
                    <!-- <div>
                        <p class="text-sm text-gray-600">Shop</p>
                        <p class="font-medium"><?= htmlspecialchars($order['shop_name'] ?? 'N/A') ?></p>
                    </div> -->
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
                <?php 
                $reviewStatuses = $data['review_statuses'] ?? [];
                foreach ($orderItems as $item): 
                ?>
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center">
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
                            <div class="text-right ml-4 flex items-center gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <?= formatVND($item['price']) ?> × <?= $item['quantity'] ?>
                                    </p>
                                    <p class="font-bold text-gray-900 mt-1">
                                        <?= formatVND($item['price'] * $item['quantity']) ?>
                                    </p>
                                </div>
                                <?php if ($order['status'] === 'completed' && isset($reviewStatuses[$item['product_id']]) && $reviewStatuses[$item['product_id']]): ?>
                                    <button onclick="openReviewModal(<?= $item['product_id'] ?>, '<?= htmlspecialchars($item['product_name']) ?>')" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                                        <i class="fas fa-star mr-1"></i>Review
                                    </button>
                                <?php endif; ?>
                            </div>
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
                      onsubmit="return confirmCancelOrder(event, '<?= $order['payment_method'] ?? 'cod' ?>', '<?= $order['payment_status'] ?? 'pending' ?>');">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition">
                        <i class="fas fa-times-circle mr-2"></i>Cancel Order
                    </button>
                </form>
                
                <script>
                function confirmCancelOrder(event, paymentMethod, paymentStatus) {
                    event.preventDefault();
                    var message = 'Are you sure you want to cancel this order?';
                    
                    if (paymentMethod === 'online' && paymentStatus === 'paid') {
                        message = 'Bạn có chắc chắn muốn hủy đơn hàng này?\n\nTiền sẽ được hoàn lại vào tài khoản của bạn trong vòng 1-3 ngày làm việc.';
                    }
                    
                    if (confirm(message)) {
                        event.target.submit();
                    }
                    return false;
                }
                </script>
            <?php elseif ($order['status'] === 'completed'): ?>
                <!-- Review buttons are now per product item -->
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

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Review Product</h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="reviewForm" action="/SHooad/public/customer/submit-review" method="POST">
                <input type="hidden" id="reviewProductId" name="product_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                    <p id="reviewProductName" class="text-gray-900 font-medium"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex gap-1">
                        <input type="radio" id="star1" name="rating" value="1" class="hidden">
                        <label for="star1" class="cursor-pointer text-gray-300 hover:text-yellow-400">
                            <i class="fas fa-star text-2xl star-rating"></i>
                        </label>
                        <input type="radio" id="star2" name="rating" value="2" class="hidden">
                        <label for="star2" class="cursor-pointer text-gray-300 hover:text-yellow-400">
                            <i class="fas fa-star text-2xl star-rating"></i>
                        </label>
                        <input type="radio" id="star3" name="rating" value="3" class="hidden">
                        <label for="star3" class="cursor-pointer text-gray-300 hover:text-yellow-400">
                            <i class="fas fa-star text-2xl star-rating"></i>
                        </label>
                        <input type="radio" id="star4" name="rating" value="4" class="hidden">
                        <label for="star4" class="cursor-pointer text-gray-300 hover:text-yellow-400">
                            <i class="fas fa-star text-2xl star-rating"></i>
                        </label>
                        <input type="radio" id="star5" name="rating" value="5" class="hidden">
                        <label for="star5" class="cursor-pointer text-gray-300 hover:text-yellow-400">
                            <i class="fas fa-star text-2xl star-rating"></i>
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional)</label>
                    <textarea id="comment" name="comment" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Share your experience..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeReviewModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReviewModal(productId, productName) {
    document.getElementById('reviewProductId').value = productId;
    document.getElementById('reviewProductName').textContent = productName;
    document.getElementById('reviewModal').classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    // Reset form
    document.getElementById('reviewForm').reset();
    // Reset stars
    document.querySelectorAll('.star-rating').forEach(star => {
        star.classList.remove('text-yellow-400');
        star.classList.add('text-gray-300');
    });
}

// Star rating functionality
document.querySelectorAll('.star-rating').forEach((star, index) => {
    star.addEventListener('click', function() {
        const rating = index + 1;
        document.querySelector(`input[name="rating"][value="${rating}"]`).checked = true;
        updateStars(rating);
    });
});

function updateStars(rating) {
    document.querySelectorAll('.star-rating').forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}
</script>
