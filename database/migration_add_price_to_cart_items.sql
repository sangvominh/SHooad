-- Add price column to cart_items table
ALTER TABLE cart_items ADD COLUMN price DECIMAL(10,2) DEFAULT NULL AFTER size;