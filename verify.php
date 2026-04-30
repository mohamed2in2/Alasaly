<?php
/**
 * verify.php — Backend endpoint called via AJAX from index.php.
 *
 * POST parameters:
 *   code  (string)  The access code entered by the student.
 *
 * JSON response:
 *   { "success": true,  "video_url": "https://..." }
 *   { "success": false, "message": "Human-readable error" }
 *
 * "No-Crash" Policy:
 *   Every failure path returns a structured JSON response and never exposes raw errors.
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

// ── Helper: send JSON and exit ────────────────────────────────────────────────
function json_response(bool $success, string $message = '', string $video_url = ''): never {
    $payload = ['success' => $success];
    if ($success) {
        $payload['video_url'] = $video_url;
    } else {
        $payload['message'] = $message;
    }
    echo json_encode($payload);
    exit;
}

// ── 1. Accept only POST ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Invalid request method.');
}

// ── 2. Validate and sanitise the incoming code ────────────────────────────────
$raw_code = trim($_POST['code'] ?? '');

if ($raw_code === '') {
    json_response(false, 'Please enter an access code.');
}

// Codes are generated in XXXXX-XXXXX format (5 alphanumeric + hyphen + 5 alphanumeric).
if (!preg_match('/^[A-Z0-9]{5}-[A-Z0-9]{5}$/i', $raw_code)) {
    json_response(false, 'Invalid code format. Codes look like XXXXX-XXXXX.');
}

$code = strtoupper($raw_code);

// ── 3. Connect to DB (graceful failure) ───────────────────────────────────────
try {
    $pdo = create_pdo();
} catch (RuntimeException $e) {
    json_response(false, $e->getMessage());
}

// ── 4. Look up the code ───────────────────────────────────────────────────────
try {
    $stmt = $pdo->prepare(
        'SELECT id, video_id, is_used FROM access_codes WHERE code = ? LIMIT 1'
    );
    $stmt->execute([$code]);
    $row = $stmt->fetch();
} catch (PDOException $e) {
    error_log('[VideoPortal] DB query error: ' . $e->getMessage());
    json_response(false, 'A database error occurred. Please try again.');
}

if (!$row) {
    json_response(false, 'This code is invalid. Please check and try again.');
}

if ((int)$row['is_used'] === 1) {
    json_response(false, 'This code has already been used.');
}

// ── 5. Check Bunny.net configuration before attempting token generation ────────
$config_errors = get_config_errors();
if (!empty($config_errors)) {
    $detail = implode(' ', $config_errors);
    error_log('[VideoPortal] Config error: ' . $detail);
    json_response(false, 'System Configuration Error: Security keys are missing. Please contact the administrator.');
}

// ── 6. Generate Bunny.net Signed URL ─────────────────────────────────────────
$video_id = $row['video_id'];
$expires   = time() + BUNNY_TOKEN_EXPIRY;

// Bunny.net token algorithm:
//   token = base64url( SHA256( security_key + video_id + expires ) )
// Reference: https://docs.bunny.net/docs/stream-signed-urls
$hash_string = BUNNY_SECURITY_KEY . $video_id . $expires;
$raw_hash    = hash('sha256', $hash_string, true);       // binary output
$token       = rtrim(strtr(base64_encode($raw_hash), '+/', '-_'), '=');

$video_url = sprintf(
    'https://iframe.mediadelivery.net/embed/%s/%s?token=%s&expires=%d',
    rawurlencode((string)BUNNY_LIBRARY_ID),
    rawurlencode($video_id),
    rawurlencode($token),
    $expires
);

// ── 7. Mark code as used (atomic update) ─────────────────────────────────────
try {
    $update = $pdo->prepare(
        'UPDATE access_codes SET is_used = 1, used_at = NOW() WHERE id = ? AND is_used = 0'
    );
    $update->execute([$row['id']]);

    // If no row was updated another request already consumed the code (race condition).
    if ($update->rowCount() === 0) {
        json_response(false, 'This code has already been used.');
    }
} catch (PDOException $e) {
    error_log('[VideoPortal] DB update error: ' . $e->getMessage());
    json_response(false, 'A database error occurred while validating your code.');
}

// ── 8. Return the signed URL ──────────────────────────────────────────────────
json_response(true, '', $video_url);
