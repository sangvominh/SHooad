# SHooad

Nền tảng bán hàng viết bằng PHP (không dùng framework), theo hướng MVC, có trang người dùng (storefront) và trang người bán (seller dashboard), sử dụng MySQL. Tài liệu này hướng dẫn cài đặt và sử dụng trên Windows với XAMPP.

## Yêu Cầu
- XAMPP (Apache + MySQL + PHP 8.x)
- Windows PowerShell (pwsh) hoặc phpMyAdmin để import SQL
- Git (tuỳ chọn)

## Cấu Trúc Dự Án (tóm tắt)
- `public/`: Web root (entry: `index.php`) – Apache nên trỏ vào thư mục này
- `app/Core/`: Core (`Database.php`, `Router.php`)
- `app/Routes/`: Tuyến đường cho `user` và `seller`
- `app/Controllers/`: Controller (`UserController`, `SellerController`)
- `app/Models/`: Model DB (`User`, `Seller`, `Shop`, `Product`, `Order`, `OrderItem`, `Banner`)
- `app/Views/`: Giao diện cho user và seller
- `database/`: Script SQL (`schema_min.sql`, `db.sql`, `test-data.sql`)

## Cài Đặt Nhanh (Windows + XAMPP)

1) Đặt mã nguồn vào htdocs của XAMPP
- Đường dẫn: `C:\xampp\htdocs\SHooad`
- Ứng dụng giả định base URL: `http://localhost/SHooad/public`

2) Cấu hình kết nối cơ sở dữ liệu
- Sửa file `app/Core/Database.php` nếu thông tin MySQL khác mặc định:
	- `host`: thường là `localhost`
	- `username`: mặc định XAMPP là `root`
	- `password`: mặc định rỗng (nếu bạn đã đặt mật khẩu, hãy cập nhật)
	- `dbname`: `SHooad`

3) Tạo database + import schema

Cách A — PowerShell (pwsh):

```pwsh
# Sửa đường dẫn MySQL nếu XAMPP của bạn cài ở nơi khác
$mysql = "C:\xampp\mysql\bin\mysql.exe"

& $mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS SHooad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Khuyến nghị: dùng schema tối giản tương thích với mã hiện tại
& $mysql -u root -p SHooad < "database\schema_min.sql"
```

Cách B — phpMyAdmin:
- Mở `http://localhost/phpmyadmin`
- Tạo database `SHooad` dùng collation `utf8mb4_unicode_ci`
- Import file `database/schema_min.sql`

Ghi chú về các file SQL:
- `database/schema_min.sql` tương thích với code hiện tại (models/views). Nên dùng file này.
- `database/db.sql` và `database/test-data.sql` có thể chứa cấu trúc cũ chưa khớp hoàn toàn với code. Chỉ dùng khi bạn biết cách điều chỉnh.

4) Khởi động dịch vụ
- Mở XAMPP Control Panel và Start Apache + MySQL

## Chạy Ứng Dụng

- Trang người dùng (storefront): `http://localhost/SHooad/public`
- Người dùng (user):
	- Đăng ký: `http://localhost/SHooad/public/user/register`
	- Đăng nhập: `http://localhost/SHooad/public/user/login`

- Người bán (seller):
	- Đăng nhập: `http://localhost/SHooad/public/seller/login`
	- Đăng ký: `http://localhost/SHooad/public/seller/signup`
	- Dashboard: `http://localhost/SHooad/public/seller/dashboard`

Sau khi đăng nhập seller, bạn sẽ thấy dashboard gồm thống kê, đơn hàng và sản phẩm. Khi bấm “Edit” ở bảng sản phẩm, URL chuyển thành `?page=product-detail&product_id=<ID>` và phần nội dung chính (main) trong dashboard được thay bằng form chỉnh sửa sản phẩm.

## Dữ Liệu Mẫu (tuỳ chọn)

Khuyến khích tạo dữ liệu qua giao diện:
- Seller: vào trang Signup để tạo `seller_account` và `shop`
- Products: thêm sản phẩm qua dashboard seller
- Users: đăng ký ở portal người dùng để mua hàng

Nếu muốn insert bằng SQL, hãy đảm bảo cột/quan hệ khớp `schema_min.sql`.

## Virtual Host (tuỳ chọn)
Muốn URL gọn (không `/SHooad/public`), tạo vhost trỏ DocumentRoot tới thư mục `public/`.

Ví dụ Apache vhost (`C:\xampp\apache\conf\extra\httpd-vhosts.conf`):
```
<VirtualHost *:80>
		ServerName shooad.local
		DocumentRoot "C:/xampp/htdocs/SHooad/public"
		<Directory "C:/xampp/htdocs/SHooad/public">
				AllowOverride All
				Require all granted
		</Directory>
		ErrorLog "logs/shooad-error.log"
		CustomLog "logs/shooad-access.log" common
</VirtualHost>
```
Thêm vào hosts (`C:\Windows\System32\drivers\etc\hosts`):
```
127.0.0.1 shooad.local
```
Truy cập: `http://shooad.local/`

## Khắc Phục Sự Cố
- 404 khi điều hướng: đảm bảo bạn truy cập `.../public/...` và thư mục dự án tên `SHooad`. Router mong đợi dạng `.../SHooad/public/<module>`.
- Lỗi kết nối DB: kiểm tra `app/Core/Database.php` và xác nhận database `SHooad` đã được tạo.
- Lỗi import SQL:
	- Ưu tiên `schema_min.sql`. Nếu dùng `db.sql`/`test-data.sql`, có thể cần chỉnh cột theo models hiện tại (`Product`, `Order`, `OrderItem`, `Seller`, `Shop`, `User`, `Banner`).
- Phân biệt hoa/thường trên Linux/Mac: có thể cần chỉnh lại đường dẫn trong `require_once` cho khớp tên file.

## File Quan Trọng
- `public/index.php`: bật error reporting, session, và nạp `Core/Router.php`
- `app/Core/Router.php`: phân tích URL và điều phối tới `app/Routes`
- `app/Controllers/SellerController.php`: đăng nhập/đăng ký seller, dashboard, orders, product detail
- `app/Models/Product.php`: thao tác sản phẩm dùng ở trang seller

## Ghi Chú Bảo Mật
- User password đã hash (`password_hash`) nhưng dữ liệu seller mẫu (nếu có) có thể chưa; trong môi trường thật, luôn hash.
- Cần bổ sung CSRF token cho form trước khi triển khai production.

## Giấy Phép
Sử dụng nội bộ/dự án. Chưa chỉ định license.
