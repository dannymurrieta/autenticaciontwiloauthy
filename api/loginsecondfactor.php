<?php

session_start();

require('../vendor/autoload.php');

use App\Controllers\UserController;

$rawData = file_get_contents("php://input");

$data = json_decode($rawData, true);

$userController = new UserController();

$res = $userController->validateCode($data['code']);

if(!$res){
     http_response_code(400);
     echo "Codigo Incorrecto";
}else{
    // Iniciar sesión
    http_response_code(200);
}