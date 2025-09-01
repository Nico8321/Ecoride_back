<?php

class Avis
{
    public static function create(PDO $pdo, $covoiturage_id, $utilisateur_id, $conducteur_id, $note, $commentaire)
    {
        $stmt = $pdo->prepare("INSERT INTO avis (covoiturage_id, auteur_id, conducteur_id, note, commentaire, date_avis, valide) VALUES (?, ?, ?, ?, ?, NOW(), 0)");
        return $stmt->execute([$covoiturage_id, $utilisateur_id, $conducteur_id, $note, $commentaire]);
    }

    public static function findByUtilisateurId(PDO $pdo, $conducteur_id)
    {
        $stmt = $pdo->prepare("SELECT * FROM avis WHERE conducteur_id = ? AND valide = 1");
        $stmt->execute([$conducteur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMoyenneByUtilisateurId(PDO $pdo, $conducteur_id)
    {
        $stmt = $pdo->prepare("SELECT ROUND(AVG(note), 1) as moyenne FROM avis WHERE conducteur_id = ? AND valide = 1");
        $stmt->execute([$conducteur_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['moyenne'] : null;
    }
    public static function findByCovoiturageId(PDO $pdo, $covoiturage_id)
    {
        $stmt = $pdo->prepare("SELECT * FROM avis WHERE covoiturage_id = ? AND valide = 1");
        $stmt->execute([$covoiturage_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function findAllToCheck(PDO $pdo)
    {
        $stmt = $pdo->prepare("SELECT * FROM avis WHERE valide = 0 ORDER BY date_avis DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function validerAvis(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("UPDATE avis SET valide = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public static function refuserAvis(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("UPDATE avis SET valide = 2 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
