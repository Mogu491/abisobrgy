-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 16, 2026 at 07:55 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abisobrgy`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sendTo` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `time` datetime DEFAULT NULL,
  `zone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `sendTo`, `message`, `time`, `zone`) VALUES
(88, 'Assembly', 'residents', 'we have meeting', '2026-04-16 07:17:22', 'Zone 3');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_logs`
--

DROP TABLE IF EXISTS `announcement_logs`;
CREATE TABLE IF NOT EXISTS `announcement_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `audience` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `response` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `zone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement_logs`
--

INSERT INTO `announcement_logs` (`id`, `title`, `audience`, `number`, `response`, `created_at`, `zone`) VALUES
(164, 'Assembly', 'residents', '+639918726085', '{\"message\":{\"status\":\"pending\",\"metadata\":{},\"content\":\"Assembly : we have meeting\",\"created\":\"2026-04-16T07:17:24Z\",\"reference_id\":\"msg_d392ad37-e022-46e8-9cc9-ca88597ab1c3\",\"recipient\":\"+639918726085\",\"fail_reason\":null}}', '2026-04-16 07:17:23', 'Zone 3');

-- --------------------------------------------------------

--
-- Table structure for table `population_forecast`
--

DROP TABLE IF EXISTS `population_forecast`;
CREATE TABLE IF NOT EXISTS `population_forecast` (
  `id` int NOT NULL AUTO_INCREMENT,
  `year` int NOT NULL,
  `actual_population` int DEFAULT NULL,
  `predicted_population` int DEFAULT NULL,
  `growth_rate` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `population_forecast`
--

INSERT INTO `population_forecast` (`id`, `year`, `actual_population`, `predicted_population`, `growth_rate`) VALUES
(1, 2026, 2300, 2714, 0.00),
(2, 2030, 5600, 4855, 78.89),
(3, 2035, 7200, 7531, 55.12);

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `zone` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('resident','official') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `phone`, `zone`, `type`, `created_at`) VALUES
(27, '09918726085', 'Zone 3', 'resident', '2026-04-16 02:36:19');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
