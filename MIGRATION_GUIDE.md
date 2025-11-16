# Hướng dẫn chạy Migration - Thống nhất trạng thái đơn hàng

## Mục đích
Script này sẽ chuyển đổi tất cả giá trị trạng thái đơn hàng từ format cũ (viết hoa, có dấu gạch dưới) sang format mới (lowercase, không dấu gạch dưới).

## Trước khi chạy

### 1. Backup Database (QUAN TRỌNG!)
```bash
# Backup toàn bộ database
mysqldump -u root -p SHooad > backup_SHooad_$(date +%Y%m%d_%H%M%S).sql

# Hoặc chỉ backup bảng orders
mysqldump -u root -p SHooad orders > backup_orders_$(date +%Y%m%d_%H%M%S).sql
```

### 2. Kiểm tra kết nối database
```bash
mysql -u root -p
USE SHooad;
SELECT COUNT(*) FROM orders;
```

## Cách chạy Migration

### Option 1: MySQL Command Line (Khuyến nghị)
```bash
mysql -u root -p SHooad < database/migrate_order_status.sql
```

### Option 2: PowerShell với đường dẫn đầy đủ
```powershell
Get-Content database/migrate_order_status.sql | C:\xampp\mysql\bin\mysql.exe -u root SHooad
```

### Option 3: phpMyAdmin
1. Mở phpMyAdmin
2. Chọn database `SHooad`
3. Click tab "SQL"
4. Copy nội dung file `database/migrate_order_status.sql`
5. Paste và click "Go"

### Option 4: MySQL Workbench
1. Mở MySQL Workbench
2. Connect tới database
3. File → Open SQL Script → Chọn `migrate_order_status.sql`
4. Click Execute (⚡ icon)

## Những gì Migration sẽ làm

### Bước 1: Hiển thị trạng thái hiện tại
Xem phân bố trạng thái đơn hàng trước khi chuyển đổi

### Bước 2: Mở rộng ENUM
Thêm cả giá trị cũ và mới vào ENUM để chuyển đổi an toàn

### Bước 3: Chuyển đổi dữ liệu
```sql
Pending_Transfer → pending
Pending_COD → pending  
Paid → pending
Processing → processing
Delivering → delivering
Completed → completed
Cancelled → cancelled
Failed → failed
```

### Bước 4: Kiểm tra
Đảm bảo không có order nào bị bỏ sót

### Bước 5: Finalize Schema
Loại bỏ giá trị cũ khỏi ENUM, chỉ giữ lowercase

### Bước 6 & 7: Verification
Hiển thị kết quả sau khi chuyển đổi

## Sau khi chạy Migration

### 1. Kiểm tra kết quả
```sql
-- Xem phân bố trạng thái mới
SELECT status, COUNT(*) as count 
FROM orders 
GROUP BY status 
ORDER BY count DESC;

-- Kiểm tra có order nào lỗi không
SELECT * FROM orders 
WHERE status NOT IN ('pending', 'processing', 'delivering', 'completed', 'cancelled', 'failed')
LIMIT 10;
```

### 2. Test ứng dụng
- [ ] Truy cập trang "My Orders" của customer
- [ ] Kiểm tra các tab filter hoạt động
- [ ] Xem chi tiết đơn hàng
- [ ] Kiểm tra màu badge hiển thị đúng
- [ ] Test trang Orders của seller
- [ ] Thử update status từ seller dashboard

### 3. Clear cache (nếu có)
```bash
# Clear PHP opcache
service apache2 restart
# hoặc
service httpd restart
```

## Nếu có lỗi

### Lỗi: "Unknown column status in field list"
**Nguyên nhân**: Bảng orders không có cột status

**Giải pháp**:
```sql
ALTER TABLE orders ADD COLUMN status VARCHAR(20) DEFAULT 'pending';
```

### Lỗi: "Data truncated for column status"
**Nguyên nhân**: Có giá trị status không hợp lệ trong database

**Giải pháp**:
```sql
-- Tìm các giá trị lỗi
SELECT DISTINCT status FROM orders;

-- Set về pending cho các giá trị không hợp lệ
UPDATE orders SET status = 'pending' WHERE status NOT IN (
    'pending', 'processing', 'delivering', 'completed', 'cancelled', 'failed',
    'Pending_Transfer', 'Pending_COD', 'Pending', 'Paid', 
    'Processing', 'Delivering', 'Completed', 'Cancelled', 'Failed'
);
```

### Khôi phục từ backup
```bash
# Restore toàn bộ database
mysql -u root -p SHooad < backup_SHooad_YYYYMMDD_HHMMSS.sql

# Hoặc chỉ restore bảng orders
mysql -u root -p SHooad < backup_orders_YYYYMMDD_HHMMSS.sql
```

## Checklist hoàn thành

- [ ] Đã backup database
- [ ] Chạy migration thành công
- [ ] Kiểm tra phân bố status mới
- [ ] Test trang customer orders
- [ ] Test trang seller orders  
- [ ] Test update order status
- [ ] Badge colors hiển thị đúng
- [ ] Filter tabs hoạt động
- [ ] Không có order bị lỗi status

## Hỗ trợ

Nếu gặp vấn đề:
1. Kiểm tra log MySQL: `/var/log/mysql/error.log` (Linux) hoặc trong phpMyAdmin
2. Xem lại file `ORDER_STATUS_UNIFICATION.md` để hiểu rõ hơn
3. Restore từ backup và thử lại

## Lưu ý quan trọng

⚠️ **SAU KHI CHẠY MIGRATION**:
- Tất cả code PHP phải dùng lowercase: `'pending'`, `'processing'`, etc.
- Không dùng giá trị cũ: ~~`'Pending_Transfer'`~~, ~~`'Processing'`~~
- UI hiển thị: Dùng `ucfirst($status)` để viết hoa chữ cái đầu

✅ **Migration này an toàn** vì:
- Kiểm tra kỹ trước khi xóa
- Có backup trước khi thay đổi
- Từng bước kiểm tra validation
- Rollback dễ dàng nếu cần
