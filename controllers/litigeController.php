<?php
require_once __DIR__ . '/../models/litige.php';

class litigeController
{
    private $col;
    public function __construct()
    {
        $this->col = MongoClientFactory::getCollection('litige');
        if (!$this->col) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la connexion à la base MongoDB"]);
            exit;
        }
    }
    public function getCol()
    {
        return $this->col;
    }
    public function createLitige($data)
    {
        $res = Litige::create($this->col, $data);
        if (!$res) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la création du litige"]);
            return;
        };
        http_response_code(201);
        echo json_encode(["message" => "Litige enregistré, id :" . $res]);
        return;
    }
}
