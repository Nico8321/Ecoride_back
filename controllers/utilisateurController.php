<?php

require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/securisationSortie.php';
require_once __DIR__ . '/../controllers/authController.php';

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
            $auth = new AuthController();
            $token = $auth->generateJWT($utilisateur['id']);
            $utilisateur['token'] = $token;
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
    public function addPhoto($id)
    {
        $utilisateur = Utilisateur::findUtilisateurById($this->pdo, $id);
        if ($utilisateur) {
            if (isset($_FILES['photo'])) {
                if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $check = getimagesize($_FILES['photo']['tmp_name']);
                    if ($check === false) {
                        http_response_code(400);
                        echo json_encode(["error" => "Le fichier n'est pas une image."]);
                        return;
                    }

                    // Sécurisation + déplacement du fichier
                    $emplacement = __DIR__ . '/../uploads/photos/';
                    if (!file_exists($emplacement)) {
                        mkdir($emplacement, 0777, true);
                    }

                    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    $extensionsAutorisées = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                    if (!in_array(strtolower($extension), $extensionsAutorisées)) {
                        http_response_code(400);
                        echo json_encode(["error" => "Extension de fichier non autorisée."]);
                        return;
                    }

                    $newFileName = uniqid("user_{$id}_", true) . '.' . $extension;
                    $targetFile = $emplacement . $newFileName;

                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                        Utilisateur::updatePhotoFilename($this->pdo, $id, $newFileName);
                        echo json_encode(["message" => "Photo enregistrée avec succès", "filename" => $newFileName]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["error" => "Erreur lors du déplacement du fichier."]);
                    }
                    return;
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Erreur lors de l'upload."]);
                    return;
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Aucun fichier reçu."]);
                return;
            }
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Utilisateur non trouvé."]);
            return;
        }
    }
}
