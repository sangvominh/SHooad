<?php
session_start();
if (isset($_SESSION['customer_id'])) {
    session_destroy();
    header('Location: /SHooad/public');
    exit();
} else {
    header('Location: /SHooad/public');
    exit();
}
?>
