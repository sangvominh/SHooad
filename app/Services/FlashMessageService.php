<?php
class FlashMessageService {
    public static function setFlashMessage($key, $message) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash_messages'][$key] = $message;
    }

    public static function getFlashMessage($key) {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['flash_messages'][$key])) {
            $message = $_SESSION['flash_messages'][$key];
            unset($_SESSION['flash_messages'][$key]);
            return $message;
        }
        return null;
    }
}