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
$apps = db()->fetchAll("SELECT * FROM job_applications WHERE user_id = ? ORDER BY applied_date DESC", [$userId]);

$statusColors = [
    'Interested'   => '#94a3b8',
    'Applied'       => '#3b82f6',
    'Interviewing' => '#8b5cf6',
    'Offered'      => '#10b981',
    'Rejected'     => '#ef4444',
    'Accepted'     => '#059669'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi Candidatures - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .app-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; transition: 0.3s; }
        .app-card:hover { border-color: var(--primary); transform: translateY(-2px); }
        .app-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .app-info h3 { margin: 0; font-size: 1.1rem; }
        .app-info p { margin: 0.25rem 0 0; color: var(--text-muted); font-size: 0.9rem; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: white; }
        .app-notes { font-size: 0.85rem; color: var(--text-light); background: rgba(0,0,0,0.2); padding: 0.75rem; border-radius: 8px; margin-top: 1rem; }
        .app-actions { display: flex; gap: 0.5rem; margin-top: 1rem; justify-content: flex-end; }
        
        .modal-body form { display: grid; gap: 1rem; }
        .modal-body label { font-size: 0.85rem; font-weight: 500; margin-bottom: 0.25rem; display: block; }
        .modal-body input, .modal-body select, .modal-body textarea { width: 100%; border-radius: 8px; background: var(--surface-2); border: 1px solid var(--border); color: white; padding: 0.6rem; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1>Suivi Candidatures 📝</h1>
                <p>Gérez vos opportunités et ne perdez plus jamais le fil.</p>
            </div>
            <div class="topbar-right">
                <button class="btn btn-primary" onclick="openModal('addAppModal')">＋ Nouvelle candidature</button>
            </div>
        </header>

        <div class="page-content">
            <?php if (empty($apps)): ?>
                <div style="text-align:center; padding: 5rem 2rem; border: 2px dashed var(--border); border-radius: 20px;">
                    <span style="font-size:3rem; display:block; margin-bottom:1rem;">🚀</span>
                    <h3>Prêt à décrocher le job de vos rêves ?</h3>
                    <p style="color:var(--text-muted); max-width:400px; margin: 0.5rem auto 1.5rem;">Commencez par ajouter votre première candidature pour suivre son évolution ici.</p>
                    <button class="btn btn-primary" onclick="openModal('addAppModal')">Ajouter ma première candidature</button>
                </div>
            <?php else: ?>
                <div class="apps-grid">
                    <?php foreach ($apps as $app): ?>
                        <div class="app-card">
                            <div class="app-header">
                                <div class="app-info">
                                    <h3><?= htmlspecialchars($app['position']) ?></h3>
                                    <p><?= htmlspecialchars($app['company']) ?></p>
                                </div>
                                <span class="status-badge" style="background: <?= $statusColors[$app['status']] ?? '#3b82f6' ?>">
                                    <?= $app['status'] ?>
                                </span>
                            </div>
                            <div style="font-size:0.8rem; color:var(--text-muted)">
                                📅 Postulé le : <?= date('d M Y', strtotime($app['applied_date'])) ?>
                            </div>
                            <?php if ($app['notes']): ?>
                                <div class="app-notes"><?= nl2br(htmlspecialchars($app['notes'])) ?></div>
                            <?php endif; ?>
                            <div class="app-actions">
                                <button class="btn btn-ghost btn-sm" onclick="deleteApp(<?= $app['id'] ?>)">🗑</button>
                                <button class="btn btn-ghost btn-sm" onclick="editApp(<?= $app['id'] ?>)">✏️</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal: Add Application -->
    <div class="modal-overlay" id="addAppModal" style="display:none;">
        <div class="modal" style="width: 100%; max-width: 500px; margin: 2rem;">
            <div class="modal-header">
                <h3>Ajouter une candidature</h3>
                <button class="modal-close" onclick="closeModal('addAppModal')">×</button>
            </div>
            <div class="modal-body">
                <form id="addAppForm" onsubmit="saveApp(event)">
                    <div>
                        <label>Entreprise</label>
                        <input type="text" name="company" placeholder="ex: Google" required>
                    </div>
                    <div>
                        <label>Poste</label>
                        <input type="text" name="position" placeholder="ex: Développeur Senior" required>
                    </div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                        <div>
                            <label>Statut</label>
                            <select name="status">
                                <option>Interested</option>
                                <option selected>Applied</option>
                                <option>Interviewing</option>
                                <option>Offered</option>
                                <option>Rejected</option>
                                <option>Accepted</option>
                            </select>
                        </div>
                        <div>
                            <label>Date de candidature</label>
                            <input type="date" name="applied_date" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div>
                        <label>Notes (optionnel)</label>
                        <textarea name="notes" rows="4" placeholder="Détails sur l'offre, lien, contact..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).style.display = 'flex'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }

        async function saveApp(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            
            const r = await fetch('<?= APP_URL ?>/api/track-application.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({...data, action: 'create'})
            });
            const res = await r.json();
            if (res.success) {
                location.reload();
            } else {
                alert(res.error || 'Erreur lors de l\'enregistrement');
            }
        }

        async function deleteApp(id) {
            if (!confirm('Supprimer cette candidature ?')) return;
            const r = await fetch('<?= APP_URL ?>/api/track-application.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id, action: 'delete'})
            });
            const res = await r.json();
            if (res.success) location.reload();
        }
    </script>
</body>
</html>
