<?php
require_once __DIR__ . "/../models/reservation.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . '/../models/covoiturage.php';
require_once __DIR__ . '/../utils/securisationSortie.php';
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/vehicule.php';
require_once __DIR__ . '/../models/avis.php';
require_once __DIR__ . '/../models/PlateformeTransactions.php';
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
    public function getReservationById($id)
    {
        $reservation = Reservation::getReservationById($this->pdo, $id);
        if (!$reservation) {

            return false;
        }
        return $reservation;
    }

    public function createReservation($data, $id, $userId)
    {
        if ((int)$data['utilisateurId'] !== (int)$userId) {
            http_response_code(403);
            echo json_encode(["error" => "Action interdite"]);
            return;
        }
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
            echo json_encode([]);
            return;
        };
        foreach ($reservations as &$reservation) {
            $covoiturageId = $reservation['covoiturage_id'];
            $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $covoiturageId);

            if ($covoiturage) {
                // Enrichir avec conducteur
                $conducteur = Utilisateur::findUtilisateurById($this->pdo, $covoiturage['conducteur_id']);
                if ($conducteur) {
                    $covoiturage['conducteur_photo'] = $conducteur['photo_url'] ?? null;
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

    public function getReservationByCovoiturageId($id, $userId)
    {

        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $id);
        if (!$covoiturage) {
            http_response_code(404);
            echo json_encode(["error" => "Covoiturage introuvable"]);
            return;
        }
        if ((int)$covoiturage['conducteur_id'] !== (int)$userId) {
            http_response_code(403);
            echo json_encode(["error" => "Action interdite"]);
            return;
        }
        $reservations = Reservation::getByCovoiturageId($this->pdo, $id);
        if ($reservations === false) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la récupération des réservations"]);
            return;
        }
        if (empty($reservations)) {
            echo json_encode([]);
            return;
        }

        foreach ($reservations as &$reservation) {
            $utilisateurId  = $reservation['utilisateur_id'];
            $utilisateur = Utilisateur::findUtilisateurById($this->pdo,  $utilisateurId);
            if ($utilisateur) {
                $reservation["utilisateur_pseudo"] = $utilisateur["pseudo"];
                $reservation['utilisateur_photo'] = $utilisateur['photo_url'];
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
        $notification = $mailController->envoyerAcceptation($user['pseudo'], $user['email'], $dateDepart);
        if (!$notification) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de l'envoi du mail de confirmation"]);
            return;
        }
        echo json_encode(["message" => "Reservation acceptée"]);
        return;
    }

    // --- INTERNAL: logique métier sans sortie JSON ---
    public function refuseReservationInternal($id, $userId): bool
    {
        $reservation = Reservation::getReservationById($this->pdo, $id);
        if (!$reservation) {
            return false;
        }

        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $reservation['covoiturage_id']);
        if (!$covoiturage) {
            return false;
        }
        if ($covoiturage['conducteur_id'] != $userId) {
            return false;
        }

        $refuser = Reservation::refuserReservation($this->pdo, $id);
        if (!$refuser) {
            return false;
        }
        $montant = $covoiturage['prix'] * $reservation['nb_places'];
        $remboursement = Utilisateur::addCreditUtilisateur($this->pdo, $reservation['utilisateur_id'], $montant);
        if (!$remboursement) {
            return false;
        }

        $user = Utilisateur::findUtilisateurById($this->pdo, $reservation['utilisateur_id']);
        $dateDepart = $covoiturage['date_depart'];
        $mailController = new MailController();
        $notification = $mailController->envoyerRefus($user['pseudo'], $user['email'], $dateDepart);
        if (!$notification) {
            return false;
        }

        return true;
    }

    public function refuseReservation($id, $userId)
    {
        if ($this->refuseReservationInternal($id, $userId)) {
            echo json_encode(["message" => "Reservation refusée"]);
            return;
        }
        http_response_code(500);
        echo json_encode(["error" => "Erreur lors du refus de la réservation"]);
        return;
    }

    // ---  logique métier sans sortie JSON ---
    public function feedbackReservationInternal($id): bool
    {
        $reservation = Reservation::getReservationById($this->pdo, $id);
        if (!$reservation) {
            return false;
        }

        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $reservation['covoiturage_id']);
        if (!$covoiturage) {
            return false;
        }

        $feedback = Reservation::AwaitingFeedback($this->pdo, $id);
        if (!$feedback) {
            return false;
        }
        $user = Utilisateur::findUtilisateurById($this->pdo, $reservation['utilisateur_id']);
        $mailController = new MailController();
        $mailController->envoyerFeedback($user['pseudo'], $user['email']);

        return true;
    }

    public function feedbackReservation($id)
    {
        if ($this->feedbackReservationInternal($id)) {
            echo json_encode(["message" => "En attente du retour client"]);
            return;
        }
        http_response_code(500);
        echo json_encode(["error" => "Erreur lors de la demande de retour client"]);
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
        $termine = Reservation::terminerReservation($this->pdo, $id);
        if (!$termine) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors du changement de statut de la réservation "]);
            return;
        }

        $montantConducteur = ($covoiturage['prix'] - 2) * $reservation['nb_places'];
        $paiementUtilisateur = Utilisateur::addCreditUtilisateur($this->pdo, $covoiturage['conducteur_id'], $montantConducteur);
        if (!$paiementUtilisateur) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors du paiement du conducteur"]);
            return;
        }
        $montantPf = (2 * $reservation['nb_places']);
        $paiementPf = PlateformeTransactions::addCredit($this->pdo, $montantPf);
        if (!$paiementPf) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de l'enregistrement des crédits plateforme"]);
            return;
        }
        http_response_code(200);
        echo json_encode(["message" => " Votre réservation est maintenant terminée , le paiement au conducteur a bien été effectué "]);
        return;
    }
    public function litigeReservation($id)
    {
        $ok = Reservation::litigeReservation($this->pdo, $id);
        if (!$ok) return false;
        return true;
    }

    public function annulerReservation($id, $userId)
    {
        $reservation = Reservation::getReservationById($this->pdo, $id);
        if (!$reservation) {
            http_response_code(404);
            echo json_encode(['error' => 'Reservation introuvable']);
            return;
        }
        if ((int)$reservation['utilisateur_id'] !== (int)$userId) {
            http_response_code(403);
            echo json_encode(["error" => "Action impossible vous ne disposez pas des droits necessaires"]);
            return;
        }
        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $reservation['covoiturage_id']);
        if (!$covoiturage) {
            http_response_code(404);
            echo json_encode(["error" => "Covoiturage introuvable"]);
            return;
        }
        $date = new DateTime(); // maintenant
        $dateDepart = new DateTime($covoiturage['date_depart']);
        if ($reservation['statut'] == 'confirme') {
            if ($dateDepart <= (clone $date)->modify('+1 day')) {
                http_response_code(403);
                echo json_encode(['error' => 'Action impossible, la date de départ est trop proche']);
                return;
            }
        }
        $annuler = Reservation::annulerReservation($this->pdo, $reservation['id']);
        if (!$annuler) {
            http_response_code(500);
            echo json_encode(['error' => "Erreur lors de l'annulation de votre réservation"]);
            return;
        }
        $montantRemboursement = $reservation['nb_places'] * $covoiturage['prix'];
        $remboursement = Utilisateur::addCreditUtilisateur($this->pdo, $reservation['utilisateur_id'], $montantRemboursement);
        if (!$remboursement) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du remboursement de votre reservation']);
            return;
        }
        if ($reservation['statut'] == 'confirme') {
            $majNbPlace = Covoiturage::addPlaces($this->pdo, $covoiturage['id'], $reservation['nb_places']);
            if (!$majNbPlace) {
                http_response_code(500);
                echo json_encode(['error' => 'Erreur lors de la mise a jour du nombres de place disponible sur le covoiturage concerné']);
                return;
            }
        }
        http_response_code(200);
        echo json_encode(["message" => " Votre réservation a été annulée"]);
        return;
    }
}
