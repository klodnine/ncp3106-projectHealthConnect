<?php
header('Content-Type: application/json');
require_once '../config.php';

$stats = [];

// Total patients
$result = $conn->query("SELECT COUNT(*) as count FROM patients");
$row = $result->fetch_assoc();
$stats['total_patients'] = $row['count'];

// Critical cases
$result = $conn->query("SELECT COUNT(*) as count FROM patients WHERE severity = 'High'");
$row = $result->fetch_assoc();
$stats['critical_cases'] = $row['count'];

// Resource statistics
$result = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN current_stock > minimum_threshold THEN 1 ELSE 0 END) as healthy,
    SUM(CASE WHEN current_stock <= minimum_threshold AND current_stock > 0 THEN 1 ELSE 0 END) as low,
    SUM(CASE WHEN current_stock = 0 THEN 1 ELSE 0 END) as critical
    FROM resources");
$row = $result->fetch_assoc();
$stats['resources'] = $row;

// Top diseases
$result = $conn->query("SELECT condition, COUNT(*) as count FROM patients GROUP BY condition ORDER BY count DESC LIMIT 5");
$diseases = [];
while ($row = $result->fetch_assoc()) {
    $diseases[] = $row;
}
$stats['top_diseases'] = $diseases;

echo json_encode(['success' => true, 'stats' => $stats]);
?>
