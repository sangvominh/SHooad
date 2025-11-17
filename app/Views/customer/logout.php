<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Clear all session data
session_unset();
session_destroy();

// Start new session for flash message
session_start();
require_once __DIR__ . '/../../Services/FlashMessageService.php';
FlashMessageService::setFlashMessage('success', 'Đăng xuất thành công!');

// Redirect to home
header('Location: /SHooad/public/customer');
exit();
?>
