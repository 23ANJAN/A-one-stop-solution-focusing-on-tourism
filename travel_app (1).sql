-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 20, 2024 at 02:19 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(10) NOT NULL,
  `card_holder` varchar(100) NOT NULL,
  `card_last_four` varchar(4) NOT NULL,
  `expiry_date` varchar(5) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_payment_status` (`payment_status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `booking_id`, `card_holder`, `card_last_four`, `expiry_date`, `payment_status`, `created_at`) VALUES
(1, 'CAB10938', '', '', '', 'completed', '2024-11-21 01:52:33'),
(2, 'CAB22673', '4353WQARRT3ATRWTAR', '34T2', '34/53', 'completed', '2024-11-21 01:56:17'),
(3, 'CAB90870', '345345q345345', '4563', '34/53', 'completed', '2024-11-21 01:59:22'),
(4, 'bus54300', '', '', '', 'completed', '2024-11-21 02:04:15'),
(5, 'hotel79792', '', '', '', 'completed', '2024-11-21 02:05:31'),
(6, 'hotel77366', '', '', '', 'completed', '2024-11-21 02:05:57'),
(7, 'homestay16', '', '', '', 'completed', '2024-11-21 02:06:05'),
(8, 'hotel45881', '', '', '', 'completed', '2024-11-21 02:06:22'),
(9, 'Restaurant', '', '', '', 'completed', '2024-11-21 02:07:28'),
(10, 'bus52978', '', '', '', 'completed', '2024-11-21 02:08:45'),
(11, 'train63926', '', '', '', 'completed', '2024-11-21 02:09:58'),
(12, 'homestay48', '4564564564565', '5645', '45/65', 'completed', '2024-12-19 16:58:45'),
(13, 'bus21599', '', '', '', 'completed', '2024-12-19 16:59:15'),
(14, 'bus96018', '', '', '', 'completed', '2024-12-20 01:46:46'),
(15, 'CAB19030', '', '', '', 'completed', '2024-12-20 01:47:37'),
(16, 'CAB30490', '', '', '', 'completed', '2024-12-20 01:48:37'),
(17, 'homestay36', '', '', '', 'completed', '2024-12-20 02:01:29'),
(18, 'hotel80759', '', '', '', 'completed', '2024-12-20 02:05:01');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
