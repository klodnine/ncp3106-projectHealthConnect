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
    
    $patient_name = isset($data['name']) ? trim($data['name']) : '';
    $dob = isset($data['dob']) ? $data['dob'] : '';
    $region = isset($data['region']) ? $data['region'] : '';
    $city = isset($data['city']) ? $data['city'] : '';
    $barangay = isset($data['barangay']) ? $data['barangay'] : '';
    $street = isset($data['street']) ? $data['street'] : '';
    $lat = isset($data['lat']) ? floatval($data['lat']) : 0;
    $lng = isset($data['lng']) ? floatval($data['lng']) : 0;
    $condition = isset($data['condition']) ? $data['condition'] : '';
    $severity = isset($data['severity']) ? $data['severity'] : '';
    $contact = isset($data['contact']) ? $data['contact'] : '';
    $insurance = isset($data['insurance']) ? $data['insurance'] : '';
    $admission_date = isset($data['admission_date']) ? $data['admission_date'] : '';
    $notes = isset($data['notes']) ? $data['notes'] : '';
    
    $stmt = $conn->prepare("INSERT INTO patients (medical_id, name, dob, region, city, barangay, street_address, latitude, longitude, condition, severity, contact, insurance, admission_date, notes, added_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    $medical_id = 'MED' . date('YmdHis') . rand(1000, 9999);
    
    $stmt->bind_param("sssssssddssssssi", 
        $medical_id, $patient_name, $dob, $region, $city, $barangay, $street, 
        $lat, $lng, $condition, $severity, $contact, $insurance, $admission_date, $notes, $_SESSION['user_id']
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Patient added successfully', 'medical_id' => $medical_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add patient']);
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
    $result = $conn->query("SELECT id, medical_id, name, dob, condition, severity, contact, created_at FROM patients ORDER BY created_at DESC");
    
    $patients = [];
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
    
    echo json_encode(['success' => true, 'patients' => $patients]);
}
?>
