<?php
require_once __DIR__ . '/../models/covoiturage.php';
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/apiAdresse.php';
require_once __DIR__ . '/../utils/apiOsrm.php';

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
    public function getByConducteurId($id)
    {
        $covoiturages = Covoiturage::findCovoiturageByUtilisateurId($this->pdo, $id);
        if ($covoiturages) {
            foreach ($covoiturages as &$covoiturage) {
                $conducteurId = $covoiturage['conducteur_id'];
                $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $conducteurId);
                if ($utilisateur) {
                    $covoiturage['conducteur_photo'] = $utilisateur['photo']
                        ?  $utilisateur['photo']
                        : null;
                    $covoiturage['conducteur_pseudo'] = $utilisateur['pseudo'];
                    $covoiturage['conducteur_note'] = $utilisateur['note'];
                }
            }

            echo json_encode($covoiturages);
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
        unset($data['arrive']);
        unset($data['depart']);
        return $data;
    }

    public function addCovoiturage($data)
    {
        $dataWithAdresse = $this->decoupageAdresse($data);
        $dataWithDuree = $this->addDureeTrajet($dataWithAdresse);
        $covoiturage = new Covoiturage($dataWithDuree);
        $covoiturage->save($this->pdo);

        echo json_encode(["message" => "Covoiturage créé"]);
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
            http_response_code(404);
            echo json_encode(["error" => "Aucun covoiturage trouvé"]);
        } else {
            foreach ($covoiturages as &$covoiturage) {
                $conducteurId = $covoiturage['conducteur_id'];
                $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $conducteurId);
                if ($utilisateur) {
                    $covoiturage['conducteur_photo'] = $utilisateur['photo']
                        ?  $utilisateur['photo']
                        : null;
                    $covoiturage['conducteur_pseudo'] = $utilisateur['pseudo'];
                    $covoiturage['conducteur_note'] = $utilisateur['note'];
                }
            }
            echo json_encode($covoiturages);
        }
    }
}
