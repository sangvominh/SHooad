# Customer Code Refactoring Summary

## Date: November 15, 2025

## Overview
Đã refactor toàn bộ customer code để:
1. Thay đổi terminology từ "user" sang "customer" cho consistency
2. Tăng tính tái sử dụng code với helpers và layout templates
3. Cải thiện maintainability và readability

## Major Changes

### 1. Renamed Folders & Files

#### Views
- `app/Views/user/` → `app/Views/customer/`
  - All views now in customer folder for clarity

#### Assets
- `public/assets/js/user/` → `public/assets/js/customer/`
  - JavaScript files updated to customer folder

#### Routes
- Created `app/Routes/customer.php` (copy of user.php)
- Kept `user.php` for backward compatibility

### 2. New Files Created

#### `app/Views/customer/layout.php`
Base layout template to reduce HTML boilerplate duplication:
```php
<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Common head content -->
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <main><?php echo $content; ?></main>
    <?php include 'partials/footer.php'; ?>
    <!-- Common scripts -->
</body>
</html>
```

#### `app/Helpers/CustomerHelper.php`
Common helper functions for better code reusability:
- `renderPartial()` - Render partial views
- `renderLayout()` - Render layout with content
- `getCustomerSession()` - Get customer session data
- `isCustomerLoggedIn()` - Check login status
- `redirect()` - Redirect helper
- `baseUrl()`, `customerUrl()`, `assetUrl()` - URL helpers
- `e()` - HTML escape helper
- `formatPrice()`, `formatDate()` - Formatting helpers
- `getProductImageUrl()` - Product image helper
- `getDiscountPercentage()`, `isOnSale()` - Product helpers
- `getColorCode()` - Color mapping helper
- `parseCSV()` - Parse comma-separated values
- `getPaginationData()` - Pagination helper
- `buildQueryString()` - Query string builder
- `getFlashMessage()`, `setFlashMessage()` - Flash message helpers

### 3. Updated Path References

All internal paths changed from `/user/` to `/customer/`:

**Before:**
```php
/SHooad/public/user/login
/SHooad/app/Views/user/cart.php
/SHooad/public/assets/js/user/dropdown.js
```

**After:**
```php
/SHooad/public/customer/login
/SHooad/public/customer/cart
/SHooad/public/assets/js/customer/dropdown.js
```

### 4. Updated Files

#### Controllers
- `app/Controllers/UserController.php`
  - All view includes updated to use `Views/customer/`
  - All redirects updated to use `/customer/` paths

#### Core
- `app/Core/Router.php`
  - Added 'customer' route support
  - Kept 'user' for backward compatibility
  - Home route now uses controller instead of direct view

#### Routes
- `app/Routes/customer.php` (new)
- `app/Routes/user.php` (kept for compatibility)
  - Controller variable renamed to `$customerController`
  - Added `checkout` and `profile` route placeholders

#### Middleware
- `app/middleware/AuthMiddleware.php`
  - `checkUserAuth()` redirect updated to `/customer/login`

#### Views - Main Pages
All updated to use customer paths:
- `app/Views/customer/home.php`
- `app/Views/customer/cart.php`
- `app/Views/customer/products.php`
- `app/Views/customer/product-detail.php`
- `app/Views/customer/login.php`
- `app/Views/customer/register.php`
- `app/Views/customer/logout.php`

#### Views - Partials
All updated to use customer paths:
- `app/Views/customer/partials/header.php`
- `app/Views/customer/partials/navigation.php`
- `app/Views/customer/partials/product-card.php`
- `app/Views/customer/partials/order-summary.php`
- All other partials...

### 5. URL Structure Changes

#### Old URLs (still work for compatibility)
```
/SHooad/public/user
/SHooad/public/user/login
/SHooad/public/user/register
/SHooad/public/user/cart
/SHooad/public/user/products
/SHooad/public/user/product-detail?id=1
```

#### New URLs (recommended)
```
/SHooad/public/customer
/SHooad/public/customer/login
/SHooad/public/customer/register
/SHooad/public/customer/cart
/SHooad/public/customer/products
/SHooad/public/customer/product-detail?id=1
/SHooad/public/customer/checkout (new)
/SHooad/public/customer/profile (new)
```

## Benefits

### 1. Improved Clarity
- "Customer" terminology is more specific than "user"
- Distinguishes from "seller" and "admin" roles clearly
- Consistent naming throughout codebase

### 2. Better Code Reusability
- **Layout template** eliminates repeated HTML boilerplate
- **Helper functions** centralize common operations
- Easier to maintain and update common functionality

### 3. Enhanced Maintainability
- Single source of truth for common functions
- Easier to update paths and URLs
- Better organization of customer-facing code

### 4. Backward Compatibility
- Old `/user/` URLs still work
- Gradual migration possible
- No breaking changes for existing links

## Usage Examples

### Using Layout Template
```php
<?php
ob_start();
?>
<h1>Welcome</h1>
<p>Content here</p>
<?php
$content = ob_get_clean();
$pageTitle = 'Home';
$bodyClass = 'bg-white';
include 'layout.php';
?>
```

### Using Helper Functions
```php
<?php
require_once __DIR__ . '/../../Helpers/CustomerHelper.php';

// URLs
echo customerUrl('cart'); // /SHooad/public/customer/cart
echo assetUrl('images/logo.png'); // /SHooad/public/assets/images/logo.png

// Check login
if (isCustomerLoggedIn()) {
    $session = getCustomerSession();
    echo e($session['customer_name']);
}

// Format data
echo formatPrice(99.99); // $99.99
echo formatDate('2025-11-15'); // formatted date

// Product helpers
$discount = getDiscountPercentage(100, 80); // 20
$imageUrl = getProductImageUrl($product['image']);
$colorCode = getColorCode('Red'); // #FF0000

// Flash messages
setFlashMessage('success', 'Login successful!');
$message = getFlashMessage('success');
?>
```

## Testing Checklist

- [x] All views render correctly
- [x] All paths updated to customer
- [x] JavaScript files load properly
- [x] Navigation links work
- [x] Login/logout functionality
- [x] Cart operations
- [x] Product detail pages
- [x] Form submissions
- [x] Backward compatibility with /user/ URLs

## Next Steps (Optional)

1. **Refactor views to use layout template**
   - Convert existing views to use `layout.php`
   - Reduce HTML duplication further

2. **Implement missing routes**
   - Add checkout functionality
   - Add customer profile page

3. **Add more helpers as needed**
   - Order helpers
   - Review helpers
   - Wishlist helpers

4. **Consider creating ViewModels**
   - Separate data preparation from views
   - Better separation of concerns

5. **Add customer-specific middleware**
   - Rate limiting
   - Activity tracking
   - Preferences management

## Migration Notes

For developers:
1. Update any hardcoded `/user/` paths to `/customer/`
2. Use helper functions instead of direct operations
3. Consider using layout template for new views
4. Keep `user.php` route for now (backward compatibility)
5. Test all customer-facing functionality after update

## Files Modified Summary

**Total files changed: 20+**

- 1 Controller
- 1 Router
- 2 Routes files
- 1 Middleware
- 7+ View files
- 10+ Partial files
- 1 New Helper file
- 1 New Layout file

## Conclusion

Codebase giờ đã:
✅ Consistent terminology (customer thay vì user)
✅ Better code organization
✅ Higher reusability with helpers
✅ Easier to maintain and extend
✅ Backward compatible
✅ More professional structure
