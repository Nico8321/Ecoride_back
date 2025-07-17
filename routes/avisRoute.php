<?php

require_once __DIR__ . '/../controllers/avisController.php';

$controller = new AvisController();
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

if ($method === 'GET' && $uri[0] === 'avis' && isset($_GET['utilisateur_id'])) {
    $controller->getAvisByUtilisateurId($_GET['utilisateur_id']);
} elseif ($method === 'GET' && $uri[0] === 'avis' && isset($_GET['moyenne_id'])) {
    $controller->getMoyenneByUtilisateurId($_GET['moyenne_id']);
} elseif ($method === 'POST' && $uri[0] === 'avis') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->addAvis($data);
} elseif ($method === 'PUT' && $uri[0] === 'avis' && isset($_GET['id'])) {
    $controller->validerAvis($_GET['id']);
} else {
    http_response_code(400);
    echo json_encode(["error" => "Route ou méthode non prise en charge"]);
}
