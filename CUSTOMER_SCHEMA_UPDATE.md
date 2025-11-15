# Customer Code Schema Update

## Summary
Đã cập nhật toàn bộ customer code để sử dụng đúng schema database với bảng `customers` và `cart_items`.

## Changes Made

### 1. Model Layer
**Renamed: `User.php` → `Customer.php`**
- Changed class name from `User` to `Customer`
- Updated all queries to use `customers` table instead of `users`
- Added status check (`status = 'active'`) in queries
- Added helper methods: `findById()`, `findByEmail()`

### 2. Session Variables
**Changed from:**
- `$_SESSION['user_id']` 
- `$_SESSION['user']`
- `$_SESSION['user_name']`

**Changed to:**
- `$_SESSION['customer_id']`
- `$_SESSION['customer_email']`
- `$_SESSION['customer_name']`

### 3. Services Layer

#### `AuthUserService.php`
- Changed from `$userModel` to `$customerModel`
- Renamed `setUserSession()` to `setCustomerSession()`
- Renamed `getUserSession()` to `getCustomerSession()`
- Updated all session variables to use `customer_*` naming

#### `UserPageService.php`
- No changes needed (already using correct schema)

### 4. Middleware
**`AuthMiddleware.php`**
- Updated `checkUserAuth()` to check `$_SESSION['customer_id']` instead of `$_SESSION['user_id']`

### 5. Views

#### `partials/header.php`
- Changed `isset($_SESSION['user'])` to `isset($_SESSION['customer_id'])`
- Updated cart query to use correct schema:
  - Get `cart.id` from `carts` table by `customer_id`
  - Then query `cart_items` by `cart_id`

#### `partials/cart-items.php`
- Updated query to use proper joins:
  ```sql
  FROM carts c
  JOIN cart_items ci ON ci.cart_id = c.id
  WHERE c.customer_id = ?
  ```
- Changed `cart_id` to `cart_item_id` in result array

#### `partials/cart-item.php`
- Updated all `data-item-id` attributes to use `cart_item_id`

#### `logout.php`
- Changed check from `$_SESSION['user']` to `$_SESSION['customer_id']`

### 6. Cart Routes

#### `add-to-cart.php`
- Changed authentication check to use `customer_id`
- Updated logic to properly use cart schema:
  1. Get or create cart by `customer_id`
  2. Check if item exists in `cart_items`
  3. Update quantity or insert new item

#### `remove-from-cart.php`
- Changed to use `customer_id`
- Updated to delete from `cart_items` table
- Verify ownership through join with `carts` table

#### `update-cart.php`
- Changed to use `customer_id`
- Updated to modify `cart_items` table
- Stock validation using `product_id` from cart item

#### `toggle-cart-select.php`
- Changed to use `customer_id`
- Added note that `selected` column doesn't exist in schema
- Returns success but doesn't update (feature not implemented in schema)

## Database Schema (Correct)

```sql
-- Customers table
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('active','inactive','banned') DEFAULT 'active'
);

-- Carts table (one per customer)
CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- Cart items table (many items per cart)
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1 CHECK (quantity > 0),
    color VARCHAR(100) NOT NULL,
    size VARCHAR(100) NOT NULL,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

## Files Modified

1. **Models:**
   - `User.php` → `Customer.php` (renamed)

2. **Services:**
   - `Services/User/AuthUserService.php`

3. **Middleware:**
   - `middleware/AuthMiddleware.php`

4. **Views:**
   - `Views/user/partials/header.php`
   - `Views/user/partials/cart-items.php`
   - `Views/user/partials/cart-item.php`
   - `Views/user/logout.php`

5. **Routes:**
   - `Routes/add-to-cart.php`
   - `Routes/remove-from-cart.php`
   - `Routes/update-cart.php`
   - `Routes/toggle-cart-select.php`

## Testing Checklist

- [ ] Customer registration works
- [ ] Customer login works with correct credentials
- [ ] Customer logout works
- [ ] Cart displays items correctly
- [ ] Add to cart creates cart and cart_items
- [ ] Update cart quantity works
- [ ] Remove from cart works
- [ ] Cart count in header updates correctly
- [ ] Product detail page loads correctly

## Optional Enhancements

If needed, you can add these columns to enhance functionality:

```sql
-- Add selection support to cart_items
ALTER TABLE cart_items ADD COLUMN selected TINYINT(1) DEFAULT 0 AFTER quantity;

-- Add timestamps to cart_items
ALTER TABLE cart_items ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE cart_items ADD COLUMN updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP;

-- Enhance customers table
ALTER TABLE customers ADD COLUMN avatar VARCHAR(255) NULL AFTER password;
ALTER TABLE customers ADD COLUMN phone VARCHAR(20) NULL AFTER email;
ALTER TABLE customers ADD COLUMN address VARCHAR(255) NULL AFTER phone;
ALTER TABLE customers ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE customers ADD COLUMN updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP;
```

## Notes

- All code now uses `customer` terminology consistently
- Cart system properly implements one-to-many relationship (1 cart → many cart_items)
- Selection feature in cart currently disabled (needs schema update)
- All authentication checks now use `customer_id` session variable
