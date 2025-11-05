-- Sellers
INSERT INTO seller_account (name, email, password)
VALUES
('Nguyen Van A', 'sang@gmail.com', '1'),
('Tran Thi B', 'seller_b@example.com', 'abcdef');

-- Shops
INSERT INTO shops (seller_id, name, description, address, phone)
VALUES
(1, 'A Tech Store', 'Cửa hàng đồ điện tử', '123 Lê Lợi, Q1, TP.HCM', '0909123456'),
(2, 'B Fashion', 'Cửa hàng thời trang nữ', '45 Hai Bà Trưng, Hà Nội', '0912345678');

-- Products
INSERT INTO products (shop_id, name, description, price)
VALUES
(1, 'Tai nghe Bluetooth', 'Tai nghe không dây chất lượng cao', 350000),
(1, 'Chuột không dây', 'Chuột Logitech chính hãng', 250000),
(2, 'Đầm công sở', 'Đầm nữ cao cấp', 500000),
(2, 'Áo thun nữ', 'Áo cotton thoáng mát', 200000);

-- Orders
INSERT INTO orders (
    shop_id, customer_name, customer_email, customer_phone, shipping_address,
    payment_method, payment_status, subtotal, shipping_fee, tax, total_amount, status
)
VALUES
(1, 'Le Minh Tuan', 'tuanlm@example.com', '0988111222', '12 Nguyễn Huệ, TP.HCM',
 'Bank Transfer', 'Paid', 600000, 20000, 10000, 630000, 'Completed'),
(2, 'Pham Thi Hoa', 'hoapt@example.com', '0977333444', '78 Cầu Giấy, Hà Nội',
 'Cash on Delivery', 'Pending', 700000, 30000, 15000, 745000, 'Processing');

-- Order items
INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
VALUES
(1, 1, 'Tai nghe Bluetooth', 1, 350000),
(1, 2, 'Chuột không dây', 1, 250000),
(2, 3, 'Đầm công sở', 1, 500000),
(2, 4, 'Áo thun nữ', 1, 200000);
