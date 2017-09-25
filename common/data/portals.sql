-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2017 at 01:32 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portals`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `birth_cert_no` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Birth Certificate No.',
  `id_no` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT 'National ID. No.',
  `kra_pin` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'KRA PIN',
  `phone` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Phone No.',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Email Address',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Username',
  `user_type` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'User Type',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Authentication Key',
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Password Hash',
  `status` smallint(6) NOT NULL DEFAULT '3' COMMENT 'Status',
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Password Reset Token',
  `registered_by` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'self' COMMENT 'Registered By',
  `registered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Registered At',
  `created_at` int(11) NOT NULL COMMENT 'Created At',
  `modified_by` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Modified By',
  `modified_at` datetime DEFAULT NULL COMMENT 'Modified At',
  `updated_at` int(11) NOT NULL COMMENT 'Updated At'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
