-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 05:53 PM
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
-- Database: `lown_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--
/*
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `password`, `role_id`, `client_id`) VALUES
(1, 'admin@admin.com', 'admin', 1, NULL),
(2, 'loan@officer.com', 'loanofficer', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_documents`
--
*/
CREATE TABLE `client_documents` (
  `client_docu_id` int(11) NOT NULL,
  `docu_type` enum('Driver''s License','Passport','SSS','UMID','PhilID') DEFAULT NULL,
  `document_one` blob DEFAULT NULL,
  `document_two` blob DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_employment`
--

CREATE TABLE `client_employment` (
  `client_emp_id` int(11) NOT NULL,
  `employer_name` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_financial_info`
--

CREATE TABLE `client_financial_info` (
  `client_fin_id` int(11) NOT NULL,
  `source_of_funds` enum('Employment','Savings','Allowance','Business','Pension') DEFAULT NULL,
  `monthly_income` int(11) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_info`
--
/*
CREATE TABLE `client_info` (
  `client_id` varchar(50) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `civil_status` enum('Single','Married','Seperated','Widowed') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `contact_number` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `barangay` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
*/
-- --------------------------------------------------------

--
-- Table structure for table `client_loan_limit`
--

CREATE TABLE `client_loan_limit` (
  `id` int(11) NOT NULL,
  `ll_amount` int(11) DEFAULT NULL,
  `ll_month` int(11) DEFAULT NULL,
  `ll_interest` int(11) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_references`
--

CREATE TABLE `client_references` (
  `client_ref_id` int(11) NOT NULL,
  `fr_first_name` varchar(50) DEFAULT NULL,
  `fr_last_name` varchar(50) DEFAULT NULL,
  `fr_relationship` enum('Mother','Father','Siblings','Friends','Colleague','Relatives') DEFAULT NULL,
  `fr_contact_number` int(11) DEFAULT NULL,
  `sr_first_name` varchar(50) DEFAULT NULL,
  `sr_last_name` varchar(50) DEFAULT NULL,
  `sr_relationship` enum('Mother','Father','Siblings','Friends','Colleague','Relatives') DEFAULT NULL,
  `sr_contact_number` int(11) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_status`
--

CREATE TABLE `client_status` (
  `id` int(11) NOT NULL,
  `c_status` varchar(50) DEFAULT NULL,
  `l_status` varchar(50) DEFAULT NULL,
  `r_status` varchar(50) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_info`
--

CREATE TABLE `loan_info` (
  `loan_id` int(11) NOT NULL,
  `amount` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `terms` int(11) DEFAULT NULL,
  `purpose` enum('Tuition','Bills','Emergency','Online Shopping') DEFAULT NULL,
  `interest` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_restrc`
--

CREATE TABLE `loan_restrc` (
  `id` int(11) NOT NULL,
  `r_amount` int(11) DEFAULT NULL,
  `r_month` int(11) DEFAULT NULL,
  `r_interest` int(11) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--
/*
CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Client'),
(3, 'Loan Officer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk users 1` (`role_id`),
  ADD KEY `fk users 2` (`client_id`);
*/
--
-- Indexes for table `client_documents`
--
ALTER TABLE `client_documents`
  ADD PRIMARY KEY (`client_docu_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `client_employment`
--
ALTER TABLE `client_employment`
  ADD PRIMARY KEY (`client_emp_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `client_financial_info`
--
ALTER TABLE `client_financial_info`
  ADD PRIMARY KEY (`client_fin_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `client_info`
--
/*
ALTER TABLE `client_info`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `client_id` (`client_id`);
*/
--
-- Indexes for table `client_loan_limit`
--
ALTER TABLE `client_loan_limit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__client_info` (`client_id`);

--
-- Indexes for table `client_references`
--
ALTER TABLE `client_references`
  ADD PRIMARY KEY (`client_ref_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `client_status`
--
ALTER TABLE `client_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_client_status_client_info` (`client_id`);

--
-- Indexes for table `loan_info`
--
ALTER TABLE `loan_info`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `loan_restrc`
--
ALTER TABLE `loan_restrc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_loan_restrc_client_info` (`client_id`);

--
-- Indexes for table `roles`
--
/*
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);
*/
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
/*
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
*/
--
-- AUTO_INCREMENT for table `client_documents`
--
ALTER TABLE `client_documents`
  MODIFY `client_docu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_employment`
--
ALTER TABLE `client_employment`
  MODIFY `client_emp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_financial_info`
--
ALTER TABLE `client_financial_info`
  MODIFY `client_fin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_loan_limit`
--
ALTER TABLE `client_loan_limit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_references`
--
ALTER TABLE `client_references`
  MODIFY `client_ref_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_status`
--
ALTER TABLE `client_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_info`
--
ALTER TABLE `loan_info`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_restrc`
--
ALTER TABLE `loan_restrc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
/*
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
*/
--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
/*
ALTER TABLE `accounts`
  ADD CONSTRAINT `fk users 1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `fk users 2` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);
*/
--
-- Constraints for table `client_documents`
--

ALTER TABLE `client_documents`
  ADD CONSTRAINT `client_documents_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `client_employment`
--
ALTER TABLE `client_employment`
  ADD CONSTRAINT `client_employment_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `client_financial_info`
--
ALTER TABLE `client_financial_info`
  ADD CONSTRAINT `client_financial_info_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `client_loan_limit`
--
ALTER TABLE `client_loan_limit`
  ADD CONSTRAINT `FK__client_info` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `client_references`
--
ALTER TABLE `client_references`
  ADD CONSTRAINT `client_references_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `client_status`
--
ALTER TABLE `client_status`
  ADD CONSTRAINT `FK_client_status_client_info` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `loan_info`
--
ALTER TABLE `loan_info`
  ADD CONSTRAINT `loan_info_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `loan_restrc`
--
ALTER TABLE `loan_restrc`
  ADD CONSTRAINT `FK_loan_restrc_client_info` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
