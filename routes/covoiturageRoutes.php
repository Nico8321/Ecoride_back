<?php
require_once __DIR__ . '/../controllers/covoiturageController.php';

$controller = new CovoiturageController();
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

if ($method === 'POST' && $uri[0] === 'covoiturage') {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->addCovoiturage($data);
}

if ($method === 'GET' && $uri[0] === "covoiturage" && $uri[1] === 'user' && isset($uri[2])) {
    $controller->getByConducteurId($uri[2]);
}

if ($method === 'GET' && $uri[0] === 'covoiturages' && count($uri) === 1) {

    $filtres = [
        'depart' => $_GET['depart'] ?? null,
        'arrivee' => $_GET['arrivee'] ?? null,
        'date' => $_GET['date'] ?? null,
        'heure' => $_GET['heure'] ?? null,
        'prix' => $_GET['prix'] ?? null,
        'note' => $_GET['note'] ?? null,
        'energie' => $_GET['energie'] ?? null,
        'duree' => $_GET['duree'] ?? null,
    ];
    $controller->rechercheCovoiturages($filtres);
}
if ($method === 'PATCH' && $uri[0] === 'covoiturage' && $uri[1] === 'annuler' && isset($uri[2]) && isset($uri[3])) {
    $controller->deleteCovoiturage($uri[2], $uri[3]);
}
if ($method === 'PATCH' && $uri[0] === 'covoiturage' && $uri[1] === 'demarrer' && isset($uri[2]) && isset($uri[3])) {
    $controller->demarrerCovoiturage($uri[2], $uri[3]);
}
if ($method === 'PATCH' && $uri[0] === 'covoiturage' && $uri[1] === 'terminer' && isset($uri[2]) && isset($uri[3])) {
    $controller->terminerCovoiturage($uri[2], $uri[3]);
}
