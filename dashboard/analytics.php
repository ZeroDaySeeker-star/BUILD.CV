<?php
// Tableau de bord : page Statistiques
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';

// Statistiques des visites du portfolio
$totalVisits     = db()->fetchOne('SELECT COUNT(*) as c FROM profile_visits WHERE user_id = ?', [$userId])['c'] ?? 0;
$todayVisits     = db()->fetchOne('SELECT COUNT(*) as c FROM profile_visits WHERE user_id = ? AND visit_date = CURDATE()', [$userId])['c'] ?? 0;
$weekVisits      = db()->fetchOne('SELECT COUNT(*) as c FROM profile_visits WHERE user_id = ? AND visit_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)', [$userId])['c'] ?? 0;

// Données quotidiennes des 30 derniers jours
$chartData = db()->fetchAll(
    'SELECT visit_date as d, COUNT(*) as v FROM profile_visits
     WHERE user_id = ? AND visit_date >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)
     GROUP BY visit_date ORDER BY visit_date ASC',
    [$userId]
);

// Remplir les dates manquantes
$filled = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $filled[$date] = 0;
}
foreach ($chartData as $r) {
    $filled[$r['d']] = (int)$r['v'];
}

// Données des sources (Referrer) pour le camembert
$sourceData = db()->fetchAll(
    "SELECT CASE 
        WHEN referrer LIKE '%linkedin.com%' THEN 'LinkedIn'
        WHEN referrer LIKE '%facebook.com%' OR referrer LIKE '%t.co%' THEN 'Réseaux Sociaux'
        WHEN referrer LIKE '%google.com%' THEN 'Recherche'
        WHEN referrer IS NULL OR referrer = '' THEN 'Direct'
        ELSE 'Autre'
    END as source, COUNT(*) as count 
    FROM profile_visits WHERE user_id = ? GROUP BY source",
    [$userId]
);

$pageTitle = 'Statistiques';
$activePage = 'analytics';
include __DIR__ . '/../includes/head.php';
?>
<div class="dashboard-layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="page-content">
            <div class="page-header">
                <h1>Statistiques du portfolio</h1>
                <p>Suivez qui consulte votre portfolio</p>
            </div>

            <?php if ($planLevel == 1): ?>
            <div class="upgrade-banner" style="margin-top: 2rem; padding: 3rem; text-align: center; border: 1px solid var(--primary); background: rgba(99,102,241,0.05); border-radius: 12px;">
                <h2 style="margin-bottom: 1rem; color: #a5b4fc;">Fonctionnalité Premium 🔒</h2>
                <p style="margin-bottom: 2rem; font-size: 1.1rem; color: var(--text-muted);">
                    Le module de statistiques avancées (suivi des visites, sources de trafic) est réservé aux abonnés Standard et Premium.
                </p>
                <a href="upgrade.php" class="btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">Débloquer les Statistiques</a>
            </div>
            <?php else: ?>

            <?php if (!$isPremium): ?>
            <div class="upgrade-banner" style="margin-bottom:1.5rem">
                <p>📊 Passez au Premium pour des statistiques détaillées, les emplacements des visiteurs et le suivi des sources de trafic.</p>
                <a href="upgrade.php" class="btn-primary">Passer au Premium</a>
            </div>
            <?php endif; ?>

            <!-- Ligne de statistiques -->
            <div class="stats-row" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(99,102,241,0.15);color:#6366f1">👁</div>
                    <div class="stat-body">
                        <div class="stat-value"><?= number_format($totalVisits) ?></div>
                        <div class="stat-label">Visites totales</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(16,185,129,0.15);color:#10b981">📅</div>
                    <div class="stat-body">
                        <div class="stat-value"><?= number_format($todayVisits) ?></div>
                        <div class="stat-label">Visites aujourd'hui</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(245,158,11,0.15);color:#f59e0b">📈</div>
                    <div class="stat-body">
                        <div class="stat-value"><?= number_format($weekVisits) ?></div>
                        <div class="stat-label">Cette semaine</div>
                    </div>
                </div>
            </div>

            <!-- Graphique -->
            <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; margin-bottom:2rem;">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Visiteurs – 30 derniers jours</h3>
                    </div>
                    <div class="chart-body" style="height:300px;">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Sources <span style="font-size:0.7rem; background:var(--primary); color:white; padding:1px 5px; border-radius:4px; margin-left:5px;">Pro</span></h3>
                    </div>
                    <div class="chart-body" style="height:300px; display:flex; align-items:center; justify-content:center;">
                        <?php if ($_SESSION['plan_level'] < 3): ?>
                            <div style="text-align:center; padding:1rem;">
                                <span style="font-size:1.5rem;">🔒</span>
                                <p style="font-size:0.8rem; color:var(--text-muted); margin-top:0.5rem;">Passez au Premium pour voir vos sources de trafic.</p>
                            </div>
                        <?php else: ?>
                            <canvas id="sourceChart"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($isPremium): ?>
            <div class="card" style="margin-top:1.5rem;padding:1.5rem">
                <h3 style="margin-bottom:1rem">Visites récentes</h3>
                <?php
                $recentVisits = db()->fetchAll(
                    'SELECT visit_date, ip_address, referrer FROM profile_visits WHERE user_id = ? ORDER BY id DESC LIMIT 20',
                    [$userId]
                );
                ?>
                <?php if ($recentVisits): ?>
                <div style="overflow-x:auto">
                    <table style="width:100%;font-size:0.85rem;border-collapse:collapse">
                        <thead>
                            <tr style="border-bottom:1px solid var(--border);text-align:left;color:var(--text-muted)">
                                <th style="padding:0.5rem">Date</th>
                                <th style="padding:0.5rem">Adresse IP</th>
                                <th style="padding:0.5rem">Source</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentVisits as $v): ?>
                            <tr style="border-bottom:1px solid var(--border)">
                                <td style="padding:0.5rem;color:var(--text-muted)"><?= htmlspecialchars($v['visit_date']) ?></td>
                                <td style="padding:0.5rem"><?= htmlspecialchars($v['ip_address'] ?? '—') ?></td>
                                <td style="padding:0.5rem;color:var(--text-muted)"><?= htmlspecialchars($v['referrer'] ?: 'Direct') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p style="color:var(--text-muted);font-size:0.88rem">Aucune visite enregistrée pour l'instant. Partagez votre lien portfolio !</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = <?= json_encode(array_map(fn($d) => date('d M', strtotime($d)), array_keys($filled))) ?>;
const data   = <?= json_encode(array_values($filled)) ?>;

new Chart(document.getElementById('visitorChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Visiteurs',
            data,
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,0.08)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointRadius: 3,
            pointBackgroundColor: '#6366f1',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { color: '#6b7280', maxTicksLimit: 10, font: { size: 11 } }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { color: '#6b7280', font: { size: 11 }, stepSize: 1 }
            }
        }
    }
});

<?php if ($_SESSION['plan_level'] >= 3): ?>
const sLabels = <?= json_encode(array_column($sourceData, 'source')) ?>;
const sValues = <?= json_encode(array_column($sourceData, 'count')) ?>;

new Chart(document.getElementById('sourceChart'), {
    type: 'doughnut',
    data: {
        labels: sLabels,
        datasets: [{
            data: sValues,
            backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { color: '#6b7280', usePointStyle: true, font: { size: 10 } } }
        },
        cutout: '70%'
    }
});
<?php endif; ?>
</script>
<?php endif; ?>
<?php include __DIR__ . '/../includes/foot.php'; ?>
