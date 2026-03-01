<?php
// API: Get user limits and usage
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
$limits = Plans::getUserLimits($userId);

echo json_encode([
    'success' => true,
    'limits' => $limits
]);
