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
-- Table structure for table `salesforceApi_data`
--

CREATE TABLE `salesforceApi_data` (
  `id` int(11) NOT NULL,
  `lead_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `loanId` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `disposition` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sub_disposition1` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sub_disposition2` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `popupdate` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `attempt_type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `attempt_status` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `mode_of_payment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `session_id` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `callDate` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `recording_filename` text COLLATE utf8_unicode_ci NOT NULL,
  `api_update` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `api_response` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `salesforceApi_data`
--

INSERT INTO `salesforceApi_data` (`id`, `lead_id`, `username`, `loanId`, `disposition`, `sub_disposition1`, `sub_disposition2`, `comments`, `amount`, `popupdate`, `attempt_type`, `attempt_status`, `mode_of_payment`, `session_id`, `phone_number`, `callDate`, `recording_filename`, `api_update`, `api_response`) VALUES
(1, '4284477', 'Test', 'SLA287895391', 'FPTP', 'Expecting Salary', '', '', '2000', '2023-09-08T16:02', 'Telecalling', 'Contact P', 'Link Payment', '8600087', '937086820', '2023-09-08 16:02:54', '20230908-160038_937086820_Test_INT-all.gsm', '0', ''),
(2, '4284483', 'Test', 'SLA287895394', 'NC', 'No contact', 'Switch off/Not reachable/DNE', 'testing API', '', '', 'Telecalling', 'No Contact', '', '8600087', '8551918707', '2023-09-08 16:17:28', '20230908-161413_8551918707_Test_INT-all.gsm', '0', ''),
(3, '4284505', 'Test', 'SLA287895399', 'EXPRD', 'Deceased', 'Date of expiry', '', '', '2023-09-09T16:32', 'Telecalling', 'Contact N', 'PAYU', '8600087', '9137015396', '2023-09-08 16:33:03', '20230908-163220_9137015396_Test_INT-all.gsm', '0', ''),
(4, '4332244', 'Test', 'SLA287895391', 'EXPRD', 'Deceased', 'Date of expiry', '', '', '2023-09-14T12:48', 'Telecalling', 'Contact N', 'NEFT', '8600087', '9370868920', '2023-09-13 12:48:55', '20230913-124738_9370868920_Test_INT-all.gsm', '1', '{\"message\":\"Data has been recieved\",\"is_token_valid\":true,\"validity_in_secs\":\"1692996278682\"}'),
(5, '4332246', 'Test', 'SLA287895393', 'FPTP', 'Expecting Salary', '', 'testing API trigger..', '10000', '2023-09-15T12:49', 'Telecalling', 'Contact P', 'SETU', '8600087', '8551918707', '2023-09-13 12:50:27', '20230913-124931_8551918707_Test_INT-all.gsm', '1', '{\"message\":\"Data has been recieved\",\"is_token_valid\":true,\"validity_in_secs\":\"1692996280321\"}'),
(6, '4333593', 'Test', 'SLA2878953948', 'PTP', '', '', 'test', '234566', '2023-09-15T14:33', 'Telecalling', 'Contact P', 'CASH', '8600087', '9370868920', '2023-09-13 14:33:53', '20230913-143259_9370868920_Test_INT-all.gsm', '1', '{\"message\":\"Data is in invalid format\"}'),
(7, '4333595', 'Test', 'SLA287895395', 'CB', 'Call Back', 'Date and time', '', '', '2023-09-22T14:38', 'Telecalling', 'Contact N', 'NEFT', '8600087', '9370868920', '2023-09-13 14:38:52', '20230913-143753_9370868920_Test_INT-all.gsm', '1', '{\"message\":\"Data has been recieved\",\"is_token_valid\":true,\"validity_in_secs\":\"1692996284058\"}'),
(8, '4333597', 'Test', 'SLA287895399', 'PTP', '', '', '', '2435', '2023-09-23T14:39', 'Telecalling', 'Contact P', 'WALLET', '8600087', '9370868920', '2023-09-13 14:39:50', '20230913-143907_9370868920_Test_INT-all.gsm', '1', '{\"message\":\"Data has been recieved\",\"is_token_valid\":true,\"validity_in_secs\":\"1692996285435\"}'),
(9, '4333597', 'Test', 'SLA287895399', 'PTP', '', '', 'sdcsdcsdcsdcs', '2435', '2023-09-23T14:39', 'Telecalling', 'Contact P', 'WALLET', '8600087', '9370868920', '2023-09-13 16:51:55', '20230913-165133_9370868920_Test_INT-all.gsm', '1', '{\"message\":\"Data has been recieved\",\"is_token_valid\":true,\"validity_in_secs\":\"1692996457734\"}'),
(11, '4335446', 'Test', 'SLA287895390', 'FPTP', 'Expecting Salary', '', '', '34435', '2023-09-17T17:13', 'Telecalling', 'Contact P', '', '8600087', '9308689207', '2023-09-13 17:14:03', '20230913-171307_9308689207_Test_INT-all.gsm', '1', '{\"message\":\"Data has been recieved\",\"is_token_valid\":true,\"validity_in_secs\":\"1692997656507\"}'),
(12, '4335449', 'Test', 'SLA287895396', 'PAID', 'Customer Claims Paid', '', 'dfdhgfjhgjhj', '23324', '2023-09-15T17:14', 'Telecalling', 'Contact P', 'NETBANKING_DD', '8600087', '9370868920', '2023-09-13 17:14:56', '20230913-171416_9370868920_Test_INT-all.gsm', '1', '{\"message\":\"Data has been recieved\",\"is_token_valid\":true,\"validity_in_secs\":\"1692997659099\"}'),
(13, '4335450', 'Test', 'SLA287895396', 'Issue', 'Any other reason', 'Any other reason', 'xgfgjghj', '', '', 'Telecalling', 'Contact P', 'NACH', '8600087', '9370868920', '2023-09-13 17:15:41', '20230913-171506_9370868920_Test_INT-all.gsm', '1', '{\"message\":\"Data has been recieved\",\"is_token_valid\":true,\"validity_in_secs\":\"1692997660403\"}'),
(14, '4335451', 'Test', 'SLA287895398', 'Issue', 'Medical Issue', 'Medical Emergency / Covid-19 Affected', 'hjkjoioi', '', '', 'Telecalling', 'Contact P', 'SETU', '8600087', '9370868920', '2023-09-13 17:16:55', '20230913-171555_9370868920_Test_INT-all.gsm', '1', '{\"message\":\"Data has been recieved\",\"is_token_valid\":true,\"validity_in_secs\":\"1692997661732\"}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `salesforceApi_data`
--
ALTER TABLE `salesforceApi_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `salesforceApi_data`
--
ALTER TABLE `salesforceApi_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
