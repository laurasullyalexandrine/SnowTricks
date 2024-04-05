-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 08 mars 2024 à 08:45
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
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `trick_id` int(11) DEFAULT NULL,
  `content` varchar(255) NOT NULL,
  `status` smallint(6) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `trick_id`, `content`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 2, 'Elles restaient paisibles, allongeant la tête en signe d\'approbation. -- Du calme! dit.', 1, '2023-11-21 13:03:21', NULL),
(2, 7, 6, 'Il tirait la bride et l\'hallucination disparaissait. À Quincampoix, pour se plaindre à Madame.', 1, '2023-11-21 13:03:21', NULL),
(3, 8, 2, 'Emma revenait à lui en parut plus blanc et plus beau que ces lacs des montagnes où le gardien.', 1, '2023-11-21 13:03:21', NULL),
(4, 9, 3, 'Lheureux même pouvait lui faire boire. Il était vêtu d\'une redingote bleue, tombant droit.', 1, '2023-11-21 13:03:21', NULL),
(5, 9, 1, 'Enfin, au revoir, madame Bovary; à votre fenêtre, et une dinde. Il avait couru le monde: il.', 1, '2023-11-21 13:03:21', NULL),
(6, 9, 8, 'Il avait pris leur mutisme et leur placidité. C\'était la quatrième était celle-ci; et chacune.', 1, '2023-11-21 13:03:21', NULL),
(7, 10, 8, 'Un garde-chasse, guéri par Monsieur, d\'une fluxion de poitrine, parce qu\'elle avait lus, et la.', 1, '2023-11-21 13:03:21', NULL),
(8, 10, 9, 'Quelle interminable soirée! Quelque chose incessamment me poussait là; j\'y suis resté comme un.', 1, '2023-11-21 13:03:21', NULL),
(9, 11, 4, 'Ils étaient tous sur la carte, elle faisait d\'un seul coup à une connaissance, et l\'introduisit.', 1, '2023-11-21 13:03:21', NULL),
(10, 11, 10, 'M. Bournisien répondit qu\'il ne pouvait assouvir. -- J\'aimerais beaucoup, dit-elle, à être nus.', 1, '2023-11-21 13:03:21', NULL);

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
('DoctrineMigrations\\Version20230707123732', '2023-11-21 13:03:07', 111),
('DoctrineMigrations\\Version20230707145111', '2023-11-21 13:03:07', 11),
('DoctrineMigrations\\Version20230721085423', '2023-11-21 13:03:07', 21),
('DoctrineMigrations\\Version20230728082120', '2023-11-21 13:03:07', 89),
('DoctrineMigrations\\Version20230728163529', '2023-11-21 13:03:07', 26),
('DoctrineMigrations\\Version20230728165650', '2023-11-21 13:03:07', 10),
('DoctrineMigrations\\Version20230803115918', '2023-11-21 13:03:07', 20),
('DoctrineMigrations\\Version20230804115124', '2023-11-21 13:03:07', 10),
('DoctrineMigrations\\Version20230804115712', '2023-11-21 13:03:07', 29),
('DoctrineMigrations\\Version20230804122141', '2023-11-21 13:03:07', 10),
('DoctrineMigrations\\Version20230804161648', '2023-11-21 13:03:07', 10),
('DoctrineMigrations\\Version20230811095850', '2023-11-21 13:03:07', 33),
('DoctrineMigrations\\Version20230811100037', '2023-11-21 13:03:08', 11),
('DoctrineMigrations\\Version20230811100330', '2023-11-21 13:03:08', 164),
('DoctrineMigrations\\Version20230811134159', '2023-11-21 13:03:08', 67),
('DoctrineMigrations\\Version20230811134628', '2023-11-21 13:03:08', 195),
('DoctrineMigrations\\Version20230825093509', '2023-11-21 13:03:08', 95),
('DoctrineMigrations\\Version20230929153438', '2023-11-21 13:03:08', 107),
('DoctrineMigrations\\Version20231003195302', '2023-11-21 13:03:08', 9),
('DoctrineMigrations\\Version20231003195753', '2023-11-21 13:03:08', 81),
('DoctrineMigrations\\Version20231003204401', '2023-11-21 13:03:08', 175),
('DoctrineMigrations\\Version20231006074944', '2023-11-21 13:03:08', 10),
('DoctrineMigrations\\Version20231008190220', '2023-11-21 13:03:08', 9),
('DoctrineMigrations\\Version20231027085026', '2023-11-21 13:03:08', 115),
('DoctrineMigrations\\Version20231111095603', '2023-11-21 13:03:09', 64),
('DoctrineMigrations\\Version20231111105515', '2023-11-21 13:03:09', 190);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `trick_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `trick_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, '655c9c901acf8.avif', '2023-11-21 13:03:28', NULL),
(2, 2, '655c9c902af48.avif', '2023-11-21 13:03:28', NULL),
(3, 3, '655c9c902f1b6.avif', '2023-11-21 13:03:28', NULL),
(4, 4, '655c9c9033161.avif', '2023-11-21 13:03:28', NULL),
(5, 5, '655c9c90350e3.avif', '2023-11-21 13:03:28', NULL),
(6, 6, '655c9c9037186.avif', '2023-11-21 13:03:28', NULL),
(7, 7, '655c9c903b187.avif', '2023-11-21 13:03:28', NULL),
(8, 8, '655c9c903f933.avif', '2023-11-21 13:03:28', NULL),
(9, 9, '655c9c9041df4.avif', '2023-11-21 13:03:28', NULL),
(10, 10, '655c9c9046152.avif', '2023-11-21 13:03:28', NULL);

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
  `trick_group_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trick`
--

INSERT INTO `trick` (`id`, `name`, `slug`, `description`, `trick_group_id`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Ollie Nollie', 'ollie-nollie', 'Nous avons parlé du Ollie ci-dessus, le Nollie c\'est l\'inverse. Accroupis-toi, déplace ton poids vers l\'avant, puis utilise le nez de ta planche pour sauter.', 1, '2023-11-21 13:03:22', NULL, 2),
(2, 'Tail Press', 'tail-press', 'Le Tail Press est initié en déplaçant ton poids vers l\'arrière de ta planche tout en restant droit et en soulevant le Nose de la neige.', 2, '2023-11-21 13:03:23', NULL, 3),
(3, 'Indy', 'indy', 'Attrape le carre des orteils de ta planche, entre les fixations, avec ta main arrière.', NULL, '2023-11-21 13:03:23', NULL, 4),
(4, 'Stalefish', 'stalefish', 'Passe la main derrière ton genou arrière et attrape le carre de ta planche entre les fixations, côté talon, avec ta main arrière.', 1, '2023-11-21 13:03:24', NULL, 5),
(5, 'Tail', 'tail', 'Attrape le talon de ta planche avec ta main arrière (juste à l\'extrémité, pas sur les côtés).', 5, '2023-11-21 13:03:25', NULL, 6),
(6, 'Wildcat', 'wildcat', 'Un Wildcat est un Backflip qui garde la planche parallèle à la trajectoire, tu fais donc une sorte de Flip \"latéral\" sans perte de vitesse.', 5, '2023-11-21 13:03:25', NULL, 7),
(7, 'Tamedog', 'tamedog', 'L\'exact opposé d\'un Wildcat est un Tamedog. C\'est un Frontflip qui garde la planche parallèle à la trajectoire. Un hard Nollie utilise le nez comme tremplin pour amorcer la rotation.', 1, '2023-11-21 13:03:26', NULL, 8),
(8, '50-50', '50-50', 'Il s\'agit de chevaucher un rail ou un box avec ta planche en ligne droite sur la structure.', 3, '2023-11-21 13:03:26', NULL, 9),
(9, 'Frontside Boardslide', 'frontside-boardslide', 'Il s\'agit de glisser jusqu\'au rail sur ton côté arrière, puis de sauter dessus avec le nez de la planche au-dessus du rail. Tu atterris avec le rail entre tes fixations, ta planche perpendiculaire à la structure.', 4, '2023-11-21 13:03:27', NULL, 10),
(10, 'Frontside Lipslide', 'frontside-lipslide', ' Identique à la figure précédente, mais tu te diriges vers le rail en le positionnant sur ton côté avant. Tu sautes ensuite avec le talon de la planche au-dessus du rail et tu atterris dessus avec le rail entre tes fixations.', 6, '2023-11-21 13:03:28', NULL, 11);

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
(1, 'Butter Trick', '2023-11-21 13:03:21', NULL),
(2, 'Grabs', '2023-11-21 13:03:21', NULL),
(3, 'Flips', '2023-11-21 13:03:21', NULL),
(4, 'Rail/Boxes', '2023-11-21 13:03:21', NULL),
(5, 'Rail', '2023-11-21 13:03:21', NULL),
(6, 'Corks', '2023-11-21 13:03:21', NULL),
(7, 'Spins', '2023-11-21 13:03:21', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `token` varchar(255) DEFAULT NULL,
  `token_created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)',
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `is_verified`, `created_at`, `updated_at`, `token`, `token_created_at`, `avatar`) VALUES
(1, 'jimmysweat@snowtricks.com', '[\"ROLE_ADMIN\"]', '$2y$13$u3vLvzzDtOFU89MyBHAsr.TcSjVg9f0PJfcz2hikx2Up3lIshNWfa', 'jimmy', 1, '2023-11-21 13:03:20', NULL, 'oiK6d68N7CEDAuXBUsULZqnUtTUnOLSX2Sy5mxDojmE', '2023-11-21 13:03:20', NULL),
(2, 'sophie@sfr.fr', '[\"ROLE_USER\"]', '$2y$13$.f9.3x89XBNTRP8gJyF8V.RJ7HpfBbmEFyonPYVyVqBAtdOphg0Pe', 'sophie', 1, '2023-11-21 13:03:21', NULL, 'mCrwTErLrYvR3taq2LDAMZl3RE5Dji1iWT9LBQpP5_U', '2023-11-21 13:03:21', NULL),
(3, 'pierre@orange.fr', '[\"ROLE_USER\"]', '$2y$13$CTRlyjMcav9/EFoirVzRkOsZ/q.wdCwv/O2heOnWtw05TWzk82Niu', 'pierre', 1, '2023-11-21 13:03:22', NULL, 'fE6uhDtLLOSt5c5-JVXJD0SDH2kfh5QNXiENLW784vU', '2023-11-21 13:03:22', NULL),
(4, 'julie@bouygues.com', '[\"ROLE_USER\"]', '$2y$13$CGbyRAGbD1lafnzj.zakBezEgot17NUmS612YjdmqUynJTuQToVYi', 'julie', 1, '2023-11-21 13:03:23', NULL, 'igcrp8sXm3ITLqilC52q3WkU2DTmf5Uo8nZ5caxozPg', '2023-11-21 13:03:23', NULL),
(5, 'nicolas@hotmail.com', '[\"ROLE_USER\"]', '$2y$13$IHeyduQbEzL5Cn.8L6y4YuoDJ8hG7K.uvAXxIgbBiGnGSnd3CMFvq', 'nicolas', 1, '2023-11-21 13:03:23', NULL, 'VSPBm2LnVRSgZadwPymktK0-agOXu8jalB5XBzC9itQ', '2023-11-21 13:03:23', NULL),
(6, 'maryse@soch.net', '[\"ROLE_USER\"]', '$2y$13$e900VdGOcCBjgffAWTKs6O5FIlfOamTarGg5xypHUyKBb8wjmiqHK', 'maryse', 1, '2023-11-21 13:03:24', NULL, '', '2023-11-21 18:08:40', NULL),
(7, 'dominique@outlook.fr', '[\"ROLE_USER\"]', '$2y$13$N/zT4mBZSk28JYF/YFcxWug2SyC0rqBhZrO5gBBFGKwgStUGWZdYu', 'dominique', 1, '2023-11-21 13:03:25', NULL, 'mCG8JNV3iGI03tqNcSRpciRdlUIyhCFzUonJ9xOwz-c', '2023-11-21 13:03:25', NULL),
(8, 'capucine@gmail.com', '[\"ROLE_USER\"]', '$2y$13$h3eUJBSq6mbN7vFvVviHCO2V8rGXT3uSaU8K.avyBCakbu3JOxLY6', 'capucine', 1, '2023-11-21 13:03:25', NULL, 'ih2xeqf32h-OdWIEsvR3I-ep_72iDXSKBaFhze9aNxo', '2023-11-21 13:03:25', NULL),
(9, 'auguste@voila.fr', '[\"ROLE_USER\"]', '$2y$13$vfWCkZL3Z5OBbrJKVW8QSeaA2oUEDWuKaKL4Bpc/27Wm.AOTPg65S', 'auguste', 1, '2023-11-21 13:03:26', NULL, 'OwCe8Y9LV-u8lyVtTVezaO0Qw1bP7WFPEm7R0YkXQus', '2023-11-21 13:03:26', NULL),
(10, 'anais@likos.net', '[\"ROLE_USER\"]', '$2y$13$echyPWqLvutsjjrZuUjwiegRDONey.D9bJzq4jjg.cvwG.Rd3F6rC', 'anais', 1, '2023-11-21 13:03:26', NULL, 'LjxamWSmKPUVjXMXbn6IB7rZKjWA4SqbTDYlmoUanDA', '2023-11-21 13:03:26', NULL),
(11, 'joseph@gmail.com', '[\"ROLE_USER\"]', '$2y$13$W7bMw8biCrJohjZrfE8vT.OZ5TlAk9akAaWcD7HE6njSdr.44upRW', 'joseph', 1, '2023-11-21 13:03:27', NULL, 'EE0sx5pp4HPq-QkMzGgqDJ6vcvOedInPtJrlwQVvo4I', '2023-11-21 13:03:27', NULL),
(15, 'laura@gmail.com', '[\"ROLE_USER\"]', '$2y$13$e2TYnYcLCfROxWzrHwyjz.UKM9ZTm3ebtunmRCJGhepfO/hJlQExS', 'Laura', 1, '2023-11-21 18:10:39', NULL, 'JJfx1fHxH5-z31Y0DSmdQ7GERahut0mTSzyijlMFank', '2023-11-21 18:10:41', 'user-avatar-655ce49059157.png');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `trick_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CB281BE2E` (`trick_id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C53D045FB281BE2E` (`trick_id`);

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
  ADD KEY `IDX_D8F0A91E9B875DF8` (`trick_group_id`),
  ADD KEY `IDX_D8F0A91EA76ED395` (`user_id`);

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
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D6495E237E06` (`name`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CC7DA2CB281BE2E` (`trick_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trick`
--
ALTER TABLE `trick`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `trickgroup`
--
ALTER TABLE `trickgroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045FB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);

--
-- Contraintes pour la table `trick`
--
ALTER TABLE `trick`
  ADD CONSTRAINT `FK_D8F0A91E9B875DF8` FOREIGN KEY (`trick_group_id`) REFERENCES `trickgroup` (`id`),
  ADD CONSTRAINT `FK_D8F0A91EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
