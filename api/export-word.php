<?php
require_once __DIR__ . '/../includes/auth-check.php';
require_once __DIR__ . '/../classes/LimitsMiddleware.php';

$userId = $_SESSION['user_id'];

// Check if user has Premium plan
$planLevel = $_SESSION['plan_level'] ?? 1;
if ($planLevel < 3) {
    echo "Cette fonctionnalité est réservée aux abonnés Premium. Veuillez mettre à niveau votre plan.";
    exit;
}

$profile = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]) ?? [];
$education = db()->fetchAll('SELECT * FROM education WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$experience = db()->fetchAll('SELECT * FROM experience WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$skills = db()->fetchAll('SELECT * FROM skills WHERE user_id = ? ORDER BY sort_order, id ASC', [$userId]) ?? [];
$projects = db()->fetchAll('SELECT * FROM projects WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$languages = db()->fetchAll('SELECT * FROM languages WHERE user_id = ? ORDER BY sort_order', [$userId]) ?? [];
$certifications = db()->fetchAll('SELECT * FROM certifications WHERE user_id = ? ORDER BY sort_order', [$userId]) ?? [];

$templateName = $profile['cv_template'] ?? 'minimal';
$templateFile = __DIR__ . '/../templates/cv/' . htmlspecialchars($templateName) . '.php';

if (!file_exists($templateFile)) {
    echo "Template non trouvé.";
    exit;
}

ob_start();
include $templateFile;
$html = ob_get_clean();

$filename = 'CV_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $profile['full_name'] ?? 'cv') . '_' . date('Y') . '.doc';

header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=$filename");

echo "<html>";
echo "<head><meta charset='UTF-8'></head>";
echo "<body>";
echo $html;
echo "</body>";
echo "</html>";
?>
