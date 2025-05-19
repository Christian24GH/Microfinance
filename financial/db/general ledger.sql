-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 13, 2025 at 07:20 PM
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
-- Table structure for table `general_ledger_tbl`
--

CREATE TABLE `general_ledger_tbl` (
  `info_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `bookkeeper_id` int(11) NOT NULL,
  `transaction_type` int(11) NOT NULL,
  `statement_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_book_keeper_tbl`
--

CREATE TABLE `gl_book_keeper_tbl` (
  `bookkeeper_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `age` int(3) NOT NULL,
  `bday` date NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_chart_of_account_tbl`
--

CREATE TABLE `gl_chart_of_account_tbl` (
  `account_id` int(11) NOT NULL,
  `accountName` varchar(50) NOT NULL,
  `accountLoan` decimal(10,2) NOT NULL,
  `accountInterest` decimal(10,2) NOT NULL,
  `accountIncome` decimal(10,2) NOT NULL,
  `account_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_report_tbl`
--

CREATE TABLE `gl_report_tbl` (
  `report_id` int(11) NOT NULL,
  `report_type` varchar(20) NOT NULL,
  `report_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_statement_tbl`
--

CREATE TABLE `gl_statement_tbl` (
  `statement_id` int(11) NOT NULL,
  `gain` decimal(10,2) NOT NULL,
  `expenses` decimal(10,2) NOT NULL,
  `revenue` decimal(10,2) NOT NULL,
  `loss` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_transaction_tbl`
--

CREATE TABLE `gl_transaction_tbl` (
  `transaction_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `transaction_type` varchar(20) NOT NULL,
  `transactionItem` varchar(20) NOT NULL,
  `transactionDate` date NOT NULL,
  `transactionTime` time NOT NULL,
  `transactionAmount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `general_ledger_tbl`
--
ALTER TABLE `general_ledger_tbl`
  ADD PRIMARY KEY (`info_id`),
  ADD UNIQUE KEY `general_ledger_tbl_ibfk_2` (`bookkeeper_id`) USING BTREE,
  ADD KEY `general_ledger_tbl_ibfk_1` (`account_id`),
  ADD KEY `statement_id` (`statement_id`),
  ADD KEY `transaction_type` (`transaction_type`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `gl_book_keeper_tbl`
--
ALTER TABLE `gl_book_keeper_tbl`
  ADD PRIMARY KEY (`bookkeeper_id`);

--
-- Indexes for table `gl_chart_of_account_tbl`
--
ALTER TABLE `gl_chart_of_account_tbl`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `gl_report_tbl`
--
ALTER TABLE `gl_report_tbl`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `gl_statement_tbl`
--
ALTER TABLE `gl_statement_tbl`
  ADD PRIMARY KEY (`statement_id`);

--
-- Indexes for table `gl_transaction_tbl`
--
ALTER TABLE `gl_transaction_tbl`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `borrower_id` (`borrower_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `general_ledger_tbl`
--
ALTER TABLE `general_ledger_tbl`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gl_book_keeper_tbl`
--
ALTER TABLE `gl_book_keeper_tbl`
  MODIFY `bookkeeper_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gl_chart_of_account_tbl`
--
ALTER TABLE `gl_chart_of_account_tbl`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gl_report_tbl`
--
ALTER TABLE `gl_report_tbl`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gl_statement_tbl`
--
ALTER TABLE `gl_statement_tbl`
  MODIFY `statement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gl_transaction_tbl`
--
ALTER TABLE `gl_transaction_tbl`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `general_ledger_tbl`
--
ALTER TABLE `general_ledger_tbl`
  ADD CONSTRAINT `general_ledger_tbl_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `gl_chart_of_account_tbl` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `general_ledger_tbl_ibfk_2` FOREIGN KEY (`bookkeeper_id`) REFERENCES `gl_book_keeper_tbl` (`bookkeeper_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `general_ledger_tbl_ibfk_4` FOREIGN KEY (`statement_id`) REFERENCES `gl_statement_tbl` (`statement_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `general_ledger_tbl_ibfk_5` FOREIGN KEY (`transaction_type`) REFERENCES `gl_transaction_tbl` (`transaction_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `general_ledger_tbl_ibfk_6` FOREIGN KEY (`report_id`) REFERENCES `gl_report_tbl` (`report_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gl_transaction_tbl`
--
ALTER TABLE `gl_transaction_tbl`
  ADD CONSTRAINT `gl_transaction_tbl_ibfk_1` FOREIGN KEY (`borrower_id`) REFERENCES `borrower_tbl` (`borrower_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
