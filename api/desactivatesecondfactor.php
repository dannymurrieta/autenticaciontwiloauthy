<?php

session_start();

require('../vendor/autoload.php');

use App\Controllers\UserController;


$userController = new UserController();

if (!$userController->isUserLoggedIn()) {
    http_response_code(401);
    echo "No existe autenticación";
    exit;
}

$userController->desactivateSecondFactor();