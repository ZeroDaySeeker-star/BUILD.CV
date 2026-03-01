<?php
// Aperçu CV – affiche le modèle de CV inline pour la prévisualisation en iframe
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

if (!isset($_SESSION['user_id'])) {
    echo '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;color:#666">Veuillez vous connecter pour voir l\'aperçu</div>'; exit;
}

$userId = $_SESSION['user_id'];

$profile        = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]) ?? [];

$education      = db()->fetchAll('SELECT * FROM education WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]);
$experience     = db()->fetchAll('SELECT * FROM experience WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]);
$skills         = db()->fetchAll('SELECT * FROM skills WHERE user_id = ? ORDER BY sort_order, id ASC', [$userId]);
$projects       = db()->fetchAll('SELECT * FROM projects WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]);
$languages      = db()->fetchAll('SELECT * FROM languages WHERE user_id = ? ORDER BY sort_order', [$userId]);
$certifications = db()->fetchAll('SELECT * FROM certifications WHERE user_id = ? ORDER BY sort_order', [$userId]);
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

$template = $profile['cv_template'] ?? 'minimal';
$templateFile = __DIR__ . '/../templates/cv/' . preg_replace('/[^a-z0-9_-]/', '', $template) . '.php';
if (!file_exists($templateFile)) $templateFile = __DIR__ . '/../templates/cv/minimal.php';

include $templateFile;
