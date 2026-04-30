<?php
/**
 * config.php — Central configuration for the Secure Video Portal.
 * Fill in your real values before going live.
 * The system will NEVER crash on missing keys; it will log and return a safe error instead.
 */

// ─── Bunny.net Stream settings ────────────────────────────────────────────────
// Library ID  — found in Bunny Stream → your library → "Library ID"
define('BUNNY_LIBRARY_ID', '');        // e.g. '123456'

// Security / Token Key — found in Bunny Stream → your library → Security → Token Authentication
define('BUNNY_SECURITY_KEY', '');      // e.g. 'a1b2c3d4e5f6...'

// Token lifetime in seconds (default: 2 hours)
define('BUNNY_TOKEN_EXPIRY', 7200);

// ─── MySQL / MariaDB connection settings ──────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'school_platform'); // change to your actual database name
define('DB_USER', 'root');
define('DB_PASS', '');                // XAMPP default is empty; change in production

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
        $errors[] = 'BUNNY_LIBRARY_ID is not set in config.php.';
    }

    if (BUNNY_SECURITY_KEY === '') {
        $errors[] = 'BUNNY_SECURITY_KEY is not set in config.php.';
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
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
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
