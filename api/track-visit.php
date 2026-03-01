<?php
// API: Track portfolio page visits
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

header('Content-Type: application/json');

$userId    = (int)($_POST['user_id'] ?? 0);
$ip        = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$referrer  = $_SERVER['HTTP_REFERER'] ?? '';

if (!$userId) { echo json_encode(['success' => false]); exit; }

// One insert per visit (we cap analytics display, not raw inserts)
db()->query(
    'INSERT INTO profile_visits (user_id, visit_date, ip_address, user_agent, referrer) VALUES (?, CURDATE(), ?, ?, ?)',
    [$userId, substr($ip, 0, 45), substr($userAgent, 0, 500), substr($referrer, 0, 500)]
);

echo json_encode(['success' => true]);
