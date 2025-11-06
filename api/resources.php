<?php
header('Content-Type: application/json; charset=utf-8');
$dataFile = __DIR__ . '/../data/resources.json';
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

    $required = ['name','category','stock','threshold'];
    foreach ($required as $k) {
        if (!isset($input[$k])) { http_response_code(422); echo json_encode(["error"=>"Missing $k"]); exit; }
    }

    $items = [];
    if (file_exists($dataFile)) { $c = file_get_contents($dataFile); $items = $c ? json_decode($c, true) : []; if(!is_array($items)) $items = []; }

    $new = [
        'id' => uniqid('r', true),
        'name' => $input['name'],
        'category' => $input['category'],
        'stock' => (int)$input['stock'],
        'threshold' => (int)$input['threshold'],
        'usageRate' => isset($input['usageRate']) ? (float)$input['usageRate'] : 0,
        'timestamp' => date('c')
    ];

    $items[] = $new;
    if (file_put_contents($dataFile, json_encode($items, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX) === false) { http_response_code(500); echo json_encode(['error'=>'Unable to save']); exit; }

    echo json_encode($new);
    exit;
}

if ($method === 'DELETE') {
    parse_str($_SERVER['QUERY_STRING'] ?? '', $qs);
    $id = $qs['id'] ?? null;
    if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }
    $items = [];
    if (file_exists($dataFile)) { $c = file_get_contents($dataFile); $items = $c ? json_decode($c, true) : []; if(!is_array($items)) $items = []; }
    $before = count($items);
    $items = array_values(array_filter($items, function($i) use ($id) { return ($i['id'] ?? '') !== $id; }));
    if (file_put_contents($dataFile, json_encode($items, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX) === false) { http_response_code(500); echo json_encode(['error'=>'Unable to save']); exit; }
    echo json_encode(['deleted' => ($before - count($items))]);
    exit;
}

http_response_code(405);
echo json_encode(['error'=>'Method not allowed']);
