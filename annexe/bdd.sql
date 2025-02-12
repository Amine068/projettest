-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour projettest_amine
CREATE DATABASE IF NOT EXISTS `projettest_amine` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `projettest_amine`;

-- Listage de la structure de table projettest_amine. annonce
CREATE TABLE IF NOT EXISTS `annonce` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_post` datetime NOT NULL,
  `price` double NOT NULL,
  `state` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `telephone` int NOT NULL,
  `subcategory_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F65593E55DC6FE57` (`subcategory_id`),
  KEY `IDX_F65593E5A76ED395` (`user_id`),
  CONSTRAINT `FK_F65593E55DC6FE57` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategory` (`id`),
  CONSTRAINT `FK_F65593E5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projettest_amine.annonce : ~2 rows (environ)
INSERT INTO `annonce` (`id`, `title`, `description`, `date_of_post`, `price`, `state`, `city`, `zipcode`, `is_validated`, `is_visible`, `is_locked`, `telephone`, `subcategory_id`, `user_id`) VALUES
	(1, 'test', 'testtttt', '2025-02-11 13:28:16', 21, 'neuf', 'Mulhouse', '68100', 0, 1, 0, 101010101, NULL, 19),
	(2, 'testaaa', 'aaaa', '2025-02-11 13:29:54', 21, 'neuf', 'Mulhouse', '68100', 0, 0, 0, 101010101, NULL, 19);

-- Listage de la structure de table projettest_amine. category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projettest_amine.category : ~9 rows (environ)
INSERT INTO `category` (`id`, `name`) VALUES
	(1, 'Vidéo & Son'),
	(3, 'Ordinateur'),
	(4, 'Accessoire informatique'),
	(5, 'Photo & Caméscope'),
	(6, 'Téléphone'),
	(7, 'Jeu Vidéo'),
	(8, 'Console'),
	(9, 'Electroménager'),
	(10, 'Autre accessoire');

-- Listage de la structure de table projettest_amine. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table projettest_amine.doctrine_migration_versions : ~5 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20250204152401', '2025-02-04 15:24:17', 35),
	('DoctrineMigrations\\Version20250205134511', '2025-02-05 13:45:24', 44),
	('DoctrineMigrations\\Version20250206224203', '2025-02-07 07:37:16', 58),
	('DoctrineMigrations\\Version20250207073644', '2025-02-07 07:37:16', 3),
	('DoctrineMigrations\\Version20250210184142', '2025-02-11 07:35:35', 37),
	('DoctrineMigrations\\Version20250211073527', '2025-02-11 07:36:20', 13),
	('DoctrineMigrations\\Version20250211081648', '2025-02-11 08:16:56', 78),
	('DoctrineMigrations\\Version20250211093554', '2025-02-11 09:36:00', 76),
	('DoctrineMigrations\\Version20250211132102', '2025-02-11 13:21:05', 54),
	('DoctrineMigrations\\Version20250211132614', '2025-02-11 13:26:18', 7);

-- Listage de la structure de table projettest_amine. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projettest_amine.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table projettest_amine. subcategory
CREATE TABLE IF NOT EXISTS `subcategory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DDCA44812469DE2` (`category_id`),
  CONSTRAINT `FK_DDCA44812469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projettest_amine.subcategory : ~33 rows (environ)
INSERT INTO `subcategory` (`id`, `category_id`, `name`) VALUES
	(2, 1, 'Retroprojecteur'),
	(3, 1, 'table de mixage'),
	(4, 1, 'Téléviseurs'),
	(5, 1, 'Barres de son'),
	(6, 3, 'Ordinateur portables'),
	(7, 3, 'Ordinateurs de bureau'),
	(8, 3, 'Station de travail'),
	(9, 4, 'Sacoches et sacs'),
	(10, 4, 'Support ordinateur portable'),
	(11, 4, 'Disques durs externes'),
	(12, 4, 'Adaptateur et hubs USB'),
	(13, 4, 'Câbles et chargeurs'),
	(14, 5, 'Appareil photo'),
	(15, 5, 'Caméra d\'action'),
	(16, 5, 'Objectifs photo'),
	(17, 5, 'Trépieds et stabilisateurs'),
	(18, 6, 'Smartphones'),
	(19, 6, 'Coques et étuis'),
	(20, 6, 'Chargeurs sans fil'),
	(21, 6, 'Supports de téléphone'),
	(22, 7, 'Jeux vidéo'),
	(23, 7, 'Réalité virtuelle'),
	(24, 8, 'Console de jeux'),
	(26, 8, 'Accessoires de console'),
	(27, 8, 'Carte mémoire et disque dur'),
	(28, 8, 'Volants et pédales'),
	(29, 9, 'Réfrigérateurs et congélateurs'),
	(30, 9, 'Lave-linge et sèche-linge'),
	(31, 9, 'Aspirateurs'),
	(32, 9, 'Cuisine'),
	(33, 10, 'Montre connectées'),
	(34, 10, 'Objets connectés'),
	(35, 10, 'Autres accessoires');

-- Listage de la structure de table projettest_amine. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projettest_amine.user : ~10 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `google_id`) VALUES
	(1, 'aaa@aaa.fr', '[]', '$2y$13$u5FCiQ0Ra9KSp3saarkVd.do3IBaSLRyxJTwxtFOm0KRq/EWaUGva', 0, NULL),
	(2, 'bbb@bbb.fr', '[]', '$2y$13$0jZqK93CeslQ2cT.M.n51.ZdsspHlWSwYp81e0f5ilD8zwecq33oa', 1, NULL),
	(3, 'ccc@ccc.fr', '[]', '$2y$13$t8FlujAU2Yih.jmHyiRhiecBWG5n97zMne.56RDtQpme2F3BicBuS', 1, NULL),
	(4, 'ddd@ddd.fr', '[]', '$2y$13$ABTrY6SR.oFB4w24Ela8xecfS/KhLP6yPXcR.oPO2.ey7uGczXfYa', 1, NULL),
	(5, 'eee@eee.fr', '[]', '$2y$13$0HsIMRdOffENVm1.E6o8vOTe/eNsP/v2Zeo1UNHapEmF2CscPyZaq', 0, NULL),
	(6, 'fff@fff.fr', '[]', '$2y$13$vFAVOj.DK21G7QzNPDcEOeME4CpBHQ5V2o4YORGSMWdW.5hlN6Rbi', 1, NULL),
	(9, 'aaaa@aaa.fr', '["ROLE_USER"]', '$2y$13$IEq510YYihD/Lop/W0m4DerNobH7FQ19tmsmKwLpF0rZUICohVsTm', 0, NULL),
	(10, 'aaaaa@aa.fr', '["ROLE_USER"]', '$2y$13$eC.uELbvpcKVffV8OIYzb.wE41Ap8ZtbOe6nkqWtSi9CXaN5qtVv2', 0, NULL),
	(14, 'aaaaa@aaa.fr', '["ROLE_USER"]', '$2y$13$8P7RRdkUgXLtFLh6otIuFuOBzLF5sjSYOqJlnnt2oscURM9t4wV2C', 0, NULL),
	(19, 'aminebouguettaya5@gmail.com', '["ROLE_USER"]', NULL, 0, '111905829402869664208');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
