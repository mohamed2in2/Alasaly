<?php
/**
 * config.php — Central configuration for the Secure Video Portal.
 * Reads values from .env (project root) with safe defaults.
 */

/**
 * Loads key/value pairs from .env into process environment.
 * Keeps existing environment variables untouched.
 */
function load_dotenv(string $path): void {
    if (!is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!is_array($lines)) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
            continue;
        }

        $equalsPos = strpos($line, '=');
        if ($equalsPos === false) {
            continue;
        }

        $key = trim(substr($line, 0, $equalsPos));
        if ($key === '') {
            continue;
        }

        $value = trim(substr($line, $equalsPos + 1));
        $quote = $value !== '' ? $value[0] : '';
        if (($quote === '"' || $quote === "'") && str_ends_with($value, $quote) && strlen($value) >= 2) {
            $value = substr($value, 1, -1);
        }

        if (array_key_exists($key, $_ENV) || getenv($key) !== false) {
            continue;
        }

        $stringValue = (string)$value;
        $_ENV[$key] = $stringValue;
        $_SERVER[$key] = $stringValue;
        putenv($key . '=' . $stringValue);
    }
}

/**
 * Returns environment value or fallback if missing.
 */
function env_value(string $key, string $default = ''): string {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }

    return trim((string)$value);
}

/**
 * Returns true when env value is a truthy flag.
 */
function env_bool(string $key, bool $default = false): bool {
    $raw = env_value($key, $default ? '1' : '0');
    return in_array(strtolower($raw), ['1', 'true', 'yes', 'on'], true);
}

load_dotenv(__DIR__ . '/.env');

// Bunny.net Stream settings
define('BUNNY_LIBRARY_ID', env_value('BUNNY_LIBRARY_ID', ''));
define('BUNNY_SECURITY_KEY', env_value('BUNNY_SECURITY_KEY', ''));
define('BUNNY_API_KEY', env_value('BUNNY_API_KEY', ''));
define('BUNNY_CDN_HOSTNAME', env_value('BUNNY_CDN_HOSTNAME', ''));
define('BUNNY_PULL_ZONE', env_value('BUNNY_PULL_ZONE', ''));

// Token lifetime in seconds (default: 2 hours)
$tokenExpiry = (int)env_value('BUNNY_TOKEN_EXPIRY', '7200');
define('BUNNY_TOKEN_EXPIRY', $tokenExpiry > 0 ? $tokenExpiry : 7200);

// MySQL / MariaDB connection settings
define('DB_HOST', env_value('DB_HOST', 'localhost'));
define('DB_PORT', env_value('DB_PORT', '3306'));
define('DB_NAME', env_value('DB_NAME', 'school_platform'));
define('DB_USER', env_value('DB_USER', 'root'));
define('DB_PASS', env_value('DB_PASS', ''));

// Demo mode (lets you test without Bunny token setup)
define('DEMO_MODE_ENABLED', env_bool('DEMO_MODE_ENABLED', false));
define('DEMO_ACCESS_CODE', strtoupper(env_value('DEMO_ACCESS_CODE', 'DEMO1-PLAY9')));
define('DEMO_VIDEO_URL', env_value('DEMO_VIDEO_URL', 'https://www.youtube.com/embed/e1yDqlXin8g'));

// Watch session security settings
$watchTtl = (int)env_value('WATCH_ACCESS_TTL_SECONDS', '300');
define('WATCH_ACCESS_TTL_SECONDS', $watchTtl > 0 ? $watchTtl : 300);
define('WATCH_ONE_TIME_USE', env_bool('WATCH_ONE_TIME_USE', true));
define('WATCH_WATERMARK_ENABLED', env_bool('WATCH_WATERMARK_ENABLED', true));

// Comma-separated IP/CIDR ban list, examples:
// 203.0.113.10, 198.51.100.0/24, 2001:db8::/32
define('BANNED_IPS_RAW', env_value('BANNED_IPS', ''));
define('BANNED_IPS', array_values(array_filter(array_map('trim', explode(',', BANNED_IPS_RAW)))));

/**
 * Returns client IP address as seen by PHP.
 */
function get_client_ip(): string {
    return (string)($_SERVER['REMOTE_ADDR'] ?? '');
}

/**
 * Checks if an IP belongs to a CIDR range.
 */
function ip_in_cidr(string $ip, string $cidr): bool {
    $parts = explode('/', $cidr, 2);
    if (count($parts) !== 2) {
        return false;
    }

    $subnet = $parts[0];
    $prefix = (int)$parts[1];
    $ipBin = @inet_pton($ip);
    $subnetBin = @inet_pton($subnet);
    if ($ipBin === false || $subnetBin === false) {
        return false;
    }

    $len = strlen($ipBin);
    if ($len !== strlen($subnetBin)) {
        return false;
    }

    $maxPrefix = $len * 8;
    if ($prefix < 0 || $prefix > $maxPrefix) {
        return false;
    }

    $bytes = intdiv($prefix, 8);
    $bits = $prefix % 8;

    if ($bytes > 0 && substr($ipBin, 0, $bytes) !== substr($subnetBin, 0, $bytes)) {
        return false;
    }

    if ($bits === 0) {
        return true;
    }

    $mask = (0xFF00 >> $bits) & 0xFF;
    return (ord($ipBin[$bytes]) & $mask) === (ord($subnetBin[$bytes]) & $mask);
}

/**
 * Returns true when given IP matches ban list (exact IP or CIDR).
 */
function is_ip_banned(string $ip): bool {
    if ($ip === '' || empty(BANNED_IPS)) {
        return false;
    }

    foreach (BANNED_IPS as $rule) {
        if ($rule === '') {
            continue;
        }
        if (strpos($rule, '/') !== false) {
            if (ip_in_cidr($ip, $rule)) {
                return true;
            }
            continue;
        }
        if (hash_equals($rule, $ip)) {
            return true;
        }
    }

    return false;
}

// ─── Validation helper ────────────────────────────────────────────────────────
/**
 * Returns an array of any missing configuration values.
 * Called by verify.php before attempting to generate a Bunny token.
 *
 * @return string[]  List of human-readable error messages (empty = all OK).
 */
function get_config_errors(): array {
    $errors = [];

    if (BUNNY_LIBRARY_ID === '') {
        $errors[] = 'BUNNY_LIBRARY_ID is not set in .env.';
    }

    if (BUNNY_SECURITY_KEY === '') {
        $errors[] = 'BUNNY_SECURITY_KEY is not set in .env.';
    }

    return $errors;
}

// ─── PDO factory ──────────────────────────────────────────────────────────────
/**
 * Creates and returns a PDO connection.
 * Throws a RuntimeException (never raw PDOException) so callers can show a friendly message.
 *
 * @return PDO
 * @throws RuntimeException
 */
function create_pdo(): PDO {
    $dsn = 'mysql:host=' . DB_HOST;
    if (DB_PORT !== '') {
        $dsn .= ';port=' . DB_PORT;
    }
    $dsn .= ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Log the raw message server-side; expose only a generic message to callers.
        error_log('[VideoPortal] DB connection failed: ' . $e->getMessage());
        throw new RuntimeException('Unable to connect to the database. Please try again later.');
    }
}
