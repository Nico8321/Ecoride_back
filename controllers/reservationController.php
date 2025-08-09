<?php
require_once __DIR__ . "/../models/reservation.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . '/../models/covoiturage.php';
require_once __DIR__ . '/../utils/securisationSortie.php';
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/vehicule.php';
require_once __DIR__ . '/../models/avis.php';
require_once __DIR__ . '/mailController.php';
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
        if ($covoiturage['nb_places'] >= $data['nbPlaces']) {
            $prix = $covoiturage['prix'] * $data['nbPlaces'];
            $verification = Utilisateur::checkCredit($this->pdo, $data['utilisateurId'], $prix);
            if (!$verification) {
                http_response_code(403);
                echo json_encode(["error" => "Credits insuffisants"]);
                return;
            }
            $succes = Utilisateur::removeCreditUtilisateur($this->pdo, $data['utilisateurId'], $prix);
            if ($succes) {

                $data['statut'] = "en attente";
                $reservation = new Reservation($data);
                $reservation->save($this->pdo);
                echo json_encode(["message" => "Reservation enregistrée"]);
                return;
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
        if ($reservations === false) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la récupération des réservations"]);
            return;
        };
        if (empty($reservations)) {
            http_response_code(404);
            echo json_encode(["error" => "Aucune réservation trouvée"]);
            return;
        };
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
        return;
    }

    public function getReservationByCovoiturageId($id)
    {
        $reservations = Reservation::getByCovoiturageId($this->pdo, $id);

        if ($reservations === false) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la récupération des réservations"]);
            return;
        }
        if (empty($reservations)) {
            http_response_code(404);
            echo json_encode(["error" => "Aucune réservation trouvée"]);
            return;
        }

        foreach ($reservations as &$reservation) {
            $utilisateurId  = $reservation['utilisateur_id'];
            $utilisateur = Utilisateur::findUtilisateurById($this->pdo,  $utilisateurId);
            if ($utilisateur) {
                $reservation["utilisateur_pseudo"] = $utilisateur["pseudo"];
                $reservation['utilisateur_photo'] = $utilisateur['photo'];
            }
        }

        echo json_encode(securisationSortie($reservations));
        return;
    }
    public function deleteReservation($id)
    {
        $reservation = Reservation::deleteReservationById($this->pdo, $id);
        if ($reservation) {
            echo json_encode(["message" => "Reservation supprimée"]);
            return;
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Reservation introuvable"]);
            return;
        }
    }

    public function confirmeReservation($id, $userId)
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
        if ($covoiturage['conducteur_id'] != $userId) {
            http_response_code(403);
            echo json_encode(["error" => "Action impossible vous ne disposez pas des droits necessaires"]);
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
        $user = Utilisateur::findUtilisateurById($this->pdo, $reservation['utilisateur_id']);
        $dateDepart = $covoiturage['date_depart'];
        $mailController = new MailController();
        $notification = $mailController->envoyerAcceptation($user['pseudo'], $user['email']);
        if (!$notification) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de l'envoi du mail de confirmation"]);
            return;
        }
        echo json_encode(["message" => "Reservation acceptée"]);
        return;
    }

    public function refuseReservation($id, $userId)
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
        if ($covoiturage['conducteur_id'] != $userId) {
            http_response_code(403);
            echo json_encode(["error" => "Action impossible vous ne disposez pas des droits necessaires"]);
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
        $user = Utilisateur::findUtilisateurById($this->pdo, $reservation['utilisateur_id']);
        $dateDepart = $covoiturage['date_depart'];
        $mailController = new MailController();
        $notification = $mailController->envoyerRefus($user['pseudo'], $user['email']);
        if (!$notification) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de l'envoi du mail de refus"]);
            return;
        }
        echo json_encode(["message" => "Reservation refusée"]);
        return;
    }
    public function feedbackReservation($id)
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

        $feedback = Reservation::AwaitingFeedback($this->pdo, $id);
        if (!$feedback) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la demande de retour client"]);
            return;
        }
        $user = Utilisateur::findUtilisateurById($this->pdo, $reservation['utilisateur_id']);
        $mailController = new MailController();
        $notification = $mailController->envoyerFeedback($user['pseudo'], $user['email']);
        if (!$notification) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de l'envoi du mail de retour client"]);
            return;
        }
        echo json_encode(["message" => "En attente du retour client"]);
        return;
    }
    public function terminerReservation($id, $userId)
    {
        $reservation = Reservation::getReservationById($this->pdo, $id);
        if (!$reservation) {
            http_response_code(404);
            echo json_encode(["error" => "Reservation introuvable"]);
            return;
        }
        if ($reservation['utilisateur_id'] != $userId) {
            http_response_code(403);
            echo json_encode(["error" => "Action impossible vous ne disposez pas des droits necessaires"]);
            return;
        }
        $termine = Reservation::terminerReservation($this->pdo, $id);
        if (!$termine) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors du changement de statut de la réservation "]);
            return;
        }
        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $reservation['covoiturage_id']);
        if (!$covoiturage) {
            http_response_code(500);
            echo json_encode(["error" => "Covoiturage introuvable"]);
            return;
        }
        if ($covoiturage['statut'] != 'termine') {
            http_response_code(403);
            echo json_encode(["error" => "Veuillez attendre la fin du covoiturage pour terminer la réservation "]);
            return;
        }

        $montantConducteur = $covoiturage['prix'] - 2 * $reservation['nb_places'];
        $paiementUtilisateur = Utilisateur::addCreditUtilisateur($this->pdo, $covoiturage['conducteur_id'], $montantConducteur);
        if (!$paiementUtilisateur) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors du paiement du conducteur"]);
            return;
        }
        http_response_code(200);
        echo json_encode(["message" => " Votre réservation est maintenant terminée , le paiement au conducteur a bien été effectué "]);
        return;
    }
    public function litigeReservation($data, $reservationId, $userId)
    {
        if (empty($data['message'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Le message est requis']);
            return;
        }
        $reservation = Reservation::getReservationById($this->pdo, $reservationId);
        if (!$reservation) {
            http_response_code(404);
            echo json_encode(['error' => 'Reservation introuvable']);
            return;
        }
        if ($reservation['utilisateur_id'] != $userId) {
            http_response_code(403);
            echo json_encode(['error' => 'Action impossible vous ne disposez pas des droits necessaires']);
            return;
        }


        $litigeData = [
            'reservation_id' => $reservationId,
            'utilisateur_id' => $userId,
            'message' => $data['message']
        ];
        $controller = new litigeController();
        $controller->createLitige($litigeData);
    }
}
