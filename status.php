<?php
/**
 * status.php - System status and testing dashboard
 */

require_once __DIR__ . '/config.php';

$status = [];

// 1. Check Bunny Stream config
$status['bunny'] = [
    'name' => 'Bunny Stream Configuration',
    'checks' => [
        'Library ID' => BUNNY_LIBRARY_ID ? '✓ ' . BUNNY_LIBRARY_ID : '✗ Missing',
        'API Key' => BUNNY_API_KEY ? '✓ Set' : '✗ Missing',
        'CDN Hostname' => BUNNY_CDN_HOSTNAME ? '✓ ' . BUNNY_CDN_HOSTNAME : '✗ Missing',
        'Security Key' => BUNNY_SECURITY_KEY ? '✓ Set' : '⚠ Empty (using direct URLs)',
    ]
];

// 2. Check Database
$db_status = 'Checking...';
$db_tables = [];
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ':' . DB_PORT . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $db_status = '✓ Connected';
    
    // Check tables
    $result = $pdo->query("SELECT COUNT(*) as count FROM access_codes");
    $row = $result->fetch();
    $db_tables[] = 'access_codes: ' . ($row ? $row['count'] . ' records' : 'error');
    
} catch (Exception $e) {
    $db_status = '✗ ' . $e->getMessage();
}

$status['database'] = [
    'name' => 'Database Status',
    'checks' => [
        'Connection' => $db_status,
        'Host' => DB_HOST . ':' . DB_PORT,
        'Database' => DB_NAME,
        'Tables' => implode(', ', $db_tables) ?: 'N/A',
    ]
];

// 3. Check Files
$status['files'] = [
    'name' => 'System Files',
    'checks' => [
        'portal.php' => file_exists('portal.php') ? '✓' : '✗',
        'verify.php' => file_exists('verify.php') ? '✓' : '✗',
        'add_video.php' => file_exists('add_video.php') ? '✓' : '✗',
        'watch.php' => file_exists('watch.php') ? '✓' : '✗',
        'config.php' => file_exists('config.php') ? '✓' : '✗',
        '.env' => file_exists('.env') ? '✓' : '✗',
    ]
];

// 4. Test video
$test_video = [
    'Video ID' => 'ee7964ca-5be8-4e00-918e-c73bc129f5e4',
    'Title' => 'الجلسة الثانية الجزء الثاني',
    'Direct URL' => 'https://player.mediadelivery.net/play/650875/ee7964ca-5be8-4e00-918e-c73bc129f5e4',
];

$status['test_video'] = [
    'name' => 'Test Video Links',
    'checks' => $test_video
];

// 5. Security
$status['security'] = [
    'name' => 'Security Settings',
    'checks' => [
        'Demo Mode' => DEMO_MODE_ENABLED ? '🟡 Enabled (for testing)' : '✓ Disabled',
        'Demo Code' => DEMO_MODE_ENABLED ? DEMO_ACCESS_CODE : 'N/A',
        'Admin Password' => env_value('ADMIN_PASSWORD') ? '✓ Set' : '✗ Not set',
        'Session Security' => session_status() === PHP_SESSION_ACTIVE ? '✓ Active' : '✓ Ready',
    ]
];

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Status Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container { max-width: 1000px; }
        h1 { color: white; margin-bottom: 30px; text-align: center; }
        .status-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .status-card h2 {
            color: #667eea;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .check-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .check-item:last-child { border-bottom: none; }
        .check-label { font-weight: bold; color: #333; }
        .check-value {
            text-align: right;
            font-family: monospace;
            font-size: 13px;
            word-break: break-word;
        }
        .checkmark { color: #28a745; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .action-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #5568d3; color: white; text-decoration: none; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 System Status Dashboard</h1>

        <?php foreach ($status as $section): ?>
        <div class="status-card">
            <h2><?php echo htmlspecialchars($section['name']); ?></h2>
            <?php foreach ($section['checks'] as $label => $value): ?>
            <div class="check-item">
                <span class="check-label"><?php echo htmlspecialchars($label); ?>:</span>
                <span class="check-value">
                    <?php 
                    if (strpos($value, '✓') === 0) {
                        echo '<span class="checkmark">' . htmlspecialchars($value) . '</span>';
                    } elseif (strpos($value, '⚠') === 0) {
                        echo '<span class="warning">' . htmlspecialchars($value) . '</span>';
                    } elseif (strpos($value, '✗') === 0) {
                        echo '<span class="error">' . htmlspecialchars($value) . '</span>';
                    } else {
                        echo htmlspecialchars($value);
                    }
                    ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <div class="status-card">
            <h2>🚀 Quick Actions</h2>
            <p>Use these links to test the system:</p>
            <div>
                <a href="portal.php" class="action-button btn-primary">📺 Open Portal</a>
                <a href="add_video.php" class="action-button btn-success">➕ Add Video (Admin)</a>
                <a href="portal.php?code=DEMO1-PLAY9" class="action-button btn-primary">🎬 Try Demo Video</a>
            </div>
        </div>

        <div class="status-card">
            <h2>📝 Setup Instructions</h2>
            <ol>
                <li>Open <strong>Add Video</strong> page</li>
                <li>Login with password: <code style="background: #f5f5f5; padding: 2px 6px;">admin123</code></li>
                <li>Copy the generated 10-character code</li>
                <li>Go to <strong>Portal</strong> and enter the code</li>
                <li>Click "Access Video" to watch from Bunny Stream</li>
            </ol>
        </div>
    </div>
</body>
</html>
