-- ============================
-- 1. SELLER (1 người bán)
-- ============================
INSERT INTO seller (name, email, phone, password, status) VALUES
('Nguyễn Văn A', 'seller@example.com', '0901234567', '$2y$10$abcdefghijklmnopqrstuvwxyz', 'open');

-- ============================
-- 2. SHOPS (1 shop)
-- ============================
INSERT INTO shops (seller_id, name, description, address, phone, status) VALUES
(1, 'Shop Thời Trang AAA', 'Chuyên cung cấp quần áo thời trang nam nữ chất lượng cao', '123 Nguyễn Huệ, Q1, TP.HCM', '0287654321', 'open');

-- ============================
-- 3. DELIVERY COMPANIES
-- ============================
INSERT INTO delivery_companies (name) VALUES
('Giao Hàng Nhanh'),
('Giao Hàng Tiết Kiệm'),
('VNPost'),
('J&T Express');

-- ============================
-- 4. CATEGORIES
-- ============================
INSERT INTO categories (name) VALUES
('Áo Nam'),
('Quần Nam'),
('Áo Nữ'),
('Quần Nữ'),
('Phụ Kiện');

-- ============================
-- 5. PRODUCTS (20 sản phẩm)
-- ============================
INSERT INTO products (shop_id, category_id, name, brand, description, colors, sizes, price, original_price, stock, sold, status) VALUES
-- Áo Nam
(1, 1, 'Áo Thun Nam Basic', 'ZARA', 'Áo thun cotton 100% thoáng mát', 'Đen,Trắng,Xám,Xanh Navy', 'S,M,L,XL,XXL', 199000, 299000, 150, 85, 'active'),
(1, 1, 'Áo Sơ Mi Nam Công Sở', 'H&M', 'Áo sơ mi cao cấp, form slim fit', 'Trắng,Xanh Dương,Hồng Nhạt', 'M,L,XL,XXL', 350000, 500000, 100, 120, 'active'),
(1, 1, 'Áo Polo Nam', 'UNIQLO', 'Áo polo có cổ, chất liệu pique', 'Đen,Trắng,Xanh,Đỏ', 'S,M,L,XL', 250000, 350000, 80, 95, 'active'),
(1, 1, 'Áo Khoác Jean Nam', 'LEVI\'S', 'Áo khoác jean phong cách Hàn Quốc', 'Xanh Đậm,Xanh Nhạt,Đen', 'M,L,XL', 450000, 650000, 60, 45, 'active'),
(1, 1, 'Áo Hoodie Nam', 'ADIDAS', 'Áo hoodie có nón, chất nỉ dày', 'Đen,Xám,Xanh Rêu', 'M,L,XL,XXL', 380000, 550000, 90, 110, 'active'),

-- Quần Nam
(1, 2, 'Quần Jean Nam Slim Fit', 'LEVI\'S', 'Quần jean co giãn nhẹ, form slim', 'Xanh Đậm,Xanh Nhạt,Đen', '29,30,31,32,33,34', 400000, 600000, 120, 88, 'active'),
(1, 2, 'Quần Kaki Nam', 'ZARA', 'Quần kaki công sở, chống nhăn', 'Be,Xám,Xanh Navy,Đen', '29,30,31,32,33', 320000, 450000, 95, 72, 'active'),
(1, 2, 'Quần Short Nam', 'H&M', 'Quần short thể thao, thoáng mát', 'Đen,Xám,Xanh,Rêu', 'M,L,XL', 180000, 280000, 110, 135, 'active'),
(1, 2, 'Quần Jogger Nam', 'NIKE', 'Quần jogger thể thao năng động', 'Đen,Xám,Xanh Rêu', 'M,L,XL,XXL', 350000, 500000, 75, 68, 'active'),

-- Áo Nữ
(1, 3, 'Áo Thun Nữ Form Rộng', 'UNIQLO', 'Áo thun oversize phong cách Hàn', 'Trắng,Đen,Hồng,Be', 'S,M,L,XL', 199000, 299000, 140, 165, 'active'),
(1, 3, 'Áo Kiểu Nữ Công Sở', 'MANGO', 'Áo kiểu sơ mi nữ tính, thanh lịch', 'Trắng,Hồng Nhạt,Xanh Pastel', 'S,M,L', 280000, 400000, 85, 92, 'active'),
(1, 3, 'Áo Croptop Nữ', 'ZARA', 'Áo croptop trẻ trung năng động', 'Đen,Trắng,Xanh,Hồng', 'S,M,L', 150000, 250000, 100, 145, 'active'),
(1, 3, 'Áo Khoác Cardigan Nữ', 'H&M', 'Áo khoác len nữ mỏng nhẹ', 'Be,Xám,Hồng,Xanh', 'Freesize', 320000, 480000, 70, 55, 'active'),
(1, 3, 'Áo Blazer Nữ', 'ZARA', 'Áo vest nữ công sở cao cấp', 'Đen,Xám,Be', 'S,M,L,XL', 550000, 800000, 50, 38, 'active'),

-- Quần Nữ
(1, 4, 'Quần Jean Nữ Skinny', 'LEVI\'S', 'Quần jean nữ ôm dáng chuẩn', 'Xanh Đậm,Xanh Nhạt,Đen', '26,27,28,29,30', 380000, 550000, 105, 98, 'active'),
(1, 4, 'Quần Ống Rộng Nữ', 'MANGO', 'Quần ống rộng thời trang Hàn Quốc', 'Đen,Be,Xám', 'S,M,L', 350000, 500000, 80, 87, 'active'),
(1, 4, 'Quần Short Jean Nữ', 'H&M', 'Quần short jean trẻ trung', 'Xanh,Đen,Trắng', 'S,M,L,XL', 220000, 320000, 95, 112, 'active'),
(1, 4, 'Váy Jean Nữ', 'ZARA', 'Váy jean chữ A xinh xắn', 'Xanh Đậm,Xanh Nhạt', 'S,M,L', 280000, 400000, 65, 73, 'active'),

-- Phụ Kiện
(1, 5, 'Nón Snapback', 'MLB', 'Nón lưỡi trai phong cách thể thao', 'Đen,Trắng,Xanh,Đỏ', 'Freesize', 180000, 280000, 120, 88, 'active'),
(1, 5, 'Túi Tote Canvas', 'CANVAS', 'Túi vải canvas đa năng', 'Trắng,Be,Đen', 'Freesize', 120000, 200000, 150, 145, 'active');

-- ============================
-- 6. PRODUCT IMAGES (3-5 ảnh/sản phẩm)
-- ============================
INSERT INTO product_images (product_id, filename) VALUES
-- Product 1
(1, 'ao-thun-nam-1.jpg'), (1, 'ao-thun-nam-2.jpg'), (1, 'ao-thun-nam-3.jpg'),
-- Product 2
(2, 'ao-somi-nam-1.jpg'), (2, 'ao-somi-nam-2.jpg'), (2, 'ao-somi-nam-3.jpg'), (2, 'ao-somi-nam-4.jpg'),
-- Product 3
(3, 'ao-polo-1.jpg'), (3, 'ao-polo-2.jpg'), (3, 'ao-polo-3.jpg'),
-- Product 4
(4, 'ao-khoac-jean-1.jpg'), (4, 'ao-khoac-jean-2.jpg'), (4, 'ao-khoac-jean-3.jpg'), (4, 'ao-khoac-jean-4.jpg'),
-- Product 5
(5, 'ao-hoodie-1.jpg'), (5, 'ao-hoodie-2.jpg'), (5, 'ao-hoodie-3.jpg'),
-- Product 6
(6, 'quan-jean-nam-1.jpg'), (6, 'quan-jean-nam-2.jpg'), (6, 'quan-jean-nam-3.jpg'),
-- Product 7
(7, 'quan-kaki-1.jpg'), (7, 'quan-kaki-2.jpg'), (7, 'quan-kaki-3.jpg'),
-- Product 8
(8, 'quan-short-nam-1.jpg'), (8, 'quan-short-nam-2.jpg'),
-- Product 9
(9, 'quan-jogger-1.jpg'), (9, 'quan-jogger-2.jpg'), (9, 'quan-jogger-3.jpg'),
-- Product 10
(10, 'ao-thun-nu-1.jpg'), (10, 'ao-thun-nu-2.jpg'), (10, 'ao-thun-nu-3.jpg'),
-- Product 11
(11, 'ao-kieu-nu-1.jpg'), (11, 'ao-kieu-nu-2.jpg'), (11, 'ao-kieu-nu-3.jpg'),
-- Product 12
(12, 'ao-croptop-1.jpg'), (12, 'ao-croptop-2.jpg'), (12, 'ao-croptop-3.jpg'),
-- Product 13
(13, 'ao-cardigan-1.jpg'), (13, 'ao-cardigan-2.jpg'),
-- Product 14
(14, 'ao-blazer-1.jpg'), (14, 'ao-blazer-2.jpg'), (14, 'ao-blazer-3.jpg'),
-- Product 15
(15, 'quan-jean-nu-1.jpg'), (15, 'quan-jean-nu-2.jpg'), (15, 'quan-jean-nu-3.jpg'),
-- Product 16
(16, 'quan-ong-rong-1.jpg'), (16, 'quan-ong-rong-2.jpg'),
-- Product 17
(17, 'quan-short-nu-1.jpg'), (17, 'quan-short-nu-2.jpg'), (17, 'quan-short-nu-3.jpg'),
-- Product 18
(18, 'vay-jean-1.jpg'), (18, 'vay-jean-2.jpg'),
-- Product 19
(19, 'non-snapback-1.jpg'), (19, 'non-snapback-2.jpg'),
-- Product 20
(20, 'tui-tote-1.jpg'), (20, 'tui-tote-2.jpg'), (20, 'tui-tote-3.jpg');

-- ============================
-- 7. CUSTOMERS (30 khách hàng)
-- ============================
INSERT INTO customers (name, email, password, status) VALUES
('Trần Thị B', 'customer1@gmail.com', '$2y$10$hashedpassword1', 'active'),
('Lê Văn C', 'customer2@gmail.com', '$2y$10$hashedpassword2', 'active'),
('Phạm Thị D', 'customer3@gmail.com', '$2y$10$hashedpassword3', 'active'),
('Hoàng Văn E', 'customer4@gmail.com', '$2y$10$hashedpassword4', 'active'),
('Ngô Thị F', 'customer5@gmail.com', '$2y$10$hashedpassword5', 'active'),
('Vũ Văn G', 'customer6@gmail.com', '$2y$10$hashedpassword6', 'active'),
('Đặng Thị H', 'customer7@gmail.com', '$2y$10$hashedpassword7', 'active'),
('Bùi Văn I', 'customer8@gmail.com', '$2y$10$hashedpassword8', 'active'),
('Dương Thị K', 'customer9@gmail.com', '$2y$10$hashedpassword9', 'active'),
('Mai Văn L', 'customer10@gmail.com', '$2y$10$hashedpassword10', 'active'),
('Trịnh Thị M', 'customer11@gmail.com', '$2y$10$hashedpassword11', 'active'),
('Lý Văn N', 'customer12@gmail.com', '$2y$10$hashedpassword12', 'active'),
('Phan Thị O', 'customer13@gmail.com', '$2y$10$hashedpassword13', 'active'),
('Đinh Văn P', 'customer14@gmail.com', '$2y$10$hashedpassword14', 'active'),
('Võ Thị Q', 'customer15@gmail.com', '$2y$10$hashedpassword15', 'active'),
('Tô Văn R', 'customer16@gmail.com', '$2y$10$hashedpassword16', 'active'),
('Đỗ Thị S', 'customer17@gmail.com', '$2y$10$hashedpassword17', 'active'),
('Hồ Văn T', 'customer18@gmail.com', '$2y$10$hashedpassword18', 'active'),
('Cao Thị U', 'customer19@gmail.com', '$2y$10$hashedpassword19', 'active'),
('Tạ Văn V', 'customer20@gmail.com', '$2y$10$hashedpassword20', 'active'),
('Lâm Thị X', 'customer21@gmail.com', '$2y$10$hashedpassword21', 'active'),
('Trương Văn Y', 'customer22@gmail.com', '$2y$10$hashedpassword22', 'active'),
('Chu Thị Z', 'customer23@gmail.com', '$2y$10$hashedpassword23', 'active'),
('Lưu Văn AA', 'customer24@gmail.com', '$2y$10$hashedpassword24', 'active'),
('Tống Thị BB', 'customer25@gmail.com', '$2y$10$hashedpassword25', 'active'),
('Hà Văn CC', 'customer26@gmail.com', '$2y$10$hashedpassword26', 'active'),
('Viên Thị DD', 'customer27@gmail.com', '$2y$10$hashedpassword27', 'active'),
('Sơn Văn EE', 'customer28@gmail.com', '$2y$10$hashedpassword28', 'active'),
('Khổng Thị FF', 'customer29@gmail.com', '$2y$10$hashedpassword29', 'active'),
('Từ Văn GG', 'customer30@gmail.com', '$2y$10$hashedpassword30', 'active');

-- ============================
-- 8. REVIEWS (80 reviews)
-- ============================
INSERT INTO reviews (product_id, customer_id, rating, comment, created_at) VALUES
-- Product 1 reviews
(1, 1, 5, 'Áo đẹp, chất liệu mát, rất hài lòng!', '2024-10-01 10:00:00'),
(1, 2, 4, 'Áo ổn, giao hàng nhanh', '2024-10-05 14:30:00'),
(1, 3, 5, 'Mặc rất thoải mái, sẽ mua thêm', '2024-10-10 09:15:00'),
(1, 4, 5, 'Chất lượng tốt, giá hợp lý', '2024-10-15 16:45:00'),

-- Product 2 reviews
(2, 5, 5, 'Áo sơ mi đẹp, form chuẩn', '2024-09-20 11:00:00'),
(2, 6, 4, 'Chất vải tốt, nhưng hơi nhăn', '2024-09-25 13:20:00'),
(2, 7, 5, 'Mặc đi làm rất đẹp', '2024-10-01 08:30:00'),
(2, 8, 5, 'Áo đẹp, giao hàng nhanh', '2024-10-08 15:00:00'),

-- Product 3 reviews
(3, 9, 4, 'Áo polo đẹp, form hơi rộng', '2024-09-15 10:45:00'),
(3, 10, 5, 'Chất liệu tốt, mặc mát', '2024-09-22 14:15:00'),
(3, 11, 5, 'Rất hài lòng với sản phẩm', '2024-10-03 09:00:00'),
(3, 12, 4, 'Đẹp nhưng màu hơi nhạt so với ảnh', '2024-10-12 16:30:00'),

-- Product 4 reviews
(4, 13, 5, 'Áo khoác jean đẹp lắm!', '2024-09-10 11:30:00'),
(4, 14, 4, 'Chất jean dày dặn', '2024-09-18 13:45:00'),
(4, 15, 5, 'Mặc rất phong cách', '2024-10-05 10:20:00'),

-- Product 5 reviews
(5, 16, 5, 'Áo hoodie ấm, chất nỉ dày', '2024-09-25 09:30:00'),
(5, 17, 5, 'Rất thích, sẽ mua thêm', '2024-10-02 14:00:00'),
(5, 18, 4, 'Đẹp nhưng hơi nặng', '2024-10-10 11:15:00'),
(5, 19, 5, 'Chất lượng tốt, giá ổn', '2024-10-16 15:30:00'),

-- Product 6 reviews
(6, 20, 5, 'Quần jean đẹp, form chuẩn', '2024-09-12 10:00:00'),
(6, 21, 4, 'Chất jean tốt nhưng hơi bó', '2024-09-20 13:30:00'),
(6, 22, 5, 'Mặc rất vừa, đẹp', '2024-10-01 09:45:00'),
(6, 23, 5, 'Sẽ giới thiệu cho bạn bè', '2024-10-11 16:00:00'),

-- Product 7 reviews
(7, 24, 4, 'Quần kaki đẹp, mặc công sở ổn', '2024-09-15 11:20:00'),
(7, 25, 5, 'Chất vải mát, không nhăn', '2024-09-28 14:45:00'),
(7, 26, 5, 'Rất hài lòng', '2024-10-07 10:30:00'),

-- Product 8 reviews
(8, 27, 5, 'Quần short mát, mặc thoải mái', '2024-10-01 09:00:00'),
(8, 28, 4, 'Đẹp nhưng hơi ngắn', '2024-10-08 13:15:00'),
(8, 29, 5, 'Chất lượng tốt', '2024-10-14 15:45:00'),
(8, 30, 5, 'Sẽ mua thêm màu khác', '2024-10-18 11:00:00'),

-- Product 9 reviews
(9, 1, 4, 'Quần jogger đẹp, mặc thể thao ok', '2024-09-22 10:30:00'),
(9, 2, 5, 'Rất thích, form đẹp', '2024-10-03 14:00:00'),
(9, 3, 5, 'Chất liệu co giãn tốt', '2024-10-13 09:30:00'),

-- Product 10 reviews
(10, 4, 5, 'Áo thun nữ đẹp lắm!', '2024-09-18 11:15:00'),
(10, 5, 5, 'Form rộng vừa vặn', '2024-09-26 13:45:00'),
(10, 6, 4, 'Đẹp nhưng hơi dài', '2024-10-04 10:00:00'),
(10, 7, 5, 'Mặc rất thoải mái', '2024-10-12 15:30:00'),
(10, 8, 5, 'Chất cotton mềm mịn', '2024-10-17 09:45:00'),

-- Product 11 reviews
(11, 9, 5, 'Áo kiểu đẹp, mặc đi làm sang', '2024-09-20 10:45:00'),
(11, 10, 4, 'Đẹp nhưng dễ nhăn', '2024-09-30 14:15:00'),
(11, 11, 5, 'Rất hài lòng', '2024-10-09 11:30:00'),
(11, 12, 5, 'Sẽ mua thêm', '2024-10-16 16:00:00'),

-- Product 12 reviews
(12, 13, 5, 'Áo croptop đẹp, form chuẩn', '2024-09-25 09:30:00'),
(12, 14, 4, 'Chất vải hơi mỏng', '2024-10-02 13:00:00'),
(12, 15, 5, 'Mặc rất xinh', '2024-10-10 10:15:00'),
(12, 16, 5, 'Giá tốt, chất lượng ok', '2024-10-15 14:45:00'),

-- Product 13 reviews
(13, 17, 4, 'Áo cardigan ấm, nhưng hơi dài tay', '2024-09-28 11:00:00'),
(13, 18, 5, 'Đẹp lắm, mặc mùa đông vừa', '2024-10-06 15:30:00'),
(13, 19, 5, 'Chất len mềm', '2024-10-14 09:00:00'),

-- Product 14 reviews
(14, 20, 5, 'Áo blazer sang trọng', '2024-09-22 10:30:00'),
(14, 21, 4, 'Đẹp nhưng hơi bó nách', '2024-10-01 13:45:00'),
(14, 22, 5, 'Mặc đi làm rất chuyên nghiệp', '2024-10-11 11:15:00'),

-- Product 15 reviews
(15, 23, 5, 'Quần jean nữ đẹp, ôm vừa', '2024-09-19 10:00:00'),
(15, 24, 5, 'Form chuẩn, chất jean tốt', '2024-09-29 14:30:00'),
(15, 25, 4, 'Đẹp nhưng hơi dài', '2024-10-08 09:45:00'),
(15, 26, 5, 'Rất hài lòng', '2024-10-15 16:00:00'),

-- Product 16 reviews
(16, 27, 5, 'Quần ống rộng đẹp, mặc mát', '2024-09-24 11:30:00'),
(16, 28, 4, 'Đẹp nhưng dễ nhăn', '2024-10-03 13:15:00'),
(16, 29, 5, 'Form đẹp, che khuyết điểm tốt', '2024-10-12 10:30:00'),

-- Product 17 reviews
(17, 30, 5, 'Quần short jean đẹp lắm', '2024-10-01 09:15:00'),
(17, 1, 4, 'Chất jean tốt', '2024-10-09 14:00:00'),
(17, 2, 5, 'Mặc mùa hè rất mát', '2024-10-16 11:45:00'),

-- Product 18 reviews
(18, 3, 5, 'Váy jean xinh, mặc đi chơi đẹp', '2024-09-26 10:45:00'),
(18, 4, 4, 'Đẹp nhưng hơi ngắn', '2024-10-05 13:30:00'),
(18, 5, 5, 'Rất thích', '2024-10-13 09:00:00'),

-- Product 19 reviews
(19, 6, 5, 'Nón đẹp, đội vừa đầu', '2024-09-30 11:00:00'),
(19, 7, 4, 'Ổn, nhưng logo hơi nhỏ', '2024-10-07 14:15:00'),
(19, 8, 5, 'Chất lượng tốt', '2024-10-14 10:30:00'),

-- Product 20 reviews
(20, 9, 5, 'Túi tote đẹp, đựng đồ nhiều', '2024-10-02 09:30:00'),
(20, 10, 5, 'Chất vải dày dặn', '2024-10-10 13:00:00'),
(20, 11, 4, 'Đẹp nhưng quai hơi ngắn', '2024-10-17 11:15:00'),
(20, 12, 5, 'Giá rẻ, chất lượng tốt', '2024-10-19 15:45:00');

-- ============================
-- 9. ORDERS (50 đơn hàng - đa dạng trạng thái)
-- ============================
INSERT INTO orders (shop_id, customer_id, customer_phone, shipping_address, date, status) VALUES
-- Tháng 9/2024
(1, 1, '0912345678', '15 Lê Lợi, Q1, TP.HCM', '2024-09-01 10:30:00', 'Completed'),
(1, 2, '0923456789', '20 Nguyễn Trãi, Q5, TP.HCM', '2024-09-02 14:15:00', 'Completed'),
(1, 3, '0934567890', '25 Võ Văn Tần, Q3, TP.HCM', '2024-09-05 09:45:00', 'Completed'),
(1, 4, '0945678901', '30 Điện Biên Phủ, Q10, TP.HCM', '2024-09-08 11:20:00', 'Completed'),
(1, 5, '0956789012', '35 Cách Mạng Tháng 8, Q3, TP.HCM', '2024-09-10 15:30:00', 'Completed'),
(1, 6, '0967890123', '40 Phan Xích Long, Phú Nhuận, TP.HCM', '2024-09-12 10:00:00', 'Completed'),
(1, 7, '0978901234', '45 Lý Thường Kiệt, Q10, TP.HCM', '2024-09-15 13:45:00', 'Completed'),
(1, 8, '0989012345', '50 Hai Bà Trưng, Q1, TP.HCM', '2024-09-18 09:15:00', 'Completed'),
(1, 9, '0990123456', '55 Trần Hưng Đạo, Q1, TP.HCM', '2024-09-20 14:30:00', 'Completed'),
(1, 10, '0901234567', '60 Pasteur, Q1, TP.HCM', '2024-09-22 11:00:00', 'Completed'),
(1, 11, '0912345679', '65 Nguyễn Thị Minh Khai, Q3, TP.HCM', '2024-09-25 16:15:00', 'Completed'),
(1, 12, '0923456780', '70 Lê Văn Sỹ, Q3, TP.HCM', '2024-09-27 10:45:00', 'Completed'),

-- Tháng 10/2024
(1, 13, '0934567891', '75 Trường Chinh, Tân Bình, TP.HCM', '2024-10-01 09:30:00', 'Completed'),
(1, 14, '0945678902', '80 Hoàng Văn Thụ, Tân Bình, TP.HCM', '2024-10-02 14:00:00', 'Completed'),
(1, 15, '0956789013', '85 Cộng Hòa, Tân Bình, TP.HCM', '2024-10-03 11:30:00', 'Completed'),
(1, 16, '0967890124', '90 Lạc Long Quân, Q11, TP.HCM', '2024-10-05 15:45:00', 'Completed'),
(1, 17, '0978901235', '95 Âu Cơ, Tân Phú, TP.HCM', '2024-10-07 10:15:00', 'Completed'),
(1, 18, '0989012346', '100 Lũy Bán Bích, Q11, TP.HCM', '2024-10-08 13:20:00', 'Completed'),
(1, 19, '0990123457', '105 Nguyễn Văn Cừ, Q5, TP.HCM', '2024-10-10 09:00:00', 'Completed'),
(1, 20, '0901234568', '110 Hùng Vương, Q5, TP.HCM', '2024-10-12 14:30:00', 'Completed'),
(1, 21, '0912345680', '115 Hậu Giang, Q6, TP.HCM', '2024-10-14 11:45:00', 'Completed'),
(1, 22, '0923456781', '120 Minh Phụng, Q6, TP.HCM', '2024-10-15 16:00:00', 'Completed'),
(1, 23, '0934567892', '125 Phạm Văn Đồng, Thủ Đức, TP.HCM', '2024-10-17 10:30:00', 'Completed'),
(1, 24, '0945678903', '130 Võ Văn Ngân, Thủ Đức, TP.HCM', '2024-10-18 13:15:00', 'Completed'),
(1, 25, '0956789014', '135 Kha Vạn Cân, Thủ Đức, TP.HCM', '2024-10-19 09:45:00', 'Completed'),

-- Tháng 11/2024 - Đơn gần đây với nhiều trạng thái khác nhau
(1, 26, '0967890125', '140 Quang Trung, Gò Vấp, TP.HCM', '2024-11-01 10:00:00', 'Completed'),
(1, 27, '0978901236', '145 Nguyễn Oanh, Gò Vấp, TP.HCM', '2024-11-02 14:15:00', 'Completed'),
(1, 28, '0989012347', '150 Phan Văn Trị, Gò Vấp, TP.HCM', '2024-11-03 11:30:00', 'Completed'),
(1, 29, '0990123458', '155 Lê Đức Thọ, Gò Vấp, TP.HCM', '2024-11-04 15:45:00', 'Completed'),
(1, 30, '0901234569', '160 Nguyễn Thái Sơn, Gò Vấp, TP.HCM', '2024-11-05 09:15:00', 'Completed'),
(1, 1, '0912345678', '15 Lê Lợi, Q1, TP.HCM', '2024-11-06 13:00:00', 'Completed'),
(1, 2, '0923456789', '20 Nguyễn Trãi, Q5, TP.HCM', '2024-11-07 10:45:00', 'Completed'),
(1, 3, '0934567890', '25 Võ Văn Tần, Q3, TP.HCM', '2024-11-08 14:30:00', 'Completed'),
(1, 4, '0945678901', '30 Điện Biên Phủ, Q10, TP.HCM', '2024-11-09 11:15:00', 'Completed'),
(1, 5, '0956789012', '35 Cách Mạng Tháng 8, Q3, TP.HCM', '2024-11-10 16:00:00', 'Completed'),

-- Đơn đang xử lý (trạng thái khác nhau)
(1, 6, '0967890123', '40 Phan Xích Long, Phú Nhuận, TP.HCM', '2024-11-11 10:30:00', 'Delivering'),
(1, 7, '0978901234', '45 Lý Thường Kiệt, Q10, TP.HCM', '2024-11-11 14:00:00', 'Delivering'),
(1, 8, '0989012345', '50 Hai Bà Trưng, Q1, TP.HCM', '2024-11-12 09:30:00', 'Delivering'),
(1, 9, '0990123456', '55 Trần Hưng Đạo, Q1, TP.HCM', '2024-11-12 13:45:00', 'Processing'),
(1, 10, '0901234567', '60 Pasteur, Q1, TP.HCM', '2024-11-13 11:00:00', 'Processing'),
(1, 11, '0912345679', '65 Nguyễn Thị Minh Khai, Q3, TP.HCM', '2024-11-13 15:15:00', 'Processing'),
(1, 12, '0923456780', '70 Lê Văn Sỹ, Q3, TP.HCM', '2024-11-14 10:00:00', 'Paid'),
(1, 13, '0934567891', '75 Trường Chinh, Tân Bình, TP.HCM', '2024-11-14 14:30:00', 'Paid'),
(1, 14, '0945678902', '80 Hoàng Văn Thụ, Tân Bình, TP.HCM', '2024-11-15 09:00:00', 'Pending_Transfer'),
(1, 15, '0956789013', '85 Cộng Hòa, Tân Bình, TP.HCM', '2024-11-15 11:30:00', 'Pending_Transfer'),

-- Đơn huỷ và thất bại
(1, 16, '0967890124', '90 Lạc Long Quân, Q11, TP.HCM', '2024-11-10 10:00:00', 'Cancelled'),
(1, 17, '0978901235', '95 Âu Cơ, Tân Phú, TP.HCM', '2024-11-12 14:00:00', 'Cancelled'),
(1, 18, '0989012346', '100 Lũy Bán Bích, Q11, TP.HCM', '2024-11-08 11:00:00', 'Failed');

-- ============================
-- 10. ORDER ITEMS (Chi tiết đơn hàng)
-- ============================
INSERT INTO order_items (order_id, product_id, quantity, price, product_name, product_color, product_size) VALUES
-- Order 1: 2 sản phẩm
(1, 1, 2, 199000, 'Áo Thun Nam Basic', 'Đen', 'L'),
(1, 6, 1, 400000, 'Quần Jean Nam Slim Fit', 'Xanh Đậm', '31'),

-- Order 2: 1 sản phẩm
(2, 2, 1, 350000, 'Áo Sơ Mi Nam Công Sở', 'Trắng', 'L'),

-- Order 3: 3 sản phẩm
(3, 10, 2, 199000, 'Áo Thun Nữ Form Rộng', 'Trắng', 'M'),
(3, 15, 1, 380000, 'Quần Jean Nữ Skinny', 'Xanh Đậm', '27'),
(3, 20, 1, 120000, 'Túi Tote Canvas', 'Be', 'Freesize'),

-- Order 4: 2 sản phẩm
(4, 3, 1, 250000, 'Áo Polo Nam', 'Xanh', 'M'),
(4, 8, 2, 180000, 'Quần Short Nam', 'Đen', 'L'),

-- Order 5: 1 sản phẩm
(5, 5, 1, 380000, 'Áo Hoodie Nam', 'Đen', 'XL'),

-- Order 6: 2 sản phẩm
(6, 7, 1, 320000, 'Quần Kaki Nam', 'Be', '30'),
(6, 19, 1, 180000, 'Nón Snapback', 'Đen', 'Freesize'),

-- Order 7: 3 sản phẩm
(7, 11, 1, 280000, 'Áo Kiểu Nữ Công Sở', 'Trắng', 'M'),
(7, 16, 1, 350000, 'Quần Ống Rộng Nữ', 'Đen', 'M'),
(7, 12, 2, 150000, 'Áo Croptop Nữ', 'Trắng', 'S'),

-- Order 8: 1 sản phẩm
(8, 4, 1, 450000, 'Áo Khoác Jean Nam', 'Xanh Đậm', 'L'),

-- Order 9: 2 sản phẩm
(9, 9, 1, 350000, 'Quần Jogger Nam', 'Xám', 'L'),
(9, 1, 1, 199000, 'Áo Thun Nam Basic', 'Trắng', 'M'),

-- Order 10: 2 sản phẩm
(10, 14, 1, 550000, 'Áo Blazer Nữ', 'Đen', 'M'),
(10, 15, 1, 380000, 'Quần Jean Nữ Skinny', 'Đen', '28'),

-- Order 11: 3 sản phẩm
(11, 10, 1, 199000, 'Áo Thun Nữ Form Rộng', 'Hồng', 'L'),
(11, 17, 1, 220000, 'Quần Short Jean Nữ', 'Xanh', 'M'),
(11, 20, 1, 120000, 'Túi Tote Canvas', 'Trắng', 'Freesize'),

-- Order 12: 1 sản phẩm
(12, 2, 2, 350000, 'Áo Sơ Mi Nam Công Sở', 'Xanh Dương', 'XL'),

-- Order 13: 2 sản phẩm
(13, 6, 1, 400000, 'Quần Jean Nam Slim Fit', 'Xanh Nhạt', '32'),
(13, 3, 1, 250000, 'Áo Polo Nam', 'Đỏ', 'L'),

-- Order 14: 1 sản phẩm
(14, 13, 1, 320000, 'Áo Khoác Cardigan Nữ', 'Be', 'Freesize'),

-- Order 15: 2 sản phẩm
(15, 5, 1, 380000, 'Áo Hoodie Nam', 'Xám', 'L'),
(15, 8, 1, 180000, 'Quần Short Nam', 'Xám', 'M'),

-- Order 16: 3 sản phẩm
(16, 1, 1, 199000, 'Áo Thun Nam Basic', 'Xanh Navy', 'XL'),
(16, 7, 1, 320000, 'Quần Kaki Nam', 'Xám', '31'),
(16, 19, 1, 180000, 'Nón Snapback', 'Trắng', 'Freesize'),

-- Order 17: 1 sản phẩm
(17, 18, 1, 280000, 'Váy Jean Nữ', 'Xanh Đậm', 'M'),

-- Order 18: 2 sản phẩm
(18, 11, 1, 280000, 'Áo Kiểu Nữ Công Sở', 'Hồng Nhạt', 'L'),
(18, 15, 1, 380000, 'Quần Jean Nữ Skinny', 'Xanh Nhạt', '29'),

-- Order 19: 2 sản phẩm
(19, 12, 2, 150000, 'Áo Croptop Nữ', 'Đen', 'M'),
(19, 17, 1, 220000, 'Quần Short Jean Nữ', 'Đen', 'L'),

-- Order 20: 1 sản phẩm
(20, 4, 1, 450000, 'Áo Khoác Jean Nam', 'Xanh Nhạt', 'XL'),

-- Order 21: 3 sản phẩm
(21, 10, 2, 199000, 'Áo Thun Nữ Form Rộng', 'Be', 'S'),
(21, 16, 1, 350000, 'Quần Ống Rộng Nữ', 'Be', 'L'),
(21, 20, 2, 120000, 'Túi Tote Canvas', 'Đen', 'Freesize'),

-- Order 22: 2 sản phẩm
(22, 2, 1, 350000, 'Áo Sơ Mi Nam Công Sở', 'Hồng Nhạt', 'M'),
(22, 6, 1, 400000, 'Quần Jean Nam Slim Fit', 'Đen', '30'),

-- Order 23: 1 sản phẩm
(23, 9, 2, 350000, 'Quần Jogger Nam', 'Đen', 'XL'),

-- Order 24: 2 sản phẩm
(24, 3, 1, 250000, 'Áo Polo Nam', 'Đen', 'XL'),
(24, 8, 1, 180000, 'Quần Short Nam', 'Xanh', 'XL'),

-- Order 25: 3 sản phẩm
(25, 5, 1, 380000, 'Áo Hoodie Nam', 'Xanh Rêu', 'M'),
(25, 7, 1, 320000, 'Quần Kaki Nam', 'Xanh Navy', '32'),
(25, 19, 1, 180000, 'Nón Snapback', 'Xanh', 'Freesize'),

-- Order 26: 2 sản phẩm
(26, 14, 1, 550000, 'Áo Blazer Nữ', 'Xám', 'L'),
(26, 15, 1, 380000, 'Quần Jean Nữ Skinny', 'Xanh Đậm', '26'),

-- Order 27: 1 sản phẩm
(27, 13, 1, 320000, 'Áo Khoác Cardigan Nữ', 'Xám', 'Freesize'),

-- Order 28: 2 sản phẩm
(28, 11, 1, 280000, 'Áo Kiểu Nữ Công Sở', 'Xanh Pastel', 'S'),
(28, 16, 1, 350000, 'Quần Ống Rộng Nữ', 'Xám', 'S'),

-- Order 29: 3 sản phẩm
(29, 10, 1, 199000, 'Áo Thun Nữ Form Rộng', 'Đen', 'XL'),
(29, 12, 1, 150000, 'Áo Croptop Nữ', 'Hồng', 'L'),
(29, 20, 1, 120000, 'Túi Tote Canvas', 'Trắng', 'Freesize'),

-- Order 30: 1 sản phẩm
(30, 4, 1, 450000, 'Áo Khoác Jean Nam', 'Đen', 'M'),

-- Order 31: 2 sản phẩm
(31, 1, 3, 199000, 'Áo Thun Nam Basic', 'Đen', 'L'),
(31, 6, 1, 400000, 'Quần Jean Nam Slim Fit', 'Xanh Đậm', '31'),

-- Order 32: 2 sản phẩm
(32, 2, 1, 350000, 'Áo Sơ Mi Nam Công Sở', 'Trắng', 'L'),
(32, 7, 1, 320000, 'Quần Kaki Nam', 'Đen', '30'),

-- Order 33: 3 sản phẩm
(33, 10, 1, 199000, 'Áo Thun Nữ Form Rộng', 'Trắng', 'M'),
(33, 17, 1, 220000, 'Quần Short Jean Nữ', 'Xanh', 'M'),
(33, 18, 1, 280000, 'Váy Jean Nữ', 'Xanh Nhạt', 'L'),

-- Order 34: 1 sản phẩm
(34, 5, 2, 380000, 'Áo Hoodie Nam', 'Đen', 'XL'),

-- Order 35: 2 sản phẩm
(35, 9, 1, 350000, 'Quần Jogger Nam', 'Xanh Rêu', 'L'),
(35, 3, 1, 250000, 'Áo Polo Nam', 'Trắng', 'M'),

-- Order 36: 2 sản phẩm - Đang giao
(36, 14, 1, 550000, 'Áo Blazer Nữ', 'Đen', 'M'),
(36, 15, 1, 380000, 'Quần Jean Nữ Skinny', 'Đen', '27'),

-- Order 37: 1 sản phẩm - Đang giao
(37, 4, 1, 450000, 'Áo Khoác Jean Nam', 'Xanh Đậm', 'L'),

-- Order 38: 3 sản phẩm - Đang giao
(38, 10, 1, 199000, 'Áo Thun Nữ Form Rộng', 'Hồng', 'L'),
(38, 12, 2, 150000, 'Áo Croptop Nữ', 'Trắng', 'S'),
(38, 20, 1, 120000, 'Túi Tote Canvas', 'Be', 'Freesize'),

-- Order 39: 2 sản phẩm - Đang xử lý
(39, 1, 1, 199000, 'Áo Thun Nam Basic', 'Xám', 'L'),
(39, 8, 1, 180000, 'Quần Short Nam', 'Đen', 'M'),

-- Order 40: 1 sản phẩm - Đang xử lý
(40, 6, 1, 400000, 'Quần Jean Nam Slim Fit', 'Xanh Nhạt', '32'),

-- Order 41: 2 sản phẩm - Đang xử lý
(41, 11, 1, 280000, 'Áo Kiểu Nữ Công Sở', 'Trắng', 'M'),
(41, 16, 1, 350000, 'Quần Ống Rộng Nữ', 'Đen', 'M'),

-- Order 42: 1 sản phẩm - Đã thanh toán
(42, 2, 1, 350000, 'Áo Sơ Mi Nam Công Sở', 'Xanh Dương', 'XL'),

-- Order 43: 2 sản phẩm - Đã thanh toán
(43, 5, 1, 380000, 'Áo Hoodie Nam', 'Xám', 'L'),
(43, 7, 1, 320000, 'Quần Kaki Nam', 'Be', '31'),

-- Order 44: 1 sản phẩm - Chờ thanh toán
(44, 13, 1, 320000, 'Áo Khoác Cardigan Nữ', 'Hồng', 'Freesize'),

-- Order 45: 2 sản phẩm - Chờ thanh toán
(45, 3, 1, 250000, 'Áo Polo Nam', 'Xanh', 'L'),
(45, 19, 1, 180000, 'Nón Snapback', 'Đỏ', 'Freesize'),

-- Order 46: 2 sản phẩm - Đã huỷ
(46, 10, 2, 199000, 'Áo Thun Nữ Form Rộng', 'Be', 'M'),
(46, 15, 1, 380000, 'Quần Jean Nữ Skinny', 'Xanh Đậm', '28'),

-- Order 47: 1 sản phẩm - Đã huỷ
(47, 4, 1, 450000, 'Áo Khoác Jean Nam', 'Xanh Nhạt', 'XL'),

-- Order 48: 2 sản phẩm - Thất bại
(48, 14, 1, 550000, 'Áo Blazer Nữ', 'Be', 'L'),
(48, 16, 1, 350000, 'Quần Ống Rộng Nữ', 'Be', 'L');

-- ============================
-- 11. CARTS (Giỏ hàng của một số khách)
-- ============================
INSERT INTO carts (customer_id) VALUES
(1), (2), (3), (4), (5), (6), (7), (8), (9), (10);

-- ============================
-- 12. CART ITEMS (Sản phẩm trong giỏ)
-- ============================
INSERT INTO cart_items (cart_id, product_id, quantity, color, size) VALUES
-- Cart 1
(1, 1, 2, 'Đen', 'L'),
(1, 3, 1, 'Xanh', 'M'),

-- Cart 2
(2, 10, 1, 'Trắng', 'M'),
(2, 15, 1, 'Xanh Đậm', '27'),

-- Cart 3
(3, 5, 1, 'Đen', 'XL'),

-- Cart 4
(4, 11, 1, 'Hồng Nhạt', 'M'),
(4, 16, 1, 'Đen', 'M'),
(4, 20, 1, 'Be', 'Freesize'),

-- Cart 5
(5, 2, 1, 'Trắng', 'L'),
(5, 6, 1, 'Xanh Đậm', '31'),

-- Cart 6
(6, 14, 1, 'Đen', 'M'),

-- Cart 7
(7, 4, 1, 'Xanh Đậm', 'L'),
(7, 19, 1, 'Đen', 'Freesize'),

-- Cart 8
(8, 12, 2, 'Trắng', 'S'),
(8, 17, 1, 'Xanh', 'M'),

-- Cart 9
(9, 9, 1, 'Xám', 'L'),
(9, 8, 1, 'Đen', 'L'),

-- Cart 10
(10, 13, 1, 'Be', 'Freesize'),
(10, 18, 1, 'Xanh Đậm', 'M');

-- ============================
-- 13. BANNERS
-- ============================
INSERT INTO banners (title, filename, is_active) VALUES
('Banner Khuyến Mãi Tháng 11', 'banner-khuyen-mai-11.jpg', 1),
('Banner Thời Trang Thu Đông', 'banner-thu-dong-2024.jpg', 1),
('Banner Sale 11.11', 'banner-sale-1111.jpg', 1),
('Banner Bộ Sưu Tập Mới', 'banner-bst-moi.jpg', 0);

-- ============================
-- KẾT THÚC
-- ============================



-- đổi mật khẩu seller
UPDATE seller SET password = '$2y$10$f1P.3Kx5LMS9Sxf7NAG7pOiqAOlxpDBzk8yN37NZ2.IfuhLDxlkCa' WHERE id = 1;