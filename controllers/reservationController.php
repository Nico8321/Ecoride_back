<?php
require_once __DIR__ . "/../models/reservation.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . '/../models/covoiturage.php';
require_once __DIR__ . '/../utils/securisationSortie.php';

class ReservationController
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

    public function createReservation($data, $id)
    {
        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $id);
        if ($covoiturage['nb_places'] > $data['nbplaces']) {
            $data['statut'] = "en attente";
            $reservation = new Reservation($data);
            $reservation->save($this->pdo);
            echo json_encode(["message" => "Reservation enregistrée"]);
        } else {
            http_response_code(403);
            echo json_encode(["error" => "Nombre de place disponibles insufisantes"]);
        }
    }
    public function getReservationByUser($id)
    {
        $reservations = Reservation::getByUtilisateurId($this->pdo, $id);
        if (!$reservations) {
            http_response_code(404);
            echo json_encode(["error" => "Aucune réservation trouvée"]);
        } else {
            echo json_encode(securisationSortie($reservations));
        }
    }
}
