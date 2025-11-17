# SHooad

Nền tảng bán hàng viết bằng PHP (không dùng framework), theo hướng MVC, có trang người dùng (storefront) và trang người bán (seller dashboard), sử dụng MySQL. Tài liệu này hướng dẫn cài đặt và sử dụng trên Windows với XAMPP.

## Yêu Cầu Hệ Thống
- XAMPP (Apache + MySQL + PHP 8.x)
- Windows PowerShell (pwsh) hoặc phpMyAdmin để import SQL
- Trình duyệt web (Chrome, Firefox, etc.)

## Cấu Trúc Dự Án (tóm tắt)
- `public/`: Thư mục gốc web (entry point: `index.php`) – Apache nên trỏ vào thư mục này
- `app/Core/`: Core files (`Database.php`, `Router.php`)
- `app/Routes/`: Routes cho user và seller
- `app/Controllers/`: Controllers (`UserController`, `SellerController`)
- `app/Models/`: Models DB (`Customer`, `Seller`, `Shop`, `Product`, `Order`, `OrderItem`, `Review`)
- `app/Views/`: Views cho customer và seller
- `app/Services/`: Business logic services
- `app/Helpers/`: Helper functions
- `app/Languages/`: Multi-language support (en.php, vi.php)
- `database/`: Scripts SQL (`db.sql`, `test-data.sql`)
- `public/assets/`: Static files (CSS, JS, images)

## Cài Đặt Nhanh (Windows + XAMPP)

### Bước 1: Chuẩn Bị Mã Nguồn
1. Clone repository hoặc tải source code về.
2. Đặt thư mục `SHooad` vào `C:\xampp\htdocs\SHooad`
3. Ứng dụng giả định base URL: `http://localhost/SHooad/public`

### Bước 2: Cấu Hình Cơ Sở Dữ Liệu
- Mở file `app/Core/Database.php` và kiểm tra thông tin kết nối:
  - `host`: `localhost`
  - `username`: `root` (mặc định XAMPP)
  - `password`: `''` (rỗng, nếu bạn đã đặt mật khẩu thì cập nhật)
  - `dbname`: `Shooad`

### Bước 3: Tạo Database và Import Dữ Liệu

#### Sử Dụng phpMyAdmin
1. Mở `http://localhost/phpmyadmin`
2. Tạo database mới tên `SHooad` với collation `utf8mb4_unicode_ci`
3. Chọn database `SHooad`, vào tab Import
4. Upload và import file `database/db.sql`

### Bước 4: Khởi Động Dịch Vụ
- Mở XAMPP Control Panel
- Start Apache và MySQL

## Chạy Ứng Dụng

### Truy Cập Ứng Dụng
- **Trang chủ (Customer Storefront)**: `http://localhost/SHooad/public`
- **Trang Seller**: `http://localhost/SHooad/public/seller`

### Tài Khoản Người Dùng (Customer)
- **Đăng ký**: `http://localhost/SHooad/public/user/register`
- **Đăng nhập**: `http://localhost/SHooad/public/user/login`
- **Trang chủ**: Sau đăng nhập, truy cập các trang như sản phẩm, giỏ hàng, thanh toán, đơn hàng.

### Tài Khoản Người Bán (Seller)
- **Đăng ký**: `http://localhost/SHooad/public/seller/signup`
- **Đăng nhập**: `http://localhost/SHooad/public/seller/login`
- **Dashboard**: `http://localhost/SHooad/public/seller/dashboard`
  - Xem thống kê bán hàng
  - Quản lý sản phẩm (thêm, sửa, xóa)
  - Xem và xử lý đơn hàng
  - Phân tích dữ liệu

## Khắc Phục Sự Cố
- **Lỗi 404**: Đảm bảo truy cập đúng URL với `/SHooad/public/`. Kiểm tra file `.htaccess` trong `public/`.
- **Lỗi kết nối DB**: Kiểm tra thông tin trong `db.php` và đảm bảo MySQL đang chạy.
- **Lỗi import SQL**: Đảm bảo database collation là `utf8mb4_unicode_ci`. Nếu gặp lỗi foreign key, import `db.sql` trước `test-data.sql`.
- **Hình ảnh không hiển thị**: Kiểm tra đường dẫn trong `public/assets/`.
- **Session không hoạt động**: Đảm bảo PHP có quyền ghi vào thư mục temp.

## Phát Triển Thêm
- **Thêm tính năng**: Sửa code trong `app/Controllers/`, `app/Models/`, `app/Views/`.
- **Test**: Tạo tài khoản test và thử các chức năng.
- **Deploy**: Upload lên server với PHP 8.x, MySQL, và cấu hình Apache trỏ đến `public/`.

## Ghi Chú Bảo Mật
- Password được hash bằng `password_hash()`.
- Validate input để tránh SQL injection (dùng prepared statements).

## Giấy Phép
Dự án nội bộ. Chưa có license cụ thể.
