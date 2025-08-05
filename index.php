<?php
require_once __DIR__ . '/vendor/autoload.php';

//CORS
header('Access-Control-Allow-Origin: https://ecoride-front-w7vl.vercel.app');
header('Access-Control-Allow-Methods: GET, POST, PUT,PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Gérer les requêtes préflight CORS

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (file_exists(__DIR__ . '/.env')) {
    Dotenv\Dotenv::createImmutable(__DIR__)->load();
}






require_once __DIR__ . '/config/database.php';

$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
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
    case 'covoiturages':
        require_once __DIR__ . '/routes/covoiturageRoutes.php';
        break;
    case 'reservation':
        require_once __DIR__ . '/routes/reservationRoutes.php';
        break;
    case 'reservations':
        require_once __DIR__ . '/routes/reservationRoutes.php';
        break;
    case 'vehicule':
        require_once __DIR__ . '/routes/vehiculeRoutes.php';
        break;
    case 'avis':
        require_once __DIR__ . '/routes/avisRoutes.php';
        break;
    case 'avis-moyenne':
        require_once __DIR__ . '/routes/avisRoutes.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route inconnue']);
        break;
}
