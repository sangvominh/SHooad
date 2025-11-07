<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// load database
require_once __DIR__ . '/../app/Core/database.php';

// load router
require_once __DIR__ . '/../app/Core/router.php';
?>