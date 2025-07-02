<?php

class Utilisateur
{
    public $id;
    public $pseudo;
    public $nom;
    public $prenom;
    public $email;
    public $password;
    public $telephone;
    public $adresse;
    public $code_postal;
    public $photo;
    public $credit;
    public $role_id;
    public $voiture_id;

    public function __construct($data)
    {
        $this->pseudo = $data['pseudo'];
        $this->nom = $data['nom'];
        $this->prenom = $data['prenom'];
        $this->email = $data['email'];
        $this->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->telephone = $data['telephone'];
        $this->adresse = $data['adresse'];
        $this->code_postal = $data['code_postal'];
        $this->photo = $data['photo'];
        $this->credit = $data['credit'];
        $this->role_id = $data['role_id'];
        $this->voiture_id = $data['voiture_id'];
    }

    public function save(PDO $pdo)   // enregistrer un nouvelle utilisateur dans la bdd 
    {
        $stmt = $pdo->prepare("INSERT INTO utilisateur (pseudo, nom, prenom, email, password, telephone, adresse, code_postal, photo, credit, role_id, voiture_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->pseudo,
            $this->nom,
            $this->prenom,
            $this->email,
            $this->password,
            $this->telephone,
            $this->adresse,
            $this->code_postal,
            $this->photo,
            $this->credit,
            $this->role_id,
            $this->voiture_id
        ]);
    }

    public static function findUtilisateurById(PDO $pdo, $id)    // trouver un utilisateur par son id 
    {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
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
            pseudo = ?, nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?
        WHERE utilisateur_id = ?
    ");

        return $stmt->execute([
            $data['pseudo'],
            $data['nom'],
            $data['prenom'],
            $data['email'],
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
        WHERE utilisateur_id = ?
    ");

        return $stmt->execute([
            $hashedPassword,
            $id
        ]);
    }
}
