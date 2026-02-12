SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `file_hub`
--

DROP DATABASE IF EXISTS file_hub;
CREATE DATABASE file_hub;
USE file_hub;

-- --------------------------------------------------------

--
-- Structure de la table `Posts`
--

CREATE TABLE `Posts` (
  `idPost` int(11) UNSIGNED NOT NULL,
  `idCreator` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `fileLink` text NOT NULL,
  `description` longtext NOT NULL,
  `likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `PostsUsers`
--

CREATE TABLE `PostsUsers` (
  `idPosts` int(10) UNSIGNED NOT NULL,
  `idUser` int(10) UNSIGNED NOT NULL,
  `CreatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `roles` int(11) DEFAULT 0 COMMENT '0: user\r\n1: admin\r\n2: opérateur\r\n 3: creator',
  `level` int(11) DEFAULT 0,
  `xp` int(11) DEFAULT 0,
  `profilePicture` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`idUser`, `firstName`, `lastName`, `userName`, `email`, `password`, `roles`, `level`, `xp`, `profilePicture`) VALUES
(1, 'Dev', 'Admin', 'Creator', 'empress.mommy.of.light@gmail.com', '$2y$12$DLfRhyHaj0cfeNgfAmXKb.NX3FgTO3b/rhsSEEI.b8CJntg2QbEZa', 3, 0, 0, 'Creator_pfp.png');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Posts`
--
ALTER TABLE `Posts`
  ADD PRIMARY KEY (`idPost`),
  ADD KEY `idCreator` (`idCreator`);

--
-- Index pour la table `PostsUsers`
--
ALTER TABLE `PostsUsers`
  ADD KEY `idPosts` (`idPosts`,`idUser`),
  ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`userName`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Posts`
--
ALTER TABLE `Posts`
  MODIFY `idPost` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `idUser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Posts`
--
ALTER TABLE `Posts`
  ADD CONSTRAINT `Posts_ibfk_1` FOREIGN KEY (`idCreator`) REFERENCES `Users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `PostsUsers`
--
ALTER TABLE `PostsUsers`
  ADD CONSTRAINT `PostsUsers_ibfk_1` FOREIGN KEY (`idPosts`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `PostsUsers_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `Users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
