<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis Dashboard - Seller</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <!-- Page Header with Dropdown -->
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Analysis Dashboard</h1>
                        <p class="text-gray-600 mt-1">Comprehensive analytics for your shop</p>
                        <?php if (empty($analysisData)): ?>
                        <p class="text-red-600 mt-2 text-sm">⚠️ Warning: No analysis data available</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Type Selector Dropdown -->
                    <div class="relative">
                        <select id="analysisTypeSelector" class="px-6 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700 font-medium cursor-pointer">
                            <option value="orders" <?php echo (isset($_GET['type']) && $_GET['type'] === 'orders') ? 'selected' : ''; ?>>Orders Analysis</option>
                            <option value="products" <?php echo (!isset($_GET['type']) || $_GET['type'] === 'products') ? 'selected' : ''; ?>>Products Analysis</option>
                        </select>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="hidden text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    <p class="mt-4 text-gray-600">Loading analysis data...</p>
                </div>

                <!-- Orders Analysis Content -->
                <div id="ordersAnalysis" class="analysis-content" style="display: <?php echo (isset($_GET['type']) && $_GET['type'] === 'orders') ? 'block' : 'none'; ?>;">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Total Orders Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                                    <p id="totalOrders" class="text-3xl font-bold text-gray-900 mt-2">-</p>
                                </div>
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Revenue Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                                    <p id="totalRevenue" class="text-3xl font-bold text-green-600 mt-2">-</p>
                                </div>
                                <div class="p-3 bg-green-100 rounded-full">
                                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Orders Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Completed</p>
                                    <p id="completedOrders" class="text-3xl font-bold text-gray-900 mt-2">-</p>
                                </div>
                                <div class="p-3 bg-purple-100 rounded-full">
                                    <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Orders Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Pending</p>
                                    <p id="pendingOrders" class="text-3xl font-bold text-orange-600 mt-2">-</p>
                                </div>
                                <div class="p-3 bg-orange-100 rounded-full">
                                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders by Status Cards -->
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Orders by Status</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Pending</p>
                                <p id="statusPending" class="text-2xl font-bold text-yellow-600">-</p>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Paid</p>
                                <p id="statusPaid" class="text-2xl font-bold text-blue-600">-</p>
                            </div>
                            <div class="text-center p-3 bg-indigo-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Processing</p>
                                <p id="statusProcessing" class="text-2xl font-bold text-indigo-600">-</p>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Delivering</p>
                                <p id="statusDelivering" class="text-2xl font-bold text-purple-600">-</p>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Completed</p>
                                <p id="statusCompleted" class="text-2xl font-bold text-green-600">-</p>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Cancelled</p>
                                <p id="statusCancelled" class="text-2xl font-bold text-red-600">-</p>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Failed</p>
                                <p id="statusFailed" class="text-2xl font-bold text-gray-600">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Revenue by Date Chart -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue by Date (Last 30 Days)</h3>
                            <canvas id="revenueByDateChart"></canvas>
                        </div>

                        <!-- Top Products Chart -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 Best Selling Products</h3>
                            <canvas id="topProductsOrdersChart"></canvas>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Orders List</h3>
                        </div>
                        <div class="p-6">
                            <table id="ordersTable" class="display w-full">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be populated by DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Products Analysis Content -->
                <div id="productsAnalysis" class="analysis-content" style="display: <?php echo (!isset($_GET['type']) || $_GET['type'] === 'products') ? 'block' : 'none'; ?>;">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Total Products Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                                    <p id="totalProducts" class="text-3xl font-bold text-gray-900 mt-2">-</p>
                                </div>
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <i class="fas fa-box text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Product Revenue Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Product Revenue</p>
                                    <p id="productRevenue" class="text-3xl font-bold text-green-600 mt-2">-</p>
                                </div>
                                <div class="p-3 bg-green-100 rounded-full">
                                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Low Stock (<10)</p>
                                    <p id="lowStock" class="text-3xl font-bold text-red-600 mt-2">-</p>
                                </div>
                                <div class="p-3 bg-red-100 rounded-full">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Top Products Chart -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 Best Selling Products</h3>
                            <canvas id="topProductsChart"></canvas>
                        </div>

                        <!-- Revenue by Category Chart -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue by Category</h3>
                            <canvas id="revenueByCategoryChart"></canvas>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Products List</h3>
                        </div>
                        <div class="p-6">
                            <table id="productsTable" class="display w-full">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Sold</th>
                                        <th>Stock</th>
                                        <th>Avg Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be populated by DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        // Pass initial data from PHP to JavaScript
        const initialAnalysisData = <?php echo json_encode($analysisData); ?>;
        const currentAnalysisType = '<?php echo $_GET['type'] ?? 'products'; ?>';
        
        // Debug - log data to console
        console.log('Analysis Type:', currentAnalysisType);
        console.log('Analysis Data:', initialAnalysisData);
    </script>
    <script src="/SHooad/public/assets/js/seller/analysis-filter.js"></script>
</body>
</html>
