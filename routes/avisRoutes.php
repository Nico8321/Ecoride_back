<?php

require_once __DIR__ . '/../controllers/avisController.php';

$controller = new AvisController();
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

if ($method === 'GET' && $uri[0] === 'avis' && isset($_GET['utilisateur_id'])) {
    $controller->getAvisByUtilisateurId($_GET['utilisateur_id']);
} elseif ($method === 'GET' && $uri[0] === 'avis-moyenne' && isset($_GET['moyenne_id'])) {
    $controller->getMoyenneByUtilisateurId($_GET['moyenne_id']);
} elseif ($method === 'GET' && $uri[0] === 'avis' && isset($_GET['covoiturage_id'])) {
    $controller->getAvisByCovoiturageId($_GET['covoiturage_id']);
} elseif ($method === 'POST' && $uri[0] === 'avis') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->addAvis($data);
} elseif ($method === 'GET' && isset($uri[0], $uri[1]) && $uri[0] === 'avis' && $uri[1] === 'staff') {
    $controller->getAllAvisToCheck();
} elseif ($method === 'PATCH' && $uri[0] === 'avis' && isset($uri[1], $uri[2])  && $uri[2] === 'accepte') {
    $controller->validerAvis($uri[1]);
} elseif ($method === 'PATCH' && $uri[0] === 'avis' && isset($uri[1], $uri[2]) && $uri[2] === 'refus') {
    $controller->refusAvis($uri[1]);
} else {
    http_response_code(400);
    echo json_encode(["error" => "Route ou méthode non prise en charge"]);
}
