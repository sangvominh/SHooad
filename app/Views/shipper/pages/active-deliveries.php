<?php
$current_page = 'deliveries';
$page_title = 'Active Deliveries';

// Sample data (replace with database queries)
$stats = [
  [
    'title' => 'Active Orders',
    'value' => '12',
    'change' => '+23.5%',
    'change_type' => 'positive',
    'icon' => 'package'
  ],
  [
    'title' => 'In Transit',
    'value' => '8',
    'change' => '+12.3%',
    'change_type' => 'positive',
    'icon' => 'truck'
  ],
  [
    'title' => 'Pending Pickup',
    'value' => '4',
    'change' => '+5.2%',
    'change_type' => 'positive',
    'icon' => 'clock'
  ],
  [
    'title' => "Today's Earnings",
    'value' => '$234.50',
    'change' => '+18.5%',
    'change_type' => 'positive',
    'icon' => 'dollar'
  ]
];

$deliveries = [
  ['id' => 'ORD-001234', 'customer' => 'John Doe', 'pickup' => '123 Main St', 'delivery' => '456 Oak Ave', 'status' => 'In Transit', 'amount' => '$12.50'],
  ['id' => 'ORD-001235', 'customer' => 'Jane Smith', 'pickup' => '789 Elm St', 'delivery' => '321 Pine Rd', 'status' => 'Pending Pickup', 'amount' => '$15.75'],
  ['id' => 'ORD-001236', 'customer' => 'Mike Johnson', 'pickup' => '555 Maple Dr', 'delivery' => '999 Cedar Ln', 'status' => 'At Location', 'amount' => '$20.00'],
  ['id' => 'ORD-001237', 'customer' => 'Sarah Wilson', 'pickup' => '111 Birch Ave', 'delivery' => '222 Spruce St', 'status' => 'In Transit', 'amount' => '$18.50'],
  ['id' => 'ORD-001238', 'customer' => 'Tom Brown', 'pickup' => '333 Willow Way', 'delivery' => '444 Ash Blvd', 'status' => 'Pending Pickup', 'amount' => '$22.25'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Active Deliveries - Shipper Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-60 h-screen bg-white border-r border-gray-200">
      <?php include 'partials/sidebar.php'; ?>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <?php include 'partials/header.php'; ?>
      
      <!-- Page Content -->
      <main class="flex-1 overflow-auto p-6">
        <!-- Page Title -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900"><?php echo $page_title; ?></h1>
          <p class="text-gray-600 mt-2">Manage and track your ongoing deliveries in real-time</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <?php foreach ($stats as $stat): ?>
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
              <div class="flex items-start justify-between">
                <div>
                  <p class="text-gray-600 text-sm font-medium"><?php echo $stat['title']; ?></p>
                  <p class="text-3xl font-bold text-gray-900 mt-3"><?php echo $stat['value']; ?></p>
                  <p class="text-sm mt-3 <?php echo $stat['change_type'] === 'positive' ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo $stat['change']; ?> this week
                  </p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-6">
          <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
              <input type="text" placeholder="Search by order ID or address..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <option>All Status</option>
              <option>Pending Pickup</option>
              <option>In Transit</option>
              <option>At Location</option>
            </select>
          </div>
        </div>

        <!-- Deliveries Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Order ID</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Customer</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Pickup Address</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Delivery Address</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Amount</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php foreach ($deliveries as $delivery): ?>
                <tr class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?php echo $delivery['id']; ?></td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo $delivery['customer']; ?></td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo $delivery['pickup']; ?></td>
                  <td class="px-6 py-4 text-sm text-gray-600"><?php echo $delivery['delivery']; ?></td>
                  <td class="px-6 py-4 text-sm">
                    <?php
                      $status = $delivery['status'];
                      $statusClass = '';
                      if ($status === 'In Transit') {
                        $statusClass = 'bg-green-100 text-green-700';
                      } elseif ($status === 'Pending Pickup') {
                        $statusClass = 'bg-amber-100 text-amber-700';
                      } else {
                        $statusClass = 'bg-blue-100 text-blue-700';
                      }
                    ?>
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full <?php echo $statusClass; ?>">
                      <?php echo $status; ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?php echo $delivery['amount']; ?></td>
                  <td class="px-6 py-4 text-sm">
                    <div class="flex gap-3">
                      <button class="text-indigo-600 hover:text-indigo-700 font-medium">View</button>
                      <button class="text-amber-600 hover:text-amber-700 font-medium">Update</button>
                      <button class="text-red-600 hover:text-red-700 font-medium">Cancel</button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex items-center justify-between">
          <p class="text-sm text-gray-600">Showing 1-5 of 12 deliveries</p>
          <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Previous</button>
            <button class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Next</button>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
