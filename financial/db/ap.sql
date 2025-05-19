-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 13, 2025 at 07:17 PM
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
-- Table structure for table `ap_funder_repayment_tbl`
--

CREATE TABLE `ap_funder_repayment_tbl` (
  `repayment_id` int(11) NOT NULL,
  `liability_id` int(11) NOT NULL,
  `amountPaid` decimal(10,2) NOT NULL,
  `paymentDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ap_funding_source_tbl`
--

CREATE TABLE `ap_funding_source_tbl` (
  `source_id` int(11) NOT NULL,
  `sourceName` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `contractTerms` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ap_liability_tbl`
--

CREATE TABLE `ap_liability_tbl` (
  `liability_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `initialAmount` decimal(10,2) NOT NULL,
  `interestRate` decimal(5,2) NOT NULL,
  `dueDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ap_report_tbl`
--

CREATE TABLE `ap_report_tbl` (
  `report_id` int(11) NOT NULL,
  `report_type` varchar(20) NOT NULL,
  `report_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ap_funder_repayment_tbl`
--
ALTER TABLE `ap_funder_repayment_tbl`
  ADD PRIMARY KEY (`repayment_id`),
  ADD KEY `liability_id` (`liability_id`);

--
-- Indexes for table `ap_funding_source_tbl`
--
ALTER TABLE `ap_funding_source_tbl`
  ADD PRIMARY KEY (`source_id`);

--
-- Indexes for table `ap_liability_tbl`
--
ALTER TABLE `ap_liability_tbl`
  ADD PRIMARY KEY (`liability_id`),
  ADD KEY `source_id` (`source_id`);

--
-- Indexes for table `ap_report_tbl`
--
ALTER TABLE `ap_report_tbl`
  ADD PRIMARY KEY (`report_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ap_funder_repayment_tbl`
--
ALTER TABLE `ap_funder_repayment_tbl`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ap_funding_source_tbl`
--
ALTER TABLE `ap_funding_source_tbl`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ap_liability_tbl`
--
ALTER TABLE `ap_liability_tbl`
  MODIFY `liability_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ap_report_tbl`
--
ALTER TABLE `ap_report_tbl`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ap_funder_repayment_tbl`
--
ALTER TABLE `ap_funder_repayment_tbl`
  ADD CONSTRAINT `ap_funder_repayment_tbl_ibfk_1` FOREIGN KEY (`liability_id`) REFERENCES `ap_liability_tbl` (`liability_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ap_liability_tbl`
--
ALTER TABLE `ap_liability_tbl`
  ADD CONSTRAINT `ap_liability_tbl_ibfk_1` FOREIGN KEY (`source_id`) REFERENCES `ap_funding_source_tbl` (`source_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
