<?php
require_once __DIR__ . '/models/voiture.php';
require_once __DIR__ . '/config/database.php';

class voitureController
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

    public function addVoiture($data, $id)
    {
        $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $id);

        if (!$utilisateur) {
            http_response_code(404);
            echo json_encode(["error" => "Utilisateur non trouvé"]);
            return;
        }

        $nbVoitures = Voiture::countByUtilisateurId($this->pdo, $id);

        if ($nbVoitures >= 3) {
            http_response_code(403);
            echo json_encode(["error" => "Nombre maximal de véhicules atteint"]);
            return;
        }

        // Ajoute la voiture
        $voiture = new Voiture($data, $id);
        $voiture->save($this->pdo, $id);
        echo json_encode(["message" => "Voiture ajoutée avec succès"]);
        return;
    }


    public function deleteVoiture($utilisateur_id, $id)
    {
        $voiture = Voiture::findById($this->pdo, $id);
        if (!$voiture) {
            http_response_code(404);
            echo json_encode(["error" => "Véhicule non trouvé"]);
            return;
        }

        if ($voiture["utilisateur_id"] !== $utilisateur_id) {
            http_response_code(403);
            echo json_encode(["error" => "Suppression non autorisée"]);
            return;
        }

        $success = Voiture::deleteVoiture($this->pdo, $id);
        if ($success) {
            echo json_encode(["message" => "Véhicule supprimé"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la suppression"]);
        }
    }
}
