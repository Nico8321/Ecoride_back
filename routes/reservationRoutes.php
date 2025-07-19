<?php
require_once __DIR__ . '/../controllers/reservationController.php';

$controller = new ReservationController();
$method = $_SERVER['REQUEST_METHOD'];


header('Content-Type: application/json');

if ($method === 'POST' && $uri[0] === 'reservation' && $uri[1] === 'covoiturage' && isset($uri[2])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->createReservation($data, $uri[2]);
}
if ($method === 'GET' && $uri[0] === 'reservation' && $uri[1] === 'utilisateur' && isset($uri[2])) {
    $controller->getReservationByUser($uri[2]);
}
if ($method === 'GET' && $uri[0] === 'reservations' && isset($uri[1])) {
    $controller->getReservationByCovoiturageId($uri[1]);
}
