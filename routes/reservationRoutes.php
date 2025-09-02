<?php
require_once __DIR__ . '/../controllers/reservationController.php';
require_once __DIR__ . '/../utils/requireAuth.php';

$controller = new ReservationController();
$method = $_SERVER['REQUEST_METHOD'];


header('Content-Type: application/json');

if ($method === 'POST' && $uri[0] === 'reservation' && $uri[1] === 'covoiturage' && isset($uri[2]) && isset($uri[3])) {
    checkId($uri[3]);
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->createReservation($data, $uri[2], $uri[3]);
}
if ($method === 'GET' && $uri[0] === 'reservation' && $uri[1] === 'utilisateur' && isset($uri[2])) {
    checkId($uri[2]);
    $controller->getReservationByUser($uri[2]);
}
if ($method === 'GET' && $uri[0] === 'reservations' && isset($uri[1]) && isset($uri[2])) {
    checkId($uri[2]);
    $controller->getReservationByCovoiturageId($uri[1], $uri[2]);
}
if ($method === 'DELETE' && $uri[0] === 'reservation' && $uri[1] === 'delete' && isset($uri[2]) && isset($uri[3])) {
    checkId($uri[3]);
    $controller->deleteReservation($uri[2]);
}
if ($method === 'PATCH' && $uri[0] === 'reservation' && $uri[1] === 'accepte' && isset($uri[2]) && isset($uri[3])) {
    checkId($uri[3]);
    $controller->confirmeReservation($uri[2], $uri[3]);
}
if ($method === 'PATCH' && $uri[0] === 'reservation' && $uri[1] === 'refuse' && isset($uri[2]) && isset($uri[3])) {
    checkId($uri[3]);
    $controller->refuseReservation($uri[2], $uri[3]);
}
if ($method === 'PATCH' && $uri[0] === 'reservation' && $uri[1] === 'termine' && isset($uri[2]) && isset($uri[3])) {
    checkId($uri[3]);
    $controller->terminerReservation($uri[2], $uri[3]);
}
if ($method === 'PATCH' && $uri[0] === 'reservation' && $uri[1] === 'annuler' && isset($uri[2]) && isset($uri[3])) {
    checkId($uri[3]);
    $controller->annulerReservation($uri[2], $uri[3]);
}
