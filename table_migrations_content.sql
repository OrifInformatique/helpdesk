-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 12, 2023 at 12:21 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2021-03-19-090827', 'User\\Database\\Migrations\\AddCiSessions', 'default', 'User', 1702381436, 1),
(2, '2021-03-19-090828', 'User\\Database\\Migrations\\AddUserType', 'default', 'User', 1702381436, 1),
(3, '2021-03-19-090829', 'User\\Database\\Migrations\\AddUser', 'default', 'User', 1702381436, 1),
(4, '2023-07-05-160700', 'User\\Database\\Migrations\\AddAzureMail', 'default', 'User', 1702381436, 1),
(5, '2023-08-08-133000', 'App\\Database\\Migrations\\AddHolidays', 'default', 'Helpdesk', 1702381436, 1),
(6, '2023-08-08-133000', 'App\\Database\\Migrations\\AddPlanning', 'default', 'Helpdesk', 1702381436, 1),
(7, '2023-08-08-133000', 'App\\Database\\Migrations\\AddPresences', 'default', 'Helpdesk', 1702381436, 1),
(8, '2023-08-08-133000', 'App\\Database\\Migrations\\AddRoles', 'default', 'Helpdesk', 1702381437, 1),
(9, '2023-08-08-133000', 'App\\Database\\Migrations\\AddStatuses', 'default', 'Helpdesk', 1702381437, 1),
(10, '2023-08-08-133000', 'App\\Database\\Migrations\\AddTerminal', 'default', 'Helpdesk', 1702381437, 1),
(11, '2023-08-08-133000', 'App\\Database\\Migrations\\AddUserData', 'default', 'Helpdesk', 1702381437, 1),
(12, '2023-08-15-150000', 'App\\Database\\Migrations\\AddLwPlanning', 'default', 'Helpdesk', 1702381437, 1),
(13, '2023-08-15-150000', 'App\\Database\\Migrations\\AddNwPlanning', 'default', 'Helpdesk', 1702381437, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
