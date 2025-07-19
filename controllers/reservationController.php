<?php
require_once __DIR__ . "/../models/reservation.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . '/../models/covoiturage.php';
require_once __DIR__ . '/../utils/securisationSortie.php';
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/vehicule.php';
require_once __DIR__ . '/../models/avis.php';

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
            foreach ($reservations as &$reservation) {
                $covoiturageId = $reservation['covoiturage_id'];
                $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $covoiturageId);

                if ($covoiturage) {
                    // Enrichir avec conducteur
                    $conducteur = Utilisateur::findUtilisateurById($this->pdo, $covoiturage['conducteur_id']);
                    if ($conducteur) {
                        $covoiturage['conducteur_photo'] = $conducteur['photo'] ?? null;
                        $covoiturage['conducteur_pseudo'] = $conducteur['pseudo'];
                        $avis = Avis::getMoyenneByUtilisateurId($this->pdo, $covoiturage['conducteur_id']);
                        $covoiturage['conducteur_note'] = $avis;
                        // $covoiturage['conducteur_note'] = $conducteur['note'];
                    }

                    // Enrichir avec véhicule
                    $vehicule = Vehicule::findById($this->pdo, $covoiturage['vehicule_id']);
                    if ($vehicule) {
                        $covoiturage['vehicule_marque'] = $vehicule['marque'];
                        $covoiturage['vehicule_modele'] = $vehicule['modele'];
                        $covoiturage['vehicule_couleur'] = $vehicule['couleur'];
                        $covoiturage['vehicule_energie'] = $vehicule['energie'];
                    }

                    // Ajouter le covoiturage enrichi à la réservation
                    $reservation['covoiturage'] = $covoiturage;
                }
            }

            echo json_encode(securisationSortie($reservations));
        }
    }
    public function getReservationByCovoiturageId($id)
    {
        $reservations = Reservation::getByCovoiturageId($this->pdo, $id);

        foreach ($reservations as &$reservation) {
            $id = $reservation['utilisateur_id'];
            $utilisateur = Utilisateur::findUtilisateurById($this->pdo,  $id);
            if ($utilisateur) {
                $reservation["utilisateur_pseudo"] = $utilisateur["pseudo"];
                $reservation['utilisateur_photo'] = $utilisateur['photo'];
            }
        }

        echo json_encode(securisationSortie($reservations));
    }
}
