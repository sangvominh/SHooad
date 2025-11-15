-- Migration: Add shipping_fee column to delivery_companies table
-- Run this if you already have the delivery_companies table without shipping_fee column

-- Step 1: Add shipping_fee column if it doesn't exist
ALTER TABLE delivery_companies 
ADD COLUMN IF NOT EXISTS shipping_fee DECIMAL(10,2) DEFAULT 30000;

-- Step 2: Update existing records with appropriate shipping fees
UPDATE delivery_companies SET shipping_fee = 30000 WHERE name LIKE '%Nhanh%';
UPDATE delivery_companies SET shipping_fee = 25000 WHERE name LIKE '%Tiết Kiệm%';
UPDATE delivery_companies SET shipping_fee = 35000 WHERE name LIKE '%VNPost%';
UPDATE delivery_companies SET shipping_fee = 28000 WHERE name LIKE '%J&T%';
