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
-- Table structure for table `vicidial_Accountdetails`
--

CREATE TABLE `vicidial_Accountdetails` (
  `id` int(11) NOT NULL,
  `entry_date_time` datetime NOT NULL,
  `accno` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `final_city` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `zone` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `pos` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `emi_amt` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `total_overdue` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `last_payment_amount` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `last_payment_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `due` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `m0` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `m1` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `m2` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `m3` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `allocationtype` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `fileName` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vicidial_Accountdetails`
--
ALTER TABLE `vicidial_Accountdetails`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vicidial_Accountdetails`
--
ALTER TABLE `vicidial_Accountdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18568;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
