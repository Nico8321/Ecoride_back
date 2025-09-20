DROP DATABASE IF EXISTS ecoride;

CREATE DATABASE ecoride DEFAULT CHARACTER
SET
    utf8mb4 DEFAULT COLLATE utf8mb4_general_ci;

USE ecoride;

SET
    FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS avis,
reservation,
covoiturage,
vehicule,
utilisateur,
plateforme_transactions,
role;

SET
    FOREIGN_KEY_CHECKS = 1;

CREATE TABLE
    role (
        role_id INT NOT NULL AUTO_INCREMENT,
        nom_role VARCHAR(50) NOT NULL UNIQUE,
        PRIMARY KEY (role_id)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
    utilisateur (
        id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
        pseudo VARCHAR(100) NOT NULL,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        email VARCHAR(200) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        telephone VARCHAR(20),
        adresse VARCHAR(255),
        photo_url VARCHAR(255),
        photo_id VARCHAR(255),
        credit INT NOT NULL,
        role_id INT NOT NULL,
        active tinyint NOT NULL DEFAULT '1',
        CONSTRAINT fk_utilisateur_role FOREIGN KEY (role_id) REFERENCES role (role_id) ON UPDATE CASCADE ON DELETE RESTRICT
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
    vehicule (
        id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
        marque VARCHAR(50) NOT NULL,
        modele VARCHAR(50) NOT NULL,
        energie ENUM ('essence', 'diesel', 'electrique', 'hybride'),
        dateImmat DATE NOT NULL,
        couleur VARCHAR(20) NOT NULL,
        immatriculation VARCHAR(20) NOT NULL UNIQUE,
        utilisateur_id INT NOT NULL,
        CONSTRAINT fk_vehicule_user FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
    covoiturage (
        id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
        conducteur_id INT NOT NULL,
        date_depart DATE NOT NULL,
        heure_depart TIME NOT NULL,
        nb_places INT NOT NULL,
        fumeur TINYINT NOT NULL,
        animaux TINYINT NOT NULL,
        prix DECIMAL(4, 2) NOT NULL,
        statut ENUM (
            'ouvert',
            'complet',
            'termine',
            'annule',
            'demarre'
        ) NOT NULL,
        vehicule_id INT NOT NULL,
        rue_depart VARCHAR(255),
        code_postal_depart VARCHAR(10),
        ville_depart VARCHAR(100),
        latitude_depart DECIMAL(9, 6),
        longitude_depart DECIMAL(9, 6),
        rue_arrivee VARCHAR(255),
        code_postal_arrivee VARCHAR(10),
        ville_arrivee VARCHAR(100),
        latitude_arrivee DECIMAL(9, 6),
        longitude_arrivee DECIMAL(9, 6),
        duree INT NOT NULL,
        CONSTRAINT fk_covoit_conducteur FOREIGN KEY (conducteur_id) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE RESTRICT,
        CONSTRAINT fk_covoit_vehicule FOREIGN KEY (vehicule_id) REFERENCES vehicule (id) ON UPDATE CASCADE ON DELETE RESTRICT
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
    reservation (
        id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
        covoiturage_id INT NOT NULL,
        utilisateur_id INT NOT NULL,
        nb_places INT NOT NULL,
        statut ENUM (
            'en attente',
            'confirme',
            'refuse',
            'retour client',
            'termine',
            'litige',
            'annule'
        ) NOT NULL,
        CONSTRAINT fk_reservation_covoiturage FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT fk_reservation_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
    avis (
        id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
        covoiturage_id INT NOT NULL,
        auteur_id INT NOT NULL,
        conducteur_id INT NOT NULL,
        note INT CHECK (
            note >= 1
            AND note <= 5
        ) NOT NULL,
        date_avis DATETIME NOT NULL,
        valide TINYINT NOT NULL,
        commentaire TEXT NOT NULL,
        CONSTRAINT fk_avis_covoiturage FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT fk_avis_auteur FOREIGN KEY (auteur_id) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT fk_avis_conducteur FOREIGN KEY (conducteur_id) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
    plateforme_transactions (
        id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
        credit INT NOT NULL,
        date_transaction DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- Index
CREATE INDEX idx_covoit_ville_date ON covoiturage (ville_depart, date_depart);

CREATE INDEX idx_covoit_statut ON covoiturage (statut);

CREATE INDEX idx_resa_statut ON reservation (statut);

CREATE INDEX idx_pt_date ON plateforme_transactions (date_transaction);