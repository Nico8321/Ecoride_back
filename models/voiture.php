<?php

class Voiture
{
    private $id;
    private $marque;
    private $modele;
    private $energie;
    public function __construct($data)
    {
        $this->marque = $data['marque'];
        $this->modele = $data['modele'];
        $this->energie = $data['energie'];
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
    public function save(PDO $pdo) // enregistrer le vehicule dans la bdd 
    {
        $stmt = $pdo->prepare("INSERT INTO voiture (marque, modele, energie) VALUES (?, ?, ?)");
        $stmt->execute([
            $this->marque,
            $this->modele,
            $this->energie
        ]);
    }
    public static function findById(PDO $pdo, $id) // trouver un vehicule par son id 
    {
        $stmt = $pdo->prepare("SELECT * FROM voiture WHERE voiture_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function deleteVoiture(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("
        DELETE FROM voiture WHERE id = ?
    ");

        return $stmt->execute([
            $id
        ]);
    }
}
