<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$error = '';
$success = '';
$step = isset($_GET['token']) ? 'reset' : 'request';
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrf)) {
        $error = 'Jeton de sécurité invalide. Veuillez réessayer.';
    } elseif ($step === 'request') {
        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Veuillez saisir une adresse e-mail valide.';
        } else {
            $user = db()->fetchOne('SELECT id FROM users WHERE email = ?', [$email]);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', time() + 3600); // 1 heure
                db()->query('UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?',
                    [$token, $expires, $user['id']]);
                // En production, envoyer un e-mail. Pour la démo, afficher le lien directement.
                $resetLink = APP_URL . '/auth/reset-password.php?token=' . $token;
                $success = 'Lien de réinitialisation généré ! <a href="' . $resetLink . '">Cliquez ici pour réinitialiser votre mot de passe</a> (en production, il serait envoyé par e-mail).';
            } else {
                $success = 'Si cet e-mail existe, un lien de réinitialisation a été envoyé.';
            }
        }
    } elseif ($step === 'reset') {
        $newPassword = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        if (strlen($newPassword) < 8) {
            $error = 'Le mot de passe doit contenir au moins 8 caractères.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Les mots de passe ne correspondent pas.';
        } else {
            $user = db()->fetchOne(
                'SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()',
                [$token]
            );
            if (!$user) {
                $error = 'Lien de réinitialisation invalide ou expiré.';
            } else {
                $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
                db()->query('UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?',
                    [$hashed, $user['id']]);
                header('Location: ' . APP_URL . '/auth/login.php?reset=success');
                exit;
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
<title>Réinitialiser le mot de passe – BUILD.CV</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/auth.css">
</head>
<body>
<div class="auth-page auth-page-centered">
    <div class="auth-card auth-card-solo">
        <div class="auth-brand-centered">
            <a href="<?= APP_URL ?>" class="logo-link">
                <span class="logo-icon">⚡</span>
                <span class="logo-text">BUILD<span class="logo-dot">.CV</span></span>
            </a>
        </div>

        <?php if ($step === 'request'): ?>
        <div class="auth-header">
            <h2>Réinitialiser votre mot de passe</h2>
            <p>Saisissez votre e-mail et nous vous enverrons un lien de réinitialisation.</p>
        </div>

        <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" class="auth-form">
            <?= csrfField() ?>
            <div class="form-group">
                <label>Adresse e-mail</label>
                <input type="email" name="email" placeholder="jean@exemple.com" required autofocus>
            </div>
            <button type="submit" class="btn-primary btn-full">Envoyer le lien de réinitialisation</button>
        </form>
        <?php endif; ?>

        <?php else: ?>
        <div class="auth-header">
            <h2>Définir un nouveau mot de passe</h2>
            <p>Choisissez un mot de passe fort pour votre compte.</p>
        </div>

        <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="POST" class="auth-form">
            <?= csrfField() ?>
            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="password" placeholder="Minimum 8 caractères" required minlength="8">
            </div>
            <div class="form-group">
                <label>Confirmer le nouveau mot de passe</label>
                <input type="password" name="confirm_password" placeholder="Répétez votre mot de passe" required>
            </div>
            <button type="submit" class="btn-primary btn-full">Réinitialiser le mot de passe</button>
        </form>
        <?php endif; ?>

        <div style="text-align:center;margin-top:1rem;">
            <a href="<?= APP_URL ?>/auth/login.php" class="text-link">← Retour à la connexion</a>
        </div>
    </div>
</div>
</body>
</html>
