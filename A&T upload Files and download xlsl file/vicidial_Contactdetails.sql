-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 31, 2024 at 03:05 PM
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
-- Table structure for table `vicidial_Contactdetails`
--

CREATE TABLE `vicidial_Contactdetails` (
  `id` int(11) NOT NULL,
  `entry_date_time` datetime NOT NULL,
  `account_reference` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `financier_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `currentaddress` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `currentfinalcity` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phn_1_curr_contact1` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phn_3_curr_mobile1` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `permanent_email_address` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `permanentfinalcity` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phn_5_perm_contact1` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phn_7_perm_mobile1` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `workaddress` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `workfinalcity` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phn_10_work_contact2` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phn_11_work_mobile1` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phn_9_work_contact1` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `last_phone_contacted` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phonenumber1` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phonenumber2` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phonenumber3` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `fileName` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vicidial_Contactdetails`
--
ALTER TABLE `vicidial_Contactdetails`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vicidial_Contactdetails`
--
ALTER TABLE `vicidial_Contactdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18559;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
