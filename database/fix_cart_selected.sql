-- Fix: Set all existing cart items to selected=1
-- This will fix the "Bạn chưa chọn sản phẩm nào để đặt hàng" error

UPDATE cart_items SET selected = 1 WHERE selected = 0;

-- Verify the changes
SELECT COUNT(*) as total_items, 
       SUM(selected) as selected_items,
       COUNT(*) - SUM(selected) as unselected_items
FROM cart_items;
