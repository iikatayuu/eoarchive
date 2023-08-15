-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 15, 2023 at 01:37 PM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eoarchive`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admins`
--

DROP TABLE IF EXISTS `tbl_admins`;
CREATE TABLE IF NOT EXISTS `tbl_admins` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Account ID',
  `username` varchar(255) NOT NULL COMMENT 'Account username',
  `password` varchar(72) NOT NULL COMMENT 'Account hashed password',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tbl_admins`
--

INSERT INTO `tbl_admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$Hgn1zM0cucafwi0M8ayO.Ohf4LPgR.yH0G228YnfgMH8g9YDsGcr2');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eo`
--

DROP TABLE IF EXISTS `tbl_eo`;
CREATE TABLE IF NOT EXISTS `tbl_eo` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'EO ID',
  `number` int NOT NULL COMMENT 'EO Number',
  `series` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'EO Series',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'EO Title/Description',
  `filename` varchar(255) NOT NULL COMMENT 'EO PDF Original Filename',
  `author` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'EO Author',
  `author_position` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'EO Author Position',
  `approved_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'EO Approved By',
  `added_by` int NOT NULL COMMENT 'Added by',
  `date_approved` date NOT NULL COMMENT 'EO Approved Date',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'EO Date Created',
  PRIMARY KEY (`id`),
  KEY `ADMIN_FK_1` (`added_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='List of Executive Orders';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_eo`
--
ALTER TABLE `tbl_eo`
  ADD CONSTRAINT `ADMIN_FK_1` FOREIGN KEY (`added_by`) REFERENCES `tbl_admins` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
