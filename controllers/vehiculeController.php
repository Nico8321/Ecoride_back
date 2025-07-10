<?php
require_once __DIR__ . '/../models/vehicule.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/securisationSortie.php';

class vehiculeController
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

    public function addVehicule($data, $id)
    {
        $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $id);

        if (!$utilisateur) {
            http_response_code(404);
            echo json_encode(["error" => "Utilisateur non trouvé"]);
            return;
        }

        $nbVehicules = Vehicule::countByUtilisateurId($this->pdo, $id);

        if ($nbVehicules >= 3) {
            http_response_code(403);
            echo json_encode(["error" => "Nombre maximal de véhicules atteint"]);
            return;
        }

        // Ajoute le vehicule
        $data['utilisateur_id'] = $id;
        $vehicule = new Vehicule($data);
        $vehicule->save($this->pdo, $id);
        echo json_encode(["message" => "Vehicule ajouté avec succès"]);
        return;
    }


    public function deleteVehicule($id, $utilisateur_id)
    {
        $vehicule = Vehicule::findById($this->pdo, $id);
        if (!$vehicule) {
            http_response_code(404);
            echo json_encode(["error" => "Véhicule non trouvé"]);
            return;
        }

        if ($vehicule["utilisateur_id"] !== $utilisateur_id) {
            http_response_code(403);
            echo json_encode(["error" => "Suppression non autorisée"]);
            return;
        }

        $success = Vehicule::deleteVehicule($this->pdo, $id);
        if ($success) {
            echo json_encode(["message" => "Véhicule supprimé"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la suppression"]);
        }
    }
}
