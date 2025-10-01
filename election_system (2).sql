-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2025 at 04:24 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `election_system`
--
CREATE DATABASE IF NOT EXISTS `election_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `election_system`;

-- --------------------------------------------------------

--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
CREATE TABLE IF NOT EXISTS `candidates` (
  `candidate_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`candidate_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`candidate_id`, `user_id`, `status`, `approved_at`) VALUES(4, 22, 'approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

DROP TABLE IF EXISTS `elections`;
CREATE TABLE IF NOT EXISTS `elections` (
  `election_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('upcoming','ongoing','completed') DEFAULT 'upcoming',
  PRIMARY KEY (`election_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `face_auth_logs`
--

DROP TABLE IF EXISTS `face_auth_logs`;
CREATE TABLE IF NOT EXISTS `face_auth_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `voter_id` int(11) DEFAULT NULL,
  `nid` varchar(20) DEFAULT NULL,
  `status` enum('success','failure') NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `voter_id` (`voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('voter','candidate','admin') NOT NULL,
  `nid` char(13) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nid` (`nid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `role`, `nid`, `created_at`) VALUES(20, 'tanha', 'tanha@gmail.com', '$2y$10$pqGCyunge5tqDLs8yhsXbu80xM1JQ9Yu06tDTf04mzqlfR0GBbbF2', 'admin', '0987654321234', '2025-09-28 13:53:01');
INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `role`, `nid`, `created_at`) VALUES(22, 'opu', 'opu@gmail.com', '$2y$10$z0YzogJ.ifl8epLzCVBXKuWiQpM0hEcwRmMTbFZH6Y2oo4/GWQtKm', 'candidate', '1234567891234', '2025-09-28 14:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

DROP TABLE IF EXISTS `voters`;
CREATE TABLE IF NOT EXISTS `voters` (
  `voter_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `face_data_path` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`voter_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`voter_id`, `user_id`, `face_data_path`, `is_verified`) VALUES(12, 20, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `election_id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `voted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`vote_id`),
  UNIQUE KEY `unique_vote` (`election_id`,`voter_id`),
  KEY `voter_id` (`voter_id`),
  KEY `candidate_id` (`candidate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `face_auth_logs`
--
ALTER TABLE `face_auth_logs`
  ADD CONSTRAINT `face_auth_logs_ibfk_1` FOREIGN KEY (`voter_id`) REFERENCES `voters` (`voter_id`) ON DELETE SET NULL;

--
-- Constraints for table `voters`
--
ALTER TABLE `voters`
  ADD CONSTRAINT `voters_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`election_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`voter_id`) REFERENCES `voters` (`voter_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`candidate_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
