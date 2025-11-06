<?php
ini_set('display_errors', '1'); error_reporting(E_ALL);
echo "Index reached\n";
require __DIR__ . '/includes/header.php';
echo "Header ok\n";
require __DIR__ . '/views/role_select.php';
echo "View ok\n";
require __DIR__ . '/includes/footer.php';
echo "Footer ok\n";
