<?php
// Portfolio view page - public route: /u/{username}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$username = $_GET['username'] ?? '';
if (!preg_match('/^[a-z0-9_]{3,30}$/', $username)) {
    http_response_code(404); die('<h1>404 – Portfolio not found</h1>');
}

$user = db()->fetchOne('SELECT * FROM users WHERE username = ?', [$username]);
if (!$user) { http_response_code(404); die('<h1>404 – Portfolio not found</h1>'); }

$userId = $user['id'];
$profile = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]);
if (!$profile) { http_response_code(404); die('<h1>Portfolio not set up yet.</h1>'); }

// Fetch User Plan Level
$planData = db()->fetchOne("
    SELECT p.position as level 
    FROM subscriptions s 
    JOIN plans p ON s.plan_id = p.id 
    WHERE s.user_id = ?", 
    [$userId]
);
$planLevel = $planData['level'] ?? 1;

// Ensure full_name is populated from users table if not perfectly synced in profiles
if (empty($profile['full_name']) && !empty($user['full_name'])) {
    $profile['full_name'] = $user['full_name'];
}

$experience     = db()->fetchAll('SELECT * FROM experience WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]);
$skills         = db()->fetchAll('SELECT * FROM skills WHERE user_id = ? ORDER BY skill_level DESC', [$userId]);
$projects       = db()->fetchAll('SELECT * FROM projects WHERE user_id = ? ORDER BY sort_order', [$userId]);
$education      = db()->fetchAll('SELECT * FROM education WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]);
$languages      = db()->fetchAll('SELECT * FROM languages WHERE user_id = ? ORDER BY sort_order', [$userId]);
$certifications = db()->fetchAll('SELECT * FROM certifications WHERE user_id = ? ORDER BY sort_order', [$userId]);

// ── Password Protection Check (Premium Feature) ──
if (!empty($profile['portfolio_password'])) {
    session_start(); // Ensure session is active
    $unlockedKey = 'portfolio_unlocked_' . $profile['id'];
    
    // Check if form submitted
    if (isset($_POST['p_pass'])) {
        if ($_POST['p_pass'] === $profile['portfolio_password']) {
            $_SESSION[$unlockedKey] = true;
        } else {
            $passError = "Mot de passe incorrect.";
        }
    }

    if (!isset($_SESSION[$unlockedKey])) {
        // Show password prompt
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Portfolio Protégé - <?= APP_NAME ?></title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
            <style>
                body { background: #06060c; color: white; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
                .box { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 3rem; border-radius: 20px; text-align: center; max-width: 400px; width: 90%; }
                h1 { font-size: 1.5rem; margin-bottom: 1rem; }
                input { width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.3); color: white; margin-bottom: 1rem; text-align: center; }
                button { background: #6366f1; color: white; border: none; padding: 0.8rem 2rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; }
                button:hover { background: #4f46e5; transform: translateY(-2px); }
                .error { color: #ef4444; font-size: 0.85rem; margin-bottom: 1rem; }
            </style>
        </head>
        <body>
            <div class="box">
                <div style="font-size:3rem; margin-bottom:1rem;">🔐</div>
                <h1>Portfolio Privé</h1>
                <p style="color:rgba(255,255,255,0.5); font-size:0.9rem; margin-bottom:2rem;">Ce portfolio est protégé par un mot de passe défini par le propriétaire.</p>
                <form method="POST">
                    <?php if (isset($passError)) echo "<div class='error'>$passError</div>"; ?>
                    <input type="password" name="p_pass" placeholder="Entrez le mot de passe" required autofocus>
                    <button type="submit">Débloquer l'accès</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Track visit asynchronously
$ip = substr($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '', 0, 45);
$userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);
$referrer = substr($_SERVER['HTTP_REFERER'] ?? '', 0, 500);

try {
    db()->query(
        'INSERT INTO profile_visits (user_id, visit_date, ip_address, user_agent, referrer) VALUES (?, NOW(), ?, ?, ?)', 
        [$userId, $ip, $userAgent, $referrer]
    );
} catch (Exception $e) {
    // Ignore tracking errors to not break the public portfolio
}

$portfolioTemplate = $profile['portfolio_template'] ?? 'portfolio_minimal';
// Sanitize template name to prevent path traversal
$portfolioTemplate = preg_replace('/[^a-z0-9_-]/', '', strtolower($portfolioTemplate));
$templateFile = __DIR__ . '/../templates/portfolio/' . $portfolioTemplate . '.php';
if (!file_exists($templateFile)) {
    $templateFile = __DIR__ . '/../templates/portfolio/portfolio_minimal.php';
}
if (!file_exists($templateFile)) {
    $templateFile = __DIR__ . '/../templates/portfolio/minimal.php';
}
if (!file_exists($templateFile)) {
    http_response_code(404);
    die('<h1>Portfolio template not found</h1>');
}

$profile['summary'] = parse_markdown_to_html($profile['summary'] ?? '');
$profile['hobbies'] = parse_markdown_to_html($profile['hobbies'] ?? '');
foreach ($experience as &$exp) {
    if (!empty($exp['description'])) {
        $exp['description'] = parse_markdown_to_html($exp['description']);
    }
}
unset($exp);
foreach ($education as &$edu) {
    if (!empty($edu['description'])) {
        $edu['description'] = parse_markdown_to_html($edu['description']);
    }
}
unset($edu);
foreach ($projects as &$proj) {
    if (!empty($proj['description'])) {
        $proj['description'] = parse_markdown_to_html($proj['description']);
    }
}
unset($proj);

ob_start();
include $templateFile;
$html = ob_get_clean();

// Dynamic SEO injection: Add meta tags to <head> if it exists
$metaTags = '';
ob_start();
include __DIR__ . '/../includes/portfolio-meta.php';
$metaTags = ob_get_clean();

if (strpos($html, '</head>') !== false) {
    $html = str_replace('</head>', $metaTags . "\n</head>", $html);
}

echo $html;
