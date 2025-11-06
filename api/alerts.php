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
    
    $title = isset($data['title']) ? trim($data['title']) : '';
    $description = isset($data['description']) ? $data['description'] : '';
    $priority = isset($data['priority']) ? $data['priority'] : 'medium';
    $action_label = isset($data['action_label']) ? $data['action_label'] : '';
    
    $stmt = $conn->prepare("INSERT INTO alerts (title, description, priority, action_label, created_by, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    
    $stmt->bind_param("ssssi", $title, $description, $priority, $action_label, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Alert added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add alert']);
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
    $result = $conn->query("SELECT id, title, description, priority, action_label, created_at FROM alerts ORDER BY FIELD(priority, 'high', 'medium', 'low'), created_at DESC");
    
    $alerts = [];
    while ($row = $result->fetch_assoc()) {
        $alerts[] = $row;
    }
    
    echo json_encode(['success' => true, 'alerts' => $alerts]);
}
?>
