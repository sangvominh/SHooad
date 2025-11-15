# Customer Code Refactoring - MVC Architecture

## Summary
Đã refactor code customer (user) để tuân thủ kiến trúc MVC tương tự như code seller, tách biệt rõ ràng giữa business logic, presentation logic và routing.

## Changes Made

### 1. Tạo Service Layer mới

#### `app/Services/User/AuthUserService.php`
- Xử lý authentication logic cho user
- Các phương thức chính:
  - `login()`: Xử lý đăng nhập
  - `register()`: Xử lý đăng ký
  - `logout()`: Xử lý đăng xuất
  - `getUserSession()`: Lấy thông tin session
  - `isLoggedIn()`: Kiểm tra trạng thái đăng nhập
- Sử dụng FlashMessageService để hiển thị thông báo

#### `app/Services/User/UserPageService.php`
- Xử lý business logic cho các trang user
- Các phương thức chính:
  - `getHomePageData()`: Lấy dữ liệu trang chủ (banners)
  - `getProductDetailData()`: Lấy chi tiết sản phẩm với xử lý colors, sizes, images
  - `getCartData()`: Placeholder cho cart logic
  - `getProductsPageData()`: Placeholder cho products logic
- Xử lý logic phức tạp như:
  - Process product images và thumbnails
  - Parse và format colors với color codes
  - Parse sizes từ comma-separated string

### 2. Refactor UserController

**Trước đây:**
- Controller trực tiếp gọi Model
- Logic nghiệp vụ trộn lẫn trong Controller
- Không có separation of concerns rõ ràng
- Sử dụng `alert()` JavaScript cho thông báo

**Sau khi refactor:**
```php
class UserController {
    private $authService;
    private $pageService;
    
    public function __construct() {
        $this->authService = new AuthUserService();
        $this->pageService = new UserPageService();
    }
    
    // Các phương thức cho từng trang
    public function home()
    public function cart()
    public function products()
    public function productDetail()
    public function register()
    public function login()
    public function logout()
}
```

### 3. Cập nhật Routes

**File: `app/Routes/user.php`**

Thêm các route mới:
- `/user` → `home()`
- `/user/cart` → `cart()`
- `/user/products` → `products()`
- `/user/product-detail` → `productDetail()`

Tất cả routes đều đi qua UserController thay vì include trực tiếp view files.

### 4. Cập nhật Middleware

**File: `app/middleware/AuthMiddleware.php`**

Thêm method `checkUserAuth()` để kiểm tra authentication cho user:
```php
public static function checkUserAuth() {
    if (!isset($_SESSION["user_id"])) {
        header('Location: /SHooad/public/user/login');
        exit();
    }
}
```

### 5. Cập nhật Views

#### `app/Views/user/home.php`
- Loại bỏ direct model calls
- Sử dụng data được truyền từ controller: `$data['banners']`

#### `app/Views/user/product-detail.php`
- Đơn giản hóa, loại bỏ ~130 dòng code xử lý logic
- Nhận dữ liệu product đã được xử lý sẵn từ controller
- Logic colors, sizes, images đã được xử lý ở service layer

#### `app/Views/user/login.php` và `register.php`
- Thêm FlashMessageService để hiển thị thông báo thành công/lỗi
- Cập nhật links để dùng public routes
- Loại bỏ JavaScript alerts

## Architecture Pattern (Giống Seller)

```
Route → Controller → Service → Model → Database
         ↓
      View (receives processed data)
```

### Benefits của kiến trúc này:

1. **Separation of Concerns**: 
   - Controller: Điều phối request/response
   - Service: Business logic
   - Model: Data access
   - View: Presentation

2. **Reusability**: 
   - Services có thể được tái sử dụng ở nhiều controller
   - Logic tập trung ở một nơi

3. **Testability**: 
   - Dễ dàng test từng layer riêng biệt
   - Mock services trong unit tests

4. **Maintainability**: 
   - Code rõ ràng, dễ bảo trì
   - Thay đổi business logic chỉ cần sửa service
   - Không phải sửa nhiều nơi

5. **Consistency**: 
   - Pattern giống nhau giữa User và Seller code
   - Dễ onboard developer mới

## Files Modified

1. `app/Controllers/UserController.php` - Refactored completely
2. `app/Routes/user.php` - Added new routes
3. `app/middleware/AuthMiddleware.php` - Added checkUserAuth()
4. `app/Views/user/home.php` - Use controller data
5. `app/Views/user/product-detail.php` - Simplified, removed logic
6. `app/Views/user/login.php` - Added flash messages
7. `app/Views/user/register.php` - Added flash messages

## Files Created

1. `app/Services/User/AuthUserService.php` - Authentication service
2. `app/Services/User/UserPageService.php` - Page logic service

## Testing Recommendations

1. Test authentication flows:
   - Register new user
   - Login with valid credentials
   - Login with invalid credentials
   - Logout

2. Test page rendering:
   - Home page with banners
   - Product detail page
   - Cart page (requires authentication)
   - Products listing page

3. Test flash messages:
   - Success message after registration
   - Error message on login failure

4. Test middleware:
   - Access cart without login → redirect to login
   - Access cart with login → display cart page

## Notes

- Cart operations (add-to-cart, update-cart, etc.) vẫn giữ nguyên vì chúng là AJAX endpoints
- Products page vẫn có logic filtering/pagination phức tạp, có thể refactor sau nếu cần
- Product model và services hiện tại chưa có method getAllProducts(), có thể thêm sau nếu cần
