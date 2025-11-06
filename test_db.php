<?php
/**
 * test_db.php
 * Quick test that includes `db.php` and reports connection status.
 * Open in a browser: http://localhost/healthcare-dashboard/test_db.php
 */

// Capture any output from db.php (in case it dies with an error message)
ob_start();
include __DIR__ . '/db.php';
$output = ob_get_clean();

if (strpos($output, 'Database connection failed:') !== false) {
    // show the connection error message
    echo $output;
    exit;
}

// If db.php didn't die, we assume the connection succeeded
if (isset($conn) && $conn instanceof mysqli && $conn->ping()) {
    echo 'Database connection OK.';
} else {
    echo 'Database connection could not be verified. Check credentials in db.php.';
}
