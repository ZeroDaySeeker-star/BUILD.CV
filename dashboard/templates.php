<?php
// Tableau de bord : page de sélection des modèles
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';

$profile = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]);
$currentCvTemplate   = $profile['cv_template'] ?? 'minimal';
$currentPortTemplate = $profile['portfolio_template'] ?? 'minimal';

$isPremium = ($_SESSION['plan_name'] ?? 'free') === 'premium';
$planLevel = $_SESSION['plan_level'] ?? 1;

$badgeLevels = ['Gratuit' => 1, 'Standard' => 2, 'Premium' => 3];

$cvTemplates = [
    ['id' => 'minimal',      'name' => 'Sobre',         'desc' => 'Classique épuré, une seule colonne',       'badge' => 'Gratuit',   'color' => '#1a1a1a'],
    ['id' => 'professional', 'name' => 'Professionnel', 'desc' => 'Deux colonnes avec barre latérale marine', 'badge' => 'Gratuit',   'color' => '#1e3a5f'],
    ['id' => 'modern',       'name' => 'Moderne',       'desc' => 'Design contemporain avec header coloré',    'badge' => 'Standard', 'color' => '#667eea'],
    ['id' => 'compact',      'name' => 'Compact',       'desc' => 'Une page optimisée, parfait pour débuter',  'badge' => 'Standard', 'color' => '#34495e'],
    ['id' => 'creative',     'name' => 'Créatif',       'desc' => 'Barre latérale indigo moderne + Inter',    'badge' => 'Standard', 'color' => '#6366f1'],
    ['id' => 'startup',      'name' => 'Startup',       'desc' => 'Style Canva moderne avec barre latérale colorée', 'badge' => 'Standard', 'color' => '#0f172a'],
    ['id' => 'timeline',     'name' => 'Timeline',      'desc' => 'Moderne avec connecteur vertical pour vos expériences', 'badge' => 'Standard', 'color' => '#6366f1'],
    ['id' => 'neoretro',     'name' => 'Neo-Retro',     'desc' => 'Style audacieux avec bordures épaisses et tons pastels', 'badge' => 'Standard', 'color' => '#fefce8'],
    ['id' => 'executive',    'name' => 'Executive',     'desc' => 'Sérieux et prestigieux, idéal pour les cadres et leaders', 'badge' => 'Standard',  'color' => '#1e293b'],
    ['id' => 'elegant',      'name' => 'Elegant',       'desc' => 'Style éditorial magazine avec typographies de luxe', 'badge' => 'Standard',  'color' => '#fffcf2'],
    ['id' => 'canva-pro-dark','name' => 'Canva Pro',    'desc' => 'Look ultra-sombre et moderne inspiré des meilleurs designs Canva', 'badge' => 'Premium', 'color' => '#0f172a'],
    ['id' => 'canva-elegant-dark', 'name' => 'Élégant Sombre', 'desc' => 'Design sombre premium avec dégradé subtil pour leaders', 'badge' => 'Premium', 'color' => '#23252a'],
    ['id' => 'ceo-luxury', 'name' => 'CEO Luxury', 'desc' => 'Bleu marine profond et dorures pour cadres dirigeants', 'badge' => 'Premium', 'color' => '#0a192f'],
    ['id' => 'creative-glass', 'name' => 'Creative Glass', 'desc' => 'Glassmorphism et dégradés vibrants pour créatifs', 'badge' => 'Premium', 'color' => '#ed64a6'],
    ['id' => 'tech-lead', 'name' => 'Tech Lead', 'desc' => 'Inspiré des éditeurs de code, idéal pour développeurs', 'badge' => 'Premium', 'color' => '#1e1e1e'],
    ['id' => 'harvard', 'name' => 'Harvard Academic', 'desc' => 'Classicisme académique strict digne des grandes universités', 'badge' => 'Premium', 'color' => '#f8f9fa'],
    ['id' => 'marketing-pro', 'name' => 'Marketing Pro', 'desc' => 'Asymétrique et coloré pour profils créatifs', 'badge' => 'Premium', 'color' => '#ff6b6b'],
    ['id' => 'data-viz', 'name' => 'Data Analyst', 'desc' => 'Style grille et dashboard pour data scientists', 'badge' => 'Premium', 'color' => '#cbd5e1'],
    ['id' => 'aesthetic-minimal', 'name' => 'Ultra Minimal', 'desc' => 'Extrême pureté, marges larges et typographie fine', 'badge' => 'Premium', 'color' => '#ffffff'],
    ['id' => 'neon-cyber', 'name' => 'Neon Cyber', 'desc' => 'Thème sombre cyberpunk avec accents néons cyan et rose', 'badge' => 'Premium', 'color' => '#00ffff'],
];

$portTemplates = [
    ['id' => 'minimal',   'name' => 'Sobre',       'desc' => 'Professionnel épuré avec animations',   'badge' => 'Gratuit',  'color' => '#ffffff'],
    ['id' => 'developer', 'name' => 'Developer',   'desc' => 'Thème sombre style code terminal',      'badge' => 'Gratuit',  'color' => '#0d1117'],
    ['id' => 'dark',      'name' => 'Dark Lux',    'desc' => 'Élégance nocturne et contrastes forts', 'badge' => 'Standard', 'color' => '#111111'],
    ['id' => 'gallery',   'name' => 'Galerie',     'desc' => 'Focus sur les images et les projets',  'badge' => 'Standard', 'color' => '#f8f9fa'],
    ['id' => 'agency',    'name' => 'Agency',      'desc' => 'Audacieux et visuel avec grille impactante', 'badge' => 'Standard', 'color' => '#ff3e3e'],
    ['id' => 'architect', 'name' => 'Architect',   'desc' => 'Minimaliste et monochrome structural',  'badge' => 'Standard', 'color' => '#ffffff'],
    ['id' => 'cyber',     'name' => 'Cyber',       'desc' => 'Néon rétro-futuriste et glitchy hack',  'badge' => 'Standard', 'color' => '#00f3ff'],
    ['id' => 'journal',   'name' => 'Journal',     'desc' => 'Chaleureux avec esthétique papier',    'badge' => 'Standard', 'color' => '#fdfaf7'],
    ['id' => 'corporate', 'name' => 'Corporate',   'desc' => 'Sérieux et structuré pour la confiance', 'badge' => 'Standard', 'color' => '#1e3a8a'],
    ['id' => 'glass',     'name' => 'Glass',       'desc' => 'Transparence et dégradés vibrants',    'badge' => 'Standard', 'color' => '#818cf8'],
    ['id' => '3d-space',     'name' => '3D Space',      'desc' => 'Thème cosmique sombre, cartes flottantes avec profondeur',   'badge' => 'Premium',   'color' => '#050505'],
    ['id' => 'bento-grid',   'name' => 'Bento Grid',    'desc' => 'Style Apple, asymétrique, coins très arrondis, ombres',      'badge' => 'Premium',   'color' => '#f5f5f7'],
    ['id' => 'retro-brutalism','name' => 'Neo Brutalist','desc' => 'Neo-brutalisme, bords sharply carrés, contraste jaune/noir','badge' => 'Premium',   'color' => '#fdf2d0'],
    ['id' => 'photographer', 'name' => 'Photographer',  'desc' => 'Axé sur l\'image, typographie fine, galeries horizontales',  'badge' => 'Premium',   'color' => '#111111'],
    ['id' => 'terminal-hacker','name' => 'Terminal OS', 'desc' => 'Effet console de commande, texte vert sur fond noir brut',   'badge' => 'Premium',   'color' => '#00ff00'],
    ['id' => 'elegant-serif','name' => 'Éditorial Serif','desc' => 'Style éditorial haut de gamme, couleurs douces, élégance',  'badge' => 'Premium',   'color' => '#f9f6f0'],
    ['id' => 'neo-pop',      'name' => 'Neo Pop Art',   'desc' => 'Pop-art, couleurs primaires vives, bordures noires épaisses','badge' => 'Premium',   'color' => '#fff100'],
    ['id' => 'minimal-notion','name' => 'Minimal Docs',  'desc' => 'Ultra-propre, noir et blanc strict, interface type Notion', 'badge' => 'Premium',   'color' => '#ffffff'],
    ['id' => 'creative-split','name' => 'Split Screen',  'desc' => 'Écran coupé en 2, texte fixe vs visuels défilants.',        'badge' => 'Premium',   'color' => '#171717'],
    ['id' => 'gradient-mesh','name' => 'Gradient Mesh', 'desc' => 'Fond dégradé fluide de dingue, cartes translucides Glass',   'badge' => 'Premium',   'color' => '#c471ed'],
];

// Gérer le changement de modèle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash'] = 'Erreur : jeton invalide.';
        header('Location: templates.php');
        exit;
    }
    
    $cvTpl   = $_POST['cv_template']        ?? $currentCvTemplate;
    $portTpl = $_POST['portfolio_template'] ?? $currentPortTemplate;
    $allowed_cv   = ['minimal','professional','modern','compact','creative','startup','timeline','neoretro','executive','elegant','canva-pro-dark','canva-elegant-dark','ceo-luxury','creative-glass','tech-lead','harvard','marketing-pro','data-viz','aesthetic-minimal','neon-cyber'];
    $allowed_port = ['minimal','developer','dark','gallery','agency','architect','cyber','journal','corporate','glass','3d-space','bento-grid','retro-brutalism','photographer','terminal-hacker','elegant-serif','neo-pop','minimal-notion','creative-split','gradient-mesh'];
    if (!in_array($cvTpl, $allowed_cv))     $cvTpl   = 'minimal';
    if (!in_array($portTpl, $allowed_port)) $portTpl = 'minimal';

    // Validation côté serveur des droits du plan
    $allTpl = array_merge($cvTemplates, $portTemplates);
    $targetTpl = null;
    foreach($allTpl as $t) {
        if ($t['id'] === $cvTpl) {
            $requiredLevel = $badgeLevels[$t['badge']] ?? 1;
            if ($planLevel < $requiredLevel) {
                $_SESSION['flash'] = 'Erreur : Votre plan ne permet pas d\'utiliser ce modèle CV.';
                header('Location: templates.php');
                exit;
            }
        }
        if ($t['id'] === $portTpl) {
            $requiredLevel = $badgeLevels[$t['badge']] ?? 1;
            if ($planLevel < $requiredLevel) {
                $_SESSION['flash'] = 'Erreur : Votre plan ne permet pas d\'utiliser ce modèle Portfolio.';
                header('Location: templates.php');
                exit;
            }
        }
    }

    // INSERT si pas encore de profil, sinon UPDATE
    db()->query(
        'INSERT INTO profiles (user_id, cv_template, portfolio_template)
         VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE cv_template = VALUES(cv_template), portfolio_template = VALUES(portfolio_template)',
        [$userId, $cvTpl, $portTpl]
    );

    $_SESSION['flash'] = 'Modèles mis à jour !';
    header('Location: templates.php');
    exit;
}

$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);


$pageTitle = 'Modèles';
$activePage = 'templates';
include __DIR__ . '/../includes/head.php';
?>
<div class="dashboard-layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="page-content">
            <?php if ($flash): ?>
            <div class="alert alert-success" style="margin-bottom:1.5rem;"><?= htmlspecialchars($flash) ?></div>
            <?php endif; ?>

            <div class="page-header">
                <h1>Modèles</h1>
                <p>Choisissez votre style de CV et de portfolio. Changez à tout moment.</p>
            </div>

            <form method="POST">
                <?= csrfField() ?>
                <!-- Modèles de CV -->
                <div class="templates-section">
                    <h2 class="templates-section-title">📄 Modèles de CV</h2>
                    <p class="templates-section-desc">Appliqué lors de la génération de votre PDF ou de l'aperçu de votre CV.</p>
                    <div class="templates-grid">
                        <?php foreach ($cvTemplates as $tpl): 
                            $reqLevel = $badgeLevels[$tpl['badge']] ?? 1;
                            $isLocked = ($planLevel < $reqLevel);
                        ?>
                        <label class="template-card <?= $currentCvTemplate === $tpl['id'] ? 'selected' : '' ?> <?= $isLocked ? 'locked' : '' ?>">
                            <input type="radio" name="cv_template" value="<?= $tpl['id'] ?>"
                                <?= $currentCvTemplate === $tpl['id'] ? 'checked' : '' ?>
                                <?= $isLocked ? 'disabled' : '' ?>
                                class="hidden-radio">
                            <div class="template-preview" style="background:<?= $tpl['color'] ?>">
                                <div class="template-mock cv-mock">
                                    <div class="mock-line" style="width:30%"></div>
                                    <div class="mock-line" style="width:70%"></div>
                                    <div class="mock-line" style="width:80%"></div>
                                </div>
                            </div>
                            <div class="template-info">
                                <div class="template-name-row">
                                    <strong><?= $tpl['name'] ?></strong>
                                    <span class="badge <?= $tpl['badge'] === 'Standard' ? 'premium' : 'free' ?>"><?= strtoupper($tpl['badge']) ?></span>
                                </div>
                                <p><?= $tpl['desc'] ?></p>
                                <?php if ($isLocked): ?>
                                <a href="upgrade.php" class="upgrade-link">Passer au plan <?= $tpl['badge'] ?> pour débloquer →</a>
                                <?php endif; ?>
                            </div>
                            <div class="template-check">✓</div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Modèles de Portfolio -->
                <div class="templates-section" style="margin-top:2.5rem">
                    <h2 class="templates-section-title">🌐 Modèles de Portfolio</h2>
                    <p class="templates-section-desc">Appliqué à votre portfolio public sur <code><?= APP_URL ?>/u/<?= htmlspecialchars($username) ?></code></p>
                    <div class="templates-grid">
                        <?php foreach ($portTemplates as $tpl): 
                            $reqLevel = $badgeLevels[$tpl['badge']] ?? 1;
                            $isLocked = ($planLevel < $reqLevel);
                        ?>
                        <label class="template-card <?= $currentPortTemplate === $tpl['id'] ? 'selected' : '' ?> <?= $isLocked ? 'locked' : '' ?>">
                            <input type="radio" name="portfolio_template" value="<?= $tpl['id'] ?>"
                                <?= $currentPortTemplate === $tpl['id'] ? 'checked' : '' ?>
                                <?= $isLocked ? 'disabled' : '' ?>
                                class="hidden-radio">
                            <div class="template-preview" style="background:<?= $tpl['id'] === 'developer' ? '#0d1117' : '#f0f4ff' ?>">
                                <div class="template-mock portfolio-mock">
                                    <div class="mock-hero"></div>
                                    <div class="mock-cards">
                                        <div class="mock-card"></div><div class="mock-card"></div><div class="mock-card"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="template-info">
                                <div class="template-name-row">
                                    <strong><?= $tpl['name'] ?></strong>
                                    <span class="badge <?= $tpl['badge'] === 'Standard' ? 'premium' : 'free' ?>"><?= strtoupper($tpl['badge']) ?></span>
                                </div>
                                <p><?= $tpl['desc'] ?></p>
                                <?php if ($isLocked): ?>
                                <a href="upgrade.php" class="upgrade-link">Passer au plan <?= $tpl['badge'] ?> pour débloquer →</a>
                                <?php endif; ?>
                            </div>
                            <div class="template-check">✓</div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-top:2rem">
                    <button type="submit" class="btn-primary">Enregistrer les modèles</button>
                    <a href="cv-builder.php" class="btn-secondary" style="margin-left:0.75rem">Aperçu du CV</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/foot.php'; ?>
<script>
// Mise à jour visuelle immédiate au clic
document.querySelectorAll('.hidden-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        // Trouver toutes les cartes du même groupe (cv ou portfolio)
        var name = this.name;
        document.querySelectorAll('input[name="' + name + '"]').forEach(function(r) {
            r.closest('.template-card').classList.remove('selected');
        });
        this.closest('.template-card').classList.add('selected');
    });
});
</script>

