<?php

require_once __DIR__ . '/../controllers/authController.php';


function requireRole($roleId)
{
    $auth = new AuthController();
    if (!$auth->verifyRole($roleId)) {
        echo json_encode(["error" => "Accès interdit"]);
        exit;
    }
}
