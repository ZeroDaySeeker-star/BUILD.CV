<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';

verifyApiCsrf();

if (($_SESSION['plan_level'] ?? 1) < 3) {
    echo json_encode(['success' => false, 'error' => 'Premium required']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];
$action = $data['action'] ?? '';

if ($action === 'create') {
    $company = $data['company'] ?? '';
    $position = $data['position'] ?? '';
    $status = $data['status'] ?? 'Applied';
    $applied_date = $data['applied_date'] ?? date('Y-m-d');
    $notes = $data['notes'] ?? '';

    if (!$company || !$position) {
        echo json_encode(['success' => false, 'error' => 'Champs obligatoires manquants']);
        exit;
    }

    $id = db()->execute(
        "INSERT INTO job_applications (user_id, company, position, status, applied_date, notes) VALUES (?, ?, ?, ?, ?, ?)",
        [$userId, $company, $position, $status, $applied_date, $notes]
    );

    echo json_encode(['success' => true, 'id' => db()->lastInsertId()]);
} elseif ($action === 'delete') {
    $id = $data['id'] ?? 0;
    db()->execute("DELETE FROM job_applications WHERE id = ? AND user_id = ?", [$id, $userId]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Action invalide']);
}
