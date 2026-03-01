<?php
// Composant barre de navigation pour les pages du tableau de bord
$pageNames = [
    'dashboard'  => 'Tableau de bord',
    'cv-builder' => 'Créateur de CV',
    'templates'  => 'Modèles',
    'portfolio'  => 'Portfolio',
    'analytics'  => 'Statistiques',
    'upgrade'    => 'Premium',
    'settings'   => 'Paramètres',
];
$currentPageName = $pageNames[$activePage ?? ''] ?? ($pageTitle ?? 'Dashboard');
?>
<div class="topbar">
    <div class="topbar-left">
        <button class="topbar-menu-btn" id="menuBtn" onclick="document.querySelector('.sidebar').classList.toggle('open')">☰</button>
        <div class="breadcrumb">
            <a href="<?= APP_URL ?>/dashboard/index.php">Accueil</a>
            <?php if (($activePage ?? '') !== 'dashboard'): ?>
            <span class="breadcrumb-sep">›</span>
            <span><?= htmlspecialchars($currentPageName) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="topbar-right">
        <a href="<?= APP_URL ?>/u/<?= htmlspecialchars($username) ?>" target="_blank" class="topbar-portfolio-link" title="Voir le portfolio">
            🌐 Mon Portfolio
        </a>
        <div class="topbar-avatar" title="<?= htmlspecialchars($fullName) ?>">
            <?= strtoupper(substr($fullName, 0, 1)) ?>
        </div>
    </div>
</div>
<style>
.topbar { display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; height: var(--topbar-h); border-bottom: 1px solid var(--border); background: var(--surface); flex-shrink: 0; }
.topbar-left  { display: flex; align-items: center; gap: 1rem; }
.topbar-right { display: flex; align-items: center; gap: 1rem; }
.topbar-menu-btn { background: none; border: none; color: var(--text-muted); font-size: 1.3rem; cursor: pointer; display: none; padding: 0.3rem; border-radius: 6px; }
.breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; }
.breadcrumb a { color: var(--text-muted); text-decoration: none; }
.breadcrumb a:hover { color: var(--text); }
.breadcrumb-sep { color: var(--text-muted); }
.breadcrumb span { color: var(--text); font-weight: 500; }
.topbar-portfolio-link { font-size: 0.82rem; color: var(--text-muted); text-decoration: none; padding: 0.35rem 0.75rem; border-radius: 20px; border: 1px solid var(--border); transition: all 0.2s; }
.topbar-portfolio-link:hover { border-color: var(--primary); color: var(--primary); }
.topbar-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--purple)); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; cursor: pointer; }
@media (max-width: 768px) { .topbar-menu-btn { display: flex; } .topbar-portfolio-link { display: none; } }
</style>
