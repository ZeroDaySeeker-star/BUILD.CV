<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/PremiumCode.php';

header('Content-Type: application/json');

// Vérification CSRF stricte (POST uniquement ou Jeton API)
$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// Support form ou JSON
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$csrf = $input['csrf_token'] ?? '';
if (!verifyCsrfToken($csrf) && !verifyApiCsrf(false)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Jeton de sécurité invalide.']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Veuillez vous connecter.']);
    exit;
}

$userId = $_SESSION['user_id'];
$code = trim($input['code'] ?? '');
$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

if (empty($code)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Le code est requis.']);
    exit;
}

// Process validation & redemption
$result = PremiumCode::redeem($userId, $code, $ipAddress);

if ($result['success']) {
    http_response_code(200);
} else {
    // Si c'est une erreur de blocage bruteforce, on renvoie une 429 Too Many Requests
    if (strpos($result['error'], 'Trop de tentatives') !== false) {
        http_response_code(429);
    } else {
        http_response_code(400);
    }
}

echo json_encode($result);
