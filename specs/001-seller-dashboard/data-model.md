# Data Model: Seller Dashboard

**Feature**: Seller Dashboard  
**Date**: 2025-10-30  
**Phase**: 1 - Data Design

## Overview

This document defines the data entities, relationships, validation rules, and state transitions for the Seller Dashboard feature. All entities follow normalization principles and support the functional requirements defined in the specification.

## Entity Definitions

### 1. Seller

**Purpose**: Represents a user with seller privileges who can manage products and view business statistics.

**Table**: `sellers`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique seller identifier |
| name | VARCHAR(255) | NOT NULL | Seller's full name or business name |
| email | VARCHAR(255) | NOT NULL, UNIQUE | Seller's email address (login identifier) |
| password_hash | VARCHAR(255) | NOT NULL | Bcrypt hashed password |
| account_status | ENUM('active', 'suspended', 'pending') | NOT NULL, DEFAULT 'pending' | Current account status |
| created_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Account creation timestamp |
| updated_at | TIMESTAMP | NULL, ON UPDATE CURRENT_TIMESTAMP | Last modification timestamp |

**Indexes**:
- PRIMARY KEY (`id`)
- UNIQUE KEY (`email`)
- INDEX (`account_status`)

**Validation Rules**:
- `name`: 3-255 characters, no special HTML characters
- `email`: Valid email format (RFC 5322), unique in system
- `password_hash`: Must be bcrypt hashed, never stored plain
- `account_status`: One of allowed enum values

**Business Rules**:
- Only sellers with `account_status = 'active'` can access dashboard
- Email cannot be changed once registered (requires admin intervention)
- Seller cannot be deleted, only suspended

---

### 2. Product

**Purpose**: Represents an item for sale managed by a seller.

**Table**: `products`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique product identifier |
| seller_id | INT | NOT NULL, FOREIGN KEY → sellers(id) | Owner of the product |
| name | VARCHAR(255) | NOT NULL | Product name/title |
| description | TEXT | NULL | Detailed product description |
| price | DECIMAL(10,2) | NOT NULL, CHECK (price >= 0) | Product price in USD |
| status | ENUM('active', 'paused', 'deleted') | NOT NULL, DEFAULT 'active' | Current product status |
| created_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Product creation timestamp |
| modified_at | TIMESTAMP | NULL, ON UPDATE CURRENT_TIMESTAMP | Last modification timestamp |

**Indexes**:
- PRIMARY KEY (`id`)
- FOREIGN KEY (`seller_id`) REFERENCES `sellers(id)` ON DELETE CASCADE
- INDEX (`seller_id`, `status`, `modified_at`) -- Composite index for dashboard query
- INDEX (`status`)
- INDEX (`modified_at`)

**Validation Rules**:
- `name`: 3-255 characters, required, sanitized for XSS
- `description`: Optional, max 10,000 characters, sanitized for XSS
- `price`: Non-negative decimal, max 2 decimal places
- `status`: One of allowed enum values
- `seller_id`: Must reference existing active seller

**Business Rules**:
- Products with `status = 'deleted'` are soft-deleted (not shown but retained)
- Products with `status = 'paused'` don't appear in customer searches
- Products with `status = 'active'` are visible to customers
- Sellers can only modify their own products
- `modified_at` updates on any field change (used for dashboard sorting)

**State Transitions**:
```
active → paused (seller pauses listing)
paused → active (seller reactivates listing)
active → deleted (seller deletes product)
paused → deleted (seller deletes paused product)
deleted → [no transitions] (soft delete is permanent)
```

---

### 3. Order

**Purpose**: Represents a completed transaction linking buyer, seller, and product.

**Table**: `orders`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique order identifier |
| product_id | INT | NOT NULL, FOREIGN KEY → products(id) | Product purchased |
| seller_id | INT | NOT NULL, FOREIGN KEY → sellers(id) | Seller who sold the product |
| buyer_id | INT | NOT NULL, FOREIGN KEY → users(id) | Customer who purchased |
| quantity | INT | NOT NULL, CHECK (quantity > 0) | Number of items purchased |
| unit_price | DECIMAL(10,2) | NOT NULL | Price per unit at time of purchase |
| total_amount | DECIMAL(10,2) | NOT NULL | Total order amount (quantity * unit_price) |
| order_status | ENUM('pending', 'completed', 'cancelled', 'refunded') | NOT NULL, DEFAULT 'pending' | Current order status |
| order_date | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Order placement timestamp |
| completed_at | TIMESTAMP | NULL | Order completion timestamp |

**Indexes**:
- PRIMARY KEY (`id`)
- FOREIGN KEY (`product_id`) REFERENCES `products(id)`
- FOREIGN KEY (`seller_id`) REFERENCES `sellers(id)`
- FOREIGN KEY (`buyer_id`) REFERENCES `users(id)`
- INDEX (`seller_id`, `order_status`, `order_date`) -- Composite index for statistics query
- INDEX (`order_date`)

**Validation Rules**:
- `quantity`: Positive integer, required
- `unit_price`: Non-negative decimal matching product price at order time
- `total_amount`: Must equal `quantity * unit_price`
- `order_status`: One of allowed enum values
- `seller_id`: Must match the seller_id of the referenced product

**Business Rules**:
- Only orders with `order_status = 'completed'` count toward seller statistics
- `unit_price` captures price at time of sale (historical record)
- `total_amount` stored for performance (avoid recalculation)
- Monthly statistics filter by `order_date` month/year AND `order_status = 'completed'`

**State Transitions**:
```
pending → completed (payment confirmed)
pending → cancelled (payment failed or user cancelled)
completed → refunded (customer requested refund)
cancelled → [no transitions] (terminal state)
refunded → [no transitions] (terminal state)
```

---

## Relationships

### Entity Relationship Diagram

```
sellers (1) ──────< (N) products
  │
  │
  └──────────────< (N) orders
                      │
                      └──────> (1) products
```

### Cardinality

- **Seller → Products**: One-to-Many (1:N)
  - One seller owns many products
  - One product belongs to exactly one seller

- **Seller → Orders**: One-to-Many (1:N)
  - One seller has many orders
  - One order belongs to exactly one seller

- **Product → Orders**: One-to-Many (1:N)
  - One product can be in many orders
  - One order references exactly one product (simplified model)

---

## Dashboard-Specific Queries

### 1. Get Dashboard Statistics

**Purpose**: Retrieve all statistics for seller dashboard in a single efficient query set.

**Query: Total Products Count**
```sql
SELECT COUNT(*) as total_products
FROM products
WHERE seller_id = :seller_id
  AND status != 'deleted';
```

**Query: Active Listings Count**
```sql
SELECT COUNT(*) as active_listings
FROM products
WHERE seller_id = :seller_id
  AND status = 'active';
```

**Query: Monthly Sales Count**
```sql
SELECT COUNT(*) as monthly_sales
FROM orders
WHERE seller_id = :seller_id
  AND order_status = 'completed'
  AND YEAR(order_date) = YEAR(CURRENT_DATE)
  AND MONTH(order_date) = MONTH(CURRENT_DATE);
```

**Query: Monthly Revenue**
```sql
SELECT COALESCE(SUM(total_amount), 0) as monthly_revenue
FROM orders
WHERE seller_id = :seller_id
  AND order_status = 'completed'
  AND YEAR(order_date) = YEAR(CURRENT_DATE)
  AND MONTH(order_date) = MONTH(CURRENT_DATE);
```

**Performance Considerations**:
- All queries use indexed columns (`seller_id`, `status`, `order_date`, `order_status`)
- Separate queries (no JOIN) for simplicity and clarity
- `COALESCE` handles NULL case when no orders exist

---

### 2. Get Recent Active Products

**Purpose**: Retrieve 10-20 most recently modified active products for dashboard display.

**Query**:
```sql
SELECT id, name, price, status, modified_at
FROM products
WHERE seller_id = :seller_id
  AND status = 'active'
ORDER BY modified_at DESC
LIMIT :limit;
```

**Parameters**:
- `:seller_id` - Current authenticated seller ID
- `:limit` - Number of products to display (10-20)

**Performance**:
- Uses composite index on (`seller_id`, `status`, `modified_at`)
- LIMIT ensures bounded result set
- Only selects needed columns (no description for dashboard list)

---

### 3. Pause Product

**Purpose**: Change product status from active to paused.

**Query**:
```sql
UPDATE products
SET status = 'paused',
    modified_at = CURRENT_TIMESTAMP
WHERE id = :product_id
  AND seller_id = :seller_id
  AND status = 'active';
```

**Validation**:
- Verify seller owns the product (seller_id match)
- Only pause if currently active
- Updates modified_at to reflect change (affects dashboard sort order)

---

### 4. Delete Product

**Purpose**: Soft delete product (change status to deleted).

**Query**:
```sql
UPDATE products
SET status = 'deleted',
    modified_at = CURRENT_TIMESTAMP
WHERE id = :product_id
  AND seller_id = :seller_id
  AND status IN ('active', 'paused');
```

**Validation**:
- Verify seller owns the product
- Can delete from active or paused state
- Soft delete preserves data for historical orders

---

## Data Integrity & Constraints

### Database-Level Constraints

1. **Foreign Key Constraints**:
   - `products.seller_id` → `sellers.id` (CASCADE on delete)
   - `orders.seller_id` → `sellers.id` (RESTRICT on delete - prevent if orders exist)
   - `orders.product_id` → `products.id` (RESTRICT on delete - prevent if orders exist)

2. **CHECK Constraints**:
   - `products.price >= 0`
   - `orders.quantity > 0`
   - `orders.total_amount = orders.quantity * orders.unit_price` (application enforced)

3. **UNIQUE Constraints**:
   - `sellers.email` (prevent duplicate accounts)

### Application-Level Validation

1. **Input Sanitization** (before INSERT/UPDATE):
   ```php
   $name = htmlspecialchars(strip_tags($input['name']), ENT_QUOTES, 'UTF-8');
   ```

2. **Length Validation**:
   - Product name: 3-255 characters
   - Email: Valid RFC 5322 format

3. **Authorization**:
   - Verify `$_SESSION['seller_id']` matches `product.seller_id` before any product operation

---

## Indexing Strategy

### Primary Indexes (for Dashboard Performance)

1. **products**:
   - `(seller_id, status, modified_at)` - Composite index for dashboard query
   - Covers WHERE filter and ORDER BY in single index scan

2. **orders**:
   - `(seller_id, order_status, order_date)` - Composite index for statistics queries
   - Enables efficient monthly aggregation

### Secondary Indexes

- `products.status` - For customer product search (out of scope but future-proof)
- `orders.order_date` - For date range queries

### Index Maintenance

- Indexes automatically maintained by MySQL
- Monitor slow query log for optimization opportunities
- Consider partitioning orders table by month if volume grows significantly

---

## Migration Strategy

### Initial Schema Creation

**File**: `database/migrations/001_create_seller_dashboard_tables.sql`

```sql
-- Sellers table (may already exist)
CREATE TABLE IF NOT EXISTS sellers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    account_status ENUM('active', 'suspended', 'pending') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_account_status (account_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    status ENUM('active', 'paused', 'deleted') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES sellers(id) ON DELETE CASCADE,
    INDEX idx_seller_status_modified (seller_id, status, modified_at),
    INDEX idx_status (status),
    INDEX idx_modified (modified_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    seller_id INT NOT NULL,
    buyer_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    unit_price DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status ENUM('pending', 'completed', 'cancelled', 'refunded') NOT NULL DEFAULT 'pending',
    order_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (seller_id) REFERENCES sellers(id),
    INDEX idx_seller_status_date (seller_id, order_status, order_date),
    INDEX idx_order_date (order_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Data Seeding (Development Only)

Create sample data for testing dashboard functionality.

---

## Summary

The data model supports all functional requirements:
- ✅ Seller authentication and authorization
- ✅ Product management (create, edit, pause, delete)
- ✅ Monthly statistics calculation (products, sales, revenue)
- ✅ Recent products display (sorted by modified_at)
- ✅ Security (foreign keys, validation, sanitization)
- ✅ Performance (indexed queries, limited result sets)

All entities follow single responsibility principle with clear boundaries and relationships. The model is normalized (3NF) with intentional denormalization for performance (total_amount in orders).
