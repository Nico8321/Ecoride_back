<?php
require_once __DIR__ . '/../controllers/vehiculeController.php';

$controller = new VehiculeController();
$method = $_SERVER['REQUEST_METHOD'];


header('Content-Type: application/json');

if ($method === 'POST' && $uri[0] === "vehicule" && $uri[1] === 'user' && isset($uri[2])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->addVehicule($data, $uri[2]);
}
if ($method === 'DELETE' && $uri[0] === 'vehicule' && isset($uri[1]) && $uri[2] === "user" && isset($uri[3])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->deleteVehicule($uri[1], $uri[3]);
}
