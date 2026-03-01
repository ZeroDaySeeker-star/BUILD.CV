<?php
// admin/index.php - Main Dashboard Controller
require_once __DIR__ . '/config/auth.php';
requireAdmin();

$pageTitle = "Tableau de Bord";
$activeMenu = "dashboard";

// --- Fetch Dashboard Stats ---
$db = db();

// Total Users
$totalUsers = $db->fetchOne("SELECT COUNT(*) as count FROM users")['count'] ?? 0;

// Active Subscriptions
$activeSubs = $db->fetchOne("SELECT COUNT(*) as count FROM subscriptions WHERE status = 'active'")['count'] ?? 0;

// Total Revenue (approximate based on active subs this month - simplified for example)
// In a real app, this would query a payments/invoices table.
$revenueQ = $db->fetchOne("
    SELECT SUM(p.price_monthly) as total 
    FROM subscriptions s 
    JOIN plans p ON s.plan_id = p.id 
    WHERE s.status = 'active'
");
$monthlyRevenue = $revenueQ['total'] ?? 0;

// Recent Users
$recentUsers = $db->fetchAll("
    SELECT id, username, email, created_at
    FROM users 
    ORDER BY created_at DESC 
    LIMIT 5
");

// Recent Activity Logs (Admins)
$recentLogs = $db->fetchAll("
    SELECT l.*, a.username 
    FROM admin_logs l 
    LEFT JOIN admins a ON l.admin_id = a.id 
    ORDER BY l.created_at DESC 
    LIMIT 5
");

?>
<?php ob_start(); // Start Content Buffer ?>

<div class="dashboard-grid">
    <!-- Stats Cards -->
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-details">
            <h3>Total Utilisateurs</h3>
            <div class="stat-value"><?= number_format($totalUsers) ?></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-details">
            <h3>Abonnements Actifs</h3>
            <div class="stat-value"><?= number_format($activeSubs) ?></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-details">
            <h3>Revenus Mensuels (Est.)</h3>
            <div class="stat-value"><?= number_format($monthlyRevenue, 0, ',', ' ') ?> F CFA</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📄</div>
        <div class="stat-details">
            <h3>Modèles de CV</h3>
            <div class="stat-value"><?= $db->fetchOne("SELECT COUNT(*) as c FROM templates WHERE template_type='cv'")['c'] ?? 0 ?></div>
        </div>
    </div>
</div>

<div class="dashboard-row">
    <!-- Recent Users Table -->
    <div class="card flex-2">
        <div class="card-header">
            <h2 class="card-title">Derniers Inscrits</h2>
            <a href="crud.php?table=users" class="btn btn-sm">Voir tout</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentUsers)): ?>
                        <tr><td colspan="4" class="text-center">Aucun utilisateur.</td></tr>
                    <?php else: ?>
                        <?php foreach($recentUsers as $u): ?>
                        <tr>
                            <td>#<?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Recent Admin Activity -->
    <div class="card flex-1">
        <div class="card-header">
            <h2 class="card-title">Activité Admin</h2>
        </div>
        <div class="activity-feed">
            <?php if (empty($recentLogs)): ?>
                <div class="text-muted text-center py-4">Aucune activité récente.</div>
            <?php else: ?>
                <?php foreach($recentLogs as $log): ?>
                <div class="activity-item">
                    <div class="activity-user"><?= htmlspecialchars($log['username'] ?? 'Système') ?></div>
                    <div class="activity-action">
                        <?= htmlspecialchars($log['action']) ?> 
                        <?php if($log['table_name']): ?>
                            sur <code><?= htmlspecialchars($log['table_name']) ?></code> (#<?= $log['record_id'] ?>)
                        <?php endif; ?>
                    </div>
                    <div class="activity-time"><?= date('d/m H:i', strtotime($log['created_at'])) ?></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/views/layouts/main.php';
?>
