<?php
// API : Sauvegarder une lettre de motivation générée
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorisé']); exit;
}

$userId = $_SESSION['user_id'];
$body = json_decode(file_get_contents('php://input'), true);

if (!$body || empty($body['content'])) {
    echo json_encode(['success' => false, 'error' => 'Données invalides']); exit;
}

$jobTitle = strip_tags($body['job_title'] ?? 'Sans titre');
$company  = strip_tags($body['company'] ?? '');
$content  = $body['content']; // Keep formatting

try {
    db()->query(
        "INSERT INTO cover_letters (user_id, job_title, company, content) VALUES (?, ?, ?, ?)",
        [$userId, $jobTitle, $company, $content]
    );
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur BDD: ' . $e->getMessage()]);
}
