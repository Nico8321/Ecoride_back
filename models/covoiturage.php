<?php
class Covoiturage
{
    private $id;
    private $conducteurId;
    private $vehiculeId;
    private $rueDepart;
    private $codePostalDepart;
    private $villeDepart;
    private $latitudeDepart;
    private $longitudeDepart;
    private $rueArrivee;
    private $codePostalArrivee;
    private $villeArrivee;
    private $latitudeArrivee;
    private $longitudeArrivee;
    private $dateDepart;
    private $heureDepart;
    private $nbPlaces;
    private $prix;
    private $statut;
    private $fumeur;
    private $animaux;
    private $duree;


    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getConducteurId()
    {
        return $this->conducteurId;
    }
    public function getVehiculeId()
    {
        return $this->vehiculeId;
    }
    public function getRueDepart()
    {
        return $this->rueDepart;
    }
    public function getCodePostalDepart()
    {
        return $this->codePostalDepart;
    }
    public function getVilleDepart()
    {
        return $this->villeDepart;
    }
    public function getLatitudeDepart()
    {
        return $this->latitudeDepart;
    }
    public function getLongitudeDepart()
    {
        return $this->longitudeDepart;
    }
    public function getRueArrivee()
    {
        return $this->rueArrivee;
    }
    public function getCodePostalArrivee()
    {
        return $this->codePostalArrivee;
    }
    public function getVilleArrivee()
    {
        return $this->villeArrivee;
    }
    public function getLatitudeArrivee()
    {
        return $this->latitudeArrivee;
    }
    public function getLongitudeArrivee()
    {
        return $this->longitudeArrivee;
    }
    public function getDateDepart()
    {
        return $this->dateDepart;
    }
    public function getHeureDepart()
    {
        return $this->heureDepart;
    }
    public function getNbPlaces()
    {
        return $this->nbPlaces;
    }
    public function getPrix()
    {
        return $this->prix;
    }

    public function getStatut()
    {
        return $this->statut;
    }
    public function getFumeur()
    {
        return $this->fumeur;
    }
    public function getAnimaux()
    {
        return $this->animaux;
    }
    public function getDuree()
    {
        return $this->duree;
    }

    public function save(PDO $pdo)
    {
        $this->statut = 'ouvert';
        $stmt = $pdo->prepare("INSERT INTO covoiturage (
        conducteur_id, vehicule_id, rue_depart,
         code_postal_depart, ville_depart,
          latitude_depart, longitude_depart,
          rue_arrivee, code_postal_arrivee,
           ville_arrivee, latitude_arrivee,
            longitude_arrivee, date_depart,
            heure_depart,
             nb_places, prix, statut,
              fumeur, animaux, duree) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->conducteurId,
            $this->vehiculeId,
            $this->rueDepart,
            $this->codePostalDepart,
            $this->villeDepart,
            $this->latitudeDepart,
            $this->longitudeDepart,
            $this->rueArrivee,
            $this->codePostalArrivee,
            $this->villeArrivee,
            $this->latitudeArrivee,
            $this->longitudeArrivee,
            $this->dateDepart,
            $this->heureDepart,
            $this->nbPlaces,
            $this->prix,
            $this->statut,
            $this->fumeur,
            $this->animaux,
            $this->duree
        ]);
    }

    public static function findCovoiturageByUtilisateurId(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM covoiturage WHERE conducteur_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findCovoiturageById(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM covoiturage WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findCovoiturageByFilter(PDO $pdo, $filtres)
    {
        $conditions = [];
        $params = [];

        if (!empty($filtres['depart'])) {
            $conditions[] = "ville_depart = ?";
            $params[] = $filtres['depart'];
        }
        if (!empty($filtres['destination'])) {
            $conditions[] = "ville_arrivee = ?";
            $params[] = $filtres['destination'];
        }
        if (!empty($filtres['date'])) {
            $conditions[] = "date_depart = ?";
            $params[] = $filtres['date'];
        }
        if (!empty($filtres['heure'])) {
            $conditions[] = "heure_depart >= ?";
            $params[] = $filtres['heure'];
        }
        if (!empty($filtres['prix'])) {
            $conditions[] = "prix <= ?";
            $params[] = $filtres['prix'];
        }
        if (!empty($filtres['energie'])) {
            $conditions[] = "vehicule_id IN (SELECT id FROM vehicule WHERE energie = ?)";
            $params[] = $filtres['energie'];
        }
        if (!empty($filtres['duree'])) {
            $conditions[] = "duree >= ?";
            $params[] = $filtres['duree'];
        }

        $sql = "SELECT c.* 
         FROM covoiturage c 
        JOIN utilisateur u ON c.conducteur_id = u.id";

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function annulerCovoiturage(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("UPDATE covoiturage SET statut = 'annule' WHERE id = ?");
        $success = $stmt->execute([$id]);
        $rowCount = $stmt->rowCount();
        error_log("Annulation covoiturage : success = " . var_export($success, true) . ", rows affected = $rowCount");
        return $rowCount > 0;
    }

    public static function addPlaces(PDO $pdo, $id, $places)
    {
        $stmt = $pdo->prepare("
        UPDATE covoiturage SET
            nb_places = nb_places + ?
        WHERE id = ?
    ");
        return $stmt->execute([
            $places,
            $id
        ]);
    }

    public static function removePlaces(PDO $pdo, $id, $places)
    {
        $stmt = $pdo->prepare("
        UPDATE covoiturage SET
        nb_places =  nb_places - ?
        WHERE id = ?
    ");
        return $stmt->execute([
            $places,
            $id
        ]);
    }
}
