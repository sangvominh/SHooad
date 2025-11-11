<?php
$all_items = [
    [
        'id' => 1,
        'name' => 'Modern Green Sweater',
        'price' => 60,
        'original_price' => 120,
        'quantity' => 1
    ],
    [
        'id' => 2,
        'name' => 'Corporate Office Shoes',
        'price' => 399,
        'original_price' => 399,
        'quantity' => 1
    ],
    [
        'id' => 3,
        'name' => 'Women Hand Bags',
        'price' => 123,
        'original_price' => 150,
        'quantity' => 2
    ]
];

$selected_count = 0;
$original_total = 0;
$sale_total = 0;

foreach ($all_items as $item) {
    $item_qty = $item['quantity'];
    $original_total += $item['original_price'] * $item_qty;
    $sale_total += $item['price'] * $item_qty;
    $selected_count++;
}

$savings = $original_total - $sale_total;
$shipping = 0;
$grand_total = $sale_total + $shipping;
?>

<div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-8 h-fit">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary (<span id="selected-count">0</span>)</h2>
    
    <div class="space-y-4 border-b border-gray-200 pb-6">
        <!-- Original Price -->
        <div class="flex justify-between text-gray-700">
            <span>Original Price</span>
            <span id="original-price-display">$0.00</span>
        </div>

        <!-- Savings -->
        <div class="flex justify-between text-gray-700">
            <span>Savings</span>
            <span id="savings-display" class="text-green-600">$0.00</span>
        </div>

        <!-- Sale Price -->
        <div class="flex justify-between text-gray-700">
            <span>Sale Price</span>
            <span id="sale-price-display">$0.00</span>
        </div>

        <!-- Shipping -->
        <div class="flex justify-between text-gray-700">
            <span>Shipping</span>
            <span class="text-green-600">FREE</span>
        </div>
    </div>

    <!-- Total -->
    <div class="flex justify-between items-center py-6 border-b border-gray-200">
        <span class="text-xl font-bold text-gray-900">Total</span>
        <span class="text-3xl font-bold text-gray-900" id="total-display">$0.00</span>
    </div>

    <!-- Payment Button -->
    <a href="/SHooad/app/Views/user/">
        <button class="w-full bg-yellow-400 text-gray-900 font-bold py-3 rounded-lg mt-6 hover:bg-yellow-500 transition">
        Proceed to Payment
        </button>
    </a>
    
</div>
