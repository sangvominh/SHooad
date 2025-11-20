-- 1. Cấu hình chung cho phiên làm việc
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = '+07:00';

-- ============================
-- 1. SELLERS
-- ============================
DROP TABLE IF EXISTS `sellers`;
CREATE TABLE `sellers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20),
    `password` VARCHAR(255) NOT NULL,
    `status` ENUM('open','closed','banned') DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 2. SHOPS
-- ============================
DROP TABLE IF EXISTS `shops`;
CREATE TABLE `shops` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sellers_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `address` VARCHAR(255),
    `phone` VARCHAR(20),
    `status` ENUM('open','closed','banned') DEFAULT 'open',
    FOREIGN KEY (`sellers_id`) REFERENCES `sellers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 3. DELIVERY COMPANIES
-- ============================
DROP TABLE IF EXISTS `delivery_companies`;
CREATE TABLE `delivery_companies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `shipping_fee` DECIMAL(10,2) DEFAULT 30000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 4. CATEGORIES
-- ============================
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 5. PRODUCTS
-- ============================
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `shop_id` INT NOT NULL,
    `category_id` INT NULL,
    `name` VARCHAR(255) NOT NULL,
    `brand` VARCHAR(100),
    `description` TEXT,
    `price` DECIMAL(10,2),
    `original_price` DECIMAL(10,2),
    `sold` INT DEFAULT 0,
    `status` ENUM('active','paused','deleted') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `modified_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`shop_id`) REFERENCES `shops`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 6. PRODUCT IMAGES
-- ============================
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `filename` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 7. COLORS
-- ============================
DROP TABLE IF EXISTS `colors`;
CREATE TABLE `colors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `hex_code` VARCHAR(7) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 8. SIZES
-- ============================
DROP TABLE IF EXISTS `sizes`;
CREATE TABLE `sizes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(20) NOT NULL UNIQUE,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 9. PRODUCT VARIANTS
-- ============================
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `color_id` INT NULL,
    `size_id` INT NULL,
    `sku` VARCHAR(100) NOT NULL UNIQUE,
    `stock` INT NOT NULL DEFAULT 0,
    `price` DECIMAL(10,2),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`color_id`) REFERENCES `colors`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`size_id`) REFERENCES `sizes`(`id`) ON DELETE SET NULL,
    UNIQUE KEY `unique_variant` (`product_id`, `color_id`, `size_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 10. CUSTOMERS
-- ============================
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `status` ENUM('active','inactive','banned') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 11. REVIEWS
-- ============================
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `customer_id` INT NOT NULL,
    `rating` INT,
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 12. ORDERS
-- ============================
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `shop_id` INT NOT NULL,
    `customer_id` INT NOT NULL,
    `customer_phone` VARCHAR(20) NOT NULL,
    `shipping_address` VARCHAR(255) NOT NULL,
    `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('pending','processing','delivering','completed','cancelled','failed') DEFAULT 'pending',
    `payment_method` ENUM('cod', 'online') DEFAULT 'cod',
    `payment_status` ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    FOREIGN KEY (`shop_id`) REFERENCES `shops`(`id`),
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 13. ORDER ITEMS
-- ============================
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NULL,
    `quantity` INT DEFAULT 1,
    `price` DECIMAL(10,2) DEFAULT 0,
    `product_name` VARCHAR(255),
    `product_color` VARCHAR(100),
    `product_size` VARCHAR(100),
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 14. CARTS
-- ============================
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 15. CART ITEMS
-- ============================
DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cart_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT DEFAULT 1,
    `color` VARCHAR(100) NOT NULL,
    `size` VARCHAR(100) NOT NULL,
    `selected` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- 16. CUSTOMER ADDRESSES
-- ============================
DROP TABLE IF EXISTS `customer_addresses`;
CREATE TABLE `customer_addresses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `is_default` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bật lại kiểm tra khóa ngoại
SET FOREIGN_KEY_CHECKS = 1;