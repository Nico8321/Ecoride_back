<?php
require_once __DIR__ . '/../models/covoiturage.php';
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/vehicule.php';
require_once __DIR__ . '/../models/avis.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/apiAdresse.php';
require_once __DIR__ . '/../utils/apiOsrm.php';
require_once __DIR__ . '/../controllers/reservationController.php';
require_once __DIR__ . '/../utils/securisationSortie.php';

class CovoiturageController
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

    public function getByCovoiturageId($id)
    {
        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $id);
        if (!$covoiturage) {
            return false;
        }
        return $covoiturage;
    }

    public function getByConducteurId($id)
    {
        $covoiturages = Covoiturage::findCovoiturageByUtilisateurId($this->pdo, $id);
        if ($covoiturages) {
            foreach ($covoiturages as &$covoiturage) {
                $conducteurId = $covoiturage['conducteur_id'];
                $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $conducteurId);
                if ($utilisateur) {
                    $covoiturage['conducteur_photo'] = $utilisateur['photo_url']
                        ?  $utilisateur['photo_url']
                        : null;
                    $covoiturage['conducteur_pseudo'] = $utilisateur['pseudo'];
                    $avis = Avis::getMoyenneByUtilisateurId($this->pdo, $conducteurId);
                    $covoiturage['conducteur_note'] = $avis;
                }
                $vehiculeId = $covoiturage['vehicule_id'];
                $vehicule = Vehicule::findById($this->pdo, $vehiculeId);
                if ($vehicule) {

                    $covoiturage['vehicule_marque'] = $vehicule['marque'];
                    $covoiturage['vehicule_modele'] = $vehicule['modele'];
                    $covoiturage['vehicule_couleur'] = $vehicule['couleur'];
                    $covoiturage['vehicule_energie'] = $vehicule['energie'];
                }
            }

            echo json_encode(securisationSortie($covoiturages));
        } else {
            echo json_encode([]);
        }
    }
    public function decoupageAdresse($data)
    {
        $depart = $data['depart'];
        $arrivee = $data['arrivee'];
        $infos = getAdresseDetailsFromAPI($depart);

        $data['rueDepart'] = $infos['rue'];
        $data['codePostalDepart'] = $infos['codePostal'];
        $data['villeDepart'] = $infos['ville'];
        $data['latitudeDepart'] = $infos['latitude'];
        $data['longitudeDepart'] = $infos['longitude'];

        $infos = getAdresseDetailsFromAPI($arrivee);
        $data['rueArrivee'] = $infos['rue'];
        $data['codePostalArrivee'] = $infos['codePostal'];
        $data['villeArrivee'] = $infos['ville'];
        $data['latitudeArrivee'] = $infos['latitude'];
        $data['longitudeArrivee'] = $infos['longitude'];
        unset($data['arrivee']);
        unset($data['depart']);
        return $data;
    }

    public function addCovoiturage($data, $userId)
    {
        if ($data['prix'] < 3) {
            http_response_code(400);
            echo json_encode(["error" => "Le prix minimum par place doit etre de 3 credits"]);
            return;
        }
        if ((int)$data['conducteurId'] !== (int)$userId) {
            http_response_code(403);
            echo json_encode(["error" => "Action interdite"]);
            return;
        }
        $dataWithAdresse = $this->decoupageAdresse($data);
        $dataWithDuree = $this->addDureeTrajet($dataWithAdresse);
        $covoiturage = new Covoiturage($dataWithDuree);
        $covoiturage->save($this->pdo);

        echo json_encode(["message" => "Covoiturage créé"]);
        return;
    }
    public function addDureeTrajet($data)
    {
        $data = getDureeTrajet($data);
        return $data;
    }

    public function rechercheCovoiturages($filtres)
    {
        $covoiturages = Covoiturage::findCovoiturageByFilter($this->pdo, $filtres);
        if (!$covoiturages) {

            echo json_encode([]);
            return;
        } else {
            foreach ($covoiturages as &$covoiturage) {
                $conducteurId = $covoiturage['conducteur_id'];
                $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $conducteurId);
                if ($utilisateur) {
                    $covoiturage['conducteur_photo'] = $utilisateur['photo_url']
                        ?  $utilisateur['photo_url']
                        : null;
                    $covoiturage['conducteur_pseudo'] = $utilisateur['pseudo'];
                    $avis = Avis::getMoyenneByUtilisateurId($this->pdo, $conducteurId);
                    $covoiturage['conducteur_note'] = $avis;
                }
                $vehiculeId = $covoiturage['vehicule_id'];
                $vehicule = Vehicule::findById($this->pdo, $vehiculeId);
                if ($vehicule) {

                    $covoiturage['vehicule_marque'] = $vehicule['marque'];
                    $covoiturage['vehicule_modele'] = $vehicule['modele'];
                    $covoiturage['vehicule_couleur'] = $vehicule['couleur'];
                    $covoiturage['vehicule_energie'] = $vehicule['energie'];
                }
            }
            echo json_encode(securisationSortie($covoiturages));
            return;
        }
    }
    public function annuleCovoiturage($userId, $covoiturageId)
    {
        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $covoiturageId);
        if (!$covoiturage) {
            http_response_code(404);
            echo json_encode(["error" => "Covoiturage introuvable"]);
            return;
        }
        $reservations = Reservation::getByCovoiturageId($this->pdo, $covoiturageId);
        if ($reservations) {
            $controller = new ReservationController();

            foreach ($reservations as $reservation) {
                if ($reservation['statut'] == 'confirme') {
                    ($controller->refuseReservationInternal($reservation['id'], $userId));
                }
            }
        }
        if ($covoiturage['conducteur_id'] == $userId) {
            $succes = Covoiturage::annulerCovoiturage($this->pdo, $covoiturageId);
            if ($succes) {
                http_response_code(200);
                echo json_encode(["message" => "Covoiturage annulé"]);
                return;
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Covoiturage non trouvé ou non modifié"]);
                return;
            }
        } else {
            http_response_code(403);
            echo json_encode(["error" => "Action interdite"]);
            return;
        }
    }
    public function demarrerCovoiturage($userId, $covoiturageId)
    {
        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $covoiturageId);
        if (!$covoiturage) {
            http_response_code(404);
            echo json_encode(["error" => "Covoiturage introuvable"]);
            return;
        }
        if ($covoiturage['conducteur_id'] == $userId) {
            $succes = Covoiturage::changeStatutCovoiturage($this->pdo, $covoiturageId, 'demarre');
            if ($succes) {
                http_response_code(200);
                echo json_encode(["message" => "Covoiturage demarré"]);
                return;
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Covoiturage non trouvé ou non modifié"]);
                return;
            }
        } else {
            http_response_code(403);
            echo json_encode(["error" => "Action interdite"]);
            return;
        }
    }
    public function terminerCovoiturage($userId, $covoiturageId)
    {

        // Vérifier que le covoiturage existe
        $covoiturage = Covoiturage::findCovoiturageById($this->pdo, $covoiturageId);
        if (!$covoiturage) {
            http_response_code(404);
            echo json_encode(["error" => "Covoiturage introuvable"]);
            return;
        }

        // Vérifier que c'est bien le conducteur
        if ($covoiturage['conducteur_id'] != $userId) {
            http_response_code(403);
            echo json_encode(["error" => "Action interdite"]);
            return;
        }

        // Changer le statut du covoiturage
        $succes = Covoiturage::changeStatutCovoiturage($this->pdo, $covoiturageId, 'termine');
        if (!$succes) {
            http_response_code(404);
            echo json_encode(["error" => "Covoiturage non trouvé ou non modifié"]);
            return;
        }

        // Récupérer toutes les réservations et envoyer un mail de feedback pour chaque réservation confirmée
        $reservations = Reservation::getByCovoiturageId($this->pdo, $covoiturageId);
        if (!empty($reservations)) {
            $controller = new ReservationController();
            foreach ($reservations as $reservation) {
                if ($reservation['statut'] === 'confirme') {
                    $controller->feedbackReservationInternal($reservation['id']);
                }
            }
        }

        // Réponse finale
        http_response_code(200);
        echo json_encode(["message" => "Covoiturage terminé"]);
        return;
    }
    public function historiqueCovoiturage()
    {
        $covoiturage = Covoiturage::getHistoriqueCovoiturage($this->pdo);
        if (!$covoiturage || count($covoiturage) === 0) {
            echo json_encode([]);
            return;
        }
        http_response_code(200);
        echo json_encode(securisationSortie($covoiturage));
        return;
    }
}
