-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 09:30 AM
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
-- Table structure for table `tbl_inv`
--

CREATE TABLE `tbl_inv` (
  `inv_id` int(11) NOT NULL,
  `type_id` int(3) NOT NULL,
  `inv_serialno` varchar(255) NOT NULL,
  `inv_propno` varchar(255) NOT NULL,
  `inv_status` varchar(255) NOT NULL,
  `inv_bnm` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_inv`
--

INSERT INTO `tbl_inv` (`inv_id`, `type_id`, `inv_serialno`, `inv_propno`, `inv_status`, `inv_bnm`) VALUES
(1, 1, 'mck12', 'cbl1', '2', 'acer kj1'),
(2, 1, 'haha', 'laugh', '1', 'joke item'),
(3, 1, 'juk', 'kuk', '1', 'heal yourself'),
(4, 1, 'trejo', 'reina', '1', 'kj ult'),
(5, 1, '23', '23', '1', '23 23'),
(6, 2, 'sd', 'sd', '1', 'acer 23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_req`
--

CREATE TABLE `tbl_req` (
  `req_id` int(11) NOT NULL,
  `req_token` varchar(255) NOT NULL,
  `inv_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `req_status` int(1) NOT NULL,
  `req_date` varchar(255) NOT NULL,
  `req_decisiondate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_type`
--

CREATE TABLE `tbl_type` (
  `type_id` int(3) NOT NULL,
  `type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_type`
--

INSERT INTO `tbl_type` (`type_id`, `type_name`) VALUES
(1, 'Computer'),
(2, 'Laptop');

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
(1, '123', '$2y$10$7cvfuMMRARItb90yhDWqeuil/pOXjDPRYR1vDXeZRgKP24zWpVtNq', '123', '2', '123', 1, '0', '2025-03-14'),
(2, '23', '$2y$10$mvAj1r/qkwiL6p.h7t.ewOh.lT/UwXJwYLU9jf.Zq.zJgi.tYi1za', '123', '1', '123', 1, 'Admin', '2025-03-14'),
(3, '123e', '$2y$10$/wWgCUkh2lM71NXrDIAq5.bIIwoytycvis0dSyx2Ph/bpDb1FXPqe', '2', '2', '2', 1, 'Admin', '2025-03-14'),
(4, 'ee', '$2y$10$OnrCfvPZf14843Tyla6VJOoaoGadwokE.j0PS5/.HMiQyH2bLjAqW', 'e', 'e', 'e', 1, 'Employee', '2025-03-14'),
(5, 'd', '$2y$10$k00caP9Hbm.yYQTWMTjwgecTsr4Uy.vGG.KBZEm1oggddObF6vgPK', 'e', 'e', 'e', 1, 'Admin', '2025-03-14'),
(6, 'sdv', '$2y$10$Yl7yuWeYHPKZemHIEKf/BeucZx3M4vz3hI0TZzv9YKNZngWV4Bgze', 'e', 'e', 'e', 1, 'Admin', '2025-03-14'),
(7, 'wsd', '$2y$10$XDN9EXgebv7LUgCCRnZGAew/Qufut3ohSxEYFUdoXo4bCSR4XJ4IW', 'sd', 'd', 'sd', 1, 'Admin', '2025-03-14'),
(8, 'ded', '$2y$10$BhVEa1Q1R8dTDvec3yKpD.7VUIBPZIRjpJ4FtGxSWEzAB.Bc4KadK', '3', '3', '3', 1, 'Admin', '2025-03-14'),
(9, 'sadsda', '$2y$10$ZihafZ3VZpJnkWc38akhxOPxVmLD9McWrRfz3KMxxeS5i.mQT5IVS', 's', 's', 's', 1, 'Admin', '2025-03-14'),
(10, '123456', '$2y$10$uPxHEUpGpAcPiXgWy5pCYORAPUTGjnehr7WbPy4DAVd4jwlQTxlVy', 'Sin', 'a', 'Davis Van', 1, 'Employee', '2025-03-14'),
(11, 'great', '$2y$10$IMeHlwaVCos9/Oc2lf8p3ODgUxE2QbtzuMu6KgP4DQUghyUkDaPXW', 'Sin', 's', 'Davis Van', 1, 'Admin', '2025-03-14 14:16:48'),
(12, 'a', '$2y$10$ChrJl4NgXpJdi6PmD5K1rOfGR0U13g9/fdZuMT/boy0cMxy9elu3y', 'a', '', 'a', 1, 'Admin', '2025-03-14 14:22:59'),
(13, 'b', '$2y$10$33fIhu5iMsfnBLufB7bhp.wBEeui9OXg.agGSiG0BPf0gqZuF5g76', 'a', 'b', 'a', 1, 'Admin', '2025-03-14 14:23:42'),
(14, 'dada', '$2y$10$UyBbN8xkko3IgiJlowCVeu0eAfs5F5Y6WswUJRHJ8Uo7ya0LYRuli', 'hahaha', 'h', 'hahah', 1, 'Employee', '2025-03-14 14:33:13'),
(15, 'god is great', '$2y$10$8MmVXRR3JU2.r6nI3gePDOJZVQR.jph5aJ4UlTLNdEYF3NGuQBom2', 'god', 'f', 'the', 1, 'Admin', '2025-03-14 16:40:44'),
(16, '20', '$2y$10$JIyQ0T5ss8lwqoMb9RQCWOdw0VNcrGgQXnj8i2Ifc5b6MrwiqSTAO', '20', '2', '20', 1, 'Employee', '2025-03-14 16:43:15'),
(17, 'freedom', '$2y$10$MP5uLf8lYW5vCOYROcqPruElFFOvmrQBKklJ6XxMrTR.v.casR.I2', 'asd', 'a', 'asd', 1, 'Admin', '2025-03-14 16:44:46'),
(18, 'asdad', '$2y$10$GCu90vYW9n9Zft/vS71Wj.qH2YNZrQBi3SLOAiVtHm9HiymfFuw3m', 'asdasd', 'a', 'asdasda', 1, 'Employee', '2025-03-14 16:47:13'),
(19, 'q', '$2y$10$P6qN2Qr.eIgMyK.yEkgYg.k.l2FZ1p.8nd8xd6t5CnQLPVPrwP1/W', 'q', 'q', 'q', 1, 'Employee', '2025-03-17 14:11:58'),
(20, 'employee', '$2y$10$XAgw.Eh42rFvxjI6pu.7XuLHwqbOopdDSYJieMvg6wnk8jzbVDnCy', 'wh', 'w', 'wh', 1, 'Employee', '2025-03-17 16:00:17'),
(21, '21', '$2y$10$r80Y3vtQ1EMT.lGpGQHvp.mKXiH5UZAy1egxY9P5v.UWWopEFk8cu', '21', '2', '21', 1, 'Employee', '2025-03-17 09:17:03'),
(22, 'Ad', '$2y$10$RDgI7rj6qx7lRja82/MksOV1GG2E9mZFnE71EDWbstdD5pF.62tmm', 'ADM', 'A', 'ADM', 1, 'Admin', '2025-03-17 09:18:17'),
(23, 'Jandel', '$2y$10$7JJDKyCQYMncXMaWiKt8a.V4o5FcTjnw7SWRunbvQaTGSSF5OyQ.q', 'Estioco', 'B', 'Jandel', 1, 'Admin', '2025-03-18 01:59:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_inv`
--
ALTER TABLE `tbl_inv`
  ADD PRIMARY KEY (`inv_id`);

--
-- Indexes for table `tbl_req`
--
ALTER TABLE `tbl_req`
  ADD PRIMARY KEY (`req_id`);

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
-- AUTO_INCREMENT for table `tbl_inv`
--
ALTER TABLE `tbl_inv`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_req`
--
ALTER TABLE `tbl_req`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_type`
--
ALTER TABLE `tbl_type`
  MODIFY `type_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
