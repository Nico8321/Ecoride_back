<?php
require_once __DIR__ . '/controllers/UtilisateurController.php';


$controller = new UtilisateurController();
$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

header('Content-Type: application/json');

if ($method === 'POST' && $uri[0] === 'signup') {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->signUp($data);
}
if ($method === 'POST' && $uri[0] === 'signin') {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->signIn($data);
}
if ($method === 'PATCH' && $uri[0] === 'user' && isset($uri[1]) && !isset($uri[2])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->patchUser($uri[1], $data);
}
if ($method === 'PATCH' && $uri[0] === 'user' && isset($uri[1]) && $uri[2] === "password") {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->patchUserPassword($uri[1], $data);
}
if ($method === 'DELETE' && $uri[0] === 'user' && isset($uri[1]) && !isset($uri[2])) {
    $controller->deleteUser($uri[1]);
}
