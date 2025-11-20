<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Load language helper
require_once __DIR__ . '/../../Helpers/LanguageHelper.php';

$cart_items = $data['cart_items'] ?? [];
$selectedItems = array_filter($cart_items, function($item) {
    return isset($item['selected']) && $item['selected'];
});

$subtotal = 0;
foreach ($selectedItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Get delivery companies
require_once __DIR__ . '/../../Core/Database.php';
$db = (new Database())->getConnection();
$deliveryCompanies = [];
if (!$db->connect_error) {
    $result = $db->query("SELECT * FROM delivery_companies ORDER BY name ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $deliveryCompanies[] = $row;
        }
        $result->free();
    }
}

// Get customer saved addresses
$customerAddresses = [];
if (isset($_SESSION['customer_id'])) {
    $db = (new Database())->getConnection();
    if (!$db->connect_error) {
        $customerId = intval($_SESSION['customer_id']);
        $result = $db->query("SELECT * FROM customer_addresses WHERE customer_id = $customerId ORDER BY is_default DESC, created_at DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $customerAddresses[] = $row;
            }
            $result->free();
        }
    }
}

$shipping = 30000; // Default shipping fee 30k VND
$tax = 0;
$total = $subtotal + $shipping + $tax;
?>

<!-- Checkout Page -->
<main class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8"><?= LanguageHelper::t('checkout.title') ?></h1>
    
    <?php if (empty($selectedItems)): ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-lg text-gray-700"><?= LanguageHelper::t('cart.empty_msg') ?></p>
            <a href="/SHooad/public/customer/cart" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <?= LanguageHelper::t('cart.continue_shopping') ?>
            </a>
        </div>
    <?php else: ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Shipping Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Shipping Address -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6"><?= LanguageHelper::t('checkout.shipping_info') ?></h2>
                
                <?php if (!empty($customerAddresses)): ?>
                <!-- Address Selection Tabs -->
                <div class="flex gap-4 mb-6 border-b border-gray-200">
                    <button type="button" id="tab-saved-address" class="px-4 py-3 font-semibold text-blue-600 border-b-2 border-blue-600 tab-btn">
                        <i class="fas fa-bookmark mr-2"></i><?= LanguageHelper::t('profile.saved_addresses') ?>
                    </button>
                    <button type="button" id="tab-new-address" class="px-4 py-3 font-semibold text-gray-500 hover:text-gray-700 tab-btn">
                        <i class="fas fa-plus-circle mr-2"></i><?= LanguageHelper::t('checkout.new_address') ?>
                    </button>
                </div>
                
                <!-- Saved Addresses Section -->
                <div id="saved-addresses-section" class="mb-6">
                    <div class="space-y-3">
                        <?php foreach ($customerAddresses as $idx => $addr): ?>
                        <label class="flex items-start p-4 border-2 <?= $idx === 0 ? 'border-blue-500 bg-blue-50' : 'border-gray-300' ?> rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                            <input type="radio" name="saved_address" value="<?= $addr['id'] ?>" 
                                   class="mt-1 w-5 h-5 text-blue-600 saved-address-radio" 
                                   <?= $idx === 0 ? 'checked' : '' ?>
                                   data-fullname="<?= htmlspecialchars($addr['full_name']) ?>"
                                   data-phone="<?= htmlspecialchars($addr['phone']) ?>"
                                   data-address="<?= htmlspecialchars($addr['address']) ?>">
                            <div class="ml-3 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-900"><?= htmlspecialchars($addr['full_name']) ?></span>
                                    <?php if ($addr['is_default']): ?>
                                    <span class="px-2 py-0.5 bg-blue-600 text-white text-xs rounded font-medium"><?= LanguageHelper::t('profile.default') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i><?= htmlspecialchars($addr['phone']) ?>
                                </div>
                                <div class="text-sm text-gray-700 mt-1">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i><?= htmlspecialchars($addr['address']) ?>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- New Address Form -->
                <form id="checkoutForm" class="space-y-4" <?= !empty($customerAddresses) ? 'style="display:none;"' : '' ?>>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1"><?= LanguageHelper::t('checkout.full_name') ?> *</label>
                            <input type="text" name="full_name" id="full_name" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1"><?= LanguageHelper::t('checkout.phone') ?> *</label>
                            <input type="tel" name="phone" id="phone" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><?= LanguageHelper::t('checkout.email') ?></label>
                        <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['customer_email'] ?? '') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><?= LanguageHelper::t('checkout.shipping_address') ?> *</label>
                        <textarea name="shipping_address" id="shipping_address" required rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><?= LanguageHelper::t('checkout.note') ?></label>
                        <textarea name="note" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="<?= LanguageHelper::t('checkout.note_placeholder') ?>"></textarea>
                    </div>
                </form>
            </div>
            
            <!-- Delivery Company Selection -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4"><?= LanguageHelper::t('checkout.delivery_company') ?></h2>
                <div class="space-y-3">
                    <?php foreach ($deliveryCompanies as $idx => $company): ?>
                    <label class="flex items-center justify-between p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <div class="flex items-center">
                            <input type="radio" name="delivery_company" value="<?= $company['id'] ?>" 
                                   <?= $idx === 0 ? 'checked' : '' ?> 
                                   class="w-4 h-4 text-blue-600 delivery-radio" 
                                   data-fee="<?= isset($company['shipping_fee']) ? floatval($company['shipping_fee']) : 30000 ?>">
                            <span class="ml-3 text-gray-700 font-medium"><?= htmlspecialchars($company['name']) ?></span>
                        </div>
                        <span class="text-sm text-gray-600 shipping-fee-text">
                            <?= number_format(isset($company['shipping_fee']) ? $company['shipping_fee'] : 30000, 0, ',', '.') ?>₫
                        </span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Payment Method -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4"><?= LanguageHelper::t('checkout.payment_method') ?></h2>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="payment_method" value="cod" checked class="w-4 h-4 text-blue-600">
                        <div class="ml-3">
                            <span class="text-gray-900 font-medium block"><?= LanguageHelper::t('checkout.cod') ?></span>
                            <span class="text-sm text-gray-500">Thanh toán khi nhận hàng</span>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="payment_method" value="online" class="w-4 h-4 text-blue-600">
                        <div class="ml-3">
                            <span class="text-gray-900 font-medium block"><?= LanguageHelper::t('checkout.bank_transfer') ?></span>
                            <span class="text-sm text-gray-500">Chuyển khoản ngân hàng (QR Code)</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4"><?= LanguageHelper::t('checkout.your_order') ?></h2>
                
                <!-- Order Items -->
                <div class="space-y-3 border-b border-gray-200 pb-4 mb-4 max-h-60 overflow-y-auto">
                    <?php foreach ($selectedItems as $item): ?>
                    <div class="flex gap-3">
                        <img src="<?= htmlspecialchars($item['image'] ?? '/SHooad/public/assets/logo/default-avatar.png') ?>" 
                            alt="<?= htmlspecialchars($item['name']) ?>" 
                            class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['name']) ?></h4>
                            <p class="text-xs text-gray-500">Size: <?= htmlspecialchars($item['size']) ?> | Color: <?= htmlspecialchars($item['color']) ?></p>
                            <p class="text-sm text-gray-700">x<?= $item['quantity'] ?> - <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>₫</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Price Summary -->
                <div class="space-y-2 border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between text-gray-700">
                        <span><?= LanguageHelper::t('checkout.subtotal') ?></span>
                        <span id="subtotal-display"><?= number_format($subtotal, 0, ',', '.') ?>₫</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span><?= LanguageHelper::t('checkout.shipping') ?></span>
                        <span id="shipping-display"><?= number_format($shipping, 0, ',', '.') ?>₫</span>
                    </div>
                </div>
                
                <!-- Total -->
                <div class="flex justify-between items-center mb-6">
                    <span class="text-lg font-bold text-gray-900"><?= LanguageHelper::t('checkout.total') ?></span>
                    <span class="text-2xl font-bold text-red-600" id="total-display"><?= number_format($total, 0, ',', '.') ?>₫</span>
                </div>
                
                <!-- Place Order Button -->
                <button id="placeOrderBtn" type="button"
                    class="w-full bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700 transition">
                    <?= LanguageHelper::t('checkout.place_order') ?>
                </button>
            </div>
        </div>
    </div>
    
    <?php endif; ?>
</main>

<!-- QR Payment Modal -->
<div id="qrPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Quét mã QR để thanh toán</h3>
            <p class="text-gray-600 mb-6">Vui lòng quét mã QR bên dưới để hoàn tất thanh toán</p>
            
            <!-- QR Code Image -->
            <div class="bg-gray-100 p-6 rounded-lg mb-6">
                <img src="/SHooad/public/assets/qrcode.png" alt="QR Code" class="mx-auto w-64 h-64" 
                        src="../../../public/assets/qrcode.png" >
            </div>
            
            <!-- Payment Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                <p class="text-sm text-gray-700 mb-2"><strong>Số tiền:</strong> <span id="qrPaymentAmount" class="text-red-600 font-bold"></span></p>
                <p class="text-sm text-gray-700 mb-2"><strong>Ngân hàng:</strong> MB Bank</p>
                <p class="text-sm text-gray-700 mb-2"><strong>Số tài khoản:</strong> 0123456789</p>
                <p class="text-sm text-gray-700"><strong>Chủ TK:</strong> NGUYEN VAN A</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button id="cancelPaymentBtn" type="button" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                    Hủy
                </button>
                <button id="confirmPaymentBtn" type="button" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                    <i class="fas fa-check mr-2"></i>Đã chuyển khoản
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var placeOrderBtn = document.getElementById('placeOrderBtn');
    var checkoutForm = document.getElementById('checkoutForm');
    var subtotal = <?= $subtotal ?>;
    var currentShippingFee = <?= $shipping ?>;
    
    // Format VND
    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
    }
    
    // Handle address tabs
    var tabSavedAddress = document.getElementById('tab-saved-address');
    var tabNewAddress = document.getElementById('tab-new-address');
    var savedAddressesSection = document.getElementById('saved-addresses-section');
    
    if (tabSavedAddress && tabNewAddress) {
        tabSavedAddress.addEventListener('click', function() {
            // Update tab styles
            tabSavedAddress.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
            tabSavedAddress.classList.remove('text-gray-500');
            tabNewAddress.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
            tabNewAddress.classList.add('text-gray-500');
            
            // Show/hide sections
            savedAddressesSection.style.display = 'block';
            checkoutForm.style.display = 'none';
            
            // Select first saved address
            var firstRadio = document.querySelector('.saved-address-radio');
            if (firstRadio) {
                firstRadio.checked = true;
                document.getElementById('full_name').value = firstRadio.dataset.fullname || '';
                document.getElementById('phone').value = firstRadio.dataset.phone || '';
                document.getElementById('shipping_address').value = firstRadio.dataset.address || '';
            }
        });
        
        tabNewAddress.addEventListener('click', function() {
            // Update tab styles
            tabNewAddress.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
            tabNewAddress.classList.remove('text-gray-500');
            tabSavedAddress.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
            tabSavedAddress.classList.add('text-gray-500');
            
            // Show/hide sections
            savedAddressesSection.style.display = 'none';
            checkoutForm.style.display = 'block';
            
            // Clear form
            document.getElementById('full_name').value = '';
            document.getElementById('phone').value = '';
            document.getElementById('shipping_address').value = '';
        });
    }
    
    // Handle saved address selection
    var savedAddressRadios = document.querySelectorAll('.saved-address-radio');
    savedAddressRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            // Update border styles
            document.querySelectorAll('.saved-address-radio').forEach(function(r) {
                var label = r.closest('label');
                if (label) {
                    if (r.checked) {
                        label.classList.add('border-blue-500', 'bg-blue-50');
                        label.classList.remove('border-gray-300');
                    } else {
                        label.classList.remove('border-blue-500', 'bg-blue-50');
                        label.classList.add('border-gray-300');
                    }
                }
            });
            
            // Fill form with saved address data (even if form is hidden)
            var fullNameField = document.getElementById('full_name');
            var phoneField = document.getElementById('phone');
            var shippingAddressField = document.getElementById('shipping_address');
            
            if (fullNameField) fullNameField.value = this.dataset.fullname || '';
            if (phoneField) phoneField.value = this.dataset.phone || '';
            if (shippingAddressField) shippingAddressField.value = this.dataset.address || '';
        });
    });
    
    // Initialize with first saved address if available
    if (savedAddressRadios.length > 0) {
        var firstChecked = document.querySelector('.saved-address-radio:checked');
        if (firstChecked) {
            var fullNameField = document.getElementById('full_name');
            var phoneField = document.getElementById('phone');
            var shippingAddressField = document.getElementById('shipping_address');
            
            if (fullNameField) fullNameField.value = firstChecked.dataset.fullname || '';
            if (phoneField) phoneField.value = firstChecked.dataset.phone || '';
            if (shippingAddressField) shippingAddressField.value = firstChecked.dataset.address || '';
        }
    }
    
    // Handle delivery company selection
    var deliveryRadios = document.querySelectorAll('.delivery-radio');
    deliveryRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            currentShippingFee = parseInt(this.dataset.fee) || 30000;
            updateTotal();
        });
    });
    
    function updateTotal() {
        var total = subtotal + currentShippingFee;
        document.getElementById('shipping-display').textContent = formatVND(currentShippingFee);
        document.getElementById('total-display').textContent = formatVND(total);
    }
    
    // QR Payment Modal handling
    var qrModal = document.getElementById('qrPaymentModal');
    var confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
    var cancelPaymentBtn = document.getElementById('cancelPaymentBtn');
    var pendingOrderData = null;
    
    function showQRModal(totalAmount) {
        document.getElementById('qrPaymentAmount').textContent = formatVND(totalAmount);
        qrModal.style.display = 'flex';
    }
    
    function hideQRModal() {
        qrModal.style.display = 'none';
        pendingOrderData = null;
    }
    
    if (cancelPaymentBtn) {
        cancelPaymentBtn.addEventListener('click', function() {
            hideQRModal();
            placeOrderBtn.disabled = false;
            placeOrderBtn.textContent = '<?= LanguageHelper::t('checkout.place_order') ?>';
        });
    }
    
    if (confirmPaymentBtn) {
        confirmPaymentBtn.addEventListener('click', function() {
            // User confirmed payment, proceed with order
            if (!pendingOrderData) return;
            
            confirmPaymentBtn.disabled = true;
            confirmPaymentBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
            
            fetch('/SHooad/app/Routes/place-order.php', {
                method: 'POST',
                body: pendingOrderData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    hideQRModal();
                    window.location.href = '/SHooad/public/customer/order-success?order_id=' + data.order_id;
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể đặt hàng'));
                    confirmPaymentBtn.disabled = false;
                    confirmPaymentBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Đã chuyển khoản';
                }
            })
            .catch(function(err) {
                console.error('Error:', err);
                alert('Có lỗi xảy ra: ' + err.message);
                confirmPaymentBtn.disabled = false;
                confirmPaymentBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Đã chuyển khoản';
            });
        });
    }
    
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', function() {
            // Get form data - ensure fields exist and have values
            var fullNameField = document.getElementById('full_name');
            var phoneField = document.getElementById('phone');
            var shippingAddressField = document.getElementById('shipping_address');
            
            var fullName = fullNameField ? fullNameField.value.trim() : '';
            var phone = phoneField ? phoneField.value.trim() : '';
            var shippingAddress = shippingAddressField ? shippingAddressField.value.trim() : '';
            
            // Validate
            if (!fullName || !phone || !shippingAddress) {
                alert('Vui lòng điền đầy đủ thông tin giao hàng');
                return;
            }
            
            // Disable button
            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Đang xử lý...';
            
            // Collect all form data
            var formData = new FormData();
            formData.append('full_name', fullName);
            formData.append('phone', phone);
            formData.append('shipping_address', shippingAddress);
            
            var emailField = document.querySelector('input[name="email"]');
            var noteField = document.querySelector('textarea[name="note"]');
            var paymentMethodField = document.querySelector('input[name="payment_method"]:checked');
            var deliveryCompanyField = document.querySelector('input[name="delivery_company"]:checked');
            
            formData.append('email', emailField ? emailField.value : '');
            formData.append('note', noteField ? noteField.value : '');
            var paymentMethod = paymentMethodField ? paymentMethodField.value : 'cod';
            formData.append('payment_method', paymentMethod);
            formData.append('delivery_company_id', deliveryCompanyField ? deliveryCompanyField.value : '1');
            formData.append('shipping_fee', currentShippingFee);
            
            // Check if online payment selected
            if (paymentMethod === 'online') {
                // Show QR modal first, don't send request yet
                pendingOrderData = formData;
                var total = subtotal + currentShippingFee;
                showQRModal(total);
                // Don't re-enable button here - will be done on modal close
            } else {
                // COD: proceed directly
                fetch('/SHooad/app/Routes/place-order.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        window.location.href = '/SHooad/public/customer/order-success?order_id=' + data.order_id;
                    } else {
                        alert('Lỗi: ' + (data.message || 'Không thể đặt hàng'));
                        placeOrderBtn.disabled = false;
                        placeOrderBtn.textContent = '<?= LanguageHelper::t('checkout.place_order') ?>';
                    }
                })
                .catch(function(err) {
                    console.error('Error:', err);
                    alert('Có lỗi xảy ra: ' + err.message);
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.textContent = '<?= LanguageHelper::t('checkout.place_order') ?>';
                });
            }
        });
    }
});
</script>
