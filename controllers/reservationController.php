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
            $prix = $covoiturage['prix'] * $data["nbplaces"];
            $verification = Utilisateur::checkCredit($this->pdo, $data['utilisateur_id'], $prix);
            if (!$verification) {
                http_response_code(403);
                echo json_encode(["error" => "Credits insuffisants"]);
                return;
            }
            $succes = Utilisateur::removeCreditUtilisateur($this->pdo, $data['utilisateur_id'], $prix);
            if ($succes) {

                $data['statut'] = "en attente";
                $reservation = new Reservation($data);
                $reservation->save($this->pdo);
                echo json_encode(["message" => "Reservation enregistrée"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Probleme lors du debit des credits"]);
                return;
            }
        } else {
            http_response_code(403);
            echo json_encode(["error" => "Nombre de place disponibles insuffisantes"]);
            return;
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
    public function deleteReservation($id)
    {
        $reservation = Reservation::deleteReservationById($this->pdo, $id);
        if ($reservation) {
            echo json_encode(["message" => "Reservation supprimée"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Reservation introuvable"]);
        }
    }

    public function confirmeReservation($id)
    {
        $reservation = Reservation::getReservationById($this->pdo, $id);
        if (!$reservation) {
            http_response_code(404);
            echo json_encode(["error" => "Reservation introuvable"]);
            return;
        }
        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $reservation['covoiturage_id']);
        if (!$covoiturage) {
            http_response_code(404);
            echo json_encode(["error" => "Covoiturage introuvable"]);
            return;
        }
        $majNbPlaces = Covoiturage::removePlaces($this->pdo, $covoiturage['id'], $reservation['nb_places']);
        if (!$majNbPlaces) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la mise a jour des places libres"]);
            return;
        }
        $succes = Reservation::accepterReservation($this->pdo, $id);
        if (!$succes) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de l'acceptation de la réservation"]);
            return;
        }
        echo json_encode(["message" => "Reservation acceptée"]);
    }

    public function refuseReservation($id)
    {
        $reservation = Reservation::getReservationById($this->pdo, $id);
        if (!$reservation) {
            http_response_code(404);
            echo json_encode(["error" => "Reservation introuvable"]);
            return;
        }

        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $reservation['covoiturage_id']);
        if (!$covoiturage) {
            http_response_code(404);
            echo json_encode(["error" => "Covoiturage introuvable"]);
            return;
        }

        $montant = $covoiturage['prix'] * $reservation['nb_places'];
        $remboursement = Utilisateur::addCreditUtilisateur($this->pdo, $reservation['utilisateur_id'], $montant);
        if (!$remboursement) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors du remboursement des crédits"]);
            return;
        }

        $refuser = Reservation::refuserReservation($this->pdo, $id);
        if (!$refuser) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors du refus de la réservation"]);
            return;
        }

        echo json_encode(["message" => "Reservation refusée"]);
    }
}
