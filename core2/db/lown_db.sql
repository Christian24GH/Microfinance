-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 12:48 AM
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
-- Table structure for table `client_document`
--

CREATE TABLE `client_document` (
  `client_doc_id` int(11) NOT NULL,
  `doc_name` varchar(255) DEFAULT NULL,
  `doc_type` varchar(255) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_document`
--

INSERT INTO `client_document` (`client_doc_id`, `doc_name`, `doc_type`, `client_id`) VALUES
(1, 'Valid ID', 'Government ID', 1),
(2, 'Payslip', 'Proof of Income', 2),
(3, 'Bank Statement', 'Financial Doc', 3),
(4, 'Company ID', 'Employment ID', 4),
(5, 'Utility Bill', 'Proof of Address', 5);

-- --------------------------------------------------------

--
-- Table structure for table `client_employment`
--

CREATE TABLE `client_employment` (
  `client_emp_id` int(11) NOT NULL,
  `employer_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_employment`
--

INSERT INTO `client_employment` (`client_emp_id`, `employer_name`, `address`, `position`, `client_id`) VALUES
(1, 'TechCorp', 'Makati City', 'Software Engineer', 1),
(2, 'FinBank', 'Quezon City', 'Accountant', 2),
(3, 'MediLife', 'Pasig City', 'Nurse', 3),
(4, 'EduNation', 'Taguig City', 'Teacher', 4),
(5, 'BuildCo', 'Cebu City', 'Architect', 5);

-- --------------------------------------------------------

--
-- Table structure for table `client_financial_info`
--

CREATE TABLE `client_financial_info` (
  `client_fin_info` int(11) NOT NULL,
  `source_of_funds` varchar(255) DEFAULT NULL,
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_financial_info`
--

INSERT INTO `client_financial_info` (`client_fin_info`, `source_of_funds`, `monthly_income`, `client_id`) VALUES
(1, 'Employment', 50000.00, 1),
(2, 'Business', 75000.00, 2),
(3, 'Remittance', 30000.00, 3),
(4, 'Employment', 45000.00, 4),
(5, 'Freelance', 60000.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `client_info`
--

CREATE TABLE `client_info` (
  `client_id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `loan_stat` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_info`
--

INSERT INTO `client_info` (`client_id`, `firstname`, `middlename`, `lastname`, `contact`, `address`, `email`, `birthdate`, `password`, `status`, `loan_stat`) VALUES
(1, 'John', 'A.', 'Doe', '09171234567', '123 Elm St', 'abello.hannaagatha.bernales@gmail.com', '1990-01-01', 'pass123', 'Active', 'Active'),
(2, 'Jane', 'B.', 'Smith', '09181234567', '456 Oak St', 'cajetapatrick1@gmail.com', '1992-02-02', 'pass456', 'Inactive', 'Flagged'),
(3, 'Carlos', 'C.', 'Garcia', '09191234567', '789 Pine St', 'anaselportes22@gmail.com', '1988-03-03', 'pass789', 'Inactive', 'Active'),
(4, 'Maria', 'D.', 'Reyes', '09201234567', '101 Maple St', 'espedillaannamariedeo@gmail.com', '1995-04-04', 'pass321', 'Inactive', 'Flagged'),
(5, 'Anna', 'E.', 'Lopez', '09211234567', '202 Birch St', 'lawrencebarraza011404@gmail.com', '1993-05-05', 'pass654', 'ACTIVE', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `client_references`
--

CREATE TABLE `client_references` (
  `client_ref_id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `relationship` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `client_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_references`
--

INSERT INTO `client_references` (`client_ref_id`, `firstname`, `lastname`, `relationship`, `contact`, `email`, `client_id`) VALUES
(1, 'Hanna Agatha', 'Abello-Bernales', 'Friend', '0939-345-6789', 'abello.hannaagatha.bernales@gmail.com', 1),
(2, 'Anasel', 'Portes', 'Family', '0915-234-5678', 'anaselportes22@gmail.com', 3),
(3, 'Patrick', 'Cajeta', 'Colleague', '0928-123-4567', 'cajetapatrick1@gmail.com', 2),
(4, 'Lawrence', 'Barraza', 'Friend', '0917-011-404', 'espedillaannamariedeo@gmail.com', 4);

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

--
-- Dumping data for table `disbursement_tbl`
--

INSERT INTO `disbursement_tbl` (`disbursement_id`, `loan_id`, `amount`, `disbursementDate`) VALUES
(1, 101, 5000.00, '2025-01-15'),
(2, 102, 7500.50, '2025-02-10'),
(3, 103, 6200.75, '2025-03-05'),
(4, 104, 8100.00, '2025-03-25'),
(5, 105, 4550.25, '2025-04-12'),
(6, 106, 9000.00, '2025-04-27'),
(7, 107, 3300.00, '2025-05-01'),
(8, 108, 12000.00, '2025-05-10'),
(9, 109, 6800.50, '2025-05-12'),
(10, 110, 10000.00, '2025-05-15');

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

--
-- Dumping data for table `disb_repayment_tbl`
--

INSERT INTO `disb_repayment_tbl` (`repayment_id`, `loan_id`, `amountPaid`, `repaymentDate`) VALUES
(1, 5, 300.00, '2025-01-20'),
(2, 5, 400.00, '2025-02-25'),
(3, 5, 350.00, '2025-02-28'),
(4, 5, 500.00, '2025-03-10'),
(5, 5, 450.00, '2025-03-28'),
(6, 5, 600.00, '2025-04-15'),
(7, 5, 550.00, '2025-04-30'),
(8, 5, 700.00, '2025-05-05');

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `log_id` int(11) NOT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `send_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('SENT','FAILED') DEFAULT 'SENT'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_logs`
--

INSERT INTO `email_logs` (`log_id`, `recipient_email`, `subject`, `message`, `send_date`, `status`) VALUES
(1, 'cajetapatrick1@gmail.com', 'Loan Application', 'Dear Client,\r\n\r\nThank you for your interest in our loan program. We are processing your application and will contact you shortly.\r\n\r\nRegards,\r\nSupport Team', '2025-05-10 05:04:19', 'SENT'),
(2, 'abello.hannaagatha.bernales@gmail.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-10 05:24:46', 'SENT'),
(3, 'cajetapatrick1@gmail.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-10 05:24:49', 'SENT'),
(4, 'anaselportes22@gmail.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-10 05:24:52', 'SENT'),
(5, 'lawrencebarraza011404@gmail.com\r\n', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-10 05:24:57', 'SENT'),
(6, 'anna.lopez@example.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-10 05:25:01', 'SENT'),
(7, 'lawrencebarraza011404@gmail.com\r\n', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-10 05:28:29', 'SENT'),
(8, 'lawrencebarraza011404@gmail.com\r\n', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-10 05:29:18', 'SENT'),
(9, 'abello.hannaagatha.bernales@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-10 05:29:50', 'SENT'),
(10, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Hanna Agatha Abello-Bernales,\r\n\r\nWe want to inform you about an upcoming system update for [Client Name]\'s account. Please be aware that there will be a brief downtime while we complete maintenance. We apologize for any inconvenience and appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:00:30', 'SENT'),
(11, 'cajetapatrick1@gmail.com', 'Repayment Reminder', 'Dear Hanna Agatha Abello-Bernales,\r\n\r\nThis is a reminder that the person you are acting as a reference for, [Client Name], has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:00:40', 'SENT'),
(12, 'abello.hannaagatha.bernales@gmail.com', 'Repayment Reminder', 'Dear Hanna Agatha Abello-Bernales,\r\n\r\nThis is a reminder that the person you are acting as a reference for, [Client Name], has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:14:47', 'SENT'),
(13, 'cajetapatrick1@gmail.com', 'Repayment Reminder', 'Dear Hanna Agatha Abello-Bernales,\r\n\r\nThis is a reminder that the person you are acting as a reference for, [Client Name], has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:14:50', 'SENT'),
(14, 'anaselportes22@gmail.com', 'Repayment Reminder', 'Dear Hanna Agatha Abello-Bernales,\r\n\r\nThis is a reminder that the person you are acting as a reference for, [Client Name], has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:14:53', 'SENT'),
(15, 'lawrencebarraza011404@gmail.com\r\n', 'Repayment Reminder', 'Dear Hanna Agatha Abello-Bernales,\r\n\r\nThis is a reminder that the person you are acting as a reference for, [Client Name], has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:14:56', 'SENT'),
(16, 'lawrencebarraza011404@gmail.com\r\n', 'System Update', 'Dear Hanna Agatha Abello-Bernales,\r\n\r\nWe want to inform you about an upcoming system update for [Client Name]\'s account. There will be a brief downtime during maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:30:57', 'SENT'),
(17, 'abello.hannaagatha.bernales@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:00', 'SENT'),
(18, 'abello.hannaagatha.bernales@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:02', 'SENT'),
(19, 'abello.hannaagatha.bernales@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:02', 'SENT'),
(20, 'cajetapatrick1@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:03', 'SENT'),
(21, 'cajetapatrick1@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:05', 'SENT'),
(22, 'cajetapatrick1@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:06', 'SENT'),
(23, 'anaselportes22@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:06', 'SENT'),
(24, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:07', 'SENT'),
(25, 'anaselportes22@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:09', 'SENT'),
(26, 'anaselportes22@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:09', 'SENT'),
(27, 'lawrencebarraza011404@gmail.com\r\n', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:10', 'SENT'),
(28, 'cajetapatrick1@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:10', 'SENT'),
(29, 'lawrencebarraza011404@gmail.com\r\n', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:12', 'SENT'),
(30, 'lawrencebarraza011404@gmail.com\r\n', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:13', 'SENT'),
(31, 'anaselportes22@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:14', 'SENT'),
(32, 'lawrencebarraza011404@gmail.com\r\n', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:17', 'SENT'),
(33, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:34:58', 'SENT'),
(34, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:00', 'SENT'),
(35, 'cajetapatrick1@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:02', 'SENT'),
(36, 'cajetapatrick1@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:04', 'SENT'),
(37, 'anaselportes22@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:06', 'SENT'),
(38, 'anaselportes22@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:07', 'SENT'),
(39, 'lawrencebarraza011404@gmail.com\r\n', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:09', 'SENT'),
(40, 'lawrencebarraza011404@gmail.com\r\n', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:10', 'SENT'),
(41, 'abello.hannaagatha.bernales@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:46', 'SENT'),
(42, 'cajetapatrick1@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:50', 'SENT'),
(43, 'anaselportes22@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:53', 'SENT'),
(44, 'lawrencebarraza011404@gmail.com\r\n', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder that the person you are acting as a reference for has an outstanding repayment. Please verify their commitment to ensure they follow through with their repayment plan.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:35:56', 'SENT'),
(45, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:39:21', 'SENT'),
(46, 'cajetapatrick1@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:39:25', 'SENT'),
(47, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Client,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:39:27', 'SENT'),
(48, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Client,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:39:27', 'SENT'),
(49, 'anaselportes22@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:39:28', 'SENT'),
(50, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Client,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:39:28', 'SENT'),
(51, 'lawrencebarraza011404@gmail.com\r\n', 'System Update', 'Dear Clients,\r\n\r\nWe want to inform you about an upcoming system update for the accounts you are associated with. There will be a brief downtime while we complete maintenance. We appreciate your understanding.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:39:31', 'SENT'),
(52, 'abello.hannaagatha.bernales@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder regarding outstanding repayments. Kindly ensure your commitment is fulfilled on time.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:43:04', 'SENT'),
(53, 'cajetapatrick1@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder regarding outstanding repayments. Kindly ensure your commitment is fulfilled on time.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:43:07', 'SENT'),
(54, 'anaselportes22@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder regarding outstanding repayments. Kindly ensure your commitment is fulfilled on time.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:43:10', 'SENT'),
(55, 'lawrencebarraza011404@gmail.com\r\n', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder regarding outstanding repayments. Kindly ensure your commitment is fulfilled on time.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:43:13', 'SENT'),
(56, 'abello.hannaagatha.bernales@gmail.com', 'Repayment Reminder', 'Dear abello.hannaagatha.bernales,\r\n\r\nThis is a reminder regarding outstanding repayments. Kindly ensure your commitment is fulfilled on time.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:43:46', 'SENT'),
(57, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:44:10', 'SENT'),
(58, 'cajetapatrick1@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:44:14', 'SENT'),
(59, 'anaselportes22@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:44:17', 'SENT'),
(60, 'lawrencebarraza011404@gmail.com\r\n', 'System Update', 'Dear Clients,\r\n\r\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 07:44:21', 'SENT'),
(61, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear abello.hannaagatha.bernales,\r\n\r\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 08:56:57', 'SENT'),
(62, 'abello.hannaagatha.bernales@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 08:57:05', 'SENT'),
(63, 'cajetapatrick1@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 08:57:09', 'SENT'),
(64, 'anaselportes22@gmail.com', 'System Update', 'Dear Clients,\r\n\r\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 08:57:13', 'SENT'),
(65, 'lawrencebarraza011404@gmail.com\r\n', 'System Update', 'Dear Clients,\r\n\r\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\r\n\r\nBest regards,\r\n[Your Company Name]', '2025-05-10 08:57:16', 'SENT'),
(66, 'abello.hannaagatha.bernales@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder regarding the loan repayment. Please note that you have been listed as a reference contact in case we are unable to reach the borrower. Kindly remind them to fulfill their repayment obligations on time.\r\n\r\nBest regards,\r\n[Tru Lend Communications]', '2025-05-11 13:48:59', 'SENT'),
(67, 'cajetapatrick1@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder regarding the loan repayment. Please note that you have been listed as a reference contact in case we are unable to reach the borrower. Kindly remind them to fulfill their repayment obligations on time.\r\n\r\nBest regards,\r\n[Tru Lend Communications]', '2025-05-11 13:49:05', 'SENT'),
(68, 'anaselportes22@gmail.com', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder regarding the loan repayment. Please note that you have been listed as a reference contact in case we are unable to reach the borrower. Kindly remind them to fulfill their repayment obligations on time.\r\n\r\nBest regards,\r\n[Tru Lend Communications]', '2025-05-11 13:49:10', 'SENT'),
(69, 'lawrencebarraza011404@gmail.com\r\n', 'Repayment Reminder', 'Dear Clients,\r\n\r\nThis is a reminder regarding the loan repayment. Please note that you have been listed as a reference contact in case we are unable to reach the borrower. Kindly remind them to fulfill their repayment obligations on time.\r\n\r\nBest regards,\r\n[Tru Lend Communications]', '2025-05-11 13:49:15', 'SENT'),
(70, 'abello.hannaagatha.bernales@gmail.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-11 13:56:25', 'SENT'),
(71, 'cajetapatrick1@gmail.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-11 13:56:29', 'SENT'),
(72, 'anaselportes22@gmail.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-11 13:56:32', 'SENT'),
(73, 'espedillaannamariedeo@gmail.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-11 13:56:35', 'SENT'),
(74, 'anna.lopez@example.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-11 13:56:39', 'SENT'),
(75, 'abello.hannaagatha.bernales@gmail.com', 'Loan Application', 'THIS IS A TEST MESSAGE ', '2025-05-11 14:41:41', 'SENT'),
(76, 'cajetapatrick1@gmail.com', 'Loan Application', 'THIS IS A TEST MESSAGE ', '2025-05-11 14:41:44', 'SENT'),
(77, 'anaselportes22@gmail.com', 'Loan Application', 'THIS IS A TEST MESSAGE ', '2025-05-11 14:41:47', 'SENT'),
(78, 'espedillaannamariedeo@gmail.com', 'Loan Application', 'THIS IS A TEST MESSAGE ', '2025-05-11 14:41:51', 'SENT'),
(79, 'anna.lopez@example.com', 'Loan Application', 'THIS IS A TEST MESSAGE ', '2025-05-11 14:41:54', 'SENT'),
(80, 'abello.hannaagatha.bernales@gmail.com', 'Loan Application', 'Dear Client,\r\n\r\nThank you for your interest in our loan program. We are processing your application and will contact you shortly.\r\n\r\nRegards,\r\nSupport Team', '2025-05-17 07:33:53', 'SENT'),
(81, 'abello.hannaagatha.bernales@gmail.com', 'Account Flag', 'hi bububububurat', '2025-05-17 07:36:47', 'SENT'),
(82, 'cajetapatrick1@gmail.com', 'Account Flag', 'hi bububububurat', '2025-05-17 07:36:50', 'SENT'),
(83, 'anaselportes22@gmail.com', 'Account Flag', 'hi bububububurat', '2025-05-17 07:36:54', 'SENT'),
(84, 'espedillaannamariedeo@gmail.com', 'Account Flag', 'hi bububububurat', '2025-05-17 07:36:57', 'SENT'),
(85, 'anna.lopez@example.com', 'Account Flag', 'hi bububububurat', '2025-05-17 07:37:01', 'SENT'),
(86, 'abello.hannaagatha.bernales@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 07:38:24', 'SENT'),
(87, 'cajetapatrick1@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 07:38:27', 'SENT'),
(88, 'anaselportes22@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 07:38:31', 'SENT'),
(89, 'espedillaannamariedeo@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 07:38:34', 'SENT'),
(90, 'anna.lopez@example.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 07:38:38', 'SENT'),
(91, 'lawrencebarraza011404@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 07:42:10', 'SENT'),
(92, 'abello.hannaagatha.bernales@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 08:04:34', 'SENT'),
(93, 'cajetapatrick1@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 08:04:37', 'SENT'),
(94, 'anaselportes22@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 08:04:40', 'SENT'),
(95, 'espedillaannamariedeo@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 08:04:43', 'SENT'),
(96, 'lawrencebarraza011404@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 08:04:46', 'SENT'),
(97, 'abello.hannaagatha.bernales@gmail.com', 'Loan Application', 'Dear Client,\r\n\r\nThank you for your interest in our loan program. We are processing your application and will contact you shortly.\r\n\r\nRegards,\r\nSupport Team', '2025-05-17 08:12:44', 'SENT'),
(98, 'cajetapatrick1@gmail.com', 'Loan Application', 'Dear Client,\r\n\r\nThank you for your interest in our loan program. We are processing your application and will contact you shortly.\r\n\r\nRegards,\r\nSupport Team', '2025-05-17 08:12:47', 'SENT'),
(99, 'anaselportes22@gmail.com', 'Loan Application', 'Dear Client,\r\n\r\nThank you for your interest in our loan program. We are processing your application and will contact you shortly.\r\n\r\nRegards,\r\nSupport Team', '2025-05-17 08:12:51', 'SENT'),
(100, 'espedillaannamariedeo@gmail.com', 'Loan Application', 'Dear Client,\r\n\r\nThank you for your interest in our loan program. We are processing your application and will contact you shortly.\r\n\r\nRegards,\r\nSupport Team', '2025-05-17 08:12:54', 'SENT'),
(101, 'lawrencebarraza011404@gmail.com', 'Loan Application', 'Dear Client,\r\n\r\nThank you for your interest in our loan program. We are processing your application and will contact you shortly.\r\n\r\nRegards,\r\nSupport Team', '2025-05-17 08:12:58', 'SENT'),
(102, 'abello.hannaagatha.bernales@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 12:47:17', 'SENT'),
(103, 'cajetapatrick1@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 12:47:20', 'SENT'),
(104, 'anaselportes22@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 12:47:23', 'SENT'),
(105, 'espedillaannamariedeo@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 12:47:27', 'SENT'),
(106, 'lawrencebarraza011404@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 12:47:30', 'SENT'),
(107, 'abello.hannaagatha.bernales@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 13:20:12', 'SENT'),
(108, 'abello.hannaagatha.bernales@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 13:20:23', 'SENT'),
(109, 'cajetapatrick1@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 13:20:26', 'SENT'),
(110, 'anaselportes22@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 13:20:29', 'SENT'),
(111, 'espedillaannamariedeo@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 13:20:33', 'SENT'),
(112, 'lawrencebarraza011404@gmail.com', 'Compliance Update', 'Dear Client,\r\n\r\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\r\n\r\nSincerely,\r\nCompliance Team', '2025-05-17 13:20:37', 'SENT'),
(113, 'abello.hannaagatha.bernales@gmail.com', 'Scheduled Payment Reminder', 'Dear Client,\r\n\r\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\r\n\r\nRegards,\r\nBilling Department', '2025-05-17 13:48:48', 'SENT'),
(114, 'abello.hannaagatha.bernales@gmail.com', 'Loan Application', 'gagooo hahhaha ', '2025-05-17 14:02:56', 'SENT'),
(115, 'anaselportes22@gmail.com', 'Loan Application', 'gudmurneng\r\n', '2025-05-17 14:03:22', 'SENT'),
(116, 'abello.hannaagatha.bernales@gmail.com', 'Account Flag', 'Dear Client,\r\n\r\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\r\n\r\nThank you.', '2025-05-17 14:07:13', 'SENT');

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `loan_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `loan_type` varchar(45) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `interest_rate` int(11) DEFAULT NULL,
  `term` int(11) DEFAULT NULL,
  `original_amount` int(11) DEFAULT NULL,
  `current_balance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`loan_id`, `client_id`, `loan_type`, `start_date`, `interest_rate`, `term`, `original_amount`, `current_balance`) VALUES
(1, 1, 'Business', '2024-01-10', 5, 12, 100000, 85000),
(2, 2, 'Personal', '2024-02-15', 6, 24, 50000, 42000),
(3, 3, 'Emergency', '2023-11-01', 4, 6, 30000, 15000),
(4, 4, 'Education', '2024-03-20', 3, 18, 60000, 58000),
(5, 5, 'Personal', '2024-04-10', 7, 12, 120000, 115000);

-- --------------------------------------------------------

--
-- Table structure for table `loan_info`
--

CREATE TABLE `loan_info` (
  `loan_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `terms` int(11) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `loan_state` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_info`
--

INSERT INTO `loan_info` (`loan_id`, `amount`, `month`, `terms`, `purpose`, `client_id`, `loan_state`) VALUES
(1, 100000.00, 12, 12, 'Home renovation', 1, 'Paid'),
(2, 50000.00, 6, 6, 'Tuition fee', 2, 'Overdue'),
(3, 150000.00, 24, 24, 'Business expansion', 3, 'Paid'),
(4, 75000.00, 10, 10, 'Medical expenses', 4, 'Delayed'),
(5, 200000.00, 36, 36, 'Car purchase', 5, 'Paid'),
(6, 120000.00, 18, 18, 'Business capital', 1, 'paid'),
(7, 50000.00, 6, 6, 'Medical expenses', 1, 'paid'),
(8, 75000.00, 12, 12, 'Tuition fee', 1, 'paid'),
(9, 90000.00, 24, 24, 'Home renovation', 1, 'paid'),
(10, 45000.00, 9, 9, 'Medical expenses', 2, 'paid'),
(11, 60000.00, 12, 12, 'Car repair', 2, 'paid'),
(12, 55000.00, 8, 8, 'Business supplies', 2, 'paid'),
(13, 40000.00, 6, 6, 'Tuition fee', 2, 'paid'),
(14, 160000.00, 24, 24, 'Home renovation', 3, 'paid'),
(15, 130000.00, 20, 20, 'Business expansion', 3, 'paid'),
(16, 110000.00, 18, 18, 'Medical expenses', 3, 'paid'),
(17, 140000.00, 24, 24, 'Vehicle purchase', 3, 'paid'),
(18, 85000.00, 12, 12, 'Tuition fee', 4, 'paid'),
(19, 90000.00, 15, 15, 'Medical expenses', 4, 'paid'),
(20, 70000.00, 10, 10, 'Car upgrade', 4, 'paid'),
(21, 95000.00, 18, 18, 'Business capital', 4, 'paid'),
(22, 175000.00, 30, 30, 'Car upgrade', 5, 'paid'),
(23, 150000.00, 24, 24, 'Home renovation', 5, 'paid'),
(24, 180000.00, 36, 36, 'Business expansion', 5, 'paid'),
(25, 160000.00, 30, 30, 'Medical expenses', 5, 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `savings_tracking`
--

CREATE TABLE `savings_tracking` (
  `id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_id` int(11) NOT NULL,
  `deposit_date` date NOT NULL,
  `amount_deposited` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `maturity_date` date NOT NULL,
  `interest_earned` decimal(15,2) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` enum('Active','Closed') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client_document`
--
ALTER TABLE `client_document`
  ADD PRIMARY KEY (`client_doc_id`),
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
  ADD PRIMARY KEY (`client_fin_info`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `client_info`
--
ALTER TABLE `client_info`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `client_references`
--
ALTER TABLE `client_references`
  ADD PRIMARY KEY (`client_ref_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `disbursement_tbl`
--
ALTER TABLE `disbursement_tbl`
  ADD PRIMARY KEY (`disbursement_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `disb_repayment_tbl`
--
ALTER TABLE `disb_repayment_tbl`
  ADD PRIMARY KEY (`repayment_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `fk_client_info` (`client_id`);

--
-- Indexes for table `loan_info`
--
ALTER TABLE `loan_info`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `savings_tracking`
--
ALTER TABLE `savings_tracking`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `disbursement_tbl`
--
ALTER TABLE `disbursement_tbl`
  MODIFY `disbursement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `disb_repayment_tbl`
--
ALTER TABLE `disb_repayment_tbl`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `savings_tracking`
--
ALTER TABLE `savings_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client_document`
--
ALTER TABLE `client_document`
  ADD CONSTRAINT `client_document_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

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
-- Constraints for table `client_references`
--
ALTER TABLE `client_references`
  ADD CONSTRAINT `client_references_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `fk_client_info` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loan_info`
--
ALTER TABLE `loan_info`
  ADD CONSTRAINT `loan_info_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
