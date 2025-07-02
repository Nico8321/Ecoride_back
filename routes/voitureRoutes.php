<?php
require_once __DIR__ . '/controllers/voitureController.php';

$controller = new VoitureController();
$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

header('Content-Type: application/json');

if ($method === 'POST' && $uri[0] === 'user' && isset($uri[1]) && $uri[2] === "vehicule") {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->addVoiture($data, $uri[1]);
}
if ($method === 'DELETE' && $uri[0] === 'user' && isset($uri[1]) && $uri[2] === "vehicule" && isset($uri[3])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->deleteVoiture($uri[1], $uri[3]);
}
