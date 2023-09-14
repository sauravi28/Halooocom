-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 13, 2023 at 05:42 PM
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
-- Table structure for table `voiceFile_move_status`
--

CREATE TABLE `voiceFile_move_status` (
  `id` int(11) NOT NULL,
  `entry_date_time` datetime NOT NULL,
  `folder_name` text COLLATE utf8_unicode_ci NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci NOT NULL,
  `path` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `voiceFile_move_status`
--

INSERT INTO `voiceFile_move_status` (`id`, `entry_date_time`, `folder_name`, `file_name`, `path`) VALUES
(12, '2023-09-13 16:58:03', '2023-09-13', '20230913-124738_9370868920_Test_INT-all.gsm', '2023-09-13/20230913-124738_9370868920_Test_INT-all.gsm'),
(13, '2023-09-13 16:58:05', '2023-09-13', '20230913-124931_8551918707_Test_INT-all.gsm', '2023-09-13/20230913-124931_8551918707_Test_INT-all.gsm'),
(14, '2023-09-13 16:58:06', '2023-09-13', '20230913-143259_9370868920_Test_INT-all.gsm', '2023-09-13/20230913-143259_9370868920_Test_INT-all.gsm'),
(15, '2023-09-13 16:58:08', '2023-09-13', '20230913-143753_9370868920_Test_INT-all.gsm', '2023-09-13/20230913-143753_9370868920_Test_INT-all.gsm'),
(16, '2023-09-13 16:58:10', '2023-09-13', '20230913-143907_9370868920_Test_INT-all.gsm', '2023-09-13/20230913-143907_9370868920_Test_INT-all.gsm'),
(17, '2023-09-13 17:01:02', '2023-09-13', '20230913-165133_9370868920_Test_INT-all.gsm', '2023-09-13/20230913-165133_9370868920_Test_INT-all.gsm'),
(18, '2023-09-13 17:21:02', '2023-09-13', '20230913-171307_9308689207_Test_INT-all.gsm', '2023-09-13/20230913-171307_9308689207_Test_INT-all.gsm'),
(19, '2023-09-13 17:21:05', '2023-09-13', '20230913-171416_9370868920_Test_INT-all.gsm', '2023-09-13/20230913-171416_9370868920_Test_INT-all.gsm'),
(20, '2023-09-13 17:21:06', '2023-09-13', '20230913-171506_9370868920_Test_INT-all.gsm', '2023-09-13/20230913-171506_9370868920_Test_INT-all.gsm'),
(21, '2023-09-13 17:21:07', '2023-09-13', '20230913-171555_9370868920_Test_INT-all.gsm', '2023-09-13/20230913-171555_9370868920_Test_INT-all.gsm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `voiceFile_move_status`
--
ALTER TABLE `voiceFile_move_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `voiceFile_move_status`
--
ALTER TABLE `voiceFile_move_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
