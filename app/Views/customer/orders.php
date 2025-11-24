<!-- Orders Page -->
<?php
require_once __DIR__ . '/../../Helpers/LanguageHelper.php';
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900"><?= LanguageHelper::t('orders.title') ?></h1>
            <p class="text-gray-600 mt-2"><?= LanguageHelper::t('orders.subtitle') ?></p>
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
                    <?= LanguageHelper::t('orders.all_orders') ?>
                    <?php 
                    $totalCount = array_sum($orderStatusCounts);
                    if ($totalCount > 0): 
                    ?>
                        <span class="ml-1">(<?= $totalCount ?>)</span>
                    <?php endif; ?>
                </a>
                
                <?php
                $statuses = [
                    'pending' => ['color' => 'yellow'],
                    'processing' => ['color' => 'blue'],
                    'delivering' => ['color' => 'indigo'],
                    'completed' => ['color' => 'green'],
                    'cancelled' => ['color' => 'red'],
                    'failed' => ['color' => 'red']
                ];
                
                foreach ($statuses as $status => $info):
                    $count = $orderStatusCounts[$status] ?? 0;
                    $isActive = $filterStatus === $status;
                    $labelKey = ($status === 'delivering') ? 'shipping' : $status;
                ?>
                    <a href="/SHooad/public/customer/orders?status=<?= $status ?>" 
                       class="px-4 py-2 rounded-lg font-medium transition <?= $isActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                        <?= LanguageHelper::t('profile.' . $labelKey) ?>
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
                                        <span class="text-gray-500 text-sm"><?= LanguageHelper::t('orders.order_id') ?>:</span>
                                        <span class="font-semibold text-gray-900 ml-1">#<?= $order['id'] ?></span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-sm"><?= LanguageHelper::t('orders.shop') ?>:</span>
                                        <span class="font-semibold text-gray-900 ml-1"><?= htmlspecialchars($order['shop_name'] ?? 'Unknown') ?></span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-sm"><?= LanguageHelper::t('orders.date') ?>:</span>
                                        <span class="text-gray-700 ml-1"><?= date('d/m/Y H:i', strtotime($order['date'])) ?></span>
                                    </div>
                                </div>
                                <div>
                                    <?php
                                    $statusKey = ($order['status'] === 'delivering') ? 'shipping' : $order['status'];
                                    ?>
                                    <span class="px-3 py-1 text-sm font-medium rounded-full <?php
                                        echo match($order['status']) {
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled', 'failed' => 'bg-red-100 text-red-800',
                                            'delivering' => 'bg-indigo-100 text-indigo-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    ?>"><?= LanguageHelper::t('profile.' . $statusKey) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Body -->
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                        <span class="font-medium"><?= LanguageHelper::t('orders.shipping_address') ?>:</span>
                                        <?= htmlspecialchars($order['shipping_address']) ?>
                                    </p>
                                    <p class="text-gray-600">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                                        <span class="font-medium"><?= LanguageHelper::t('orders.phone') ?>:</span>
                                        <?= htmlspecialchars($order['customer_phone']) ?>
                                    </p>
                                </div>
                                <div class="text-right flex gap-2 justify-end">
                                    <a href="/SHooad/public/customer/order-detail?id=<?= $order['id'] ?>" 
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                                        <i class="fas fa-eye mr-2"></i><?= LanguageHelper::t('orders.view_details') ?>
                                    </a>
                                    <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                                        <form method="POST" action="/SHooad/public/customer/cancel-order" 
                                              onsubmit="return confirmCancelOrder<?= $order['id'] ?>(event);" 
                                              class="inline-block">
                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                            <button type="submit" 
                                                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition">
                                                <i class="fas fa-times mr-2"></i><?= LanguageHelper::t('orders.cancel_order') ?>
                                            </button>
                                        </form>
                                        
                                        <script>
                                        function confirmCancelOrder<?= $order['id'] ?>(event) {
                                            event.preventDefault();
                                            var message = '<?= LanguageHelper::t('orders.confirm_cancel') ?>';
                                            
                                            <?php if (isset($order['payment_method']) && $order['payment_method'] === 'online' && 
                                                      isset($order['payment_status']) && $order['payment_status'] === 'paid'): ?>
                                                message = '<?= LanguageHelper::t('orders.confirm_cancel_paid') ?>';
                                            <?php endif; ?>
                                            
                                            if (confirm(message)) {
                                                event.target.submit();
                                            }
                                            return false;
                                        }
                                        </script>
                                    <?php endif; ?>
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
                        <?php
                        $statusKey = ($filterStatus === 'delivering') ? 'shipping' : $filterStatus;
                        echo str_replace(':status', LanguageHelper::t('profile.' . $statusKey), LanguageHelper::t('orders.no_orders_status'));
                        ?>
                    <?php else: ?>
                        <?= LanguageHelper::t('orders.no_orders_yet') ?>
                    <?php endif; ?>
                </h2>
                <p class="text-gray-600 mb-6">
                    <?php if ($filterStatus): ?>
                        <?= LanguageHelper::t('orders.no_orders_msg') ?>
                    <?php else: ?>
                        <?= LanguageHelper::t('orders.start_shopping_msg') ?>
                    <?php endif; ?>
                </p>
                <?php if (!$filterStatus): ?>
                    <a href="/SHooad/public/customer/products" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                        <i class="fas fa-shopping-cart mr-2"></i><?= LanguageHelper::t('orders.start_shopping') ?>
                    </a>
                <?php else: ?>
                    <a href="/SHooad/public/customer/orders" 
                       class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition">
                        <i class="fas fa-list mr-2"></i><?= LanguageHelper::t('orders.view_all_orders') ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Refund Notice Modal -->
<?php if (isset($_SESSION['show_refund_notice']) && $_SESSION['show_refund_notice']): ?>
<div id="refundNoticeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-check-circle text-green-600 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3"><?= LanguageHelper::t('orders.order_cancelled') ?></h3>
            <p class="text-gray-600 mb-6">
                <?= str_replace('{order_id}', $_SESSION['refund_order_id'] ?? '', LanguageHelper::t('orders.order_cancelled_msg')) ?>
                <br><br>
                <span class="text-green-600 font-semibold"><?= LanguageHelper::t('orders.refund_msg') ?></span>
                <br>
                <span class="text-sm text-gray-500"><?= LanguageHelper::t('orders.refund_note') ?></span>
            </p>
            <button onclick="closeRefundModal()" 
                    class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <?= LanguageHelper::t('orders.got_it') ?>
            </button>
        </div>
    </div>
</div>

<script>
function closeRefundModal() {
    document.getElementById('refundNoticeModal').style.display = 'none';
    <?php 
    unset($_SESSION['show_refund_notice']);
    unset($_SESSION['refund_order_id']);
    ?>
}
</script>
<?php endif; ?>
