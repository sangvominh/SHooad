SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================
-- SELLER
-- ============================
INSERT INTO seller (name, email, phone, password, status) VALUES
('Nguyen Van A', 'seller1@example.com', '0901234567', 'hashed_pw_1', 'open'),
('Tran Thi B', 'seller2@example.com', '0909876543', 'hashed_pw_2', 'open');

-- ============================
-- SHOPS
-- ============================
INSERT INTO shops (seller_id, name, address, phone, status) VALUES
(1, 'Shop A', '123 Main St', '0903334444', 'open'),
(1, 'Shop A 2', '99 Le Loi', '0905556666', 'open'),
(2, 'Shop B', '456 Market St', '0907778888', 'open');

-- ============================
-- CATEGORIES
-- ============================
INSERT INTO categories (name) VALUES
('Clothes'),
('Shoes'),
('Electronics');

-- ============================
-- PRODUCTS
-- ============================
INSERT INTO products 
(shop_id, category_id, name, brand, description, colors, sizes, price, original_price, stock, sold)
VALUES
(1, 1, 'T-Shirt Basic', 'Uniqlo', 'Cotton shirt', 'red,blue,black', 'M,L,XL', 199000, 250000, 50, 10),
(1, 2, 'Running Shoes', 'Nike', 'Lightweight running shoes', 'white,black', '40,41,42,43', 1500000, 1800000, 30, 5),
(2, 3, 'Bluetooth Headphone', 'Sony', 'Noise cancelling', 'black', '', 2500000, 3000000, 20, 2);

-- ============================
-- PRODUCT IMAGES
-- ============================
INSERT INTO product_images (product_id, filename) VALUES
(1, 'tshirt1.jpg'),
(1, 'tshirt2.jpg'),
(2, 'shoes1.jpg'),
(3, 'sony1.jpg');

-- ============================
-- CUSTOMERS
-- ============================
INSERT INTO customers (name, email, password, status) VALUES
('Le Minh', 'customer1@example.com', 'hashed_cus_pw1', 'active'),
('Pham Tuan', 'customer2@example.com', 'hashed_cus_pw2', 'active');

-- ============================
-- REVIEWS
-- ============================
INSERT INTO reviews (product_id, customer_id, rating, comment)
VALUES
(1, 1, 5, 'Good quality!'),
(2, 2, 4, 'Pretty good');

-- ============================
-- ORDERS
-- ============================
INSERT INTO orders (shop_id, customer_id, customer_phone, shipping_address, status)
VALUES
(1, 1, '0909991111', '12 Nguyen Trai, Ha Noi', 'Paid'),
(1, 2, '0908882222', '45 Tran Phu, Ha Noi', 'Pending_COD');

-- ============================
-- ORDER ITEMS
-- ============================
INSERT INTO order_items 
(order_id, product_id, quantity, price, product_name, product_color, product_size)
VALUES
(1, 1, 2, 199000, 'T-Shirt Basic', 'blue', 'L'),
(1, 2, 1, 1500000, 'Running Shoes', 'black', '42'),
(2, 1, 1, 199000, 'T-Shirt Basic', 'red', 'M');

-- ============================
-- CARTS
-- ============================
INSERT INTO carts (customer_id) VALUES
(1),
(2);

-- ============================
-- CART ITEMS
-- ============================
INSERT INTO cart_items (cart_id, product_id, quantity, color, size)
VALUES
(1, 1, 1, 'red', 'M'),
(1, 2, 1, 'white', '42'),
(2, 3, 1, 'black', '');

-- ============================
-- BANNERS
-- ============================
INSERT INTO banners (title, filename, is_active) VALUES
('Sale 50%', 'banner1.jpg', 1),
('New Arrival', 'banner2.jpg', 1);

SET FOREIGN_KEY_CHECKS = 1;
