<?php
require_once __DIR__ . '/models/utilisateur.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/utils/securisationSortie.php';

class UtilisateurController
{
    private $pdo;

    public function __construct()    //connection a la bdd
    {
        $this->pdo = Database::getConnection();
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function getOne($id) //recuperation d'un utilisateur par l'id 
    {
        $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $id);
        if ($utilisateur) {
            unset($utilisateur['password']);
        }
        echo json_encode(securisationSortie($utilisateur));
    }

    public function signUp($data)
    {
        if (Utilisateur::emailExists($this->pdo, $data['email'])) {
            http_response_code(409);
            echo json_encode(["error" => "Email déjà utilisé"]);
            return;
        }
        $data['role_id'] = 3; // rôle utilisateur par défaut 
        $utilisateur = new Utilisateur($data);
        $utilisateur->save($this->pdo);
        echo json_encode(["message" => "Utilisateur créé"]);
    }

    public function signIn($data)
    {
        $utilisateur = Utilisateur::findUtilisateurByMail($this->pdo, $data['email']);

        if ($utilisateur && password_verify($data['password'], $utilisateur['password'])) {
            unset($utilisateur['password']);
            echo json_encode(securisationSortie($utilisateur));
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Identifiants invalides"]);
        }
    }

    public function patchUser($id, $data)
    {
        $success = Utilisateur::updateUtilisateur($this->pdo, $id, $data);

        if ($success) {
            echo json_encode(["message" => "Utilisateur modifié"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Utilisateur non trouvé ou non modifié"]);
        }
    }

    public function patchUserPassword($id, $data)
    {
        $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $id);
        if ($utilisateur && password_verify($data['password'], $utilisateur['password'])) {
            $hashedPassword = password_hash($data['newPassword'], PASSWORD_BCRYPT);
            $success = Utilisateur::updateUtilisateurPassword($this->pdo, $id, $hashedPassword);

            if ($success) {
                echo json_encode(["message" => "Mot de passe modifié"]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Utilisateur non trouvé ou non modifié"]);
            }
        }
    }

    public function deleteUser($id)
    {
        $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $id);
        if ($utilisateur) {
            $success = Utilisateur::deleteUtilisateur($this->pdo, $id);
            if ($success) {
                echo json_encode(["message" => "Utilisateur supprimé"]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Utilisateur non trouvé ou non modifié"]);
            }
        }
    }
}
