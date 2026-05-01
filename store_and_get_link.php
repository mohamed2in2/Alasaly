<?php
/**
 * store_and_get_link.php
 * Stores a video in the database and generates an access link
 */

require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ':' . DB_PORT . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Video details from Bunny Stream
    $video_id = 'ee7964ca-5be8-4e00-918e-c73bc129f5e4';
    $video_title = 'الجلسة الثانية الجزء الثاني';
    
    // Generate unique 10-character access code
    $access_code = strtoupper(bin2hex(random_bytes(5)));

    // Insert into database
    $stmt = $pdo->prepare('INSERT INTO access_codes (code, video_id) VALUES (?, ?)');
    $stmt->execute([$access_code, $video_id]);

    // Get the base URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $base_url = $protocol . '://' . $host;

    // Generate the access link
    $access_link = $base_url . '/portal.php?code=' . urlencode($access_code);

    // Display results
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Video Stored Successfully</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 5px; color: #155724; }
        .info { background: #e7f3ff; border: 1px solid #b3d9ff; padding: 15px; margin: 15px 0; border-radius: 5px; color: #004085; }
        code { background: #f5f5f5; padding: 5px 10px; border-radius: 3px; font-family: monospace; }
        input { width: 100%; padding: 10px; margin: 10px 0; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #218838; }
    </style>
</head>
<body>
    <div class='success'>
        <h2>✓ Video Stored Successfully!</h2>
    </div>

    <div class='info'>
        <strong>Video Title:</strong> $video_title<br>
        <strong>Video ID:</strong> <code>$video_id</code><br>
        <strong>Access Code:</strong> <code>$access_code</code><br>
    </div>

    <div class='info'>
        <strong>📎 Access Link:</strong><br>
        <input type='text' value='$access_link' id='link' readonly>
        <button class='btn' onclick=\"
            var copyText = document.getElementById('link');
            copyText.select();
            document.execCommand('copy');
            alert('Link copied to clipboard!');
        \">Copy Link</button>
    </div>

    <div class='info'>
        <strong>Database Entry:</strong><br>
        - Code: <code>$access_code</code><br>
        - Video ID: <code>$video_id</code><br>
        - Status: Not used yet<br>
        - Created: " . date('Y-m-d H:i:s') . "
    </div>

    <div class='info' style='background: #fff3cd; border-color: #ffc107; color: #856404;'>
        <strong>⚠️ Share this link with students:</strong><br>
        <code style='display: block; word-break: break-all; margin: 10px 0;'>$access_link</code>
        <br>When they visit this link, they can enter the code to access the video.
    </div>
</body>
</html>";

} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>
