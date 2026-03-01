<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/dashboard/');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $username  = strtolower(trim($_POST['username'] ?? ''));
    $full_name = trim($_POST['full_name'] ?? '');
    $csrf      = $_POST['csrf_token'] ?? '';

    if (isset($_SESSION['last_reg_attempt']) && time() - $_SESSION['last_reg_attempt'] < 30) {
        $error = 'Veuillez patienter 30 secondes avant de tenter une nouvelle inscription.';
    } elseif (!verifyCsrfToken($csrf)) {
        $error = 'Jeton de sécurité invalide. Veuillez réessayer.';
    } elseif (!$full_name || !$username || !$email || !$password) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez saisir une adresse e-mail valide.';
    } elseif (!preg_match('/^[a-z0-9_]{3,30}$/', $username)) {
        $error = 'Le nom d\'utilisateur doit contenir 3 à 30 caractères (lettres, chiffres, tirets bas uniquement).';
    } elseif (strlen($password) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères.';
    } else {
        $existing = db()->fetchOne('SELECT id FROM users WHERE email = ? OR username = ?', [$email, $username]);
        if ($existing) {
            $error = 'Un compte avec cet e-mail ou ce nom d\'utilisateur existe déjà.';
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            db()->query(
                'INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)',
                [$username, $email, $hashed, $full_name]
            );
            $userId = db()->lastInsertId();

            // Profile Guard: créer profil et souscription automatiquement
            ProfileGuard::onUserRegistered($userId);

            $_SESSION['user_id']  = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['full_name']= $full_name;
            $_SESSION['last_reg_attempt'] = time();

            header('Location: ' . APP_URL . '/dashboard/');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Créer un compte – <?= APP_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/auth.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-left">
        <div class="auth-brand">
            <a href="<?= APP_URL ?>" class="logo-link">
                <span class="logo-icon">⚡</span>
                <span class="logo-text">BUILD<span class="logo-dot">.CV</span></span>
            </a>
        </div>
        <div class="auth-hero">
            <h1>Construisez votre histoire professionnelle.</h1>
            <p>Créez un CV et un portfolio personnel époustouflants en quelques minutes — aucune compétence en design requise.</p>
            <div class="auth-features">
                <div class="feature-item"><span class="feature-icon">✓</span> Créateur de CV professionnel</div>
                <div class="feature-item"><span class="feature-icon">✓</span> Site portfolio personnel</div>
                <div class="feature-item"><span class="feature-icon">✓</span> Contenu assisté par IA</div>
                <div class="feature-item"><span class="feature-icon">✓</span> Téléchargement PDF</div>
            </div>
        </div>
    </div>
    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Créer votre compte</h2>
                <p>Vous avez déjà un compte ? <a href="<?= APP_URL ?>/auth/login.php">Se connecter</a></p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form" id="registerForm">
                <?= csrfField() ?>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="full_name" placeholder="Jean Dupont" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Nom d'utilisateur</label>
                        <div class="input-prefix">
                            <span class="prefix">@</span>
                            <input type="text" name="username" placeholder="jeandupont" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required pattern="[a-z0-9_]{3,30}">
                        </div>
                        <small>Votre portfolio : build.cv/u/<strong id="usernamePreview">nomutilisateur</strong></small>
                    </div>
                </div>
                <div class="form-group">
                    <label>Adresse e-mail</label>
                    <input type="email" name="email" placeholder="jean@exemple.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <div class="input-suffix">
                        <input type="password" name="password" id="passwordInput" placeholder="Minimum 8 caractères" required minlength="8">
                        <button type="button" class="toggle-password" onclick="togglePassword()">👁</button>
                    </div>
                </div>
                <button type="submit" class="btn-primary btn-full">
                    <span class="btn-text">Créer mon compte</span>
                    <span class="btn-arrow">→</span>
                </button>
                <p class="auth-terms">En créant un compte, vous acceptez nos <a href="#">Conditions d'utilisation</a> et notre <a href="#">Politique de confidentialité</a>.</p>
            </form>
        </div>
    </div>
</div>
<script>
document.querySelector('[name="username"]').addEventListener('input', function() {
    document.getElementById('usernamePreview').textContent = this.value || 'nomutilisateur';
});
function togglePassword() {
    const input = document.getElementById('passwordInput');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
