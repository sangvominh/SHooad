<?php
class AuthMiddleware {
    public static function checkSellerAuth() {
        if (!isset($_SESSION["seller_id"]) || !isset($_SESSION["shop_id"])) {
            header('Location: /SHooad/public/seller/login');
            exit();
        }
    }
}