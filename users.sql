-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2026 at 05:01 AM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vinfast_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(254) NOT NULL,
  `country_code` char(2) DEFAULT NULL,
  `phone_e164` varchar(16) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('candidate','jobs_admin','application_admin','about_admin','super_admin') NOT NULL DEFAULT 'candidate',
  `account_status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `candidate_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `country_code`, `phone_e164`, `password_hash`, `role`, `account_status`, `candidate_id`, `created_at`) VALUES
(1, 'admin', 'Assessment Administrator', 'admin@gmail.com\r\n', NULL, NULL, '$2y$12$oX825GLNK6TsXkYdjwJXRudm8zTJxqzJsHZYWXztJ9t1xRgonRC.i', 'super_admin', 'active', NULL, '2026-11-26 08:36:46'),
(2, 'QuynhMina ', 'Mina', 'mina@vinfast.test', NULL, NULL, '$2y$12$CE8BqLSlqd/GNFLORBM3qOa1tCHTY.bmd3kn81DKSZFg/ObxoPKsW', 'super_admin', 'active', NULL, '2026-11-28 02:21:02'),
(3, 'EthanD', 'Duong Nguyen Khoi', 'ethan@vinfast.test', NULL, NULL, '$2y$12$ige9/mTkNxliEo0CdxtUeOMNWcT3VsBDazCOlaSLR7pbrsY1o83ae', 'jobs_admin', 'active', NULL, '2026-11-28 02:23:19'),
(4, 'thanh', 'Thanh', 'thanh@vinfast.test', NULL, NULL, '$2y$12$LuIT0yOC5JTXlM8WrFk7peIEeXYMQ0IiQaPp2lN9Eh.nAAJnKwfye', 'application_admin', 'active', NULL, '2026-11-28 02:25:12'),
(5, 'dai', 'Dai', 'dai@vinfast.test', NULL, NULL, '$2y$12$IXKGN1JaFMkAh8mdX8J7aOCd2Mt4zq4lH33J53i4xocaJ70Q27j3e', 'about_admin', 'active', NULL, '2026-11-28 02:32:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`,`phone_e164`,`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
