-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2026 at 07:20 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fashion_family`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'EUR',
  `quantity` int DEFAULT '1',
  `category` varchar(100) DEFAULT NULL,
  `article_condition` enum('new','like_new','good','fair','poor') DEFAULT 'good',
  `status` enum('active','sold','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `title`, `description`, `image_path`, `price`, `currency`, `quantity`, `category`, `article_condition`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'Montre Michael kors gold', 'Montre Michael Kors Femme Gold – Élégance intemporelle\r\n\r\nApportez une touche de luxe à votre style avec cette magnifique montre Michael Kors pour femme, au design raffiné et moderne. Son boîtier et son bracelet en finition dorée offrent une allure chic et sophistiquée, idéale pour toutes les occasions.\r\n\r\nDotée d’un cadran élégant et lumineux, cette montre allie parfaitement esthétique et fonctionnalité. Que ce soit pour une tenue de soirée ou un look quotidien, elle s’adapte à tous les styles.\r\n\r\nCaractéristiques :\r\n\r\nCouleur : Or brillant\r\nBracelet : Acier inoxydable\r\nMouvement : Quartz précis\r\nStyle : Élégant et tendance\r\nFermeture : Boucle déployante sécurisée', '/uploads/article_69e0a1ecc997d7.29970089.png', 449.99, 'EUR', 1, 'accessoires', 'new', 'active', '2025-07-16 08:46:36', '2026-04-21 19:19:04'),
(3, 3, 'T-shirt Nike Jamais Porté', 'T-shirt Nike jamais porté. De bonne qualité, il a été acheté directement au Nike Store. Toujours dans son emballage.', '/uploads/article_69e0a38d80c5b5.96870802.jpg', 40.00, 'EUR', 1, 'vetements', 'new', 'active', '2025-04-01 08:53:33', '2026-04-21 19:18:59'),
(4, 1, 'Appareil Photo Reflex', 'Canon EOS avec objectif 18-55mm.', '/uploads/article_69e09f5d687522.37790684.png', 450.00, 'EUR', 1, 'Électronique', 'like_new', 'active', '2026-04-01 12:20:50', '2026-04-21 19:18:01'),
(5, 2, 'VTT Rockrider', 'Vélo tout terrain taille L, révisé.', '/uploads/article_5f3a2b1c4d5e6f.12345678.png', 280.00, 'EUR', 1, 'Sport', 'good', 'active', '2025-12-03 10:40:50', '2026-04-21 19:17:25'),
(6, 3, 'MacBook Air M1', '8Go RAM, 256Go SSD. État impeccable.', '/uploads/article_7a8b9c0d1e2f3a.87654321.png', 750.00, 'EUR', 1, 'Informatique', 'new', 'active', '2025-07-08 09:40:50', '2026-04-21 19:17:38'),
(7, 4, 'Chaise de Bureau', 'Chaise ergonomique noire.', '/uploads/article_1a2b3c4d5e6f7g.11223344.png', 45.00, 'EUR', 2, 'Maison', 'fair', 'active', '2026-04-02 11:40:50', '2026-04-21 19:18:04'),
(8, 5, 'Livre PHP 8', 'Apprendre le PHP procédural et la POO.', '/uploads/article_9z8y7x6w5v4u3t.99887766.png', 25.50, 'EUR', 5, 'Livres', 'new', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(9, 6, 'Casque Audio Sony', 'Réduction de bruit active.', '/uploads/article_4k3j2i1h0g9f8e.55443322.png', 120.00, 'EUR', 1, 'Électronique', 'good', 'active', '2025-11-04 02:40:50', '2026-04-21 19:18:20'),
(10, 7, 'Guitare Acoustique', 'Guitare pour débutant avec housse.', '/uploads/article_1p2q3r4s5t6u7v.66554433.png', 80.00, 'EUR', 1, 'Musique', 'good', 'active', '2025-12-08 10:40:50', '2026-04-21 19:18:15'),
(11, 8, 'Console PS5', 'Édition standard avec deux manettes.', '/uploads/article_2m3n4o5p6q7r8s.22334455.png', 400.00, 'EUR', 1, 'Jeux Vidéo', 'like_new', 'active', '2026-03-10 10:40:50', '2026-04-21 19:18:10'),
(12, 9, 'Table Basse', 'Table en bois massif style industriel.', '/uploads/article_8w7x6y5z4a3b2c.77889900.png', 110.00, 'EUR', 1, 'Mobilier', 'good', 'active', '2026-04-15 09:40:50', '2026-04-21 19:18:25'),
(13, 10, 'Smartphone Android', 'Écran 6.5 pouces, 128Go stockage.', '/uploads/article_5d4c3b2a1z0y9x.10203040.png', 199.99, 'EUR', 3, 'Téléphonie', 'new', 'active', '2026-01-06 04:40:50', '2026-04-21 19:17:46'),
(14, 1, 'Clavier Mécanique', 'Switchs rouges, rétroéclairage RGB.', '/uploads/article_bc123de456fg78.98765432.png', 65.00, 'EUR', 1, 'Informatique', 'new', 'active', '2026-04-16 02:40:50', '2026-04-21 19:18:31'),
(15, 2, 'Veste de Pluie', 'Imperméable taille M.', '/uploads/article_de456fg789hi01.12121212.png', 35.00, 'EUR', 1, 'Vêtements', 'good', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(16, 3, 'Écran 27 pouces', 'Dalle IPS, résolution 4K.', '/uploads/article_fg789hi012jk34.34343434.png', 220.00, 'EUR', 2, 'Informatique', 'like_new', 'active', '2025-10-05 05:40:50', '2026-04-21 19:18:39'),
(17, 4, 'Enceinte Bluetooth', 'Étanche, autonomie 12h.', '/uploads/article_hi012jk345lm67.56565656.png', 55.00, 'EUR', 1, 'Audio', 'good', 'active', '2025-06-10 09:40:50', '2026-04-21 19:19:47'),
(18, 5, 'Jeu de Société', 'Stratégie et gestion.', '/uploads/article_jk345lm678no90.78787878.png', 30.00, 'EUR', 1, 'Loisirs', 'new', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(19, 11, 'Set de Casseroles', 'Inox, compatible induction.', '/uploads/article_mn678no901pq23.90909090.png', 85.00, 'EUR', 1, 'Cuisine', 'new', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(20, 12, 'Sac à Dos Trekking', 'Contenance 50L.', '/uploads/article_pq234rs567tu89.13131313.png', 95.00, 'EUR', 1, 'Sport', 'like_new', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(21, 13, 'Montre Connectée', 'Suivi sommeil et sport.', '/uploads/article_st567uv890wx12.14141414.png', 140.00, 'EUR', 1, 'Électronique', 'good', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(22, 14, 'Perceuse Sans Fil', 'Livrée avec 2 batteries.', '/uploads/article_uv890wx123yz45.15151515.png', 75.00, 'EUR', 1, 'Bricolage', 'fair', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(23, 15, 'Tapis de Yoga', 'Épaisseur 6mm, antidérapant.', '/uploads/article_wx123yz456ab78.16161616.png', 20.00, 'EUR', 10, 'Sport', 'new', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(24, 16, 'Machine à Café', 'À grains, broyeur intégré.', '/uploads/article_yz456ab789cd01.17171717.png', 320.00, 'EUR', 1, 'Cuisine', 'good', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(25, 17, 'Lampe de Chevet', 'Design moderne, LED.', '/uploads/article_ab789cd012ef34.18181818.png', 15.00, 'EUR', 2, 'Maison', 'new', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(26, 18, 'Drone Compact', 'Caméra 4K, stabilisateur.', '/uploads/article_cd012ef345gh67.19191919.png', 290.00, 'EUR', 1, 'Loisirs', 'like_new', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(27, 19, 'Baskets Running', 'Taille 42, très légères.', '/uploads/article_ef345gh678ij90.20202020.png', 60.00, 'EUR', 1, 'Vêtements', 'new', 'active', '2026-01-12 20:07:24', '2026-04-21 19:19:15'),
(28, 20, 'Micro Ondes', 'Puissance 800W.', '/uploads/article_gh678ij901kl23.21212121.png', 50.00, 'EUR', 1, 'Cuisine', 'poor', 'active', '2025-11-10 10:40:50', '2026-04-21 19:19:08'),
(29, 21, 'Tablette Tactile', '10 pouces, streaming.', '/uploads/article_ij901kl234mn56.22222222.png', 180.00, 'EUR', 1, 'Informatique', 'good', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(30, 22, 'Canapé 2 places', 'Tissu gris, pieds bois.', '/uploads/article_kl234mn567op89.23232323.png', 250.00, 'EUR', 1, 'Mobilier', 'fair', 'active', '2026-04-16 09:40:50', '2026-04-16 09:40:50'),
(31, 23, 'Radiateur Électrique', 'À inertie sèche.', '/uploads/article_mn567op890qr12.24242424.png', 90.00, 'EUR', 1, 'Maison', 'good', 'active', '2026-03-30 15:40:50', '2026-04-21 19:18:46'),
(32, 24, 'Aspirateur Robot', 'Connecté Wi-Fi.', '/uploads/article_op890qr123st45.25252525.png', 210.00, 'EUR', 1, 'Maison', 'like_new', 'active', '2026-04-16 20:40:50', '2026-04-21 19:18:49'),
(33, 25, 'Souris Gamer', 'Capteur optique.', '/uploads/article_qr123st456uv78.26262626.png', 40.00, 'EUR', 1, 'Informatique', 'new', 'active', '2025-12-08 10:40:50', '2026-04-21 19:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `article_id` int DEFAULT NULL,
  `body` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `article_id`, `body`, `read_at`, `created_at`) VALUES
(1, 3, 1, 2, 'Je l\'ai moins cher au Maroc faut arrêter les arnaques', '2026-04-16 08:49:32', '2026-04-16 08:48:59'),
(2, 2, 1, 2, 'vous faites une réduction de 90 pourcent :) ?', '2026-04-16 08:50:56', '2026-04-16 08:49:01'),
(3, 1, 3, 2, 'Vous etes libre Monsieur , Un grand bien vous fasse de la commander au maroc', '2026-04-16 08:51:10', '2026-04-16 08:50:46'),
(4, 1, 2, 2, 'bien evidemment vous recevrez une mniature de l\'article avec une reduction de sa quantite a 90%', '2026-04-16 08:52:02', '2026-04-16 08:51:48'),
(5, 3, 4, 7, 'coucou', NULL, '2026-04-21 17:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `buyer_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 10.00, 'shipped', '2026-04-16 08:39:04', '2026-04-16 09:32:12'),
(2, 3, 40.00, 'pending', '2026-04-21 17:46:26', '2026-04-21 17:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `article_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `article_id`, `quantity`, `price`) VALUES
(2, 2, 33, 1, 40.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `created_at`) VALUES
(1, 'mila', 'milaz@hotmail.be', 'admin', '$2y$10$lKpYX6yzxl3d1n4N9LQc4u0XgahxxT.pLYGX6i4lqhD3XmN8IZpz2', '2026-04-16 08:33:11'),
(2, 'bilal test', 'lahssibibilal@gmail.com', 'admin', '$2y$10$GgM/MiCWetUZ5p7ev65lpOgMRnBcXYhQmU5n7MQxF6bLIJL8JiC/C', '2026-04-16 08:33:41'),
(3, 'Kevin Urbain', 'kevin.urbain.pro@gmail.com', 'admin', '$2y$10$.Zr2MpyjPy0P.Vm6GCAsieYQjO5MujM.MQPUXhGIgRcwdl60/KMIK', '2026-04-16 08:45:43'),
(4, 'moi2', 'user1@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-11-08 13:30:01'),
(5, 'alpacino', 'alpacino@yahoo.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-18 16:22:06'),
(6, 'User_3', 'user3@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-24 07:56:24'),
(7, 'Donald Trump', 'user4@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-07-13 05:11:40'),
(8, 'harun', 'harun@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-03-07 16:45:02'),
(9, 'User_6', 'user6@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-01-09 10:46:06'),
(10, 'romain', 'romain@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-04-15 18:11:22'),
(11, 'Bradpitt', 'bradpitt@hotmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-29 01:14:26'),
(12, 'User_9', 'user9@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-03-27 14:14:04'),
(13, 'User_10', 'user10@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-08-27 10:02:55'),
(14, 'isaline', 'isalina@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-04-10 22:08:23'),
(15, 'melanie', 'melanie@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-02-13 23:09:04'),
(16, 'User_13', 'user13@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-06-29 15:56:07'),
(17, 'moi', 'user14@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-10-24 00:49:23'),
(18, 'User_15', 'user15@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-16 18:54:27'),
(19, 'User_16', 'user16@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-09-23 10:40:06'),
(20, 'kevinleboss', 'user17@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-06-24 11:13:04'),
(21, 'User_18', 'user18@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-12-02 14:40:59'),
(22, 'User_19', 'user19@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-11-16 06:21:37'),
(23, 'User_20', 'user20@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-30 02:21:07'),
(24, 'User_21', 'user21@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-09-20 07:16:40'),
(25, 'Eric Delbrassine', 'user22@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-04-29 19:55:54'),
(26, 'diana', 'diana@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-03-08 20:25:28'),
(27, 'User_24', 'user24@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-08-26 08:55:33'),
(28, 'User_25', 'user25@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-31 21:57:19'),
(29, 'User_26', 'user26@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-10-30 01:35:54'),
(30, 'Jackson5', 'user27@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-08-11 04:43:27'),
(31, 'User_28', 'user28@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-10 09:25:30'),
(32, 'laila', 'laila@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-03-31 23:33:07'),
(33, 'User_30', 'user30@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-02-16 08:05:00'),
(34, 'Joel Miller', 'user31@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-08-10 08:21:38'),
(35, 'User_32', 'user32@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-13 08:09:06'),
(36, 'Shrek 2', 'user33@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-09-15 06:16:33'),
(37, 'User_34', 'user34@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-02-23 21:31:25'),
(38, 'User_35', 'user35@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-03 07:23:20'),
(39, 'Monsieur X', 'user36@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-12-11 10:58:22'),
(40, 'Kerem Kucuk', 'user37@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-06-04 23:17:48'),
(41, 'User_38', 'user38@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-01-02 02:09:49'),
(42, 'User_39', 'user39@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-06-15 03:31:38'),
(43, 'User_40', 'user40@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-12-19 01:44:41'),
(44, 'User_41', 'user41@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-03-06 12:44:25'),
(45, 'User_42', 'user42@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-09-14 01:04:00'),
(46, 'Leon S. Kennedy', 'user43@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-09-09 05:42:43'),
(47, 'billy', 'billy@hotmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-18 15:57:32'),
(48, 'User_45', 'user45@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-11-21 05:15:28'),
(49, 'User_46', 'user46@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-01-03 17:00:38'),
(50, 'User_47', 'user47@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-01-31 11:52:43'),
(51, 'isaac', 'isaac@hotmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-02-08 22:57:40'),
(52, 'User_49', 'user49@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-12-29 21:46:05'),
(53, 'montre449', 'user50@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-05-13 06:33:23'),
(54, 'Billy Butcher', 'user51@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-07-17 06:04:35'),
(55, 'Gustave Jambon', 'user52@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-30 01:18:42'),
(56, 'Bella de Gims', 'user53@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-09-21 02:55:42'),
(57, 'User_54', 'user54@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-04 01:18:21'),
(58, 'guenael', 'guenael@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-03-27 12:20:34'),
(59, 'User_56', 'user56@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-11-15 00:23:45'),
(60, 'User_57', 'user57@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-14 03:33:00'),
(61, 'Patrick Bruel', 'user58@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-12-03 07:09:39'),
(62, 'Didier', 'user59@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-03-23 15:45:13'),
(63, 'clooney', 'clooney@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-24 23:53:05'),
(64, 'amazoncmieux', 'user61@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-05-15 14:45:43'),
(65, 'User_62', 'user62@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-11 16:45:17'),
(66, 'Pastrèslégal', 'user63@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-25 06:05:12'),
(67, 'Emiliano Pizzadelamama', 'user64@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-08-12 18:46:06'),
(68, 'Titouan fortnite', 'user65@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-08-04 18:10:49'),
(69, 'FatBoy', 'user66@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-10-30 01:11:44'),
(70, 'selena', 'selena@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-28 14:02:11'),
(71, 'Eric Delbrassine', 'user68@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-08-10 09:11:40'),
(72, 'User_69', 'user69@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-07-08 18:27:44'),
(73, 'ar rachid', 'user70@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-06-24 07:19:36'),
(74, 'User_71', 'user71@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-07-19 19:50:44'),
(75, 'User_72', 'user72@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-06 19:50:51'),
(76, 'User_73', 'user73@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-02-24 06:17:58'),
(77, 'User_74', 'user74@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-29 10:33:12'),
(78, 'User_75', 'user75@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-20 00:28:03'),
(79, 'User_76', 'user76@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-12-26 09:16:37'),
(80, 'User_77', 'user77@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-09-27 11:34:51'),
(81, 'Chat Gpt', 'user78@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-06-13 20:40:21'),
(82, 'Claude', 'user79@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-09-28 11:13:09'),
(83, 'leonardo', 'leonardo@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-26 08:40:39'),
(84, 'Pas très Halal', 'user81@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-11-02 00:19:44'),
(85, 'Ifa Pme', 'user82@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-09-06 14:12:39'),
(86, 'User_83', 'user83@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-08-11 12:39:59'),
(87, 'Patrick', 'user84@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-09-19 11:17:57'),
(88, 'User_85', 'user85@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-06-19 09:05:44'),
(89, 'Jean Pierre', 'user86@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-11-19 02:10:45'),
(90, 'cetaitmieuxavant', 'user87@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-09-24 22:12:31'),
(91, 'CharleroiVille', 'user88@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-09-20 23:06:16'),
(92, 'Lalie', 'lalia@gmail.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-02-13 15:29:45'),
(93, 'Jean Michel', 'user90@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-02-23 22:45:52'),
(94, 'Mohammed Henni', 'user91@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-02-03 09:56:04'),
(95, 'Marion', 'user92@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-09-22 19:58:38'),
(96, 'Le Planqué', 'user93@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-01-24 12:40:57'),
(97, 'protech', 'user94@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-11-09 18:04:48'),
(98, 'Obamama', 'user95@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-10-20 18:57:05'),
(99, 'Lepen', 'user96@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-02-25 07:06:56'),
(100, 'scarface', 'scareface@yahoo.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-23 17:19:24'),
(101, 'azertydi46', 'user98@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-07-27 07:52:10'),
(102, 'arnaqueurducoin', 'user99@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2024-05-14 01:58:31'),
(103, 'J\'adoreMons', 'user100@example.com', 'user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-10-31 00:52:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_price` (`price`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `idx_messages_sender` (`sender_id`),
  ADD KEY `idx_messages_receiver` (`receiver_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_buyer` (`buyer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
