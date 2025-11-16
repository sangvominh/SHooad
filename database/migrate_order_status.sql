-- ============================================================
-- COMPREHENSIVE ORDER STATUS MIGRATION
-- Date: 2025-11-16
-- Purpose: Unify all order statuses to lowercase format
-- ============================================================

-- BACKUP WARNING: Consider backing up your orders table before running!
-- Example: mysqldump -u root -p SHooad orders > orders_backup.sql

-- ============================================================
-- STEP 1: Check current status distribution
-- ============================================================
SELECT 'Current Order Status Distribution:' as info;
SELECT status, COUNT(*) as count 
FROM orders 
GROUP BY status 
ORDER BY count DESC;

-- ============================================================
-- STEP 2: Modify ENUM to support both old and new values
-- ============================================================
ALTER TABLE orders MODIFY COLUMN status ENUM(
    'pending', 'processing', 'delivering', 'completed', 'cancelled', 'failed',
    'Pending_Transfer', 'Pending_COD', 'Pending', 'Paid', 
    'Processing', 'Delivering', 'Completed', 'Cancelled', 'Failed'
) DEFAULT 'pending';

SELECT 'ENUM updated to support both old and new values' as info;

-- ============================================================
-- STEP 3: Convert all existing orders to lowercase
-- ============================================================

-- Convert all pending-like statuses
UPDATE orders SET status = 'pending' 
WHERE status IN ('Pending_Transfer', 'Pending_COD', 'Pending', 'Paid');

SELECT 'Converted pending statuses' as info;

-- Convert Processing
UPDATE orders SET status = 'processing' 
WHERE status = 'Processing';

SELECT 'Converted processing statuses' as info;

-- Convert Delivering
UPDATE orders SET status = 'delivering' 
WHERE status = 'Delivering';

SELECT 'Converted delivering statuses' as info;

-- Convert Completed
UPDATE orders SET status = 'completed' 
WHERE status = 'Completed';

SELECT 'Converted completed statuses' as info;

-- Convert Cancelled
UPDATE orders SET status = 'cancelled' 
WHERE status = 'Cancelled';

SELECT 'Converted cancelled statuses' as info;

-- Convert Failed
UPDATE orders SET status = 'failed' 
WHERE status = 'Failed';

SELECT 'Converted failed statuses' as info;

-- ============================================================
-- STEP 4: Verify all conversions completed
-- ============================================================
SELECT 'Checking for unconverted orders...' as info;
SELECT COUNT(*) as unconverted_count 
FROM orders 
WHERE status NOT IN ('pending', 'processing', 'delivering', 'completed', 'cancelled', 'failed');

-- Display any problematic records
SELECT 'Orders with invalid status (if any):' as info;
SELECT id, status, date 
FROM orders 
WHERE status NOT IN ('pending', 'processing', 'delivering', 'completed', 'cancelled', 'failed')
LIMIT 10;

-- ============================================================
-- STEP 5: Remove old ENUM values (final schema)
-- ============================================================
ALTER TABLE orders MODIFY COLUMN status ENUM(
    'pending', 'processing', 'delivering', 'completed', 'cancelled', 'failed'
) DEFAULT 'pending';

SELECT 'ENUM updated to final lowercase-only values' as info;

-- ============================================================
-- STEP 6: Display new status distribution
-- ============================================================
SELECT 'New Order Status Distribution (after migration):' as info;
SELECT status, COUNT(*) as count 
FROM orders 
GROUP BY status 
ORDER BY 
    FIELD(status, 'pending', 'processing', 'delivering', 'completed', 'cancelled', 'failed'),
    count DESC;

-- ============================================================
-- STEP 7: Verify ENUM definition
-- ============================================================
SELECT 'Final ENUM definition:' as info;
SHOW COLUMNS FROM orders LIKE 'status';

-- ============================================================
-- MIGRATION COMPLETE
-- ============================================================
SELECT '
╔══════════════════════════════════════════════════════════╗
║     ORDER STATUS MIGRATION COMPLETED SUCCESSFULLY        ║
╠══════════════════════════════════════════════════════════╣
║ All order statuses have been converted to lowercase:    ║
║  • pending                                               ║
║  • processing                                            ║
║  • delivering                                            ║
║  • completed                                             ║
║  • cancelled                                             ║
║  • failed                                                ║
╠══════════════════════════════════════════════════════════╣
║ Next Steps:                                              ║
║  1. Test customer order pages                            ║
║  2. Test seller order management                         ║
║  3. Verify all status badges display correctly           ║
║  4. Check order filtering works properly                 ║
╚══════════════════════════════════════════════════════════╝
' as migration_summary;
