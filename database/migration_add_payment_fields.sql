-- Migration: Add payment method and status fields to orders table
-- Date: 2025-11-16

-- Add payment_method column
ALTER TABLE orders 
ADD COLUMN payment_method ENUM('cod', 'online') DEFAULT 'cod' AFTER status;

-- Add payment_status column
ALTER TABLE orders 
ADD COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending' AFTER payment_method;

-- Update existing orders: COD orders are considered paid on delivery
UPDATE orders 
SET payment_method = 'cod', 
    payment_status = CASE 
        WHEN status = 'completed' THEN 'paid'
        ELSE 'pending'
    END
WHERE payment_method IS NULL OR payment_method = 'cod';
