<?php
// API: Get available templates for current plan
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();
require_once __DIR__ . '/../classes/Plans.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non authentifié']);
    exit;
}

$userId = $_SESSION['user_id'];
$type = $_GET['type'] ?? null; // 'cv' or 'portfolio'

if ($type && !in_array($type, ['cv', 'portfolio'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Type invalide']);
    exit;
}

$templates = Plans::getAvailableTemplates($userId, $type);
$plan = Plans::getUserPlan($userId);

echo json_encode([
    'success' => true,
    'current_plan' => $plan['name'],
    'templates' => $templates
]);
