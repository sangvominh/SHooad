<?php
class AuthMiddleware {
    public static function checkSellerAuth() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["seller_id"]) || !isset($_SESSION["shop_id"])) {
            header('Location: /SHooad/public/seller/login');
            exit();
        }
    }

    public static function checkUserAuth() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["customer_id"])) {
            header('Location: /SHooad/public/customer/login');
            exit();
        }
    }
}