<?php
// Header partial - Navigation and user info
$page_title = $page_title ?? 'Dashboard';
$user_name = 'John Doe';
$user_email = 'john@example.com';
?>
<header class="bg-white border-b border-gray-200">
  <div class="flex items-center justify-between px-6 py-4">
    <div class="flex items-center gap-4">
      <!-- Removed toggle button -->
      <div class="bg-teal-600 text-white px-3 py-2 rounded-lg font-bold text-lg">
        TS
      </div>
      <div>
        <h1 class="text-xl font-semibold text-gray-900"><?php echo $page_title; ?></h1>
        <p class="text-sm text-gray-600">Manage your seller account</p>
      </div>
    </div>
    
    <div class="flex items-center gap-4">
      <input type="text" placeholder="Search..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-teal-600">
      
      <button class="p-2 hover:bg-gray-100 rounded-lg">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </button>

      <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
        <div class="bg-teal-100 w-10 h-10 rounded-full flex items-center justify-center">
          <span class="text-teal-700 font-bold">JD</span>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-900"><?php echo $user_name; ?></p>
          <p class="text-xs text-gray-600"><?php echo $user_email; ?></p>
        </div>
      </div>
    </div>
  </div>
</header>
