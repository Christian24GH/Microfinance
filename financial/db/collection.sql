-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 13, 2025 at 07:19 PM
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
  `fk_loan_id` int(11) NOT NULL,
  `paymentMethod` enum('Cash','GCash','Maya','Bank Transfer','Cheque') NOT NULL,
  `amountPaid` decimal(12,2) NOT NULL,
  `date` date NOT NULL,
  `history` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_ar_tbl`
--

CREATE TABLE `collection_ar_tbl` (
  `ar_id` int(11) NOT NULL,
  `loanacc_id` int(11) NOT NULL,
  `disb_loan_id` int(11) NOT NULL,
  `outstandingAmount` decimal(12,2) NOT NULL,
  `transactionDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_management_tbl`
--

CREATE TABLE `collection_management_tbl` (
  `collection_id` int(11) NOT NULL,
  `fk_disbursement_id` int(11) NOT NULL,
  `fk_disb_loan_id` int(11) NOT NULL,
  `overdueAccount` varchar(50) NOT NULL,
  `overdueDate` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `collection_account_payment_tbl_ibfk_1` (`fk_loan_id`);

--
-- Indexes for table `collection_ar_tbl`
--
ALTER TABLE `collection_ar_tbl`
  ADD PRIMARY KEY (`ar_id`),
  ADD KEY `collection_ar_tbl_ibfk_1` (`loanacc_id`),
  ADD KEY `disb_loan_id` (`disb_loan_id`);

--
-- Indexes for table `collection_management_tbl`
--
ALTER TABLE `collection_management_tbl`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `collection_management_tbl_ibfk_1` (`fk_disbursement_id`),
  ADD KEY `fk_loan_id` (`fk_disb_loan_id`);

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
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_ar_tbl`
--
ALTER TABLE `collection_ar_tbl`
  MODIFY `ar_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_management_tbl`
--
ALTER TABLE `collection_management_tbl`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `collection_account_payment_tbl_ibfk_1` FOREIGN KEY (`fk_loan_id`) REFERENCES `ar_repayment_management_tbl` (`repayment_id`);

--
-- Constraints for table `collection_ar_tbl`
--
ALTER TABLE `collection_ar_tbl`
  ADD CONSTRAINT `collection_ar_tbl_ibfk_1` FOREIGN KEY (`loanacc_id`) REFERENCES `ar_loan_account_tbl` (`loanacc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `collection_ar_tbl_ibfk_2` FOREIGN KEY (`disb_loan_id`) REFERENCES `disb_loan_tbl` (`loan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `collection_management_tbl`
--
ALTER TABLE `collection_management_tbl`
  ADD CONSTRAINT `collection_management_tbl_ibfk_1` FOREIGN KEY (`fk_disbursement_id`) REFERENCES `disbursement_tbl` (`disbursement_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `collection_management_tbl_ibfk_2` FOREIGN KEY (`fk_disb_loan_id`) REFERENCES `disb_loan_tbl` (`loan_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
