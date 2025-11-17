-- ============================
-- 0. COLORS & SIZES (Master data)
-- ============================
INSERT INTO colors (name, hex_code) VALUES
('Đen', '#000000'),
('Trắng', '#FFFFFF'),
('Đỏ', '#FF0000'),
('Xanh Dương', '#0000FF'),
('Xanh Navy', '#000080'),
('Xanh Lá', '#008000'),
('Vàng', '#FFFF00'),
('Cam', '#FFA500'),
('Hồng', '#FFC0CB'),
('Tím', '#800080'),
('Nâu', '#8B4513'),
('Xám', '#808080'),
('Be', '#F5F5DC'),
('Xanh Rêu', '#556B2F'),
('Hồng Nhạt', '#FFB6C1'),
('Xanh Nhạt', '#87CEEB'),
('Xanh Đậm', '#00008B');

INSERT INTO sizes (name, sort_order) VALUES
('XS', 1),
('S', 2),
('M', 3),
('L', 4),
('XL', 5),
('XXL', 6),
('XXXL', 7),
('29', 10),
('30', 11),
('31', 12),
('32', 13),
('33', 14),
('34', 15);

-- ============================
-- 1. SELLERS (2 người bán)
-- ============================
INSERT INTO sellers (name, email, phone, password, status) VALUES
('Nguyễn Văn A', 'seller1@example.com', '0901234567', '$2y$10$f1P.3Kx5LMS9Sxf7NAG7pOiqAOlxpDBzk8yN37NZ2.IfuhLDxlkCa', 'open'),
('Trần Thị B', 'seller2@example.com', '0912345678', '$2y$10$f1P.3Kx5LMS9Sxf7NAG7pOiqAOlxpDBzk8yN37NZ2.IfuhLDxlkCa', 'open');

-- ============================
-- 2. SHOPS (2 shops)
-- ============================
INSERT INTO shops (sellers_id, name, description, address, phone, status) VALUES
(1, 'Fashion House', 'Thời trang cao cấp cho giới trẻ, phong cách Hàn Quốc', '123 Nguyễn Huệ, Q1, TP.HCM', '0287654321', 'open'),
(2, 'Trendy Store', 'Quần áo thời trang đa dạng, giá cả phải chăng', '456 Lê Lợi, Q3, TP.HCM', '0287654322', 'open');

-- ============================
-- 3. DELIVERY COMPANIES
-- ============================
INSERT INTO delivery_companies (name, shipping_fee) VALUES
('Giao Hàng Nhanh', 30000),
('Giao Hàng Tiết Kiệm', 25000),
('VNPost', 35000),
('J&T Express', 28000);

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
-- 5. PRODUCTS (20 sản phẩm - chia cho 2 shop)
-- ============================
INSERT INTO products (shop_id, category_id, name, brand, description, price, original_price, stock, sold, status) VALUES
-- SHOP 1: Fashion House (10 sản phẩm - Nam)
-- Áo Nam
(1, 1, 'Áo Thun Nam Basic', 'ZARA', 'Áo thun cotton 100% thoáng mát', 199000, 299000, 0, 85, 'active'),
(1, 1, 'Áo Sơ Mi Nam Công Sở', 'H&M', 'Áo sơ mi cao cấp, form slim fit', 350000, 500000, 0, 120, 'active'),
(1, 1, 'Áo Polo Nam', 'UNIQLO', 'Áo polo có cổ, chất liệu pique', 250000, 350000, 0, 95, 'active'),
(1, 1, 'Áo Khoác Jean Nam', 'LEVI\'S', 'Áo khoác jean phong cách Hàn Quốc', 450000, 650000, 60, 45, 'active'),
(1, 1, 'Áo Hoodie Nam', 'ADIDAS', 'Áo hoodie có nón, chất nỉ dày', 380000, 550000, 90, 110, 'active'),

-- Quần Nam
(1, 2, 'Quần Jean Nam Slim Fit', 'LEVI\'S', 'Quần jean co giãn nhẹ, form slim', 400000, 600000, 0, 88, 'active'),
(1, 2, 'Quần Kaki Nam', 'ZARA', 'Quần kaki công sở, chống nhăn', 320000, 450000, 95, 72, 'active'),
(1, 2, 'Quần Short Nam', 'H&M', 'Quần short thể thao, thoáng mát', 180000, 280000, 110, 135, 'active'),
(1, 2, 'Quần Jogger Nam', 'NIKE', 'Quần jogger thể thao năng động', 350000, 500000, 75, 68, 'active'),

-- Phụ Kiện
(1, 5, 'Nón Snapback', 'MLB', 'Nón lưỡi trai phong cách thể thao', 180000, 280000, 120, 88, 'active'),

-- SHOP 2: Trendy Store (10 sản phẩm - Nữ)
-- Áo Nữ
(2, 3, 'Áo Thun Nữ Form Rộng', 'UNIQLO', 'Áo thun oversize phong cách Hàn', 199000, 299000, 0, 165, 'active'),
(2, 3, 'Áo Kiểu Nữ Công Sở', 'MANGO', 'Áo kiểu sơ mi nữ tính, thanh lịch', 280000, 400000, 85, 92, 'active'),
(2, 3, 'Áo Croptop Nữ', 'ZARA', 'Áo croptop trẻ trung năng động', 150000, 250000, 0, 145, 'active'),
(2, 3, 'Áo Khoác Cardigan Nữ', 'H&M', 'Áo khoác len nữ mỏng nhẹ', 320000, 480000, 70, 55, 'active'),
(2, 3, 'Áo Blazer Nữ', 'ZARA', 'Áo vest nữ công sở cao cấp', 550000, 800000, 50, 38, 'active'),

-- Quần Nữ
(2, 4, 'Quần Jean Nữ Skinny', 'LEVI\'S', 'Quần jean nữ ôm dáng chuẩn', 380000, 550000, 105, 98, 'active'),
(2, 4, 'Quần Ống Rộng Nữ', 'MANGO', 'Quần ống rộng thời trang Hàn Quốc', 350000, 500000, 80, 87, 'active'),
(2, 4, 'Quần Short Jean Nữ', 'H&M', 'Quần short jean trẻ trung', 220000, 320000, 95, 112, 'active'),
(2, 4, 'Váy Jean Nữ', 'ZARA', 'Váy jean chữ A xinh xắn', 280000, 400000, 65, 73, 'active'),

-- Phụ Kiện
(2, 5, 'Túi Tote Canvas', 'MUJI', 'Túi vải canvas đa năng', 120000, 200000, 150, 132, 'active');

-- ============================
-- 6. PRODUCT VARIANTS (biến thể màu-size với SKU)
-- ============================
INSERT INTO product_variants (product_id, color_id, size_id, sku, stock, price) VALUES
-- Product 1: Áo Thun Nam Basic (4 colors x 5 sizes = 20 variants)
(1,1,2,'SKU-1-C1-S2',8,NULL),(1,1,3,'SKU-1-C1-S3',8,NULL),(1,1,4,'SKU-1-C1-S4',8,NULL),(1,1,5,'SKU-1-C1-S5',8,NULL),(1,1,6,'SKU-1-C1-S6',8,NULL),
(1,2,2,'SKU-1-C2-S2',7,NULL),(1,2,3,'SKU-1-C2-S3',7,NULL),(1,2,4,'SKU-1-C2-S4',7,NULL),(1,2,5,'SKU-1-C2-S5',7,NULL),(1,2,6,'SKU-1-C2-S6',7,NULL),
(1,12,2,'SKU-1-C12-S2',8,NULL),(1,12,3,'SKU-1-C12-S3',8,NULL),(1,12,4,'SKU-1-C12-S4',8,NULL),(1,12,5,'SKU-1-C12-S5',8,NULL),(1,12,6,'SKU-1-C12-S6',8,NULL),
(1,5,2,'SKU-1-C5-S2',7,NULL),(1,5,3,'SKU-1-C5-S3',7,NULL),(1,5,4,'SKU-1-C5-S4',7,NULL),(1,5,5,'SKU-1-C5-S5',7,NULL),(1,5,6,'SKU-1-C5-S6',7,NULL),

-- Product 2: Áo Sơ Mi Nam (3 colors x 4 sizes = 12 variants)
(2,2,3,'SKU-2-C2-S3',9,NULL),(2,2,4,'SKU-2-C2-S4',9,NULL),(2,2,5,'SKU-2-C2-S5',9,NULL),(2,2,6,'SKU-2-C2-S6',8,NULL),
(2,4,3,'SKU-2-C4-S3',9,NULL),(2,4,4,'SKU-2-C4-S4',9,NULL),(2,4,5,'SKU-2-C4-S5',9,NULL),(2,4,6,'SKU-2-C4-S6',8,NULL),
(2,15,3,'SKU-2-C15-S3',8,NULL),(2,15,4,'SKU-2-C15-S4',7,NULL),(2,15,5,'SKU-2-C15-S5',8,NULL),(2,15,6,'SKU-2-C15-S6',7,NULL),

-- Product 3: Áo Polo Nam (4 colors x 4 sizes = 16 variants)
(3,1,2,'SKU-3-C1-S2',5,NULL),(3,1,3,'SKU-3-C1-S3',5,NULL),(3,1,4,'SKU-3-C1-S4',5,NULL),(3,1,5,'SKU-3-C1-S5',5,NULL),
(3,2,2,'SKU-3-C2-S2',5,NULL),(3,2,3,'SKU-3-C2-S3',5,NULL),(3,2,4,'SKU-3-C2-S4',5,NULL),(3,2,5,'SKU-3-C2-S5',5,NULL),
(3,6,2,'SKU-3-C6-S2',5,NULL),(3,6,3,'SKU-3-C6-S3',5,NULL),(3,6,4,'SKU-3-C6-S4',5,NULL),(3,6,5,'SKU-3-C6-S5',5,NULL),
(3,3,2,'SKU-3-C3-S2',5,NULL),(3,3,3,'SKU-3-C3-S3',5,NULL),(3,3,4,'SKU-3-C3-S4',5,NULL),(3,3,5,'SKU-3-C3-S5',5,NULL),

-- Product 4: Áo Khoác Jean Nam (3 colors x 3 sizes = 9 variants)
(4,17,3,'SKU-4-C17-S3',7,NULL),(4,17,4,'SKU-4-C17-S4',7,NULL),(4,17,5,'SKU-4-C17-S5',6,NULL),
(4,16,3,'SKU-4-C16-S3',7,NULL),(4,16,4,'SKU-4-C16-S4',7,NULL),(4,16,5,'SKU-4-C16-S5',6,NULL),
(4,1,3,'SKU-4-C1-S3',7,NULL),(4,1,4,'SKU-4-C1-S4',7,NULL),(4,1,5,'SKU-4-C1-S5',6,NULL),

-- Product 5: Áo Hoodie Nam (3 colors x 4 sizes = 12 variants)
(5,1,3,'SKU-5-C1-S3',8,NULL),(5,1,4,'SKU-5-C1-S4',8,NULL),(5,1,5,'SKU-5-C1-S5',7,NULL),(5,1,6,'SKU-5-C1-S6',7,NULL),
(5,12,3,'SKU-5-C12-S3',8,NULL),(5,12,4,'SKU-5-C12-S4',8,NULL),(5,12,5,'SKU-5-C12-S5',7,NULL),(5,12,6,'SKU-5-C12-S6',7,NULL),
(5,14,3,'SKU-5-C14-S3',8,NULL),(5,14,4,'SKU-5-C14-S4',7,NULL),(5,14,5,'SKU-5-C14-S5',8,NULL),(5,14,6,'SKU-5-C14-S6',7,NULL),

-- Product 6: Quần Jean Nam (3 colors x 6 sizes = 18 variants)
(6,17,8,'SKU-6-C17-S8',7,NULL),(6,17,9,'SKU-6-C17-S9',7,NULL),(6,17,10,'SKU-6-C17-S10',7,NULL),(6,17,11,'SKU-6-C17-S11',7,NULL),(6,17,12,'SKU-6-C17-S12',6,NULL),(6,17,13,'SKU-6-C17-S13',6,NULL),
(6,16,8,'SKU-6-C16-S8',7,NULL),(6,16,9,'SKU-6-C16-S9',7,NULL),(6,16,10,'SKU-6-C16-S10',7,NULL),(6,16,11,'SKU-6-C16-S11',7,NULL),(6,16,12,'SKU-6-C16-S12',6,NULL),(6,16,13,'SKU-6-C16-S13',6,NULL),
(6,1,8,'SKU-6-C1-S8',7,NULL),(6,1,9,'SKU-6-C1-S9',7,NULL),(6,1,10,'SKU-6-C1-S10',7,NULL),(6,1,11,'SKU-6-C1-S11',6,NULL),(6,1,12,'SKU-6-C1-S12',6,NULL),(6,1,13,'SKU-6-C1-S13',6,NULL),

-- Product 7: Quần Kaki Nam (4 colors x 5 sizes = 20 variants)
(7,13,8,'SKU-7-C13-S8',5,NULL),(7,13,9,'SKU-7-C13-S9',5,NULL),(7,13,10,'SKU-7-C13-S10',5,NULL),(7,13,11,'SKU-7-C13-S11',5,NULL),(7,13,12,'SKU-7-C13-S12',4,NULL),
(7,12,8,'SKU-7-C12-S8',5,NULL),(7,12,9,'SKU-7-C12-S9',5,NULL),(7,12,10,'SKU-7-C12-S10',5,NULL),(7,12,11,'SKU-7-C12-S11',5,NULL),(7,12,12,'SKU-7-C12-S12',4,NULL),
(7,5,8,'SKU-7-C5-S8',5,NULL),(7,5,9,'SKU-7-C5-S9',5,NULL),(7,5,10,'SKU-7-C5-S10',5,NULL),(7,5,11,'SKU-7-C5-S11',5,NULL),(7,5,12,'SKU-7-C5-S12',4,NULL),
(7,1,8,'SKU-7-C1-S8',5,NULL),(7,1,9,'SKU-7-C1-S9',5,NULL),(7,1,10,'SKU-7-C1-S10',4,NULL),(7,1,11,'SKU-7-C1-S11',5,NULL),(7,1,12,'SKU-7-C1-S12',4,NULL),

-- Product 8: Quần Short Nam (3 colors x 3 sizes = 9 variants)
(8,1,3,'SKU-8-C1-S3',13,NULL),(8,1,4,'SKU-8-C1-S4',12,NULL),(8,1,5,'SKU-8-C1-S5',12,NULL),
(8,12,3,'SKU-8-C12-S3',13,NULL),(8,12,4,'SKU-8-C12-S4',12,NULL),(8,12,5,'SKU-8-C12-S5',12,NULL),
(8,6,3,'SKU-8-C6-S3',12,NULL),(8,6,4,'SKU-8-C6-S4',12,NULL),(8,6,5,'SKU-8-C6-S5',12,NULL),

-- Product 9: Quần Jogger Nam (3 colors x 4 sizes = 12 variants)
(9,1,3,'SKU-9-C1-S3',7,NULL),(9,1,4,'SKU-9-C1-S4',6,NULL),(9,1,5,'SKU-9-C1-S5',6,NULL),(9,1,6,'SKU-9-C1-S6',6,NULL),
(9,12,3,'SKU-9-C12-S3',7,NULL),(9,12,4,'SKU-9-C12-S4',6,NULL),(9,12,5,'SKU-9-C12-S5',6,NULL),(9,12,6,'SKU-9-C12-S6',6,NULL),
(9,14,3,'SKU-9-C14-S3',6,NULL),(9,14,4,'SKU-9-C14-S4',6,NULL),(9,14,5,'SKU-9-C14-S5',7,NULL),(9,14,6,'SKU-9-C14-S6',6,NULL),

-- Product 10: Áo Thun Nữ (4 colors x 4 sizes = 16 variants)
(10,2,2,'SKU-10-C2-S2',9,NULL),(10,2,3,'SKU-10-C2-S3',9,NULL),(10,2,4,'SKU-10-C2-S4',9,NULL),(10,2,5,'SKU-10-C2-S5',8,NULL),
(10,1,2,'SKU-10-C1-S2',9,NULL),(10,1,3,'SKU-10-C1-S3',9,NULL),(10,1,4,'SKU-10-C1-S4',9,NULL),(10,1,5,'SKU-10-C1-S5',8,NULL),
(10,9,2,'SKU-10-C9-S2',9,NULL),(10,9,3,'SKU-10-C9-S3',9,NULL),(10,9,4,'SKU-10-C9-S4',9,NULL),(10,9,5,'SKU-10-C9-S5',8,NULL),
(10,13,2,'SKU-10-C13-S2',9,NULL),(10,13,3,'SKU-10-C13-S3',8,NULL),(10,13,4,'SKU-10-C13-S4',9,NULL),(10,13,5,'SKU-10-C13-S5',9,NULL),

-- Product 11: Áo Kiểu Nữ (2 colors x 3 sizes = 6 variants)
(11,2,2,'SKU-11-C2-S2',14,NULL),(11,2,3,'SKU-11-C2-S3',15,NULL),(11,2,4,'SKU-11-C2-S4',14,NULL),
(11,15,2,'SKU-11-C15-S2',14,NULL),(11,15,3,'SKU-11-C15-S3',14,NULL),(11,15,4,'SKU-11-C15-S4',14,NULL),

-- Product 12: Áo Croptop Nữ (4 colors x 3 sizes = 12 variants)
(12,1,2,'SKU-12-C1-S2',9,NULL),(12,1,3,'SKU-12-C1-S3',8,NULL),(12,1,4,'SKU-12-C1-S4',8,NULL),
(12,2,2,'SKU-12-C2-S2',9,NULL),(12,2,3,'SKU-12-C2-S3',8,NULL),(12,2,4,'SKU-12-C2-S4',8,NULL),
(12,6,2,'SKU-12-C6-S2',8,NULL),(12,6,3,'SKU-12-C6-S3',9,NULL),(12,6,4,'SKU-12-C6-S4',8,NULL),
(12,9,2,'SKU-12-C9-S2',8,NULL),(12,9,3,'SKU-12-C9-S3',9,NULL),(12,9,4,'SKU-12-C9-S4',8,NULL),

-- Product 13: Áo Cardigan Nữ (4 colors x 1 size = 4 variants - Freesize)
(13,13,3,'SKU-13-C13-S3',18,NULL),
(13,12,3,'SKU-13-C12-S3',17,NULL),
(13,9,3,'SKU-13-C9-S3',18,NULL),
(13,6,3,'SKU-13-C6-S3',17,NULL),

-- Product 14: Áo Blazer Nữ (3 colors x 4 sizes = 12 variants)
(14,1,2,'SKU-14-C1-S2',4,NULL),(14,1,3,'SKU-14-C1-S3',4,NULL),(14,1,4,'SKU-14-C1-S4',5,NULL),(14,1,5,'SKU-14-C1-S5',4,NULL),
(14,12,2,'SKU-14-C12-S2',4,NULL),(14,12,3,'SKU-14-C12-S3',4,NULL),(14,12,4,'SKU-14-C12-S4',5,NULL),(14,12,5,'SKU-14-C12-S5',4,NULL),
(14,13,2,'SKU-14-C13-S2',4,NULL),(14,13,3,'SKU-14-C13-S3',4,NULL),(14,13,4,'SKU-14-C13-S4',4,NULL),(14,13,5,'SKU-14-C13-S5',4,NULL),

-- Product 15: Quần Jean Nữ (3 colors x 5 sizes = 15 variants)
(15,17,8,'SKU-15-C17-S8',7,NULL),(15,17,9,'SKU-15-C17-S9',7,NULL),(15,17,10,'SKU-15-C17-S10',7,NULL),(15,17,11,'SKU-15-C17-S11',7,NULL),(15,17,12,'SKU-15-C17-S12',7,NULL),
(15,16,8,'SKU-15-C16-S8',7,NULL),(15,16,9,'SKU-15-C16-S9',7,NULL),(15,16,10,'SKU-15-C16-S10',7,NULL),(15,16,11,'SKU-15-C16-S11',7,NULL),(15,16,12,'SKU-15-C16-S12',7,NULL),
(15,1,8,'SKU-15-C1-S8',7,NULL),(15,1,9,'SKU-15-C1-S9',7,NULL),(15,1,10,'SKU-15-C1-S10',7,NULL),(15,1,11,'SKU-15-C1-S11',7,NULL),(15,1,12,'SKU-15-C1-S12',7,NULL),

-- Product 16: Quần Ống Rộng Nữ (3 colors x 3 sizes = 9 variants)
(16,1,2,'SKU-16-C1-S2',9,NULL),(16,1,3,'SKU-16-C1-S3',9,NULL),(16,1,4,'SKU-16-C1-S4',9,NULL),
(16,13,2,'SKU-16-C13-S2',9,NULL),(16,13,3,'SKU-16-C13-S3',9,NULL),(16,13,4,'SKU-16-C13-S4',9,NULL),
(16,12,2,'SKU-16-C12-S2',9,NULL),(16,12,3,'SKU-16-C12-S3',9,NULL),(16,12,4,'SKU-16-C12-S4',8,NULL),

-- Product 17: Quần Short Jean Nữ (3 colors x 4 sizes = 12 variants)
(17,4,2,'SKU-17-C4-S2',8,NULL),(17,4,3,'SKU-17-C4-S3',8,NULL),(17,4,4,'SKU-17-C4-S4',8,NULL),(17,4,5,'SKU-17-C4-S5',8,NULL),
(17,1,2,'SKU-17-C1-S2',8,NULL),(17,1,3,'SKU-17-C1-S3',8,NULL),(17,1,4,'SKU-17-C1-S4',8,NULL),(17,1,5,'SKU-17-C1-S5',8,NULL),
(17,2,2,'SKU-17-C2-S2',8,NULL),(17,2,3,'SKU-17-C2-S3',8,NULL),(17,2,4,'SKU-17-C2-S4',8,NULL),(17,2,5,'SKU-17-C2-S5',7,NULL),

-- Product 18: Váy Jean Nữ (2 colors x 3 sizes = 6 variants)
(18,17,2,'SKU-18-C17-S2',11,NULL),(18,17,3,'SKU-18-C17-S3',11,NULL),(18,17,4,'SKU-18-C17-S4',11,NULL),
(18,16,2,'SKU-18-C16-S2',11,NULL),(18,16,3,'SKU-18-C16-S3',11,NULL),(18,16,4,'SKU-18-C16-S4',10,NULL),

-- Product 19: Nón Snapback (4 colors x 1 size = 4 variants - Freesize)
(19,1,3,'SKU-19-C1-S3',30,NULL),
(19,2,3,'SKU-19-C2-S3',30,NULL),
(19,6,3,'SKU-19-C6-S3',30,NULL),
(19,3,3,'SKU-19-C3-S3',30,NULL),

-- Product 20: Túi Tote Canvas (3 colors x 1 size = 3 variants - Freesize)
(20,2,3,'SKU-20-C2-S3',50,NULL),
(20,13,3,'SKU-20-C13-S3',50,NULL),
(20,1,3,'SKU-20-C1-S3',50,NULL);

-- ============================
-- 6. PRODUCT IMAGES (3-5 ảnh/sản phẩm)
-- ============================
INSERT INTO product_images (product_id, filename) VALUES
-- Product 1
(1, 'ao-thun-nam-1.jpg'), (1, 'ao-thun-nam-2.jpg'), (1, 'ao-thun-nam-3.jpg'),
-- Product 2
(2, 'ao-somi-nam-1.webp'), (2, 'ao-somi-nam-2.webp'), (2, 'ao-somi-nam-3.webp'),
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
-- 8. REVIEWS (Đa dạng đánh giá cho các sản phẩm)
-- ============================
INSERT INTO reviews (product_id, customer_id, rating, comment, created_at) VALUES
-- Product 1: Áo Thun Nam Basic (5 reviews - rating cao)
(1, 1, 5, 'Áo đẹp lắm! Chất cotton 100% mềm mại, thoáng mát. Mặc cả ngày không bị bí. Giá 199k quá hời so với chất lượng!', '2024-10-01 10:00:00'),
(1, 3, 5, 'Mặc rất thoải mái, form chuẩn. Size M vừa khít người 65kg. Sẽ quay lại mua thêm màu khác!', '2024-10-10 09:15:00'),
(1, 7, 4, 'Áo ổn, chất liệu tốt. Trừ 1 sao vì giao hơi lâu, nhưng sản phẩm không có gì để chê', '2024-10-18 14:30:00'),
(1, 13, 5, 'Chất lượng tốt, giá hợp lý. Đã mua 3 cái khác màu rồi, đều đẹp hết!', '2024-10-25 16:45:00'),
(1, 20, 5, 'Áo basic nhưng chất lượng cao cấp. Form đẹp, may kỹ, không bị lỗi chỉ. Recommend!', '2024-11-02 11:20:00'),

-- Product 2: Áo Sơ Mi Nam Công Sở (4 reviews - mix rating)
(2, 5, 5, 'Áo sơ mi đẹp, form slim fit ôm vừa đủ. Mặc đi làm rất sang, đồng nghiệp khen nhiều!', '2024-09-20 11:00:00'),
(2, 11, 4, 'Chất vải tốt nhưng hơi dễ nhăn. Phải ủi kỹ trước khi mặc. Ngoài ra thì OK', '2024-10-01 08:30:00'),
(2, 19, 5, 'Mặc đi làm rất đẹp và lịch sự. Chất liệu cao cấp, xứng đáng 350k', '2024-10-15 15:00:00'),
(2, 25, 3, 'Áo đẹp nhưng size hơi nhỏ so với mô tả. Nên chọn size lớn hơn 1 bậc', '2024-10-28 13:45:00'),

-- Product 3: Áo Polo Nam (6 reviews)
(3, 2, 5, 'Áo polo UNIQLO chất lượng không bàn cãi! Chất pique mát mẻ, form đẹp', '2024-09-15 10:45:00'),
(3, 10, 4, 'Chất liệu tốt, mặc mát. Trừ 1 sao vì form hơi rộng một chút', '2024-09-22 14:15:00'),
(3, 15, 5, 'Rất hài lòng! Đúng chuẩn polo Nhật Bản. Giá 250k là quá rẻ', '2024-10-03 09:00:00'),
(3, 22, 4, 'Đẹp nhưng màu Navy hơi nhạt hơn ảnh một tí. Overall vẫn OK', '2024-10-12 16:30:00'),
(3, 27, 5, 'Mua cho chồng, anh ấy rất thích. Chất vải mềm, thoáng khí', '2024-10-20 10:15:00'),
(3, 29, 5, 'Polo đẹp, mặc đi chơi hoặc đi làm đều được. Versatile!', '2024-11-05 14:00:00'),

-- Product 4: Áo Khoác Jean Nam (3 reviews)
(4, 9, 5, 'Áo khoác jean LEVI\'S xịn sò! Chất jean dày dặn, wash đẹp. Mặc phong cách Hàn Quốc cực ngầu', '2024-09-10 11:30:00'),
(4, 16, 4, 'Chất jean dày dặn, form đẹp. Nhưng giá 450k hơi cao một chút', '2024-10-05 10:20:00'),
(4, 23, 5, 'Mặc rất phong cách! Khoác ngoài áo thun là đẹp ngay. Đáng tiền!', '2024-10-18 13:45:00'),

-- Product 5: Áo Hoodie Nam (5 reviews - shop 1)
(5, 6, 5, 'Áo hoodie ấm áp, chất nỉ dày dặn. Mặc mùa đông Sài Gòn vừa vặn. ADIDAS quality!', '2024-09-25 09:30:00'),
(5, 14, 5, 'Rất thích! Nón che kín, túi rộng. Sẽ mua thêm màu khác cho anh trai', '2024-10-02 14:00:00'),
(5, 21, 4, 'Đẹp và ấm nhưng hơi nặng. Mặc lâu sẽ thấy mỏi vai một chút', '2024-10-10 11:15:00'),
(5, 26, 5, 'Chất lượng tốt, logo thêu đẹp. Giá 380k so với hãng thì quá OK', '2024-10-16 15:30:00'),
(5, 30, 5, 'Hoodie đẹp nhất từng mua! Form rộng thoải mái, chất nỉ mềm không xù lông', '2024-11-01 10:00:00'),

-- Product 6: Quần Jean Nam Slim Fit (4 reviews - shop 1)
(6, 8, 5, 'Quần jean LEVI\'S không bàn cãi! Chất jean co giãn, form slim fit ôm đẹp. Người mình 70kg mặc size 31 vừa khít', '2024-09-12 10:00:00'),
(6, 12, 4, 'Chất jean tốt nhưng hơi bó đùi. Nên chọn size lên 1 nếu chân to', '2024-09-20 13:30:00'),
(6, 18, 5, 'Mặc rất vừa, đẹp! Jean co giãn thoải mái, không bó cứng. Recommend', '2024-10-01 09:45:00'),
(6, 28, 5, 'Sẽ giới thiệu cho bạn bè. Quần đẹp, giá 400k hợp lý', '2024-10-11 16:00:00'),

-- Product 7: Quần Kaki Nam (3 reviews - shop 1)
(7, 4, 4, 'Quần kaki đẹp, mặc công sở ổn. Chất vải hơi cứng lúc đầu, mặc vài lần thì mềm', '2024-09-15 11:20:00'),
(7, 17, 5, 'Chất vải mát mẻ, chống nhăn tốt. Mặc cả ngày không bị nhăn. Perfect cho dân văn phòng!', '2024-09-28 14:45:00'),
(7, 24, 5, 'Rất hài lòng! Túi sâu, may kỹ. Đã mua thêm 2 cái khác màu', '2024-10-07 10:30:00'),

-- Product 10: Áo Thun Nữ Form Rộng - SHOP 2 (6 reviews - đa dạng)
(11, 1, 5, 'Áo thun nữ form rộng siêu đẹp! Chất cotton mềm mại, mặc thoải mái cả ngày. Trendy Store ship nhanh lắm!', '2024-09-18 11:15:00'),
(11, 8, 5, 'Form oversize vừa vặn, không quá rộng. Mặc với quần jean hoặc váy đều đẹp', '2024-09-26 13:45:00'),
(11, 15, 4, 'Đẹp nhưng hơi dài. Mình cao 1m55 phải xắn lên một chút', '2024-10-04 10:00:00'),
(11, 20, 5, 'Mặc rất thoải mái! Phong cách Hàn Quốc chuẩn. Đã mua 4 màu rồi', '2024-10-12 15:30:00'),
(11, 25, 5, 'Chất cotton mềm mịn, không nhăn. Giặt nhiều lần vẫn giữ form tốt', '2024-10-17 09:45:00'),
(11, 29, 5, 'Trendy Store tuyệt vời! Áo đẹp, giá rẻ, giao hàng nhanh. 5 sao xứng đáng!', '2024-11-03 14:20:00'),

-- Product 12: Áo Kiểu Nữ Công Sở - SHOP 2 (5 reviews)
(12, 5, 5, 'Áo kiểu đẹp lắm! Mặc đi làm rất sang trọng. Đồng nghiệp hỏi mua ở đâu hoài', '2024-09-20 10:45:00'),
(12, 11, 4, 'Đẹp nhưng chất vải hơi dễ nhăn. Phải ủi trước khi mặc. Ngoài ra thì OK', '2024-09-30 14:15:00'),
(12, 19, 5, 'Rất hài lòng! Áo sơ mi nữ tính, form đẹp không bó. 280k quá rẻ', '2024-10-09 11:30:00'),
(12, 23, 5, 'Trendy Store có nhiều đồ đẹp quá! Áo này mình sẽ mua thêm màu khác', '2024-10-16 16:00:00'),
(12, 27, 4, 'Chất liệu tốt, may đẹp. Trừ 1 sao vì size hơi nhỏ', '2024-10-25 13:30:00'),

-- Product 13: Áo Croptop Nữ - SHOP 2 (4 reviews - trẻ trung)
(13, 7, 5, 'Áo croptop đẹp, form chuẩn! Mặc đi chơi, đi biển đều OK. Giá 150k quá hời', '2024-09-25 09:30:00'),
(13, 13, 3, 'Chất vải hơi mỏng, hơi lộ. Nên mặc áo lót dày hoặc bra đệm', '2024-10-02 13:00:00'),
(13, 18, 5, 'Mặc rất xinh! Form crop vừa phải, không quá ngắn. Năng động trẻ trung', '2024-10-10 10:15:00'),
(13, 22, 5, 'Giá tốt, chất lượng OK. Mua 3 màu để thay đổi phong cách', '2024-10-15 14:45:00'),

-- Product 16: Quần Jean Nữ Skinny - SHOP 2 (5 reviews)
(16, 2, 5, 'Quần jean nữ đẹp lắm! Form skinny ôm vừa vặn, tôn dáng cực kỳ. LEVI\'S chất lượng không phải bàn', '2024-09-19 10:00:00'),
(16, 10, 5, 'Form chuẩn, chất jean co giãn tốt. Size 27 vừa khít người 50kg. Perfect!', '2024-09-29 14:30:00'),
(16, 16, 4, 'Đẹp nhưng hơi dài. Mình 1m58 phải cắt bớt. Nhưng chất jean rất tốt', '2024-10-08 09:45:00'),
(16, 21, 5, 'Rất hài lòng! Trendy Store có nhiều quần jean đẹp. Mình mua thêm quần ống rộng rồi', '2024-10-15 16:00:00'),
(16, 26, 5, 'Jean skinny ôm đẹp, không bó chặt. Phối với boots hoặc sneakers đều xinh', '2024-10-28 11:20:00'),

-- Product 17: Quần Ống Rộng Nữ - SHOP 2 (4 reviews)
(17, 4, 5, 'Quần ống rộng đẹp, mặc mát mẻ! Che khuyết điểm chân to rất tốt. Recommended!', '2024-09-24 11:30:00'),
(17, 14, 4, 'Đẹp nhưng chất vải hơi dễ nhăn. Phải cẩn thận khi ngồi lâu', '2024-10-03 13:15:00'),
(17, 24, 5, 'Form đẹp, ống rộng vừa phải. Mặc đi làm hoặc đi chơi đều OK', '2024-10-12 10:30:00'),
(17, 30, 5, 'Trendy Store ship nhanh, đóng gói cẩn thận. Quần đẹp, sẽ ủng hộ shop lâu dài!', '2024-11-06 15:00:00'),

-- Product 20: Túi Tote Canvas - SHOP 2 (3 reviews)
(20, 6, 4, 'Túi canvas đẹp, đựng được nhiều đồ. Nhưng quai hơi ngắn, đeo vai hơi cao', '2024-10-17 11:15:00'),
(20, 12, 5, 'Giá rẻ, chất lượng tốt! 120k mà túi to, vải dày. Đi học đi làm đều tiện', '2024-10-19 15:45:00'),
(20, 17, 5, 'Túi MUJI style, minimalist đẹp. Trendy Store có nhiều phụ kiện hay, sẽ quay lại!', '2024-10-28 13:30:00');

-- ============================
-- 9. ORDERS (50 đơn hàng - đa dạng trạng thái)
-- ============================
INSERT INTO orders (shop_id, customer_id, customer_phone, shipping_address, date, status, payment_method, payment_status) VALUES
-- Tháng 9/2024 - Đơn COD đã hoàn thành - Mix Shop 1 & 2
(1, 1, '0912345678', '15 Lê Lợi, Q1, TP.HCM', '2024-09-01 10:30:00', 'completed', 'cod', 'paid'),
(2, 2, '0923456789', '20 Nguyễn Trãi, Q5, TP.HCM', '2024-09-02 14:15:00', 'completed', 'cod', 'paid'),
(1, 3, '0934567890', '25 Võ Văn Tần, Q3, TP.HCM', '2024-09-05 09:45:00', 'completed', 'cod', 'paid'),
(2, 4, '0945678901', '30 Điện Biên Phủ, Q10, TP.HCM', '2024-09-08 11:20:00', 'completed', 'cod', 'paid'),
(1, 5, '0956789012', '35 Cách Mạng Tháng 8, Q3, TP.HCM', '2024-09-10 15:30:00', 'completed', 'cod', 'paid'),
(2, 6, '0967890123', '40 Phan Xích Long, Phú Nhuận, TP.HCM', '2024-09-12 10:00:00', 'completed', 'online', 'paid'),
(1, 7, '0978901234', '45 Lý Thường Kiệt, Q10, TP.HCM', '2024-09-15 13:45:00', 'completed', 'cod', 'paid'),
(2, 8, '0989012345', '50 Hai Bà Trưng, Q1, TP.HCM', '2024-09-18 09:15:00', 'completed', 'online', 'paid'),
(1, 9, '0990123456', '55 Trần Hưng Đạo, Q1, TP.HCM', '2024-09-20 14:30:00', 'completed', 'cod', 'paid'),
(2, 10, '0901234567', '60 Pasteur, Q1, TP.HCM', '2024-09-22 11:00:00', 'completed', 'cod', 'paid'),
(1, 11, '0912345679', '65 Nguyễn Thị Minh Khai, Q3, TP.HCM', '2024-09-25 16:15:00', 'completed', 'online', 'paid'),
(2, 12, '0923456780', '70 Lê Văn Sỹ, Q3, TP.HCM', '2024-09-27 10:45:00', 'completed', 'cod', 'paid'),

-- Tháng 10/2024 - Mix COD và Online - Mix Shop 1 & 2
(1, 13, '0934567891', '75 Trường Chinh, Tân Bình, TP.HCM', '2024-10-01 09:30:00', 'completed', 'cod', 'paid'),
(2, 14, '0945678902', '80 Hoàng Văn Thụ, Tân Bình, TP.HCM', '2024-10-02 14:00:00', 'completed', 'online', 'paid'),
(1, 15, '0956789013', '85 Cộng Hòa, Tân Bình, TP.HCM', '2024-10-03 11:30:00', 'completed', 'cod', 'paid'),
(2, 16, '0967890124', '90 Lạc Long Quân, Q11, TP.HCM', '2024-10-05 15:45:00', 'completed', 'online', 'paid'),
(1, 17, '0978901235', '95 Âu Cơ, Tân Phú, TP.HCM', '2024-10-07 10:15:00', 'completed', 'cod', 'paid'),
(2, 18, '0989012346', '100 Lũy Bán Bích, Q11, TP.HCM', '2024-10-08 13:20:00', 'completed', 'online', 'paid'),
(1, 19, '0990123457', '105 Nguyễn Văn Cừ, Q5, TP.HCM', '2024-10-10 09:00:00', 'completed', 'cod', 'paid'),
(2, 20, '0901234568', '110 Hùng Vương, Q5, TP.HCM', '2024-10-12 14:30:00', 'completed', 'cod', 'paid'),
(1, 21, '0912345680', '115 Hậu Giang, Q6, TP.HCM', '2024-10-14 11:45:00', 'completed', 'online', 'paid'),
(2, 22, '0923456781', '120 Minh Phụng, Q6, TP.HCM', '2024-10-15 16:00:00', 'completed', 'cod', 'paid'),
(1, 23, '0934567892', '125 Phạm Văn Đồng, Thủ Đức, TP.HCM', '2024-10-17 10:30:00', 'completed', 'online', 'paid'),
(2, 24, '0945678903', '130 Võ Văn Ngân, Thủ Đức, TP.HCM', '2024-10-18 13:15:00', 'completed', 'cod', 'paid'),
(1, 25, '0956789014', '135 Kha Vạn Cân, Thủ Đức, TP.HCM', '2024-10-19 09:45:00', 'completed', 'online', 'paid'),

-- Tháng 11/2024 - Đơn gần đây với nhiều trạng thái khác nhau - Mix Shop 1 & 2
(2, 26, '0967890125', '140 Quang Trung, Gò Vấp, TP.HCM', '2024-11-01 10:00:00', 'completed', 'cod', 'paid'),
(1, 27, '0978901236', '145 Nguyễn Oanh, Gò Vấp, TP.HCM', '2024-11-02 14:15:00', 'completed', 'online', 'paid'),
(2, 28, '0989012347', '150 Phan Văn Trị, Gò Vấp, TP.HCM', '2024-11-03 11:30:00', 'completed', 'cod', 'paid'),
(1, 29, '0990123458', '155 Lê Đức Thọ, Gò Vấp, TP.HCM', '2024-11-04 15:45:00', 'completed', 'online', 'paid'),
(2, 30, '0901234569', '160 Nguyễn Thái Sơn, Gò Vấp, TP.HCM', '2024-11-05 09:15:00', 'completed', 'cod', 'paid'),
(1, 1, '0912345678', '15 Lê Lợi, Q1, TP.HCM', '2024-11-06 13:00:00', 'completed', 'online', 'paid'),
(2, 2, '0923456789', '20 Nguyễn Trãi, Q5, TP.HCM', '2024-11-07 10:45:00', 'completed', 'cod', 'paid'),
(1, 3, '0934567890', '25 Võ Văn Tần, Q3, TP.HCM', '2024-11-08 14:30:00', 'completed', 'online', 'paid'),
(2, 4, '0945678901', '30 Điện Biên Phủ, Q10, TP.HCM', '2024-11-09 11:15:00', 'completed', 'cod', 'paid'),
(1, 5, '0956789012', '35 Cách Mạng Tháng 8, Q3, TP.HCM', '2024-11-10 16:00:00', 'completed', 'online', 'paid'),

-- Đơn đang xử lý (trạng thái khác nhau) - Mix Shop 1 & 2
(2, 6, '0967890123', '40 Phan Xích Long, Phú Nhuận, TP.HCM', '2024-11-11 10:30:00', 'delivering', 'cod', 'pending'),
(1, 7, '0978901234', '45 Lý Thường Kiệt, Q10, TP.HCM', '2024-11-11 14:00:00', 'delivering', 'online', 'paid'),
(2, 8, '0989012345', '50 Hai Bà Trưng, Q1, TP.HCM', '2024-11-12 09:30:00', 'delivering', 'cod', 'pending'),
(1, 9, '0990123456', '55 Trần Hưng Đạo, Q1, TP.HCM', '2024-11-12 13:45:00', 'processing', 'online', 'paid'),
(2, 10, '0901234567', '60 Pasteur, Q1, TP.HCM', '2024-11-13 11:00:00', 'processing', 'cod', 'pending'),
(1, 11, '0912345679', '65 Nguyễn Thị Minh Khai, Q3, TP.HCM', '2024-11-13 15:15:00', 'processing', 'online', 'paid'),
(2, 12, '0923456780', '70 Lê Văn Sỹ, Q3, TP.HCM', '2024-11-14 10:00:00', 'pending', 'cod', 'pending'),
(1, 13, '0934567891', '75 Trường Chinh, Tân Bình, TP.HCM', '2024-11-14 14:30:00', 'pending', 'online', 'paid'),
(2, 14, '0945678902', '80 Hoàng Văn Thụ, Tân Bình, TP.HCM', '2024-11-15 09:00:00', 'pending', 'cod', 'pending'),
(1, 15, '0956789013', '85 Cộng Hòa, Tân Bình, TP.HCM', '2024-11-15 11:30:00', 'pending', 'online', 'paid'),

-- Đơn huỷ và thất bại - Mix Shop 1 & 2
(2, 16, '0967890124', '90 Lạc Long Quân, Q11, TP.HCM', '2024-11-10 10:00:00', 'cancelled', 'cod', 'pending'),
(1, 17, '0978901235', '95 Âu Cơ, Tân Phú, TP.HCM', '2024-11-12 14:00:00', 'cancelled', 'online', 'paid'),
(2, 18, '0989012346', '100 Lũy Bán Bích, Q11, TP.HCM', '2024-11-08 11:00:00', 'failed', 'cod', 'pending');

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
-- PRODUCT VARIANTS (với giá đa dạng để test)
-- ============================
INSERT INTO product_variants (product_id, color_id, size_id, sku, stock, price) VALUES
-- Áo Thun Nam Basic (product_id=1, base_price=199000)
(1, 1, 2, 'ATN-BLK-S', 25, 180000),  -- Đen-S: giá thấp hơn
(1, 1, 3, 'ATN-BLK-M', 30, 199000),  -- Đen-M: giá gốc
(1, 1, 4, 'ATN-BLK-L', 35, 220000),  -- Đen-L: giá cao hơn
(1, 2, 3, 'ATN-WHT-M', 20, 210000),  -- Trắng-M: giá cao hơn
(1, 2, 4, 'ATN-WHT-L', 15, NULL),    -- Trắng-L: không có giá riêng (dùng giá gốc)

-- Áo Sơ Mi Nam Công Sở (product_id=2, base_price=350000)
(2, 1, 3, 'ASM-BLK-M', 15, 320000),  -- Đen-M: giá thấp hơn
(2, 1, 4, 'ASM-BLK-L', 20, 350000),  -- Đen-L: giá gốc
(2, 1, 5, 'ASM-BLK-XL', 10, 380000), -- Đen-XL: giá cao hơn
(2, 4, 4, 'ASM-BLU-L', 12, 360000),  -- Xanh Dương-L: giá trung bình
(2, 4, 5, 'ASM-BLU-XL', 8, NULL),    -- Xanh Dương-XL: không có giá riêng

-- Áo Thun Nữ Form Rộng (product_id=11, base_price=199000)
(11, 9, 1, 'ATN-PNK-XS', 20, 170000), -- Hồng-XS: giá thấp hơn
(11, 9, 2, 'ATN-PNK-S', 25, 199000),  -- Hồng-S: giá gốc
(11, 9, 3, 'ATN-PNK-M', 30, 210000),  -- Hồng-M: giá cao hơn
(11, 2, 2, 'ATN-WHT-S', 18, 185000),  -- Trắng-S: giá trung bình
(11, 2, 3, 'ATN-WHT-M', 22, NULL),    -- Trắng-M: không có giá riêng

-- Áo Polo Nam (product_id=3, base_price=250000) - chỉ một vài variant
(3, 1, 3, 'APL-BLK-M', 15, 240000),  -- Đen-M: giá thấp hơn một chút
(3, 2, 3, 'APL-WHT-M', 12, 260000),  -- Trắng-M: giá cao hơn một chút

-- Quần Jean Nam (product_id=6, base_price=400000) - chỉ một vài variant
(6, 1, 11, 'QJN-BLK-30', 18, 380000), -- Đen-30: giá thấp hơn
(6, 1, 12, 'QJN-BLK-31', 20, 400000), -- Đen-31: giá gốc
(6, 1, 13, 'QJN-BLK-32', 15, 420000), -- Đen-32: giá cao hơn

-- Áo Croptop Nữ (product_id=13, base_price=150000) - giá thấp
(13, 1, 2, 'ACT-BLK-S', 15, 140000),  -- Đen-S: giá thấp hơn
(13, 9, 2, 'ACT-PNK-S', 12, 160000),  -- Hồng-S: giá cao hơn
(13, 2, 2, 'ACT-WHT-S', 10, NULL);    -- Trắng-S: không có giá riêng

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