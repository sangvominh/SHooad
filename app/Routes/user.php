<?php
require_once __DIR__ . '/../Controllers/UserController.php';
$user_controller = new UserController();

$path = $parts[3] ?? '';

switch ($path) {
    case '':
        require_once __DIR__ . '/../views/user/home.php';
        break;
    case 'register':
        $user_controller->register();
        break;

    case 'login':
        $user_controller->login();
        break;

    case 'logout':
        $user_controller->logout();
        break;
}
?>