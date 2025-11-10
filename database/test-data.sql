-- ===============================
-- Sample Data for seller_account
-- ===============================
INSERT INTO seller_account (name, email, phone, password, status)
VALUES 
('TechZone', 'seller1@techzone.com', '0909123456', '123456', 'active'),
('BookWorld', 'seller2@bookworld.com', '0909234567', '123456', 'active'),
('Fashionista', 'seller3@fashionista.com', '0909345678', '123456', 'suspended');

-- ===============================
-- Sample Data for delivery_companies
-- ===============================
INSERT INTO delivery_companies (name, code, phone, email, address, website, api_endpoint, api_token, status)
VALUES
('Giao Hàng Nhanh', 'GHN', '19001234', 'support@ghn.vn', '123 Le Loi, HCMC', 'https://ghn.vn', 'https://api.ghn.vn/order', 'TOKEN_GHN_123', 'active'),
('Giao Hàng Tiết Kiệm', 'GHTK', '19002222', 'support@ghtk.vn', '45 Tran Hung Dao, Hanoi', 'https://ghtk.vn', 'https://api.ghtk.vn/order', 'TOKEN_GHTK_456', 'active'),
('VNPost Express', 'VNPOST', '19001111', 'cs@vnpost.vn', '1 Dinh Tien Hoang, Hanoi', 'https://vnpost.vn', 'https://api.vnpost.vn', 'TOKEN_VNPOST_789', 'inactive');

-- ===============================
-- Sample Data for shops
-- ===============================
INSERT INTO shops (seller_id, name, description, address, phone, logo_url, status)
VALUES
(1, 'TechZone Official Store', 'Chuyên laptop, PC, phụ kiện chính hãng', '12 Nguyen Hue, HCMC', '0909123456', 'https://cdn.example.com/logos/techzone.png', 'open'),
(2, 'BookWorld Store', 'Cung cấp sách văn học, giáo dục, kỹ năng sống', '45 Nguyen Trai, Hanoi', '0909234567', 'https://cdn.example.com/logos/bookworld.png', 'open'),
(3, 'Fashionista Boutique', 'Shop thời trang nữ hiện đại', '23 Vo Van Tan, HCMC', '0909345678', 'https://cdn.example.com/logos/fashionista.png', 'closed');

-- ===============================
-- Sample Data for categories
-- ===============================
INSERT INTO categories (name)
VALUES
('Electronics'),
('Books'),
('Clothing'),
('Accessories');

-- ===============================
-- Sample Data for products
-- ===============================
INSERT INTO products (shop_id, category_id, sku, name, description, thumbnail_url, price, stock, status)
VALUES
(1, 1, 'LAPTOP-001', 'Laptop ASUS Vivobook', 'Laptop cho sinh viên văn phòng', 'https://cdn.example.com/products/laptop1.jpg', 15990000, 15, 'active'),
(1, 4, 'MOUSE-002', 'Chuột Logitech M330', 'Chuột không dây, siêu êm', 'https://cdn.example.com/products/mouse1.jpg', 450000, 50, 'active'),
(2, 2, 'BOOK-101', 'Đắc Nhân Tâm', 'Sách kỹ năng sống nổi tiếng của Dale Carnegie', 'https://cdn.example.com/products/book1.jpg', 85000, 100, 'active'),
(3, 3, 'DRESS-501', 'Đầm caro cổ vuông', 'Thời trang nữ, chất liệu cotton', 'https://cdn.example.com/products/dress1.jpg', 320000, 30, 'paused');

-- ===============================
-- Sample Data for users (customers)
-- ===============================
INSERT INTO users (name, email, password)
VALUES
('Nguyen Van A', 'a@gmail.com', '123456'),
('Tran Thi B', 'b@gmail.com', '123456'),
('Le Van C', 'c@gmail.com', '123456');

-- ===============================
-- Sample Data for orders
-- ===============================
INSERT INTO orders (order_code, shop_id, delivery_company_id, customer_id, customer_name, customer_email, customer_phone,
shipping_province, shipping_city, shipping_ward, shipping_detail,
payment_method, payment_status, shipping_status, shipping_tracking_number,
subtotal, shipping_fee, tax, total_amount, status)
VALUES
('ORD001', 1, 1, 1, 'Nguyen Van A', 'a@gmail.com', '0909123456',
'Hồ Chí Minh', 'Quận 1', 'Phường Bến Nghé', '12 Nguyễn Huệ',
'Cash on Delivery', 'Pending', 'Pending', NULL,
15990000, 30000, 0, 16020000, 'Pending'),

('ORD002', 2, 2, 2, 'Tran Thi B', 'b@gmail.com', '0909234567',
'Hà Nội', 'Hoàn Kiếm', 'Phường Tràng Tiền', '45 Nguyễn Trãi',
'Bank Transfer', 'Paid', 'Delivering', 'GHN12345',
85000, 25000, 0, 110000, 'Processing');

-- ===============================
-- Sample Data for order_items
-- ===============================
INSERT INTO order_items (order_id, product_id, product_name, thumbnail_url, quantity, price)
VALUES
(1, 1, 'Laptop ASUS Vivobook', 'https://cdn.example.com/products/laptop1.jpg', 1, 15990000),
(2, 3, 'Đắc Nhân Tâm', 'https://cdn.example.com/products/book1.jpg', 1, 85000);
