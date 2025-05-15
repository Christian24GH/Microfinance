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
-- Table structure for table `disbursement_tbl`
--

CREATE TABLE `disbursement_tbl` (
  `disbursement_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `disbursementDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disb_loan_tbl`
--

CREATE TABLE `disb_loan_tbl` (
  `loan_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `interestRate` decimal(5,2) NOT NULL,
  `term` date NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disb_repayment_tbl`
--

CREATE TABLE `disb_repayment_tbl` (
  `repayment_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `amountPaid` decimal(10,2) NOT NULL,
  `repaymentDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disb_report_tbl`
--

CREATE TABLE `disb_report_tbl` (
  `report_id` int(11) NOT NULL,
  `report_type` varchar(20) NOT NULL,
  `report_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `disbursement_tbl`
--
ALTER TABLE `disbursement_tbl`
  ADD PRIMARY KEY (`disbursement_id`),
  ADD KEY `disbursement_tbl_ibfk_1` (`loan_id`);

--
-- Indexes for table `disb_loan_tbl`
--
ALTER TABLE `disb_loan_tbl`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `borrower_id` (`borrower_id`);

--
-- Indexes for table `disb_repayment_tbl`
--
ALTER TABLE `disb_repayment_tbl`
  ADD PRIMARY KEY (`repayment_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `disb_report_tbl`
--
ALTER TABLE `disb_report_tbl`
  ADD PRIMARY KEY (`report_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `disbursement_tbl`
--
ALTER TABLE `disbursement_tbl`
  MODIFY `disbursement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disb_loan_tbl`
--
ALTER TABLE `disb_loan_tbl`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disb_repayment_tbl`
--
ALTER TABLE `disb_repayment_tbl`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disb_report_tbl`
--
ALTER TABLE `disb_report_tbl`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `disbursement_tbl`
--
ALTER TABLE `disbursement_tbl`
  ADD CONSTRAINT `disbursement_tbl_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `disb_loan_tbl` (`loan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `disb_loan_tbl`
--
ALTER TABLE `disb_loan_tbl`
  ADD CONSTRAINT `disb_loan_tbl_ibfk_1` FOREIGN KEY (`borrower_id`) REFERENCES `borrower_tbl` (`borrower_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `disb_repayment_tbl`
--
ALTER TABLE `disb_repayment_tbl`
  ADD CONSTRAINT `disb_repayment_tbl_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `disb_loan_tbl` (`loan_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
