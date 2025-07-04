<?php
require_once __DIR__ . '/controllers/covoiturageController.php';

$controller = new CovoiturageController();
$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

header('Content-Type: application/json');

if ($method === 'GET' && $uri[0] === 'user' && isset($uri[1]) && $uri[2] === "covoiturage") {
    $controller->getByConducteurId($uri[1]);
}
if ($method === 'POST' && $uri[0] === 'covoiturage') {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->addCovoiturage($data);
}
if ($method === 'GET' && $uri[0] === 'covoiturage' && count($uri) === 1) {

    $filtres = [
        'depart' => $_GET['depart'] ?? null,
        'destination' => $_GET['destination'] ?? null,
        'date' => $_GET['date'] ?? null,
        'heure' => $_GET['heure'] ?? null,
        'prix' => $_GET['prix'] ?? null,
        'note' => $_GET['note'] ?? null,
        'energie' => $_GET['energie'] ?? null,
    ];
    $controller->rechercheCovoiturages($filtres);
}
