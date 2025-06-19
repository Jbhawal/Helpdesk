-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 18, 2025 at 07:53 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `helpdesk`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
CREATE TABLE IF NOT EXISTS `complaints` (
  `COMPID` int NOT NULL AUTO_INCREMENT,
  `CTYPE` varchar(15) DEFAULT NULL,
  `SUB` varchar(100) DEFAULT NULL,
  `DESCR` varchar(250) DEFAULT NULL,
  `EMPCODE` varchar(5) DEFAULT NULL,
  `COMPDATE` date DEFAULT NULL,
  `STATUS` varchar(50) DEFAULT NULL,
  `UPLOADEDFILE` varchar(50) DEFAULT NULL,
  `FORWARDTO` varchar(25) DEFAULT NULL,
  `OFFNAME` varchar(50) DEFAULT NULL,
  `OFFREM` varchar(150) DEFAULT NULL,
  `ADMREM` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`COMPID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`COMPID`, `CTYPE`, `SUB`, `DESCR`, `EMPCODE`, `COMPDATE`, `STATUS`, `UPLOADEDFILE`, `FORWARDTO`, `OFFNAME`, `OFFREM`, `ADMREM`) VALUES
(1, 'Hardware', 'osub1', 'aaaaaaaaaaaaaaa dddddddddddddddddd', '21', '2025-06-18', 'Pending', NULL, 'Forwarded to Admin', '', NULL, NULL),
(2, 'Software', 'osub2', 'aasscc', '21', '2025-06-18', 'Pending', NULL, 'Forwarded to Admin', '', NULL, NULL),
(3, 'Network', 'esub1', 'gresvf', '11', '2025-06-18', 'Forwarded to Admin', '../uploads/i2.jpg', '21', 'Osam', NULL, NULL),
(4, 'Hardware', 'osub3', 'dshsrj', '21', '2025-06-18', 'Pending', NULL, '31', 'Arita', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `ROWID` int NOT NULL AUTO_INCREMENT,
  `COMPID` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EMPCODE` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `OREM` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ODATE` date DEFAULT NULL,
  `AREM` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ADATE` date DEFAULT NULL,
  PRIMARY KEY (`ROWID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mastertable`
--

DROP TABLE IF EXISTS `mastertable`;
CREATE TABLE IF NOT EXISTS `mastertable` (
  `ROWID` int NOT NULL AUTO_INCREMENT,
  `CODEHEAD` varchar(4) DEFAULT NULL,
  `UCODE` int DEFAULT NULL,
  `SHORTDESC` varchar(16) DEFAULT NULL,
  `LONGDESC` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`ROWID`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `mastertable`
--

INSERT INTO `mastertable` (`ROWID`, `CODEHEAD`, `UCODE`, `SHORTDESC`, `LONGDESC`) VALUES
(1, 'SEC', 1, 'X-I', 'Expenditure-I'),
(2, 'SEC', 2, 'X-II', 'Expenditure-II'),
(3, 'SEC', 3, 'SBS', 'Stores Bill - Stock'),
(4, 'SEC', 4, 'SBNS', 'Stores Bill - Non Stock'),
(5, 'SEC', 5, 'STS', 'Stores - Suspense'),
(6, 'SEC', 6, 'EGA', 'Establishment Bills-Gazetted'),
(7, 'SEC', 7, 'ENG', 'Establishment Bills-Non Gazetted'),
(8, 'SEC', 8, 'PF', 'PF Section'),
(9, 'SEC', 9, 'PEN', 'Pension'),
(10, 'SEC', 10, 'BKS', 'Books Section'),
(11, 'SEC', 11, 'TA', 'Traffic Accounts'),
(12, 'SEC', 12, 'TAC', 'Claims and Refunds (Traffic)'),
(13, 'SEC', 13, 'C&P', 'Cash and Pay'),
(14, 'SEC', 14, 'CTG', 'Catering'),
(15, 'SEC', 15, 'SL', 'Sleeper/Tr. Earning'),
(16, 'SEC', 16, 'WCC', 'Workmans Compensation '),
(17, 'SEC', 17, 'RCT', 'Rly-Claims-Tribunal'),
(18, 'SEC', 18, 'IROF/GRS', 'IROF/GRS'),
(19, 'SEC', 19, 'LOANS', 'Loans'),
(20, 'SEC', 20, 'NPS', 'NPS'),
(21, 'SEC', 21, 'FUEL', 'FUEL'),
(22, 'SEC', 22, 'WS Cordination', 'Workshop Cordination'),
(23, 'SEC', 23, 'PRS_UTS', 'PRS UTS Section'),
(24, 'SEC', 24, 'FAS', 'FAS'),
(25, 'SEC', 25, 'POL/HQ-STORE', 'POL/HQ-STORE'),
(26, 'SEC', 26, 'RBC-BO', 'Railway Board Contracts-BO'),
(27, 'SEC', 27, 'RBC-NBO', 'Railway Board Contracts-NBO'),
(28, 'SEC', 28, 'HIRE AND PENALTY', 'HIRE AND PENALTY'),
(29, 'SEC', 90, 'GST(ADJ) JV', 'GST(ADJ) JV'),
(30, 'SEC', 98, 'PORT-SECTION', 'PORT-SECTION'),
(31, 'SEC', 99, 'OTH', 'Others'),
(32, 'DEPT', 1, 'ACC', 'ACCOUNTS'),
(33, 'DEPT', 2, 'ADT', 'AUDIT'),
(34, 'DEPT', 3, 'ADM', 'GEN. ADMN.'),
(35, 'DEPT', 4, 'COM', 'COMMERCIAL'),
(36, 'DEPT', 5, 'ENG', 'ENGINEERING'),
(37, 'DEPT', 6, 'ELE', 'ELECTRICAL'),
(38, 'DEPT', 7, 'MEC', 'MECHANICAL'),
(39, 'DEPT', 8, 'MED', 'MEDICAL'),
(40, 'DEPT', 9, 'OPT', 'OPERATING'),
(41, 'DEPT', 10, 'PER', 'PERSONNEL'),
(42, 'DEPT', 11, 'SNT', 'SnT'),
(43, 'DEPT', 12, 'STR', 'STORES'),
(44, 'DEPT', 13, 'SEC', 'SECURITY'),
(45, 'DEPT', 14, 'RCT', 'RCT'),
(46, 'DEPT', 15, 'OTH', 'RLY BOARD'),
(47, 'DEPT', 20, 'RRB', 'RRB'),
(48, 'DEPT', 45, 'GATI', 'Gati Shakti/Construction'),
(49, 'DEPT', 99, 'NA', 'NA');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `ROWID` int NOT NULL AUTO_INCREMENT,
  `EMPCODE` varchar(5) DEFAULT NULL,
  `EMPNAME` varchar(50) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `PHNNO` varchar(15) DEFAULT NULL,
  `PWD` varchar(35) DEFAULT NULL,
  `DESG` varchar(25) DEFAULT NULL,
  `SEC` varchar(50) DEFAULT NULL,
  `CATEGORY` varchar(20) DEFAULT NULL,
  `DEPT` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ROWID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ROWID`, `EMPCODE`, `EMPNAME`, `EMAIL`, `PHNNO`, `PWD`, `DESG`, `SEC`, `CATEGORY`, `DEPT`) VALUES
(1, '11', 'Eram', 'eram@aa.in', '1234567891', '123456', 'edesig1', '22', 'E', '12'),
(2, '21', 'Osam', 'osam@aa.com', '1234512345', '123456', 'odesig1', '16', 'O', '45'),
(3, '31', 'Arita', 'arita@aa.com', '1234512346', '123456', 'adesig1', '8', 'A', '9');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
