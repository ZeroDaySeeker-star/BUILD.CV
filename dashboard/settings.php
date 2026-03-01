<?php
// Tableau de bord : Paramètres du compte
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';

$errors  = [];
$success = [];

// ── Récupérer les infos du profil ─────────────────
$userId = $_SESSION['user_id'];
$profile = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]);

// ── Mise à jour des informations du compte ─────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = "Erreur de sécurité : session expirée. Veuillez recharger la page.";
    } else {
        // Action : modifier les infos
        if ($_POST['action'] === 'update_account') {
        $newName     = trim($_POST['full_name'] ?? '');
        $newEmail    = trim($_POST['email'] ?? '');
        $newUsername = trim($_POST['username'] ?? '');

        if (!$newName)     $errors[] = 'Le nom complet est requis.';
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) $errors[] = 'Adresse e-mail invalide.';
        if (!preg_match('/^[a-z0-9_-]{3,30}$/', $newUsername)) {
            $errors[] = 'Le nom d\'utilisateur doit contenir 3-30 caractères (lettres minuscules, chiffres, _ ou -).';
        }

        // Vérifier que l'email/username n'est pas déjà utilisé par quelqu'un d'autre
        if (!$errors) {
            $emailTaken = db()->fetchOne('SELECT id FROM users WHERE email = ? AND id != ?', [$newEmail, $userId]);
            $userTaken  = db()->fetchOne('SELECT id FROM users WHERE username = ? AND id != ?', [$newUsername, $userId]);
            if ($emailTaken)  $errors[] = 'Cet e-mail est déjà utilisé par un autre compte.';
            if ($userTaken)   $errors[] = 'Ce nom d\'utilisateur est déjà pris.';
        }

        if (!$errors) {
            db()->query(
                'UPDATE users SET full_name = ?, email = ?, username = ? WHERE id = ?',
                [$newName, $newEmail, $newUsername, $userId]
            );
            $_SESSION['full_name'] = $newName;
            $_SESSION['username']  = $newUsername;
            $success[] = 'Informations mises à jour avec succès !';
            // Rafraîchir les données
            $currentUser = db()->fetchOne('SELECT * FROM users WHERE id = ?', [$userId]);
        }
    }

    // Action : modifier le mot de passe
    if ($_POST['action'] === 'change_password') {
        $oldPwd  = $_POST['current_password'] ?? '';
        $newPwd  = $_POST['new_password'] ?? '';
        $confPwd = $_POST['confirm_password'] ?? '';

        if (!$oldPwd)  $errors[] = 'Le mot de passe actuel est requis.';
        if (strlen($newPwd) < 8) $errors[] = 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
        if ($newPwd !== $confPwd) $errors[] = 'Les deux nouveaux mots de passe ne correspondent pas.';

        if (!$errors) {
            $user = db()->fetchOne('SELECT password FROM users WHERE id = ?', [$userId]);
            if (!password_verify($oldPwd, $user['password'])) {
                $errors[] = 'Mot de passe actuel incorrect.';
            } else {
                db()->query('UPDATE users SET password = ? WHERE id = ?', [password_hash($newPwd, PASSWORD_DEFAULT), $userId]);
                $success[] = 'Mot de passe modifié avec succès !';
            }
        }
    }

    // Action : modifier la sécurité du portfolio (PREMIUM ONLY)
    if ($_POST['action'] === 'update_portfolio_security') {
        if ($_SESSION['plan_level'] < 3) {
            $errors[] = "Cette fonctionnalité est réservée aux membres Premium.";
        } else {
            $protoPass = trim($_POST['portfolio_password'] ?? '');
            $customDom = trim($_POST['custom_domain'] ?? '');
            
            db()->query(
                'UPDATE profiles SET portfolio_password = ?, custom_domain = ? WHERE user_id = ?',
                [$protoPass ?: null, $customDom ?: null, $userId]
            );
            $success[] = 'Paramètres de sécurité mis à jour !';
            $profile = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]);
        }
    }
}
}

$pageTitle  = 'Paramètres';
$activePage = 'settings';
include __DIR__ . '/../includes/head.php';
?>
<div class="dashboard-layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="page-content">

            <div class="page-header">
                <h1>⚙️ Paramètres</h1>
                <p>Gérez votre compte et vos préférences.</p>
            </div>

            <?php foreach ($errors as $e): ?>
            <div class="alert alert-error" style="margin-bottom:1rem;">⚠️ <?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
            <?php foreach ($success as $s): ?>
            <div class="alert alert-success" style="margin-bottom:1rem;">✅ <?= htmlspecialchars($s) ?></div>
            <?php endforeach; ?>

            <div class="settings-grid">

                <!-- ── Informations du compte ── -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <span class="settings-card-icon">👤</span>
                        <div>
                            <h2>Informations du compte</h2>
                            <p>Modifiez votre nom, e-mail et nom d'utilisateur.</p>
                        </div>
                    </div>
                    <form method="POST" class="settings-form">
                        <?= csrfField() ?>
                        <input type="hidden" name="action" value="update_account">

                        <div class="form-group">
                            <label for="full_name">Nom complet</label>
                            <input type="text" id="full_name" name="full_name"
                                   value="<?= htmlspecialchars($currentUser['full_name'] ?? '') ?>"
                                   placeholder="Prénom Nom" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Adresse e-mail</label>
                            <input type="email" id="email" name="email"
                                   value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>"
                                   placeholder="vous@exemple.com" required>
                        </div>

                        <div class="form-group">
                            <label for="username">Nom d'utilisateur</label>
                            <div class="input-prefix-wrap">
                                <span class="input-prefix"><?= APP_URL ?>/u/</span>
                                <input type="text" id="username" name="username"
                                       value="<?= htmlspecialchars($currentUser['username'] ?? '') ?>"
                                       placeholder="monpseudo" required>
                            </div>
                            <small>URL publique de votre portfolio. Uniquement lettres minuscules, chiffres, _ ou -.</small>
                        </div>

                        <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                    </form>
                </div>

                <!-- ── Sécurité / Mot de passe ── -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <span class="settings-card-icon">🔒</span>
                        <div>
                            <h2>Sécurité</h2>
                            <p>Modifiez votre mot de passe.</p>
                        </div>
                    </div>
                    <form method="POST" class="settings-form">
                        <?= csrfField() ?>
                        <input type="hidden" name="action" value="change_password">

                        <div class="form-group">
                            <label for="current_password">Mot de passe actuel</label>
                            <input type="password" id="current_password" name="current_password"
                                   placeholder="••••••••" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password">Nouveau mot de passe</label>
                            <input type="password" id="new_password" name="new_password"
                                   placeholder="Minimum 8 caractères" required>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                            <input type="password" id="confirm_password" name="confirm_password"
                                   placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn-primary">Changer le mot de passe</button>
                    </form>
                </div>

                <!-- ── Abonnement actuel ── -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <span class="settings-card-icon">⭐</span>
                        <div>
                            <h2>Abonnement</h2>
                            <p>Votre offre actuelle.</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.5rem;">
                        <div>
                            <div style="font-size:1.3rem;font-weight:700;margin-bottom:0.25rem;">
                                <?php
                                $planNameRaw = $_SESSION['plan'] ?? 'free';
                                if ($planNameRaw === 'premium') echo '⭐ Premium';
                                elseif ($planNameRaw === 'standard') echo '💎 Standard';
                                else echo '🔵 Gratuit';
                                ?>
                            </div>
                            <div style="font-size:0.85rem;color:var(--text-muted);">
                                <?php
                                if ($planNameRaw === 'premium') {
                                    echo 'Accès complet à tous les modèles et fonctionnalités avancées.';
                                } elseif ($planNameRaw === 'standard') {
                                    echo 'Accès intermédiaire : 3 CVs, 5 modèles, sans filigrane.';
                                } else {
                                    echo 'Accès aux modèles gratuits. Passez au Premium pour développer votre potentiel.';
                                }
                                ?>
                            </div>
                        </div>
                        <?php if ($_SESSION['plan_level'] < 3): ?>
                        <a href="upgrade.php" class="btn-primary" style="white-space:nowrap;margin-left:1rem;">
                            Passer au Premium →
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ── Sécurité Portfolio (PREMIUM) ── -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <span class="settings-card-icon">🔐</span>
                        <div>
                            <h2>Sécurité & Branding du Portfolio</h2>
                            <p>Options exclusives au mode Premium.</p>
                        </div>
                    </div>
                    <?php if ($_SESSION['plan_level'] < 3): ?>
                        <div style="padding: 1.5rem; text-align:center; background: rgba(0,0,0,0.2); border-radius:12px; border:1px dashed var(--border);">
                            <p style="margin-bottom:1rem; font-size:0.9rem;">Débloquez la protection par mot de passe et l'utilisation d'un domaine personnalisé.</p>
                            <a href="upgrade.php" class="btn btn-ghost btn-sm">Découvrir les avantages Premium</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" class="settings-form">
                            <?= csrfField() ?>
                            <input type="hidden" name="action" value="update_portfolio_security">
                            
                            <div class="form-group">
                                <label>Mot de passe du portfolio</label>
                                <input type="text" name="portfolio_password" value="<?= htmlspecialchars($profile['portfolio_password'] ?? '') ?>" placeholder="Laissez vide pour désactiver">
                                <small>Si défini, les visiteurs devront saisir ce mot de passe pour voir votre portfolio.</small>
                            </div>

                            <div class="form-group">
                                <label>Nom de domaine personnalisé (exclusif)</label>
                                <input type="text" name="custom_domain" value="<?= htmlspecialchars($profile['custom_domain'] ?? '') ?>" placeholder="www.votre-nom.com">
                                <small>Entrez votre domaine. Notre équipe vous contactera pour la configuration DNS.</small>
                            </div>

                            <button type="submit" class="btn-primary">Mettre à jour la sécurité</button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- ── Zone dangereuse ── -->
                <div class="settings-card danger-zone">
                    <div class="settings-card-header">
                        <span class="settings-card-icon">⚠️</span>
                        <div>
                            <h2>Zone dangereuse</h2>
                            <p>Ces actions sont irréversibles.</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-top:0.5rem;">
                        <div>
                            <div style="font-weight:600;font-size:0.9rem;margin-bottom:0.2rem;">Supprimer mon compte</div>
                            <div style="font-size:0.82rem;color:var(--text-muted);">
                                Supprime définitivement votre compte, votre CV et votre portfolio.
                            </div>
                        </div>
                        <button type="button" class="btn-danger-solid" onclick="confirmDelete()">
                            Supprimer le compte
                        </button>
                    </div>
                </div>

            </div><!-- /settings-grid -->
        </div><!-- /page-content -->
    </div><!-- /main-content -->
</div><!-- /dashboard-layout -->

<?php include __DIR__ . '/../includes/foot.php'; ?>
<script>
function confirmDelete() {
    if (confirm('⚠️ Êtes-vous sûr(e) de vouloir supprimer définitivement votre compte ?\n\nCette action est irréversible.')) {
        alert('Fonctionnalité bientôt disponible. Contactez le support pour supprimer votre compte.');
    }
}
</script>
