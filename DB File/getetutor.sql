-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 13, 2012 at 09:18 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `getetutor`
--

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `tutor_name` varchar(255) NOT NULL,
  `is_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_name`, `department_id`, `tutor_name`, `is_active`) VALUES
(3, 'Open Source', 2, '', 'Y'),
(4, 'Mosfet Design', 1, '', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `code`, `name`, `status`) VALUES
(1, '001', 'Electronics & Engineering', 'Y'),
(2, '002', 'Computer Science', 'Y'),
(3, '003', 'Civil Engineering', 'N'),
(4, '004', 'Mechanical Engineering', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grp_name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `grp_created_by` int(11) NOT NULL,
  `is_active` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `grp_name`, `department_id`, `course_id`, `grp_created_by`, `is_active`) VALUES
(1, 'etest', 1, 4, 1, 'Y'),
(3, 'sbgroup', 2, 3, 1, 'Y'),
(4, 'pallavigroup', 2, 3, 40, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE IF NOT EXISTS `group_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `request_status` enum('1','0') NOT NULL DEFAULT '1',
  `request_date` datetime NOT NULL,
  `is_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `user_id`, `group_id`, `request_status`, `request_date`, `is_active`) VALUES
(1, 34, 1, '0', '2012-08-08 00:00:00', 'N'),
(4, 40, 3, '1', '2012-08-12 01:04:40', 'N'),
(6, 0, 0, '1', '2012-08-13 02:33:21', 'N'),
(7, 0, 0, '1', '2012-08-13 02:24:22', 'Y'),
(8, 1, 1, '1', '2012-08-13 04:20:13', 'Y'),
(10, 4, 40, '1', '2012-08-13 06:18:43', 'Y'),
(11, 44, 4, '0', '2012-08-13 07:44:03', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `group_posts`
--

CREATE TABLE IF NOT EXISTS `group_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `posted_by` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `group_posts`
--

INSERT INTO `group_posts` (`id`, `group_id`, `title`, `content`, `posted_by`, `is_active`) VALUES
(1, 1, 'testing', 'this is test post', 1, 0),
(10, 1, 'test title', '<p>&nbsp;this is test content 11111</p>', 1, 0),
(11, 1, 'cgncvncn', '<p>&nbsp;vcnvcncvncvn</p>', 40, 0),
(12, 1, 'dsfdsf', '<p>&nbsp;dsfdsfdsf</p>', 1, 0),
(13, 3, 'sdfsdf', '<p>sdfdsfsdfdstttttt</p>', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `log_details`
--

CREATE TABLE IF NOT EXISTS `log_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `event` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `db_add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=584 ;

--
-- Dumping data for table `log_details`
--

INSERT INTO `log_details` (`id`, `user_id`, `event`, `ip_address`, `db_add_date`) VALUES
(1, 2, 'Logged into the system', '127.0.0.1', '2011-09-16 00:00:00'),
(2, 2, 'Logged out from the system', '127.0.0.1', '2011-09-16 12:12:12'),
(3, 2, 'Logged into the system', '127.0.0.1', '2011-09-16 00:00:00'),
(4, 2, 'Changed password', '127.0.0.1', '2011-09-16 00:00:00'),
(5, 2, 'has created an user', '127.0.0.1', '2011-09-16 00:00:00'),
(6, 2, 'has changed status of an user', '127.0.0.1', '2011-09-16 00:00:00'),
(7, 3, 'has changed status of an user', '127.0.0.1', '2011-09-16 00:00:00'),
(8, 3, 'has created an user', '127.0.0.1', '2011-09-16 00:00:00'),
(9, 3, 'has created an user', '127.0.0.1', '2011-09-16 00:00:00'),
(10, 3, 'has updated an user', '127.0.0.1', '2011-09-16 00:00:00'),
(11, 3, 'has deleted an user', '127.0.0.1', '2011-09-16 00:00:00'),
(12, 3, 'has uploaded the file in the system', '127.0.0.1', '2011-09-16 00:00:00'),
(13, 3, 'has uploaded the file in the system', '127.0.0.1', '2011-09-16 00:00:00'),
(14, 3, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-16 00:00:00'),
(15, 3, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-16 00:00:00'),
(16, 3, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-16 00:00:00'),
(17, 3, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-16 00:00:00'),
(18, 2, 'has logged off from the system', '127.0.0.1', '2011-09-16 00:00:00'),
(19, 3, 'has logged in to the system', '127.0.0.1', '2011-09-16 00:00:00'),
(20, 2, 'has logged off from the system', '127.0.0.1', '2011-09-16 17:47:32'),
(21, 2, 'has logged in to the system', '127.0.0.1', '2011-09-16 17:47:35'),
(22, 2, 'has logged in to the system', '127.0.0.1', '2011-09-16 19:36:07'),
(23, 1, 'has logged off from the system', '127.0.0.1', '2011-09-16 20:44:06'),
(24, 1, 'has logged in to the system', '127.0.0.1', '2011-09-16 20:49:10'),
(25, 1, 'has logged off from the system', '127.0.0.1', '2011-09-16 21:37:13'),
(26, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 11:13:36'),
(27, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 12:05:32'),
(28, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:06:58'),
(29, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:07:30'),
(30, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:09:01'),
(31, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:09:10'),
(32, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:10:11'),
(33, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:10:22'),
(34, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:10:56'),
(35, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 12:11:28'),
(36, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:11:29'),
(37, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:11:37'),
(38, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:11:48'),
(39, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:12:03'),
(40, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:12:17'),
(41, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:12:56'),
(42, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:13:59'),
(43, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:14:44'),
(44, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:15:28'),
(45, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:16:01'),
(46, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:16:57'),
(47, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:17:36'),
(48, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:18:04'),
(49, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:18:08'),
(50, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:18:13'),
(51, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:18:33'),
(52, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:18:45'),
(53, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:19:05'),
(54, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:19:18'),
(55, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:21:59'),
(56, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:22:51'),
(57, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:22:58'),
(58, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:24:00'),
(59, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:24:32'),
(60, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:24:48'),
(61, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:24:57'),
(62, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:30:15'),
(63, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:30:17'),
(64, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:30:36'),
(65, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:31:05'),
(66, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:31:18'),
(67, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:31:38'),
(68, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:31:46'),
(69, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:32:33'),
(70, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:34:27'),
(71, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:36:35'),
(72, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:37:07'),
(73, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:37:09'),
(74, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:37:43'),
(75, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:40:02'),
(76, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:40:04'),
(77, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:40:26'),
(78, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:40:28'),
(79, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:41:06'),
(80, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:41:08'),
(81, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:45:12'),
(82, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:45:23'),
(83, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:45:27'),
(84, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:47:19'),
(85, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:47:21'),
(86, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:49:16'),
(87, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 12:49:22'),
(88, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:49:39'),
(89, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 12:50:06'),
(90, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 12:50:17'),
(91, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 12:50:26'),
(92, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 13:15:10'),
(93, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 13:15:23'),
(94, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 13:16:01'),
(95, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 13:24:29'),
(96, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 13:25:00'),
(97, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 13:25:52'),
(98, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 13:27:29'),
(99, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 13:33:53'),
(100, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 13:34:17'),
(101, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 13:35:38'),
(102, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 13:35:49'),
(103, 1, 'has logged off from the system', '127.0.0.1', '2011-09-19 13:46:23'),
(104, 1, 'has logged in to the system', '127.0.0.1', '2011-09-19 14:25:26'),
(105, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 14:25:47'),
(106, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 14:51:44'),
(107, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 14:53:23'),
(108, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 14:54:08'),
(109, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-19 14:54:43'),
(110, 1, 'has logged in to the system', '127.0.0.1', '2011-09-20 13:22:36'),
(111, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 13:54:52'),
(112, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:36:47'),
(113, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:37:52'),
(114, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:38:34'),
(115, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:38:50'),
(116, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:39:57'),
(117, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:41:52'),
(118, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:42:25'),
(119, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:43:13'),
(120, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:44:20'),
(121, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:45:37'),
(122, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:53:29'),
(123, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:56:13'),
(124, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 19:58:32'),
(125, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:04:58'),
(126, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:19:24'),
(127, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:20:58'),
(128, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:26:55'),
(129, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:38:30'),
(130, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:39:10'),
(131, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:40:11'),
(132, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:40:28'),
(133, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:40:52'),
(134, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:41:57'),
(135, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:42:49'),
(136, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:43:53'),
(137, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:44:25'),
(138, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:45:45'),
(139, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:46:54'),
(140, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:47:45'),
(141, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:49:36'),
(142, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:50:14'),
(143, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 20:51:37'),
(144, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 21:04:38'),
(145, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-20 21:11:29'),
(146, 1, 'has logged off from the system', '127.0.0.1', '2011-09-20 21:38:26'),
(147, 1, 'has logged in to the system', '127.0.0.1', '2011-09-21 10:46:51'),
(148, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 10:47:57'),
(149, 1, 'has logged in to the system', '127.0.0.1', '2011-09-21 13:09:25'),
(150, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 13:10:06'),
(151, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 13:11:29'),
(152, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 13:28:55'),
(153, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 13:36:18'),
(154, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 13:48:46'),
(155, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 13:49:07'),
(156, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 14:39:21'),
(157, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 15:10:52'),
(158, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 15:16:10'),
(159, 1, 'has logged off from the system', '127.0.0.1', '2011-09-21 16:08:51'),
(160, 1, 'has logged in to the system', '127.0.0.1', '2011-09-21 16:08:56'),
(161, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 17:12:22'),
(162, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 17:19:27'),
(163, 1, 'has uploaded the file in the system', '127.0.0.1', '2011-09-21 17:59:19'),
(164, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 18:28:04'),
(165, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 18:29:09'),
(166, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 18:30:05'),
(167, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 18:32:31'),
(168, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-21 18:34:40'),
(169, 1, 'has logged off from the system', '127.0.0.1', '2011-09-21 19:58:52'),
(170, 1, 'has logged in to the system', '127.0.0.1', '2011-09-22 15:25:20'),
(171, 1, 'has logged in to the system', '127.0.0.1', '2011-09-22 15:35:27'),
(172, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-22 16:42:54'),
(173, 1, 'has logged off from the system', '127.0.0.1', '2011-09-22 16:47:45'),
(174, 1, 'has logged in to the system', '127.0.0.1', '2011-09-28 18:14:03'),
(175, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-28 19:15:07'),
(176, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2011-09-28 19:16:57'),
(177, 1, 'has logged off from the system', '127.0.0.1', '2011-09-28 19:57:06'),
(178, 1, 'has logged in to the system', '127.0.0.1', '2011-09-29 15:11:49'),
(179, 1, 'has logged in to the system', '127.0.0.1', '2011-11-09 12:30:54'),
(180, 1, 'has logged off from the system', '127.0.0.1', '2011-11-09 12:36:28'),
(181, 1, 'has logged in to the system', '127.0.0.1', '2011-11-09 17:50:28'),
(182, 1, 'has logged off from the system', '127.0.0.1', '2011-11-09 18:55:57'),
(183, 1, 'has logged in to the system', '127.0.0.1', '2012-04-24 12:49:31'),
(184, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2012-04-24 13:41:58'),
(185, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2012-04-24 13:42:16'),
(186, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2012-04-24 13:42:19'),
(187, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2012-04-24 13:42:40'),
(188, 1, 'has logged off from the system', '127.0.0.1', '2012-04-24 13:46:50'),
(189, 1, 'has logged in to the system', '127.0.0.1', '2012-07-13 11:54:29'),
(190, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2012-07-13 11:55:03'),
(191, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2012-07-13 11:56:26'),
(192, 1, 'has downloaded leads in the file from the system', '127.0.0.1', '2012-07-13 11:58:05'),
(193, 1, 'has logged off from the system', '127.0.0.1', '2012-07-13 12:10:03'),
(194, 1, 'has logged in to the system', '127.0.0.1', '2012-07-13 12:15:13'),
(195, 1, 'has logged in to the system', '::1', '2012-07-14 15:46:12'),
(196, 1, 'has logged off from the system', '::1', '2012-07-14 16:29:36'),
(197, 2, 'has logged in to the system', '::1', '2012-07-14 16:29:43'),
(198, 2, 'has logged off from the system', '::1', '2012-07-14 16:29:51'),
(199, 1, 'has logged in to the system', '::1', '2012-07-14 16:29:58'),
(200, 1, 'has logged off from the system', '::1', '2012-07-14 16:53:06'),
(201, 1, 'has logged in to the system', '::1', '2012-07-14 16:53:12'),
(202, 1, 'has logged in to the system', '::1', '2012-07-15 15:28:44'),
(203, 1, 'has logged off from the system', '::1', '2012-07-15 22:57:51'),
(204, 1, 'has logged in to the system', '::1', '2012-07-15 22:57:58'),
(205, 1, 'has logged off from the system', '::1', '2012-07-16 01:39:25'),
(206, 1, 'has logged in to the system', '::1', '2012-07-16 01:39:35'),
(207, 1, 'has logged off from the system', '::1', '2012-07-16 13:59:16'),
(208, 1, 'has logged off from the system', '::1', '2012-07-16 13:59:16'),
(209, 1, 'has logged in to the system', '::1', '2012-07-16 13:59:26'),
(210, 1, 'has logged off from the system', '::1', '2012-07-16 15:29:45'),
(211, 1, 'has logged in to the system', '::1', '2012-07-16 15:29:51'),
(212, 1, 'has logged off from the system', '::1', '2012-07-16 16:04:08'),
(213, 1, 'has logged in to the system', '::1', '2012-07-16 16:04:15'),
(214, 1, 'has logged off from the system', '::1', '2012-07-18 00:56:01'),
(215, 1, 'has logged in to the system', '::1', '2012-07-18 00:56:09'),
(216, 1, 'has logged off from the system', '::1', '2012-07-18 01:06:16'),
(217, 1, 'has logged in to the system', '::1', '2012-07-18 01:06:22'),
(218, 1, 'has logged off from the system', '::1', '2012-07-19 13:57:36'),
(219, 1, 'has logged in to the system', '::1', '2012-07-19 13:57:59'),
(220, 1, 'has logged off from the system', '::1', '2012-07-19 15:33:12'),
(221, 1, 'has logged in to the system', '::1', '2012-07-19 15:33:22'),
(222, 1, 'has logged off from the system', '::1', '2012-07-19 15:45:08'),
(223, 1, 'has logged off from the system', '::1', '2012-07-19 16:51:56'),
(224, 1, 'has logged in to the system', '::1', '2012-07-19 17:20:48'),
(225, 1, 'has logged off from the system', '::1', '2012-07-19 17:21:05'),
(226, 1, 'has logged off from the system', '::1', '2012-07-19 18:31:12'),
(227, 1, 'has logged in to the system', '::1', '2012-07-19 18:32:08'),
(228, 1, 'has logged off from the system', '::1', '2012-07-19 18:32:10'),
(229, 1, 'has logged off from the system', '::1', '2012-07-21 00:51:11'),
(230, 1, 'has logged off from the system', '::1', '2012-07-21 23:34:31'),
(231, 1, 'has logged in to the system', '::1', '2012-07-21 23:59:12'),
(232, 1, 'has created an user', '::1', '2012-07-21 23:59:59'),
(233, 1, 'has logged off from the system', '::1', '2012-07-22 00:26:24'),
(234, 1, 'has logged in to the system', '::1', '2012-07-22 00:26:53'),
(235, 1, 'has logged off from the system', '::1', '2012-07-22 00:27:47'),
(236, 1, 'has logged in to the system', '::1', '2012-07-22 00:35:23'),
(237, 1, 'has deleted an user', '::1', '2012-07-22 00:38:30'),
(238, 1, 'has logged off from the system', '::1', '2012-07-22 00:38:34'),
(239, 0, 'has created an user', '', '2012-07-22 00:38:46'),
(240, 1, 'has logged in to the system', '::1', '2012-07-22 00:50:09'),
(241, 1, 'has deleted an user', '::1', '2012-07-22 00:50:14'),
(242, 1, 'has created an user', '::1', '2012-07-22 00:50:26'),
(243, 1, 'has logged off from the system', '::1', '2012-07-22 00:52:45'),
(244, 1, 'has logged in to the system', '::1', '2012-07-22 22:45:03'),
(245, 1, 'has created an user', '::1', '2012-07-22 22:45:33'),
(246, 1, 'has deleted an user', '::1', '2012-07-22 22:45:51'),
(247, 1, 'has created an user', '::1', '2012-07-22 22:46:05'),
(248, 1, 'has deleted an user', '::1', '2012-07-22 22:47:09'),
(249, 1, 'has deleted an user', '::1', '2012-07-22 22:53:01'),
(250, 1, 'has created an user', '::1', '2012-07-22 22:53:20'),
(251, 1, 'has deleted an user', '::1', '2012-07-22 22:55:31'),
(252, 1, 'has created an user', '::1', '2012-07-22 22:57:25'),
(253, 1, 'has created an user', '::1', '2012-07-22 22:58:20'),
(254, 1, 'has deleted an user', '::1', '2012-07-22 22:58:24'),
(255, 1, 'has deleted an user', '::1', '2012-07-22 22:58:26'),
(256, 1, 'has deleted an user', '::1', '2012-07-22 22:58:30'),
(257, 1, 'has created an user', '::1', '2012-07-22 23:48:03'),
(258, 1, 'has logged off from the system', '::1', '2012-07-23 00:16:11'),
(259, 1, 'has logged off from the system', '::1', '2012-07-23 00:17:01'),
(260, 0, 'has created an user', '', '2012-07-23 00:17:31'),
(261, 0, 'has created an user', '', '2012-07-23 00:56:53'),
(262, 1, 'has logged in to the system', '::1', '2012-07-23 01:49:02'),
(263, 1, 'has logged off from the system', '::1', '2012-07-23 09:34:55'),
(264, 0, 'has created an user', '', '2012-07-23 09:35:30'),
(265, 0, 'has created an user', '', '2012-07-23 09:36:57'),
(266, 0, 'has created an user', '', '2012-07-23 09:38:39'),
(267, 1, 'has logged in to the system', '::1', '2012-07-23 09:38:59'),
(268, 1, 'has deleted an user', '::1', '2012-07-23 09:39:08'),
(269, 1, 'has deleted an user', '::1', '2012-07-23 09:39:12'),
(270, 1, 'has deleted an user', '::1', '2012-07-23 09:39:15'),
(271, 1, 'has deleted an user', '::1', '2012-07-23 09:39:19'),
(272, 1, 'has deleted an user', '::1', '2012-07-23 09:39:22'),
(273, 1, 'has logged off from the system', '::1', '2012-07-23 11:34:50'),
(274, 0, 'has created an user', '', '2012-07-23 11:35:21'),
(275, 1, 'has logged in to the system', '::1', '2012-07-23 14:29:12'),
(276, 1, 'has logged off from the system', '::1', '2012-07-23 14:29:39'),
(277, 1, 'has logged in to the system', '::1', '2012-07-23 15:05:27'),
(278, 1, 'has logged off from the system', '::1', '2012-07-23 15:08:01'),
(279, 1, 'has logged in to the system', '::1', '2012-07-23 15:08:25'),
(280, 1, 'has logged off from the system', '::1', '2012-07-23 15:08:41'),
(281, 2, 'has logged in to the system', '::1', '2012-07-23 15:09:34'),
(282, 2, 'has logged off from the system', '::1', '2012-07-23 15:09:45'),
(283, 0, 'has created an user', '', '2012-07-23 20:57:14'),
(284, 1, 'has logged in to the system', '::1', '2012-07-23 22:55:21'),
(285, 1, 'has created an user', '::1', '2012-07-23 22:55:38'),
(286, 1, 'has logged off from the system', '::1', '2012-07-23 22:56:29'),
(287, 1, 'has logged in to the system', '::1', '2012-07-23 23:21:01'),
(288, 1, 'has logged off from the system', '::1', '2012-07-23 23:35:27'),
(289, 0, 'has created an user', '', '2012-07-24 09:45:44'),
(290, 1, 'has logged in to the system', '::1', '2012-07-24 09:45:56'),
(291, 1, 'has created an user', '::1', '2012-07-24 09:48:24'),
(292, 1, 'has logged off from the system', '::1', '2012-07-24 09:48:43'),
(293, 1, 'has logged in to the system', '::1', '2012-07-24 14:46:47'),
(294, 1, 'has logged off from the system', '::1', '2012-07-24 18:22:38'),
(295, 1, 'has logged in to the system', '::1', '2012-07-24 18:32:04'),
(296, 1, 'has deleted an user', '::1', '2012-07-24 18:32:36'),
(297, 1, 'has deleted an user', '::1', '2012-07-24 18:32:40'),
(298, 1, 'has deleted an user', '::1', '2012-07-24 18:32:43'),
(299, 1, 'has deleted an user', '::1', '2012-07-24 18:32:46'),
(300, 1, 'has deleted an user', '::1', '2012-07-24 18:32:50'),
(301, 1, 'has deleted an user', '::1', '2012-07-24 18:32:56'),
(302, 1, 'has logged off from the system', '::1', '2012-07-24 18:33:03'),
(303, 0, 'has created an user', '', '2012-07-24 18:33:32'),
(304, 1, 'has logged in to the system', '::1', '2012-07-24 18:33:42'),
(305, 1, 'has changed status of an user', '::1', '2012-07-24 18:40:59'),
(306, 1, 'has changed status of an user', '::1', '2012-07-24 18:41:00'),
(307, 1, 'has changed status of an user', '::1', '2012-07-24 18:41:01'),
(308, 1, 'has changed status of an user', '::1', '2012-07-24 18:41:02'),
(309, 1, 'has changed status of an user', '::1', '2012-07-24 18:41:03'),
(310, 1, 'has changed status of an user', '::1', '2012-07-24 18:41:03'),
(311, 1, 'has changed status of an user', '::1', '2012-07-24 18:41:04'),
(312, 1, 'has changed status of an user', '::1', '2012-07-24 18:41:05'),
(313, 1, 'has changed status of an user', '::1', '2012-07-24 18:41:15'),
(314, 1, 'has changed status of an user', '::1', '2012-07-24 18:41:15'),
(315, 1, 'has logged off from the system', '::1', '2012-07-24 18:51:53'),
(316, 0, 'has created an user', '', '2012-07-24 18:52:43'),
(317, 0, 'has created an user', '', '2012-07-24 18:53:19'),
(318, 1, 'has logged in to the system', '::1', '2012-07-24 18:53:41'),
(319, 31, 'has logged in to the system', '::1', '2012-07-26 09:42:12'),
(320, 31, 'has logged off from the system', '::1', '2012-07-26 09:42:17'),
(321, 31, 'has logged in to the system', '::1', '2012-07-26 09:42:43'),
(322, 31, 'has logged off from the system', '::1', '2012-07-26 09:43:37'),
(323, 31, 'has logged in to the system', '::1', '2012-07-26 09:44:17'),
(324, 31, 'has logged off from the system', '::1', '2012-07-26 10:03:00'),
(325, 31, 'has logged in to the system', '::1', '2012-07-26 10:03:14'),
(326, 31, 'has logged off from the system', '::1', '2012-07-26 10:14:15'),
(327, 31, 'has logged in to the system', '::1', '2012-07-26 10:14:39'),
(328, 31, 'has logged off from the system', '::1', '2012-07-26 10:26:03'),
(329, 31, 'has logged in to the system', '::1', '2012-07-26 10:26:10'),
(330, 31, 'has logged off from the system', '::1', '2012-07-26 10:26:47'),
(331, 33, 'has logged in to the system', '::1', '2012-07-26 10:26:56'),
(332, 33, 'has logged off from the system', '::1', '2012-07-26 10:27:19'),
(333, 32, 'has logged in to the system', '::1', '2012-07-26 10:27:30'),
(334, 32, 'has logged off from the system', '::1', '2012-07-26 10:32:04'),
(335, 1, 'has logged in to the system', '::1', '2012-07-26 10:32:16'),
(336, 1, 'has logged off from the system', '::1', '2012-07-26 11:02:56'),
(337, 31, 'has logged in to the system', '::1', '2012-07-26 11:03:03'),
(338, 31, 'has logged off from the system', '::1', '2012-07-26 11:05:00'),
(339, 32, 'has logged in to the system', '::1', '2012-07-26 11:05:10'),
(340, 32, 'has logged off from the system', '::1', '2012-07-26 11:05:27'),
(341, 1, 'has logged in to the system', '::1', '2012-07-26 12:04:12'),
(342, 1, 'has logged off from the system', '::1', '2012-07-29 11:40:48'),
(343, 1, 'has logged in to the system', '::1', '2012-07-29 11:41:18'),
(344, 1, 'has logged in to the system', '::1', '2012-07-29 11:59:21'),
(345, 1, 'has logged in to the system', '::1', '2012-07-29 19:04:46'),
(346, 1, 'has logged off from the system', '::1', '2012-07-29 19:37:53'),
(347, 1, 'has logged in to the system', '::1', '2012-07-29 19:38:11'),
(348, 1, 'has logged in to the system', '::1', '2012-07-29 22:24:43'),
(349, 1, 'has logged in to the system', '::1', '2012-07-30 14:43:03'),
(350, 1, 'has logged in to the system', '::1', '2012-07-30 20:52:17'),
(351, 1, 'has logged off from the system', '::1', '2012-07-30 21:21:13'),
(352, 1, 'has logged in to the system', '::1', '2012-07-30 21:21:27'),
(353, 1, 'has logged off from the system', '::1', '2012-07-30 21:29:33'),
(354, 1, 'has logged in to the system', '::1', '2012-07-30 21:29:42'),
(355, 1, 'has logged off from the system', '::1', '2012-07-30 21:30:17'),
(356, 1, 'has logged in to the system', '::1', '2012-07-30 21:30:42'),
(357, 1, 'has logged off from the system', '::1', '2012-07-30 21:36:07'),
(358, 1, 'has logged in to the system', '::1', '2012-07-30 21:37:13'),
(359, 1, 'has logged off from the system', '::1', '2012-07-30 21:37:49'),
(360, 1, 'has logged in to the system', '::1', '2012-07-30 21:38:04'),
(361, 1, 'has logged off from the system', '::1', '2012-07-30 21:38:06'),
(362, 1, 'has logged in to the system', '::1', '2012-07-30 21:38:14'),
(363, 1, 'has logged in to the system', '::1', '2012-07-30 21:58:51'),
(364, 1, 'has logged off from the system', '::1', '2012-07-30 22:15:08'),
(365, 1, 'has logged in to the system', '::1', '2012-07-30 22:55:17'),
(366, 1, 'has logged in to the system', '::1', '2012-07-30 22:57:06'),
(367, 1, 'has logged off from the system', '::1', '2012-07-30 23:04:52'),
(368, 1, 'has logged in to the system', '::1', '2012-07-30 23:05:08'),
(369, 1, 'has logged in to the system', '::1', '2012-07-31 00:30:52'),
(370, 1, 'has logged off from the system', '::1', '2012-08-01 17:38:04'),
(371, 1, 'has logged in to the system', '::1', '2012-08-01 17:38:12'),
(372, 1, 'has logged off from the system', '::1', '2012-08-05 10:18:14'),
(373, 12, 'has logged in to the system', '::1', '2012-08-05 10:19:27'),
(374, 12, 'has logged off from the system', '::1', '2012-08-05 10:19:37'),
(375, 1, 'has logged in to the system', '::1', '2012-08-05 10:19:46'),
(376, 1, 'has logged off from the system', '::1', '2012-08-05 10:23:45'),
(377, 1, 'has logged in to the system', '::1', '2012-08-05 10:33:46'),
(378, 12, 'has logged in to the system', '::1', '2012-08-05 10:39:57'),
(379, 1, 'has changed status of an user', '::1', '2012-08-05 10:41:36'),
(380, 1, 'has logged off from the system', '::1', '2012-08-05 10:41:49'),
(381, 1, 'has logged in to the system', '::1', '2012-08-05 10:42:48'),
(382, 1, 'has changed status of an user', '::1', '2012-08-05 10:42:56'),
(383, 1, 'has logged off from the system', '::1', '2012-08-05 10:42:59'),
(384, 33, 'has logged in to the system', '::1', '2012-08-05 10:43:04'),
(385, 33, 'has logged off from the system', '::1', '2012-08-05 10:43:10'),
(386, 1, 'has logged in to the system', '::1', '2012-08-05 10:43:16'),
(387, 1, 'has logged off from the system', '::1', '2012-08-05 12:02:23'),
(388, 1, 'has logged in to the system', '::1', '2012-08-06 11:40:32'),
(389, 1, 'has logged off from the system', '::1', '2012-08-06 12:14:14'),
(390, 33, 'has logged in to the system', '::1', '2012-08-06 12:14:26'),
(391, 33, 'has logged off from the system', '::1', '2012-08-06 12:14:42'),
(392, 1, 'has logged in to the system', '::1', '2012-08-06 14:34:16'),
(393, 1, 'has logged off from the system', '::1', '2012-08-06 14:36:42'),
(394, 1, 'has logged in to the system', '::1', '2012-08-06 14:36:47'),
(395, 1, 'has logged off from the system', '::1', '2012-08-06 14:37:27'),
(396, 0, 'has logged off from the system', '', '2012-08-06 14:37:34'),
(397, 1, 'has logged in to the system', '::1', '2012-08-06 14:37:40'),
(398, 1, 'has logged off from the system', '::1', '2012-08-06 14:38:35'),
(399, 33, 'has logged in to the system', '::1', '2012-08-06 14:38:44'),
(400, 33, 'has logged off from the system', '::1', '2012-08-06 14:39:21'),
(401, 1, 'has logged in to the system', '::1', '2012-08-06 14:39:28'),
(402, 1, 'has logged off from the system', '::1', '2012-08-06 16:10:31'),
(403, 0, 'has logged off from the system', '', '2012-08-06 16:10:35'),
(404, 1, 'has logged in to the system', '::1', '2012-08-06 16:10:47'),
(405, 1, 'has logged in to the system', '::1', '2012-08-07 14:13:32'),
(406, 1, 'has logged in to the system', '::1', '2012-08-07 15:03:16'),
(407, 1, 'has logged off from the system', '::1', '2012-08-07 16:38:42'),
(408, 33, 'has logged in to the system', '::1', '2012-08-07 16:38:51'),
(409, 33, 'has logged off from the system', '::1', '2012-08-07 16:39:40'),
(410, 1, 'has logged in to the system', '::1', '2012-08-07 16:39:45'),
(411, 1, 'has logged off from the system', '::1', '2012-08-07 16:56:18'),
(412, 33, 'has logged in to the system', '::1', '2012-08-07 16:56:25'),
(413, 33, 'has logged off from the system', '::1', '2012-08-07 16:56:34'),
(414, 1, 'has logged in to the system', '::1', '2012-08-07 16:56:40'),
(415, 1, 'has logged off from the system', '::1', '2012-08-08 20:29:24'),
(416, 33, 'has logged in to the system', '::1', '2012-08-08 20:29:30'),
(417, 33, 'has logged off from the system', '::1', '2012-08-08 20:36:28'),
(418, 1, 'has logged in to the system', '::1', '2012-08-08 20:36:35'),
(419, 1, 'has logged off from the system', '::1', '2012-08-08 21:14:08'),
(420, 1, 'has logged in to the system', '::1', '2012-08-08 21:14:23'),
(421, 1, 'has logged off from the system', '::1', '2012-08-08 21:17:30'),
(422, 1, 'has logged in to the system', '::1', '2012-08-08 22:47:04'),
(423, 1, 'has logged off from the system', '::1', '2012-08-08 22:47:23'),
(424, 0, 'has created an user', '', '2012-08-08 22:48:10'),
(425, 1, 'has logged in to the system', '::1', '2012-08-08 22:48:30'),
(426, 1, 'has changed status of an user', '::1', '2012-08-08 22:48:40'),
(427, 1, 'has logged off from the system', '::1', '2012-08-08 22:48:42'),
(428, 34, 'has logged in to the system', '::1', '2012-08-08 22:52:58'),
(429, 34, 'has logged off from the system', '::1', '2012-08-08 22:53:02'),
(430, 1, 'has logged in to the system', '::1', '2012-08-08 22:53:55'),
(431, 1, 'has logged off from the system', '::1', '2012-08-08 23:02:50'),
(432, 34, 'has logged in to the system', '::1', '2012-08-08 23:14:44'),
(433, 34, 'Changed password', '::1', '2012-08-08 23:15:01'),
(434, 34, 'has logged off from the system', '::1', '2012-08-08 23:15:04'),
(435, 34, 'has logged in to the system', '::1', '2012-08-08 23:15:10'),
(436, 34, 'has logged off from the system', '::1', '2012-08-08 23:15:12'),
(437, 1, 'has logged in to the system', '::1', '2012-08-08 23:23:26'),
(438, 1, 'has logged off from the system', '::1', '2012-08-08 23:31:43'),
(439, 1, 'has logged in to the system', '::1', '2012-08-09 00:13:19'),
(440, 1, 'has logged off from the system', '::1', '2012-08-09 00:56:44'),
(441, 1, 'has logged in to the system', '::1', '2012-08-09 09:08:40'),
(442, 1, 'has logged off from the system', '::1', '2012-08-09 19:11:12'),
(443, 34, 'has logged in to the system', '::1', '2012-08-09 19:12:02'),
(444, 34, 'has logged off from the system', '::1', '2012-08-10 00:40:29'),
(445, 1, 'has logged in to the system', '::1', '2012-08-10 00:40:36'),
(446, 1, 'has logged off from the system', '::1', '2012-08-10 10:08:39'),
(447, 1, 'has logged in to the system', '::1', '2012-08-10 10:11:01'),
(448, 1, 'has logged off from the system', '::1', '2012-08-10 10:56:15'),
(449, 1, 'has logged in to the system', '::1', '2012-08-10 10:57:00'),
(450, 1, 'has logged off from the system', '::1', '2012-08-10 10:59:06'),
(451, 0, 'has created an user', '', '2012-08-10 10:59:08'),
(452, 0, 'has created an user', '', '2012-08-10 11:00:25'),
(453, 1, 'has logged in to the system', '::1', '2012-08-10 11:05:40'),
(454, 1, 'has logged off from the system', '::1', '2012-08-10 11:10:42'),
(455, 1, 'has logged in to the system', '::1', '2012-08-10 11:12:14'),
(456, 1, 'has logged off from the system', '::1', '2012-08-10 11:26:39'),
(457, 0, 'has created an user', '', '2012-08-10 11:26:47'),
(458, 0, 'has created an user', '', '2012-08-10 11:26:57'),
(459, 1, 'has logged in to the system', '::1', '2012-08-10 11:28:57'),
(460, 1, 'has logged off from the system', '::1', '2012-08-10 11:32:56'),
(461, 1, 'has logged in to the system', '::1', '2012-08-10 11:45:44'),
(462, 1, 'Profile Changed', '::1', '2012-08-10 12:06:46'),
(463, 1, 'Profile Changed', '::1', '2012-08-10 12:06:50'),
(464, 1, 'has logged in to the system', '::1', '2012-08-10 12:17:38'),
(465, 1, 'has logged in to the system', '::1', '2012-08-10 12:19:35'),
(466, 1, 'has logged in to the system', '::1', '2012-08-10 16:23:23'),
(467, 1, 'has logged in to the system', '::1', '2012-08-10 16:24:26'),
(468, 1, 'has logged off from the system', '::1', '2012-08-10 18:51:50'),
(469, 34, 'has logged in to the system', '::1', '2012-08-10 18:52:13'),
(470, 34, 'has logged off from the system', '::1', '2012-08-10 18:55:26'),
(471, 1, 'has logged in to the system', '::1', '2012-08-10 22:25:00'),
(472, 1, 'has logged off from the system', '::1', '2012-08-11 14:04:52'),
(473, 39, 'has logged in to the system', '::1', '2012-08-11 14:05:12'),
(474, 39, 'has logged off from the system', '::1', '2012-08-11 14:05:57'),
(475, 39, 'has logged in to the system', '::1', '2012-08-11 14:06:06'),
(476, 39, 'has logged off from the system', '::1', '2012-08-11 14:06:48'),
(477, 39, 'has logged in to the system', '::1', '2012-08-11 14:08:56'),
(478, 39, 'has logged off from the system', '::1', '2012-08-11 14:10:25'),
(479, 1, 'has logged in to the system', '::1', '2012-08-11 14:10:32'),
(480, 1, 'has logged off from the system', '::1', '2012-08-11 16:17:41'),
(481, 0, 'has created an user', '', '2012-08-11 16:35:41'),
(482, 1, 'has logged in to the system', '::1', '2012-08-11 16:39:09'),
(483, 1, 'has changed status of an user', '::1', '2012-08-11 16:39:19'),
(484, 1, 'has logged off from the system', '::1', '2012-08-11 16:39:21'),
(485, 40, 'has logged in to the system', '::1', '2012-08-11 16:39:32'),
(486, 40, 'has logged off from the system', '::1', '2012-08-11 16:44:28'),
(487, 39, 'has logged in to the system', '::1', '2012-08-11 16:44:37'),
(488, 39, 'has logged off from the system', '::1', '2012-08-11 16:54:55'),
(489, 0, 'has created an user', '', '2012-08-11 17:31:46'),
(490, 0, 'has created an user', '', '2012-08-11 17:32:40'),
(491, 1, 'has logged in to the system', '::1', '2012-08-11 17:39:57'),
(492, 1, 'has logged off from the system', '::1', '2012-08-11 19:50:40'),
(493, 40, 'has logged in to the system', '::1', '2012-08-11 19:51:18'),
(494, 40, 'has logged off from the system', '::1', '2012-08-11 19:52:08'),
(495, 0, 'has created an user', '', '2012-08-11 19:52:29'),
(496, 1, 'has logged in to the system', '::1', '2012-08-11 19:52:36'),
(497, 1, 'has changed status of an user', '::1', '2012-08-11 19:52:41'),
(498, 1, 'has logged off from the system', '::1', '2012-08-11 19:52:43'),
(499, 43, 'has logged in to the system', '::1', '2012-08-11 19:52:51'),
(500, 43, 'has logged off from the system', '::1', '2012-08-11 19:53:19'),
(501, 0, 'has created an user', '', '2012-08-11 19:53:35'),
(502, 1, 'has logged in to the system', '::1', '2012-08-11 19:53:41'),
(503, 1, 'has changed status of an user', '::1', '2012-08-11 19:53:56'),
(504, 1, 'has logged off from the system', '::1', '2012-08-11 19:53:57'),
(505, 44, 'has logged in to the system', '::1', '2012-08-11 19:54:04'),
(506, 44, 'has logged off from the system', '::1', '2012-08-11 19:54:38'),
(507, 1, 'has logged in to the system', '::1', '2012-08-11 19:54:45'),
(508, 1, 'has logged off from the system', '::1', '2012-08-11 20:02:09'),
(509, 44, 'has logged in to the system', '::1', '2012-08-11 20:02:18'),
(510, 44, 'has logged off from the system', '::1', '2012-08-11 20:02:39'),
(511, 1, 'has logged in to the system', '::1', '2012-08-11 20:02:46'),
(512, 1, 'has logged off from the system', '::1', '2012-08-12 10:57:23'),
(513, 0, 'has created an user', '', '2012-08-12 11:18:59'),
(514, 0, 'has created an user', '', '2012-08-12 11:30:47'),
(515, 0, 'has created an user', '', '2012-08-12 11:37:07'),
(516, 0, 'has created an user', '', '2012-08-12 12:27:09'),
(517, 0, 'has created an user', '', '2012-08-12 12:27:58'),
(518, 0, 'has created an user', '', '2012-08-12 12:30:19'),
(519, 0, 'has created an user', '', '2012-08-12 12:31:58'),
(520, 0, 'has created an user', '', '2012-08-12 12:32:56'),
(521, 1, 'has logged in to the system', '::1', '2012-08-12 12:34:45'),
(522, 1, 'has logged in to the system', '::1', '2012-08-12 14:18:50'),
(523, 1, 'has logged in to the system', '::1', '2012-08-12 14:28:29'),
(524, 1, 'has logged off from the system', '::1', '2012-08-12 14:30:25'),
(525, 40, 'has logged in to the system', '::1', '2012-08-12 14:30:34'),
(526, 40, 'has logged off from the system', '::1', '2012-08-12 14:30:42'),
(527, 1, 'has logged in to the system', '::1', '2012-08-12 14:31:26'),
(528, 1, 'has logged off from the system', '::1', '2012-08-12 14:39:26'),
(529, 40, 'has logged in to the system', '::1', '2012-08-12 14:39:34'),
(530, 40, 'has logged off from the system', '::1', '2012-08-12 14:39:42'),
(531, 1, 'has logged in to the system', '::1', '2012-08-12 14:39:48'),
(532, 1, 'has logged off from the system', '::1', '2012-08-12 14:40:10'),
(533, 1, 'has logged off from the system', '::1', '2012-08-12 14:41:32'),
(534, 1, 'has logged in to the system', '::1', '2012-08-12 14:41:46'),
(535, 1, 'has logged off from the system', '::1', '2012-08-12 17:13:00'),
(536, 1, 'has logged in to the system', '::1', '2012-08-12 17:14:53'),
(537, 1, 'has changed status of an user', '::1', '2012-08-12 17:17:21'),
(538, 1, 'has changed status of an user', '::1', '2012-08-12 17:17:22'),
(539, 1, 'has changed status of an user', '::1', '2012-08-12 17:17:24'),
(540, 1, 'has changed status of an user', '::1', '2012-08-12 17:17:28'),
(541, 1, 'has logged off from the system', '::1', '2012-08-12 17:17:44'),
(542, 39, 'has logged in to the system', '::1', '2012-08-12 17:17:53'),
(543, 1, 'has logged in to the system', '::1', '2012-08-12 17:18:24'),
(544, 1, 'has logged off from the system', '::1', '2012-08-12 17:20:23'),
(545, 40, 'has logged in to the system', '::1', '2012-08-12 17:20:33'),
(546, 39, 'has logged off from the system', '::1', '2012-08-12 17:22:05'),
(547, 40, 'has logged in to the system', '::1', '2012-08-12 17:22:16'),
(548, 40, 'has logged off from the system', '::1', '2012-08-12 17:35:34'),
(549, 39, 'has logged in to the system', '::1', '2012-08-12 17:35:48'),
(550, 39, 'has logged off from the system', '::1', '2012-08-12 17:38:53'),
(551, 1, 'has logged in to the system', '::1', '2012-08-12 18:15:42'),
(552, 1, 'has logged off from the system', '::1', '2012-08-12 18:53:33'),
(553, 40, 'has logged in to the system', '::1', '2012-08-12 18:53:40'),
(554, 40, 'has logged off from the system', '::1', '2012-08-12 20:46:13'),
(555, 1, 'has logged in to the system', '::1', '2012-08-12 20:46:20'),
(556, 1, 'has logged off from the system', '::1', '2012-08-12 21:36:47'),
(557, 40, 'has logged in to the system', '::1', '2012-08-12 21:37:00'),
(558, 40, 'has logged off from the system', '::1', '2012-08-12 21:42:51'),
(559, 44, 'has logged in to the system', '::1', '2012-08-12 21:43:11'),
(560, 44, 'has logged off from the system', '::1', '2012-08-12 21:43:29'),
(561, 40, 'has logged in to the system', '::1', '2012-08-12 21:43:34'),
(562, 40, 'has logged off from the system', '::1', '2012-08-12 21:54:41'),
(563, 44, 'has logged in to the system', '::1', '2012-08-12 21:54:47'),
(564, 44, 'has logged off from the system', '::1', '2012-08-12 22:04:08'),
(565, 40, 'has logged in to the system', '::1', '2012-08-12 22:04:19'),
(566, 40, 'has logged off from the system', '::1', '2012-08-12 22:06:04'),
(567, 44, 'has logged in to the system', '::1', '2012-08-12 22:06:10'),
(568, 44, 'has logged off from the system', '::1', '2012-08-12 22:06:17'),
(569, 40, 'has logged in to the system', '::1', '2012-08-12 22:06:30'),
(570, 40, 'has logged off from the system', '::1', '2012-08-12 22:10:38'),
(571, 44, 'has logged in to the system', '::1', '2012-08-12 22:10:45'),
(572, 44, 'has logged off from the system', '::1', '2012-08-12 22:13:33'),
(573, 1, 'has logged in to the system', '::1', '2012-08-12 22:13:41'),
(574, 1, 'has logged off from the system', '::1', '2012-08-12 22:51:05'),
(575, 44, 'has logged in to the system', '::1', '2012-08-12 22:51:13'),
(576, 44, 'has logged off from the system', '::1', '2012-08-12 22:52:25'),
(577, 39, 'has logged in to the system', '::1', '2012-08-12 22:52:32'),
(578, 39, 'has logged off from the system', '::1', '2012-08-12 22:53:15'),
(579, 39, 'has logged in to the system', '::1', '2012-08-12 22:53:30'),
(580, 39, 'has logged off from the system', '::1', '2012-08-12 22:57:21'),
(581, 39, 'has logged in to the system', '::1', '2012-08-12 22:57:29'),
(582, 39, 'has logged off from the system', '::1', '2012-08-12 23:49:17'),
(583, 1, 'has logged in to the system', '::1', '2012-08-12 23:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `page_name` varchar(255) NOT NULL DEFAULT '',
  `display_order` int(11) NOT NULL DEFAULT '0',
  `is_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `sub_admin` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_name`, `parent_id`, `page_name`, `display_order`, `is_active`, `sub_admin`) VALUES
(1, 'Admin Utilities', 0, '', 0, 'Y', '0,1,2'),
(2, 'Home', 1, 'main.php', 0, 'Y', '0,1,2'),
(3, 'Change Password', 1, 'changepwd.php', 0, 'Y', '0,1,2'),
(4, '<a href="javascript:logout();"> Logout</a>', 1, '', 0, 'Y', '0,1,2'),
(5, 'User Management', 0, '', 0, 'Y', '0,1,2'),
(6, 'List User', 5, 'user_list.php', 0, 'Y', '0'),
(20, 'Manage Menus', 1, 'menu_management.php', 3, 'Y', '0'),
(48, 'My Account', 1, 'change_profile.php', 0, 'Y', '0,1,2'),
(23, 'Get Schedule', 5, 'getschedule.php', 2, 'Y', '0,1'),
(16, 'Department Management', 0, '', 0, 'Y', '0'),
(17, 'Manage Department', 16, 'department_manage.php', 0, 'Y', '0'),
(18, 'Log Details', 0, '', 0, 'Y', '0'),
(19, 'View Log Details', 18, 'view_log_details.php', 0, 'Y', '0'),
(40, 'Upload Study Material', 39, 'upload_study_mate.php', 0, 'Y', '0,1,2'),
(39, 'Course Management', 0, '', 0, 'Y', '0,1,2'),
(41, 'Manage Course', 39, 'course_manage.php', 0, 'Y', '0'),
(42, 'Live Chat', 0, '', 0, 'Y', '0,1,2'),
(43, 'Study Through Chat', 42, 'chat.php', 0, 'Y', '0,1,2'),
(44, 'Group Management', 0, '', 0, 'Y', '0,1,2'),
(45, 'Manage Group', 44, 'manage_group.php', 0, 'Y', '0,1,2'),
(46, 'Manage Group Memebers', 44, 'manage_group_members.php', 0, 'Y', '0,1,2'),
(47, 'Get Appointment', 5, 'getappointment.php', 0, 'Y', '0,2');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE IF NOT EXISTS `schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sdate` varchar(100) NOT NULL,
  `day` varchar(50) NOT NULL,
  `stime` varchar(100) NOT NULL,
  `maxstrength` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('F','V') NOT NULL DEFAULT 'V',
  `is_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `sdate`, `day`, `stime`, `maxstrength`, `user_id`, `status`, `is_active`) VALUES
(2, '5/6/2012', 'Wednesday', '10:11 AM', 3, 39, 'V', 'Y'),
(3, '8/7/2012', 'Friday', '3:30 AM', 3, 39, 'F', 'Y'),
(4, '08/22/2012', 'Wednesday', '10:30 am', 3, 1, 'V', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_request`
--

CREATE TABLE IF NOT EXISTS `schedule_request` (
  `schedule_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `approve` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule_request`
--

INSERT INTO `schedule_request` (`schedule_id`, `student_id`, `tutor_id`, `approve`) VALUES
(2, 1, 39, 'N'),
(2, 40, 39, 'N'),
(3, 40, 39, 'Y'),
(2, 43, 39, 'N'),
(3, 44, 39, 'N');

-- --------------------------------------------------------

--
-- Table structure for table `study_materials`
--

CREATE TABLE IF NOT EXISTS `study_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mate_name` varchar(255) NOT NULL,
  `mate_type` varchar(255) NOT NULL,
  `mate_size` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `is_active` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `study_materials`
--

INSERT INTO `study_materials` (`id`, `mate_name`, `mate_type`, `mate_size`, `department_id`, `course_id`, `uploaded_by`, `is_active`) VALUES
(1, '1344109903_checklist_08062012.pdf', 'application/pdf', 313630, 2, 3, 1, 'Y'),
(2, '1344493458_One_Pair_Of_Hands2.WMV', 'video/x-ms-wmv', 6437005, 2, 3, 1, 'Y'),
(3, '1344800826_Tulips.jpg', 'image/jpeg', 620888, 4, 0, 1, 'N');

-- --------------------------------------------------------

--
-- Table structure for table `user_master`
--

CREATE TABLE IF NOT EXISTS `user_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` enum('SUP','SUB','SUBS') NOT NULL DEFAULT 'SUB',
  `is_active` enum('Y','N') NOT NULL DEFAULT 'N',
  `department_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `db_add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `user_master`
--

INSERT INTO `user_master` (`id`, `name`, `username`, `password`, `email`, `type`, `is_active`, `department_id`, `course_id`, `db_add_date`) VALUES
(1, 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@gmail.com', 'SUP', 'Y', 0, 0, '2011-08-09 02:36:10'),
(34, 'testfirst', 'testfirst', '098f6bcd4621d373cade4e832627b4f6', 'testfirst@gmail.com', 'SUBS', 'Y', 1, 4, '2012-08-08 22:48:10'),
(40, 'Pallavi', 'pallavi', '098f6bcd4621d373cade4e832627b4f6', 'pallavi@gmail.com', 'SUBS', 'Y', 0, 0, '2012-08-11 16:35:41'),
(39, 'testtuotor', 'testtuotor', '098f6bcd4621d373cade4e832627b4f6', 'testtuotor@gmail.com', 'SUB', 'Y', 1, 4, '2012-08-01 05:19:13'),
(43, 'shyama', 'shyama', 'cc9c3bd76b315f9143da96f20388a199', 'shyamasreeb@gmail.com', 'SUBS', 'Y', 0, 0, '2012-08-11 19:52:29'),
(44, 'sid', 'sid', 'b8c1a3069167247e3503f0daba6c5723', 'sid@gmail.com', 'SUBS', 'Y', 0, 0, '2012-08-11 19:53:35');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
