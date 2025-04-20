-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2024 at 04:47 AM
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
-- Database: `booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `from_place` varchar(255) NOT NULL,
  `to_place` varchar(255) NOT NULL,
  `fare` int(11) NOT NULL,
  `status` enum('Pending','Confirmed','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rider_id` int(11) DEFAULT NULL,
  `reached` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `from_place`, `to_place`, `fare`, `status`, `created_at`, `rider_id`, `reached`) VALUES
(28, 8, 'Villaba', 'Ormoc', 150, 'Confirmed', '2024-12-15 06:05:50', 1, '2024-12-15 06:06:25'),
(29, 8, 'Samar', 'Tacloban', 520, 'Confirmed', '2024-12-15 06:11:48', 4, '2024-12-15 06:15:25'),
(30, 1, 'Simangan', 'Mejia', 20, 'Confirmed', '2024-12-15 06:17:44', 4, '2024-12-15 06:19:23'),
(31, 1, 'Villaba', 'Ormoc', 150, 'Confirmed', '2024-12-15 09:13:28', 5, '2024-12-15 09:15:19'),
(32, 10, 'Samar', 'Tacloban', 520, 'Confirmed', '2024-12-15 09:19:03', 5, '2024-12-15 09:53:22'),
(33, 11, 'Baybay', 'Ormoc', 50, 'Confirmed', '2024-12-16 01:49:25', 5, '2024-12-16 01:50:58'),
(34, 11, 'Simangan', 'Cogon', 10, 'Pending', '2024-12-16 01:59:13', NULL, '2024-12-16 01:59:13'),
(35, 3, 'Baybay', 'Ormoc', 50, 'Confirmed', '2024-12-18 05:04:48', 5, '2024-12-18 05:07:39'),
(36, 13, 'Simangan', 'City', 15, 'Confirmed', '2024-12-23 06:18:34', 5, '2024-12-23 06:20:48'),
(37, 1, 'Baybay', 'Ormoc', 50, 'Confirmed', '2024-12-29 00:50:02', 1, '2024-12-29 00:51:40'),
(38, 1, 'Earth', 'Mars', 1000000, 'Confirmed', '2024-12-29 00:55:35', 1, '2024-12-29 03:06:34'),
(39, 15, 'Simangan', 'Mejia', 20, 'Confirmed', '2024-12-30 06:56:40', 6, '2024-12-30 06:58:48'),
(40, 15, 'Villaba', 'Ormoc', 150, 'Pending', '2024-12-30 07:05:00', NULL, '2024-12-30 07:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `booking_history`
--

CREATE TABLE `booking_history` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rider_id` int(11) DEFAULT NULL,
  `from_place` varchar(255) DEFAULT NULL,
  `to_place` varchar(255) DEFAULT NULL,
  `fare` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `confirmed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `arrived` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_history`
--

INSERT INTO `booking_history` (`id`, `booking_id`, `user_id`, `rider_id`, `from_place`, `to_place`, `fare`, `status`, `confirmed_at`, `arrived`) VALUES
(26, 31, 1, 5, 'Villaba', 'Ormoc', 150, 'Confirmed', '2024-12-15 09:15:19', '2024-12-15 09:15:34'),
(27, 32, 10, 5, 'Samar', 'Tacloban', 520, 'Confirmed', '2024-12-15 09:19:56', '2024-12-15 09:20:14'),
(28, 33, 11, 5, 'Baybay', 'Ormoc', 50, 'Confirmed', '2024-12-16 01:50:06', '2024-12-16 01:50:21'),
(29, 35, 3, 5, 'Baybay', 'Ormoc', 50, 'Confirmed', '2024-12-18 05:07:39', '2024-12-18 05:07:53'),
(30, 36, 13, 5, 'Simangan', 'City', 15, 'Confirmed', '2024-12-23 06:20:48', '2024-12-23 06:21:08'),
(31, 37, 1, 1, 'Baybay', 'Ormoc', 50, 'Confirmed', '2024-12-29 00:51:40', '2024-12-29 00:51:40'),
(32, 38, 1, 1, 'Earth', 'Mars', 1000000, 'Confirmed', '2024-12-29 03:06:34', '2024-12-29 03:06:34'),
(33, 39, 15, 6, 'Simangan', 'Mejia', 20, 'Confirmed', '2024-12-30 06:58:48', '2024-12-30 07:03:25');

-- --------------------------------------------------------

--
-- Table structure for table `fare`
--

CREATE TABLE `fare` (
  `id` int(11) NOT NULL,
  `from_place` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `fare` int(11) NOT NULL,
  `date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fare`
--

INSERT INTO `fare` (`id`, `from_place`, `destination`, `fare`, `date`) VALUES
(1, 'Villaba', 'Ormoc', 150, '2024-12-08'),
(2, 'Leyte', 'Manila', 1100, '2024-12-08'),
(3, 'Samar', 'Tacloban', 520, '2024-12-08'),
(4, 'Baybay', 'Ormoc', 50, '2024-12-08'),
(5, 'Simangan', 'Mejia', 20, '2024-12-08'),
(7, 'Earth', 'Neptune', 300000, '2024-12-08'),
(8, 'Simangan', 'Cogon', 10, '2024-12-16'),
(9, 'Simangan', 'City', 15, '2024-12-16'),
(10, 'ormoc doc', 'pauli', 150, '2024-12-23'),
(11, 'sm', 'ipil', 40, '2024-12-30');

-- --------------------------------------------------------

--
-- Table structure for table `rider_reg`
--

CREATE TABLE `rider_reg` (
  `rider_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `plate_number` varchar(50) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `license` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rider_reg`
--

INSERT INTO `rider_reg` (`rider_id`, `name`, `plate_number`, `contact`, `address`, `password`, `license`) VALUES
(1, 'jury', 'csz', '984920', 'tokyo', 'lovey18', 0),
(3, 'fbb c', '45t5', '988765', 'vgbngh', 'jury', 5432345),
(4, 'hatdog', 'virginia0-0', '89899', 'Highlands', 'secret', 69),
(5, 'lovely', 'hshd7', '78655', 'villaba', 'lovely123', 7878),
(6, 'rider', '234hg', '09363527', 'santo nino', 'rider123', 1246368382);

-- --------------------------------------------------------

--
-- Table structure for table `user_feedbacks`
--

CREATE TABLE `user_feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_feedbacks`
--

INSERT INTO `user_feedbacks` (`id`, `user_id`, `feedback`, `rating`) VALUES
(1, 0, 'guyguyg', 5),
(2, 3, 'jjjk', 5),
(3, 3, 'hhuhdfuhufhe', 5),
(4, 3, 'jsjferfeu', 3),
(5, 3, 'hbhh', 1),
(6, 1, 'haaaaaaaaaaa', 5),
(7, 1, 'hiuhihihh', 5),
(8, 1, 'amboooooot kahago naba ', 1),
(9, 1, 'juryyyot', 4),
(10, 11, 'kay baho ang helmet, way laba2', 3),
(11, 1, 'xxzswxw', 5),
(12, 3, 'sdxbheb', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_reg`
--

CREATE TABLE `user_reg` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_reg`
--

INSERT INTO `user_reg` (`user_id`, `name`, `number`, `email`, `password`, `created_at`) VALUES
(1, 'jury', '123', 'jury@gmail', 'jury456', '2024-11-28 10:21:16'),
(2, 'jury', '0987', 'lovely@gmail.com', '12', '2024-11-29 18:21:15'),
(3, 'lovely', '18', 'SECRET@gmail.com', 'love18_24', '2024-11-29 22:15:40'),
(5, 'juryjean', '1234', 'jm@gmail.com', '567j', '2024-11-30 09:48:59'),
(6, 'troy', '6', 'troy@gmail.com', 'troyot', '2024-12-02 14:16:49'),
(7, 'jur', '13432', 'jur@gmail.com', '123456', '2024-12-06 19:12:38'),
(8, 'richard', '09123456789', 'richard@gmail.com', 'richard123', '2024-12-15 11:43:24'),
(9, 'jury', '1234', 'jury@gmail.com', 'jury123', '2024-12-15 17:12:23'),
(10, 'juliet', '12345', 'juliet@gmail.com', 'juliet123', '2024-12-15 17:18:41'),
(11, 'Bartolome', '09123456789', 'bart@gmail.com', 'bart123', NULL),
(13, 'boy catingub', '09123456789', 'boy@gmail.com', '123', NULL),
(14, 'JOYJOY', '09631152955', 'joy@gmail.com', 'JOY123', NULL),
(15, 'user', '09876642', 'user@gmail.com', 'user123', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `rider_id` (`rider_id`);

--
-- Indexes for table `booking_history`
--
ALTER TABLE `booking_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `fare`
--
ALTER TABLE `fare`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rider_reg`
--
ALTER TABLE `rider_reg`
  ADD PRIMARY KEY (`rider_id`);

--
-- Indexes for table `user_feedbacks`
--
ALTER TABLE `user_feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_reg`
--
ALTER TABLE `user_reg`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `booking_history`
--
ALTER TABLE `booking_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `fare`
--
ALTER TABLE `fare`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rider_reg`
--
ALTER TABLE `rider_reg`
  MODIFY `rider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_feedbacks`
--
ALTER TABLE `user_feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_reg`
--
ALTER TABLE `user_reg`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_reg` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`rider_id`) REFERENCES `rider_reg` (`rider_id`);

--
-- Constraints for table `booking_history`
--
ALTER TABLE `booking_history`
  ADD CONSTRAINT `booking_history_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
