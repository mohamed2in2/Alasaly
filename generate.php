<?php
/**
 * generate.php — Admin panel for bulk-generating access codes.
 *
 * Open this file in your browser ONLY from a trusted network or after adding
 * password protection (HTTP Basic Auth or a login check).
 *
 * Actions:
 *   GET  — Shows the form and the list of all existing codes.
 *   POST — Generates N unique codes linked to a given video_id, saves to DB.
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$message      = '';
$message_type = 'success'; // 'success' | 'danger'
$new_codes    = [];

// ── Database connection ───────────────────────────────────────────────────────
$pdo = null;
$db_error = '';
try {
    $pdo = create_pdo();
} catch (RuntimeException $e) {
    $db_error = $e->getMessage();
}

// ── Generate codes on POST ────────────────────────────────────────────────────
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {

    $count    = max(1, min(200, (int)($_POST['count']    ?? 10)));
    $video_id = trim($_POST['video_id'] ?? '');

    if ($video_id === '') {
        $message      = 'Please enter a Video ID.';
        $message_type = 'danger';
    } else {
        $generated = 0;
        $attempts  = 0;

        $insert = $pdo->prepare(
            'INSERT IGNORE INTO access_codes (code, video_id) VALUES (?, ?)'
        );

        while ($generated < $count && $attempts < $count * 5) {
            $attempts++;
            $code = generate_random_code();
            $insert->execute([$code, $video_id]);
            if ($insert->rowCount() === 1) {
                $new_codes[] = $code;
                $generated++;
            }
        }

        if ($generated === $count) {
            $message = "✅ Successfully generated {$generated} codes for Video ID: <strong>" . htmlspecialchars($video_id) . '</strong>';
        } else {
            $message      = "⚠️ Only {$generated} of {$count} codes could be generated (possible collision).";
            $message_type = 'warning';
        }
    }
}

// ── Fetch all codes for display ───────────────────────────────────────────────
$all_codes = [];
if ($pdo) {
    try {
        $all_codes = $pdo->query(
            'SELECT id, code, video_id, is_used, created_at, used_at
             FROM access_codes
             ORDER BY created_at DESC
             LIMIT 500'
        )->fetchAll();
    } catch (PDOException $e) {
        error_log('[VideoPortal] Fetch codes error: ' . $e->getMessage());
    }
}

// ── Helper: generate a random unique code (format: XXXXX-XXXXX) ──────────────
function generate_random_code(): string {
    $chars  = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // exclude 0/O and 1/I to avoid confusion
    $result = '';
    $max    = strlen($chars) - 1;
    for ($i = 0; $i < 10; $i++) {
        $result .= $chars[random_int(0, $max)];
    }
    // Format as XXXXX-XXXXX for readability
    return substr($result, 0, 5) . '-' . substr($result, 5, 5);
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — Generate Access Codes</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f0f2f5; }
        .card { border-radius: 12px; }
        .code-badge { font-family: monospace; font-size: 1rem; letter-spacing: 1px; }
        .table th { white-space: nowrap; }
    </style>
</head>
<body>
<div class="container py-5">

    <h2 class="mb-4 fw-bold">🔑 Access Code Generator — Admin Panel</h2>

    <?php if ($db_error): ?>
        <div class="alert alert-danger">
            <strong>Database Error:</strong> <?= htmlspecialchars($db_error) ?>
            <br>Check your <code>config.php</code> settings.
        </div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>

    <!-- ── New-codes preview ──────────────────────────────────────────── -->
    <?php if (!empty($new_codes)): ?>
        <div class="card shadow-sm mb-4 p-4">
            <h5 class="mb-3">Newly Generated Codes:</h5>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($new_codes as $c): ?>
                    <span class="badge bg-success code-badge fs-6 px-3 py-2">
                        <?= htmlspecialchars($c) ?>
                    </span>
                <?php endforeach; ?>
            </div>
            <small class="text-muted mt-2">Copy these codes and distribute them to your students.</small>
        </div>
    <?php endif; ?>

    <!-- ── Generation form ───────────────────────────────────────────── -->
    <div class="card shadow-sm mb-5 p-4">
        <h5 class="mb-3">Generate New Codes</h5>
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Video ID (from Bunny.net)</label>
                    <input type="text" name="video_id" class="form-control"
                           placeholder="e.g. 88273abc-1234-5678-abcd-ef0123456789" required>
                    <div class="form-text">Found in Bunny Stream → your video → Details.</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Number of Codes</label>
                    <input type="number" name="count" class="form-control"
                           value="10" min="1" max="200" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" name="generate" class="btn btn-primary w-100">
                        Generate &amp; Save
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ── Existing codes table ──────────────────────────────────────── -->
    <div class="card shadow-sm p-4">
        <h5 class="mb-3">All Access Codes (last 500)</h5>
        <?php if (empty($all_codes)): ?>
            <p class="text-muted">No codes found. Generate some above.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Video ID</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Used At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_codes as $row): ?>
                            <tr>
                                <td><?= (int)$row['id'] ?></td>
                                <td>
                                    <code class="code-badge">
                                        <?= htmlspecialchars($row['code']) ?>
                                    </code>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars($row['video_id']) ?></small>
                                </td>
                                <td>
                                    <?php if ($row['is_used']): ?>
                                        <span class="badge bg-secondary">Used</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars((string)$row['created_at']) ?></small>
                                </td>
                                <td>
                                    <small><?= $row['used_at'] ? htmlspecialchars($row['used_at']) : '—' ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
