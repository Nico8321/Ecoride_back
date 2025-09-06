<?php

class Utilisateur
{
    private $id;
    private $pseudo;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $telephone;
    private $adresse;
    private $photo;
    private $credit;
    private $role_id;

    public function __construct($data)
    {
        $this->pseudo = $data['pseudo'];
        $this->nom = $data['nom'];
        $this->prenom = $data['prenom'];
        $this->email = $data['email'];
        $this->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->telephone = $data['telephone'] ?? null;
        $this->adresse = $data['adresse'] ?? null;
        $this->photo = $data['photo'] ?? null;
        $this->credit = (isset($data['credit']) && $data['credit'] !== null) ? intval($data['credit']) : 20;
        $this->role_id = $data['role_id'];
    }

    public function getId()
    {
        return $this->id;
    }
    public function getPseudo()
    {
        return $this->pseudo;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function getPrenom()
    {
        return $this->prenom;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getTelephone()
    {
        return $this->telephone;
    }
    public function getAdresse()
    {
        return $this->adresse;
    }
    public function getPhoto()
    {
        return $this->photo;
    }
    public function getCredit()
    {
        return $this->credit;
    }
    public function getRoleId()
    {
        return $this->role_id;
    }

    public function save(PDO $pdo)   // enregistrer un nouvelle utilisateur dans la bdd 
    {
        $stmt = $pdo->prepare("INSERT INTO utilisateur (pseudo, nom, prenom, email, password, telephone, adresse, photo, credit, role_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->pseudo,
            $this->nom,
            $this->prenom,
            $this->email,
            $this->password,
            $this->telephone,
            $this->adresse,
            $this->photo,
            $this->credit,
            $this->role_id
        ]);
    }

    public static function findUtilisateurById(PDO $pdo, $id)    // trouver un utilisateur par son id 
    {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function emailExists(PDO $pdo, $email)  // verification que l'email est pas deja present dans la BDD
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
    public static function findUtilisateurByMail(PDO $pdo, $email)    // trouver un utilisateur par son mail
    {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function updateUtilisateur(PDO $pdo, $id, $data)
    {
        $stmt = $pdo->prepare("
        UPDATE utilisateur SET
            pseudo = ?, nom = ?, prenom = ?, telephone = ?, adresse = ?
        WHERE id = ?
    ");

        return $stmt->execute([
            $data['pseudo'],
            $data['nom'],
            $data['prenom'],
            $data['telephone'],
            $data['adresse'],
            $id
        ]);
    }
    public static function updateUtilisateurPassword(PDO $pdo, $id, $hashedPassword)
    {
        $stmt = $pdo->prepare("
        UPDATE utilisateur SET
            password = ?
        WHERE id = ?
    ");

        return $stmt->execute([
            $hashedPassword,
            $id
        ]);
    }
    public static function deleteUtilisateur(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("
        DELETE FROM utilisateur WHERE id = ?
    ");

        return $stmt->execute([
            $id
        ]);
    }
    public static function updatePhotoFilename(PDO $pdo, $id, $filename)
    {
        $stmt = $pdo->prepare("UPDATE utilisateur SET photo = ? WHERE id = ?");
        return $stmt->execute([$filename, $id]);
    }

    public static function addCreditUtilisateur(PDO $pdo, $id, $credit)
    {
        $stmt = $pdo->prepare("
        UPDATE utilisateur SET
            credit = credit + ?
        WHERE id = ?
    ");
        return $stmt->execute([
            $credit,
            $id
        ]);
    }
    public static function removeCreditUtilisateur(PDO $pdo, $id, $prix)
    {
        $stmt = $pdo->prepare("
        UPDATE utilisateur SET
            credit = credit - ?
        WHERE id = ?
    ");
        return $stmt->execute([
            $prix,
            $id
        ]);
    }
    public static function checkCredit(PDO $pdo, $id, $prix)
    {
        $stmt = $pdo->prepare("SELECT credit FROM utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        $credit = $stmt->fetchColumn();

        return $credit !== false && $credit >= $prix;
    }
    public static function getAll(PDO $pdo)
    {
        $stmt = $pdo->prepare("SELECT id, pseudo, nom, prenom, email, role_id FROM utilisateur ORDER BY role_id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function ajoutEmploye(PDO $pdo, $data)
    {
        $stmt = $pdo->prepare("
        INSERT INTO utilisateur (nom, prenom, email, password, role_id)
        VALUES (?, ?, ?, ?, ?)
    ");
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['role_id'],
        ]);
    }
}
