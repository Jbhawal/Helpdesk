-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 06, 2025 at 02:44 PM
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
  `OFFEMPCODE` varchar(20) DEFAULT NULL,
  `ADMEMPCODE` varchar(20) DEFAULT NULL,
  `CUREMPCODE` varbinary(20) DEFAULT NULL,
  PRIMARY KEY (`COMPID`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`COMPID`, `CTYPE`, `SUB`, `DESCR`, `EMPCODE`, `COMPDATE`, `STATUS`, `UPLOADEDFILE`, `OFFEMPCODE`, `ADMEMPCODE`, `CUREMPCODE`) VALUES
(7, 'Software', 'eramsub', 'adtnzg', '11', '2025-06-22', 'Closed', '../uploads/i3.jpg', '21', '31', NULL),
(8, 'Hardware', 'Printer not working', 'Printer is not working.', '11', '2025-06-24', 'Closed', '../uploads/i5.png', '22', '31', NULL),
(9, 'Software', 'New subject', 'New complaint description', '12', '2025-06-24', 'Rejected by Officer', NULL, '22', '', 0x3132),
(20, 'Network', 'shf', 'fda', '21', '2025-06-24', 'Rejected', NULL, '21', '31', NULL),
(21, 'Network', 'test', 'aredfb', '12', '2025-06-24', 'Rejected by Officer', NULL, '22', '', 0x3132),
(22, 'Network', 'No Network at 6th Floor', 'No Internet connection available in entire 6th Floor', '50208', '2025-06-24', 'In Progress', NULL, '21', '31', 0x3331),
(24, 'Hardware', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'sac', '21', '2025-06-24', 'Pending', NULL, '21', '31', 0x3331),
(25, 'Hardware', 'esubject', 'hgfsg', '12', '2025-06-25', 'Rejected by Officer', NULL, '21', '', 0x3132),
(26, 'Hardware', 'test', 'sn', '22', '2025-06-25', 'Return to User', NULL, '22', '31', 0x3232),
(27, 'Hardware', 'Subject testing', 'Description for Testing', '21', '2025-06-26', 'Pending', NULL, '21', '31', 0x3331),
(28, 'Software', 'testing sub', 'testing description', '21', '2025-06-26', 'Return to User', NULL, '21', '31', 0x3231),
(29, 'Network', 'testing', 'agnsgn', '22', '2025-06-26', 'Pending', NULL, '22', '31', 0x3331),
(30, 'Hardware', '1', '1', '22', '2025-06-26', 'In Progress', NULL, '22', '31', 0x3331),
(31, 'Software', '2', '2', '22', '2025-06-26', 'In Progress', NULL, '22', '31', 0x3331),
(32, 'Network', '3', '3', '22', '2025-06-26', 'Return to User', NULL, '22', '31', 0x3232),
(34, 'Hardware', 'Monitor not displaying', 'The monitor connected to my desktop suddenly stopped displaying anything. It remains black even after restart.', '1111', '2025-06-27', 'Rejected', '../uploads/i4.png', '2111', '31', NULL),
(35, 'Hardware', 'Monitor not displaying', 'The monitor connected to my desktop suddenly stopped displaying anything. It remains black even after restart.', '1111', '2025-06-27', 'In Progress', '../uploads/i4.png', '2111', '31', 0x3331),
(37, 'Software', 'Unable to login to PF software', 'I am unable to log in to the Provident Fund portal. It keeps saying \"Invalid Credentials\" even though the details are correct.', '1111', '2025-05-23', 'Pending', NULL, '2111', '', 0x32313131),
(38, 'Network', 'No internet connection', 'There is no internet connectivity in our section since morning. All systems are affected.', '1111', '2025-06-27', 'Rejected by Officer', NULL, '2111', '', 0x31313131),
(39, 'Hardware', 'CPU overheating issue', 'My CPU gets extremely hot after 10–15 minutes of use and shuts down automatically.', '2111', '2025-06-27', 'Pending', NULL, '2111', '31', 0x3331),
(40, 'Hardware', ' Keyboard Keys Not Working', 'Several keys on my keyboard (like \"E\", \"D\", and \"Ctrl\") are not functioning properly.', '1111', '2025-06-27', 'Rejected by Officer', NULL, '2111', '', 0x31313131),
(41, 'Hardware', 'Check', 'Test', '1111', '2025-05-23', 'Pending', NULL, '2111', NULL, 0x32313131),
(42, 'Network', 'LAN Not Working', 'My system is not detecting any LAN cable input. Network icon shows \"disconnected.\"', '2111', '2025-06-27', 'Pending', NULL, '2111', '31', 0x3331),
(46, 'Hardware', 'test', 'av', '2111', '2025-06-27', 'Pending', NULL, '2111', '31', 0x3331);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `ROWID` int NOT NULL AUTO_INCREMENT,
  `COMPID` varchar(5) DEFAULT NULL,
  `FORSTATUS` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `CATEGORY` varchar(20) DEFAULT NULL,
  `REMARKS` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `REMDATE` date DEFAULT NULL,
  PRIMARY KEY (`ROWID`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`ROWID`, `COMPID`, `FORSTATUS`, `CATEGORY`, `REMARKS`, `REMDATE`) VALUES
(15, '7', 'In Progress', 'Admin', 'okay', '2025-06-24'),
(4, '7', 'Return to User', 'Officer', 'Description not understood', '2025-06-22'),
(8, '7', 'Pending', 'Employee', 're sent the complaint from Eram to Osam', '2025-06-23'),
(14, '7', 'Pending', 'Officer', 'see with img', '2025-06-23'),
(9, '7', 'Pending', 'Employee', 're sent the complaint from Eram to Osam', '2025-06-23'),
(10, '7', 'Return to User', 'Officer', 're again 1', '2025-06-23'),
(11, '7', 'Pending', 'Employee', 're sent again from Eram to Osam', '2025-06-23'),
(12, '7', 'Return to User', 'Officer', 're', '2025-06-23'),
(13, '7', 'Pending', 'Employee', 'resubmit with img', '2025-06-23'),
(16, '7', 'Closed', 'Admin', 'closing', '2025-06-24'),
(17, '4', 'Return to User', 'Admin', 'sending back to officer', '2025-06-24'),
(18, '4', 'Pending', 'Officer', 're submit from officer', '2025-06-24'),
(19, '4', 'In Progress', 'Admin', 'okay', '2025-06-24'),
(20, '4', 'Rejected', 'Admin', 'not possible', '2025-06-24'),
(21, '8', 'Return to User', 'Officer', 'need image', '2025-06-24'),
(22, '8', 'Pending', 'Employee', 'attached.', '2025-06-24'),
(23, '8', 'Pending', 'Officer', 'Okay', '2025-06-24'),
(24, '8', 'Return to User', 'Admin', 'sending back', '2025-06-24'),
(25, '8', 'Pending', 'Employee', 'fwd', '2025-06-24'),
(26, '8', 'Pending', 'Officer', 'fwdd', '2025-06-24'),
(29, '8', 'Closed', 'Admin', 'Done', '2025-06-24'),
(28, '8', 'In Progress', 'Admin', 'Okay working on it', '2025-06-24'),
(30, '9', 'Return to User', 'Officer', 'Description not understood', '2025-06-24'),
(31, '9', 'Pending', 'Employee', 're sent the complaint from E to O', '2025-06-24'),
(32, '9', 'Rejected by Officer', 'Officer', 'Description not understood', '2025-06-24'),
(33, '20', 'Return to User', 'Admin', 'sending back to officer', '2025-06-24'),
(34, '20', 'Pending', 'Officer', 'check', '2025-06-24'),
(35, '20', 'Rejected', 'Admin', 'description not understood', '2025-06-24'),
(36, '21', 'Rejected by Officer', 'Officer', 'Description not understood', '2025-06-24'),
(37, '22', 'Return to User', 'Officer', 'Plz mention date', '2025-06-24'),
(38, '22', 'Pending', 'Employee', 'No Internet connection available in entire 6th Floor forom 22.06.2025', '2025-06-24'),
(39, '22', 'Pending', 'Officer', 'Please resolve urgently', '2025-06-24'),
(42, '25', 'Rejected by Officer', 'Officer', 'Reject', '2025-06-25'),
(43, '26', 'Return to User', 'Admin', 'sending back to officer', '2025-06-25'),
(41, '22', 'In Progress', 'Admin', 'Work under progress. Informed electrical dept. No electrical line.', '2025-06-24'),
(44, '28', 'In Progress', 'Admin', 'a', '2025-06-26'),
(45, '27', 'In Progress', 'Admin', '', '2025-06-26'),
(46, '28', 'In Progress', 'Admin', '', '2025-06-26'),
(47, '28', 'In Progress', 'Admin', '', '2025-06-26'),
(48, '28', 'In Progress', 'Admin', '', '2025-06-26'),
(49, '27', 'In Progress', 'Admin', '', '2025-06-26'),
(50, '27', 'In Progress', 'Admin', '1', '2025-06-26'),
(51, '28', 'In Progress', 'Admin', '2', '2025-06-26'),
(52, '29', 'In Progress', 'Admin', '1', '2025-06-26'),
(53, '29', 'In Progress', 'Admin', '2', '2025-06-26'),
(54, '31', 'In Progress', 'Admin', '1', '2025-06-26'),
(55, '32', 'In Progress', 'Admin', '', '2025-06-26'),
(56, '30', 'In Progress', 'Admin', '1', '2025-06-26'),
(57, '30', 'In Progress', 'Admin', '1', '2025-06-26'),
(58, '31', 'In Progress', 'Admin', '', '2025-06-26'),
(59, '31', 'In Progress', 'Admin', '', '2025-06-26'),
(60, '31', 'In Progress', 'Admin', '', '2025-06-26'),
(61, '32', 'Return to User', 'Admin', '', '2025-06-26'),
(62, '32', 'Return to User', 'Admin', '', '2025-06-26'),
(63, '28', 'Return to User', 'Admin', 'cgeck', '2025-06-26'),
(64, '34', 'Pending', 'Officer', 'Okay', '2025-06-27'),
(65, '37', 'Return to User', 'Officer', 'upload image', '2025-06-27'),
(66, '40', 'Rejected by Officer', 'Officer', 'department currently closed.', '2025-06-27'),
(67, '34', 'Rejected', 'Admin', 'department closed currently.', '2025-06-27'),
(68, '35', 'In Progress', 'Admin', 'Okay working on it', '2025-06-27'),
(69, '36', 'Closed', 'Admin', '', '2025-06-27'),
(70, '44', 'In Progress', 'Admin', '', '2025-06-27');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ROWID`, `EMPCODE`, `EMPNAME`, `EMAIL`, `PHNNO`, `PWD`, `DESG`, `SEC`, `CATEGORY`, `DEPT`) VALUES
(1, '11', 'Eram', 'eram@gmail.com', '1234567891', '123456', 'edesig1', '22', 'Employee', '12'),
(2, '21', 'Osam', 'sujit2709@gmail.com', '1234512345', '123456', 'odesig1', '16', 'Officer', '45'),
(3, '31', 'Admin', 'studywithjbh@gmail.com', '1234512346', '123456', 'adesig1', '8', 'Admin', '9'),
(4, '12', 'Esam', 'joyitabhawal@gmail.com', '1234535225', '123456', 'edesig2', '12', 'Employee', '14'),
(5, '22', 'Osita', 'osita@edu.in', '3436431114', '123456', 'odesignation', '26', 'Officer', '7'),
(6, '50208', 'Babita Hansda', 'bab@gmail.com', '9330020509', 'Test@123', 'SE/IT', '11', 'Employee', '1'),
(11, '1111', 'Joy B', 'joyitabhawal@gmail.com', '9999999999', '123456', 'Trainee', '1', 'Employee', '10'),
(12, '2111', 'JB', 'jellysince1@gmail.com', '9999999999', '123456', 'Trainee officer', '11', 'Officer', '20');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
