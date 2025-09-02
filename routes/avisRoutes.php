<?php

require_once __DIR__ . '/../controllers/avisController.php';
require_once __DIR__ . '/../utils/requireAuth.php';

$controller = new AvisController();
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');
//recuperation des avis d'un utilisateur
if ($method === 'GET' && $uri[0] === 'avis' && isset($_GET['utilisateur_id'])) {
    $controller->getAvisByUtilisateurId($_GET['utilisateur_id']);
}
// recuperation moyenne conducteur 
elseif ($method === 'GET' && $uri[0] === 'avis-moyenne' && isset($_GET['moyenne_id'])) {
    $controller->getMoyenneByUtilisateurId($_GET['moyenne_id']);
}
// recuperation des avis par covoiturage
elseif ($method === 'GET' && $uri[0] === 'avis' && isset($_GET['covoiturage_id'])) {
    $controller->getAvisByCovoiturageId($_GET['covoiturage_id']);
}
// ajout d'un avis d'un passager
elseif ($method === 'POST' && $uri[0] === 'avis' && isset($_GET['utilisateur_id'])) {
    checkId($_GET['utilisateur_id']);
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->addAvis($data, $_GET['utilisateur_id']);
}
//recuperation des avis a valider
elseif ($method === 'GET' && isset($uri[0], $uri[1]) && $uri[0] === 'avis' && $uri[1] === 'staff') {
    requireRole(2);
    $controller->getAllAvisToCheck();
}
//acceptation d'un avis
elseif ($method === 'PATCH' && $uri[0] === 'avis' && isset($uri[1], $uri[2])  && $uri[2] === 'accepte') {
    requireRole(2);
    $controller->validerAvis($uri[1]);
}
//refus d'un avis
elseif ($method === 'PATCH' && $uri[0] === 'avis' && isset($uri[1], $uri[2]) && $uri[2] === 'refus') {
    requireRole(2);
    $controller->refusAvis($uri[1]);
} else {
    http_response_code(400);
    echo json_encode(["error" => "Route ou méthode non prise en charge"]);
}
