-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2023 at 03:49 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codexworld_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

-- Table structure for table `members`
CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `insured_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `policy_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `issue_date` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `st` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `md` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `current_premium` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `term_premium` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `net_premium` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `regular_premium_paid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `original_annual_premium` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `x2_original_annual_premium1month` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `share` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `term_date` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `persistency_factor` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `projected_persistency_reinstated` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
