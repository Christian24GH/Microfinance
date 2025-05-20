-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 08:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee_performance`
--

-- --------------------------------------------------------

--
-- Table structure for table `performance`
--

CREATE TABLE `performance` (
  `id` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `performance_score` int(11) DEFAULT NULL CHECK (`performance_score` between 0 and 100),
  `review_date` date NOT NULL,
  `quality_of_work` varchar(50) NOT NULL,
  `attendance` varchar(50) NOT NULL,
  `reliability` varchar(50) NOT NULL,
  `decision_making` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance`
--

INSERT INTO `performance` (`id`, `employee_name`, `job_title`, `performance_score`, `review_date`, `quality_of_work`, `attendance`, `reliability`, `decision_making`, `created_at`) VALUES
(2, 'Gon', 'Loan Officer', 100, '2025-05-20', 'Meets Expectation', 'Meets Expectation', 'Meets Expectation', 'Meets Expectation', '2025-05-19 19:03:40'),
(3, 'Killua', 'Credit Analyst', 100, '2025-05-20', 'Exceeds Expectation', 'Exceeds Expectation', 'Exceeds Expectation', 'Exceeds Expectation', '2025-05-20 03:33:40'),
(4, 'Gon', 'Loan Officer', 100, '2025-05-20', 'Exceeds Expectation', 'Exceeds Expectation', 'Exceeds Expectation', 'Exceeds Expectation', '2025-05-20 04:23:33'),
(6, 'Gon', 'Loan Officer', 100, '2025-05-20', 'Exceeds Expectation', 'Exceeds Expectation', 'Exceeds Expectation', 'Exceeds Expectation', '2025-05-20 05:47:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `performance`
--
ALTER TABLE `performance`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `performance`
--
ALTER TABLE `performance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
