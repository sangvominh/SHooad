# Quickstart Guide: Seller Dashboard

**Feature**: Seller Dashboard  
**Date**: 2025-10-30  
**Audience**: Developers implementing this feature

## Overview

This quickstart guide provides a step-by-step implementation path for the Seller Dashboard feature. Follow these phases in order to build a working dashboard that meets all requirements.

---

## Prerequisites

Before starting implementation, ensure you have:

- ✅ XAMPP installed and running (Apache + MySQL + PHP 7.4+)
- ✅ MySQL database created (e.g., `shooad_db`)
- ✅ `.env` file created with database credentials
- ✅ TailwindCSS accessible (CDN or compiled)
- ✅ Basic MVC structure in place (`app/`, `public/`, `config/`)
- ✅ Authentication system functional (seller login/session management)

---

## Implementation Phases

### Phase 0: Database Setup (30 minutes)

**Goal**: Create database tables with proper schema and indexes.

1. **Create migration file**: `database/migrations/001_seller_dashboard.sql`

2. **Run migration**:
   ```bash
   mysql -u root -p shooad_db < database/migrations/001_seller_dashboard.sql
   ```

3. **Verify tables**:
   ```sql
   SHOW TABLES;
   DESCRIBE sellers;
   DESCRIBE products;
   DESCRIBE orders;
   ```

4. **Create sample data** (optional, for testing):
   ```sql
   INSERT INTO sellers (name, email, password_hash, account_status) 
   VALUES ('Test Seller', 'seller@test.com', '$2y$10$...', 'active');
   
   INSERT INTO products (seller_id, name, description, price, status)
   VALUES (1, 'Sample Product', 'Description', 29.99, 'active');
   ```

**Verification**: Query tables to confirm data inserted correctly.

---

### Phase 1: Core Classes (2 hours)

**Goal**: Build Model classes with database interaction methods.

#### 1.1 Create Seller Model

**File**: `app/Models/Seller.php`

```php
<?php

class Seller {
    private $db;
    private $id;
    private $name;
    private $email;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT id, name, email, account_status 
            FROM sellers 
            WHERE id = ? AND account_status = 'active'
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Additional methods as needed
}
```

#### 1.2 Create Product Model

**File**: `app/Models/Product.php`

**Key Methods**:
- `getRecentActiveProducts($sellerId, $limit = 20)` - Dashboard product list
- `pause($productId, $sellerId)` - Pause product
- `delete($productId, $sellerId, $confirmName)` - Soft delete with name confirmation
- `getStatistics($sellerId)` - Product counts

#### 1.3 Create Order Model

**File**: `app/Models/Order.php`

**Key Methods**:
- `getMonthlySalesCount($sellerId)` - Current month sales
- `getMonthlyRevenue($sellerId)` - Current month revenue

**Verification**: Test each model method individually with sample data.

---

### Phase 2: Controller Layer (1.5 hours)

**Goal**: Create controller to handle dashboard requests.

#### 2.1 Create Dashboard Controller

**File**: `app/Controllers/SellerDashboardController.php`

```php
<?php

class SellerDashboardController {
    private $db;
    private $sellerModel;
    private $productModel;
    private $orderModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->sellerModel = new Seller($db);
        $this->productModel = new Product($db);
        $this->orderModel = new Order($db);
    }
    
    public function index() {
        // Check authentication
        if (!isset($_SESSION['seller_id'])) {
            header('Location: /seller/login');
            exit;
        }
        
        $sellerId = $_SESSION['seller_id'];
        
        // Fetch data
        $seller = $this->sellerModel->findById($sellerId);
        $statistics = $this->getStatistics($sellerId);
        $recentProducts = $this->productModel->getRecentActiveProducts($sellerId, 20);
        
        // Render view
        require 'app/Views/seller/dashboard.php';
    }
    
    private function getStatistics($sellerId) {
        return [
            'total_products' => $this->productModel->getTotalCount($sellerId),
            'active_listings' => $this->productModel->getActiveCount($sellerId),
            'monthly_sales' => $this->orderModel->getMonthlySalesCount($sellerId),
            'monthly_revenue' => $this->orderModel->getMonthlyRevenue($sellerId)
        ];
    }
    
    public function pauseProduct($productId) {
        // Validate CSRF, ownership, then pause
        // Redirect with flash message
    }
    
    public function deleteProduct($productId) {
        // Validate CSRF, ownership, name confirmation, then delete
        // Redirect with flash message
    }
}
```

**Verification**: Test controller methods with var_dump() before creating views.

---

### Phase 3: View Layer (2 hours)

**Goal**: Create HTML templates with TailwindCSS styling.

#### 3.1 Create Dashboard View

**File**: `app/Views/seller/dashboard.php`

**Layout Structure**:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - SHooad</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include 'partials/sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Flash Messages -->
            <?php include 'partials/flash-messages.php'; ?>
            
            <!-- Statistics -->
            <?php include 'partials/statistics.php'; ?>
            
            <!-- Recent Products -->
            <?php include 'partials/recent-products.php'; ?>
        </main>
    </div>
    
    <script src="/js/dashboard.js"></script>
</body>
</html>
```

#### 3.2 Create Sidebar Partial

**File**: `app/Views/seller/partials/sidebar.php`

**Features**:
- Navigation links (Dashboard, Add Product, Product List, Orders, Account)
- Active state highlighting
- Mobile: Hidden by default with toggle button
- Desktop: Always visible

#### 3.3 Create Statistics Partial

**File**: `app/Views/seller/partials/statistics.php`

**Display**:
- Total Products: `<?= htmlspecialchars($statistics['total_products']) ?>`
- Active Listings: `<?= htmlspecialchars($statistics['active_listings']) ?>`
- Monthly Sales: `<?= htmlspecialchars($statistics['monthly_sales']) ?>`
- Monthly Revenue: `$<?= number_format($statistics['monthly_revenue'], 2) ?>`

#### 3.4 Create Products Partial

**File**: `app/Views/seller/partials/recent-products.php`

**Features**:
- Loop through `$recentProducts` array
- Display name, price, status for each product
- Action buttons: Edit, Pause, Delete
- Delete button triggers modal with confirmation

**Verification**: View dashboard in browser, verify layout responsive on mobile/desktop.

---

### Phase 4: JavaScript Interactions (1 hour)

**Goal**: Add minimal vanilla JavaScript for interactivity.

#### 4.1 Sidebar Toggle

**File**: `public/js/dashboard.js`

```javascript
// Sidebar toggle for mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-open');
        });
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('sidebar-open');
            }
        });
    }
});
```

#### 4.2 Delete Confirmation Modal

```javascript
// Delete confirmation with type-to-confirm
function showDeleteModal(productId, productName) {
    const modal = document.getElementById('delete-modal');
    const confirmInput = document.getElementById('delete-confirm-input');
    const confirmButton = document.getElementById('delete-confirm-button');
    const productNameDisplay = document.getElementById('product-name-display');
    
    // Set product info
    productNameDisplay.textContent = productName;
    
    // Clear previous input
    confirmInput.value = '';
    confirmButton.disabled = true;
    
    // Enable button only when name matches
    confirmInput.addEventListener('input', function() {
        confirmButton.disabled = (this.value !== productName);
    });
    
    // Set form action
    document.getElementById('delete-form').action = `/seller/dashboard/products/${productId}/delete`;
    
    // Show modal
    modal.classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
```

**Verification**: Test sidebar toggle, delete modal functionality.

---

### Phase 5: Routing & Integration (30 minutes)

**Goal**: Wire up routes to controller methods.

#### 5.1 Update Router

**File**: `app/Core/Router.php` or `public/index.php`

```php
// Dashboard routes
$router->get('/seller/dashboard', 'SellerDashboardController@index');
$router->post('/seller/dashboard/products/{id}/pause', 'SellerDashboardController@pauseProduct');
$router->post('/seller/dashboard/products/{id}/delete', 'SellerDashboardController@deleteProduct');
```

#### 5.2 Test End-to-End

1. Navigate to `/seller/dashboard`
2. Verify statistics display correctly
3. Verify products list shows recent items
4. Test pause action
5. Test delete action with name confirmation
6. Test mobile responsive behavior

**Verification**: Complete user flow from login to dashboard actions.

---

### Phase 6: Security Hardening (1 hour)

**Goal**: Ensure all security requirements met.

#### 6.1 CSRF Protection

Add CSRF token generation and validation to all POST endpoints.

#### 6.2 Input Sanitization

Review all user inputs:
- Product IDs: `filter_var($id, FILTER_VALIDATE_INT)`
- Product names: `htmlspecialchars(strip_tags($input))`

#### 6.3 Output Escaping

Verify all view templates use `htmlspecialchars()`:
```php
<?= htmlspecialchars($data, ENT_QUOTES, 'UTF-8') ?>
```

#### 6.4 Authorization Checks

Ensure every product action verifies seller ownership:
```php
if ($product['seller_id'] !== $_SESSION['seller_id']) {
    throw new UnauthorizedException();
}
```

**Verification**: Attempt to access/modify other seller's products, verify blocked.

---

### Phase 7: Error Handling (30 minutes)

**Goal**: Graceful error handling with user-friendly messages.

#### 7.1 Database Errors

Wrap database calls in try-catch:
```php
try {
    $products = $this->productModel->getRecentActiveProducts($sellerId);
} catch (PDOException $e) {
    error_log("Dashboard DB error: " . $e->getMessage());
    $this->showError("Unable to load dashboard. Please try again later.");
}
```

#### 7.2 Validation Errors

Return specific messages for validation failures:
- "Product name does not match. Deletion cancelled."
- "Invalid product ID."
- "You don't have permission to modify this product."

**Verification**: Trigger errors intentionally, verify appropriate messages shown.

---

### Phase 8: Performance Optimization (30 minutes)

**Goal**: Ensure dashboard loads within 2 seconds.

#### 8.1 Database Indexing

Verify indexes exist:
```sql
SHOW INDEX FROM products;
SHOW INDEX FROM orders;
```

#### 8.2 Query Optimization

Use EXPLAIN to analyze queries:
```sql
EXPLAIN SELECT * FROM products WHERE seller_id = 1 AND status = 'active' ORDER BY modified_at DESC LIMIT 20;
```

#### 8.3 Page Weight

Check total page size (target <500KB):
- Browser DevTools → Network tab
- Verify TailwindCSS CDN cached

**Verification**: Use browser DevTools to measure load time and page weight.

---

## Testing Checklist

### Functional Testing

- [ ] Dashboard loads within 2 seconds
- [ ] Statistics display correct counts and revenue
- [ ] Recent products show 10-20 items, sorted by modified date
- [ ] "View All" link appears when >20 products exist
- [ ] Pause button changes product status to paused
- [ ] Delete requires typing exact product name
- [ ] Delete button disabled until name matches
- [ ] Statistics update after pause/delete actions
- [ ] Flash messages display for success/error

### Responsive Testing

- [ ] Desktop (>1024px): Sidebar always visible
- [ ] Tablet (768px-1024px): Sidebar toggleable
- [ ] Mobile (<768px): Sidebar hidden, toggle button visible
- [ ] Layout no horizontal scroll on any device
- [ ] All buttons and links tappable on mobile

### Security Testing

- [ ] Cannot access dashboard without authentication
- [ ] Cannot pause/delete another seller's products
- [ ] CSRF token validated on all POST requests
- [ ] SQL injection attempts blocked (prepared statements)
- [ ] XSS attempts blocked (output escaping)
- [ ] Error messages don't expose technical details

### Edge Cases

- [ ] Seller with 0 products: Empty state message shown
- [ ] Seller with 100+ products: Only 20 shown, link to full list
- [ ] New seller with no sales: Revenue shows $0.00
- [ ] Month boundary: Statistics reset correctly on 1st of month
- [ ] Long product names: Truncated with ellipsis
- [ ] Database connection failure: Generic error message

---

## Troubleshooting

### Dashboard won't load
- Check session is active: `var_dump($_SESSION);`
- Verify seller_id exists in database
- Check PHP error log: `tail -f /xampp/apache/logs/error_log`

### Statistics show incorrect values
- Verify date filters in SQL queries
- Check order_status filter (only 'completed' orders)
- Run queries manually in MySQL to debug

### Products not displaying
- Check product status = 'active'
- Verify seller_id matches session
- Check modified_at values exist (not NULL)

### Mobile sidebar not working
- Verify JavaScript loaded (check browser console)
- Check element IDs match JavaScript selectors
- Test in browser DevTools device emulation

---

## Performance Benchmarks

**Target Metrics**:
- Dashboard load time: <2 seconds
- Statistics query time: <200ms
- Product list query time: <100ms
- Page weight: <500KB (excluding images)

**Monitoring**:
```php
$start = microtime(true);
// ... code execution ...
$end = microtime(true);
error_log("Dashboard load time: " . ($end - $start) . " seconds");
```

---

## Next Steps

After completing seller dashboard implementation:

1. ✅ Mark feature complete on branch `001-seller-dashboard`
2. 🔄 Create pull request with thorough description
3. 👀 Request code review focusing on security and performance
4. 🧪 Manual testing by QA or stakeholders
5. 🚀 Merge to main branch and deploy

**Follow-up Features** (separate branches):
- Add Product form
- Product List page with advanced filtering
- Orders management
- Account settings

---

## Resources

- **Constitution**: `.specify/memory/constitution.md`
- **Specification**: `specs/001-seller-dashboard/spec.md`
- **Data Model**: `specs/001-seller-dashboard/data-model.md`
- **API Contracts**: `specs/001-seller-dashboard/contracts/api-endpoints.md`
- **TailwindCSS Docs**: https://tailwindcss.com/docs
- **PHP PDO Docs**: https://www.php.net/manual/en/book.pdo.php

---

## Summary

This quickstart provides a clear implementation path from database setup to production-ready dashboard. Follow phases sequentially, verify each phase before proceeding, and use the testing checklist to ensure quality. Estimated total implementation time: 8-10 hours for an experienced PHP developer.
