<?php
/**
 * db.php
 * Simple MySQLi connection file. Adjust the values below to match your
 * local XAMPP/MAMP/production database credentials.
 *
 * Exposes:
 * - $conn  (mysqli connection object)
 */

// --- Edit these values to match your environment ---
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'healthcare_db'; // change to your database name

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
	// On production, you might want to log this instead of exposing it.
	die('Database connection failed: ' . $conn->connect_error);
}

// Recommended charset
$conn->set_charset('utf8mb4');

// Optional: a small helper to get the connection (if you prefer function style)
if (!function_exists('get_db_connection')) {
	function get_db_connection()
	{
		global $conn;
		return $conn;
	}
}

