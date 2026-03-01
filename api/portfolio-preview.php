<?php
// Aperçu Portfolio – affiche le modèle de portfolio inline pour la prévisualisation en iframe
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

if (!isset($_SESSION['user_id'])) {
    echo '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;color:#666">Veuillez vous connecter pour voir l\'aperçu</div>'; exit;
}

$userId = $_SESSION['user_id'];

$profile = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]) ?? [];
// Ensure profile has summary field (some installs use 'bio' field name)
if (empty($profile['summary']) && !empty($profile['bio'])) {
    $profile['summary'] = $profile['bio'];
}

$projects  = db()->fetchAll('SELECT * FROM projects WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$skills    = db()->fetchAll('SELECT * FROM skills WHERE user_id = ? ORDER BY sort_order, id ASC', [$userId]) ?? [];
$education = db()->fetchAll('SELECT * FROM education WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$experience = db()->fetchAll('SELECT * FROM experience WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$languages  = db()->fetchAll('SELECT * FROM languages WHERE user_id = ? ORDER BY sort_order', [$userId]) ?? [];

$template = $profile['portfolio_template'] ?? 'minimal';

// sanitize template
$template = preg_replace('/[^a-z0-9_-]/', '', strtolower($template));
$tplFile = __DIR__ . '/../templates/portfolio/' . $template . '.php';
if (!file_exists($tplFile)) {
    $tplFile = __DIR__ . '/../templates/portfolio/minimal.php';
}
if (!file_exists($tplFile)) {
    echo '<div style="color:#666;font-family:sans-serif;padding:2rem">Template introuvable</div>'; exit;
}

include $tplFile;
