-- Migration: Add customer_addresses table
-- Date: 2025-11-15

CREATE TABLE IF NOT EXISTS customer_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- Add delivery company and shipping fee to orders table
ALTER TABLE orders 
ADD COLUMN delivery_company_id INT NULL AFTER shipping_address,
ADD COLUMN shipping_fee DECIMAL(10,2) DEFAULT 0 AFTER delivery_company_id,
ADD FOREIGN KEY (delivery_company_id) REFERENCES delivery_companies(id);
