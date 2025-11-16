-- Migration: Fix cart selection and order status
-- Date: 2025-11-16
-- Purpose: 
--   1. Set existing cart items to unselected (selected=0) by default
--   2. Update old order statuses to new lowercase format
--   3. Ensure selected column exists in cart_items

-- Step 1: Ensure selected column exists in cart_items (run if not exists)
ALTER TABLE cart_items 
ADD COLUMN IF NOT EXISTS selected TINYINT(1) DEFAULT 0 AFTER size;

-- Step 2: Set all existing cart items to unselected (user must manually select)
-- This prevents automatic selection of all items
UPDATE cart_items SET selected = 0;

-- Step 3: Update old order status values to new lowercase format
UPDATE orders SET status = 'pending' WHERE status IN ('Pending_Transfer', 'Pending_COD', 'Pending');
UPDATE orders SET status = 'processing' WHERE status = 'Processing';
UPDATE orders SET status = 'delivering' WHERE status = 'Delivering';
UPDATE orders SET status = 'completed' WHERE status = 'Completed';
UPDATE orders SET status = 'cancelled' WHERE status = 'Cancelled';
UPDATE orders SET status = 'failed' WHERE status = 'Failed';
UPDATE orders SET status = 'paid' WHERE status = 'Paid';

-- Step 4: Verify the changes
SELECT 'Cart Items Summary:' as info;
SELECT 
    COUNT(*) as total_items, 
    SUM(selected) as selected_items,
    COUNT(*) - SUM(selected) as unselected_items
FROM cart_items;

SELECT '\nOrder Status Distribution:' as info;
SELECT status, COUNT(*) as count 
FROM orders 
GROUP BY status 
ORDER BY count DESC;
