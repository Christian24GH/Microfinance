DROP TABLE IF EXISTS `client_documents`;
CREATE TABLE `client_documents` (
  `client_docu_id` INT NOT NULL AUTO_INCREMENT,
  `docu_type` ENUM('Driver''s License','Passport','SSS','UMID','PhilID') DEFAULT NULL,
  `document_one` BLOB,
  `document_two` BLOB,
  `client_id` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`client_docu_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_documents_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
);

DROP TABLE IF EXISTS `client_employment`;
CREATE TABLE `client_employment` (
  `client_emp_id` INT NOT NULL AUTO_INCREMENT,
  `employer_name` VARCHAR(50) DEFAULT NULL,
  `address` TEXT,
  `position` VARCHAR(50) DEFAULT NULL,
  `client_id` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`client_emp_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_employment_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
);

INSERT INTO `client_employment` (`client_emp_id`,`employer_name`,`address`,`position`,`client_id`) VALUES 
(1,'eme','Eme Eme ','eme','c25000276');

DROP TABLE IF EXISTS `client_financial_info`;
CREATE TABLE `client_financial_info` (
  `client_fin_id` INT NOT NULL AUTO_INCREMENT,
  `source_of_funds` ENUM('Employment','Savings','Allowance','Business','Pension') DEFAULT NULL,
  `monthly_income` INT DEFAULT NULL,
  `client_id` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`client_fin_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_financial_info_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
);

INSERT INTO `client_financial_info` (`client_fin_id`,`source_of_funds`,`monthly_income`,`client_id`) VALUES 
(1,'Savings',10000,'c25000276');

DROP TABLE IF EXISTS `client_info`;
CREATE TABLE `client_info` (
  `client_id` VARCHAR(50) NOT NULL,
  `first_name` VARCHAR(50) DEFAULT NULL,
  `middle_name` VARCHAR(50) DEFAULT NULL,
  `last_name` VARCHAR(50) DEFAULT NULL,
  `sex` ENUM('Male','Female') DEFAULT NULL,
  `civil_status` ENUM('Single','Married','Seperated','Widowed') DEFAULT NULL,
  `birthdate` DATE DEFAULT NULL,
  `contact_number` INT DEFAULT NULL,
  `address` TEXT,
  `barangay` VARCHAR(50) DEFAULT NULL,
  `city` VARCHAR(50) DEFAULT NULL,
  `province` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
);

INSERT INTO `client_info` (`client_id`,`first_name`,`middle_name`,`last_name`,`sex`,`civil_status`,`birthdate`,`contact_number`,`address`,`barangay`,`city`,`province`) VALUES 
('c25000276','John Kenneth','Padua','Gado','Male','Single','2002-09-16',0,'Eme Eme ','Gaya Gaya','San Jose Del Monte','Bulacan');

DROP TABLE IF EXISTS `client_references`;
CREATE TABLE `client_references` (
  `client_ref_id` INT NOT NULL AUTO_INCREMENT,
  `fr_first_name` VARCHAR(50) DEFAULT NULL,
  `fr_last_name` VARCHAR(50) DEFAULT NULL,
  `fr_relationship` ENUM('Mother','Father','Siblings','Friends','Colleague','Relatives') DEFAULT NULL,
  `fr_contact_number` INT DEFAULT NULL,
  `sr_first_name` VARCHAR(50) DEFAULT NULL,
  `sr_last_name` VARCHAR(50) DEFAULT NULL,
  `sr_relationship` ENUM('Mother','Father','Siblings','Friends','Colleague','Relatives') DEFAULT NULL,
  `sr_contact_number` INT DEFAULT NULL,
  `client_id` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`client_ref_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_references_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
);

INSERT INTO `client_references` (`client_ref_id`,`fr_first_name`,`fr_last_name`,`fr_relationship`,`fr_contact_number`,`sr_first_name`,`sr_last_name`,`sr_relationship`,`sr_contact_number`,`client_id`) VALUES 
(1,'em','eme','Father',0,'eme','eme','Friends',0,'c25000276');

DROP TABLE IF EXISTS `loan_info`;
CREATE TABLE `loan_info` (
  `loan_id` INT NOT NULL AUTO_INCREMENT,
  `amount` INT DEFAULT NULL,
  `month` INT DEFAULT NULL,
  `terms` INT DEFAULT NULL,
  `purpose` TEXT,
  `client_id` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`loan_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `loan_info_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
);

INSERT INTO `loan_info` (`loan_id`,`amount`,`month`,`terms`,`purpose`,`client_id`) VALUES 
(1,5500,3,6,'Bills','c25000276');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `roles` (`id`,`role_name`) VALUES 
(1,'Admin'),
(2,'Client'),
(3,'Loan Officer');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(50) DEFAULT NULL,
  `password` VARCHAR(50) DEFAULT NULL,
  `role_id` INT DEFAULT NULL,
  `client_id` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_1` (`role_id`),
  KEY `fk_users_2` (`client_id`),
  CONSTRAINT `fk_users_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `fk_users_2` FOREIGN KEY (`client_id`) REFERENCES `client_info` (`client_id`)
);

INSERT INTO `users` (`id`,`email`,`password`,`role_id`,`client_id`) VALUES 
(1,'admin@admin.com','admin',1,NULL),
(2,'loan@officer.com','loanofficer',3,NULL),
(3,'johnkenneth413@gmail.com','hatdog',2,'c25000276');