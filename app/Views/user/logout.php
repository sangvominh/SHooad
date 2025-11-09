<?php
session_start();
if (isset($_SESSION['user'])) {
    session_destroy();
    header('Location: /SHooad/public');
    exit();
} else {
    header('Location: /SHooad/public');
    exit();
}
?>
