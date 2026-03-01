<?php
require_once __DIR__ . '/../includes/auth-check.php';

// Charger les statistiques
$totalVisitsResult = db()->fetchOne(
    'SELECT COUNT(*) as total FROM profile_visits WHERE user_id = ?',
    [$_SESSION['user_id']]
);
$totalVisits = ($totalVisitsResult && isset($totalVisitsResult['total'])) ? $totalVisitsResult['total'] : 0;

$educationResult = db()->fetchOne('SELECT COUNT(*) as c FROM education WHERE user_id = ?', [$_SESSION['user_id']]);
$hasEducation = ($educationResult && isset($educationResult['c'])) ? $educationResult['c'] : 0;

$experienceResult = db()->fetchOne('SELECT COUNT(*) as c FROM experience WHERE user_id = ?', [$_SESSION['user_id']]);
$hasExperience = ($experienceResult && isset($experienceResult['c'])) ? $experienceResult['c'] : 0;

$skillsResult = db()->fetchOne('SELECT COUNT(*) as c FROM skills WHERE user_id = ?', [$_SESSION['user_id']]);
$hasSkills = ($skillsResult && isset($skillsResult['c'])) ? $skillsResult['c'] : 0;

$projectsResult = db()->fetchOne('SELECT COUNT(*) as c FROM projects WHERE user_id = ?', [$_SESSION['user_id']]);
$hasProjects = ($projectsResult && isset($projectsResult['c'])) ? $projectsResult['c'] : 0;

$completeness = 0;
if ($profile && $profile['full_name']) $completeness += 20;
if ($profile && $profile['summary'])   $completeness += 10;
if ($hasEducation > 0)  $completeness += 20;
if ($hasExperience > 0) $completeness += 20;
if ($hasSkills > 0)     $completeness += 15;
if ($hasProjects > 0)   $completeness += 15;

$portfolioUrl = APP_URL . '/u/' . $_SESSION['username'];
$currentTemplate = $profile['cv_template'] ?? 'minimal';

// Visites des 7 derniers jours pour le mini-graphique
$visitData = db()->fetchAll(
    'SELECT visit_date, COUNT(*) as visits 
     FROM profile_visits 
     WHERE user_id = ? AND visit_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
     GROUP BY visit_date ORDER BY visit_date ASC',
    [$_SESSION['user_id']]
);

$maxVisit = max(array_column($visitData, 'visits') ?: [0, 1]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tableau de bord – BUILD.CV</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/dashboard.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" onclick="toggleSidebar()">☰</button>
                <div>
                    <h1>Tableau de bord</h1>
                    <p>Bon retour, <?= htmlspecialchars($_SESSION['full_name']) ?> 👋</p>
                </div>
            </div>
            <div class="topbar-right">
                <a href="<?= $portfolioUrl ?>" target="_blank" class="topbar-btn topbar-btn-ghost">🌐 Voir le portfolio</a>
                <a href="<?= APP_URL ?>/dashboard/cv-builder.php" class="topbar-btn topbar-btn-primary">📋 Mon CV</a>
            </div>
        </header>

        <main class="page-content">
            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-purple">📊</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $totalVisits ?></div>
                        <div class="stat-label">Visites du portail</div>
                        <div class="stat-trend trend-up">↑ Depuis le début</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon stat-icon-cyan">✅</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $completeness ?>%</div>
                        <div class="stat-label">Complétude du CV</div>
                        <div class="stat-trend <?= $completeness >= 80 ? 'trend-up' : 'trend-neutral' ?>">
                            <?= $completeness >= 80 ? '✓ Excellent !' : 'Complétez votre profil' ?>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon stat-icon-green">🎨</div>
                    <div class="stat-info">
                        <div class="stat-value" style="text-transform:capitalize"><?= $currentTemplate ?></div>
                        <div class="stat-label">Modèle de CV actif</div>
                        <div class="stat-trend trend-neutral"><a href="<?= APP_URL ?>/dashboard/templates.php" style="color:var(--primary);text-decoration:none">Changer →</a></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon stat-icon-pink">🚀</div>
                    <div class="stat-info">
                        <div class="stat-value" style="text-transform:capitalize"><?= $_SESSION['plan'] === 'free' ? 'Gratuit' : ($_SESSION['plan'] === 'premium' ? 'Premium' : 'Standard') ?></div>
                        <div class="stat-label">Offre actuelle</div>
                        <div class="stat-trend">
                            <?php if ($_SESSION['plan'] === 'free'): ?>
                            <a href="<?= APP_URL ?>/dashboard/upgrade.php" style="color:var(--warning);text-decoration:none">Passer au Standard ⭐</a>
                            <?php else: ?>
                            <span class="trend-up">Toutes les fonctionnalités déverrouillées</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lien portfolio + Actions rapides -->
            <div class="two-col">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">🔗 Votre lien portfolio</div>
                        <span class="plan-badge plan-badge-<?= $_SESSION['plan'] ?>"><?= $_SESSION['plan'] === 'free' ? 'Gratuit' : ($_SESSION['plan'] === 'premium' ? 'Premium' : 'Standard') ?></span>
                    </div>
                    <div class="portfolio-link-box">
                        <span class="portfolio-url"><?= $portfolioUrl ?></span>
                        <button class="copy-btn" onclick="copyUrl('<?= $portfolioUrl ?>')">Copier</button>
                    </div>
                    <a href="<?= $portfolioUrl ?>" target="_blank" class="btn btn-ghost btn-sm">
                        🌐 Ouvrir le portfolio
                    </a>

                    <!-- Progression du CV -->
                    <div style="margin-top:1.5rem;">
                        <div style="display:flex;justify-content:space-between;font-size:0.82rem;margin-bottom:0.5rem;">
                            <span style="color:var(--text-muted)">Complétude du CV</span>
                            <span style="color:var(--primary);font-weight:600"><?= $completeness ?>%</span>
                        </div>
                        <div style="height:6px;background:var(--surface-3);border-radius:3px;overflow:hidden">
                            <div style="height:100%;width:<?= $completeness ?>%;background:linear-gradient(90deg,var(--primary),var(--purple));border-radius:3px;transition:width 0.5s ease;"></div>
                        </div>
                        <?php if ($completeness < 100): ?>
                        <div style="margin-top:0.75rem;font-size:0.8rem;color:var(--text-muted);">
                            <?php if (!$hasEducation): ?>• Ajoutez votre <a href="<?= APP_URL ?>/dashboard/cv-builder.php#education" style="color:var(--primary)">formation</a><br><?php endif; ?>
                            <?php if (!$hasExperience): ?>• Ajoutez votre <a href="<?= APP_URL ?>/dashboard/cv-builder.php#experience" style="color:var(--primary)">expérience professionnelle</a><br><?php endif; ?>
                            <?php if (!$hasSkills): ?>• Listez vos <a href="<?= APP_URL ?>/dashboard/cv-builder.php#skills" style="color:var(--primary)">compétences</a><br><?php endif; ?>
                            <?php if (!$hasProjects): ?>• Présentez vos <a href="<?= APP_URL ?>/dashboard/cv-builder.php#projects" style="color:var(--primary)">projets</a><?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">📈 Visiteurs du portfolio (7 derniers jours)</div>
                        <a href="<?= APP_URL ?>/dashboard/analytics.php" class="card-action">Statistiques complètes →</a>
                    </div>
                    <div class="chart-placeholder">
                        <?php if ($_SESSION['plan'] !== 'free'): ?>
                        <div class="chart-bars">
                            <?php
                            $dates = [];
                            for ($i = 6; $i >= 0; $i--) {
                                $dates[] = date('Y-m-d', strtotime("-$i days"));
                            }
                            $visitMap = [];
                            foreach ($visitData as $v) {
                                $visitMap[$v['visit_date']] = $v['visits'];
                            }
                            foreach ($dates as $date) {
                                $v = $visitMap[$date] ?? 0;
                                $h = $maxVisit > 0 ? max(4, round(($v / $maxVisit) * 100)) : 4;
                                $day = date('D', strtotime($date));
                                echo "<div class='chart-bar' style='height:{$h}%;' title='$day: $v visites'></div>";
                            }
                            ?>
                        </div>
                        <?php else: ?>
                        <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(0,0,0,0.2); border-radius: 8px; border: 1px dashed rgba(255,255,255,0.1);">
                            <span style="font-size: 2rem; margin-bottom: 0.5rem;">🔒</span>
                            <span style="font-size: 0.85rem; color: var(--text-muted); text-align: center; padding: 0 1rem;">Statistiques réservées aux membres Premium</span>
                            <a href="<?= APP_URL ?>/dashboard/upgrade.php" class="btn btn-primary btn-sm" style="margin-top: 1rem;">Débloquer</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($_SESSION['plan'] !== 'free'): ?>
                    <div style="display:flex;justify-content:space-around;font-size:0.72rem;color:var(--text-muted);margin-top:0.5rem;">
                        <?php foreach ($dates as $date): ?>
                            <span><?= date('D', strtotime($date)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card" style="margin-top:1.5rem;">
                <div class="card-header">
                    <div class="card-title">⚡ Actions rapides</div>
                </div>
                <div class="actions-grid">

                    <a href="<?= APP_URL ?>/dashboard/cv-builder.php" class="action-card">
                        <div class="action-card-icon">📝</div>
                        <div class="action-card-title">Modifier le CV</div>
                        <div class="action-card-desc">Mettre à jour vos informations</div>
                    </a>
                    <a href="<?= APP_URL ?>/api/generate-pdf.php" class="action-card">
                        <div class="action-card-icon">⬇️</div>
                        <div class="action-card-title">Télécharger le PDF</div>
                        <div class="action-card-desc">Obtenir votre CV en PDF</div>
                    </a>
                    <a href="<?= APP_URL ?>/dashboard/templates.php" class="action-card">
                        <div class="action-card-icon">🌈</div>
                        <div class="action-card-title">Changer de modèle</div>
                        <div class="action-card-desc">Choisir un nouveau design</div>
                    </a>
                    <a href="<?= $portfolioUrl ?>" target="_blank" class="action-card">
                        <div class="action-card-icon">🌐</div>
                        <div class="action-card-title">Voir le portfolio</div>
                        <div class="action-card-desc">Consulter votre page live</div>
                    </a>
                    <a href="<?= APP_URL ?>/dashboard/analytics.php" class="action-card">
                        <div class="action-card-icon">📊</div>
                        <div class="action-card-title">Statistiques</div>
                        <div class="action-card-desc">Suivre les performances</div>
                    </a>
                    <a href="<?= APP_URL ?>/dashboard/settings.php" class="action-card">
                        <div class="action-card-icon">⚙️</div>
                        <div class="action-card-title">Paramètres</div>
                        <div class="action-card-desc">Gérer votre compte</div>
                    </a>
                </div>
            </div>

            <?php if ($_SESSION['plan'] === 'free'): ?>
            <div class="upgrade-banner">
                <div class="upgrade-banner-text">
                    <h3>⭐ Débloquez les fonctionnalités Premium</h3>
                    <p>Accédez à tous les modèles, aux outils IA, au domaine personnalisé, aux statistiques avancées et bien plus encore.</p>
                </div>
                <a href="<?= APP_URL ?>/dashboard/upgrade.php" class="btn btn-primary">Passer au Premium →</a>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}
function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => showToast('Lien du portfolio copié !', 'success'));
}
function showToast(msg, type = 'info') {
    const c = document.getElementById('toastContainer');
    const t = document.createElement('div');
    t.className = `toast toast-${type}`;
    t.textContent = msg;
    c.appendChild(t);
    setTimeout(() => t.remove(), 3500);
}
</script>
</body>
</html>
