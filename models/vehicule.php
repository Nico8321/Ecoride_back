<?php

class Vehicule
{
    private $id;
    private $marque;
    private $modele;
    private $energie;
    private $utilisateur_id;

    public function __construct($data)
    {
        $this->marque = $data['marque'];
        $this->modele = $data['modele'];
        $this->energie = $data['energie'];
        $this->utilisateur_id = $data['utilisateur_id'];
    }
    public function getId()
    {
        return $this->id;
    }
    public function getMarque()
    {
        return $this->marque;
    }
    public function getModele()
    {
        return $this->modele;
    }
    public function getEnergie()
    {
        return $this->energie;
    }
    public function save(PDO $pdo, $utilisateurId) // enregistrer le vehicule dans la bdd 
    {
        $stmt = $pdo->prepare("INSERT INTO vehicule (marque, modele, energie, utilisateur_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $this->marque,
            $this->modele,
            $this->energie,
            $utilisateurId
        ]);
    }
    public static function findById(PDO $pdo, $id) // trouver un vehicule par son id 
    {
        $stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function deleteVehicule(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("
        DELETE FROM vehicule WHERE id = ?
    ");

        return $stmt->execute([
            $id
        ]);
    }

    public static function countByUtilisateurId(PDO $pdo, $utilisateur_id)
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM vehicule WHERE utilisateur_id = ?");
        $stmt->execute([$utilisateur_id]);
        return $stmt->fetchColumn();
    }
}
