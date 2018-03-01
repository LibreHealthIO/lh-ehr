SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2014 at 02:30 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `libreehr`
--

-- --------------------------------------------------------

--
-- Table structure for table `tcmnobjectivetypes`
--

CREATE TABLE IF NOT EXISTS `tcmnobjectivetypes` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Label` varchar(64) NOT NULL,
  `Priority` tinyint(3) unsigned DEFAULT NULL,
  `Disabled` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tcmnobjectivetypes`
--

INSERT INTO `tcmnobjectivetypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES
(1, 'Mental Health Services / Counseling', 1, '0'),
(2, 'Mental Health Services / Substance Abuse Treatment', 2, '0'),
(3, 'Mental Health Services / Residential Treatment', 3, '0'),
(4, 'Family Support / Family Education', 4, '0'),
(5, 'Educational', 5, '0'),
(6, 'Basic Needs', 6, '0'),
(7, 'Financial', 7, '0'),
(8, 'Health Care', 8, '0'),
(9, 'Legal', 9, '0'),
(10, 'Environmental Support', 10, '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2014 at 02:30 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `libreehr`
--

-- --------------------------------------------------------

--
-- Table structure for table `tcmnlocationtypes`
--

CREATE TABLE IF NOT EXISTS `tcmnlocationtypes` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Label` varchar(45) NOT NULL,
  `Priority` tinyint(3) unsigned DEFAULT NULL,
  `Disabled` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tcmnlocationtypes`
--

INSERT INTO `tcmnlocationtypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES
(1, 'Office', 1, '0'),
(2, 'Home', 2, '0'),
(3, 'School', 3, '0'),
(4, 'Other', 4, '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2014 at 02:30 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `libreehr`
--

-- --------------------------------------------------------

--
-- Table structure for table `tcmncontacttypes`
--

CREATE TABLE IF NOT EXISTS `tcmncontacttypes` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Label` varchar(45) NOT NULL,
  `Priority` tinyint(3) unsigned DEFAULT NULL,
  `Disabled` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tcmncontacttypes`
--

INSERT INTO `tcmncontacttypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES
(1, 'Phone', 1, '0'),
(2, 'Written', 2, '0'),
(3, 'Face to Face', 3, '0'),
(4, 'Other', 4, '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2014 at 02:30 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `libreehr`
--

-- --------------------------------------------------------

--
-- Table structure for table `tcmnactivitytypes`
--

CREATE TABLE IF NOT EXISTS `tcmnactivitytypes` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Label` varchar(45) NOT NULL,
  `Priority` tinyint(3) unsigned DEFAULT NULL,
  `Disabled` enum('0','1') NOT NULL DEFAULT '0',
  `definition` text,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tcmnactivitytypes`
--

INSERT INTO `tcmnactivitytypes` (`Id`, `Label`, `Priority`, `Disabled`, `definition`) VALUES
(1, 'Assessment', 1, '0', 'Initial assessment or assessment update'),
(2, 'Service Plan', 2, '0', 'Development/implementation of service plan or service plan review'),
(3, 'Linking', 3, '0', 'Providing referrals for services, supports or resources'),
(4, 'Monitoring', 4, '0', 'Monitoring the effectiveness of services and service plan'),
(5, 'Coordinating', 5, '0', 'Coordination between service providers'),
(6, 'Advocating', 6, '0', 'Assisting client with needs by communicating on their behalf'),
(7, 'Crisis Support/Intervention', 7, '0', 'Providing support and resources to reduce impact of crisis (phone call to deescalate, providing resources to prevent hospitalization, abuse report or some other critical incident)'),
(8, 'Documenting', 8, '0', 'Documentation of activities on progress note');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2014 at 02:30 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `libreehr`
--

-- --------------------------------------------------------

--
-- Table structure for table `form_tcmnote`
--

CREATE TABLE IF NOT EXISTS `form_tcmnote` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `TCMServicePlanId` bigint(20) unsigned DEFAULT NULL,
  `pid` bigint(20) NOT NULL,
  `ClientName` varchar(255) DEFAULT NULL,
  `DateWritten` varchar(255) DEFAULT NULL,
  `user` varchar(255) NOT NULL,
  `authorized` tinyint(4) NOT NULL,
  `activity` enum('''0''','''1''') NOT NULL,
  `date` datetime NOT NULL,
  `CaseManagerName` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `CaseManagerSupervisorId` varchar(255) DEFAULT NULL,
  `CaseManagerSupervisorName` varchar(255) DEFAULT NULL,
  `ManagerNote` text,
  `TotalMinutes` bigint(20) NOT NULL,
  `FinalizedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `pid` (`pid`),
  KEY `DateWritten` (`DateWritten`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2014 at 02:29 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `libreehr`
--

-- --------------------------------------------------------

--
-- Table structure for table `behavioralhealthprogressnotes`
--

CREATE TABLE IF NOT EXISTS `behavioralhealthprogressnotes` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `TCMNoteId` bigint(20) NOT NULL,
  `ProblemId` bigint(20) NOT NULL COMMENT 'ref: BehavioralHealthProblems.Id',
  `Note` text,
  PRIMARY KEY (`Id`),
  KEY `TCMNoteId` (`TCMNoteId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=476 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2014 at 02:29 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `libreehr`
--

-- --------------------------------------------------------

--
-- Table structure for table `behavioralhealthprogressactivities`
--

CREATE TABLE IF NOT EXISTS `behavioralhealthprogressactivities` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `TCMNoteId` bigint(20) NOT NULL,
  `StartTime` char(12) NOT NULL,
  `EndTime` char(12) NOT NULL,
  `LocationTypeId` bigint(20) DEFAULT NULL,
  `ActivityTypeId` bigint(20) DEFAULT NULL,
  `ContactTypeId` bigint(20) DEFAULT NULL,
  `Description` text,
  PRIMARY KEY (`Id`),
  KEY `TCMNoteId` (`TCMNoteId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=411 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
