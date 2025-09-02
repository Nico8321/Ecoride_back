<?php

require_once __DIR__ . '/../controllers/litigeController.php';
require_once __DIR__ . '/../utils/requireAuth.php';

$controller = new LitigeController();
$method = $_SERVER['REQUEST_METHOD'];


header('Content-Type: application/json');


if ($method === 'POST' && $uri[0] === 'litige' && isset($uri[1], $uri[2])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->createLitige($uri[1], $uri[2], $data);
}
if ($method === 'PATCH' && $uri[0] === 'litige' && isset($uri[1], $uri[2]) && $uri[2] === 'cloture') {
    requireRole(2);
    $controller->cloturerLitige($uri[1]);
}
if ($method === 'GET' && $uri[0] === 'litige' && isset($uri[1]) && !isset($uri[2])) {
    requireRole(2);
    $controller->getLitigeById($uri[1]);
}
if ($method === 'GET' && $uri[0] === 'litiges' && !isset($uri[1])) {
    requireRole(2);
    $controller->getAllLitiges();
}
if ($method === 'PATCH' && $uri[0] === 'litige' && $uri[1] === 'note' && isset($uri[2])) {
    requireRole(2);
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->addNoteLitige($uri[2], $data);
}
