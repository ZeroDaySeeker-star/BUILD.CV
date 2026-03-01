<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> – <?= APP_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/dashboard.css?v=<?= time() ?>">
<?php if (($pageTitle ?? '') === 'CV Builder'): ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/cv-builder.css?v=<?= time() ?>">
<?php endif; ?>

<meta name="csrf-token" content="<?= generateCsrfToken() ?>">
<script>
// Intercept fetch to automatically add CSRF token
const originalFetch = window.fetch;
window.fetch = async function(resource, config) {
    if (config && ['POST', 'PUT', 'DELETE'].includes(config.method?.toUpperCase())) {
        config.headers = {
            ...config.headers,
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        };
    }
    return originalFetch.call(window, resource, config);
};
</script>

<style>
/* Shared component styles across all dashboard pages */
.page-header { margin-bottom: 2rem; }
.page-header h1 { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.5px; }
.page-header p  { color: var(--text-muted); font-size: 0.9rem; margin-top: 0.3rem; }

.alert { padding: 0.85rem 1.1rem; border-radius: 8px; font-size: 0.88rem; font-weight: 500; }
.alert-success { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
.alert-error   { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }

.card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); }

.chart-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); }
.chart-header { padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--border); }
.chart-header h3 { font-size: 0.95rem; font-weight: 600; }
.chart-body { padding: 1.25rem; height: 230px; }

.stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; }
.stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
.stat-value { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.5px; }
.stat-label { color: var(--text-muted); font-size: 0.8rem; font-weight: 500; }

.badge.free    { background: rgba(100,100,100,0.1); color: var(--text-muted); font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 10px; font-weight: 600; }
.badge.premium { background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(139,92,246,0.15)); color: var(--primary); font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 10px; font-weight: 700; border: 1px solid rgba(99,102,241,0.3); }

.hidden-radio { display: none; }

.template-card {
    border: 2px solid var(--border); border-radius: var(--radius); background: var(--surface);
    cursor: pointer; overflow: hidden; transition: all 0.2s; display: flex; flex-direction: column; position: relative;
}
.template-card:hover { border-color: rgba(99,102,241,0.5); transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
.template-card.selected { border-color: var(--primary); box-shadow: 0 0 0 1px var(--primary), 0 8px 24px rgba(99,102,241,0.15); }
.template-card.locked   { opacity: 0.65; cursor: not-allowed; }

.template-preview { height: 120px; display: flex; align-items: center; justify-content: center; padding: 1rem; }
.template-mock { width: 100%; height: 80px; background: rgba(255,255,255,0.1); border-radius: 6px; display: flex; gap: 6px; overflow: hidden; }
.mock-sidebar { width: 28%; background: rgba(255,255,255,0.05); }
.mock-content { flex: 1; padding: 8px; display: flex; flex-direction: column; gap: 5px; }
.mock-line { height: 6px; background: rgba(255,255,255,0.25); border-radius: 3px; }
.mock-title { width: 70%; height: 9px; }
.mock-line.short { width: 50%; }

.portfolio-mock { flex-direction: column; height: 80px; }
.mock-hero  { height: 40%; background: rgba(255,255,255,0.07); border-radius: 4px; }
.mock-cards { display: flex; gap: 4px; flex: 1; padding-top: 5px; }
.mock-card  { flex: 1; background: rgba(255,255,255,0.12); border-radius: 4px; }

.template-info { padding: 1rem; flex: 1; }
.template-name-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.3rem; }
.template-name-row strong { font-size: 0.92rem; }
.template-info p { font-size: 0.8rem; color: var(--text-muted); }
.upgrade-link { font-size: 0.78rem; color: var(--primary); display: block; margin-top: 0.4rem; }

.template-check {
    position: absolute; top: 0.5rem; right: 0.5rem;
    background: var(--primary); color: white; width: 22px; height: 22px;
    border-radius: 50%; font-size: 0.65rem; font-weight: 700;
    display: none; align-items: center; justify-content: center;
}
.template-card.selected .template-check { display: flex; }

.templates-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
.templates-section-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.3rem; }
.templates-section-desc  { font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem; }

.upgrade-banner { background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(139,92,246,0.1)); border: 1px solid rgba(99,102,241,0.25); border-radius: var(--radius); padding: 1.1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
.upgrade-banner p { font-size: 0.88rem; color: var(--text); }

.fgroup { display: flex; flex-direction: column; gap: 0.35rem; }
.fgroup label { font-size: 0.82rem; font-weight: 500; color: var(--text-light); }
.fgroup input, .fgroup select, .fgroup textarea {
    background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px;
    padding: 0.65rem 0.85rem; color: var(--text); font-family: var(--font); font-size: 0.88rem;
    outline: none; transition: border-color 0.2s;
}
.fgroup input:focus { border-color: var(--primary); }
.fgroup input:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
</head>
<body class="animate-fade-in">
    <!-- Global Loader -->
    <div id="globalLoader" class="global-loader-overlay">
        <div class="spinner-ring"></div>
    </div>
    
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                var loader = document.getElementById('globalLoader');
                if(loader) loader.classList.add('hidden');
            }, 250); // slight delay for smooth transition
        });
    </script>
