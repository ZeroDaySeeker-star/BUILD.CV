<?php
require_once __DIR__ . '/auth-check.php';

// Handling form submission for creating codes
$successMsg = $_SESSION['flash_success'] ?? '';
$errorMsg = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = "Jeton CSRF invalide.";
    } else {
        $planId = (int)$_POST['plan_id'];
        $count = (int)$_POST['count'];
        $duration = (int)$_POST['duration'];
        $maxUses = (int)$_POST['max_uses'];
        $expires = $_POST['expires_at'] ?: null; // Optional
        
        if ($count > 0 && $count <= 500) {
            $codes = PremiumCode::generate($planId, $duration, $maxUses, $expires, $count);
            $_SESSION['flash_success'] = count($codes) . " code(s) généré(s) avec succès !";
        } else {
            $_SESSION['flash_error'] = "Le nombre de codes doit être entre 1 et 500.";
        }
    }
    header("Location: " . APP_URL . "/admin/codes.php");
    exit;
}

// Handling disable action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'disable') {
    if (verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $codeId = (int)$_POST['code_id'];
        db()->query("UPDATE premium_codes SET status = 'disabled' WHERE id = ?", [$codeId]);
        $_SESSION['flash_success'] = "Le code a été désactivé.";
    } else {
        $_SESSION['flash_error'] = "Jeton CSRF invalide pour la désactivation.";
    }
    header("Location: " . APP_URL . "/admin/codes.php");
    exit;
}

// Fetch plans for dropdown
$plans = db()->fetchAll("SELECT * FROM plans WHERE is_active = 1 ORDER BY position ASC");

// Filtering
$statusFilter = $_GET['status'] ?? '';
$where = "WHERE 1=1";
$params = [];
if ($statusFilter) {
    $where .= " AND c.status = ?";
    $params[] = $statusFilter;
}

// Fetch codes
$codes = db()->fetchAll("
    SELECT c.*, p.display_name as plan_name 
    FROM premium_codes c 
    JOIN plans p ON c.plan_id = p.id 
    $where
    ORDER BY c.created_at DESC 
    LIMIT 500
", $params);

// Fetch stats
$stats = db()->fetchOne("
    SELECT 
        COUNT(*) as total_codes,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_codes,
        SUM(CASE WHEN status = 'used' THEN 1 ELSE 0 END) as used_codes
    FROM premium_codes
");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration des Codes - BUILD.CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/dashboard.css">
    <style>
        .admin-layout { padding: 40px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
        .card { background: var(--surface); padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid var(--border); margin-bottom: 20px;}
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 0.9rem; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid var(--border); }
        .table th { background: var(--surface-2); color: var(--text-muted); font-weight: 600; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; }
        .status-active { background: rgba(16,185,129,0.1); color: var(--success); }
        .status-used { background: rgba(59,130,246,0.1); color: #3b82f6; }
        .status-expired { background: rgba(245,158,11,0.1); color: var(--warning); }
        .status-disabled { background: rgba(239,68,68,0.1); color: var(--error); }
        .form-group { margin-bottom: 15px; }
        .form-label { display: block; margin-bottom: 5px; font-weight: 500; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface-2); color: var(--text); }
        .btn-danger { background: var(--error); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; }
        .btn-danger:hover { background: #b91c1c; }
    </style>
</head>
<body style="background: var(--bg); color: var(--text);">
<div class="admin-layout">
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>👑 Administration : Codes Premium</h1>
        <div>
            <a href="<?= APP_URL ?>/admin/export-codes.php" class="btn btn-ghost" style="margin-right:10px;">📥 Exporter CSV</a>
            <a href="<?= APP_URL ?>/dashboard/" class="btn btn-ghost">← Retour au Dashboard</a>
        </div>
    </div>

    <?php if ($successMsg): ?><div style="padding:15px; background: rgba(16,185,129,0.1); color:var(--success); border:1px solid var(--success); border-radius:6px; margin-bottom:20px;"><?= $successMsg ?></div><?php endif; ?>
    <?php if ($errorMsg): ?><div style="padding:15px; background: rgba(239,68,68,0.1); color:var(--error); border:1px solid var(--error); border-radius:6px; margin-bottom:20px;"><?= $errorMsg ?></div><?php endif; ?>

    <div class="grid-2">
        <div>
            <div class="card">
                <h3>📊 Statistiques des codes</h3>
                <ul style="list-style: none; padding: 0; margin-top:15px; line-height: 1.8;">
                    <li>Total générés : <strong><?= $stats['total_codes'] ?? 0 ?></strong></li>
                    <li>Actifs / Disponibles : <strong style="color:var(--success)"><?= $stats['active_codes'] ?? 0 ?></strong></li>
                    <li>Utilisés : <strong style="color:#3b82f6"><?= $stats['used_codes'] ?? 0 ?></strong></li>
                </ul>
            </div>

            <div class="card">
                <h3>➕ Générer des codes</h3>
                <form method="POST" style="margin-top:15px;">
                    <?= csrfField() ?>
                    <input type="hidden" name="action" value="generate">
                    <div class="form-group">
                        <label class="form-label">Plan associé</label>
                        <select name="plan_id" class="form-control" required>
                            <?php foreach ($plans as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['display_name']) ?> (<?= $p['price_monthly'] ?> FCFA)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durée (Mois)</label>
                        <input type="number" name="duration" value="1" min="1" max="60" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Utilisations Max par code</label>
                        <input type="number" name="max_uses" value="1" min="1" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nombre de codes à générer</label>
                        <input type="number" name="count" value="1" min="1" max="500" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date d'expiration (Optionnel)</label>
                        <input type="date" name="expires_at" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%">Générer</button>
                </form>
            </div>
        </div>

        <div class="card" style="overflow-x: auto;">
            <div style="display:flex; justify-content: space-between; align-items: center;">
                <h3>📋 Liste des codes générés (Max 500)</h3>
                <form method="GET" style="display:flex; gap:10px;">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Actifs</option>
                        <option value="used" <?= $statusFilter === 'used' ? 'selected' : '' ?>>Utilisés</option>
                        <option value="disabled" <?= $statusFilter === 'disabled' ? 'selected' : '' ?>>Désactivés</option>
                    </select>
                </form>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Plan & Durée</th>
                        <th>Utilisations</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($codes as $c): ?>
                    <tr>
                        <td><strong style="letter-spacing:1px; font-family:monospace;"><?= htmlspecialchars($c['code']) ?></strong></td>
                        <td><?= htmlspecialchars($c['plan_name']) ?><br><small style="color:var(--text-muted)"><?= $c['duration_months'] ?> mois</small></td>
                        <td><?= $c['used_count'] ?> / <?= $c['max_uses'] ?></td>
                        <td>
                            <span class="status-badge status-<?= $c['status'] ?>"><?= ucfirst($c['status']) ?></span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                        <td>
                            <?php if ($c['status'] === 'active'): ?>
                            <form method="POST" style="display:inline;">
                                <?= csrfField() ?>
                                <input type="hidden" name="action" value="disable">
                                <input type="hidden" name="code_id" value="<?= $c['id'] ?>">
                                <button type="submit" class="btn-danger" onclick="return confirm('Désactiver ce code ?');">Désactiver</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($codes)): ?>
                    <tr><td colspan="6" style="text-align:center; padding: 20px;">Aucun code trouvé.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
