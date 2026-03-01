<?php
// Administration - Profil Visitors Log
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth-check.php';

// Pagination variables
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 50;
$offset = ($page - 1) * $perPage;

// Fetch total count
$totalStats = db()->fetchOne("SELECT COUNT(*) as total FROM profile_visits");
$totalVisits = $totalStats['total'] ?? 0;
$totalPages = ceil($totalVisits / $perPage);

// Fetch visitors
$visitors = db()->fetchAll("
    SELECT pv.*, u.username as visited_username, u.full_name as visited_name
    FROM profile_visits pv
    JOIN users u ON pv.user_id = u.id
    ORDER BY pv.visit_date DESC
    LIMIT $perPage OFFSET $offset
");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Visiteurs des Portfolios - Administration</title>
    <style>
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #06060c; color: #e8e8f0; padding: 2rem; margin: 0; }
        .container { max-width: 1200px; margin: 0 auto; background: rgba(255,255,255,0.05); padding: 2rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); }
        h1 { color: #6366f1; margin-top: 0; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; font-size: 0.9rem; }
        th, td { padding: 0.8rem; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { background: rgba(255,255,255,0.05); color: #9ca3af; font-weight: 600; }
        
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; background: rgba(99, 102, 241, 0.2); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.3); }
        .user-agent { max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; color: #9ca3af; }
        .user-agent:hover { white-space: normal; overflow: visible; background: #1a1d27; position: absolute; padding: 0.5rem; border-radius: 4px; border: 1px solid #374151; z-index: 10; max-width: 400px; }
        .referer { color: #10b981; text-decoration: none; }
        
        .pagination { display: flex; gap: 0.5rem; margin-top: 2rem; justify-content: center; }
        .page-btn { padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); color: white; text-decoration: none; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1); }
        .page-btn:hover { background: rgba(255,255,255,0.1); }
        .page-btn.active { background: #6366f1; border-color: #6366f1; }
        .btn-nav { display: inline-block; background: transparent; color: #9ca3af; border: 1px solid #374151; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; margin-bottom: 1rem; transition: background 0.2s; }
        .btn-nav:hover { background: rgba(255,255,255,0.05); color: white; }
    </style>
</head>
<body>
    <div class="container">
        <a href="generate-code.php" class="btn-nav">← Retour aux codes</a>
        
        <div class="header-actions">
            <div>
                <h1>Statistiques des Visiteurs</h1>
                <p style="color:#9ca3af; margin:0;">Historique des connexions sur les portfolios. Total enregistré : <?= number_format($totalVisits) ?></p>
            </div>
        </div>

        <?php if (empty($visitors)): ?>
            <p style="text-align: center; color: #9ca3af; padding: 3rem 0;">Aucune visite enregistrée pour le moment.</p>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table>
                    <tr>
                        <th>Date & Heure</th>
                        <th>Adresse IP</th>
                        <th>Portfolio Visité</th>
                        <th>Referrer (Source)</th>
                        <th>Navigateur / OS (User Agent)</th>
                    </tr>
                    <?php foreach ($visitors as $v): ?>
                    <tr>
                        <td style="white-space:nowrap;"><?= date('d/m/Y H:i:s', strtotime($v['visit_date'])) ?></td>
                        <td><code style="background:rgba(255,255,255,0.1);padding:0.2rem 0.4rem;border-radius:4px;"><?= htmlspecialchars($v['ip_address'] ?? 'Inconnue') ?></code></td>
                        <td>
                            <a href="<?= APP_URL ?>/u/<?= htmlspecialchars($v['visited_username']) ?>" target="_blank" style="color:#6366f1; text-decoration:none; font-weight:600;">
                                @<?= htmlspecialchars($v['visited_username']) ?>
                            </a>
                        </td>
                        <td>
                            <?php if (!empty($v['referrer'])): ?>
                                <a href="<?= htmlspecialchars($v['referrer']) ?>" target="_blank" class="referer" title="<?= htmlspecialchars($v['referrer']) ?>">
                                    <?= htmlspecialchars(parse_url($v['referrer'], PHP_URL_HOST) ?? 'Lien direct') ?>
                                </a>
                            <?php else: ?>
                                <span style="color:#6b7280; font-size:0.8rem;">Direct / Inconnu</span>
                            <?php endif; ?>
                        </td>
                        <td style="position:relative;">
                            <span class="user-agent" title="<?= htmlspecialchars($v['user_agent'] ?? '') ?>">
                                <?= htmlspecialchars($v['user_agent'] ?? 'Inconnu') ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="page-btn">Précédent</a>
                <?php endif; ?>
                
                <?php 
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                for ($i = $start; $i <= $end; $i++): 
                ?>
                    <a href="?page=<?= $i ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="page-btn">Suivant</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
