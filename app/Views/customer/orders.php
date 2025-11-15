<!-- Orders Page -->
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="text-gray-600 mt-2">Track and manage your orders</p>
        </div>

        <?php 
        $orders = $data['orders'] ?? [];
        $orderStatusCounts = $data['orderStatusCounts'] ?? [];
        $filterStatus = $data['filterStatus'] ?? null;
        ?>

        <!-- Order Status Tabs -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="/SHooad/public/customer/orders" 
                   class="px-4 py-2 rounded-lg font-medium transition <?= !$filterStatus ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                    All Orders
                    <?php 
                    $totalCount = array_sum($orderStatusCounts);
                    if ($totalCount > 0): 
                    ?>
                        <span class="ml-1">(<?= $totalCount ?>)</span>
                    <?php endif; ?>
                </a>
                
                <?php
                $statuses = [
                    'Pending_Transfer' => ['label' => 'Pending Payment', 'color' => 'orange'],
                    'Paid' => ['label' => 'Paid', 'color' => 'green'],
                    'Processing' => ['label' => 'Processing', 'color' => 'blue'],
                    'Delivering' => ['label' => 'Shipping', 'color' => 'indigo'],
                    'Pending_COD' => ['label' => 'COD Pending', 'color' => 'yellow'],
                    'Completed' => ['label' => 'Completed', 'color' => 'green'],
                    'Cancelled' => ['label' => 'Cancelled', 'color' => 'red'],
                    'Failed' => ['label' => 'Failed', 'color' => 'red']
                ];
                
                foreach ($statuses as $status => $info):
                    $count = $orderStatusCounts[$status] ?? 0;
                    $isActive = $filterStatus === $status;
                ?>
                    <a href="/SHooad/public/customer/orders?status=<?= $status ?>" 
                       class="px-4 py-2 rounded-lg font-medium transition <?= $isActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                        <?= $info['label'] ?>
                        <?php if ($count > 0): ?>
                            <span class="ml-1">(<?= $count ?>)</span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Orders List -->
        <?php if (count($orders) > 0): ?>
            <div class="space-y-4">
                <?php foreach ($orders as $order): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Order Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <span class="text-gray-500 text-sm">Order ID:</span>
                                        <span class="font-semibold text-gray-900 ml-1">#<?= $order['id'] ?></span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-sm">Shop:</span>
                                        <span class="font-semibold text-gray-900 ml-1"><?= htmlspecialchars($order['shop_name'] ?? 'Unknown') ?></span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-sm">Date:</span>
                                        <span class="text-gray-700 ml-1"><?= date('d/m/Y H:i', strtotime($order['date'])) ?></span>
                                    </div>
                                </div>
                                <div>
                                    <span class="px-3 py-1 text-sm font-medium rounded-full <?php
                                        echo match($order['status']) {
                                            'Completed' => 'bg-green-100 text-green-800',
                                            'Cancelled', 'Failed' => 'bg-red-100 text-red-800',
                                            'Delivering' => 'bg-indigo-100 text-indigo-800',
                                            'Processing' => 'bg-blue-100 text-blue-800',
                                            'Paid' => 'bg-green-100 text-green-800',
                                            'Pending_COD' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-orange-100 text-orange-800'
                                        };
                                    ?>"><?= str_replace('_', ' ', $order['status']) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Body -->
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                        <span class="font-medium">Shipping Address:</span>
                                        <?= htmlspecialchars($order['shipping_address']) ?>
                                    </p>
                                    <p class="text-gray-600">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                                        <span class="font-medium">Phone:</span>
                                        <?= htmlspecialchars($order['customer_phone']) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <a href="/SHooad/public/customer/order-detail?id=<?= $order['id'] ?>" 
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-shopping-bag text-gray-300 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    <?php if ($filterStatus): ?>
                        No <?= str_replace('_', ' ', $filterStatus) ?> Orders
                    <?php else: ?>
                        No Orders Yet
                    <?php endif; ?>
                </h2>
                <p class="text-gray-600 mb-6">
                    <?php if ($filterStatus): ?>
                        You don't have any orders with this status.
                    <?php else: ?>
                        Start shopping to create your first order!
                    <?php endif; ?>
                </p>
                <?php if (!$filterStatus): ?>
                    <a href="/SHooad/public/customer/products" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                        <i class="fas fa-shopping-cart mr-2"></i>Start Shopping
                    </a>
                <?php else: ?>
                    <a href="/SHooad/public/customer/orders" 
                       class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition">
                        <i class="fas fa-list mr-2"></i>View All Orders
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
