<?php
require_once __DIR__ . '/../controllers/PlateformeTransactionsController.php';
require_once __DIR__ . '/../utils/requireAuth.php';

$controller = new PlateformeTransactionsController();
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

if ($method === 'GET' && $uri[0] === 'transaction' &&  isset($uri[1]) && $uri[1] === 'historique') {
    requireRole(2); // a remplacer par 3 en prod 
    $controller->getHistorique();
}
