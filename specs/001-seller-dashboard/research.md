# Research: Seller Dashboard

**Feature**: Seller Dashboard  
**Date**: 2025-10-30  
**Phase**: 0 - Technical Research & Decision Making

## Overview

This document captures technical research and architectural decisions for the Seller Dashboard feature. All technical unknowns from the specification have been researched and resolved to enable implementation planning.

## Technology Stack Decisions

### 1. TailwindCSS Integration Method

**Decision**: Use TailwindCSS via CDN for initial development, with option to compile later

**Rationale**:
- CDN approach provides fastest setup for XAMPP environment
- No Node.js build process required initially
- TailwindCSS 3.x CDN supports JIT (Just-In-Time) compilation in browser
- Can transition to CLI compilation later for production optimization
- Aligns with "no external frameworks" principle (TailwindCSS is CSS utility framework, not JS framework)

**Alternatives Considered**:
- **TailwindCSS CLI compilation**: Requires Node.js setup, adds build complexity for XAMPP environment
- **Custom CSS**: Rejected per constitution principle IV (TailwindCSS only)
- **Pre-compiled TailwindCSS**: Larger file size, no customization benefits

**Implementation**:
```html
<script src="https://cdn.tailwindcss.com"></script>
```

### 2. Session Management & Authentication

**Decision**: PHP native sessions with secure cookie configuration

**Rationale**:
- Built into PHP, no external dependencies
- Sufficient for seller authentication requirements
- Supports secure cookie flags (HttpOnly, Secure, SameSite)
- Session data stored server-side, only session ID in cookie

**Configuration**:
```php
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true, // HTTPS only in production
    'cookie_samesite' => 'Strict',
    'use_strict_mode' => true
]);
```

**Alternatives Considered**:
- **JWT tokens**: Overkill for server-rendered application
- **OAuth2**: Not required for seller authentication (assumes existing auth system)

### 3. Database Query Patterns

**Decision**: PDO with prepared statements, no ORM

**Rationale**:
- Prepared statements prevent SQL injection (constitution requirement)
- PDO provides consistent API across database drivers
- Direct SQL gives full control and transparency
- No ORM overhead or learning curve
- Aligns with "clean architecture" principle

**Pattern Example**:
```php
$stmt = $pdo->prepare("SELECT * FROM products WHERE seller_id = ? AND status = ? ORDER BY modified_date DESC LIMIT ?");
$stmt->execute([$sellerId, 'active', 20]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

**Alternatives Considered**:
- **Eloquent ORM**: Adds Laravel dependency, violates constitution
- **Doctrine ORM**: Too heavy for project needs
- **Raw queries**: SQL injection risk, rejected for security

### 4. Input Sanitization Strategy

**Decision**: Multi-layer sanitization with context-appropriate functions

**Rationale**:
- Different contexts require different sanitization (HTML, SQL, JavaScript)
- PHP native functions sufficient for requirements
- Validation first, then sanitization, then escaping on output

**Implementation Layers**:
1. **Input Validation**: `filter_input()`, `filter_var()` with appropriate filters
2. **Data Sanitization**: `htmlspecialchars()`, `strip_tags()` before storage
3. **Output Escaping**: `htmlspecialchars($data, ENT_QUOTES, 'UTF-8')` in views
4. **SQL Protection**: PDO prepared statements with parameter binding

**Example**:
```php
// Input validation
$productName = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_STRING);

// Additional validation
if (strlen($productName) < 3 || strlen($productName) > 255) {
    throw new ValidationException('Invalid product name length');
}

// Output escaping in view
echo htmlspecialchars($productName, ENT_QUOTES, 'UTF-8');
```

### 5. Mobile Sidebar Implementation

**Decision**: CSS-only toggle with vanilla JavaScript for state management

**Rationale**:
- No jQuery or framework needed (constitution requirement)
- CSS transforms for smooth animation
- JavaScript only toggles CSS class
- Accessible via keyboard (ESC to close)

**Implementation Approach**:
```javascript
// public/js/dashboard.js
document.getElementById('sidebar-toggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('sidebar-open');
});

// Close on outside click
document.addEventListener('click', function(e) {
    if (!e.target.closest('#sidebar') && !e.target.closest('#sidebar-toggle')) {
        document.getElementById('sidebar').classList.remove('sidebar-open');
    }
});
```

**CSS**:
```css
/* TailwindCSS utility classes */
.sidebar { @apply fixed inset-y-0 left-0 -translate-x-full transition-transform lg:translate-x-0; }
.sidebar-open { @apply translate-x-0; }
```

### 6. Delete Confirmation with Type-to-Confirm

**Decision**: Custom modal with vanilla JavaScript validation

**Rationale**:
- Type-to-confirm provides extra safety against accidental deletion
- Modal overlay prevents interaction with other elements
- Vanilla JavaScript implementation aligns with constitution
- Better UX than browser confirm() dialog

**Implementation Flow**:
1. User clicks "Delete" button
2. Modal appears with product name displayed
3. User must type exact product name in input field
4. "Confirm Delete" button only enabled when input matches
5. Form submits DELETE request on confirmation

### 7. Statistics Calculation Strategy

**Decision**: Real-time calculation on each page load, no caching

**Rationale**:
- Dashboard requires up-to-date statistics
- Seller count is low enough that query performance acceptable
- Monthly scope limits data volume (only current month orders)
- Indexing on date fields ensures fast queries
- Future optimization: add caching if performance degrades

**Query Pattern**:
```sql
-- Total products for seller
SELECT COUNT(*) FROM products WHERE seller_id = ?

-- Active listings
SELECT COUNT(*) FROM products WHERE seller_id = ? AND status = 'active'

-- Monthly sales count
SELECT COUNT(*) FROM orders 
WHERE seller_id = ? 
  AND status = 'completed'
  AND MONTH(order_date) = MONTH(CURRENT_DATE)
  AND YEAR(order_date) = YEAR(CURRENT_DATE)

-- Monthly revenue
SELECT SUM(total_amount) FROM orders
WHERE seller_id = ?
  AND status = 'completed'
  AND MONTH(order_date) = MONTH(CURRENT_DATE)
  AND YEAR(order_date) = YEAR(CURRENT_DATE)
```

### 8. Error Handling Pattern

**Decision**: Try-catch with user-friendly messages, server-side logging

**Rationale**:
- Never expose technical details to users (security requirement)
- Log full error details server-side for debugging
- Generic error messages for users with contact support option
- Specific validation errors shown inline

**Pattern**:
```php
try {
    // Business logic
} catch (PDOException $e) {
    // Log full error
    error_log("Database error: " . $e->getMessage());
    
    // Show generic message to user
    $errorMessage = "Unable to load dashboard. Please try again later.";
} catch (ValidationException $e) {
    // Show specific validation error
    $errorMessage = $e->getMessage();
}
```

## Best Practices Applied

### PHP MVC Architecture
- **Models**: Data access and business logic only, no presentation
- **Views**: Presentation only, minimal PHP (loops, conditionals, escaping)
- **Controllers**: Coordination layer, handles request/response, delegates to models
- **Single Responsibility**: Each class has one clear purpose

### Security Best Practices
- **Prepared Statements**: All queries use parameter binding
- **Input Validation**: Whitelist validation on all user input
- **Output Escaping**: `htmlspecialchars()` on all dynamic content
- **CSRF Protection**: Token validation on state-changing operations
- **Session Security**: Secure cookie configuration, regenerate on privilege escalation

### Performance Best Practices
- **Indexed Columns**: seller_id, status, order_date, modified_date
- **Limit Queries**: Dashboard shows max 20 products
- **Efficient JOINs**: Avoid N+1 queries, use JOIN where needed
- **Pagination**: "View All" link prevents loading excessive data

### Accessibility Best Practices
- **Semantic HTML**: Use proper heading hierarchy, nav elements
- **Keyboard Navigation**: Sidebar toggle, form controls accessible via keyboard
- **Screen Reader Support**: ARIA labels on icon-only buttons
- **Focus Management**: Trap focus in modal, restore on close

## Integration Points

### Existing Systems (Assumed)
- **Authentication System**: Seller login/logout, session management
- **Product CRUD**: Create, Read, Update, Delete operations for products
- **Order Management**: Order processing and status tracking
- **Database Schema**: Existing tables for sellers, products, orders

### New Components Required
- **SellerDashboardController**: New controller for dashboard route
- **Dashboard View**: New view template with layout
- **Statistics Calculations**: New methods in Seller/Product/Order models
- **Routes**: Add `/seller/dashboard` route

## Risk Mitigation

### Performance Risks
- **Risk**: Statistics queries slow with large product/order counts
- **Mitigation**: Database indexing, query optimization, future caching if needed
- **Monitoring**: Log query execution times

### Security Risks
- **Risk**: XSS via product names or descriptions
- **Mitigation**: Strict output escaping in all views
- **Monitoring**: Regular security audits of user input handling

### Usability Risks
- **Risk**: Type-to-confirm deletion too cumbersome
- **Mitigation**: Clear instructions, exact match indicator, escape to cancel
- **Monitoring**: User feedback collection

## Open Questions & Future Enhancements

### Deferred to Implementation
- **Cache Strategy**: Implement if statistics queries become performance bottleneck
- **Concurrent Editing**: Handle via optimistic locking if needed (low priority)
- **Historical Statistics**: Add year-over-year comparison (out of scope)

### Future Enhancements (Out of Current Scope)
- **Real-time Updates**: WebSocket for live statistics updates
- **Advanced Analytics**: Charts, graphs, trend analysis
- **Bulk Operations**: Multi-select products for batch actions
- **Product Search**: Filter/search in dashboard view

## Conclusion

All technical unknowns have been researched and resolved. The implementation approach aligns with all constitution principles:
- ✅ Clean MVC architecture
- ✅ Single responsibility classes
- ✅ No global code
- ✅ TailwindCSS only for styling
- ✅ No external JavaScript frameworks
- ✅ No testing requirements
- ✅ Secure coding standards
- ✅ Environment-based configuration

Ready to proceed to Phase 1: Design & Contracts.
