<?php
require_once __DIR__ . '/../controllers/authController.php';

$controller = new authController();
$method = $_SERVER['REQUEST_METHOD'];


header('Content-Type: application/json');

if ($method === 'GET' && $uri[0] === 'auth' && $uri[1] === 'verify') {

    $controller->verifyToken();
}
