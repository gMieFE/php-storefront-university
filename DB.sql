-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 02:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `etikeciu_parduotuve`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'Balta'),
(7, 'Spalvota'),
(8, 'Juoda');

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE `labels` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `desc` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labels`
--

INSERT INTO `labels` (`id`, `category_id`, `name`, `desc`, `price`, `photo`, `date`) VALUES
(19, 4, 'Baltas Fonas 1', 'Baltas fonas...Dydis...Kokybe...', 20.00, '1765912308_cordy.png', '2025-12-16 21:02:23'),
(20, 7, 'Melynas su Baltu', 'Didelis Buteliukas Melynas su Baltu fonas...', 25.00, '1765912321_Digestive Enzymes1.png', '2025-12-16 21:03:39'),
(21, 8, 'Juodas fonas 1', 'Maiselio lipdukas, priekis, juodas fonas....', 15.00, '1765912354_CREATINE_1000G_2025_09_03.jpg', '2025-12-16 21:04:36'),
(22, 4, 'Stilius B1', 'Blahhahahah....', 23.00, '1765912384_L-ISOLEUCINE POWDER.jpg', '2025-12-16 21:13:04');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` varchar(255) NOT NULL,
  `sum` decimal(10,2) NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `email`, `address`, `sum`, `date`) VALUES
(2, 1, 'vava', 'admin@gmail.com', 'vavavava g. Vilnius 19', 20.00, '2025-12-17 17:05:05'),
(3, 1, 'goda', 'admin@gmail.com', 'gagaga g. 19', 23.00, '2025-12-17 21:30:11'),
(4, 1, '', 'admin@gmail.com', '', 83.00, '2025-12-17 22:26:56'),
(5, 1, '', 'admin@gmail.com', '', 15.00, '2025-12-17 22:27:17'),
(6, 1, '', 'admin@gmail.com', '', 15.00, '2025-12-17 22:27:48'),
(7, 1, '', 'admin@gmail.com', '', 123.00, '2025-12-17 22:30:07'),
(8, 1, 'gg', 'admin@gmail.com', 'gg g. 19', 25.00, '2025-12-18 14:51:59');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `label_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `label_id`, `price`, `text`) VALUES
(1, 2, 19, 20.00, '1765983553_eeee.docx'),
(2, 3, 22, 23.00, '1765999794_eeee.docx'),
(3, 4, 21, 15.00, '1766003210_New Microsoft Word Document.doc'),
(4, 4, 20, 25.00, '1766003173_eeee.docx'),
(5, 4, 22, 23.00, '1766003189_eeee.docx'),
(6, 4, 19, 20.00, '1766003196_New Microsoft Word Document.doc'),
(7, 5, 21, 15.00, '1766003231_eeee.docx'),
(8, 6, 21, 15.00, '1766003263_New Microsoft Word Document.doc'),
(9, 7, NULL, 123.00, '1766003403_eeee.docx'),
(10, 8, 20, 25.00, '1766061952_eeee.docx');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass`, `role`) VALUES
(1, 'admi', 'admin@gmail.com', '$2y$10$W3VLAcFsi4uAuLI5g1iheOy.rU5SeE6VnWhhaxpBxVKdMKdbMiYNm', 'admin'),
(2, 'user', 'user@gmail.com', '$2y$10$W3VLAcFsi4uAuLI5g1iheOy.rU5SeE6VnWhhaxpBxVKdMKdbMiYNm', 'user'),
(3, 'goda', 'goda@gmail.com', '$2y$10$27CtXV7L0slvpn/gnR99KuPDn9fba96rMbr5.w0tm7.x4VmC4lfOy', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `labels_ibfk_1` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `label_id` (`label_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `labels`
--
ALTER TABLE `labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `labels`
--
ALTER TABLE `labels`
  ADD CONSTRAINT `labels_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`label_id`) REFERENCES `labels` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
