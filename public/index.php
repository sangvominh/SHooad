<?php
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Database.php';

use App\Core\Router;

// Delegate routing to the Router class which handles controllers/views
Router::route();
    