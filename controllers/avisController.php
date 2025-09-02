<?php
require_once __DIR__ . '/../models/avis.php';
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../utils/securisationSortie.php';

class AvisController
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

    public function getAvisByUtilisateurId($id)
    {
        $avis = Avis::findByUtilisateurId($this->pdo, $id);
        echo json_encode($avis);
    }

    public function getAvisByCovoiturageId($id)
    {
        $listAvis = Avis::findByCovoiturageId($this->pdo, $id);
        if ($listAvis) {
            foreach ($listAvis as &$avis) {
                $utilisateurId = $avis['auteur_id'];
                $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $utilisateurId);
                if ($utilisateur) {
                    $avis['auteur_photo'] = $utilisateur['photo']
                        ?  $utilisateur['photo']
                        : null;
                    $avis['auteur_pseudo'] = $utilisateur['pseudo'];
                }
            }
        }
        echo json_encode(securisationSortie($listAvis));
    }
    public function getAllAvisToCheck()
    {
        $listAvis = Avis::findAllToCheck($this->pdo);
        if ($listAvis) {
            foreach ($listAvis as &$avis) {
                $utilisateurId = $avis['auteur_id'];
                $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $utilisateurId);
                if ($utilisateur) {
                    $avis['auteur_photo'] = $utilisateur['photo']
                        ?  $utilisateur['photo']
                        : null;
                    $avis['auteur_pseudo'] = $utilisateur['pseudo'];
                }
            }
        }
        echo json_encode(securisationSortie($listAvis));
    }


    public function getMoyenneByUtilisateurId($id)
    {
        $moyenne = Avis::getMoyenneByUtilisateurId($this->pdo, $id);
        echo json_encode(["moyenne" => $moyenne]);
    }

    public function addAvis($data, $userId)
    {
        if ((int)$data['auteur_id'] !== (int)$userId) {
            http_response_code(403);
            echo json_encode(["error" => "Action impossible vous ne disposez pas des droits necessaires"]);
            return;
        }
        $result = Avis::create(
            $this->pdo,
            $data['covoiturage_id'],
            $data['auteur_id'],
            $data['conducteur_id'],
            $data['note'],
            $data['commentaire']
        );

        if ($result) {
            echo json_encode(["message" => "Avis ajouté"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de l'ajout de l'avis"]);
        }
    }

    public function validerAvis($id)
    {
        $result = Avis::validerAvis($this->pdo, $id);
        if ($result) {
            echo json_encode(["message" => "Avis validé"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Échec de la validation de l'avis"]);
        }
    }
    public function refusAvis($id)
    {
        $result = Avis::refuserAvis($this->pdo, $id);
        if ($result) {
            echo json_encode(["message" => "Avis refusé"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Échec du refus de l'avis"]);
        }
    }
}
