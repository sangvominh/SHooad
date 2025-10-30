# API Contracts: Seller Dashboard

**Feature**: Seller Dashboard  
**Date**: 2025-10-30  
**Phase**: 1 - API Design

## Overview

This document defines the HTTP endpoints, request/response formats, and error handling for the Seller Dashboard feature. The application uses server-rendered PHP pages with minimal AJAX for specific actions.

## Authentication

All endpoints require seller authentication via PHP sessions.

**Session Requirements**:
- Valid PHP session with `$_SESSION['seller_id']` set
- Session must not be expired
- Seller account must have `status = 'active'`

**Unauthorized Access**:
- Redirect to `/seller/login` with message
- HTTP Status: 302 (Redirect)

---

## Endpoints

### 1. GET /seller/dashboard

**Purpose**: Display seller dashboard with statistics and recent products.

**Authentication**: Required (seller session)

**Request**:
```http
GET /seller/dashboard HTTP/1.1
Host: localhost
Cookie: PHPSESSID=abc123...
```

**Response**: HTML page (server-rendered)

**Response Data** (passed to view template):
```php
[
    'seller' => [
        'id' => 1,
        'name' => 'John\'s Store',
        'email' => 'john@example.com'
    ],
    'statistics' => [
        'total_products' => 45,
        'active_listings' => 38,
        'monthly_sales' => 12,
        'monthly_revenue' => 1234.56
    ],
    'recent_products' => [
        [
            'id' => 10,
            'name' => 'Product Name',
            'price' => 29.99,
            'status' => 'active',
            'modified_at' => '2025-10-30 14:30:00'
        ],
        // ... up to 20 products
    ],
    'errors' => [],
    'success_message' => null
]
```

**Status Codes**:
- `200 OK`: Dashboard rendered successfully
- `302 Found`: Not authenticated, redirect to login
- `500 Internal Server Error`: Database error

**Performance**:
- Target load time: <2 seconds
- Database queries: 5 (statistics) + 1 (products) = 6 total

---

### 2. POST /seller/dashboard/products/{id}/pause

**Purpose**: Pause an active product (remove from customer listings).

**Authentication**: Required (seller session, must own product)

**Request**:
```http
POST /seller/dashboard/products/123/pause HTTP/1.1
Host: localhost
Cookie: PHPSESSID=abc123...
Content-Type: application/x-www-form-urlencoded

csrf_token=xyz789...
```

**Request Body**:
- `csrf_token` (required): CSRF protection token from session

**Success Response** (Redirect):
```http
HTTP/1.1 302 Found
Location: /seller/dashboard
Set-Cookie: flash_message=Product paused successfully
```

**Error Response** (Redirect with error):
```http
HTTP/1.1 302 Found
Location: /seller/dashboard
Set-Cookie: flash_error=Failed to pause product
```

**Status Codes**:
- `302 Found`: Redirect to dashboard with flash message
- `403 Forbidden`: Product does not belong to seller
- `404 Not Found`: Product ID does not exist
- `400 Bad Request`: Invalid CSRF token or product already paused

**Side Effects**:
- Updates `products.status` to 'paused'
- Updates `products.modified_at` to current timestamp
- Statistics on dashboard will reflect change

---

### 3. POST /seller/dashboard/products/{id}/delete

**Purpose**: Soft delete a product (mark as deleted).

**Authentication**: Required (seller session, must own product)

**Request**:
```http
POST /seller/dashboard/products/456/delete HTTP/1.1
Host: localhost
Cookie: PHPSESSID=abc123...
Content-Type: application/x-www-form-urlencoded

csrf_token=xyz789...&confirm_name=Product+Name
```

**Request Body**:
- `csrf_token` (required): CSRF protection token
- `confirm_name` (required): Exact product name for confirmation

**Validation**:
- `confirm_name` must exactly match product name (case-sensitive)
- Product must belong to authenticated seller
- Product must have status 'active' or 'paused'

**Success Response** (Redirect):
```http
HTTP/1.1 302 Found
Location: /seller/dashboard
Set-Cookie: flash_message=Product deleted successfully
```

**Error Response** (Redirect with error):
```http
HTTP/1.1 302 Found
Location: /seller/dashboard
Set-Cookie: flash_error=Product name does not match. Deletion cancelled.
```

**Status Codes**:
- `302 Found`: Redirect to dashboard with flash message
- `403 Forbidden`: Product does not belong to seller
- `404 Not Found`: Product ID does not exist
- `400 Bad Request`: Invalid CSRF token or name mismatch

**Side Effects**:
- Updates `products.status` to 'deleted'
- Updates `products.modified_at` to current timestamp
- Product removed from dashboard display
- Statistics updated to reflect deletion

---

### 4. GET /seller/dashboard/products/{id}/edit

**Purpose**: Navigate to product edit form (out of scope for dashboard, but linked).

**Authentication**: Required (seller session, must own product)

**Request**:
```http
GET /seller/dashboard/products/789/edit HTTP/1.1
Host: localhost
Cookie: PHPSESSID=abc123...
```

**Response**: HTML page with pre-filled edit form

**Status Codes**:
- `200 OK`: Edit form rendered
- `302 Found`: Not authenticated, redirect to login
- `403 Forbidden`: Product does not belong to seller
- `404 Not Found`: Product ID does not exist

**Note**: Edit form implementation is out of scope for seller dashboard feature but endpoint exists for navigation.

---

## CSRF Protection

All state-changing operations (POST, PUT, DELETE) require CSRF token validation.

**Token Generation**:
```php
// In controller, add to session
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
```

**Token Validation**:
```php
// In controller, validate submitted token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    throw new SecurityException('Invalid CSRF token');
}
```

**Token Inclusion in Forms**:
```html
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
```

---

## Error Handling

### Flash Messages

**Success Messages**:
Stored in session, displayed once on next page load, then cleared.

```php
$_SESSION['flash_message'] = 'Product paused successfully';
```

**Error Messages**:
Stored in session, displayed once on next page load, then cleared.

```php
$_SESSION['flash_error'] = 'Failed to delete product. Please try again.';
```

**Display in View**:
```php
<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>
```

### Error Response Format

**User-Facing Errors**:
Generic messages, no technical details exposed.

**Examples**:
- "Unable to load dashboard. Please try again later."
- "Product not found or you don't have permission to access it."
- "Product name does not match. Deletion cancelled."

**Server-Side Logging**:
```php
error_log("Dashboard error for seller {$sellerId}: " . $exception->getMessage());
```

---

## Request/Response Flow

### Dashboard Page Load

```
Client Request
    ↓
Router (index.php)
    ↓
SellerDashboardController::index()
    ↓
├─ Check authentication (session)
├─ Fetch statistics (4 queries)
├─ Fetch recent products (1 query)
└─ Render view (dashboard.php)
    ↓
HTML Response
```

### Product Action (Pause/Delete)

```
Client Request (POST)
    ↓
Router (index.php)
    ↓
SellerDashboardController::pauseProduct()
    ↓
├─ Check authentication (session)
├─ Validate CSRF token
├─ Validate ownership
├─ Update product status
├─ Set flash message
└─ Redirect to dashboard
    ↓
302 Redirect Response
```

---

## Data Validation Rules

### Product ID
- **Type**: Integer
- **Validation**: Must be positive integer, must exist in database
- **Sanitization**: `filter_var($id, FILTER_VALIDATE_INT)`

### Product Name (for delete confirmation)
- **Type**: String
- **Validation**: Must exactly match database value (case-sensitive)
- **Sanitization**: `htmlspecialchars(trim($input))`
- **Max Length**: 255 characters

### CSRF Token
- **Type**: String (hex)
- **Validation**: Must match session token exactly
- **Length**: 64 characters (32 bytes hex-encoded)

---

## Performance Considerations

### Database Query Optimization
- Use prepared statements (security + performance)
- Indexed columns for WHERE clauses
- Limited result sets (LIMIT 20 for products)
- Avoid N+1 queries (no JOIN needed for dashboard)

### Page Load Optimization
- TailwindCSS via CDN (cached by browser)
- Minimal inline JavaScript (<5KB)
- No external API calls on page load
- Database connection pooling (handled by PDO)

### Caching Strategy (Future)
If performance degrades:
- Cache statistics for 30-60 seconds
- Cache recent products for 60 seconds
- Invalidate cache on product actions

---

## Security Considerations

### Input Validation
- All inputs validated before database interaction
- Prepared statements for SQL injection prevention
- HTML escaping for XSS prevention

### Output Encoding
```php
<?= htmlspecialchars($data, ENT_QUOTES, 'UTF-8') ?>
```

### Authorization
- Every product action verifies `seller_id` matches session
- No product IDs exposed in URLs that could be guessed
- CSRF protection on all state-changing operations

### Session Security
```php
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true, // Production only
    'cookie_samesite' => 'Strict'
]);
```

---

## Testing Scenarios (Manual)

### Happy Path
1. Load dashboard → verify statistics and products display
2. Click "Pause" on product → verify status changes
3. Click "Delete" on product → verify confirmation, type name, confirm
4. Verify statistics update after actions

### Error Scenarios
1. Invalid CSRF token → error message displayed
2. Wrong product name in delete confirmation → deletion prevented
3. Attempt to pause/delete another seller's product → 403 Forbidden
4. Database connection failure → generic error message

### Edge Cases
1. Seller with 0 products → empty state message shown
2. Seller with 100+ products → only first 20 shown, "View All" link present
3. Mobile view → sidebar hidden, toggle button functional

---

## Summary

The API design follows RESTful principles with server-rendered responses. All endpoints enforce authentication, authorization, CSRF protection, and input validation. Error handling provides user-friendly messages without exposing technical details. Performance is optimized through indexed queries and limited result sets.

**Contract Compliance**:
- ✅ All endpoints authenticated
- ✅ CSRF protection on state-changing operations
- ✅ Input validation on all user data
- ✅ Output escaping on all dynamic content
- ✅ Error handling with logging
- ✅ Performance targets defined
