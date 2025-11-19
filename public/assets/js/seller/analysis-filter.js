// Analysis Dashboard JavaScript
let ordersDataTable = null;
let productsDataTable = null;
let revenueChart = null;
let topProductsOrdersChart = null;
let topProductsChart = null;
let categoryChart = null;

// Helper function to format VND currency
function formatVND(amount) {
    return amount.toLocaleString('vi-VN') + ' ₫';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing analysis dashboard...');
    console.log('Current type:', currentAnalysisType);
    console.log('Has data:', !!initialAnalysisData);
    
    // Load initial data
    try {
        loadAnalysisData(currentAnalysisType);
    } catch (error) {
        console.error('Error in loadAnalysisData:', error);
    }
    
    // Setup type selector
    const selector = document.getElementById('analysisTypeSelector');
    if (selector) {
        selector.addEventListener('change', function() {
            const selectedType = this.value;
            console.log('Changing to type:', selectedType);
            window.location.href = `/SHooad/public/seller/analysis?type=${selectedType}`;
        });
    } else {
        console.error('Type selector not found!');
    }
    
    // Setup date range selector
    const dateSelector = document.getElementById('dateRangeSelector');
    if (dateSelector) {
        dateSelector.addEventListener('change', function() {
            const selectedDays = this.value;
            console.log('Changing date range to:', selectedDays, 'days');
            
            // Update chart title
            updateChartTitle(selectedDays);
            
            // Show loading state
            showLoadingState();
            
            // Fetch new data from server
            const url = `/SHooad/public/seller/analysis?type=${currentAnalysisType}&days=${selectedDays}&ajax=1`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Received filtered data:', data);
                    if (currentAnalysisType === 'orders') {
                        renderOrdersAnalysis(data);
                    } else {
                        renderProductsAnalysis(data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching analysis data:', error);
                    alert('Error loading analysis data. Please try again.');
                })
                .finally(() => {
                    hideLoadingState();
                });
        });
    }
});

function loadAnalysisData(type, days = 30) {
    console.log('Loading analysis data for type:', type, 'days:', days);
    console.log('Data received:', initialAnalysisData);
    
    try {
        if (type === 'orders') {
            renderOrdersAnalysis(initialAnalysisData);
        } else {
            renderProductsAnalysis(initialAnalysisData);
        }
    } catch (error) {
        console.error('Error loading analysis data:', error);
        hideLoading();
    }
}

function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('ordersAnalysis').style.display = 'none';
    document.getElementById('productsAnalysis').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
}

function renderOrdersAnalysis(data) {
    console.log('Rendering orders analysis...');
    hideLoading();
    
    document.getElementById('ordersAnalysis').style.display = 'block';
    document.getElementById('productsAnalysis').style.display = 'none';
    
    if (!data || !data.stats) {
        console.error('Invalid data structure:', data);
        return;
    }
    
    // Update stats cards
    document.getElementById('totalOrders').textContent = data.stats.total_orders.toLocaleString();
    document.getElementById('totalRevenue').textContent = formatVND(data.stats.total_revenue);
    document.getElementById('completedOrders').textContent = data.stats.by_status.Completed.toLocaleString();
    document.getElementById('pendingOrders').textContent = data.stats.by_status.Pending.toLocaleString();
    
    // Update status breakdown
    document.getElementById('statusPending').textContent = data.stats.by_status.Pending.toLocaleString();
    document.getElementById('statusPaid').textContent = data.stats.by_status.Paid.toLocaleString();
    document.getElementById('statusProcessing').textContent = data.stats.by_status.Processing.toLocaleString();
    document.getElementById('statusDelivering').textContent = data.stats.by_status.Delivering.toLocaleString();
    document.getElementById('statusCompleted').textContent = data.stats.by_status.Completed.toLocaleString();
    document.getElementById('statusCancelled').textContent = data.stats.by_status.Cancelled.toLocaleString();
    document.getElementById('statusFailed').textContent = data.stats.by_status.Failed.toLocaleString();
    
    // Render charts
    renderRevenueByDateChart(data.charts.revenue_by_date);
    renderTopProductsOrdersChart(data.charts.top_products);
    
    // Render table
    renderOrdersTable(data.orders);
    
    console.log('Orders analysis rendered successfully');
}

function renderProductsAnalysis(data) {
    console.log('Rendering products analysis...');
    hideLoading();
    
    document.getElementById('ordersAnalysis').style.display = 'none';
    document.getElementById('productsAnalysis').style.display = 'block';
    
    if (!data || !data.stats) {
        console.error('Invalid data structure:', data);
        return;
    }
    
    // Update stats cards
    document.getElementById('totalProducts').textContent = data.stats.total_products.toLocaleString();
    document.getElementById('productRevenue').textContent = formatVND(data.stats.total_revenue);
    document.getElementById('lowStock').textContent = data.stats.low_stock.toLocaleString();
    
    // Render charts
    renderTopProductsChart(data.charts.top_products);
    renderRevenueByCategoryChart(data.charts.revenue_by_category);
    
    // Render table
    renderProductsTable(data.products);
    
    console.log('Products analysis rendered successfully');
}

function renderRevenueByDateChart(data) {
    const ctx = document.getElementById('revenueByDateChart');
    
    if (revenueChart) {
        revenueChart.destroy();
    }
    
    if (!data || data.length === 0) {
        console.log('No revenue data available');
        data = [];
    }
    
    const labels = data.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    });
    const revenues = data.map(item => item.revenue);
    
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: revenues,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: ' + formatVND(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatVND(value);
                        }
                    }
                }
            }
        }
    });
}

function renderTopProductsOrdersChart(data) {
    const ctx = document.getElementById('topProductsOrdersChart');
    
    if (topProductsOrdersChart) {
        topProductsOrdersChart.destroy();
    }
    
    const labels = data.map(item => item.name.length > 20 ? item.name.substring(0, 20) + '...' : item.name);
    const quantities = data.map(item => item.quantity);
    
    topProductsOrdersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Quantity Sold',
                data: quantities,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(139, 92, 246)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

function renderTopProductsChart(data) {
    const ctx = document.getElementById('topProductsChart');
    
    if (topProductsChart) {
        topProductsChart.destroy();
    }
    
    const labels = data.map(item => item.name.length > 20 ? item.name.substring(0, 20) + '...' : item.name);
    const quantities = data.map(item => item.quantity);
    
    topProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Quantity Sold',
                data: quantities,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(139, 92, 246)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

function renderRevenueByCategoryChart(data) {
    const ctx = document.getElementById('revenueByCategoryChart');
    
    if (categoryChart) {
        categoryChart.destroy();
    }
    
    const labels = data.map(item => item.category);
    const revenues = data.map(item => item.revenue);
    
    const colors = [
        'rgba(59, 130, 246, 0.8)',
        'rgba(16, 185, 129, 0.8)',
        'rgba(245, 158, 11, 0.8)',
        'rgba(239, 68, 68, 0.8)',
        'rgba(139, 92, 246, 0.8)',
        'rgba(236, 72, 153, 0.8)',
        'rgba(20, 184, 166, 0.8)',
        'rgba(251, 146, 60, 0.8)'
    ];
    
    categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: revenues,
                backgroundColor: colors.slice(0, data.length),
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + formatVND(value) + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

function renderOrdersTable(orders) {
    if (ordersDataTable) {
        ordersDataTable.destroy();
    }
    
    ordersDataTable = $('#ordersTable').DataTable({
        data: orders,
        columns: [
            { 
                data: 'order_id',
                render: function(data) {
                    return '<a href="/SHooad/public/seller/order-detail?order_id=' + data + '" class="text-blue-600 hover:underline">#' + data + '</a>';
                }
            },
            { data: 'customer' },
            { 
                data: 'date',
                render: function(data) {
                    const date = new Date(data);
                    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    const statusColors = {
                        'Pending_Transfer': 'bg-yellow-100 text-yellow-800',
                        'Pending_COD': 'bg-yellow-100 text-yellow-800',
                        'Paid': 'bg-blue-100 text-blue-800',
                        'Processing': 'bg-indigo-100 text-indigo-800',
                        'Delivering': 'bg-purple-100 text-purple-800',
                        'Completed': 'bg-green-100 text-green-800',
                        'Cancelled': 'bg-red-100 text-red-800',
                        'Failed': 'bg-gray-100 text-gray-800'
                    };
                    const colorClass = statusColors[data] || 'bg-gray-100 text-gray-800';
                    return '<span class="px-2 py-1 text-xs font-semibold rounded ' + colorClass + '">' + data.replace('_', ' ') + '</span>';
                }
            },
            { 
                data: 'total',
                render: function(data) {
                    return formatVND(parseFloat(data));
                }
            }
        ],
        order: [[2, 'desc']],
        pageLength: 10,
        responsive: true,
        language: {
            search: "Search orders:",
            lengthMenu: "Show _MENU_ orders per page",
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
            infoEmpty: "No orders found",
            infoFiltered: "(filtered from _MAX_ total orders)"
        }
    });
}

function renderProductsTable(products) {
    if (productsDataTable) {
        productsDataTable.destroy();
    }
    
    productsDataTable = $('#productsTable').DataTable({
        data: products,
        columns: [
            { 
                data: 'product_id',
                render: function(data) {
                    return '<a href="/SHooad/public/seller/product-detail?product_id=' + data + '" class="text-blue-600 hover:underline">#' + data + '</a>';
                }
            },
            { 
                data: 'name',
                render: function(data) {
                    return data.length > 50 ? data.substring(0, 50) + '...' : data;
                }
            },
            { data: 'category' },
            { 
                data: 'sold',
                render: function(data) {
                    return data.toLocaleString();
                }
            },
            { 
                data: 'stock',
                render: function(data) {
                    const stockClass = data < 10 ? 'text-red-600 font-semibold' : 'text-gray-900';
                    return '<span class="' + stockClass + '">' + data.toLocaleString() + '</span>';
                }
            },
            { 
                data: 'avg_rating',
                render: function(data) {
                    const stars = '★'.repeat(Math.floor(data)) + '☆'.repeat(5 - Math.floor(data));
                    return '<span class="text-yellow-500">' + stars + '</span> <span class="text-gray-600">(' + data.toFixed(1) + ')</span>';
                }
            }
        ],
        order: [[3, 'desc']],
        pageLength: 10,
        responsive: true,
        language: {
            search: "Search products:",
            lengthMenu: "Show _MENU_ products per page",
            info: "Showing _START_ to _END_ of _TOTAL_ products",
            infoEmpty: "No products found",
            infoFiltered: "(filtered from _MAX_ total products)"
        }
    });
}

// Loading state helpers
function showLoadingState() {
    const overlay = document.createElement('div');
    overlay.id = 'loadingOverlay';
    overlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
    overlay.innerHTML = '<div class="bg-white rounded-lg p-6 flex items-center gap-3"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><span class="text-gray-700 font-medium">Loading data...</span></div>';
    document.body.appendChild(overlay);
}

function hideLoadingState() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.remove();
    }
}

// Update chart title based on selected time period
function updateChartTitle(days) {
    const titles = {
        '7': 'Last 7 days',
        '30': 'Last 30 days',
        '90': 'Last 90 days',
        '365': 'Last year',
        'all': 'All time'
    };
    const periodText = titles[days] || 'Last 30 days';
    
    const chartTitle = document.getElementById('revenueChartTitle');
    if (chartTitle) {
        chartTitle.textContent = `Revenue by Date (${periodText})`;
    }
}
