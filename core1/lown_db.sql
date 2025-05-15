/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `client_documents`;
CREATE TABLE `client_documents` (
  `client_docu_id` int NOT NULL AUTO_INCREMENT,
  `docu_type` enum('Driver''s License','Passport','SSS','UMID','PhilID') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `document_one` blob,
  `document_two` blob,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_docu_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_documents_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `client_employment`;
CREATE TABLE `client_employment` (
  `client_emp_id` int NOT NULL AUTO_INCREMENT,
  `employer_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text,
  `position` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_emp_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_employment_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `client_employment` (`client_emp_id`,`employer_name`,`address`,`position`,`client_id`) VALUES 
(1,'eme','Eme Eme ','eme','c25000276');

DROP TABLE IF EXISTS `client_financial_info`;
CREATE TABLE `client_financial_info` (
  `client_fin_id` int NOT NULL AUTO_INCREMENT,
  `source_of_funds` enum('Employment','Savings','Allowance','Business','Pension') DEFAULT NULL,
  `monthly_income` int DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_fin_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_financial_info_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `client_financial_info` (`client_fin_id`,`source_of_funds`,`monthly_income`,`client_id`) VALUES 
(1,'Savings',10000,'c25000276');

DROP TABLE IF EXISTS `client_info`;
CREATE TABLE `client_info` (
  `client_id` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `middle_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sex` enum('Male','Female') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `civil_status` enum('Single','Married','Seperated','Widowed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `contact_number` int DEFAULT NULL,
  `address` text,
  `barangay` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `client_info` (`client_id`,`first_name`,`middle_name`,`last_name`,`sex`,`civil_status`,`birthdate`,`contact_number`,`address`,`barangay`,`city`,`province`) VALUES 
('c25000276','John Kenneth','Padua','Gado','Male','Single','2002-09-16',0,'Eme Eme ','Gaya Gaya','San Jose Del Monte','Bulacan');

DROP TABLE IF EXISTS `client_references`;
CREATE TABLE `client_references` (
  `client_ref_id` int NOT NULL AUTO_INCREMENT,
  `fr_first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fr_last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `client_references` (`client_ref_id`,`fr_first_name`,`fr_last_name`,`fr_relationship`,`fr_contact_number`,`sr_first_name`,`sr_last_name`,`sr_relationship`,`sr_contact_number`,`client_id`) VALUES 
(1,'em','eme','Father',0,'eme','eme','Friends',0,'c25000276');

DROP TABLE IF EXISTS `loan_info`;
CREATE TABLE `loan_info` (
  `loan_id` int NOT NULL AUTO_INCREMENT,
  `amount` int DEFAULT NULL,
  `month` int DEFAULT NULL,
  `terms` int DEFAULT NULL,
  `purpose` text,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`loan_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `loan_info_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `loan_info` (`loan_id`,`amount`,`month`,`terms`,`purpose`,`client_id`) VALUES 
(1,5500,3,6,'Bills','c25000276');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` (`id`,`role_name`) VALUES 
(1,'Admin'),
(2,'Client'),
(3,'Loan Officer');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_1` (`role_id`),
  KEY `fk_users_2` (`client_id`),
  CONSTRAINT `fk_users_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `fk_users_2` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`,`email`,`password`,`role_id`,`client_id`) VALUES 
(1,'admin@admin.com','admin',1,NULL),
(2,'loan@officer.com','loanofficer',3,NULL),
(3,'johnkenneth413@gmail.com','hatdog',2,'c25000276');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
