<?php
// Tableau de bord : page Paramètres du portfolio
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';

$profile = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]);
$flash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Jeton de sécurité (CSRF) invalide. Veuillez réessayer.";
    } else {
        // Le formulaire ne fait plus de mise à jour de domaine.
        // L'identifiant est défini par le nom d'utilisateur.
        $flash = 'Paramètres du portfolio enregistrés !';
    }
}

$portfolioUrl = APP_URL . '/u/' . $username;

$pageTitle = 'Paramètres du portfolio';
$activePage = 'portfolio';
include __DIR__ . '/../includes/head.php';
?>
<div class="dashboard-layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="page-content">
            <?php if ($flash): ?>
            <div class="alert alert-success" style="margin-bottom:1.5rem"><?= htmlspecialchars($flash) ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
            <div class="alert alert-error" style="margin-bottom:1.5rem"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="page-header">
                <h1>Paramètres du portfolio</h1>
                <p>Gérez votre site portfolio public</p>
            </div>

            <!-- Carte lien portfolio -->
            <div class="card" style="padding:1.5rem;margin-bottom:1.5rem">
                <h3 style="margin-bottom:0.5rem">Votre URL de portfolio</h3>
                <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:1rem">Partagez ce lien avec les recruteurs et clients.</p>
                <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center">
                    <div style="flex:1;min-width:0;background:var(--surface-2);border:1px solid var(--border);border-radius:8px;padding:0.6rem 1rem;font-family:monospace;font-size:0.88rem;color:var(--primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        <?= htmlspecialchars($portfolioUrl) ?>
                    </div>
                    <button onclick="navigator.clipboard.writeText('<?= $portfolioUrl ?>');this.textContent='Copié !';setTimeout(()=>this.textContent='Copier le lien',2000)" class="btn-secondary">Copier le lien</button>
                    <a href="<?= $portfolioUrl ?>" target="_blank" class="btn-primary">Voir →</a>
                </div>
            </div>

            <!-- Formulaire de paramètres -->
            <div class="card" style="padding:1.5rem;margin-bottom:1.5rem">
                <h3 style="margin-bottom:1.25rem">⚙️ Options du portfolio</h3>
                <form method="POST">
                    <?= csrfField() ?>


                    <!-- Nom d'utilisateur / slug -->
                    <div class="fgroup" style="margin-bottom:1.25rem">
                        <label>Identifiant portfolio</label>
                        <div style="display:flex;align-items:center;gap:0.5rem">
                            <span style="color:var(--text-muted);font-size:0.85rem"><?= htmlspecialchars(APP_URL) ?>/u/</span>
                            <input type="text" value="<?= htmlspecialchars($username) ?>" disabled style="max-width:200px;background:var(--surface-3)">
                        </div>
                        <small style="color:var(--text-muted)">Votre nom d'utilisateur détermine cette URL. Pour le modifier, allez dans les paramètres de votre compte.</small>
                    </div>

                    <?php if ($isPremium): ?>
                    <button type="submit" class="btn-primary">Enregistrer les paramètres</button>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Raccourci modèle -->
            <div class="card" style="padding:1.5rem">
                <h3 style="margin-bottom:0.5rem">Modèle de portfolio</h3>
                <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:1rem">
                    Modèle actuel : <strong><?= ucfirst($profile['portfolio_template'] ?? 'minimal') ?></strong>
                </p>
                <a href="templates.php" class="btn-secondary">Changer de modèle →</a>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/foot.php'; ?>
