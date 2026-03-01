<?php
// Interface Admin - Génération de codes d'abonnement
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth-check.php';
require_once __DIR__ . '/../classes/PremiumCode.php';

$message = '';
$generatedCodes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    $planId = intval($_POST['plan_id'] ?? 2);
    $duration = intval($_POST['duration_months'] ?? 1);
    $count = intval($_POST['count'] ?? 1);

    try {
        $generatedCodes = PremiumCode::generate($planId, $duration, 1, null, $count);
        if ($generatedCodes) {
            $message = "<div class='success-msg'>" . count($generatedCodes) . " code(s) généré(s) avec succès !</div>";
        }
    } catch (Exception $e) {
        $message = "<div class='error-msg'>Erreur : " . $e->getMessage() . "</div>";
    }
}

// Action : Révoquer un code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['revoke_code'])) {
    $codeToRevoke = $_POST['revoke_code'];
    try {
        db()->query("UPDATE premium_codes SET status = 'revoked' WHERE code = ? AND status = 'active'", [$codeToRevoke]);
        $message = "<div class='success-msg'>Le code $codeToRevoke a été désactivé.</div>";
    } catch (Exception $e) {
        $message = "<div class='error-msg'>Erreur lors de la révocation.</div>";
    }
}

// Statistiques Globales
$stats = db()->fetchOne("
    SELECT 
        COUNT(*) as total_codes,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_codes,
        SUM(CASE WHEN status = 'used' THEN 1 ELSE 0 END) as used_codes
    FROM premium_codes
");

// Récupérer les codes récents avec informations de l'utilisateur (s'il a été utilisé)
$recentCodes = db()->fetchAll("
    SELECT pc.*, p.display_name as plan_name, users.username as used_by_user
    FROM premium_codes pc 
    JOIN plans p ON p.id = pc.plan_id 
    LEFT JOIN code_usages cu ON cu.code_id = pc.id
    LEFT JOIN users ON users.id = cu.user_id
    ORDER BY pc.created_at DESC 
    LIMIT 50
");

// Si les tables n'existent pas encore, on propose de les installer
$tablesExist = true;
try {
    db()->query("SELECT 1 FROM premium_codes LIMIT 1");
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Table') !== false && strpos($e->getMessage(), 'doesn\'t exist') !== false) {
        $tablesExist = false;
        if (isset($_POST['install_db'])) {
            $sql = "
            CREATE TABLE IF NOT EXISTS premium_codes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                code VARCHAR(50) UNIQUE NOT NULL,
                plan_id INT NOT NULL,
                duration_months INT DEFAULT 1,
                used_count INT DEFAULT 0,
                max_uses INT DEFAULT 1,
                status ENUM('active', 'used', 'expired', 'revoked') DEFAULT 'active',
                expires_at DATETIME NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (plan_id) REFERENCES plans(id)
            );
            CREATE TABLE IF NOT EXISTS code_usages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                code_id INT NOT NULL,
                user_id INT NOT NULL,
                ip_address VARCHAR(45),
                used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (code_id) REFERENCES premium_codes(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            );
            CREATE TABLE IF NOT EXISTS code_attempts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ip_address VARCHAR(45) NOT NULL,
                user_id INT NULL,
                attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );";
            
            db()->getConnection()->exec($sql);
            $tablesExist = true;
            $message = "<div class='success-msg'>Tables créées avec succès ! Rafraîchissez la page.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générateur de Codes - BUILD.CV</title>
    <style>
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #06060c; color: #e8e8f0; padding: 2rem; margin: 0; }
        .container { max-width: 900px; margin: 0 auto; background: rgba(255,255,255,0.05); padding: 2rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); }
        h1, h2 { color: #6366f1; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        select, input { width: 100%; padding: 0.8rem; background: #0f1016; color: white; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; }
        .btn { background: #6366f1; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 1rem; }
        .btn:hover { background: #4f46e5; }
        .success-msg { background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; }
        .error-msg { background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #ef4444; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; }
        .code-box { background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 1.5rem; letter-spacing: 2px; text-align: center; border: 1px dashed #6366f1; user-select: all; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.8rem; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { background: rgba(255,255,255,0.05); }
        .status-active { color: #10b981; }
        .status-used { color: #8b5cf6; }
        .status-expired, .status-revoked { color: #ef4444; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: rgba(255,255,255,0.05); padding: 1.5rem; text-align: center; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); }
        .stat-card h3 { margin: 0 0 0.5rem 0; font-size: 0.9rem; color: var(--text-muted); }
        .stat-card .value { font-size: 2rem; font-weight: bold; color: white; }
        
        .btn-small { background: #ef4444; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; cursor: pointer; font-size: 0.8rem; }
        .btn-small:hover { background: #dc2626; }
        .btn-small:hover { background: #dc2626; }
        
        .tabs { display: flex; gap: 0.5rem; margin-top: 3rem; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .tab-btn { background: transparent; color: #9ca3af; border: none; padding: 0.8rem 1.5rem; cursor: pointer; font-size: 1rem; font-weight: 500; border-bottom: 2px solid transparent; }
        .tab-btn:hover { color: white; background: rgba(255,255,255,0.05); }
        .tab-btn.active { color: #6366f1; border-bottom-color: #6366f1; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Générateur de Codes - BUILD.CV</h1>
        <p>Générez un code après avoir reçu et vérifié le paiement par WhatsApp (Flooz/TMoney).</p>
        
        <div style="margin-bottom: 2rem;">
            <a href="visitors.php" class="btn" style="text-decoration: none; display: inline-block;">👁️ Voir les statistiques visiteurs</a>
        </div>
        
        <?= $message ?>

        <?php if (!$tablesExist): ?>
            <div class="error-msg">Les tables de base de données pour les codes n'existent pas.</div>
            <form method="POST">
                <button type="submit" name="install_db" class="btn">Installer les tables requises</button>
            </form>
        <?php else: ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Générés</h3>
                <div class="value"><?= $stats['total_codes'] ?? 0 ?></div>
            </div>
            <div class="stat-card" style="border-color: rgba(16, 185, 129, 0.3);">
                <h3 style="color:#10b981">Disponibles (Actifs)</h3>
                <div class="value"><?= $stats['active_codes'] ?? 0 ?></div>
            </div>
            <div class="stat-card" style="border-color: rgba(139, 92, 246, 0.3);">
                <h3 style="color:#8b5cf6">Déjà Utilisés</h3>
                <div class="value"><?= $stats['used_codes'] ?? 0 ?></div>
            </div>
        </div>
        
        <?php if (!empty($generatedCodes)): ?>
            <h2>Code(s) Généré(s) :</h2>
            <?php foreach ($generatedCodes as $code): ?>
                <div class="code-box"><?= htmlspecialchars($code) ?></div>
            <?php endforeach; ?>
            <p>Copiez ce code et envoyez-le au client sur WhatsApp.</p>
        <?php endif; ?>

        <form method="POST" style="margin-top:2rem; padding-top:2rem; border-top:1px solid rgba(255,255,255,0.1);">
            <div class="form-group">
                <label>Offre (Plan)</label>
                <select name="plan_id">
                    <option value="2">Standard (3000 FCFA)</option>
                    <option value="3">Premium (5000 FCFA)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Durée (Mois)</label>
                <input type="number" name="duration_months" value="1" min="1" max="60">
            </div>
            
            <div class="form-group">
                <label>Nombre de codes à générer</label>
                <input type="number" name="count" value="1" min="1" max="50">
            </div>
            
            <button type="submit" name="generate" class="btn">Générer le Code</button>
        </form>

        <h2 style="margin-top: 3rem;">Gérer les Codes Générés</h2>
        <?php 
        $groupedCodes = ['active' => [], 'used' => [], 'revoked' => [], 'expired' => []];
        foreach ($recentCodes as $row) {
            $groupedCodes[$row['status']][] = $row;
        }
        
        function renderCodeTable($codes) {
            if (empty($codes)) {
                echo "<p style='color:#9ca3af'>Aucun code dans cette catégorie.</p>";
                return;
            }
            echo "<table><tr><th>Code</th><th>Plan</th><th>Durée</th><th>Statut</th><th>Utilisé par</th><th>Date Création</th><th>Action</th></tr>";
            foreach ($codes as $row) {
                echo "<tr>";
                echo "<td><strong>" . htmlspecialchars($row['code']) . "</strong></td>";
                echo "<td>" . htmlspecialchars($row['plan_name'] ?? 'Inconnu') . "</td>";
                echo "<td>{$row['duration_months']} mois</td>";
                echo "<td class='status-{$row['status']}'><strong>" . strtoupper($row['status']) . "</strong></td>";
                echo "<td style='color:#a5b4fc'>" . ($row['used_by_user'] ? '@'.htmlspecialchars($row['used_by_user']) : '<span style="color:#6b7280">-</span>') . "</td>";
                echo "<td>" . date('d/m/Y H:i', strtotime($row['created_at'])) . "</td>";
                echo "<td>";
                if ($row['status'] === 'active') {
                    echo "<form method='POST' style='display:inline;' onsubmit=\"return confirm('Êtes-vous sûr de vouloir désactiver ce code ? Le client ne pourra plus l\'utiliser.');\">";
                    echo "<input type='hidden' name='revoke_code' value='" . htmlspecialchars($row['code']) . "'>";
                    echo "<button type='submit' class='btn-small'>Désactiver</button>";
                    echo "</form>";
                } else {
                    echo "<span style='color:#6b7280; font-size:0.8rem;'>Aucune</span>";
                }
                echo "</td></tr>";
            }
            echo "</table>";
        }
        ?>

        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('active')">Actifs (<?= count($groupedCodes['active']) ?>)</button>
            <button class="tab-btn" onclick="showTab('used')">Utilisés (<?= count($groupedCodes['used']) ?>)</button>
            <button class="tab-btn" onclick="showTab('revoked')">Désactivés/Expirés (<?= count($groupedCodes['revoked']) + count($groupedCodes['expired']) ?>)</button>
        </div>

        <div id="tab-active" class="tab-content active">
            <?php renderCodeTable($groupedCodes['active']); ?>
        </div>
        <div id="tab-used" class="tab-content">
            <?php renderCodeTable($groupedCodes['used']); ?>
        </div>
        <div id="tab-revoked" class="tab-content">
            <?php renderCodeTable(array_merge($groupedCodes['revoked'], $groupedCodes['expired'])); ?>
        </div>
        
        <?php endif; ?>
    </div>

    <script>
    function showTab(id) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelector(`.tab-btn[onclick="showTab('${id}')"]`).classList.add('active');
        document.getElementById('tab-' + id).classList.add('active');
    }
    </script>
</body>
</html>
