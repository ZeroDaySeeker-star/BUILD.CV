<?php
// views/layouts/main.php - Main Admin Wrapper
// Requires variables: $pageTitle, $content
$activeMenu = $activeMenu ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Administration') ?> | BUILD.CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Default Dark Theme */
            --bg-base: #0f172a;
            --bg-surface: #1e293b;
            --bg-sidebar: #0f172a;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
            
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --success: #10b981;
            --error: #ef4444;
            --warning: #f59e0b;
        }

        [data-theme="light"] {
            --bg-base: #f8fafc;
            --bg-surface: #ffffff;
            --bg-sidebar: #1e293b;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-base);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Typography & Utilities */
        h1, h2, h3 { font-weight: 600; color: var(--text-main); }
        a { color: var(--primary); text-decoration: none; }
        .text-center { text-align: center; }
        .text-muted { color: var(--text-muted); }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: var(--bg-surface);
            color: var(--text-main);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn:hover { background-color: var(--primary); color: white; border-color: var(--primary); }
        .btn-primary { background-color: var(--primary); color: white; border: none; }
        .btn-danger { background-color: rgba(239,68,68,0.1); color: var(--error); border-color: transparent; }
        .btn-danger:hover { background-color: var(--error); color: white; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.8rem; }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: var(--bg-sidebar);
            color: white;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
            flex-shrink: 0;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header span { color: var(--primary); }
        
        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }
        
        .nav-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
            font-weight: 500;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-item:hover, .nav-item.active {
            background-color: rgba(255,255,255,0.05);
            color: white;
            border-left: 3px solid var(--primary);
        }
        
        /* Layout */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        
        .topbar {
            height: 60px;
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-info {
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .content {
            padding: 2rem;
            flex: 1;
            overflow-y: auto;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .alert-success { background-color: rgba(16,185,129,0.1); color: var(--success); border: 1px solid rgba(16,185,129,0.2); }
        .alert-error { background-color: rgba(239,68,68,0.1); color: var(--error); border: 1px solid rgba(239,68,68,0.2); }

        /* Dashboard Grid & Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-row {
            display: flex; gap: 1.5rem; flex-wrap: wrap;
        }
        .flex-1 { flex: 1; min-width: 300px; }
        .flex-2 { flex: 2; min-width: 400px; }

        .stat-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .stat-icon {
            font-size: 2rem;
            width: 60px; height: 60px;
            display: flex; align-items: center; justify-content: center;
            background-color: rgba(99,102,241,0.1);
            border-radius: 10px;
            color: var(--primary);
        }
        .stat-details h3 { font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text-main); }

        .card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            display: flex; flex-direction: column;
        }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-title { font-size: 1.1rem; }
        .card-body { padding: 1.5rem; }

        /* Tables */
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; text-align: left; }
        .table th, .table td { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color); font-size: 0.9rem; }
        .table th { font-weight: 600; color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        .table tr:last-child td { border-bottom: none; }
        .table tbody tr:hover { background-color: rgba(0,0,0,0.02); }
        [data-theme="dark"] .table tbody tr:hover { background-color: rgba(255,255,255,0.02); }

        .badge { padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-success { background: rgba(16,185,129,0.1); color: var(--success); }
        .badge-error { background: rgba(239,68,68,0.1); color: var(--error); }
        .badge-primary { background: rgba(99,102,241,0.1); color: var(--primary); }

        /* Forms */
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text-muted); }
        .form-control {
            width: 100%; padding: 0.75rem 1rem;
            background-color: var(--bg-base); color: var(--text-main);
            border: 1px solid var(--border-color); border-radius: 6px;
            font-family: inherit; font-size: 0.95rem;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 2px rgba(99,102,241,0.2); }

        /* Activity Feed */
        .activity-feed { padding: 1rem 1.5rem; }
        .activity-item { padding: 1rem 0; border-bottom: 1px solid var(--border-color); }
        .activity-item:last-child { border-bottom: none; }
        .activity-user { font-weight: 600; font-size: 0.9rem; }
        .activity-action { font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem; }
        .activity-time { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; opacity: 0.7; }

    </style>
</head>
<body>
    
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-header">
            BUILD<span>.</span>CV <sub>Admin</sub>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= ADMIN_URL ?>/index.php" class="nav-item <?= $activeMenu === 'dashboard' ? 'active' : '' ?>">📊 Tableau de bord</a>
            <a href="<?= ADMIN_URL ?>/crud.php?table=users" class="nav-item <?= $activeMenu === 'users' ? 'active' : '' ?>">👥 Utilisateurs</a>
            <a href="<?= ADMIN_URL ?>/crud.php?table=plans" class="nav-item <?= $activeMenu === 'plans' ? 'active' : '' ?>">💎 Abonnements (Plans)</a>
            <a href="<?= ADMIN_URL ?>/crud.php?table=templates" class="nav-item <?= $activeMenu === 'templates' ? 'active' : '' ?>">🎨 Modèles</a>
            <a href="<?= ADMIN_URL ?>/crud.php?table=subscriptions" class="nav-item <?= $activeMenu === 'subscriptions' ? 'active' : '' ?>">💳 Paiements</a>
            
            <?php if(($_SESSION['admin_role'] ?? '') === 'super_admin'): ?>
            <div style="margin: 1.5rem 1.5rem 0.5rem; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Système</div>
            <a href="<?= ADMIN_URL ?>/crud.php?table=admins" class="nav-item <?= $activeMenu === 'admins' ? 'active' : '' ?>">🛡️ Administrateurs</a>
            <a href="<?= ADMIN_URL ?>/crud.php?table=admin_logs" class="nav-item <?= $activeMenu === 'logs' ? 'active' : '' ?>">📋 Logs d'Activité</a>
            <a href="<?= ADMIN_URL ?>/backup.php" class="nav-item <?= $activeMenu === 'backup' ? 'active' : '' ?>">💾 Sauvegarde BDD</a>
            <?php endif; ?>
        </nav>
        <div style="padding: 1.5rem;">
            <a href="<?= ADMIN_URL ?>/logout.php" class="btn btn-danger" style="display: block; text-align: center; width: 100%;">Déconnexion</a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <header class="topbar">
            <div>
                <h2><?= htmlspecialchars($pageTitle) ?></h2>
            </div>
            <div class="topbar-right">
                <button id="themeToggle" class="btn btn-sm">🌙 Mode Sombre</button>
                <div class="user-info">
                    <span style="background:var(--primary); color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold;">
                        <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
                    </span>
                    <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
                    <small class="badge badge-primary"><?= htmlspecialchars($_SESSION['admin_role'] ?? '') ?></small>
                </div>
            </div>
        </header>

        <main class="content">
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <!-- INJECTED CONTENT -->
            <?= $content ?? '' ?>
            <!-- /INJECTED CONTENT -->
            
        </main>
    </div>

    <!-- Theme Persistance Script -->
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const root = document.documentElement;
        
        // Load preference
        const savedTheme = localStorage.getItem('adminTheme') || 'dark';
        root.setAttribute('data-theme', savedTheme);
        updateButtonText(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = root.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            root.setAttribute('data-theme', newTheme);
            localStorage.setItem('adminTheme', newTheme);
            updateButtonText(newTheme);
        });

        function updateButtonText(theme) {
            themeToggle.textContent = theme === 'dark' ? '☀️ Mode Clair' : '🌙 Mode Sombre';
        }
    </script>
</body>
</html>
