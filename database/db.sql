SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ===============================
-- Seller accounts (Vendor)
-- ===============================
CREATE TABLE IF NOT EXISTS seller_account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    status ENUM('active','suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_seller_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- Delivery Companies (External Partners)
-- ===============================
CREATE TABLE IF NOT EXISTS delivery_companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,      -- ví dụ: GHTK, GHN, VNPOST
    phone VARCHAR(20),
    email VARCHAR(100),
    address VARCHAR(255),
    website VARCHAR(255),
    api_endpoint VARCHAR(255),             -- URL API để gọi
    api_token VARCHAR(255),                -- token xác thực (nếu có)
    status ENUM('active','inactive','banned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_delivery_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- Shops
-- ===============================
CREATE TABLE IF NOT EXISTS shops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    address VARCHAR(255),
    phone VARCHAR(20),
    logo_url VARCHAR(255),
    status ENUM('open','closed','banned') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES seller_account(id) ON DELETE CASCADE,
    INDEX idx_seller (seller_id),
    INDEX idx_shop_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- Categories
-- ===============================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- Products
-- ===============================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_id INT NOT NULL,
    category_id INT NULL,
    sku VARCHAR(100),
    name VARCHAR(255) NOT NULL,
    description TEXT,
    thumbnail_url VARCHAR(255),
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    stock INT DEFAULT 0,
    status ENUM('active','paused','deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    UNIQUE KEY idx_sku (sku),
    INDEX idx_shop_status_modified (shop_id, status, modified_at),
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- Orders
-- ===============================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(50) UNIQUE,
    shop_id INT NOT NULL,

    delivery_company_id INT NULL,            -- công ty giao hàng được shop chọn

    customer_id INT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100),
    customer_phone VARCHAR(20),

    shipping_province VARCHAR(100),
    shipping_city VARCHAR(100),
    shipping_ward VARCHAR(100),
    shipping_detail VARCHAR(255),

    payment_method ENUM('Cash on Delivery','Bank Transfer','Credit Card') DEFAULT 'Cash on Delivery',
    payment_transaction_id VARCHAR(100),
    payment_status ENUM('Pending','Paid','Refunded') DEFAULT 'Pending',

    shipping_status ENUM('Pending','Assigned','Picked Up','Delivering','Delivered','Failed','Returned') DEFAULT 'Pending',
    shipping_tracking_number VARCHAR(100),

    subtotal DECIMAL(10,2) DEFAULT 0,
    shipping_fee DECIMAL(10,2) DEFAULT 0,
    tax DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) DEFAULT 0,

    status ENUM('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending',
    cancel_reason VARCHAR(255),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE,
    FOREIGN KEY (delivery_company_id) REFERENCES delivery_companies(id) ON DELETE SET NULL,
    INDEX idx_shop_status (shop_id, status),
    INDEX idx_orders_shop_status_date (shop_id, status, created_at),
    INDEX idx_payment_status (payment_status),
    INDEX idx_delivery_company (delivery_company_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- Order Items
-- ===============================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NULL,
    variant_id INT NULL,
    product_name VARCHAR(255) NOT NULL,
    thumbnail_url VARCHAR(255),
    quantity INT DEFAULT 1 CHECK (quantity > 0),
    price DECIMAL(10,2) DEFAULT 0 CHECK (price >= 0),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- Users (Customers)
-- ===============================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SET FOREIGN_KEY_CHECKS = 1;
