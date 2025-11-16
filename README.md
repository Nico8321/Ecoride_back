# EcoRide – Backend

![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![Heroku](https://img.shields.io/badge/deploy-Heroku-430098?logo=heroku&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Heroku%20Container%20Stack-430098?logo=docker)
![Licence](https://img.shields.io/badge/Licence-MIT-green)
![Status](https://img.shields.io/badge/status-Stable-success)

## 📝 Description

Backend de l'application EcoRide, destiné à gérer les utilisateurs, les covoiturages, les réservations, les avis, les litiges, et le stockage de photos.  
L’API est développée en PHP procédural avec PDO, sécurisée via JWT, et communique avec un frontend JavaScript disponible 👉 [ici](https://github.com/Nico8321/Ecoride_front.git)

Déploiement en production via Heroku (stack container) avec une image Docker personnalisée (php-fpm + nginx + supervisor).

## 🕹️Fonctionnalités principales

- Authentification (inscription, connexion, tokens JWT)
- Gestion des utilisateurs (rôles, profils)
- Création et recherche de trajets de covoiturage
- Réservation d’un trajet
- Dépôt et récupération d’avis
- Upload et accès aux photos de profil (stockage sur Cloudinary)
- Accès sécurisé selon le rôle (admin / utilisateur / employé)

## 🛠️ Technologies utilisées

### Backend

- PHP 8.x (sans framework)
- MySQL (via PDO)
- JSON Web Token (JWT)
- Architecture REST
- Dotenv (`.env`)
- CORS
- API:

  - project-OSRM pour la gestion des temps de trajet
  - adresse.gouv pour la récupération des coordonnées GPS des adresses saisies

- MongoDB (litiges via driver officiel mongodb/mongodb)
- Cloudinary (stockage et gestion des photos de profil)
- PHPMailer

### Conteneurisation

- Docker
- php-fpm
- nginx
- supervisor
- Heroku stack container

## 🚀 Déploiement (Heroku)

L’API est déployée sur Heroku via la stack _container_ :

- Build Docker via `heroku container:push`
- Release via `heroku container:release`
- nginx + php-fpm gérés par supervisord

## 🏠 Structure

- `index.php` → routeur principal
- `/controllers` → logique métier (utilisateurs, covoiturages, réservations…)
- `/models` → accès base de données (PDO)
- `/routes` → endpoints REST
- `/utils` → APIs, modèles de mail pour PHPMailer, fonctions de sécurisation des sorties, `requireAuth.php` pour la vérification des userId et des rôles
- `/config` → connexion aux bases de données
- `docker-nginx.conf` → config nginx
- `supervisord.conf` → lance nginx + php-fpm
- `Dockerfile` → image Heroku

## 🔒 Sécurité mise en place

- Hashage des mots de passe avec password_hash() et vérification avec password_verify().

- Requêtes préparées PDO pour prévenir les injections SQL.

- Filtrage et validation des données saisies par l’utilisateur (htmlspecialchars).

- Gestion des sessions PHP sécurisées.

- Protection des formulaires contre les attaques XSS.

## 🔬 Tests et validation

- Tests manuels avec Postman : vérification des routes API (utilisateur, covoiturage, réservation, véhicule).

- Scénarios fonctionnels : inscription, connexion, ajout de véhicule, création de trajet, réservation, annulation.

- Tests d’erreurs : saisie de données incorrectes, formulaires incomplets, mauvais identifiants.

## 🔥 Lancer l’API avec Docker (méthode officielle)

### 🔧 Prérequis

- Docker installé

### ⚙️ Étapes

#### 1. Créer un fichier `.env` à la racine avec :

voir `.env.example` partie MODE DOCKER & CONFIG COMMUNE.

#### 2. Build

`docker build -t ecoride-local . `

Puis lancer le conteneur

`docker run -p 8080:8080 --env-file .env ecoride-local `

API accessible sur :
👉 http://localhost:8080

⚠️ MongoDB + extension PHP sont déjà installés dans le Dockerfile
→ aucune manipulation locale nécessaire.

---

## 🧩 2 — Installation sans Docker (optionnel)

### 🔧 Prérequis

- Serveur PHP (XAMPP, MAMP, ou PHP CLI)
- MySQL
- Outil type Postman ou navigateur
- driver officiel mongodb/mongodb(voir étape 2 si besoin )
- Compte Cloudinary (pour gérer l’hébergement des images).
- Cluster MongoDB (MongoDB Atlas, ou local, pour gérer les litiges)

### ⚙️ Étapes

#### 1. Cloner le dépôt

```
git clone https://github.com/Nico8321/Ecoride_back.git
cd Ecoride_back
```

#### 2. Installation du driver MongoDB pour PHP

La bibliothèque PHP MongoDB est une abstraction de haut niveau pour l'extension PHP MongoDB,  
l'installation de l'extension est obligatoire pour vous connecter à MongoDB et interagir avec les données stockées dans votre cluster.  
👉[Lien vers la documentation mongoDB PHP Library](https://www.mongodb.com/docs/php-library/current/get-started/)

```bash
pecl install mongodb
```

#### 3. Installer les dépendances

```
composer install
```

#### 4. Créer un fichier `.env` à la racine avec :

Voir le `.env.example` partie CONFIG COMMUNE

```env
DB_HOST=localhost
DB_NAME=ecoride
DB_USER=root
DB_PASS=
JWT_SECRET=VotreCléSecrèteIci
EMAIL= Adresse email utilisée pour l’envoi de mails aux utilisateurs
PASSWORD_MAIL= Mot de passe ou clé SMTP
MONGO_URI= URI mongoDb atlas : mongodb+srv://<username>:<password>@cluster0.mongodb.net/ , ou local: mongodb://localhost:XXXX
MONGO_DB= nom de la base MongoDb
CLOUDINARY_CLOUD_NAME=yourCloudName
CLOUDINARY_API_KEY=yourApiKey
CLOUDINARY_API_SECRET=yourApiSecret

```

#### 5. Importer le fichier `ecoride.sql` dans MySQL

> - Lancer XAMPP
> - Démarrer Apache et MySQL
> - Aller dans un navigateur à l’adresse :
>   👉 http://localhost/phpmyadmin
> - Créez une base de données nommée "ecoride"
> - Aller dans l’onglet Import
> - Choisir le fichier ecoride.sql
> - Cliquer sur Exécuter
> - Vous pouvez également importer le fichier [`ecoride_seed.sql`](./sql/ecoride_seed.sql) pour injecter les données de test (utilisateurs, covoiturages, etc...)

#### 6. Lancer le serveur :

```
 php -S localhost:8000
```

#### 7. Tester les routes via Postman ou depuis le frontend

## 📌 Routes principales (exemples)

| Méthode | URL                                    | Description                    |
| ------: | -------------------------------------- | ------------------------------ |
|    POST | /user/signup                           | Inscription                    |
|    POST | /user/signin                           | Connexion                      |
|     GET | /covoiturages                          | Liste des trajets disponibles  |
|    POST | /covoiturage/{userId}                  | Ajouter un trajet              |
|    POST | /reservation/covoiturage/{id}/{userId} | Réserver un trajet             |
|     GET | /avis?covoiturage_id={id}              | Récupérer les avis d’un trajet |
|    POST | /avis?utilisateur_id={id}              | Déposer un avis                |
|    POST | /user/photo/{userId}                   | Envoyer une image              |

## 🎁📝 Auteur

**Nicolas Beuve**

Projet réalisé dans le cadre du titre professionnel **DWWM** (Studi – _2025-2026_)
