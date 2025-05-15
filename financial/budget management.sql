-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 13, 2025 at 07:18 PM
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
-- Table structure for table `bm_budget_adjustment_tbl`
--

CREATE TABLE `bm_budget_adjustment_tbl` (
  `adjustment_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `adjustmentAmount` decimal(10,2) NOT NULL,
  `adjustmentDate` date NOT NULL,
  `reasonCode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bm_budget_monitoring_tbl`
--

CREATE TABLE `bm_budget_monitoring_tbl` (
  `monitor_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `actualSpend` decimal(10,2) NOT NULL,
  `monitoringDate` date NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bm_budget_planning_tbl`
--

CREATE TABLE `bm_budget_planning_tbl` (
  `plan_id` int(11) NOT NULL,
  `planName` varchar(50) NOT NULL,
  `allocatedBudget` decimal(10,2) NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bm_report_tbl`
--

CREATE TABLE `bm_report_tbl` (
  `report_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `reportDate` date NOT NULL,
  `report_type` varchar(20) NOT NULL,
  `analytics` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bm_budget_adjustment_tbl`
--
ALTER TABLE `bm_budget_adjustment_tbl`
  ADD PRIMARY KEY (`adjustment_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `bm_budget_monitoring_tbl`
--
ALTER TABLE `bm_budget_monitoring_tbl`
  ADD PRIMARY KEY (`monitor_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `bm_budget_planning_tbl`
--
ALTER TABLE `bm_budget_planning_tbl`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `bm_report_tbl`
--
ALTER TABLE `bm_report_tbl`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bm_budget_adjustment_tbl`
--
ALTER TABLE `bm_budget_adjustment_tbl`
  MODIFY `adjustment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bm_budget_monitoring_tbl`
--
ALTER TABLE `bm_budget_monitoring_tbl`
  MODIFY `monitor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bm_budget_planning_tbl`
--
ALTER TABLE `bm_budget_planning_tbl`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bm_report_tbl`
--
ALTER TABLE `bm_report_tbl`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bm_budget_adjustment_tbl`
--
ALTER TABLE `bm_budget_adjustment_tbl`
 ADD CONSTRAINT `bm_budget_adjustment_tbl_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `bm_budget_planning_tbl` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bm_budget_monitoring_tbl`
--
ALTER TABLE `bm_budget_monitoring_tbl`
  ADD CONSTRAINT `bm_budget_monitoring_tbl_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `bm_budget_planning_tbl` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bm_report_tbl`
--
ALTER TABLE `bm_report_tbl`
  ADD CONSTRAINT `bm_report_tbl_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `bm_budget_planning_tbl` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
