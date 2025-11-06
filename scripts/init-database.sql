-- Initialize Healthcare Database
-- Run this script to create all necessary tables

-- Create database
CREATE DATABASE IF NOT EXISTS healthcare_db;
USE healthcare_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('healthcare_worker', 'admin', 'viewer') DEFAULT 'viewer',
    phone VARCHAR(20),
    organization VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Patients table
CREATE TABLE IF NOT EXISTS patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medical_id VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    region VARCHAR(50),
    city VARCHAR(50),
    barangay VARCHAR(50),
    street_address VARCHAR(255),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    condition VARCHAR(100),
    severity ENUM('Low', 'Medium', 'High') DEFAULT 'Low',
    contact VARCHAR(20),
    insurance VARCHAR(100),
    admission_date DATE,
    notes LONGTEXT,
    added_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY fk_patients_users (added_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_medical_id (medical_id),
    INDEX idx_condition (condition),
    INDEX idx_severity (severity),
    INDEX idx_region (region),
    INDEX idx_city (city)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Resources table
CREATE TABLE IF NOT EXISTS resources (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    category ENUM('Medication', 'Medical Supplies', 'Equipment', 'PPE', 'Laboratory', 'Emergency') DEFAULT 'Medication',
    current_stock INT DEFAULT 0,
    unit VARCHAR(50),
    minimum_threshold INT DEFAULT 0,
    daily_usage_rate DECIMAL(10, 2) DEFAULT 0,
    added_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY fk_resources_users (added_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_name (name),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Alerts table
CREATE TABLE IF NOT EXISTS alerts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description LONGTEXT,
    priority ENUM('high', 'medium', 'low') DEFAULT 'medium',
    action_label VARCHAR(100),
    is_acknowledged BOOLEAN DEFAULT FALSE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY fk_alerts_users (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_priority (priority),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert demo healthcare worker account (password: demo123)
INSERT INTO users (name, email, password, role, organization) VALUES 
('Demo Healthcare Worker', 'demo@healthcare.com', '$2y$10$OIJ7mJtaI8.5mJqKtQT3He.Bx5K5K5K5K5K5K5K5', 'healthcare_worker', 'Demo Hospital')
ON DUPLICATE KEY UPDATE name = name;
