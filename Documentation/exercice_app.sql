-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le : ven. 07 mars 2025 à 07:45
-- Version du serveur : 8.2.0
-- Version de PHP : 8.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `exercice_app`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `insert_user`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_user` (IN `p_username` VARCHAR(200), IN `p_email` VARCHAR(188), IN `p_password` VARCHAR(255), IN `p_role` ENUM('enfant','parent','enseignant'))   BEGIN
    DECLARE user_exists INT;

    -- Vérifier si l'utilisateur existe déjà
    SELECT COUNT(*) INTO user_exists
    FROM users
    WHERE username = p_username OR email = p_email;

    IF user_exists = 0 THEN
        -- Si l'utilisateur n'existe pas, insérer
        INSERT INTO users (username, email, password, role, created_at)
        VALUES (p_username, p_email, p_password, p_role, NOW());
    ELSE
        -- Si l'utilisateur existe déjà, afficher un message ou gérer le cas
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Doublon détecté';
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `exercices`
--

DROP TABLE IF EXISTS `exercices`;
CREATE TABLE IF NOT EXISTS `exercices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `exercice_type` varchar(50) NOT NULL,
  `min_value` int NOT NULL,
  `max_value` int NOT NULL,
  `created_by` int NOT NULL,
  `exercice_name` varchar(255) NOT NULL,
  `exercice_image` varchar(255) NOT NULL,
  `teacher_id` int DEFAULT NULL,
  `class_level` enum('CP','CE1','CE2','CM1','CM2') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `email` varchar(188) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('enfant','parent','enseignant') NOT NULL,
  `child_name` varchar(100) DEFAULT NULL,
  `teacher_students` text,
  `teacher_id` int DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `class_level` enum('CP','CE1','CE2','CM1','CM2') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`, `child_name`, `teacher_students`, `teacher_id`, `parent_id`, `class_level`) VALUES
(40, 'Mr.Synave', 'Synave@gmail.com', '$2y$10$3TCUdU6q4nod5PqQQUubCuxAcg/u0z3jU7ZCoP5sJwaIKZe9qRT3.', '2025-03-06 13:56:37', 'enseignant', NULL, 'Eve Mazurier, Alexis Payen', NULL, NULL, NULL),
(39, 'Mme.Kerkeni', 'kerkeni@gmail.com', '$2y$10$TN6OqyprhlUBH3IJEjCi1OSqGpvVw6ERPHDMJrnp0VD0VxbtDeQxS', '2025-03-06 13:56:06', 'parent', 'Eve Mazurier', NULL, NULL, NULL, NULL),
(38, 'Mme.Martin', 'martin@gmail.com', '$2y$10$iJ8vvoxk2bTrcSbaiArGh.u3yvcfNLlFsPFdewhxPUBnJI3TmZyCS', '2025-03-06 13:55:34', 'parent', 'Alexis Payen, Brian Dupuis', NULL, NULL, NULL, NULL),
(37, 'Brian Dupuis', NULL, '$2y$10$SdgSmJupVud0TH0ucKDoMOkW6tbLFuD3QSNVAgkCqs9xTYhdEKFru', '2025-03-06 13:54:25', 'enfant', NULL, NULL, 41, 38, 'CE2'),
(36, 'Eve Mazurier', NULL, '$2y$10$1nPoaSIBLK.kP0XSTFi8Ju16mmB7S3/swX/WpjlTjn58wMJET.muO', '2025-03-06 13:54:01', 'enfant', NULL, NULL, 40, 39, 'CE1'),
(35, 'Alexis Payen', NULL, '$2y$10$Js0vGdYPE2vZWxKsPUDZSez/qTqZLXOZ5KzGZoo/JdMPKgAUfLF3G', '2025-03-06 13:53:33', 'enfant', NULL, NULL, 40, 38, 'CE1'),
(41, 'Mme.Pacou', 'pacou@gmail.com', '$2y$10$DXF.WhAJBDsGGvHrbp6y9eci5QBcafPLIwfD8lCkZAvt.lJKgTeES', '2025-03-06 13:56:59', 'enseignant', NULL, 'Brian Dupuis', NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
