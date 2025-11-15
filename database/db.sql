CREATE TABLE IF NOT EXISTS seller (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    status ENUM('open','closed','banned') DEFAULT 'open',
)

CREATE TABLE IF NOT EXISTS shops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    phone VARCHAR(20),
    status ENUM('open','closed','banned') DEFAULT 'open',
)

CREATE TABLE IF NOT EXISTS delivery_companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
)

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
)

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_id INT NOT NULL,
    category_id INT NULL,

    name VARCHAR(255) NOT NULL,
    brand VARCHAR(100) NULL,
    description TEXT,
    colors TEXT,
    sizes TEXT,
    price DECIMAL(10,2),
    original_price DECIMAL(10,2),
    stock INT,
    sold INT,
    status ENUM('active','paused','deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id),
)

CREATE TABLE product_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  filename VARCHAR(255) NOT NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
)

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    customer_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
)

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_id INT NOT NULL,                     -- để shop quản lý
    customer_phone VARCHAR(20) NOT NULL,      -- snapshot
    shipping_address VARCHAR(255) NOT NULL,     -- snapshot
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM(
        'Pending_Transfer',   -- chờ thanh toán chuyển khoản
        'Paid',               -- đã thanh toán
        'Processing',         -- đang xử lý đơn
        'Delivering',         -- đang vận chuyển
        'Pending_COD',        -- chờ thanh toán COD
        'Completed',          -- hoàn tất
        'Cancelled',          -- hủy
        'Failed'              -- thanh toán hoặc giao hàng thất bại
    )

    FOREIGN KEY (shop_id) REFERENCES shops(id)
)

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NULL,
    quantity INT DEFAULT 1 CHECK (quantity > 0),
    price DECIMAL(10,2) DEFAULT 0 CHECK (price >= 0),
    -- cần thêm snapshot khác
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)

CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('active','inactive','banned') DEFAULT 'active'
);

CREATE TABLE IF NOT EXISTS carts (
  id INT PRIMARY KEY AUTO_INCREMENT,
  customer_id INT UNSIGNED NOT NULL,

  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1 CHECK (quantity > 0) NOT NULL,
    color VARCHAR(100) NOT NULL,
    size VARCHAR(100) NOT NULL,

    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
);

CREATE TABLE IF NOT EXISTS banners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) DEFAULT NULL,
  filename VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1
);