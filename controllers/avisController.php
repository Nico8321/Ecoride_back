<?php
require_once __DIR__ . '/../models/avis.php';


class AvisController
{
    private $pdo;
    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }
    public function getPdo()
    {
        return $this->pdo;
    }

    public function getAvisByUtilisateurId($id)
    {
        $avis = Avis::findByUtilisateurId($this->pdo, $id);
        echo json_encode($avis);
    }

    public function getMoyenneByUtilisateurId($id)
    {
        $moyenne = Avis::getMoyenneByUtilisateurId($this->pdo, $id);
        echo json_encode(["moyenne" => $moyenne]);
    }

    public function addAvis($data)
    {
        $result = Avis::create(
            $this->pdo,
            $data['auteur_id'],
            $data['conducteur_id'],
            $data['note'],
            $data['commentaire']
        );

        if ($result) {
            echo json_encode(["message" => "Avis ajouté"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de l'ajout de l'avis"]);
        }
    }

    public function validerAvis($id)
    {
        $result = Avis::validerAvis($this->pdo, $id);
        if ($result) {
            echo json_encode(["message" => "Avis validé"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Échec de la validation de l'avis"]);
        }
    }
}
