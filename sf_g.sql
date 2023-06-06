-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 28, 2021 at 05:44 PM
-- Server version: 8.0.26-0ubuntu0.20.04.2
-- PHP Version: 7.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `st_service`
--

-- --------------------------------------------------------

--
-- Table structure for table `sf_guard_user`
--

CREATE TABLE `sf_guard_user` (
  `id` int UNSIGNED NOT NULL,
  `id_number` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `college_id` smallint UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `fathers_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `grand_fathers_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `user_type_id` int DEFAULT '1',
  `access_domain` tinyint(1) NOT NULL,
  `mobile_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `email_address` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `alternative_email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `algorithm` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `confirm_token` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `token_selector` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `request_expires` datetime DEFAULT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) DEFAULT '1',
  `registered` tinyint(1) DEFAULT NULL,
  `is_super_admin` tinyint(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `auth` tinyint(1) NOT NULL DEFAULT '2',
  `password_changed` tinyint(1) NOT NULL,
  `recovery` tinyint(1) NOT NULL,
  `user_flag` tinyint(1) NOT NULL,
  `group_flag` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `dif` tinyint(1) NOT NULL,
  `locked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sf_guard_user`
--
ALTER TABLE `sf_guard_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_number` (`id_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sf_guard_user`
--
ALTER TABLE `sf_guard_user`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
