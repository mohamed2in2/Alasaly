<?php
require_once __DIR__ . '/supabase.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add_video.php');
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$adminPassword = env_value('ADMIN_PASSWORD', '');
$providedPassword = $_POST['admin_password'] ?? '';

if ($adminPassword === '' || !hash_equals($adminPassword, (string)$providedPassword)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$videoId = trim((string)($_POST['video_id'] ?? ''));
$count = max(1, min(200, (int)($_POST['count'] ?? 1)));

if ($videoId === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'video_id is required']);
    exit;
}

$codes = [];
$attempts = 0;
$maxAttempts = $count * 20;

while (count($codes) < $count && $attempts < $maxAttempts) {
    $attempts++;
    $code = random_code(10);

    $response = supabase_request('POST', 'access_codes', [
        'code' => $code,
        'video_id' => $videoId,
        'is_used' => false,
    ], [], true);

    if ($response['status'] === 201 || $response['status'] === 200) {
        $codes[] = $code;
        continue;
    }

    if ($response['status'] === 409) {
        continue;
    }

    error_log('generate.php insert error: status=' . $response['status'] . ' body=' . $response['body']);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to create code']);
    exit;
}

if (count($codes) !== $count) {
    http_response_code(409);
    echo json_encode([
        'success' => false,
        'message' => 'Could not generate all requested codes',
        'codes' => $codes,
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Codes generated successfully',
    'video_id' => $videoId,
    'codes' => $codes,
]);
