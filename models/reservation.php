<?php
class Reservation
{
    private $utilisateurId;
    private $covoiturageId;
    private $nbPlaces;
    private $statut;

    public function __construct($data)
    {
        $this->utilisateurId = $data['utilisateurId'];
        $this->covoiturageId = $data['covoiturageId'];
        $this->nbPlaces = $data['nbPlaces'];
        $this->statut = $data['statut'];
    }
    public function getUtilisateurId()
    {
        return $this->utilisateurId;
    }
    public function getCovoiturageId()
    {
        return $this->covoiturageId;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getNbPlaces()
    {
        return $this->nbPlaces;
    }

    public function setStatut($newStatut)
    {

        $this->statut = $newStatut;
    }
    public function save(PDO $pdo)
    {
        $stmt = $pdo->prepare("INSERT INTO reservation (utilisateur_id, covoiturage_id, nb_places, statut) VALUES (?, ?, ?)");
        $stmt->execute([
            $this->utilisateurId,
            $this->covoiturageId,
            $this->nbPlaces,
            $this->statut
        ]);
    }
    public static function getByUtilisateurId(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM reservation WHERE utilisateur_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
