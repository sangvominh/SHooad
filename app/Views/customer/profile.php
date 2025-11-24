<!-- Profile Page -->
<?php
require_once __DIR__ . '/../../Helpers/LanguageHelper.php';
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Flash Messages -->
        <?php
        require_once __DIR__ . '/../../Services/FlashMessageService.php';
        $successMsg = FlashMessageService::getFlashMessage('success');
        $errorMsg = FlashMessageService::getFlashMessage('error');
        ?>
        
        <?php if ($successMsg): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($successMsg) ?></span>
                <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMsg): ?>
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($errorMsg) ?></span>
                <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>

        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900"><?= LanguageHelper::t('profile.title') ?></h1>
            <p class="text-gray-600 mt-2"><?= LanguageHelper::t('profile.subtitle') ?></p>
        </div>

        <?php 
        $customer = $data['customer'] ?? null;
        $addresses = $data['addresses'] ?? [];
        $orderStatusCounts = $data['orderStatusCounts'] ?? [];
        $recentOrders = $data['recentOrders'] ?? [];
        ?>

        <?php if ($customer): ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Profile Card -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-900"><?= LanguageHelper::t('profile.personal_info') ?></h2>
                            <button onclick="openEditProfileModal()" class="text-blue-600 hover:text-blue-700 font-medium">
                                <i class="fas fa-edit mr-1"></i><?= LanguageHelper::t('profile.edit') ?>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-32 text-gray-600 font-medium"><?= LanguageHelper::t('profile.full_name_label') ?></div>
                                <div class="flex-1 text-gray-900"><?= htmlspecialchars($customer['name']) ?></div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-32 text-gray-600 font-medium"><?= LanguageHelper::t('profile.email_label') ?></div>
                                <div class="flex-1 text-gray-900"><?= htmlspecialchars($customer['email']) ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Addresses Section -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-900"><?= LanguageHelper::t('profile.saved_addresses') ?></h2>
                            <button onclick="openAddAddressModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                <i class="fas fa-plus mr-2"></i><?= LanguageHelper::t('profile.add_new') ?>
                            </button>
                        </div>

                            <?php if (count($addresses) > 0): ?>
                                <div class="space-y-4">
                                    <?php foreach ($addresses as $address): ?>
                                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($address['full_name']) ?></h3>
                                                        <?php if ($address['is_default']): ?>
                                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded"><?= LanguageHelper::t('profile.default') ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <p class="text-gray-600 mb-1">
                                                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                                                        <?= htmlspecialchars($address['phone']) ?>
                                                    </p>
                                                    <p class="text-gray-600">
                                                        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                                        <?= htmlspecialchars($address['address']) ?>
                                                    </p>
                                                </div>
                                                <div class="flex gap-2 ml-4">
                                                    <button onclick='openEditAddressModal(<?= json_encode($address) ?>)' class="text-blue-600 hover:text-blue-700 p-2" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button onclick="confirmDeleteAddress(<?= $address['id'] ?>)" class="text-red-600 hover:text-red-700 p-2" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <i class="fas fa-map-marked-alt text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg"><?= LanguageHelper::t('profile.no_addresses') ?></p>
                                    <p class="text-gray-400 mt-2"><?= LanguageHelper::t('profile.add_address_msg') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- My Orders Section -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-900"><?= LanguageHelper::t('profile.my_orders') ?></h2>
                            <a href="/SHooad/public/customer/orders" class="text-blue-600 hover:text-blue-700 font-medium">
                                <?= LanguageHelper::t('profile.view_all') ?> <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- Order Status Tabs -->
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-2 mb-6">
                            <?php
                            $statuses = [
                                'pending' => ['icon' => 'fa-clock', 'color' => 'yellow'],
                                'processing' => ['icon' => 'fa-box', 'color' => 'blue'],
                                'delivering' => ['icon' => 'fa-truck', 'color' => 'indigo'],
                                'completed' => ['icon' => 'fa-check-double', 'color' => 'green'],
                                'cancelled' => ['icon' => 'fa-times-circle', 'color' => 'red'],
                                'failed' => ['icon' => 'fa-exclamation-triangle', 'color' => 'red']
                            ];
                            
                            foreach ($statuses as $status => $info):
                                $count = $orderStatusCounts[$status] ?? 0;
                                $labelKey = ($status === 'delivering') ? 'shipping' : $status;
                            ?>
                                <a href="/SHooad/public/customer/orders?status=<?= $status ?>" 
                                   class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-<?= $info['color'] ?>-500 hover:bg-<?= $info['color'] ?>-50 transition group">
                                    <div class="relative">
                                        <i class="fas <?= $info['icon'] ?> text-2xl text-gray-400 group-hover:text-<?= $info['color'] ?>-600 mb-2"></i>
                                        <?php if ($count > 0): ?>
                                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                                <?= $count > 9 ? '9+' : $count ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-xs text-gray-600 group-hover:text-<?= $info['color'] ?>-600 text-center">
                                        <?= LanguageHelper::t('profile.' . $labelKey) ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <!-- Recent Orders -->
                        <?php if (count($recentOrders) > 0): ?>
                            <div class="space-y-3">
                                <?php foreach ($recentOrders as $order): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-gray-500 text-sm">Order #<?= $order['id'] ?></span>
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded <?php
                                                        echo match($order['status']) {
                                                            'Completed' => 'bg-green-100 text-green-800',
                                                            'Cancelled' => 'bg-red-100 text-red-800',
                                                            'Delivering' => 'bg-indigo-100 text-indigo-800',
                                                            'Processing' => 'bg-blue-100 text-blue-800',
                                                            'Paid' => 'bg-green-100 text-green-800',
                                                            default => 'bg-orange-100 text-orange-800'
                                                        };
                                                    ?>"><?= str_replace('_', ' ', $order['status']) ?></span>
                                                </div>
                                                <p class="text-gray-900 font-medium"><?= htmlspecialchars($order['shop_name'] ?? 'Shop') ?></p>
                                                <p class="text-gray-500 text-sm"><?= date('d/m/Y H:i', strtotime($order['date'])) ?></p>
                                            </div>
                                            <a href="/SHooad/public/customer/order-detail?id=<?= $order['id'] ?>" 
                                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                <?= LanguageHelper::t('profile.view_detail') ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-12">
                                <i class="fas fa-shopping-bag text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg"><?= LanguageHelper::t('profile.no_orders') ?></p>
                                <p class="text-gray-400 mt-2"><?= LanguageHelper::t('profile.start_shopping') ?></p>
                                <a href="/SHooad/public/customer/products" 
                                   class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                                    <?= LanguageHelper::t('profile.browse_products') ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4"><?= LanguageHelper::t('profile.quick_actions') ?></h2>
                        <div class="space-y-2">
                            <a href="/SHooad/public/customer/orders" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition group">
                                <i class="fas fa-list-ul text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                <span class="text-gray-700 group-hover:text-blue-600"><?= LanguageHelper::t('profile.all_orders') ?></span>
                            </a>
                            <a href="/SHooad/public/customer/cart" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition group">
                                <i class="fas fa-shopping-cart text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                <span class="text-gray-700 group-hover:text-blue-600"><?= LanguageHelper::t('profile.my_cart') ?></span>
                            </a>
                            <button onclick="openChangePasswordModal()" class="w-full flex items-center p-3 rounded-lg hover:bg-gray-50 transition group">
                                <i class="fas fa-key text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                <span class="text-gray-700 group-hover:text-blue-600"><?= LanguageHelper::t('profile.change_password') ?></span>
                            </button>
                            <a href="/SHooad/public/customer/logout" class="flex items-center p-3 rounded-lg hover:bg-red-50 transition group">
                                <i class="fas fa-sign-out-alt text-gray-400 group-hover:text-red-600 mr-3"></i>
                                <span class="text-gray-700 group-hover:text-red-600">Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-user-slash text-gray-300 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-2"><?= LanguageHelper::t('profile.profile_not_found') ?></h2>
                <p class="text-gray-600"><?= LanguageHelper::t('profile.unable_load_profile') ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Edit Profile</h3>
            <button onclick="closeEditProfileModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editProfileForm" method="POST" action="/SHooad/public/customer/update-profile">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($customer['name'] ?? '') ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeEditProfileModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Address Modal -->
<div id="addAddressModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Add New Address</h3>
            <button onclick="closeAddAddressModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="addAddressForm" method="POST" action="/SHooad/public/customer/add-address">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="full_name" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_default" value="1" id="isDefault" class="mr-2">
                    <label for="isDefault" class="text-sm text-gray-700">Set as default address</label>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeAddAddressModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add Address
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Address Modal -->
<div id="editAddressModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Edit Address</h3>
            <button onclick="closeEditAddressModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editAddressForm" method="POST" action="/SHooad/public/customer/edit-address">
            <input type="hidden" name="address_id" id="edit_address_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="full_name" id="edit_full_name" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" id="edit_phone" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" id="edit_address" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_default" value="1" id="edit_isDefault" class="mr-2">
                    <label for="edit_isDefault" class="text-sm text-gray-700">Set as default address</label>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeEditAddressModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Address
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Address Confirmation Modal -->
<div id="deleteAddressModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-red-600">Delete Address</h3>
            <button onclick="closeDeleteAddressModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <p class="text-gray-700 mb-6">Are you sure you want to delete this address? This action cannot be undone.</p>
        <form id="deleteAddressForm" method="POST" action="/SHooad/public/customer/delete-address">
            <input type="hidden" name="address_id" id="delete_address_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteAddressModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Change Password</h3>
            <button onclick="closeChangePasswordModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="changePasswordForm" method="POST" action="/SHooad/public/customer/change-password">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" name="new_password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" name="confirm_password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeChangePasswordModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Change Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditProfileModal() {
    document.getElementById('editProfileModal').classList.remove('hidden');
}

function closeEditProfileModal() {
    document.getElementById('editProfileModal').classList.add('hidden');
}

function openAddAddressModal() {
    document.getElementById('addAddressModal').classList.remove('hidden');
}

function closeAddAddressModal() {
    document.getElementById('addAddressModal').classList.add('hidden');
}

function openChangePasswordModal() {
    document.getElementById('changePasswordModal').classList.remove('hidden');
}

function closeChangePasswordModal() {
    document.getElementById('changePasswordModal').classList.add('hidden');
}

function openEditAddressModal(address) {
    document.getElementById('edit_address_id').value = address.id;
    document.getElementById('edit_full_name').value = address.full_name;
    document.getElementById('edit_phone').value = address.phone;
    document.getElementById('edit_address').value = address.address;
    document.getElementById('edit_isDefault').checked = address.is_default == 1;
    document.getElementById('editAddressModal').classList.remove('hidden');
}

function closeEditAddressModal() {
    document.getElementById('editAddressModal').classList.add('hidden');
}

function confirmDeleteAddress(addressId) {
    document.getElementById('delete_address_id').value = addressId;
    document.getElementById('deleteAddressModal').classList.remove('hidden');
}

function closeDeleteAddressModal() {
    document.getElementById('deleteAddressModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const modals = ['editProfileModal', 'addAddressModal', 'editAddressModal', 'deleteAddressModal', 'changePasswordModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
</script>
