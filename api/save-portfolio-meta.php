<?php
/**
 * POST /api/save-portfolio-meta.php
 * Update global portfolio metadata directly to the profiles table.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorisé']); exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']); exit;
}

$title = strip_tags(trim($data['title'] ?? ''));
$description = strip_tags(trim($data['description'] ?? ''));
$templateId = strip_tags(trim($data['template_id'] ?? 'portfolio_minimal'));
$isPublic = !empty($data['is_public']) ? 1 : 0;

try {
    // Check if profile exists
    $existing = db()->fetchOne('SELECT id FROM profiles WHERE user_id = ?', [$userId]);
    
    if ($existing) {
        db()->query(
            "UPDATE profiles SET title = ?, summary = ?, portfolio_template = ?, is_public = ? WHERE user_id = ?",
            [$title, $description, $templateId, $isPublic, $userId]
        );
    } else {
        db()->query(
            "INSERT INTO profiles (user_id, title, summary, portfolio_template, is_public) VALUES (?, ?, ?, ?, ?)",
            [$userId, $title, $description, $templateId, $isPublic]
        );
    }
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur serveur: ' . $e->getMessage()]);
}
