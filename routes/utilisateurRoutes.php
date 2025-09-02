<?php
require_once __DIR__ . '/../controllers/utilisateurController.php';
require_once __DIR__ . '/../utils/requireAuth.php';

$controller = new UtilisateurController();
$method = $_SERVER['REQUEST_METHOD'];


header('Content-Type: application/json');
//creation compte
if ($method === 'POST' && $uri[0] === 'user' && $uri[1] === 'signup') {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->signUp($data);
} //connection
if ($method === 'POST' && $uri[0] === 'user' && $uri[1] === 'signin') {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->signIn($data);
} // patch infos utilisateur
if ($method === 'PATCH' && $uri[0] === 'user' && isset($uri[1]) && !isset($uri[2])) {
    checkId($uri[1]);
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->patchUser($uri[1], $data);
} // patch password utilisateur
if ($method === 'PATCH' && $uri[0] === 'user' && isset($uri[1], $uri[2]) && $uri[2] === "password") {
    checkId($uri[1]);
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->patchUserPassword($uri[1], $data);
} //suppression compte utilisateur
if ($method === 'DELETE' && $uri[0] === 'user' && isset($uri[1]) && !isset($uri[2])) {
    checkId($uri[1]);
    $controller->deleteUser($uri[1]);
} //ajout photo de profil utilisateur
if ($method === 'POST' && $uri[0] === 'user' && $uri[1] === 'photo' && isset($uri[2])) {
    checkId($uri[2]);
    $controller->addPhoto($uri[2]);
}
