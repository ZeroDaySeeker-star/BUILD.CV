<?php
// API : Supprimer un élément de section
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorisé']); exit;
}

$userId = $_SESSION['user_id'];
$body = json_decode(file_get_contents('php://input'), true);
$type = $body['type'] ?? '';
$id   = (int)($body['id'] ?? 0);
// NOTE: Legacy cv_id ignored (single-profile architecture).

$map = [
    'education'      => 'education',
    'experience'     => 'experience',
    'skills'         => 'skills',
    'projects'       => 'projects',
    'languages'      => 'languages',
    'certifications' => 'certifications',
];

if (!isset($map[$type]) || !$id) {
    echo json_encode(['success' => false, 'error' => 'Requête invalide']); exit;
}

// Always delete from flat user-scoped table
$table = $map[$type];
db()->query("DELETE FROM {$table} WHERE id = ? AND user_id = ?", [$id, $userId]);

echo json_encode(['success' => true]);
