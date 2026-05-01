<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/supabase.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function issue_watch_url(string $streamUrl): string {
    session_regenerate_id(true);
    $nonce = bin2hex(random_bytes(24));
    $watchTtl = (int)env_value('WATCH_ACCESS_TTL_SECONDS', '300');
    if ($watchTtl <= 0) {
        $watchTtl = 300;
    }
    $_SESSION['watch_access'] = [
        'stream_url' => $streamUrl,
        'nonce' => $nonce,
        'expires_at' => time() + $watchTtl,
        'issued_at' => time(),
        'ua' => hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ];

    return 'watch.php?n=' . rawurlencode($nonce);
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!is_array($payload)) {
    $payload = $_POST;
}

$rawCode = trim((string)($payload['code'] ?? ''));
if ($rawCode === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'code is required']);
    exit;
}

$code = strtoupper($rawCode);

if ((int)env_value('DEMO_MODE_ENABLED', '0') === 1 && hash_equals(env_value('DEMO_ACCESS_CODE', ''), $code)) {
    $demoUrl = env_value('DEMO_VIDEO_URL', '');
    echo json_encode([
        'success' => true,
        'video_url' => issue_watch_url($demoUrl),
        'signed_url' => $demoUrl,
    ]);
    exit;
}

$lookup = supabase_request('GET', 'access_codes', null, [
    'code' => 'eq.' . $code,
    'select' => 'id,video_id,is_used',
    'limit' => '1',
], false);

if ($lookup['status'] >= 400) {
    error_log('verify.php lookup error: status=' . $lookup['status'] . ' body=' . $lookup['body']);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database lookup failed']);
    exit;
}

$rows = is_array($lookup['json']) ? $lookup['json'] : [];
$row = $rows[0] ?? null;

if (!$row) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Invalid code']);
    exit;
}

if (filter_var((string)($row['is_used'] ?? ''), FILTER_VALIDATE_BOOLEAN)) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Code already used']);
    exit;
}

$update = supabase_request('PATCH', 'access_codes?id=eq.' . rawurlencode((string)$row['id']), [
    'is_used' => true,
    'used_at' => gmdate('c'),
], [], true);

if ($update['status'] >= 400) {
    error_log('verify.php update error: status=' . $update['status'] . ' body=' . $update['body']);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Could not mark code as used']);
    exit;
}

$bucket = env_value('STORAGE_BUCKET', '');
$expires = (int)env_value('SIGNED_URL_EXPIRE_SECONDS', '3600');
$videoId = (string)$row['video_id'];
$libraryId = env_value('BUNNY_LIBRARY_ID', '');

$signedUrl = '';
if ($bucket !== '') {
    $sign = supabase_storage_sign_url($bucket, $videoId, $expires);
    if ($sign['status'] < 400 && is_array($sign['json'])) {
        $signedUrl = (string)($sign['json']['signedURL'] ?? $sign['json']['signed_url'] ?? '');
    } else {
        error_log('verify.php sign error: status=' . $sign['status'] . ' body=' . $sign['body']);
    }
}

$videoUrl = $signedUrl;
if ($videoUrl === '' && $libraryId !== '') {
    $videoUrl = sprintf(
        'https://player.mediadelivery.net/play/%s/%s',
        rawurlencode($libraryId),
        rawurlencode($videoId)
    );
}

$watchUrl = $videoUrl !== '' ? issue_watch_url($videoUrl) : null;

echo json_encode([
    'success' => true,
    'video_url' => $watchUrl,
    'signed_url' => $signedUrl !== '' ? $signedUrl : null,
    'video_id' => $videoId,
]);
