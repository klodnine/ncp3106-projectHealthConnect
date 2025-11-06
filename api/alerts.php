<?php
header('Content-Type: application/json; charset=utf-8');
$dataFile = __DIR__ . '/../data/alerts.json';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (!file_exists($dataFile)) { echo json_encode([]); exit; }
    $json = file_get_contents($dataFile);
    echo $json === false ? json_encode([]) : $json;
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) { http_response_code(400); echo json_encode(["error"=>"Invalid JSON"]); exit; }

    $required = ['title','priority'];
    foreach ($required as $k) { if (empty($input[$k])) { http_response_code(422); echo json_encode(["error"=>"Missing $k"]); exit; } }

    $alerts = [];
    if (file_exists($dataFile)) { $c = file_get_contents($dataFile); $alerts = $c ? json_decode($c, true) : []; if(!is_array($alerts)) $alerts = []; }

    $new = [
        'id' => uniqid('a', true),
        'title' => $input['title'],
        'priority' => $input['priority'],
        'details' => $input['details'] ?? '',
        'timestamp' => date('c')
    ];

    $alerts[] = $new;
    if (file_put_contents($dataFile, json_encode($alerts, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX) === false) { http_response_code(500); echo json_encode(['error'=>'Unable to save']); exit; }

    echo json_encode($new);
    exit;
}

if ($method === 'DELETE') {
    parse_str($_SERVER['QUERY_STRING'] ?? '', $qs);
    $id = $qs['id'] ?? null;
    if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }
    $alerts = [];
    if (file_exists($dataFile)) { $c = file_get_contents($dataFile); $alerts = $c ? json_decode($c, true) : []; if(!is_array($alerts)) $alerts = []; }
    $before = count($alerts);
    $alerts = array_values(array_filter($alerts, function($a) use ($id) { return ($a['id'] ?? '') !== $id; }));
    if (file_put_contents($dataFile, json_encode($alerts, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX) === false) { http_response_code(500); echo json_encode(['error'=>'Unable to save']); exit; }
    echo json_encode(['deleted' => ($before - count($alerts))]);
    exit;
}

http_response_code(405);
echo json_encode(['error'=>'Method not allowed']);
