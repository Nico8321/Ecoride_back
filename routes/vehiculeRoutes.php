<?php
require_once __DIR__ . '/../controllers/vehiculeController.php';
require_once __DIR__ . '/../utils/requireAuth.php';

$controller = new VehiculeController();
$method = $_SERVER['REQUEST_METHOD'];


header('Content-Type: application/json');
//ajout d'un vehicule
if ($method === 'POST' && $uri[0] === "vehicule" && $uri[1] === 'user' && isset($uri[2])) {
    checkId($uri[2]);
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->addVehicule($data, $uri[2]);
} //supression d'un vehicule
if ($method === 'DELETE' && $uri[0] === 'vehicule' && isset($uri[1], $uri[2], $uri[3]) && $uri[2] === "user") {
    checkId($uri[3]);
    $controller->deleteVehicule($uri[1], $uri[3]);
} // récuperation des vehicules
if ($method === 'GET' && $uri[0] === 'vehicule' && $uri[1] === 'user' && isset($uri[2])) {
    checkId($uri[2]);
    $controller->getVehicule($uri[2]);
}
