-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 10, 2024 at 06:15 PM
-- Server version: 10.2.32-MariaDB-log
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asterisk`
--

-- --------------------------------------------------------

--
-- Table structure for table `vicidial_PaymentStatus_data`
--

CREATE TABLE `vicidial_PaymentStatus_data` (
  `id` int(11) NOT NULL,
  `entry_date_time` datetime NOT NULL,
  `payment_status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `pay_date` date NOT NULL,
  `filename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mail_status` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `st` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ammount` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `client_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vicidial_PaymentStatus_data`
--
ALTER TABLE `vicidial_PaymentStatus_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vicidial_PaymentStatus_data`
--
ALTER TABLE `vicidial_PaymentStatus_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
