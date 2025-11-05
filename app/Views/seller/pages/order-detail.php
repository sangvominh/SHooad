<?php
$subtotal = $order["subtotal"];
$shipping = $order["shipping_fee"];
$tax = $order["tax"];
$total = $subtotal + $shipping + $tax;
$shipping_address = $order["shipping_ward"] . ', ' . $order["shipping_city"] . ', ' . $order["shipping_province"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail #<?php echo $orderId; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Back button linking to home page -->
                    <a href="/SHooad/public/seller/dashboard" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Back to Home</span>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Order Details</h1>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        <?php 
                        echo match($order['status']) {
                            'Pending' => 'bg-yellow-100 text-yellow-800',
                            'Processing' => 'bg-blue-100 text-blue-800',
                            'Completed' => 'bg-green-100 text-green-800',
                            'Cancelled' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                        ?>
                    ">
                        <?php echo $order['status']; ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Order Items -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Header Info -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Order #<?php echo $orderId; ?></h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Order Date</p>
                                <p class="font-medium text-gray-900"><?php echo $order['created_at']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                                <p class="font-medium text-gray-900">$<?php echo number_format($order['total_amount'], 2); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Payment Method</p>
                                <p class="font-medium text-gray-900"><?php echo $order['payment_method']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Payment Status</p>
                                <p class="font-medium text-green-600"><?php echo $order['payment_status']; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="font-bold text-gray-900">Order Items</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($order_items as $item): ?>
                            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></p>
                                    <p class="text-sm text-gray-600">Price: $<?php echo number_format($item['price'], 2); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 mb-1">Qty: <span class="font-medium"><?php echo $item['quantity']; ?></span></p>
                                    <p class="font-medium text-gray-900">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Summary & Customer Info -->
                <div class="space-y-6">
                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <!-- <button class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium">
                            Print Order
                        </button> -->
                        <form method="POST" action="/SHooad/public/seller/update-order-status" class="space-y-2">
                            <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">

                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Processing" <?php if ($order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                <option value="Completed" <?php if ($order['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                <option value="Cancelled" <?php if ($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>

                            <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium">
                                Update Status
                            </button>
                        </form>
                    </div>
                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Order Summary</h3>
                        <div class="space-y-3 pb-4 border-b border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-gray-900">$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping:</span>
                                <span class="text-gray-900">$<?php echo number_format($shipping, 2); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax:</span>
                                <span class="text-gray-900">$<?php echo number_format($tax, 2); ?></span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-4">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-teal-600">$<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Customer Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Name</p>
                                <p class="text-gray-900"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Email</p>
                                <p class="text-gray-900 break-all"><?php echo htmlspecialchars($order['customer_email']); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Phone</p>
                                <p class="text-gray-900"><?php echo htmlspecialchars($order['customer_phone']); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Shipping Address</h3>
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wide mb-2">Address</p>
                            <p class="text-gray-900"><?php echo htmlspecialchars($shipping_address); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
