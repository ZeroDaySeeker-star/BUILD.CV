<?php
// API : Activer un code d'abonnement
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorisé']); exit;
}

$userId = $_SESSION['user_id'];
$body = json_decode(file_get_contents('php://input'), true);
$inputCode = strtoupper(trim($body['code'] ?? ''));

if (empty($inputCode)) {
    echo json_encode(['success' => false, 'error' => 'Veuillez entrer un code.']); exit;
}

// 1. Chercher le code
$codeRow = db()->fetchOne("SELECT * FROM subscription_codes WHERE code = ?", [$inputCode]);

if (!$codeRow) {
    echo json_encode(['success' => false, 'error' => 'Ce code est invalide.']); exit;
}

// 2. Vérifier le statut
if ($codeRow['status'] !== 'active') {
    echo json_encode(['success' => false, 'error' => 'Ce code a déjà été utilisé ou est expiré.']); exit;
}

// Vérifier l'expiration
if (strtotime($codeRow['expires_at']) < time()) {
    db()->query("UPDATE subscription_codes SET status = 'expired' WHERE id = ?", [$codeRow['id']]);
    echo json_encode(['success' => false, 'error' => 'Ce code a expiré. Veuillez contacter le support.']); exit;
}

// 3. Activer l'abonnement
try {
    db()->beginTransaction();

    $planId = $codeRow['plan_id'];
    $startDate = date('Y-m-d H:i:s');
    $endDate = date('Y-m-d H:i:s', strtotime('+1 year')); // Standard 1 an

    // Marquer le code comme utilisé
    db()->query(
        "UPDATE subscription_codes SET status = 'used', used_by = ?, used_at = ? WHERE id = ?",
        [$userId, $startDate, $codeRow['id']]
    );

    // Mettre à jour ou créer l'abonnement
    $existingSub = db()->fetchOne("SELECT id FROM subscriptions WHERE user_id = ?", [$userId]);
    
    if ($existingSub) {
        db()->query(
            "UPDATE subscriptions SET plan_id = ?, status = 'active', start_date = ?, end_date = ? WHERE user_id = ?",
            [$planId, $startDate, $endDate, $userId]
        );
    } else {
        db()->query(
            "INSERT INTO subscriptions (user_id, plan_id, status, start_date, end_date) VALUES (?, ?, 'active', ?, ?)",
            [$userId, $planId, $startDate, $endDate]
        );
    }

    // Récupérer les nouveaux templates débloqués
    $planRules = db()->fetchOne("SELECT cv_limit, portfolio_limit, display_name FROM plans WHERE id = ?", [$planId]);

    db()->commit();
    
    // Mettre à jour la session
    $_SESSION['plan_level'] = $planId;

    echo json_encode([
        'success' => true, 
        'message' => 'Félicitations ! Votre abonnement ' . ($planRules['display_name'] ?? '') . ' est maintenant actif.'
    ]);
    
} catch (Exception $e) {
    db()->rollBack();
    echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'activation: ' . $e->getMessage()]);
}
