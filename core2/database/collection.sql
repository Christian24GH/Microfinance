-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 09, 2025 at 05:22 PM
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
-- Database: `microfinance`
--

-- --------------------------------------------------------

--
-- Table structure for table `collection_account_payment_tbl`
--

CREATE TABLE `collection_account_payment_tbl` (
  `payment_id` int(11) NOT NULL,
  `ar_id` int(11) NOT NULL,
  `paymentMethod` enum('GCash','Bank') NOT NULL,
  `remainingBalance` decimal(12,2) NOT NULL,
  `date` date NOT NULL,
  `history` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_account_payment_tbl`
--

INSERT INTO `collection_account_payment_tbl` (`payment_id`, `ar_id`, `paymentMethod`, `remainingBalance`, `date`, `history`) VALUES
(1, 8, 'Bank', 500.00, '2025-02-02', 'pay 500');

-- --------------------------------------------------------

--
-- Table structure for table `collection_ar_tbl`
--

CREATE TABLE `collection_ar_tbl` (
  `ar_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `outstandingAmount` decimal(12,2) NOT NULL,
  `transactionDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_ar_tbl`
--

INSERT INTO `collection_ar_tbl` (`ar_id`, `borrower_id`, `outstandingAmount`, `transactionDate`) VALUES
(8, 1, 500.00, '2025-02-02');

-- --------------------------------------------------------

--
-- Table structure for table `collection_management_tbl`
--

CREATE TABLE `collection_management_tbl` (
  `collection_id` int(11) NOT NULL,
  `ar_id` int(11) NOT NULL,
  `overdueAccount` varchar(50) NOT NULL,
  `overdueDate` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_management_tbl`
--

INSERT INTO `collection_management_tbl` (`collection_id`, `ar_id`, `overdueAccount`, `overdueDate`, `status`) VALUES
(2, 8, 'jerald', '2022-02-02', 'pass overdue');

-- --------------------------------------------------------

--
-- Table structure for table `collection_report_tbl`
--

CREATE TABLE `collection_report_tbl` (
  `report_id` int(11) NOT NULL,
  `aging` text NOT NULL,
  `invoice` text NOT NULL,
  `transactionLog` text NOT NULL,
  `date` date NOT NULL,
  `report_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collection_account_payment_tbl`
--
ALTER TABLE `collection_account_payment_tbl`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `ar_id` (`ar_id`);

--
-- Indexes for table `collection_ar_tbl`
--
ALTER TABLE `collection_ar_tbl`
  ADD PRIMARY KEY (`ar_id`),
  ADD KEY `borrower_id` (`borrower_id`);

--
-- Indexes for table `collection_management_tbl`
--
ALTER TABLE `collection_management_tbl`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `collection_management_tbl_ibfk_1` (`ar_id`);

--
-- Indexes for table `collection_report_tbl`
--
ALTER TABLE `collection_report_tbl`
  ADD PRIMARY KEY (`report_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collection_account_payment_tbl`
--
ALTER TABLE `collection_account_payment_tbl`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `collection_ar_tbl`
--
ALTER TABLE `collection_ar_tbl`
  MODIFY `ar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `collection_management_tbl`
--
ALTER TABLE `collection_management_tbl`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `collection_report_tbl`
--
ALTER TABLE `collection_report_tbl`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collection_account_payment_tbl`
--
ALTER TABLE `collection_account_payment_tbl`
  ADD CONSTRAINT `collection_account_payment_tbl_ibfk_1` FOREIGN KEY (`ar_id`) REFERENCES `collection_ar_tbl` (`ar_id`);

--
-- Constraints for table `collection_ar_tbl`
--
ALTER TABLE `collection_ar_tbl`
  ADD CONSTRAINT `collection_ar_tbl_ibfk_1` FOREIGN KEY (`borrower_id`) REFERENCES `borrower_tbl` (`borrower_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `collection_management_tbl`
--
ALTER TABLE `collection_management_tbl`
  ADD CONSTRAINT `collection_management_tbl_ibfk_1` FOREIGN KEY (`ar_id`) REFERENCES `collection_ar_tbl` (`ar_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
