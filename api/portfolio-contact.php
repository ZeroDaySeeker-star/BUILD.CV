<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Public API endpoint for portfolio contact form
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$profileId   = (int)($data['profile_id'] ?? 0);
$senderName  = trim($data['name'] ?? '');
$senderEmail = trim($data['email'] ?? '');
$subject     = trim($data['subject'] ?? 'Message depuis votre portfolio');
$message     = trim($data['message'] ?? '');

if (!$profileId || !$senderName || !$senderEmail || !$message) {
    echo json_encode(['success' => false, 'error' => 'Veuillez remplir tous les champs obligatoires.']);
    exit;
}

if (!filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Adresse e-mail invalide.']);
    exit;
}

// Check if the profile owner is Premium (feature restriction)
$profile = db()->fetchOne("
    SELECT p.id, u.id as user_id, s.plan_id, pl.position as plan_level 
    FROM profiles p 
    JOIN users u ON p.user_id = u.id 
    LEFT JOIN subscriptions s ON u.id = s.user_id 
    LEFT JOIN plans pl ON s.plan_id = pl.id 
    WHERE p.id = ?", 
    [$profileId]
);

if (!$profile || ($profile['plan_level'] ?? 1) < 3) {
    echo json_encode(['success' => false, 'error' => 'Cette fonctionnalité est réservée aux comptes Premium.']);
    exit;
}

try {
    db()->execute(
        "INSERT INTO portfolio_messages (profile_id, sender_name, sender_email, subject, message) VALUES (?, ?, ?, ?, ?)",
        [$profileId, $senderName, $senderEmail, $subject, $message]
    );
    echo json_encode(['success' => true, 'message' => 'Message envoyé avec succès !']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'envoi du message.']);
}
