<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/dashboard/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        $error = 'Jeton de sécurité invalide. Veuillez réessayer.';
    } elseif (!$email || !$password) {
        $error = 'Veuillez saisir votre e-mail et votre mot de passe.';
    } else {
        // Anti Brute-Force
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['first_login_attempt'] = time();
        }
        
        if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['first_login_attempt']) < 900) {
            $error = 'Trop de tentatives échouées. Veuillez réessayer dans 15 minutes.';
        } else {
            $user = db()->fetchOne('SELECT * FROM users WHERE email = ?', [$email]);
            if ($user && password_verify($password, $user['password'])) {
                // Success - Reset attempts
                unset($_SESSION['login_attempts']);
                unset($_SESSION['first_login_attempt']);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];

            // Profile Guard: vérifier profil et souscription
            ProfileGuard::onUserLogin($user['id']);

            // Récupérer le plan
            $plan = Plans::getUserPlan($user['id']);
            $_SESSION['plan'] = $plan['name'] ?? 'free';

            // Validate redirect URL to prevent open redirects
            $redirect = $_GET['redirect'] ?? APP_URL . '/dashboard/';
            // Only allow relative URLs or URLs within the same application
            if (!empty($redirect)) {
                $parsedUrl = parse_url($redirect);
                $appParsedUrl = parse_url(APP_URL);
                if (!isset($parsedUrl['scheme']) && !isset($parsedUrl['host'])) {
                    // Relative URL, safe to use
                    $redirect = APP_URL . '/' . ltrim($redirect, '/');
                } elseif (isset($parsedUrl['host']) && $parsedUrl['host'] === $appParsedUrl['host']) {
                    // Same domain, safe to use
                } else {
                    // Different domain, use default
                    $redirect = APP_URL . '/dashboard/';
                }
            } else {
                $redirect = APP_URL . '/dashboard/';
            }
            header('Location: ' . $redirect);
            exit;
        } else {
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] == 1) {
                $_SESSION['first_login_attempt'] = time();
            }
            $error = 'E-mail ou mot de passe incorrect.';
        }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion – BUILD.CV</title>
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
            <h1>Bon retour.</h1>
            <p>Connectez-vous pour continuer à construire votre histoire professionnelle et gérer votre portfolio.</p>
            <div class="auth-stat-cards">
                <div class="stat-card"><strong>10K+</strong><span>CV créés</span></div>
                <div class="stat-card"><strong>5K+</strong><span>Portfolios en ligne</span></div>
                <div class="stat-card"><strong>IA</strong><span>Assistée</span></div>
            </div>
        </div>
    </div>
    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Connexion à votre compte</h2>
                <p>Nouveau ici ? <a href="<?= APP_URL ?>/auth/register.php">Créer un compte gratuit</a></p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
            <div class="alert alert-success">Mot de passe réinitialisé avec succès. Vous pouvez maintenant vous connecter.</div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <?= csrfField() ?>
                <div class="form-group">
                    <label>Adresse e-mail</label>
                    <input type="email" name="email" placeholder="jean@exemple.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
                </div>
                <div class="form-group">
                    <label>
                        Mot de passe
                        <a href="<?= APP_URL ?>/auth/reset-password.php" class="forgot-link">Mot de passe oublié ?</a>
                    </label>
                    <div class="input-suffix">
                        <input type="password" name="password" id="passwordInput" placeholder="Votre mot de passe" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">👁</button>
                    </div>
                </div>
                <button type="submit" class="btn-primary btn-full">
                    <span class="btn-text">Se connecter</span>
                    <span class="btn-arrow">→</span>
                </button>
            </form>
            <div class="auth-divider"><span>Nouveau sur BUILD.CV ?</span></div>
            <a href="<?= APP_URL ?>/auth/register.php" class="btn-outline btn-full">Créer un compte gratuit</a>
        </div>
    </div>
</div>
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
