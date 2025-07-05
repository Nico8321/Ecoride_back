<?php
header('Content-Type: application/json');

require_once 'config/database.php';

$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$base = $uri[0] ?? '';

switch ($base) {
    case 'user':
        require_once __DIR__ . '/routes/utilisateurRoutes.php';
        break;
    case 'auth':
        require_once __DIR__ . '/routes/authRoutes.php';
        break;
    case 'covoiturage':
        require_once __DIR__ . '/routes/covoiturageRoutes.php';
        break;
    case 'reservation':
        require_once __DIR__ . '/routes/reservationRoutes.php';
        break;
    case 'vehicule':
        require_once __DIR__ . '/routes/vehiculeRoutes.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route inconnue']);
        break;
}
