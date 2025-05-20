-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for lown_db
CREATE DATABASE IF NOT EXISTS `lown_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `lown_db`;

-- Dumping structure for table lown_db.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk users 1` (`role_id`),
  KEY `fk users 2` (`client_id`),
  CONSTRAINT `fk users 1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `fk users 2` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.client_documents
CREATE TABLE IF NOT EXISTS `client_documents` (
  `client_docu_id` int NOT NULL AUTO_INCREMENT,
  `docu_type` enum('Driver''s License','Passport','SSS','UMID','PhilID') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `document_one` blob,
  `document_two` blob,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_docu_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_documents_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.client_employment
CREATE TABLE IF NOT EXISTS `client_employment` (
  `client_emp_id` int NOT NULL AUTO_INCREMENT,
  `employer_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `address` text,
  `position` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_emp_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_employment_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.client_financial_info
CREATE TABLE IF NOT EXISTS `client_financial_info` (
  `client_fin_id` int NOT NULL AUTO_INCREMENT,
  `source_of_funds` enum('Employment','Savings','Allowance','Business','Pension') DEFAULT NULL,
  `monthly_income` int DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_fin_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_financial_info_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.client_info
CREATE TABLE IF NOT EXISTS `client_info` (
  `client_id` varchar(50) NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `middle_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sex` enum('Male','Female') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `civil_status` enum('Single','Married','Seperated','Widowed') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `contact_number` int DEFAULT NULL,
  `address` text,
  `barangay` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.client_loan_limit
CREATE TABLE IF NOT EXISTS `client_loan_limit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ll_amount` int DEFAULT NULL,
  `ll_month` int DEFAULT NULL,
  `ll_interest` int DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__client_info` (`client_id`),
  CONSTRAINT `FK__client_info` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.client_references
CREATE TABLE IF NOT EXISTS `client_references` (
  `client_ref_id` int NOT NULL AUTO_INCREMENT,
  `fr_first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fr_last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fr_relationship` enum('Mother','Father','Siblings','Friends','Colleague','Relatives') DEFAULT NULL,
  `fr_contact_number` int DEFAULT NULL,
  `sr_first_name` varchar(50) DEFAULT NULL,
  `sr_last_name` varchar(50) DEFAULT NULL,
  `sr_relationship` enum('Mother','Father','Siblings','Friends','Colleague','Relatives') DEFAULT NULL,
  `sr_contact_number` int DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_ref_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_references_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.client_status
CREATE TABLE IF NOT EXISTS `client_status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `c_status` varchar(50) DEFAULT NULL,
  `l_status` varchar(50) DEFAULT NULL,
  `r_status` varchar(50) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_client_status_client_info` (`client_id`),
  CONSTRAINT `FK_client_status_client_info` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.loan_info
CREATE TABLE IF NOT EXISTS `loan_info` (
  `loan_id` int NOT NULL AUTO_INCREMENT,
  `amount` int DEFAULT NULL,
  `month` int DEFAULT NULL,
  `terms` int DEFAULT NULL,
  `purpose` enum('Tuition','Bills','Emergency','Online Shopping') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `interest` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`loan_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `loan_info_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.loan_restrc
CREATE TABLE IF NOT EXISTS `loan_restrc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `r_amount` int DEFAULT NULL,
  `r_month` int DEFAULT NULL,
  `r_interest` int DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_loan_restrc_client_info` (`client_id`),
  CONSTRAINT `FK_loan_restrc_client_info` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table lown_db.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
