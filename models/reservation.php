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
        $stmt = $pdo->prepare("INSERT INTO reservation (utilisateur_id, covoiturage_id, nb_places, statut) VALUES (?, ?, ?, ?)");
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
    public static function getReservationById(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM reservation WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function getByCovoiturageId(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM reservation WHERE covoiturage_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteReservationById(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("
        DELETE FROM reservation WHERE id = ?
    ");

        return $stmt->execute([
            $id
        ]);
    }
    public static function accepterReservation(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("
        UPDATE reservation SET
            statut = 'confirme'
        WHERE id = ?
    ");
        return $stmt->execute([
            $id
        ]);
    }
    public static function refuserReservation(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("
        UPDATE reservation SET
            statut = 'refuse'
        WHERE id = ?
    ");
        return $stmt->execute([
            $id
        ]);
    }
    public static function AwaitingFeedback(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("
        UPDATE reservation SET
            statut = 'retour client'
        WHERE id = ?
    ");
        return $stmt->execute([
            $id
        ]);
    }
    public static function terminerReservation(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("
        UPDATE reservation SET
            statut = 'termine' 
        WHERE id = ?
    ");
        return $stmt->execute([
            $id
        ]);
    }
    public static function litigeReservation(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("
        UPDATE reservation SET
            statut = 'litige' 
        WHERE id = ?
    ");
        return $stmt->execute([
            $id
        ]);
    }
}
