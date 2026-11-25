-- Filename: manage.sql
-- Author: Nguyen Nhat Thanh (106394055)
-- Description: Creates the HR administrator `users` table (with hashed passwords)
--              and ensures the `eoi` table exists in `vinfast_db`.

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
CREATE DATABASE IF NOT EXISTS `vinfast_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `vinfast_db`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'HR Manager',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_users_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
-- Marker account: username = "admin", password = "admin" (stored as bcrypt hash)
-- Secondary HR account: username = "thanh.nguyen", password = "VinFast2026!"
--

INSERT IGNORE INTO `users` (`user_id`, `username`, `password_hash`, `full_name`, `role`) VALUES
(1, 'admin', '$2y$12$3e9fJeQxvWlCJgvhMgH.CuYmbAoegP1YbOT/XFoGVEEYaVJilxpAe', 'VinFast HR Administrator', 'HR Manager'),
(2, 'thanh.nguyen', '$2y$12$kzGO8Zk8D5206lS5SuPeBuRIp655/S/6yrYxRvieU6O7CBaRiyQk.', 'Nguyen Nhat Thanh', 'Recruitment Lead');

-- --------------------------------------------------------

--
-- Ensure `eoi` table exists (matches process_eoi.sql schema)
--

CREATE TABLE IF NOT EXISTS `eoi` (
  `EOInumber` int(11) NOT NULL AUTO_INCREMENT,
  `job_ref` varchar(10) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `dob` varchar(15) NOT NULL,
  `gender` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `state` varchar(10) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  `street_address` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `primary_skill` varchar(50) NOT NULL,
  `additional_skills` varchar(255) DEFAULT NULL,
  `other_skills` text DEFAULT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `status` enum('New','Current','Final') NOT NULL DEFAULT 'New',
  PRIMARY KEY (`EOInumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;