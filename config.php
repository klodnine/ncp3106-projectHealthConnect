<?php
/**
 * Database Configuration
 * Update these credentials with your actual database info
 */

// Database connection details
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'healthcare_db');

// Connect to database
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to utf8
    $conn->set_charset("utf8");
    
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// Base URL for the application
define('BASE_URL', 'http://localhost/healthcare/');

// Session timeout in seconds
define('SESSION_TIMEOUT', 3600);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
