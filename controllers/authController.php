<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController
{
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = $_ENV['JWT_SECRET'] ?? false;
    }
    public  function  generateJWT($userId)
    {
        $issuedAt = time();
        $expiration = $issuedAt + 900;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expiration,
            'userId' => $userId,

        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }
    public function verifyToken()
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Token manquant"]);
            return;
        }

        $authHeader = $headers['Authorization'];
        if (!str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(400);
            echo json_encode(["error" => "Format d'autorisation invalide"]);
            return;
        }

        $token = trim(str_replace('Bearer', '', $authHeader));

        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            echo json_encode(["success" => true, "user_id" => $decoded->userId]);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Token invalide ou expiré"]);
        }
    }
}
