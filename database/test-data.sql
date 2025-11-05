SET FOREIGN_KEY_CHECKS = 0;

-- ===============================
-- Seller accounts
-- ===============================
INSERT INTO seller_account (name, email, phone, password, status, created_at)
VALUES
('Nguyen Van A', 'sellerA@example.com', '0901234567', '123456', 'active', NOW()),
('Tran Thi B', 'sellerB@example.com', '0907654321', '123456', 'active', NOW());

-- ===============================
-- Shipper accounts
-- ===============================
INSERT INTO shipper_account (name, email, phone, password, status, created_at)
VALUES
('Le Van C', 'shipperC@example.com', '0901122334', '123456', 'available', NOW()),
('Pham Thi D', 'shipperD@example.com', '0902233445', '123456', 'available', NOW());

-- ===============================
-- Shops
-- ===============================
INSERT INTO shops (seller_id, name, description, address, phone, status, created_at, updated_at)
VALUES
(1, 'TechZone', 'Chuyên đồ công nghệ, phụ kiện điện tử', '123 Le Loi, Q1, TP.HCM', '0901112233', 'open', NOW(), NOW()),
(2, 'HomeStyle', 'Đồ gia dụng, nội thất hiện đại', '45 Tran Hung Dao, Ha Noi', '0903334455', 'open', NOW(), NOW());

-- ===============================
-- Categories
-- ===============================
INSERT INTO categories (name)
VALUES
('Điện thoại'),
('Laptop'),
('Đồ gia dụng');

-- ===============================
-- Products
-- ===============================
INSERT INTO products (shop_id, category_id, sku, name, description, price, stock, status, created_at)
VALUES
(1, 1, 'P1001', 'iPhone 15 Pro', 'Điện thoại cao cấp Apple', 29990000, 10, 'active', NOW()),
(1, 2, 'P1002', 'MacBook Air M2', 'Laptop Apple mỏng nhẹ', 28990000, 8, 'active', NOW()),
(2, 3, 'P2001', 'Nồi chiên không dầu Philips', 'Dung tích 5.5L, bảo hành 2 năm', 2990000, 20, 'active', NOW());

-- ===============================
-- Orders
-- ===============================
INSERT INTO orders (
    order_code, shop_id, shipper_id, customer_id, customer_name, customer_email, customer_phone,
    shipping_province, shipping_city, shipping_ward, shipping_detail,
    payment_method, payment_status, shipping_status,
    subtotal, shipping_fee, tax, total_amount, status, created_at, updated_at
)
VALUES
('ORD001', 1, 1, NULL, 'Le Minh Tuan', 'tuanle@gmail.com', '0905566778',
 'Ho Chi Minh', 'Quan 1', 'Ben Nghe', '12 Nguyen Hue',
 'Cash on Delivery', 'Pending', 'Pending',
 29990000, 30000, 0, 30020000, 'Pending', NOW(), NOW()),

('ORD002', 2, 2, NULL, 'Pham Thi Hoa', 'hoapt@gmail.com', '0909988776',
 'Ha Noi', 'Hoan Kiem', 'Hang Bong', '56 Hang Dao',
 'Bank Transfer', 'Paid', 'Shipped',
 2990000, 20000, 0, 3010000, 'Processing', NOW(), NOW());

-- ===============================
-- Order Items
-- ===============================
INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
VALUES
(1, 1, 'iPhone 15 Pro', 1, 29990000),
(2, 3, 'Nồi chiên không dầu Philips', 1, 2990000);

-- ===============================
-- Inventory Logs
-- ===============================
INSERT INTO inventory_logs (product_id, change_amount, source, created_at)
VALUES
(1, -1, 'order', NOW()),
(3, -1, 'order', NOW());

SET FOREIGN_KEY_CHECKS = 1;
