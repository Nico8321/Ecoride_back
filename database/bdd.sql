

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


-- Base de données : `ecoride`
--



--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `covoiturage_id` int(11) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `conducteur_id` int(11) NOT NULL,
  `note` tinyint(4) NOT NULL,
  `commentaire` text NOT NULL,
  `date_avis` datetime NOT NULL,
  `valide` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Déchargement des données de la table `avis`
--
INSERT INTO avis (covoiturage_id, auteur_id, conducteur_id, note, commentaire, date_avis, valide) VALUES
(1, 5, 6, 5, 'Trajet parfait, conducteur très ponctuel.', '2025-07-17 15:30:00', 1),
(2, 8, 7, 4, 'Bonne ambiance à bord, voiture propre.', '2025-07-20 13:00:00', 1),
(3, 9, 8, 3, 'Confort moyen mais trajet rapide.', '2025-07-22 19:00:00', 1),
(4, 7, 9, 5, 'Excellent covoiturage, merci !', '2025-07-25 21:30:00', 1),
(5, 6, 5, 2, 'Beaucoup de retard au départ.', '2025-08-21 12:00:00', 1),
(6, 7, 5, 4, 'Bon trajet, à refaire.', '2025-08-21 13:45:00', 1);
(7, 7, 6, 5, 'Très bon trajet, conducteur sympa.', '2025-07-10 20:30:00', 1),
(7, 8, 6, 4, 'Bonne expérience, un peu de retard.', '2025-07-10 21:00:00', 1),
(8, 9, 6, 5, 'Parfait ! Très agréable, à refaire.', '2025-07-12 19:45:00', 1);
(9, 7, 5, 5, 'Super conducteur, trajet très fluide.', '2025-07-01 18:00:00', 1),
(9, 8, 5, 4, 'Un petit détour mais bonne ambiance.', '2025-07-01 18:30:00', 1),
(10, 9, 5, 5, 'Très agréable, à refaire !', '2025-07-05 19:00:00', 1);
--
-- Structure de la table `covoiturage`
--

CREATE TABLE `covoiturage` (
  `id` int(11) NOT NULL,
  `conducteur_id` int(11) NOT NULL,
  `date_depart` date NOT NULL,
  `heure_depart` time NOT NULL,
  `nb_places` int(11) NOT NULL,
  `fumeur` tinyint(4) NOT NULL,
  `animaux` tinyint(4) NOT NULL,
  `prix` decimal(4,2) NOT NULL,
  `statut` enum('ouvert','complet','annule','termine') NOT NULL,
  `vehicule_id` int(11) NOT NULL,
  `rue_depart` varchar(255) DEFAULT NULL,
  `code_postal_depart` varchar(10) DEFAULT NULL,
  `ville_depart` varchar(100) DEFAULT NULL,
  `latitude_depart` float DEFAULT NULL,
  `longitude_depart` float DEFAULT NULL,
  `rue_arrivee` varchar(255) DEFAULT NULL,
  `code_postal_arrivee` varchar(10) DEFAULT NULL,
  `ville_arrivee` varchar(100) DEFAULT NULL,
  `latitude_arrivee` float DEFAULT NULL,
  `longitude_arrivee` float DEFAULT NULL,
  `duree` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `covoiturage`
--

INSERT INTO `covoiturage` (`id`, `conducteur_id`, `date_depart`, `heure_depart`, `nb_places`, `fumeur`, `animaux`, `prix`, `statut`, `vehicule_id`, `rue_depart`, `code_postal_depart`, `ville_depart`, `latitude_depart`, `longitude_depart`, `rue_arrivee`, `code_postal_arrivee`, `ville_arrivee`, `latitude_arrivee`, `longitude_arrivee`, `duree`) VALUES
(1, 6, '2025-07-17', '10:00:00', 1, 1, 1, 10.00, 'ouvert', 17, 'Lou Mistralet', '13140', 'Miramas', 43.5831, 5.01067, 'Grand', '43590', 'Beauzac', 45.257, 4.11643, 818),
(2, 7, '2025-07-20', '09:00:00', 2, 0, 1, 8.50, 'ouvert', 20, 'Rue des Lilas', '75012', 'Paris', 48.8461, 2.3876, 'Cours Lafayette', '69003', 'Lyon', 45.7597, 4.8422, 290),
(3, 8, '2025-07-22', '14:30:00', 3, 1, 0, 12.00, 'ouvert', 21, 'Avenue du Port', '13001', 'Marseille', 43.2965, 5.3698, 'Place de la Comédie', '34000', 'Montpellier', 43.6119, 3.8777, 180),
(4, 9, '2025-07-25', '18:00:00', 1, 0, 0, 15.00, 'ouvert', 22, 'Place Victor Hugo', '44000', 'Nantes', 47.2186, -1.5536, 'Rue Alsace Lorraine', '31000', 'Toulouse', 43.6047, 1.4442, 340),
(5, 5, '2025-08-21', '09:30:00', 4, 1, 1, 4.00, 'ouvert', 19, 'Rue des Caux', '83790', 'Pignans', 43.3003, 6.22856, 'Boulevard chave', '13005', 'Marseille', 43.2954, 5.39472, 332),
(6, 5, '2025-08-21', '09:30:00', 3, 1, 1, 8.00, 'ouvert', 19, 'Boulevard chave', '13005', 'Marseille', 43.2954, 5.39472, 'Rue Ordener', '75018', 'Paris', 48.8922, 2.34605, 1263),
(7, 6, '2025-07-10', '08:30:00', 3, 0, 0, 9.00, 'termine', 17, 'Rue de la République', '13002', 'Marseille', 43.2965, 5.3698, 'Rue Sainte-Catherine', '33000', 'Bordeaux', 44.8378, -0.5792, 480),
(8, 6, '2025-07-12', '10:00:00', 2, 1, 0, 6.50, 'termine', 18, 'Rue Nationale', '75001', 'Paris', 48.8566, 2.3522, 'Place du Capitole', '31000', 'Toulouse', 43.6047, 1.4442, 420),
(9, 5, '2025-07-01', '07:00:00', 3, 1, 0, 10.00, 'termine', 19, 'Quai des Belges', '13001', 'Marseille', 43.2965, 5.3698, 'Place Bellecour', '69002', 'Lyon', 45.7578, 4.8321, 320),
(10, 5, '2025-07-05', '14:00:00', 2, 0, 0, 7.00, 'termine', 19, 'Rue Paradis', '13006', 'Marseille', 43.2842, 5.3768, 'Place Masséna', '06000', 'Nice', 43.7000, 7.2700, 250);
-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `covoiturage_id` int(11) NOT NULL,
  `nb_places` tinyint(3) UNSIGNED DEFAULT NULL,
  `statut` enum('en attente','confirme','refuse') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id`, `utilisateur_id`, `covoiturage_id`, `nb_places`, `statut`) VALUES
(1, 5, 1, 1, 'en attente'),
(2, 5, 3, 2, 'en attente'),
(3, 5, 3, 2, 'en attente'),
(4, 5, 3, 2, 'en attente'),
(5, 6, 6, 2, 'confirme'),
(6, 6, 6, 2, 'en attente'),
(7, 7, 7, 1, 'confirme'),
(8, 8, 7, 1, 'confirme'),
(9, 9, 8, 2, 'confirme'),
(10, 7, 9, 1, 'confirme'),
(11, 8, 9, 1, 'confirme'),
(12, 9, 10, 2, 'confirme'),
(13, 7, 9, 1, 'refuse'),
(14, 8, 9, 1, 'refuse'),
(15, 9, 10, 1, 'refuse');
-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `nom_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`role_id`, `nom_role`) VALUES
(1, 'utilisateur'),
(2, 'employe'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(30) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `credit` int(11) NOT NULL DEFAULT 0,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `pseudo`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `photo`, `credit`, `role_id`) VALUES
(5, 'NicoLeKing', 'Beuve', 'Nicolas', 'nico@email.fr', '$2y$10$DXtwtlJTARxycakv6zkuqOw2vgTpfgB2z4mOajgVIFZnFaXUbBIa2', '0603030457', '123 rue du Test', 'user_5_6873b060f3b370.84309236.jpg', 20, 1),
(6, 'Swann83', 'Beuve', 'Swann', 'swann@email.fr', '$2y$10$vQhunueXj8gX1sHo6uUpPePbABEJCVi67ROPV/CPPZaeN.RAujiei', '0612121245', '456 boulevard du faron 83200 toulon', 'user_6_6873b4b5a0ba66.02946981.jpg', 20, 1),
(7, 'MarieTest', 'Durand', 'Marie', 'marie@test.com', '$2y$10$abcdefg...', '0600000001', '12 rue des Lilas', 'marie.jpg', 15, 1),
(8, 'PaulUser', 'Martin', 'Paul', 'paul@test.com', '$2y$10$abcdefg...', '0600000002', '34 avenue du Port', 'paul.jpg', 10, 1),
(9, 'JulieDev', 'Lemoine', 'Julie', 'julie@test.com', '$2y$10$abcdefg...', '0600000003', '56 place Victor Hugo', 'julie.jpg', 20, 1);

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

CREATE TABLE `vehicule` (
  `id` int(11) NOT NULL,
  `marque` varchar(50) NOT NULL,
  `modele` varchar(50) NOT NULL,
  `energie` enum('essence','diesel','hybride','electrique','gpl') NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `dateImmat` date NOT NULL,
  `couleur` varchar(25) NOT NULL,
  `immatriculation` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vehicule`
--

INSERT INTO `vehicule` (`id`, `marque`, `modele`, `energie`, `utilisateur_id`, `dateImmat`, `couleur`, `immatriculation`) VALUES
(17, 'mercedes', 'classe a ', 'diesel', 6, '2023-08-21', 'gris', 'AB123CD'),
(18, 'BMW', 'Serie 3 ', 'hybride', 6, '2022-01-01', 'noir', 'AB456CD'),
(19, 'BMW', 'Serie 3 ', 'electrique', 5, '2022-01-01', 'bleu', 'AB123CD'),
(20, 'Renault', 'Clio', 'essence', 7, '2022-03-01', 'rouge', 'CD456EF'),
(21, 'Peugeot', '208', 'diesel', 8, '2021-07-15', 'blanc', 'EF789GH'),
(22, 'Tesla', 'Model 3', 'electrique', 9, '2023-01-10', 'noir', 'GH123IJ');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auteur_id` (`auteur_id`),
  ADD KEY `covoiturage_id` (`conducteur_id`),
  ADD KEY `fk_avis_covoiturager_id` (`covoiturage_id`);

--
-- Index pour la table `covoiturage`
--
ALTER TABLE `covoiturage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_conducteur_utilisateur` (`conducteur_id`),
  ADD KEY `fk_voiture_id` (`vehicule_id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `covoiturage_id` (`covoiturage_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Index pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_voiture_utilisateur` (`utilisateur_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `covoiturage`
--
ALTER TABLE `covoiturage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `vehicule`
--
ALTER TABLE `vehicule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `fk_avis_auteur_id` FOREIGN KEY (`auteur_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `fk_avis_conducteur_id` FOREIGN KEY (`conducteur_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `fk_avis_covoiturage_id` FOREIGN KEY (`covoiturage_id`) REFERENCES `covoiturage` (`id`);

--
-- Contraintes pour la table `covoiturage`
--
ALTER TABLE `covoiturage`
  ADD CONSTRAINT `fk_covoiturage_conducteur_id` FOREIGN KEY (`conducteur_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `fk_covoiturage_voiture_id` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicule` (`id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_covoiturage_id` FOREIGN KEY (`covoiturage_id`) REFERENCES `covoiturage` (`id`),
  ADD CONSTRAINT `fk_reservation_utilisateur_id` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT ` fk_utilisateur_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);

--
-- Contraintes pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD CONSTRAINT `fk_voiture_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

