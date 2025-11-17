# SHooad

E-commerce platform built with PHP (no framework), following MVC pattern, with customer storefront and seller dashboard, using MySQL. This document guides installation and usage on Windows with XAMPP.

## System Requirements
- XAMPP (Apache + MySQL + PHP 8.x)
- Windows PowerShell (pwsh) or phpMyAdmin for SQL import
- Web browser (Chrome, Firefox, etc.)

## Project Structure (Summary)
- `public/`: Web root directory (entry point: `index.php`) – Apache should point to this folder
- `app/Core/`: Core files (`Database.php`, `Router.php`)
- `app/Routes/`: Routes for user and seller
- `app/Controllers/`: Controllers (`UserController`, `SellerController`)
- `app/Models/`: DB Models (`Customer`, `Seller`, `Shop`, `Product`, `Order`, `OrderItem`, `Review`)
- `app/Views/`: Views for customer and seller
- `app/Services/`: Business logic services
- `app/Helpers/`: Helper functions
- `app/Languages/`: Multi-language support (en.php, vi.php)
- `database/`: SQL scripts (`db.sql`, `test-data.sql`)
- `public/assets/`: Static files (CSS, JS, images)

## Quick Installation (Windows + XAMPP)

### Step 1: Prepare Source Code
1. Clone the repository or download the source code.
2. Place the `SHooad` folder into `C:\xampp\htdocs\SHooad`
3. The application assumes base URL: `http://localhost/SHooad/public`

### Step 2: Configure Database
- Open file `app/Core/Database.php` and check connection info:
  - `host`: `localhost`
  - `username`: `root` (default XAMPP)
  - `password`: `''` (empty, update if you set a password)
  - `dbname`: `SHooad`

### Step 3: Create Database and Import Data

#### Using phpMyAdmin
1. Open `http://localhost/phpmyadmin`
2. Create a new database named `SHooad` with collation `utf8mb4_unicode_ci`
3. Select database `SHooad`, go to Import tab
4. Upload and import file `database/db.sql`

### Step 4: Start Services
- Open XAMPP Control Panel
- Start Apache and MySQL

## Running the Application

### Access the Application
- **Home Page (Customer Storefront)**: `http://localhost/SHooad/public`
- **Seller Page**: `http://localhost/SHooad/public/seller`

### Customer Accounts
- **Register**: `http://localhost/SHooad/public/user/register`
- **Login**: `http://localhost/SHooad/public/user/login`
- **Home**: After login, access pages like products, cart, checkout, orders.

### Seller Accounts
- **Register**: `http://localhost/SHooad/public/seller/signup`
- **Login**: `http://localhost/SHooad/public/seller/login`
- **Dashboard**: `http://localhost/SHooad/public/seller/dashboard`
  - View sales statistics
  - Manage products (add, edit, delete)
  - View and process orders
  - Analyze data

## Troubleshooting
- **404 Error**: Ensure accessing correct URL with `/SHooad/public/`. Check `.htaccess` in `public/`.
- **DB Connection Error**: Check info in `Database.php` and ensure MySQL is running.
- **SQL Import Error**: Ensure database collation is `utf8mb4_unicode_ci`. If foreign key errors, import `db.sql` first.
- **Images not displaying**: Check paths in `public/assets/`.
- **Session not working**: Ensure PHP has write permission to temp folder.

## Further Development
- **Add features**: Edit code in `app/Controllers/`, `app/Models/`, `app/Views/`.
- **Test**: Create test accounts and try functionalities.
- **Deploy**: Upload to server with PHP 8.x, MySQL, and configure Apache to point to `public/`.

## Security Notes
- Passwords are hashed using `password_hash()`.
- Validate input to prevent SQL injection (use prepared statements).

## License
Internal project. No specific license yet.