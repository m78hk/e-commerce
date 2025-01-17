-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 02, 2024 at 01:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abcshop_mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_accounts`
--

DROP TABLE IF EXISTS `tb_accounts`;
CREATE TABLE IF NOT EXISTS `tb_accounts` (
  `uid` int(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `payment_info` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `role` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'user',
  `is_admin` tinyint(1) DEFAULT 0,
  `firebase_uid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_accounts`
--

INSERT INTO `tb_accounts` (`uid`, `username`, `password`, `email`, `phone`, `address`, `payment_info`, `role`, `is_admin`, `firebase_uid`) VALUES
(4, 'roy', '$2y$10$W7wOVvJjYkgdGll5C.gIw.Toq6qnZbrm/7rViCgRfKZzZ1QJUzvqu', 'abc@abc.com', '123456789', 'abcd1213', '987654321', 'user', 1, ''),
(12, 'customer1', '$2y$10$TU1h.mSvVZ3oPTE4.KV5L.HYV38SYvvNvGYXCTlq8h2kXUHaWE/Ri', 'qwe@qwe.com', '987654321', '', '', 'user', 0, ''),
(14, 'customer 3', '$2y$10$nBmOodFRSaqic6yGnWyAOeuKCcESyq8qhJeMtiwvDlutqwAVVcSKi', 'm78.roy.mo@gmail.com', NULL, NULL, NULL, 'user', 0, ''),
(15, 'customer 7', '$2y$10$EQuwJ1dUtCK0Hu75Akh0w.KSl4PTR8ifyFHLfl6bJbMfIDlDxSoXG', 'cvb@cvb.com', '0123456789', 'null', 'null', 'user', 0, 'HpwjQDNWZfXv1zkYntspdOPURCv1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
