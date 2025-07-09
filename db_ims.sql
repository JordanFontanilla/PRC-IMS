-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 11:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ims`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_auditlog`
--

CREATE TABLE `tbl_auditlog` (
  `auditlog_id` int(25) NOT NULL,
  `auditlog_empname` varchar(255) NOT NULL,
  `auditlog_action` varchar(255) NOT NULL,
  `auditlog_dateandtime` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_auditlog`
--

INSERT INTO `tbl_auditlog` (`auditlog_id`, `auditlog_empname`, `auditlog_action`, `auditlog_dateandtime`) VALUES
(1, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 11:07:43'),
(2, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 11:07:44'),
(3, 'Admin', 'Clicked User Management Side Nav', '2025-05-06 11:17:12'),
(4, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 11:17:13'),
(5, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 11:17:14'),
(6, 'Admin', 'Clicked User Management Side Nav', '2025-05-06 11:19:06'),
(7, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 11:19:07'),
(8, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 11:19:08'),
(9, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 11:41:44'),
(10, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 11:41:45'),
(11, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-06 11:58:10'),
(12, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 11:59:48'),
(13, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 11:59:50'),
(14, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 12:43:14'),
(15, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 12:43:15'),
(16, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 12:48:19'),
(17, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 12:48:20'),
(18, 'Admin', 'Clicked User Management Side Nav', '2025-05-06 12:59:57'),
(19, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 12:59:58'),
(20, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 12:59:59'),
(21, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-06 13:00:00'),
(22, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 13:01:19'),
(23, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-06 13:01:21'),
(24, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 13:01:22'),
(25, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-06 13:01:23'),
(26, 'Admin', 'Clicked manual add option', '2025-05-06 13:03:23'),
(27, 'Admin', 'Clicked manual add option', '2025-05-06 13:04:46'),
(28, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 13:04:51'),
(29, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-06 13:04:52'),
(30, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 13:04:55'),
(31, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-06 13:07:11'),
(32, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 13:07:12'),
(33, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 13:27:18'),
(34, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 14:54:14'),
(35, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 14:54:15'),
(36, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 15:33:19'),
(37, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 15:33:21'),
(38, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 15:55:42'),
(39, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 15:55:43'),
(40, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-06 17:56:26'),
(41, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-06 17:58:50'),
(42, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-06 17:58:51'),
(43, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-07 12:27:35'),
(44, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-07 12:27:36'),
(45, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-07 12:28:28'),
(46, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-07 12:28:29'),
(47, 'Admin', 'Clicked User Management Side Nav', '2025-05-08 13:20:51'),
(48, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-08 13:20:54'),
(49, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-08 13:20:55'),
(50, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-08 16:10:22'),
(51, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-08 16:13:54'),
(52, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-08 16:13:56'),
(53, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-08 17:09:43'),
(54, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-08 17:10:06'),
(55, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-08 17:15:04'),
(56, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-08 17:19:21'),
(57, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-09 08:52:14'),
(58, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-09 08:52:15'),
(59, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-09 13:00:55'),
(60, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-09 14:07:01'),
(61, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-09 14:07:02'),
(62, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-09 14:07:23'),
(63, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-09 14:07:23'),
(64, 'Admin', 'Clicked User Management Side Nav', '2025-05-09 15:24:00'),
(65, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-09 15:24:00'),
(66, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-09 15:24:01'),
(67, 'Admin', 'Clicked User Management Side Nav', '2025-05-09 15:25:10'),
(68, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-09 15:25:11'),
(69, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-09 15:25:12'),
(70, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-09 15:33:19'),
(71, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-09 15:33:21'),
(72, 'Admin', 'Clicked User Management Side Nav', '2025-05-13 08:49:26'),
(73, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-13 08:49:28'),
(74, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-13 08:49:29'),
(75, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 08:49:30'),
(76, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-13 09:14:40'),
(77, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-13 09:14:43'),
(78, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 09:16:24'),
(79, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 09:16:42'),
(80, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-13 09:16:46'),
(81, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 09:16:47'),
(82, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-13 09:27:52'),
(83, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 09:27:53'),
(84, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-13 09:40:21'),
(85, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 09:40:25'),
(86, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-13 11:29:02'),
(87, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 11:29:03'),
(88, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-13 11:32:12'),
(89, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 11:32:16'),
(90, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 11:43:47'),
(91, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 11:44:58'),
(92, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-13 13:17:44'),
(93, 'Admin', 'Clicked Pending Request card', '2025-05-14 08:13:38'),
(94, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-14 08:13:41'),
(95, 'Admin', 'Clicked Available Equipment card', '2025-05-14 08:13:42'),
(96, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-14 08:13:44'),
(97, 'Admin', 'Clicked Total Equipment card', '2025-05-14 08:13:44'),
(98, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-14 08:13:51'),
(99, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-14 08:13:53'),
(100, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-14 08:13:57'),
(101, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-14 08:14:23'),
(102, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-15 14:20:23'),
(103, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-15 14:20:25'),
(104, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-15 14:20:26'),
(105, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-15 14:20:28'),
(106, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-15 14:20:29'),
(107, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-15 14:20:30'),
(108, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-15 14:20:31'),
(109, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-15 14:20:37'),
(110, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-16 08:13:27'),
(111, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-16 08:13:33'),
(112, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-16 08:13:34'),
(113, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-16 08:24:21'),
(114, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-16 08:24:27'),
(115, 'Admin', 'Clicked Request Equipment Side Nav', '2025-05-16 08:24:27'),
(116, 'Admin', 'Clicked search item option', '2025-05-16 08:24:29'),
(117, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-16 08:37:20'),
(118, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-16 08:46:39'),
(119, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-16 11:22:02'),
(120, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-16 11:22:03'),
(121, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-16 15:58:34'),
(122, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-16 16:05:32'),
(123, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-16 16:05:33'),
(124, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-16 16:05:54'),
(125, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-16 16:05:55'),
(126, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-16 16:05:55'),
(127, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-16 16:05:56'),
(128, 'Admin', 'Clicked Equipment Pie Chart card', '2025-05-16 16:05:57'),
(129, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-16 16:06:25'),
(130, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-16 16:06:26'),
(131, 'Admin', 'Clicked Dashboard Side Nav', '2025-05-16 16:23:02'),
(132, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-05-16 16:23:41'),
(133, 'Admin', 'Clicked Inventory List Side Nav', '2025-05-16 16:23:41'),
(134, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-06-26 16:42:49'),
(135, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-06-26 16:42:50'),
(136, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-06-26 16:42:51'),
(137, 'Admin', 'Clicked Inventory List Side Nav', '2025-06-26 16:42:52'),
(138, 'Admin', 'Clicked Request Equipment Side Nav', '2025-06-26 16:42:53'),
(139, 'Admin', 'Clicked Request Equipment Side Nav', '2025-06-26 16:42:54'),
(140, 'Admin', 'Clicked Inventory List Side Nav', '2025-06-26 16:42:55'),
(141, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-06-26 16:54:41'),
(142, 'Admin', 'Clicked Inventory List Side Nav', '2025-06-26 16:54:41'),
(143, 'Admin', 'Clicked Request Equipment Side Nav', '2025-06-26 16:54:43'),
(144, 'Admin', 'Clicked Request Equipment Side Nav', '2025-06-26 16:54:45'),
(145, 'Admin', 'Clicked Request Equipment Side Nav', '2025-06-26 16:54:50'),
(146, 'Admin', 'Clicked Inventory List Side Nav', '2025-06-26 16:54:51'),
(147, 'Admin', 'Clicked User Management Side Nav', '2025-06-26 16:54:52'),
(148, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 08:06:06'),
(149, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-01 08:06:09'),
(150, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 08:06:25'),
(151, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 08:06:55'),
(152, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 08:07:12'),
(153, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 08:07:19'),
(154, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 08:07:20'),
(155, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 08:07:21'),
(156, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-01 08:07:22'),
(157, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-01 08:07:25'),
(158, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 08:07:29'),
(159, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-01 08:16:59'),
(160, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 10:35:18'),
(161, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 10:35:21'),
(162, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 11:43:47'),
(163, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 11:43:54'),
(164, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 13:27:29'),
(165, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 13:27:53'),
(166, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 14:12:48'),
(167, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-01 14:15:39'),
(168, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 14:15:51'),
(169, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 14:15:52'),
(170, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-01 14:15:57'),
(171, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-01 14:16:02'),
(172, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-01 14:21:23'),
(173, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 14:21:30'),
(174, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 14:21:30'),
(175, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 14:21:31'),
(176, 'Admin', 'Clicked User Management Side Nav', '2025-07-01 14:21:33'),
(177, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-01 14:21:48'),
(178, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 14:24:33'),
(179, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 14:24:34'),
(180, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 15:27:09'),
(181, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 15:27:15'),
(182, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 15:30:45'),
(183, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-01 15:30:46'),
(184, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 15:30:46'),
(185, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 15:50:21'),
(186, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 15:50:21'),
(187, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 15:50:22'),
(188, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 15:50:24'),
(189, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 16:26:58'),
(190, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 16:27:06'),
(191, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 16:31:16'),
(192, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 16:31:19'),
(193, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 16:31:20'),
(194, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 16:31:22'),
(195, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 16:32:40'),
(196, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 16:32:41'),
(197, 'Admin', 'Clicked User Management Side Nav', '2025-07-01 16:33:12'),
(198, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 16:33:13'),
(199, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 16:33:14'),
(200, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-01 16:52:21'),
(201, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-01 16:52:22'),
(202, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-02 08:11:47'),
(203, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-02 08:11:48'),
(204, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-02 09:03:47'),
(205, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-02 09:03:48'),
(206, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-02 09:04:00'),
(207, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-02 09:31:00'),
(208, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-02 09:31:19'),
(209, 'Admin', 'Clicked search item option', '2025-07-02 09:31:28'),
(210, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-02 09:31:35'),
(211, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-02 09:31:44'),
(212, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-02 14:03:50'),
(213, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-02 14:03:51'),
(214, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-02 16:37:07'),
(215, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-02 16:37:15'),
(216, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-02 16:37:17'),
(217, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-02 16:37:41'),
(218, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-02 16:37:44'),
(219, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-02 16:37:55'),
(220, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-02 16:37:56'),
(221, 'Admin', 'Clicked User Management Side Nav', '2025-07-03 08:25:40'),
(222, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 08:25:46'),
(223, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 08:26:03'),
(224, 'Admin', 'Clicked User Management Side Nav', '2025-07-03 08:29:00'),
(225, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 08:29:02'),
(226, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 08:29:16'),
(227, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 08:29:34'),
(228, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 08:37:42'),
(229, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 08:41:29'),
(230, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 08:45:06'),
(231, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 08:52:39'),
(232, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 13:56:58'),
(233, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 13:57:03'),
(234, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 13:57:26'),
(235, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-03 13:57:51'),
(236, 'Admin', 'Clicked search item option', '2025-07-03 13:57:58'),
(237, 'Admin', 'Clicked search item option', '2025-07-03 13:58:02'),
(238, 'Admin', 'Clicked search item option', '2025-07-03 13:58:05'),
(239, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 14:07:42'),
(240, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 14:07:44'),
(241, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 14:07:55'),
(242, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-03 14:07:58'),
(243, 'Admin', 'Clicked proceed', '2025-07-03 14:08:20'),
(244, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 14:08:43'),
(245, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-03 14:11:52'),
(246, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 14:17:50'),
(247, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 14:20:01'),
(248, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 14:37:50'),
(249, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-03 14:39:35'),
(250, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 14:39:48'),
(251, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-03 14:39:53'),
(252, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 14:40:33'),
(253, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 14:40:45'),
(254, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 15:13:34'),
(255, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 15:13:50'),
(256, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-03 15:26:35'),
(257, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 15:26:37'),
(258, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 15:26:47'),
(259, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 15:27:42'),
(260, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 15:47:39'),
(261, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 15:47:40'),
(262, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-03 15:48:30'),
(263, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 15:48:31'),
(264, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 15:48:37'),
(265, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 16:26:17'),
(266, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-03 16:26:18'),
(267, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 16:26:30'),
(268, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-03 16:45:26'),
(269, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 08:28:30'),
(270, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 08:28:32'),
(271, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 08:28:45'),
(272, 'Admin', 'Clicked proceed', '2025-07-04 08:29:03'),
(273, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 08:29:26'),
(274, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 08:29:49'),
(275, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 08:29:49'),
(276, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 08:29:49'),
(277, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 08:29:52'),
(278, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 08:30:02'),
(279, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 08:36:10'),
(280, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 08:36:10'),
(281, 'Admin', 'Clicked User Management Side Nav', '2025-07-04 08:36:17'),
(282, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 08:36:18'),
(283, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 08:36:20'),
(284, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 08:40:52'),
(285, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 08:40:55'),
(286, 'Admin', 'Clicked User Management Side Nav', '2025-07-04 08:40:57'),
(287, 'Admin', 'Created user: rodulfo (Employee)', '2025-07-04 08:41:56'),
(288, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-04 09:18:58'),
(289, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-04 09:19:27'),
(290, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-04 09:19:49'),
(291, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 09:21:30'),
(292, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 09:21:35'),
(293, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 09:21:35'),
(294, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 09:21:37'),
(295, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 09:21:43'),
(296, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 09:21:50'),
(297, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 09:21:50'),
(298, 'Admin', 'Clicked User Management Side Nav', '2025-07-04 10:15:59'),
(299, 'Admin', 'Clicked User Management Side Nav', '2025-07-04 10:16:00'),
(300, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 10:16:01'),
(301, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 10:16:02'),
(302, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-04 10:16:09'),
(303, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-04 10:19:21'),
(304, 'Admin', 'Clicked User Management Side Nav', '2025-07-04 10:19:22'),
(305, 'Admin', 'Clicked User Management Side Nav', '2025-07-04 10:19:25'),
(306, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 10:21:45'),
(307, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 10:21:48'),
(308, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 10:21:50'),
(309, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 10:22:12'),
(310, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 10:22:14'),
(311, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 10:22:17'),
(312, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 10:22:17'),
(313, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 10:22:27'),
(314, 'Admin', 'Clicked User Management Side Nav', '2025-07-04 10:23:17'),
(315, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-04 10:23:20'),
(316, 'rodulfo', 'Clicked Total Equipment card', '2025-07-04 10:23:50'),
(317, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-04 10:24:48'),
(318, 'rodulfo', 'Clicked Total Equipment card', '2025-07-04 10:24:50'),
(319, 'Admin', 'Clicked User Management Side Nav', '2025-07-04 10:25:31'),
(320, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-04 10:25:33'),
(321, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 10:25:38'),
(322, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 10:25:39'),
(323, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 10:25:49'),
(324, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 11:13:05'),
(325, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 11:13:06'),
(326, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 11:20:25'),
(327, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 11:20:26'),
(328, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 13:27:47'),
(329, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 13:34:42'),
(330, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 13:34:46'),
(331, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 13:34:47'),
(332, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 13:45:28'),
(333, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 13:45:28'),
(334, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 13:45:29'),
(335, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 13:45:38'),
(336, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 13:45:39'),
(337, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 13:45:41'),
(338, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-04 13:55:58'),
(339, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 13:56:00'),
(340, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-04 13:56:00'),
(341, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-04 13:56:01'),
(342, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-07 09:25:33'),
(343, 'Admin', 'Clicked Inventory Count card', '2025-07-07 10:12:56'),
(344, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 10:12:58'),
(345, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 10:13:01'),
(346, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 10:19:31'),
(347, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 10:19:38'),
(348, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 10:19:39'),
(349, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 10:36:05'),
(350, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 10:36:16'),
(351, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 10:40:07'),
(352, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 10:41:10'),
(353, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 10:41:15'),
(354, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 11:01:52'),
(355, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 11:05:06'),
(356, 'Admin', 'Clicked User Management Side Nav', '2025-07-07 11:22:17'),
(357, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 11:22:35'),
(358, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 11:22:36'),
(359, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 11:22:37'),
(360, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 11:22:39'),
(361, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 11:30:45'),
(362, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 11:30:49'),
(363, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 11:30:50'),
(364, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 11:30:56'),
(365, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 11:30:57'),
(366, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 11:31:03'),
(367, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 11:32:56'),
(368, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 11:38:20'),
(369, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 11:47:15'),
(370, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 11:54:37'),
(371, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 11:54:49'),
(372, 'Admin', 'Clicked search item option', '2025-07-07 11:54:51'),
(373, 'Admin', 'Clicked proceed', '2025-07-07 11:55:08'),
(374, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 11:55:16'),
(375, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 11:55:23'),
(376, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 11:55:40'),
(377, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 13:03:38'),
(378, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 13:03:43'),
(379, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:43:30'),
(380, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:43:31'),
(381, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:43:32'),
(382, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:43:38'),
(383, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:43:41'),
(384, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 16:43:47'),
(385, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 16:43:48'),
(386, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:43:49'),
(387, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:44:16'),
(388, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:44:20'),
(389, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 16:44:22'),
(390, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 16:44:23'),
(391, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 16:44:24'),
(392, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 16:44:37'),
(393, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 16:44:39'),
(394, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 16:44:40'),
(395, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:44:44'),
(396, 'Admin', 'Clicked User Management Side Nav', '2025-07-07 16:44:45'),
(397, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:44:48'),
(398, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:44:49'),
(399, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:44:49'),
(400, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 16:44:51'),
(401, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:44:52'),
(402, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:45:10'),
(403, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 16:45:12'),
(404, 'Admin', 'Clicked search item option', '2025-07-07 16:45:17'),
(405, 'Admin', 'Clicked proceed', '2025-07-07 16:45:32'),
(406, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:45:49'),
(407, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:46:07'),
(408, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-07 16:47:52'),
(409, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-07 16:47:53'),
(410, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-07 16:47:54'),
(411, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-07 16:48:20'),
(412, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-08 08:29:06'),
(413, 'rodulfo', 'Clicked Total Equipment card', '2025-07-08 08:29:09'),
(414, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-08 08:29:28'),
(415, 'rodulfo', 'Clicked Total Equipment card', '2025-07-08 08:29:29'),
(416, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-08 08:29:39'),
(417, 'rodulfo', 'Clicked Available Equipment card', '2025-07-08 08:29:44'),
(418, 'rodulfo', 'Clicked Pending Request card', '2025-07-08 08:29:49'),
(419, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-08 08:29:53'),
(420, 'rodulfo', 'Clicked Dashboard Side Nav', '2025-07-08 08:29:58'),
(421, 'Admin', 'Clicked User Management Side Nav', '2025-07-08 08:30:16'),
(422, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:30:17'),
(423, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-08 08:30:20'),
(424, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 08:30:23'),
(425, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 08:30:24'),
(426, 'Admin', 'Reported item ID 985 from table tbl_inv as Missing.', '2025-07-08 08:30:30'),
(427, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:30:34'),
(428, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:30:44'),
(429, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:30:53'),
(430, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:30:53'),
(431, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:30:54'),
(432, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 08:30:57'),
(433, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:31:02'),
(434, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:31:05'),
(435, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:31:08'),
(436, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:31:14'),
(437, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:31:17'),
(438, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:31:24'),
(439, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:32:04'),
(440, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:32:06'),
(441, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:15'),
(442, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 08:35:18'),
(443, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 08:35:19'),
(444, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:21'),
(445, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:23'),
(446, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:27'),
(447, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:29'),
(448, 'Admin', 'Clicked User Management Side Nav', '2025-07-08 08:35:29'),
(449, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:30'),
(450, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:30'),
(451, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:34'),
(452, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:47'),
(453, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:49'),
(454, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:53'),
(455, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:35:55'),
(456, 'Admin', 'Clicked User Management Side Nav', '2025-07-08 08:38:32'),
(457, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 08:38:32'),
(458, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 08:38:35'),
(459, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 08:38:35'),
(460, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 08:38:36'),
(461, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-08 08:38:36'),
(462, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:15:53'),
(463, 'Admin', 'Clicked Equipment Pie Chart card', '2025-07-08 09:16:12'),
(464, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:24:15'),
(465, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 09:24:17'),
(466, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 09:24:17'),
(467, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 09:24:18'),
(468, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:24:21'),
(469, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:24:32'),
(470, 'Admin', 'Clicked User Management Side Nav', '2025-07-08 09:24:33'),
(471, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-08 09:24:36'),
(472, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:26:19'),
(473, 'Admin', 'Clicked User Management Side Nav', '2025-07-08 09:26:20'),
(474, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:26:21'),
(475, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:26:22'),
(476, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:26:22'),
(477, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 09:26:26'),
(478, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 09:26:35'),
(479, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 09:27:03'),
(480, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 09:27:05'),
(481, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 09:27:07'),
(482, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 09:27:08'),
(483, 'Admin', 'Reported item ID 986 from table tbl_inv_consumables as Missing.', '2025-07-08 09:27:40'),
(484, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:27:45'),
(485, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 09:27:45'),
(486, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 09:27:52'),
(487, 'Admin', 'Reported item ID 985 from table tbl_inv as Missing.', '2025-07-08 09:27:56'),
(488, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:28:01'),
(489, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:28:08'),
(490, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 09:28:09'),
(491, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:42:20'),
(492, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:42:22'),
(493, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 09:42:47'),
(494, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:04:04'),
(495, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 10:04:09'),
(496, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:10:22'),
(497, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 10:10:31'),
(498, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 10:10:32'),
(499, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:20:36'),
(500, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 10:20:36'),
(501, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 10:20:40'),
(502, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 10:20:41'),
(503, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 10:20:41'),
(504, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:20:57'),
(505, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:23:50'),
(506, 'Admin', 'Clicked User Management Side Nav', '2025-07-08 10:23:51'),
(507, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-08 10:24:18'),
(508, 'Admin', 'Clicked Total Equipment card', '2025-07-08 10:24:19'),
(509, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:24:27'),
(510, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:24:27'),
(511, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:24:28'),
(512, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:24:28'),
(513, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 10:24:29'),
(514, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-08 10:24:30'),
(515, 'Admin', 'Clicked Available Equipment card', '2025-07-08 10:24:32'),
(516, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-08 10:24:33'),
(517, 'Admin', 'Clicked Total Equipment card', '2025-07-08 10:24:34'),
(518, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-08 10:24:35'),
(519, 'Admin', 'Clicked Total Equipment card', '2025-07-08 10:29:28'),
(520, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:29:29'),
(521, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:29:30'),
(522, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:29:30'),
(523, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:29:30'),
(524, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:29:31'),
(525, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:29:32'),
(526, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 10:29:44'),
(527, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 10:29:45'),
(528, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 10:30:08'),
(529, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 10:30:09'),
(530, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 10:30:09'),
(531, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 11:25:45'),
(532, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 11:25:50'),
(533, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 11:25:53'),
(534, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 11:25:55'),
(535, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 11:44:07'),
(536, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 11:44:10'),
(537, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 11:44:11'),
(538, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 11:44:16'),
(539, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 11:44:17'),
(540, 'Admin', 'Reported item ID 985 from table tbl_inv as Missing.', '2025-07-08 11:59:04'),
(541, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 12:00:33'),
(542, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 12:00:38'),
(543, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 12:00:39'),
(544, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 12:00:40'),
(545, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 13:15:45'),
(546, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 13:52:27'),
(547, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 13:52:28'),
(548, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 13:56:58'),
(549, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 13:57:00'),
(550, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 14:00:28'),
(551, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:00:29'),
(552, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 14:00:29'),
(553, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:00:35'),
(554, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:03:12'),
(555, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:05:49'),
(556, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:05:51'),
(557, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:06:09'),
(558, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:06:13'),
(559, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:06:15'),
(560, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:06:17'),
(561, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:06:20'),
(562, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:06:23'),
(563, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 14:14:10'),
(564, 'Admin', 'Clicked Available Equipment card', '2025-07-08 15:05:53'),
(565, 'Admin', 'Clicked Total Equipment card', '2025-07-08 15:05:57'),
(566, 'Admin', 'Deleted item ID 986 from table tbl_inv_consumables.', '2025-07-08 15:08:26'),
(567, 'Admin', 'Deleted item ID 986 from table tbl_inv_consumables.', '2025-07-08 15:08:29'),
(568, 'Admin', 'Deleted item ID 986 from table tbl_inv.', '2025-07-08 15:16:03'),
(569, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 15:22:21'),
(570, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 15:22:24'),
(571, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 15:22:33'),
(572, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 15:23:20'),
(573, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 15:23:21'),
(574, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 15:41:28'),
(575, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 15:41:29'),
(576, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 15:41:32'),
(577, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 15:41:32'),
(578, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 15:44:08'),
(579, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 15:44:11'),
(580, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 15:49:40'),
(581, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 15:49:42'),
(582, 'Admin', 'Clicked Dashboard Side Nav', '2025-07-08 16:17:17'),
(583, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:17:19'),
(584, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:17:19'),
(585, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:17:20'),
(586, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:53:24'),
(587, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:53:26'),
(588, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:53:26'),
(589, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:53:29'),
(590, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 16:53:29'),
(591, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 16:53:30'),
(592, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 16:53:30'),
(593, 'Admin', 'Clicked User Management Side Nav', '2025-07-08 16:55:15'),
(594, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:55:17'),
(595, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:55:18'),
(596, 'Admin', 'Clicked Inventory Dropdown Side Nav', '2025-07-08 16:55:35'),
(597, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 16:55:36'),
(598, 'Admin', 'Clicked Request Equipment Side Nav', '2025-07-08 16:55:37'),
(599, 'Admin', 'Clicked Inventory List Side Nav', '2025-07-08 16:55:38');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_borrow_request`
--

CREATE TABLE `tbl_borrow_request` (
  `breq_id` int(11) NOT NULL,
  `breq_token` varchar(255) NOT NULL,
  `emp_name` varchar(255) NOT NULL,
  `breq_status` int(1) NOT NULL,
  `breq_date` varchar(255) NOT NULL,
  `breq_decisiondate` varchar(255) NOT NULL,
  `breq_remarks` varchar(255) NOT NULL,
  `breq_signature` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_borrow_request`
--

INSERT INTO `tbl_borrow_request` (`breq_id`, `breq_token`, `emp_name`, `breq_status`, `breq_date`, `breq_decisiondate`, `breq_remarks`, `breq_signature`) VALUES
(1, 'PRC-000001', 'Josh', 5, '2025-07-02 09:32:18', '2025-07-03 08:29:31', 'Borrow', 'signatures/PRC-000001.png'),
(2, 'PRC-000002', 'Josh', 5, '2025-07-03 14:08:30', '2025-07-03 14:40:56', 'FOR PRINTING', 'signatures/PRC-000002.png'),
(3, 'PRC-000003', 'JADE', 5, '2025-07-03 14:40:22', '2025-07-03 14:40:52', 'For borrowing', 'signatures/PRC-000003.png'),
(4, 'PRC-000004', 'Josh', 5, '2025-07-04 08:29:08', '2025-07-04 09:21:41', 'For gaming', 'signatures/PRC-000004.png'),
(5, 'PRC-000005', 'Joshi', 5, '2025-07-07 11:55:12', '2025-07-07 11:55:37', 'Borrow', 'signatures/PRC-000005.png'),
(6, 'PRC-000006', 'Joshig', 5, '2025-07-07 16:45:36', '2025-07-07 16:46:05', 'Mobile', 'signatures/PRC-000006.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_borrow_request_items`
--

CREATE TABLE `tbl_borrow_request_items` (
  `br_item_id` int(11) NOT NULL,
  `breq_token` varchar(255) NOT NULL,
  `inv_id` int(11) NOT NULL,
  `is_approved` int(1) NOT NULL,
  `borrowed_date` varchar(255) NOT NULL,
  `returned_date` varchar(255) NOT NULL,
  `returner_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_borrow_request_items`
--

INSERT INTO `tbl_borrow_request_items` (`br_item_id`, `breq_token`, `inv_id`, `is_approved`, `borrowed_date`, `returned_date`, `returner_name`) VALUES
(1, 'PRC-000001', 985, 1, '2025-07-03 08:29:12', '2025-07-03 08:29:31', 'Ma\'am G'),
(2, 'PRC-000002', 984, 1, '2025-07-03 14:08:40', '2025-07-03 14:40:56', 'Ma\'am GG'),
(3, 'PRC-000003', 985, 1, '2025-07-03 14:40:30', '2025-07-03 14:40:52', 'Ma\'am G'),
(4, 'PRC-000004', 984, 1, '2025-07-04 08:29:23', '2025-07-04 09:21:41', 'Ma\'am G'),
(5, 'PRC-000005', 984, 1, '2025-07-07 11:55:20', '2025-07-07 11:55:37', 'Ma\'am GX'),
(6, 'PRC-000006', 984, 1, '2025-07-07 16:45:46', '2025-07-07 16:46:05', 'Ma\'am GXG');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_consumable_requests`
--

CREATE TABLE `tbl_consumable_requests` (
  `request_id` int(11) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity_requested` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inv`
--

CREATE TABLE `tbl_inv` (
  `inv_id` int(11) NOT NULL,
  `type_id` int(3) NOT NULL,
  `inv_serialno` varchar(255) NOT NULL,
  `inv_propno` varchar(255) NOT NULL,
  `inv_propname` varchar(255) NOT NULL,
  `inv_price` decimal(12,2) DEFAULT 0.00,
  `inv_status` varchar(255) NOT NULL,
  `inv_bnm` varchar(255) NOT NULL,
  `inv_date_added` varchar(255) DEFAULT NULL,
  `date_acquired` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `condition` varchar(255) DEFAULT 'New',
  `inv_quantity` int(11) NOT NULL,
  `end_user` varchar(255) NOT NULL,
  `accounted_to` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_inv`
--

INSERT INTO `tbl_inv` (`inv_id`, `type_id`, `inv_serialno`, `inv_propno`, `inv_propname`, `inv_price`, `inv_status`, `inv_bnm`, `inv_date_added`, `date_acquired`, `price`, `condition`, `inv_quantity`, `end_user`, `accounted_to`) VALUES
(984, 1, '09123X-123D', 'PO123', 'REGULATION', 0.00, '1', 'Altech Y-2019', 'July 02, 2025 08:34 AM', '2025-07-02', 20000.00, 'New', 0, '', 'Ma\'am Y'),
(985, 1, '09123X-1233', 'PO125', 'Regulation', 0.00, '1', 'Altech Y-2019', 'July 02, 2025 09:03 AM', '2025-07-16', 20000.00, 'Good', 0, '', 'Ma\'am Z'),
(987, 2, '09123X-123312', 'PO12578', 'Regulation', 0.00, '1', 'Altech Y-2019', 'July 08, 2025 03:49 PM', NULL, 30000.00, 'New', 0, 'Gason', 'Ma\'am Z');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inv_consumables`
--

CREATE TABLE `tbl_inv_consumables` (
  `inv_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `inv_bnm` varchar(255) DEFAULT NULL,
  `inv_serialno` varchar(255) DEFAULT NULL,
  `inv_propno` varchar(255) DEFAULT NULL,
  `inv_propname` varchar(255) DEFAULT NULL,
  `inv_status` int(11) DEFAULT 1,
  `inv_date_added` varchar(255) DEFAULT NULL,
  `date_acquired` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `inv_quantity` int(11) DEFAULT 1,
  `end_user` varchar(255) DEFAULT NULL,
  `accounted_to` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_inv_consumables`
--

INSERT INTO `tbl_inv_consumables` (`inv_id`, `type_id`, `inv_bnm`, `inv_serialno`, `inv_propno`, `inv_propname`, `inv_status`, `inv_date_added`, `date_acquired`, `price`, `inv_quantity`, `end_user`, `accounted_to`) VALUES
(1, 38, 'Downy Rose Scent', NULL, NULL, '0', 6, '0', '2025-07-09', 800.00, 69, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_type`
--

CREATE TABLE `tbl_type` (
  `type_id` int(3) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `type_origin` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_type`
--

INSERT INTO `tbl_type` (`type_id`, `type_name`, `type_origin`) VALUES
(1, 'System Unit', 'Non-Consumable'),
(2, 'Monitor', 'Non-Consumable'),
(3, 'Mouse', 'Non-Consumable'),
(4, 'Keyboard', 'Non-Consumable'),
(5, 'UPS', 'Non-Consumable'),
(6, 'LAN Adapter', 'Non-Consumable'),
(7, 'Laptop', 'Non-Consumable'),
(8, 'Security Cable Lock', 'Non-Consumable'),
(9, 'Speaker', 'Non-Consumable'),
(10, 'Switch', 'Non-Consumable'),
(11, 'Fingerprint Scanner', 'Non-Consumable'),
(12, 'Printer', 'Non-Consumable'),
(13, 'All in One Unit', 'Non-Consumable'),
(14, 'Laptop Pouch', 'Non-Consumable'),
(15, 'AC Adapter', 'Non-Consumable'),
(16, 'Barcode Scanner', 'Non-Consumable'),
(17, 'Camera', 'Non-Consumable'),
(18, 'HDD', 'Non-Consumable'),
(19, 'Modem', 'Non-Consumable'),
(20, 'Projector', 'Non-Consumable'),
(21, 'Router', 'Non-Consumable'),
(22, 'SDD', 'Non-Consumable'),
(23, 'VGA Splitter', 'Non-Consumable'),
(24, 'Webcam', 'Non-Consumable'),
(25, 'Security Camera', 'Non-Consumable'),
(26, 'Signature Pad', 'Non-Consumable'),
(27, 'Server', 'Non-Consumable'),
(28, 'NAS', 'Non-Consumable'),
(29, 'Satellite Dish', 'Non-Consumable'),
(30, 'Group', 'Non-Consumable'),
(31, 'Microphone', 'Non-Consumable'),
(32, 'Image Scanner', 'Non-Consumable'),
(33, 'HDMI Splitter', 'Non-Consumable'),
(34, 'Network Cable Tester', 'Non-Consumable'),
(35, 'DRONE', 'Non-Consumable'),
(36, 'DRONE RC', 'Non-Consumable'),
(37, 'Rack Mount', 'Non-Consumable'),
(38, 'Cleaning supplies', 'Consumable');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_ln` varchar(255) NOT NULL,
  `user_mi` varchar(255) NOT NULL,
  `user_fn` varchar(255) NOT NULL,
  `user_status` int(11) NOT NULL,
  `user_level` varchar(255) NOT NULL,
  `user_date_created` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `user_password`, `user_ln`, `user_mi`, `user_fn`, `user_status`, `user_level`, `user_date_created`) VALUES
(1, 'Admin', '$2y$10$AQyHxe9lfuYmN0SuJk0ja.mb7TYoBODNXKZbvGr/4c3dHsNJy3XZ.', 'CAR', '-', 'PRC', 1, 'Admin', '2025-03-21 03:52:55'),
(2, 'rodulfo', '$2y$10$rYPHyPS346U8nM3wmVsoU.9neq7r7TVgWNJJ79DDr6EE87WCDRpfq', 'Dacocot', 'g', 'Rodulfo', 1, 'Employee', '2025-07-04 02:41:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_auditlog`
--
ALTER TABLE `tbl_auditlog`
  ADD PRIMARY KEY (`auditlog_id`);

--
-- Indexes for table `tbl_borrow_request`
--
ALTER TABLE `tbl_borrow_request`
  ADD PRIMARY KEY (`breq_id`);

--
-- Indexes for table `tbl_borrow_request_items`
--
ALTER TABLE `tbl_borrow_request_items`
  ADD PRIMARY KEY (`br_item_id`);

--
-- Indexes for table `tbl_consumable_requests`
--
ALTER TABLE `tbl_consumable_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `item_type_id` (`item_type_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_inv`
--
ALTER TABLE `tbl_inv`
  ADD PRIMARY KEY (`inv_id`);

--
-- Indexes for table `tbl_inv_consumables`
--
ALTER TABLE `tbl_inv_consumables`
  ADD PRIMARY KEY (`inv_id`);

--
-- Indexes for table `tbl_type`
--
ALTER TABLE `tbl_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_auditlog`
--
ALTER TABLE `tbl_auditlog`
  MODIFY `auditlog_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=600;

--
-- AUTO_INCREMENT for table `tbl_borrow_request`
--
ALTER TABLE `tbl_borrow_request`
  MODIFY `breq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_borrow_request_items`
--
ALTER TABLE `tbl_borrow_request_items`
  MODIFY `br_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_consumable_requests`
--
ALTER TABLE `tbl_consumable_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_inv`
--
ALTER TABLE `tbl_inv`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=988;

--
-- AUTO_INCREMENT for table `tbl_inv_consumables`
--
ALTER TABLE `tbl_inv_consumables`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_type`
--
ALTER TABLE `tbl_type`
  MODIFY `type_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
