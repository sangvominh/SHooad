# Seller Analysis Dashboard

## Overview
A comprehensive analytics dashboard for sellers to track and analyze their shop's performance through orders and products statistics.

## Features

### Orders Analysis
- **Statistics Cards:**
  - Total Orders count
  - Total Revenue
  - Completed Orders
  - Pending Orders
  
- **Orders by Status Breakdown:**
  - Pending (Pending_Transfer + Pending_COD)
  - Paid
  - Processing
  - Delivering
  - Completed
  - Cancelled
  - Failed

- **Charts:**
  - Revenue by Date (Last 30 Days) - Line Chart
  - Top 5 Best Selling Products by Quantity - Bar Chart

- **Orders Table:**
  - Order ID (clickable link to order detail)
  - Customer Name
  - Order Date
  - Status (color-coded badges)
  - Total Amount
  - DataTables features: search, sort, pagination

### Products Analysis
- **Statistics Cards:**
  - Total Products count
  - Total Product Revenue
  - Low Stock Products (<10 units)

- **Charts:**
  - Top 5 Best Selling Products - Horizontal Bar Chart
  - Revenue by Category - Doughnut Chart

- **Products Table:**
  - Product ID (clickable link to product detail)
  - Product Name
  - Category
  - Quantity Sold
  - Stock (highlighted in red if low stock)
  - Average Rating (star display)
  - DataTables features: search, sort, pagination

## File Structure

```
app/
├── Controllers/
│   └── SellerController.php           # Added analysis() method
├── Services/
│   └── Seller/
│       └── SellerAnalysisService.php  # NEW - Analysis data logic
├── Routes/
│   └── seller.php                     # Added 'analysis' route
└── Views/
    └── seller/
        ├── js/
        │   └── analysis-filter.js     # NEW - Frontend logic
        ├── pages/
        │   └── analysis.php           # NEW - Main view
        └── partials/
            └── sidebar.php            # Updated with Analysis link
```

## Technical Implementation

### Backend (PHP)

#### SellerAnalysisService.php
Main service class handling all data queries:

**Methods:**
- `getOrdersAnalysis(int $shop_id)` - Returns complete orders analysis data
- `getProductsAnalysis(int $shop_id)` - Returns complete products analysis data
- `getOrderStats(int $shop_id)` - Order statistics with status breakdown
- `getProductStats(int $shop_id)` - Product statistics including low stock
- `getRevenueByDate(int $shop_id)` - Daily revenue for last 30 days
- `getTopSellingProducts(int $shop_id, int $limit)` - Top products by quantity sold
- `getRevenueByCategory(int $shop_id)` - Revenue breakdown by category
- `getOrdersList(int $shop_id)` - Complete orders list with customer info
- `getProductsList(int $shop_id)` - Complete products list with ratings

**Data Handling:**
- Excludes 'Cancelled' and 'Failed' orders from revenue calculations
- Aggregates Pending_Transfer and Pending_COD as "Pending"
- Calculates average ratings from reviews table
- Uses COALESCE for null-safe aggregations

#### SellerController.php
Added `analysis()` method:
- Checks seller authentication
- Handles type selection (orders/products)
- Supports AJAX requests for dynamic data loading
- Returns JSON for AJAX or renders full page

#### Routes
Added route: `case 'analysis'` → `$seller_controller->analysis()`

### Frontend (HTML/CSS/JS)

#### analysis.php
- TailwindCSS for responsive layout
- Chart.js for data visualization
- DataTables for table functionality
- FontAwesome for icons
- Dropdown selector for analysis type
- Two main content sections (orders/products) with dynamic visibility

#### analysis-filter.js
JavaScript functionality:

**Main Functions:**
- `loadAnalysisData(type)` - Loads and renders appropriate analysis view
- `renderOrdersAnalysis(data)` - Renders orders view with stats, charts, table
- `renderProductsAnalysis(data)` - Renders products view with stats, charts, table
- `renderRevenueByDateChart(data)` - Creates line chart for daily revenue
- `renderTopProductsOrdersChart(data)` - Creates vertical bar chart
- `renderTopProductsChart(data)` - Creates horizontal bar chart
- `renderRevenueByCategoryChart(data)` - Creates doughnut chart
- `renderOrdersTable(orders)` - Initializes DataTable for orders
- `renderProductsTable(products)` - Initializes DataTable for products

**Chart.js Configuration:**
- Responsive design
- Custom tooltips with formatted values
- Color-coded datasets
- Proper axis labels and formatting

**DataTables Configuration:**
- Custom column rendering
- Clickable links to detail pages
- Status badges with color coding
- Date formatting
- Currency formatting
- Star rating display
- Sorting and filtering

## Database Queries

### Key Queries Used:

1. **Order Statistics:**
```sql
SELECT COUNT(*), SUM(revenue), SUM(status counts)
FROM orders WHERE shop_id = ?
```

2. **Revenue by Date:**
```sql
SELECT DATE(o.date), SUM(oi.price * oi.quantity)
FROM orders o JOIN order_items oi
WHERE shop_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY DATE(o.date)
```

3. **Top Products:**
```sql
SELECT p.name, SUM(oi.quantity) as total_quantity
FROM products p JOIN order_items oi JOIN orders o
WHERE p.shop_id = ? AND o.status NOT IN ('Cancelled', 'Failed')
GROUP BY p.id ORDER BY total_quantity DESC LIMIT 5
```

4. **Revenue by Category:**
```sql
SELECT c.name, SUM(oi.price * oi.quantity)
FROM products p JOIN categories c JOIN order_items oi JOIN orders o
WHERE p.shop_id = ? GROUP BY c.id
```

## Usage

### Access the Dashboard
1. Login as a seller
2. Navigate to "Analysis" from the sidebar menu
3. URL: `/SHooad/public/seller/analysis`

### Switch Analysis Type
- Use the dropdown in the top-right corner
- Select "Orders Analysis" or "Products Analysis"
- Page will reload with selected data

### Interact with Data
- **Cards:** View key metrics at a glance
- **Charts:** Hover for detailed tooltips
- **Tables:** 
  - Search using the search box
  - Sort by clicking column headers
  - Paginate through results
  - Click IDs to view details

## Dependencies

### CSS Libraries:
- TailwindCSS (CDN) - Utility-first CSS framework
- DataTables CSS (v1.13.7) - Table styling
- FontAwesome (v6.4.0) - Icons

### JavaScript Libraries:
- jQuery (v3.7.1) - Required for DataTables
- Chart.js (latest) - Data visualization
- DataTables (v1.13.7) - Advanced table features

## Future Enhancements

Potential improvements:
1. Date range selector for custom period analysis
2. Export functionality (PDF, Excel)
3. Real-time updates using WebSocket
4. Comparison with previous periods
5. Sales forecasting
6. Customer segmentation analysis
7. Inventory alerts and recommendations
8. Performance benchmarks against similar shops

## Troubleshooting

### Common Issues:

1. **Charts not displaying:**
   - Check browser console for errors
   - Ensure Chart.js is loaded
   - Verify data format from backend

2. **Tables not working:**
   - Ensure jQuery is loaded before DataTables
   - Check data array structure
   - Verify column definitions match data keys

3. **No data showing:**
   - Verify shop_id is set in session
   - Check database has orders/products for the shop
   - Review browser console for API errors

4. **Incorrect calculations:**
   - Verify order status exclusions (Cancelled, Failed)
   - Check date range logic
   - Review aggregation queries

## Performance Considerations

- Queries use proper indexes (shop_id, order_id, product_id)
- Limited to last 30 days for date-based charts
- Top products limited to 5 items
- DataTables handles large datasets with pagination
- AJAX support for dynamic loading without page refresh

## Security

- Authentication required via `AuthMiddleware::checkSellerAuth()`
- Shop_id from session prevents cross-shop data access
- Prepared statements prevent SQL injection
- All queries scoped to authenticated seller's shop
