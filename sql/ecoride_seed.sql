USE ecoride;

-- ==============================================
-- SEED DE DONNÉES - ECORIDE (gros volume)
-- ==============================================
-- Rôle
INSERT INTO
    role (role_id, nom_role)
VALUES
    (3, 'admin'),
    (2, 'employe'),
    (1, 'user');

-- ==============================================
-- UTILISATEURS (30)
-- ==============================================
INSERT INTO
    utilisateur (
        id,
        pseudo,
        nom,
        prenom,
        email,
        password,
        telephone,
        adresse,
        photo,
        credit,
        role_id
    )
VALUES
    (
        1,
        'Paul',
        'Martin',
        'Paul',
        'paul.martin@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000001',
        NULL,
        NULL,
        120,
        1
    ),
    (
        2,
        'Claire',
        'Durand',
        'Claire',
        'claire.durand@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000002',
        NULL,
        NULL,
        200,
        1
    ),
    (
        3,
        'Julien',
        'Petit',
        'Julien',
        'julien.petit@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000003',
        NULL,
        NULL,
        90,
        1
    ),
    (
        4,
        'Emma',
        'Roux',
        'Emma',
        'emma.roux@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000004',
        NULL,
        NULL,
        150,
        1
    ),
    (
        5,
        'Lucas',
        'Blanc',
        'Lucas',
        'lucas.blanc@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000005',
        NULL,
        NULL,
        80,
        1
    ),
    (
        6,
        'Sophie',
        'Garnier',
        'Sophie',
        'sophie.garnier@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000006',
        NULL,
        NULL,
        60,
        1
    ),
    (
        7,
        'Chevalier',
        'Chevalier',
        'Hugo',
        'hugo.chevalier@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000007',
        NULL,
        NULL,
        170,
        1
    ),
    (
        8,
        'Nina',
        'Morel',
        'Nina',
        'nina.morel@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000008',
        NULL,
        NULL,
        300,
        1
    ),
    (
        9,
        'Antoine',
        'Fournier',
        'Antoine',
        'antoine.fournier@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000009',
        NULL,
        NULL,
        50,
        1
    ),
    (
        10,
        'Elisa',
        'Lopez',
        'Elisa',
        'elisa.lopez@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000010',
        NULL,
        NULL,
        95,
        1
    ),
    (
        11,
        'Karim',
        'Muller',
        'Karim',
        'karim.muller@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000011',
        NULL,
        NULL,
        200,
        1
    ),
    (
        12,
        'Camille',
        'Lemoine',
        'Camille',
        'camille.lemoine@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000012',
        NULL,
        NULL,
        110,
        1
    ),
    (
        13,
        'Noah',
        'Barbier',
        'Noah',
        'noah.barbier@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000013',
        NULL,
        NULL,
        130,
        1
    ),
    (
        14,
        'Laura',
        'Dupuis',
        'Laura',
        'laura.dupuis@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000014',
        NULL,
        NULL,
        75,
        1
    ),
    (
        15,
        'Yanis',
        'Mercier',
        'Yanis',
        'yanis.mercier@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000015',
        NULL,
        NULL,
        145,
        1
    ),
    (
        16,
        'Alice',
        'Faure',
        'Alice',
        'alice.faure@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000016',
        NULL,
        NULL,
        180,
        1
    ),
    (
        17,
        'Mehdi',
        'Andre',
        'Mehdi',
        'mehdi.andre@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000017',
        NULL,
        NULL,
        90,
        1
    ),
    (
        18,
        'Chloe',
        'Lefevre',
        'Chloe',
        'chloe.lefevre@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000018',
        NULL,
        NULL,
        70,
        1
    ),
    (
        19,
        'Rayan',
        'Perrot',
        'Rayan',
        'rayan.perrot@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000019',
        NULL,
        NULL,
        155,
        1
    ),
    (
        20,
        'Eva',
        'Robin',
        'Eva',
        'eva.robin@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000020',
        NULL,
        NULL,
        200,
        1
    ),
    (
        21,
        'Tom',
        'Roy',
        'Tom',
        'tom.roy@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000021',
        NULL,
        NULL,
        95,
        1
    ),
    (
        22,
        'Manon',
        'Lucas',
        'Manon',
        'manon.lucas@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000022',
        NULL,
        NULL,
        115,
        1
    ),
    (
        23,
        'Adam',
        'Henry',
        'Adam',
        'adam.henry@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000023',
        NULL,
        NULL,
        125,
        1
    ),
    (
        24,
        'Sarah',
        'Gauthier',
        'Sarah',
        'sarah.gauthier@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000024',
        NULL,
        NULL,
        135,
        1
    ),
    (
        25,
        'Nathan',
        'Joly',
        'Nathan',
        'nathan.joly@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000025',
        NULL,
        NULL,
        85,
        1
    ),
    (
        26,
        'Maya',
        'Marchand',
        'Maya',
        'maya.marchand@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000026',
        NULL,
        NULL,
        140,
        1
    ),
    (
        27,
        'Lina',
        'Renard',
        'Lina',
        'lina.renard@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000027',
        NULL,
        NULL,
        160,
        1
    ),
    (
        28,
        'Leo',
        'Schmitt',
        'Leo',
        'leo.schmitt@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000028',
        NULL,
        NULL,
        105,
        1
    ),
    (
        29,
        'Clara',
        'Perez',
        'Clara',
        'clara.perez@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000029',
        NULL,
        NULL,
        190,
        1
    ),
    (
        30,
        'Hugo',
        'Collet',
        'Hugo',
        'hugo.collet@mail.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0600000030',
        NULL,
        NULL,
        210,
        1
    ),
    (
        31,
        'Hulk',
        'Bruce',
        'Banner',
        'staff@ecoride.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0612345678',
        '123 rue du Test',
        NULL,
        0,
        2
    ),
    (
        32,
        'IronMan',
        'Tony',
        'Stark',
        'admin@ecoride.com',
        '$2y$12$8Xs.7iZVogg3t4hpNCxYUumeVNOlXbUAZbVv.Uu3BBv95yl3wZqya',
        '0612345678',
        NULL,
        NULL,
        0,
        3
    );

-- ==============================================
-- VÉHICULES (12 conducteurs)
-- ==============================================
INSERT INTO
    vehicule (
        marque,
        modele,
        energie,
        dateImmat,
        couleur,
        immatriculation,
        utilisateur_id
    )
VALUES
    (
        'Peugeot',
        '208',
        'essence',
        '2021-03-15',
        'Rouge',
        'AA-111-AA',
        1
    ),
    (
        'Tesla',
        'Model 3',
        'electrique',
        '2022-07-20',
        'Noir',
        'BB-222-BB',
        2
    ),
    (
        'Renault',
        'Clio',
        'diesel',
        '2020-05-10',
        'Bleu',
        'CC-333-CC',
        3
    ),
    (
        'Toyota',
        'Yaris',
        'hybride',
        '2019-11-02',
        'Gris',
        'DD-444-DD',
        4
    ),
    (
        'Citroen',
        'C3',
        'essence',
        '2018-04-13',
        'Jaune',
        'EE-555-EE',
        5
    ),
    (
        'Volkswagen',
        'Golf',
        'diesel',
        '2020-08-25',
        'Blanc',
        'FF-666-FF',
        6
    ),
    (
        'BMW',
        'Serie 1',
        'diesel',
        '2021-09-10',
        'Noir',
        'GG-777-GG',
        7
    ),
    (
        'Mercedes',
        'Classe A',
        'essence',
        '2022-01-19',
        'Bleu',
        'HH-888-HH',
        8
    ),
    (
        'Dacia',
        'Sandero',
        'essence',
        '2017-05-22',
        'Blanc',
        'II-999-II',
        9
    ),
    (
        'Opel',
        'Corsa',
        'essence',
        '2019-06-30',
        'Rouge',
        'JJ-000-JJ',
        10
    ),
    (
        'Fiat',
        '500',
        'essence',
        '2020-04-12',
        'Gris',
        'KK-111-KK',
        11
    ),
    (
        'Audi',
        'A3',
        'diesel',
        '2022-03-27',
        'Noir',
        'LL-222-LL',
        12
    );

-- ==============================================
-- COVOITURAGES (14 trajets, dont 2 terminés)
-- ==============================================
INSERT INTO
    covoiturage (
        conducteur_id,
        date_depart,
        heure_depart,
        nb_places,
        fumeur,
        animaux,
        prix,
        statut,
        vehicule_id,
        rue_depart,
        code_postal_depart,
        ville_depart,
        latitude_depart,
        longitude_depart,
        rue_arrivee,
        code_postal_arrivee,
        ville_arrivee,
        latitude_arrivee,
        longitude_arrivee,
        duree
    )
VALUES
    (
        1,
        '2026-08-20',
        '08:00:00',
        3,
        0,
        1,
        15.00,
        'ouvert',
        1,
        '10 Rue Victor Hugo',
        '75001',
        'Paris',
        48.8566,
        2.3522,
        '20 Rue République',
        '69002',
        'Lyon',
        45.7640,
        4.8357,
        270
    ),
    (
        2,
        '2026-08-21',
        '09:00:00',
        2,
        0,
        0,
        25.00,
        'ouvert',
        2,
        '1 Place du Capitole',
        '31000',
        'Toulouse',
        43.6045,
        1.4442,
        '5 Av Jean Jaurès',
        '34000',
        'Montpellier',
        43.6119,
        3.8777,
        150
    ),
    (
        3,
        '2026-08-22',
        '07:30:00',
        4,
        1,
        0,
        10.00,
        'ouvert',
        3,
        '15 Av Verdun',
        '06000',
        'Nice',
        43.7102,
        7.2620,
        '12 Bd Croisette',
        '06400',
        'Cannes',
        43.5528,
        7.0174,
        45
    ),
    (
        4,
        '2026-08-23',
        '18:00:00',
        3,
        0,
        0,
        30.00,
        'ouvert',
        4,
        '30 Rue Nationale',
        '59000',
        'Lille',
        50.6292,
        3.0573,
        '40 Rue Alsace',
        '80000',
        'Amiens',
        49.8950,
        2.3023,
        120
    ),
    (
        5,
        '2026-08-24',
        '10:15:00',
        2,
        0,
        1,
        22.00,
        'ouvert',
        5,
        '12 Av Victor Hugo',
        '44000',
        'Nantes',
        47.2184,
        -1.5536,
        '5 Rue Carmes',
        '49000',
        'Angers',
        47.4784,
        -0.5632,
        50
    ),
    (
        6,
        '2026-08-25',
        '07:45:00',
        3,
        1,
        0,
        18.00,
        'ouvert',
        6,
        '2 Rue Ste-Catherine',
        '33000',
        'Bordeaux',
        44.8378,
        -0.5792,
        '8 Quai Chartrons',
        '17000',
        'La Rochelle',
        46.1591,
        -1.1522,
        120
    ),
    (
        7,
        '2026-08-26',
        '09:30:00',
        4,
        0,
        0,
        28.00,
        'ouvert',
        7,
        '5 Bd République',
        '34000',
        'Montpellier',
        43.6119,
        3.8777,
        '1 Rue Wilson',
        '30000',
        'Nîmes',
        43.8367,
        4.3601,
        60
    ),
    (
        8,
        '2026-08-27',
        '08:20:00',
        2,
        0,
        0,
        35.00,
        'ouvert',
        8,
        '10 Rue Alsace',
        '67000',
        'Strasbourg',
        48.5734,
        7.7521,
        '20 Rue Liberté',
        '21000',
        'Dijon',
        47.3220,
        5.0415,
        180
    ),
    (
        9,
        '2026-08-28',
        '08:00:00',
        3,
        0,
        1,
        12.00,
        'ouvert',
        9,
        'Place Bellecour',
        '69002',
        'Lyon',
        45.7640,
        4.8357,
        'Place Comédie',
        '34000',
        'Montpellier',
        43.6119,
        3.8777,
        210
    ),
    (
        10,
        '2026-08-29',
        '07:00:00',
        2,
        0,
        0,
        14.00,
        'ouvert',
        10,
        'Bd Haussmann',
        '75009',
        'Paris',
        48.8738,
        2.3312,
        'Place Royale',
        '44000',
        'Nantes',
        47.2184,
        -1.5536,
        240
    ),
    (
        11,
        '2026-08-30',
        '09:00:00',
        4,
        0,
        1,
        20.00,
        'ouvert',
        11,
        'Bd de la Mer',
        '14000',
        'Caen',
        49.1829,
        -0.3707,
        'Bd Clémenceau',
        '35000',
        'Rennes',
        48.1173,
        -1.6778,
        130
    ),
    (
        12,
        '2026-08-31',
        '08:15:00',
        3,
        0,
        0,
        26.00,
        'ouvert',
        12,
        'Cours Mirabeau',
        '13100',
        'Aix-en-Provence',
        43.5297,
        5.4474,
        'Vieux-Port',
        '13000',
        'Marseille',
        43.2965,
        5.3698,
        40
    ),
    -- trajets terminés pour avis
    (
        11,
        '2025-08-10',
        '08:00:00',
        3,
        0,
        0,
        18.00,
        'termine',
        11,
        'Place Bellecour',
        '69002',
        'Lyon',
        45.7640,
        4.8357,
        'Place Capitole',
        '31000',
        'Toulouse',
        43.6045,
        1.4442,
        300
    ),
    (
        12,
        '2025-08-12',
        '09:30:00',
        2,
        0,
        0,
        20.00,
        'termine',
        12,
        'Cours Lafayette',
        '69006',
        'Lyon',
        45.7690,
        4.8496,
        'Place Verdun',
        '33000',
        'Bordeaux',
        44.8378,
        -0.5792,
        240
    );

-- ==============================================
-- RÉSERVATIONS (50+)
-- ==============================================
INSERT INTO
    reservation (covoiturage_id, utilisateur_id, nb_places, statut)
VALUES
    (1, 5, 1, 'confirme'),
    (1, 6, 1, 'en attente'),
    (1, 7, 1, 'confirme'),
    (2, 8, 1, 'confirme'),
    (2, 9, 1, 'en attente'),
    (3, 10, 2, 'confirme'),
    (3, 11, 1, 'confirme'),
    (4, 12, 1, 'confirme'),
    (4, 13, 1, 'en attente'),
    (5, 14, 1, 'confirme'),
    (6, 15, 1, 'confirme'),
    (6, 16, 2, 'en attente'),
    (7, 17, 1, 'confirme'),
    (7, 18, 1, 'confirme'),
    (8, 19, 1, 'en attente'),
    (8, 20, 1, 'confirme'),
    (9, 21, 1, 'confirme'),
    (9, 22, 1, 'en attente'),
    (10, 23, 1, 'confirme'),
    (10, 24, 1, 'confirme'),
    (11, 25, 1, 'confirme'),
    (11, 26, 1, 'en attente'),
    (12, 27, 1, 'confirme'),
    (12, 28, 1, 'confirme'),
    (13, 29, 1, 'confirme'),
    (14, 30, 1, 'confirme'),
    (13, 5, 1, 'confirme'),
    (13, 6, 1, 'confirme'),
    (14, 7, 1, 'confirme');

-- ==============================================
-- AVIS (sur covoiturages terminés uniquement)
-- ==============================================
INSERT INTO
    avis (
        covoiturage_id,
        auteur_id,
        conducteur_id,
        note,
        date_avis,
        valide,
        commentaire
    )
VALUES
    (
        13,
        5,
        11,
        5,
        '2025-08-10 12:00:00',
        0,
        'Super trajet, conducteur ponctuel.'
    ),
    (
        13,
        6,
        11,
        4,
        '2025-08-10 12:30:00',
        0,
        'Bonne ambiance, un peu de retard.'
    ),
    (
        14,
        7,
        12,
        5,
        '2025-08-12 14:00:00',
        1,
        'Conducteur au top, trajet parfait.'
    );

-- ==============================================
-- MISE EN COHÉRENCE : réserverations terminées
-- (les crédits PF sont générés quand la résa est terminée)
-- ==============================================
UPDATE reservation
SET
    statut = 'termine'
WHERE
    covoiturage_id IN (13, 14)
    AND statut = 'confirme';

-- ==============================================
-- PLATEFORME : transactions (2 crédits par place)
-- Covoiturage 13 (2025-08-10) : 3 résas confirmées -> 3 * 2 = 6 crédits
-- Covoiturage 14 (2025-08-12) : 2 résas confirmées -> 2 * 2 = 4 crédits
-- On insère une ligne par réservation pour refléter ton flux réel.
-- ==============================================
INSERT INTO
    plateforme_transactions (credit, date_transaction)
VALUES
    -- Covoiturage 13 (3 réservations)
    (2, '2025-08-10 12:05:00'),
    (2, '2025-08-10 12:10:00'),
    (2, '2025-08-10 12:15:00'),
    -- Covoiturage 14 (2 réservations)
    (2, '2025-08-12 14:10:00'),
    (2, '2025-08-12 14:20:00');