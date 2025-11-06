-- healthcare-dashboard database schema
-- Run this file in MySQL or import via phpMyAdmin / MySQL Workbench
-- Adjust the database name if you prefer

CREATE DATABASE IF NOT EXISTS `healthcare_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `healthcare_db`;

-- -----------------------------------------------------
-- Table `users` - application users (administrators / staff)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(200) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `role` ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `locations` - physical locations / map markers
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `locations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `street` VARCHAR(255) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `postal` VARCHAR(20) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `latitude` DECIMAL(10,7) DEFAULT NULL,
  `longitude` DECIMAL(10,7) DEFAULT NULL,
  `max_severity` ENUM('Low','Medium','High') DEFAULT 'Low',
  `total_cases` INT UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `patients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `patients` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `medical_id` VARCHAR(60) NOT NULL UNIQUE,
  `first_name` VARCHAR(120) NOT NULL,
  `last_name` VARCHAR(120) DEFAULT NULL,
  `dob` DATE DEFAULT NULL,
  `gender` ENUM('Male','Female','Other') DEFAULT NULL,
  `street` VARCHAR(255) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `postal` VARCHAR(20) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `location_id` INT UNSIGNED DEFAULT NULL,
  `severity` ENUM('Low','Medium','High') NOT NULL DEFAULT 'Low',
  `admission_date` DATE NOT NULL DEFAULT (CURRENT_DATE),
  `status` VARCHAR(80) DEFAULT 'admitted',
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_location` (`location_id`),
  KEY `idx_admission_date` (`admission_date`),
  CONSTRAINT `fk_patients_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `supplies` - medical supplies inventory
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `supplies` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `quantity` INT DEFAULT 0,
  `unit` VARCHAR(30) DEFAULT NULL,
  `reorder_level` INT DEFAULT 0,
  `location_id` INT UNSIGNED DEFAULT NULL,
  `last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_supplies_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Table `courses` - training / resources displayed on dashboard
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `courses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------
-- Sample seed data (safe examples) - adjust/remove as needed
-- -----------------------------------------------------

-- Locations
INSERT INTO `locations` (`name`, `street`, `city`, `state`, `postal`, `country`, `latitude`, `longitude`, `max_severity`, `total_cases`) VALUES
('Central Health Clinic','123 Main Street','Springfield','State','12345','Country', 40.712776, -74.005974, 'Medium', 12),
('Eastside Field Hospital','456 East Ave','Springfield','State','12346','Country', 40.713500, -74.002000, 'High', 25),
('Westside Community Center','789 West Blvd','Springfield','State','12347','Country', 40.710000, -74.010000, 'Low', 3);

-- Patients (example records for charts/testing)
INSERT INTO `patients` (`medical_id`, `first_name`, `last_name`, `dob`, `gender`, `street`, `city`, `state`, `postal`, `country`, `location_id`, `severity`, `admission_date`, `status`, `notes`) VALUES
('MED-10001','Alice','Johnson','1980-06-12','Female','12 Park Lane','Springfield','State','12345','Country',1,'High', DATE_SUB(CURDATE(), INTERVAL 1 DAY),'admitted','Severe respiratory distress'),
('MED-10002','Bob','Smith','1975-11-02','Male','78 Oak Road','Springfield','State','12345','Country',2,'Medium', CURDATE(),'admitted','Fever and cough'),
('MED-10003','Carlos','Diaz','1992-03-22','Male','34 Pine Street','Springfield','State','12346','Country',1,'Low', DATE_SUB(CURDATE(), INTERVAL 3 DAY),'discharged','Routine checkup'),
('MED-10004','Diana','Ng','1958-09-15','Female','9 Maple Ave','Springfield','State','12347','Country',3,'High', CURDATE(),'admitted','Elderly, needs close monitoring'),
('MED-10005','Ethan','Lee','2001-01-08','Male','101 Elm St','Springfield','State','12345','Country',2,'Medium', DATE_SUB(CURDATE(), INTERVAL 2 DAY),'admitted','Observation');

-- Supplies
INSERT INTO `supplies` (`name`,`category`,`quantity`,`unit`,`reorder_level`,`location_id`) VALUES
('Surgical Masks','PPE',1200,'pieces',200,1),
('Saline Bags 500ml','Consumable',80,'bags',20,2),
('Hand Sanitizer 1L','Hygiene',50,'bottles',10,1);

-- Courses / resources
INSERT INTO `courses` (`title`, `description`) VALUES
('Diabetes Management for Healthcare Providers','Practical course on diabetes screening and management'),
('Electronic Health Records Training','Introduction to EHR best practices and documentation');

-- Helpful indexes for common queries
CREATE INDEX IF NOT EXISTS `idx_patients_severity` ON `patients` (`severity`);
CREATE INDEX IF NOT EXISTS `idx_supplies_name` ON `supplies` (`name`(100));

-- End of file
