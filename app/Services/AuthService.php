<?php
class AuthService {
    public static function setSellerAuth($seller_id, $shop_id) {
        $_SESSION['seller_id'] = $seller_id;
        $_SESSION['shop_id'] = $shop_id;
    }

    public static function getSellerAuth() {
        return [
            'seller_id' => $_SESSION['seller_id'] ?? null,
            'shop_id' => $_SESSION['shop_id'] ?? null
        ];
    }

    public static function logout() {
        session_unset();
        session_destroy();
    }
}