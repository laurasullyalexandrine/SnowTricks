-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 10 août 2023 à 09:57
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `snowtricks`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230707123732', '2023-08-10 09:45:42', 100),
('DoctrineMigrations\\Version20230707145111', '2023-08-10 09:45:42', 11),
('DoctrineMigrations\\Version20230721085423', '2023-08-10 09:45:42', 20),
('DoctrineMigrations\\Version20230728082120', '2023-08-10 09:45:42', 57),
('DoctrineMigrations\\Version20230728163529', '2023-08-10 09:45:42', 21),
('DoctrineMigrations\\Version20230728165650', '2023-08-10 09:45:42', 10),
('DoctrineMigrations\\Version20230803115918', '2023-08-10 09:45:42', 19),
('DoctrineMigrations\\Version20230804115124', '2023-08-10 09:45:42', 10),
('DoctrineMigrations\\Version20230804115712', '2023-08-10 09:45:42', 20),
('DoctrineMigrations\\Version20230804122141', '2023-08-10 09:45:42', 10),
('DoctrineMigrations\\Version20230804161648', '2023-08-10 09:45:42', 10);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trick`
--

CREATE TABLE `trick` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `description` longtext NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `trick_group_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trick`
--

INSERT INTO `trick` (`id`, `name`, `slug`, `description`, `picture`, `video`, `trick_group_id`, `created_at`, `updated_at`) VALUES
(1, 'Ollie Nollie', 'ollie-nollie', 'Nous avons parlé du Ollie ci-dessus, le Nollie c\'est l\'inverse. Accroupis-toi, déplace ton poids vers l\'avant, puis utilise le nez de ta planche pour sauter.', 'snowboard-home.png', NULL, 6, '2023-08-10 09:45:57', NULL),
(2, 'Tail Press', 'tail-press', 'Le Tail Press est initié en déplaçant ton poids vers l\'arrière de ta planche tout en restant droit et en soulevant le Nose de la neige.', 'https://media.istockphoto.com/id/1092719712/fr/photo/snowboardeur-sautant-dans-les-airs-avec-le-bleu-intense-du-ciel-%C3%A0-larri%C3%A8re-plan.jpg?s=612x612&w=0&k=20&c=aT-Zm-FYNBTa5hjzlfp5x_06K1SZRVydHLN3GDTulpo=', NULL, 1, '2023-08-10 09:45:57', NULL),
(3, 'Indy', 'indy', 'Attrape le carre des orteils de ta planche, entre les fixations, avec ta main arrière.', 'snowboard-home.png', NULL, 3, '2023-08-10 09:45:57', NULL),
(4, 'Stalefish', 'stalefish', 'Passe la main derrière ton genou arrière et attrape le carre de ta planche entre les fixations, côté talon, avec ta main arrière.', 'https://img.freepik.com/premium-photo/active-snowboarder-board-slides-down-snowy-mountain-illustration_305419-2169.jpg?w=2000', NULL, 7, '2023-08-10 09:45:57', NULL),
(5, 'Tail', 'tail', 'Attrape le talon de ta planche avec ta main arrière (juste à l\'extrémité, pas sur les côtés).', 'https://img.freepik.com/premium-photo/skier-is-flying-through-air-front-snowy-mountain_868783-347.jpg', NULL, 6, '2023-08-10 09:45:57', NULL),
(6, 'Wildcat', 'wildcat', 'Un Wildcat est un Backflip qui garde la planche parallèle à la trajectoire, tu fais donc une sorte de Flip \"latéral\" sans perte de vitesse.', 'snowboard-home.png', NULL, 4, '2023-08-10 09:45:57', NULL),
(7, 'Tamedog', 'tamedog', 'L\'exact opposé d\'un Wildcat est un Tamedog. C\'est un Frontflip qui garde la planche parallèle à la trajectoire. Un hard Nollie utilise le nez comme tremplin pour amorcer la rotation.', 'https://img.freepik.com/photos-premium/snowboarder-saute-montagne-generative-ai_851394-273.jpg?w=2000', NULL, 2, '2023-08-10 09:45:57', NULL),
(8, '50-50', '50-50', 'Il s\'agit de chevaucher un rail ou un box avec ta planche en ligne droite sur la structure.', 'https://img.freepik.com/photos-premium/snowboarder-volant-montagnes-sports-hiver-extremes-ai-generative_391052-12657.jpg?w=2000', NULL, 4, '2023-08-10 09:45:57', NULL),
(9, 'Frontside Boardslide', 'frontside-boardslide', 'Il s\'agit de glisser jusqu\'au rail sur ton côté arrière, puis de sauter dessus avec le nez de la planche au-dessus du rail. Tu atterris avec le rail entre tes fixations, ta planche perpendiculaire à la structure.', 'snowboard-home.png', NULL, 3, '2023-08-10 09:45:57', NULL),
(10, 'Frontside Lipslide', 'frontside-lipslide', ' Identique à la figure précédente, mais tu te diriges vers le rail en le positionnant sur ton côté avant. Tu sautes ensuite avec le talon de la planche au-dessus du rail et tu atterris dessus avec le rail entre tes fixations.', 'https://img.freepik.com/photos-premium/snowboard-glace-image-art-du-generateur-ai_848845-146.jpg?w=2000', NULL, 5, '2023-08-10 09:45:57', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `trickgroup`
--

CREATE TABLE `trickgroup` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trickgroup`
--

INSERT INTO `trickgroup` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Butter Trick', '2023-08-10 09:45:56', NULL),
(2, 'Grabs', '2023-08-10 09:45:57', NULL),
(3, 'Flips', '2023-08-10 09:45:57', NULL),
(4, 'Rail/Boxes', '2023-08-10 09:45:57', NULL),
(5, 'Rail', '2023-08-10 09:45:57', NULL),
(6, 'Corks', '2023-08-10 09:45:57', NULL),
(7, 'Spins', '2023-08-10 09:45:57', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `token` varchar(255) DEFAULT NULL,
  `token_created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `trick`
--
ALTER TABLE `trick`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D8F0A91E5E237E06` (`name`),
  ADD KEY `IDX_D8F0A91E9B875DF8` (`trick_group_id`);

--
-- Index pour la table `trickgroup`
--
ALTER TABLE `trickgroup`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trick`
--
ALTER TABLE `trick`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `trickgroup`
--
ALTER TABLE `trickgroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `trick`
--
ALTER TABLE `trick`
  ADD CONSTRAINT `FK_D8F0A91E9B875DF8` FOREIGN KEY (`trick_group_id`) REFERENCES `trickgroup` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
