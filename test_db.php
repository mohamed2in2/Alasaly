<?php
// test_db.php - smoke test for Supabase REST connectivity.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/supabase.php';

$response = supabase_request('GET', 'access_codes', null, [
    'select' => 'id',
    'limit' => '1',
], false);

if ($response['status'] >= 400) {
    http_response_code(500);
    $message = 'Supabase API check failed';
    $body = $response['body'] ?? '';
    if (str_contains($body, 'PGRST205') || str_contains($body, 'public.access_codes')) {
        $message = 'Supabase API reachable, but the public.access_codes view is missing. Run database.sql in Supabase first.';
    } elseif (str_contains($body, 'PGRST106')) {
        $message = 'Supabase API reachable, but the school_platform schema is not exposed. The public.access_codes view is the recommended fix.';
    }

    echo json_encode([
        'ok' => false,
        'error' => $message,
    ]);
    error_log('Test REST error: status=' . $response['status'] . ' body=' . $response['body']);
    exit;
}

echo json_encode([
    'ok' => true,
    'message' => 'Supabase API reachable',
]);
