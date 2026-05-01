<?php
// supabase.php - small helper layer for env loading + Supabase REST calls.

function load_env_file(string $path): void
{
    if (!is_file($path) || !is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if ($name === '' || getenv($name) !== false) {
            continue;
        }

        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }

        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
    }
}

load_env_file(__DIR__ . '/.env');

function env_value(string $name, string $default = ''): string
{
    $value = getenv($name);
    return $value === false || $value === '' ? $default : $value;
}

function supabase_base_url(): string
{
    $url = env_value('SUPABASE_URL', '');
    $url = rtrim($url, '/');
    $url = preg_replace('#/rest/v1$#', '', $url) ?? $url;
    $url = preg_replace('#/storage/v1$#', '', $url) ?? $url;
    return rtrim((string)$url, '/');
}

function supabase_headers(bool $json = true, string $schema = 'public'): array
{
    $key = env_value('SUPABASE_SERVICE_ROLE_KEY', '');
    $headers = [
        'apikey: ' . $key,
        'Authorization: Bearer ' . $key,
        'Accept-Profile: ' . $schema,
        'Content-Profile: ' . $schema,
    ];
    if ($json) {
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
    }
    return $headers;
}

function supabase_request(string $method, string $path, ?array $jsonBody = null, array $query = [], bool $json = true, string $schema = 'public'): array
{
    $baseUrl = supabase_base_url();
    if ($baseUrl === '') {
        return ['status' => 0, 'body' => '', 'json' => null, 'error' => 'SUPABASE_URL is missing'];
    }

    $url = $baseUrl . '/rest/v1/' . ltrim($path, '/');
    if ($query) {
        $url .= '?' . http_build_query($query);
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => supabase_headers($json, $schema),
        CURLOPT_TIMEOUT => 20,
    ]);

    if ($jsonBody !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsonBody, JSON_UNESCAPED_SLASHES));
    }

    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $decoded = null;
    if (is_string($body) && $body !== '') {
        $decoded = json_decode($body, true);
    }

    return [
        'status' => $status,
        'body' => is_string($body) ? $body : '',
        'json' => $decoded,
        'error' => $error ?: null,
    ];
}

function supabase_storage_sign_url(string $bucket, string $path, int $expiresIn): array
{
    $baseUrl = supabase_base_url();
    if ($baseUrl === '') {
        return ['status' => 0, 'body' => '', 'json' => null, 'error' => 'SUPABASE_URL is missing'];
    }

    $url = $baseUrl . '/storage/v1/object/sign/' . rawurlencode($bucket) . '/' . ltrim($path, '/');
    $url .= '?expiresIn=' . max(1, $expiresIn);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => supabase_headers(true),
        CURLOPT_TIMEOUT => 20,
        CURLOPT_POSTFIELDS => '{}',
    ]);

    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $decoded = null;
    if (is_string($body) && $body !== '') {
        $decoded = json_decode($body, true);
    }

    return [
        'status' => $status,
        'body' => is_string($body) ? $body : '',
        'json' => $decoded,
        'error' => $error ?: null,
    ];
}

function random_code(int $len = 10): string
{
    $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
    $max = strlen($chars) - 1;
    $result = '';
    for ($i = 0; $i < $len; $i++) {
        $result .= $chars[random_int(0, $max)];
    }
    return substr($result, 0, 5) . '-' . substr($result, 5, 5);
}
