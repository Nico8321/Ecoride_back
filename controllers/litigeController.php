<?php

require_once __DIR__ . '/../models/litige.php';
require_once __DIR__ . '/../config/mongo.php';
require_once __DIR__ . '/../controllers/reservationController.php';
require_once __DIR__ . '/../controllers/covoiturageController.php';
require_once __DIR__ . '/../controllers/utilisateurController.php';

class LitigeController
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
    public function createLitige($reservationId, $userId, $data)
    {
        if (empty($data['message'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Le message est requis']);
            return;
        }
        //recuperation des infos de la reservation 
        $reservationController = new ReservationController();
        $reservation = $reservationController->getReservationById($reservationId);

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
        //recuperation des infos du covoiturage
        $covoiturageController = new CovoiturageController();
        $covoiturage = $covoiturageController->getByCovoiturageId($reservation['covoiturage_id']);

        if (!$covoiturage) {
            http_response_code(404);
            echo json_encode(['error' => 'Covoiturage introuvable']);
            return;
        }
        //récuperration des infos du redacteur
        $utilisateurController = new UtilisateurController();
        $redacteur = $utilisateurController->getOneInternal($userId);
        if (!$redacteur) {
            http_response_code(404);
            echo json_encode(['error' => 'Rédacteur introuvable']);
            return;
        }
        //récuperration des infos du conducteur
        $conducteur = $utilisateurController->getOneInternal($covoiturage['conducteur_id']);
        if (!$conducteur) {
            http_response_code(404);
            echo json_encode(['error' => 'Conducteur introuvable']);
            return;
        }
        //ajout des infos de la reservation au litige
        $data['reservation'] = [
            'id' => $reservation['id'],
            'nb_places' => $reservation['nb_places']
        ];
        //ajout des infos du redacteur au litige
        $data['redacteur'] = [
            'id' => $redacteur['id'],
            'pseudo' => $redacteur['pseudo'],
            'mail' => $redacteur['email'],
        ];
        //ajout des infos du conducteur au litige
        $data['conducteur'] = [
            'id' => $conducteur['id'],
            'pseudo' => $conducteur['pseudo'],
            'mail' => $conducteur['email'],
        ];


        //ajout des infos du covoiturage au litige
        $data['covoiturage'] = [
            'date_depart' => $covoiturage['date_depart'],
            'prix' => $covoiturage['prix'],
            'ville_depart' => $covoiturage['ville_depart'],
            'ville_arrivee' => $covoiturage['ville_arrivee'],
        ];

        $res = Litige::create($this->col, $data);
        if (!$res) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la création du litige"]);
            return;
        };
        $statutReservation = $reservationController->litigeReservation($reservationId);
        if (!$statutReservation) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la modification du statut de la reservation"]);
            return;
        };
        http_response_code(201);
        echo json_encode(["id" => (string)$res, "message" => "Litige enregistré"]);
        return;
    }
}
