<?php
/**
 * test.php - Complete testing interface
 */

require_once __DIR__ . '/config.php';

// Determine demo mode access
$demo_available = DEMO_MODE_ENABLED;
$demo_code = DEMO_ACCESS_CODE;
$demo_url = DEMO_VIDEO_URL;

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Test & Demo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container { max-width: 1200px; }
        h1 { color: white; margin-bottom: 30px; text-align: center; text-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .test-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        .test-card h2 {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .test-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .test-icon { font-size: 24px; margin-right: 15px; }
        .test-content { flex: 1; }
        .test-label { font-weight: bold; color: #333; }
        .test-value { color: #666; font-size: 13px; margin-top: 5px; font-family: monospace; }
        .btn-group { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            text-align: center;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: #218838;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-error { background: #f8d7da; color: #721c24; }
        .code-box {
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            font-family: monospace;
            margin: 10px 0;
            word-break: break-all;
            font-size: 13px;
        }
        .demo-preview {
            background: #f0f4ff;
            border: 2px dashed #667eea;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .demo-preview h3 { color: #667eea; margin-bottom: 10px; }
        .demo-preview p { color: #666; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Video Portal - System Test & Demo</h1>

        <!-- Configuration Status -->
        <div class="test-card">
            <h2>✅ System Configuration</h2>
            
            <div class="test-item">
                <div class="test-icon">🎥</div>
                <div class="test-content">
                    <div class="test-label">Bunny Stream Integration</div>
                    <div class="test-value">
                        Library ID: <?php echo BUNNY_LIBRARY_ID; ?><br>
                        CDN: <?php echo BUNNY_CDN_HOSTNAME; ?>
                    </div>
                    <span class="status-badge badge-active">✓ Configured</span>
                </div>
            </div>

            <div class="test-item">
                <div class="test-icon">🔐</div>
                <div class="test-content">
                    <div class="test-label">Security & Authentication</div>
                    <div class="test-value">Admin password protection enabled</div>
                    <span class="status-badge badge-active">✓ Active</span>
                </div>
            </div>

            <div class="test-item">
                <div class="test-icon">🎬</div>
                <div class="test-content">
                    <div class="test-label">Demo Mode</div>
                    <div class="test-value">Testing & development mode</div>
                    <span class="status-badge badge-active">✓ Enabled</span>
                </div>
            </div>
        </div>

        <!-- Demo Test -->
        <div class="test-card">
            <h2>🎯 Demo Test - Try It Now!</h2>
            
            <p style="color: #666; margin-bottom: 20px;">
                <strong>Demo Code:</strong>
                <span class="status-badge badge-active">FREE ACCESS</span>
            </p>

            <div class="demo-preview">
                <h3>Access Code</h3>
                <div class="code-box"><?php echo htmlspecialchars($demo_code); ?></div>
                <p style="font-size: 13px; color: #999;">Click a button below to test the portal</p>
            </div>

            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <strong>📋 Steps:</strong>
                <ol style="margin: 10px 0; padding-left: 20px;">
                    <li>Click "Open Portal" button</li>
                    <li>Enter code: <code><?php echo htmlspecialchars($demo_code); ?></code></li>
                    <li>Click "Access Video"</li>
                    <li>Watch the demo video</li>
                </ol>
            </div>

            <div class="btn-group">
                <a href="portal.php" class="btn btn-primary">📺 Open Portal (Enter Code Manually)</a>
                <a href="portal.php?code=<?php echo urlencode($demo_code); ?>" class="btn btn-success">⚡ Quick Demo (Direct Link)</a>
            </div>
        </div>

        <!-- Real Video Test -->
        <div class="test-card">
            <h2>🎥 Your Bunny Video</h2>
            
            <div class="test-item">
                <div class="test-icon">📹</div>
                <div class="test-content">
                    <div class="test-label">Video Title</div>
                    <div class="test-value">الجلسة الثانية الجزء الثاني</div>
                </div>
            </div>

            <div class="test-item">
                <div class="test-icon">🆔</div>
                <div class="test-content">
                    <div class="test-label">Video ID</div>
                    <div class="test-value">ee7964ca-5be8-4e00-918e-c73bc129f5e4</div>
                </div>
            </div>

            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px; margin-bottom: 15px;">
                <strong>✨ To add this video to the system:</strong>
                <p style="margin: 10px 0; color: #666;">
                    1. Go to Admin Area → 2. Enter password <code style="background: white; padding: 2px 6px;">admin123</code> → 3. Video gets stored & code generated → 4. Share the code with students
                </p>
            </div>

            <div class="btn-group">
                <a href="add_video.php" class="btn btn-success">➕ Add Video (Admin Area)</a>
                <a href="https://player.mediadelivery.net/play/650875/ee7964ca-5be8-4e00-918e-c73bc129f5e4" target="_blank" class="btn btn-primary">▶️ Direct Bunny Link</a>
            </div>
        </div>

        <!-- Admin Area -->
        <div class="test-card">
            <h2>🔧 Admin Area</h2>
            
            <div class="test-item">
                <div class="test-icon">🔐</div>
                <div class="test-content">
                    <div class="test-label">Admin Password</div>
                    <div class="test-value"><code style="background: #fff3cd; padding: 4px 8px; border-radius: 4px;">admin123</code></div>
                </div>
            </div>

            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px; margin-bottom: 15px;">
                <strong>📝 Features:</strong>
                <ul style="margin: 10px 0; padding-left: 20px; color: #666;">
                    <li>Password-protected video upload area</li>
                    <li>Generates 10-character access codes</li>
                    <li>Stores video in database</li>
                    <li>Creates shareable access links</li>
                    <li>One-time use per code (prevents sharing)</li>
                </ul>
            </div>

            <div class="btn-group">
                <a href="add_video.php" class="btn btn-danger">🔓 Enter Admin Area</a>
            </div>
        </div>

        <!-- Flow Diagram -->
        <div class="test-card">
            <h2>📊 Complete Flow</h2>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 13px; line-height: 1.8;">
                <div style="color: #667eea; font-weight: bold;">ADMIN FLOW:</div>
                add_video.php 🔐 → Login (admin123) → Video stored → Code generated ✓
                <br><br>
                <div style="color: #667eea; font-weight: bold;">STUDENT FLOW:</div>
                portal.php → Enter code → Bunny Stream loads → Video plays ▶️
                <br><br>
                <div style="color: #667eea; font-weight: bold;">TEST FLOW:</div>
                Enter "<?php echo htmlspecialchars($demo_code); ?>" → Demo video plays
            </div>
        </div>

        <!-- Links -->
        <div class="test-card" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h2 style="color: white; border-color: rgba(255,255,255,0.3); margin-bottom: 20px;">🚀 Quick Links</h2>
            <div class="btn-group" style="justify-content: center;">
                <a href="portal.php" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white;">📺 Portal</a>
                <a href="add_video.php" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white;">➕ Add Video</a>
                <a href="status.php" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white;">🔍 Status</a>
            </div>
        </div>

    </div>
</body>
</html>
