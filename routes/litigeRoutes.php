<?php

require_once __DIR__ . '/../controllers/litigeController.php';

$controller = new LitigeController();
$method = $_SERVER['REQUEST_METHOD'];


header('Content-Type: application/json');


if ($method === 'POST' && $uri[0] === 'litige' && isset($uri[1], $uri[2])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->createLitige($uri[1], $uri[2], $data);
}
