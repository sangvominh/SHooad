<?php
require_once __DIR__ . '/../controllers/ShipperController.php';
$shipper_controller = new ShipperController();

$path = $parts[3] ?? '';

switch ($path) {
    case (''):
    case ('dashboard'):
        $shipper_controller->dashboard();
        break;
    case ('login'):
        $shipper_controller->login();
        break;
    case ('signup'):
        $shipper_controller->signup();
        break;
    case ('logout'):
        $shipper_controller->logout();
        break;
    case ('order-detail'):
        $shipper_controller->orderDetail();
        break;
    case ('update-order-status'):
        $shipper_controller->updateOrderStatus();
        break;
    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
}
?>