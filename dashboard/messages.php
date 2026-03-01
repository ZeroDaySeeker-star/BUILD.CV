<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';

// Premium Check
if (($_SESSION['plan_level'] ?? 1) < 3) {
    header('Location: ' . APP_URL . '/dashboard/upgrade.php?error=premium_only');
    exit;
}

$userId = $_SESSION['user_id'];
$profile = db()->fetchOne("SELECT id FROM profiles WHERE user_id = ?", [$userId]);
$profileId = $profile['id'] ?? 0;

$messages = db()->fetchAll("SELECT * FROM portfolio_messages WHERE profile_id = ? ORDER BY created_at DESC", [$profileId]);

// Update sidebar active state handled by includes/sidebar.php
$pageTitle = 'Messagerie Portfolio';
$activePage = 'messages';
include __DIR__ . '/../includes/head.php';
?>
<div class="dashboard-layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="page-content">
            <header class="page-header">
                <h1>Messagerie Portfolio 📩</h1>
                <p>Messages reçus via le formulaire de contact de votre portfolio.</p>
            </header>

            <?php if (empty($messages)): ?>
                <div style="text-align:center; padding: 5rem 2rem; border: 2px dashed var(--border); border-radius: 20px;">
                    <span style="font-size:3rem; display:block; margin-bottom:1rem;">📭</span>
                    <h3>Votre boîte de réception est vide</h3>
                    <p style="color:var(--text-muted); max-width:400px; margin: 0.5rem auto;">Les messages envoyés par les recruteurs depuis votre portfolio apparaîtront ici.</p>
                </div>
            <?php else: ?>
                <div class="messages-list" style="display:grid; gap:1rem;">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message-card" style="background:var(--surface); border: 1px solid var(--border); border-radius:12px; padding:1.5rem; transition:0.3s; <?= !$msg['is_read'] ? 'border-left: 4px solid var(--primary);' : '' ?>">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
                                <div>
                                    <h3 style="margin:0; font-size:1.05rem;"><?= htmlspecialchars($msg['subject']) ?></h3>
                                    <p style="margin:0.25rem 0 0; font-size:0.85rem; color:var(--text-muted);">De: <strong><?= htmlspecialchars($msg['sender_name']) ?></strong> (<?= htmlspecialchars($msg['sender_email']) ?>)</p>
                                </div>
                                <div style="text-align:right;">
                                    <span style="font-size:0.75rem; color:var(--text-muted);"><?= date('d M Y, H:i', strtotime($msg['created_at'])) ?></span>
                                    <?php if (!$msg['is_read']): ?>
                                        <div style="font-size:0.65rem; color:var(--primary); font-weight:700; margin-top:0.25rem;">NOUVEAU</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div style="background:rgba(0,0,0,0.2); padding:1rem; border-radius:8px; font-size:0.92rem; line-height:1.6; white-space:pre-wrap;"><?= htmlspecialchars($msg['message']) ?></div>
                            <div style="margin-top:1rem; display:flex; gap:0.5rem; justify-content:flex-end;">
                                <a href="mailto:<?= htmlspecialchars($msg['sender_email']) ?>?subject=Re: <?= htmlspecialchars($msg['subject']) ?>" class="btn btn-ghost btn-sm">Répondre par e-mail ✉️</a>
                                <button class="btn btn-ghost btn-sm" onclick="deleteMessage(<?= $msg['id'] ?>)">Supprimer</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/foot.php'; ?>

<script>
async function deleteMessage(id) {
    if (!confirm('Supprimer ce message ?')) return;
    // Implementation of delete call
    alert('Bientôt disponible !');
}
</script>
