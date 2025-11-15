<?php
// Header partial - Navigation and user info
$page_title = $page_title ?? 'Dashboard';
$user_name = $data['seller']['name'] ?? 'Seller Name';
$user_email = $data['seller']['email'] ?? 'seller@example.com';
?>
<header class="bg-white border-b border-gray-200">
  <div class="flex items-center justify-between px-6 py-4">
    <div class="flex items-center gap-4">
      <div>
        <h1 class="text-xl font-semibold text-gray-900"><?php echo $page_title; ?></h1>
        <p class="text-sm text-gray-600">Manage your shop</p>
      </div>
    </div>
    
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
        <!-- <div class="bg-teal-100 w-10 h-10 rounded-full flex items-center justify-center">
          <span class="text-teal-700 font-bold">JD</span>
        </div> -->
        <div>
          <p class="text-sm font-medium text-gray-900"><?php echo $user_name; ?></p>
          <p class="text-xs text-gray-600"><?php echo $user_email; ?></p>
        </div>
      </div>
    </div>
  </div>
</header>
