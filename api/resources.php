<?php
header('Content-Type: application/json');
require_once '../config.php';

// Check if user is logged in as healthcare worker
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'healthcare_worker') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $resource_name = isset($data['name']) ? trim($data['name']) : '';
    $category = isset($data['category']) ? $data['category'] : '';
    $stock = isset($data['stock']) ? intval($data['stock']) : 0;
    $unit = isset($data['unit']) ? $data['unit'] : '';
    $threshold = isset($data['threshold']) ? intval($data['threshold']) : 0;
    $usage_rate = isset($data['usage_rate']) ? floatval($data['usage_rate']) : 0;
    
    $stmt = $conn->prepare("INSERT INTO resources (name, category, current_stock, unit, minimum_threshold, daily_usage_rate, added_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    
    $stmt->bind_param("ssisidi", 
        $resource_name, $category, $stock, $unit, $threshold, $usage_rate, $_SESSION['user_id']
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Resource added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add resource']);
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
    $result = $conn->query("SELECT id, name, category, current_stock, unit, minimum_threshold, daily_usage_rate, created_at FROM resources ORDER BY created_at DESC");
    
    $resources = [];
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }
    
    echo json_encode(['success' => true, 'resources' => $resources]);
}
?>
