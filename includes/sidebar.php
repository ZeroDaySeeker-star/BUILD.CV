<?php
// Inclusion partielle : barre latérale
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$userId       = $_SESSION['user_id'];
$userName     = htmlspecialchars($_SESSION['username']);
$fullName     = htmlspecialchars($_SESSION['full_name']);
$plan         = $_SESSION['plan'];

// Charger la photo de profil
$photo = $profile['profile_photo'] ?? null;

function nav_item($href, $icon, $label, $page, $current, $badge = '') {
    $active = ($current === $page) ? 'active' : '';
    $b = $badge ? '<span class="nav-badge">' . $badge . '</span>' : '';
    echo "<a href=\"$href\" class=\"nav-item $active\"><span class=\"nav-icon\">$icon</span>$label$b</a>";
}
?>
<aside class="sidebar" id="sidebar">
    <a href="<?= APP_URL ?>/dashboard/" class="sidebar-brand">
        <span class="sidebar-logo-icon">⚡</span>
        <span class="sidebar-logo-text">BUILD<span class="sidebar-logo-dot">.CV</span></span>
    </a>

    <nav class="sidebar-nav">
        <div class="nav-label">Principal</div>
        <?php nav_item(APP_URL.'/dashboard/', '🏠', 'Tableau de bord', 'index', $current_page); ?>
        <?php nav_item(APP_URL.'/dashboard/cv-builder.php', '📝', 'Créateur de CV', 'cv-builder', $current_page); ?>
        <?php nav_item(APP_URL.'/dashboard/portfolio.php', '🌐', 'Portfolio', 'portfolio', $current_page); ?>
        <?php nav_item(APP_URL.'/dashboard/templates.php', '🎨', 'Modèles', 'templates', $current_page); ?>
        <?php 
        $aiBadge = ($plan === 'free') ? 'Std/Prem' : '';
        nav_item(APP_URL.'/dashboard/ai-tools.php', '✨', 'Outils IA', 'ai-tools', $current_page, $aiBadge); 
        ?>

        <?php 
        $trackerBadge = ($plan !== 'premium') ? '⭐ Pro' : '';
        nav_item(APP_URL.'/dashboard/application-tracker.php', '📋', 'Candidatures', 'application-tracker', $current_page, $trackerBadge); 
        ?>

        <?php 
        $msgBadge = ($plan !== 'premium') ? '⭐ Pro' : '';
        nav_item(APP_URL.'/dashboard/messages.php', '📩', 'Messages', 'messages', $current_page, $msgBadge); 
        ?>

        <div class="nav-label" style="margin-top:0.75rem;">Outils</div>
        <?php nav_item(APP_URL.'/dashboard/analytics.php', '📊', 'Statistiques', 'analytics', $current_page); ?>
        <?php nav_item(APP_URL.'/dashboard/settings.php', '⚙️', 'Paramètres', 'settings', $current_page); ?>

        <?php if ($plan === 'free'): ?>
        <div class="nav-label" style="margin-top:0.75rem;">Compte</div>
        <a href="<?= APP_URL ?>/dashboard/upgrade.php" class="nav-item" style="color:var(--warning);">
            <span class="nav-icon">⭐</span>Passer au Premium
        </a>
        <?php nav_item(APP_URL.'/dashboard/redeem.php', '🔑', 'Utiliser un code', 'redeem', $current_page); ?>
        <?php else: ?>
        <div class="nav-label" style="margin-top:0.75rem;">Compte</div>
        <?php nav_item(APP_URL.'/dashboard/redeem.php', '🔑', 'Utiliser un code', 'redeem', $current_page); ?>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= APP_URL ?>/dashboard/settings.php" class="sidebar-user">
            <div class="user-avatar">
                <?php if ($photo && file_exists(UPLOAD_DIR . $photo)): ?>
                    <img src="<?= UPLOAD_URL . $photo ?>" alt="Avatar" loading="lazy">
                <?php else: ?>
                    <?= strtoupper(substr($fullName, 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= $fullName ?></div>
                <div class="user-plan plan-<?= $plan ?>">
                    <?php
                    if ($plan === 'premium') echo '⭐ Premium';
                    elseif ($plan === 'standard') echo '💎 Standard';
                    else echo '🔵 Offre Gratuite';
                    ?>
                </div>
            </div>
        </a>
        <a href="<?= APP_URL ?>/auth/logout.php" class="nav-item" style="margin-top:0.5rem;color:var(--error);">
            <span class="nav-icon">🚪</span>Déconnexion
        </a>
    </div>
</aside>
