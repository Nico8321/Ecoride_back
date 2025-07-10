<?php

class Vehicule
{
    private $id;
    private $immatriculation;
    private $dateImmat;
    private $marque;
    private $modele;
    private $energie;
    private $couleur;
    private $utilisateur_id;

    public function __construct($data)
    {
        $this->immatriculation = $data['immatriculation'];
        $this->dateImmat = $data['dateImmat'];
        $this->marque = $data['marque'];
        $this->modele = $data['modele'];
        $this->energie = $data['energie'];
        $this->couleur = $data['couleur'];
        $this->utilisateur_id = $data['utilisateur_id'];
    }
    public function getId()
    {
        return $this->id;
    }
    public function getImmatriculation()
    {
        return $this->immatriculation;
    }
    public function getDateImmat()
    {
        return $this->dateImmat;
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
    public function getCouleur()
    {
        return $this->couleur;
    }
    public function getUtilisateurId()
    {
        return $this->utilisateur_id;
    }
    public function save(PDO $pdo, $utilisateurId) // enregistrer le vehicule dans la bdd 
    {
        $stmt = $pdo->prepare("INSERT INTO vehicule (immatriculation, dateImmat, marque, modele, energie, couleur, utilisateur_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->immatriculation,
            $this->dateImmat,
            $this->marque,
            $this->modele,
            $this->energie,
            $this->couleur,
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
