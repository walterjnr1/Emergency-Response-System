-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 02:58 PM
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
-- Database: `emergency_response`
--

-- --------------------------------------------------------

--
-- Table structure for table `emergency_alerts`
--

CREATE TABLE `emergency_alerts` (
  `id` int(11) NOT NULL,
  `elderly_id` int(11) DEFAULT NULL,
  `alert_message` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `status` enum('Pending','Responding','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_alerts`
--

INSERT INTO `emergency_alerts` (`id`, `elderly_id`, `alert_message`, `location`, `status`, `created_at`) VALUES
(149, 5, 'Emergency alert triggered by Ndueso Walter at address: 09012 Aba rd.', '09012 Aba rd', 'Responding', '2025-06-06 12:19:20'),
(150, 5, 'Emergency alert triggered by Ndueso Walter at address: 09012 Aba rd.', '09012 Aba rd', 'Pending', '2025-06-06 12:20:55');

-- --------------------------------------------------------

--
-- Table structure for table `medical_profiles`
--

CREATE TABLE `medical_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `conditions` text DEFAULT NULL,
  `medications` text DEFAULT NULL,
  `allergies` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `id` int(11) NOT NULL,
  `alert_id` int(11) DEFAULT NULL,
  `caregiver_id` int(11) DEFAULT NULL,
  `response_note` text DEFAULT NULL,
  `responded_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `caregiver_id` int(4) NOT NULL,
  `role` enum('elderly','caregiver','admin') DEFAULT NULL,
  `photo` text DEFAULT 'uploadImage/Profile/default.png',
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `latitude`, `longitude`, `caregiver_id`, `role`, `photo`, `status`, `created_at`) VALUES
(5, 'Ndueso Walter', 'newleastpaysolution@gmail.com', 'escobar2012', '08067361023', 'Ikot Obong Erong, Ikot Ekpene, Akwa Ibom, 530001, Nigeria', '5.16473562896982', '7.692146301269532', 10, 'elderly', 'uploadImage/Profile/user_5_1749121059.jpg', 1, '2025-05-27 04:01:37'),
(10, 'Glory Uko', 'newleastpaysolution@yahoo.com', 'escobar2012', NULL, NULL, NULL, NULL, 10, 'caregiver', 'uploadImage/Profile/default.png', 1, '2025-05-29 10:48:44'),
(11, 'Edet UD', 'newleastpaysolution@gmail.comss', 'escobar2012', NULL, NULL, NULL, NULL, 10, 'caregiver', 'uploadImage/Profile/default.png', 1, '2025-06-05 10:38:06'),
(12, 'Sarah Edet', 'newleastpaysolution@gmail.coma', 'escobar2012', '09043456678', NULL, NULL, NULL, 11, 'elderly', 'uploadImage/Profile/default.png', 1, '2025-06-06 07:25:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `emergency_alerts`
--
ALTER TABLE `emergency_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `elderly_id` (`elderly_id`);

--
-- Indexes for table `medical_profiles`
--
ALTER TABLE `medical_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alert_id` (`alert_id`),
  ADD KEY `caregiver_id` (`caregiver_id`);

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
-- AUTO_INCREMENT for table `emergency_alerts`
--
ALTER TABLE `emergency_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `medical_profiles`
--
ALTER TABLE `medical_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `emergency_alerts`
--
ALTER TABLE `emergency_alerts`
  ADD CONSTRAINT `emergency_alerts_ibfk_1` FOREIGN KEY (`elderly_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `medical_profiles`
--
ALTER TABLE `medical_profiles`
  ADD CONSTRAINT `medical_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`alert_id`) REFERENCES `emergency_alerts` (`id`),
  ADD CONSTRAINT `responses_ibfk_2` FOREIGN KEY (`caregiver_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
