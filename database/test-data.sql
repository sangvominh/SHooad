-- Dữ liệu mẫu cho bảng sellers
INSERT INTO sellers (name, email, password_hash, account_status)
VALUES
('Nguyen Van A', 'a@example.com', 'hash_a123', 'active'),
('Tran Thi B', 'b@example.com', 'hash_b123', 'pending'),
('Le Van C', 'c@example.com', 'hash_c123', 'suspended');

-- Dữ liệu mẫu cho bảng products
INSERT INTO products (seller_id, name, description, price, status)
VALUES
(1, 'Áo thun nam', 'Áo thun cotton 100%', 150000, 'active'),
(1, 'Quần jean nữ', 'Quần jean co giãn', 320000, 'paused'),
(2, 'Giày thể thao', 'Giày sneaker trắng', 450000, 'active'),
(3, 'Túi xách da', 'Túi xách da bò thật', 800000, 'active');

-- Dữ liệu mẫu cho bảng orders
INSERT INTO orders (product_id, seller_id, buyer_id, quantity, unit_price, total_amount, order_status)
VALUES
(1, 1, 101, 2, 150000, 300000, 'completed'),
(2, 1, 102, 1, 320000, 320000, 'pending'),
(3, 2, 103, 1, 450000, 450000, 'cancelled'),
(4, 3, 104, 2, 800000, 1600000, 'refunded');
