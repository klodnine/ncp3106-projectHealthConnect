<?php
header('Content-Type: application/json; charset=utf-8');

// Simple JSON-file based API for patients. Falls back to DB when configured (api/db.php PDO)
$dataFile = __DIR__ . '/../data/patients.json';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (!file_exists($dataFile)) {
        echo json_encode([]);
        exit;
    }
    $json = file_get_contents($dataFile);
    echo $json === false ? json_encode([]) : $json;
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid JSON payload"]);
        exit;
    }

    // Minimal validation
    $required = ['name','location','condition','severity','admissionDate'];
    foreach ($required as $k) {
        if (empty($input[$k])) {
            http_response_code(422);
            echo json_encode(["error" => "Missing field: $k"]);
            exit;
        }
    }

    // Load existing
    $patients = [];
    if (file_exists($dataFile)) {
        $c = file_get_contents($dataFile);
        $patients = $c ? json_decode($c, true) : [];
        if (!is_array($patients)) $patients = [];
    }

    $new = [
        'id' => uniqid('p', true),
        'name' => $input['name'],
        'location' => $input['location'],
        'condition' => $input['condition'],
        'severity' => $input['severity'],
        'admissionDate' => $input['admissionDate'],
        'contact' => $input['contact'] ?? '',
        'coordinates' => $input['coordinates'] ?? null,
        'updateDiseaseTracking' => !empty($input['updateDiseaseTracking']),
        'timestamp' => date('c')
    ];

    $patients[] = $new;
    if (file_put_contents($dataFile, json_encode($patients, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Unable to save data']);
        exit;
    }

    echo json_encode($new);
    exit;
}

if ($method === 'DELETE') {
    // expects ?id=...
    parse_str($_SERVER['QUERY_STRING'] ?? '', $qs);
    $id = $qs['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id parameter']);
        exit;
    }
    $patients = [];
    if (file_exists($dataFile)) {
        $c = file_get_contents($dataFile);
        $patients = $c ? json_decode($c, true) : [];
        if (!is_array($patients)) $patients = [];
    }
    $before = count($patients);
    $patients = array_values(array_filter($patients, function($p) use ($id) { return ($p['id'] ?? '') !== $id; }));
    if (file_put_contents($dataFile, json_encode($patients, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Unable to save data']);
        exit;
    }
    echo json_encode(['deleted' => ($before - count($patients))]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
