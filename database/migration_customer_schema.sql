-- Migration: Update customer code to match correct schema
-- Date: 2025-11-15

-- Note: Main schema already correct in db.sql
-- This file documents optional enhancements

-- Optional: Add 'selected' column to cart_items if selection feature needed
-- ALTER TABLE cart_items ADD COLUMN selected TINYINT(1) DEFAULT 0 AFTER quantity;

-- Optional: Add timestamps to cart_items
-- ALTER TABLE cart_items ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER selected;
-- ALTER TABLE cart_items ADD COLUMN updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP AFTER created_at;

-- Optional: Add customer avatar support
-- ALTER TABLE customers ADD COLUMN avatar VARCHAR(255) NULL AFTER password;
-- ALTER TABLE customers ADD COLUMN phone VARCHAR(20) NULL AFTER email;
-- ALTER TABLE customers ADD COLUMN address VARCHAR(255) NULL AFTER phone;
-- ALTER TABLE customers ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER status;
-- ALTER TABLE customers ADD COLUMN updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP AFTER created_at;
