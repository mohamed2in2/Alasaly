<?php
/**
 * add_video.php - Password-protected admin panel for adding Bunny videos.
 */

require_once __DIR__ . '/supabase.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header('Location: add_video.php');
    exit;
}

$adminPassword = env_value('ADMIN_PASSWORD', '');
$isAuthenticated = (bool)($_SESSION['admin_authenticated'] ?? false);
$loginError = '';
$saveError = '';
$saveSuccess = null;
$manageError = '';
$manageSuccess = '';
$recentCodes = [];
$recentCodesError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_password'])) {
    $providedPassword = (string)$_POST['login_password'];
    if ($adminPassword !== '' && hash_equals($adminPassword, $providedPassword)) {
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_login_time'] = time();
        $isAuthenticated = true;
    } else {
        $loginError = 'Invalid password.';
    }
}

if ($isAuthenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manage_action'], $_POST['code_id'])) {
    $codeId = (int)$_POST['code_id'];
    $action = (string)$_POST['manage_action'];

    if ($codeId <= 0) {
        $manageError = 'Invalid code id.';
    } elseif ($action === 'delete_code') {
        $response = supabase_request('DELETE', 'access_codes?id=eq.' . rawurlencode((string)$codeId), null, [], false);
        if ($response['status'] === 200 || $response['status'] === 204) {
            $manageSuccess = 'Code deleted.';
        } else {
            error_log('[VideoPortal] delete_code error: status=' . $response['status'] . ' body=' . $response['body']);
            $manageError = 'Could not delete the code.';
        }
    } elseif ($action === 'enable_code' || $action === 'disable_code') {
        $isUsed = $action === 'disable_code';
        $payload = [
            'is_used' => $isUsed,
            'used_at' => $isUsed ? gmdate('c') : null,
        ];
        $response = supabase_request('PATCH', 'access_codes?id=eq.' . rawurlencode((string)$codeId), $payload, [], true);
        if ($response['status'] >= 200 && $response['status'] < 300) {
            $manageSuccess = $isUsed ? 'Code disabled.' : 'Code enabled.';
        } else {
            error_log('[VideoPortal] toggle_code error: status=' . $response['status'] . ' body=' . $response['body']);
            $manageError = 'Could not update the code.';
        }
    }
}

if ($isAuthenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_video'])) {
    $videoId = trim((string)($_POST['video_id'] ?? ''));
    $codeCount = max(1, min(20, (int)($_POST['code_count'] ?? 1)));

    if ($videoId === '') {
        $saveError = 'Video ID is required.';
    } else {
        $codes = [];
        $attempts = 0;
        $maxAttempts = $codeCount * 20;

        while (count($codes) < $codeCount && $attempts < $maxAttempts) {
            $attempts++;
            $accessCode = strtoupper(bin2hex(random_bytes(5)));

            $response = supabase_request('POST', 'access_codes', [
                'code' => $accessCode,
                'video_id' => $videoId,
                'is_used' => false,
            ], [], true);

            if ($response['status'] === 201 || $response['status'] === 200) {
                $codes[] = $accessCode;
                continue;
            }

            if ($response['status'] === 409) {
                continue;
            }

            error_log('[VideoPortal] add_video insert error: status=' . $response['status'] . ' body=' . $response['body']);
            $saveError = 'Failed to save the video code. Please check Supabase configuration.';
            break;
        }

        if ($saveError === '' && count($codes) > 0) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = (string)($_SERVER['HTTP_HOST'] ?? 'localhost');
            $portalBase = $protocol . '://' . $host . '/portal.php?code=';

            $saveSuccess = [
                'video_id' => $videoId,
                'codes' => $codes,
                // Open portal in preview mode so admin clicks don't immediately consume the code
                'first_link' => $portalBase . urlencode($codes[0]) . '&preview=1',
            ];
        } elseif ($saveError === '' && count($codes) === 0) {
            $saveError = 'Could not generate a unique code.';
        }
    }
}

if ($isAuthenticated) {
    $recentResponse = supabase_request('GET', 'access_codes', null, [
        'select' => 'id,code,video_id,is_used,created_at,used_at',
        'order' => 'created_at.desc',
        'limit' => '20',
    ], false);

    if ($recentResponse['status'] < 400 && is_array($recentResponse['json'])) {
        $recentCodes = $recentResponse['json'];
    } else {
        $recentCodesError = 'Could not load existing codes.';
        if ($recentResponse['status'] >= 400) {
            error_log('[VideoPortal] list_codes error: status=' . $recentResponse['status'] . ' body=' . $recentResponse['body']);
        }
    }
}

if (!$isAuthenticated) {
    ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Video Upload</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            width: min(420px, 100%);
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            padding: 32px;
        }
        .badge {
            display: inline-block;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        h1 { margin: 14px 0 8px; font-size: 28px; color: #1f2937; }
        p { margin: 0 0 24px; color: #6b7280; line-height: 1.5; }
        label { display: block; margin-bottom: 8px; font-weight: 700; color: #374151; }
        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            font-size: 15px;
        }
        button {
            width: 100%;
            margin-top: 18px;
            border: 0;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(130deg, #4f46e5, #7c3aed);
            cursor: pointer;
        }
        .error {
            margin-bottom: 14px;
            padding: 12px 14px;
            border-radius: 12px;
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="badge">Admin Area</div>
        <h1>Enter Password</h1>
        <p>Use the admin password to open the video upload panel.</p>
        <?php if ($loginError !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($loginError); ?></div>
        <?php endif; ?>
        <form method="POST">
            <label for="login_password">Password</label>
            <input type="password" id="login_password" name="login_password" autocomplete="current-password" required>
            <button type="submit">Open Admin Panel</button>
        </form>
    </div>
</body>
</html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Add Video</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            padding: 24px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(160deg, #f8fafc, #e2e8f0);
            color: #0f172a;
        }
        .wrap {
            width: min(920px, 100%);
            margin: 0 auto;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            gap: 12px;
        }
        .topbar h1 { margin: 0; font-size: 28px; }
        .logout {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            background: #ef4444;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }
        .panel {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            padding: 22px;
            margin-bottom: 18px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        label { display: block; margin: 0 0 8px; font-weight: 700; color: #334155; }
        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 15px;
        }
        .full { grid-column: 1 / -1; }
        .submit {
            margin-top: 14px;
            border: 0;
            border-radius: 12px;
            padding: 12px 16px;
            background: linear-gradient(130deg, #cc4b2c, #db6243);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }
        .error, .success {
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 16px;
        }
        .error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .success {
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            word-break: break-all;
        }
        .link {
            display: inline-block;
            margin-top: 8px;
            color: #4f46e5;
            font-weight: 700;
            word-break: break-all;
        }
        @media (max-width: 720px) {
            .grid { grid-template-columns: 1fr; }
            .topbar { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="topbar">
            <div>
                <h1>Admin Panel</h1>
                <div>Protected upload area for Bunny videos</div>
            </div>
            <form method="POST">
                <input type="hidden" name="logout" value="1">
                <button class="logout" type="submit">Logout</button>
            </form>
        </div>

        <?php if ($manageError !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($manageError); ?></div>
        <?php endif; ?>

        <?php if ($manageSuccess !== ''): ?>
            <div class="success"><?php echo htmlspecialchars($manageSuccess); ?></div>
        <?php endif; ?>

        <?php if ($saveError !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($saveError); ?></div>
        <?php endif; ?>

        <?php if (is_array($saveSuccess)): ?>
            <div class="success">
                <div><strong>Video saved successfully.</strong></div>
                <div>Video ID: <?php echo htmlspecialchars($saveSuccess['video_id']); ?></div>
                <div class="code">Code: <?php echo htmlspecialchars(implode(', ', $saveSuccess['codes'])); ?></div>
                <a class="link" href="<?php echo htmlspecialchars($saveSuccess['first_link']); ?>">Open portal with the first code</a>
            </div>
        <?php endif; ?>

        <div class="panel">
            <form method="POST">
                <input type="hidden" name="save_video" value="1">
                <div class="grid">
                    <div class="full">
                        <label for="video_id">Bunny Video ID</label>
                        <input id="video_id" name="video_id" type="text" required placeholder="ee7964ca-5be8-4e00-918e-c73bc129f5e4">
                    </div>
                    <div>
                        <label for="code_count">How many codes?</label>
                        <input id="code_count" name="code_count" type="number" min="1" max="20" value="1">
                    </div>
                    <div>
                        <label>Admin password</label>
                        <input type="password" value="••••••••" disabled>
                    </div>
                </div>
                <button class="submit" type="submit">Generate Code and Save</button>
            </form>
        </div>

        <div class="panel">
            <strong>Existing Codes</strong>
            <p style="margin-top: 8px; color: #64748b;">Use this table to delete a code or re-enable it if needed.</p>
            <?php if ($recentCodesError !== ''): ?>
                <div class="error"><?php echo htmlspecialchars($recentCodesError); ?></div>
            <?php elseif (empty($recentCodes)): ?>
                <p style="margin: 0; color: #64748b;">No codes found.</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 700px;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 10px;">Code</th>
                            <th style="padding: 10px;">Video ID</th>
                            <th style="padding: 10px;">Status</th>
                            <th style="padding: 10px;">Created</th>
                            <th style="padding: 10px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentCodes as $codeRow): ?>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px; font-family: ui-monospace, monospace; font-weight: 700;"><?php echo htmlspecialchars((string)($codeRow['code'] ?? '')); ?></td>
                                <td style="padding: 10px; font-family: ui-monospace, monospace;"><?php echo htmlspecialchars((string)($codeRow['video_id'] ?? '')); ?></td>
                                <td style="padding: 10px;">
                                    <?php echo !empty($codeRow['is_used']) ? 'Disabled / Used' : 'Enabled'; ?>
                                </td>
                                <td style="padding: 10px;"><?php echo htmlspecialchars((string)($codeRow['created_at'] ?? '')); ?></td>
                                <td style="padding: 10px; white-space: nowrap;">
                                    <form method="POST" style="display:inline-block; margin-right: 8px;">
                                        <input type="hidden" name="code_id" value="<?php echo htmlspecialchars((string)($codeRow['id'] ?? '')); ?>">
                                        <input type="hidden" name="manage_action" value="<?php echo !empty($codeRow['is_used']) ? 'enable_code' : 'disable_code'; ?>">
                                        <button class="submit" type="submit" style="margin-top:0; padding:8px 12px; background:#475569;">
                                            <?php echo !empty($codeRow['is_used']) ? 'Enable' : 'Disable'; ?>
                                        </button>
                                    </form>
                                    <form method="POST" style="display:inline-block;">
                                        <input type="hidden" name="code_id" value="<?php echo htmlspecialchars((string)($codeRow['id'] ?? '')); ?>">
                                        <input type="hidden" name="manage_action" value="delete_code">
                                        <button class="submit" type="submit" style="margin-top:0; padding:8px 12px; background:#dc2626;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="panel">
            <strong>How this works</strong>
            <ol>
                <li>Enter the Bunny video ID.</li>
                <li>Save it to Supabase and generate a 10-character code.</li>
                <li>Share the code through the portal.</li>
                <li>Use the table above to delete or re-enable codes.</li>
            </ol>
        </div>
     </div>
 </body>
 </html>
