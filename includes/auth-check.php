<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Plans.php';

if (!isset($_SESSION['user_id'])) {
    $redirect = urlencode($_SERVER['REQUEST_URI']);
    header('Location: ' . APP_URL . '/auth/login.php?redirect=' . $redirect);
    exit;
}

// Refresh user data
$currentUser = db()->fetchOne('SELECT * FROM users WHERE id = ?', [$_SESSION['user_id']]);
if (!$currentUser) {
    session_destroy();
    header('Location: ' . APP_URL . '/auth/login.php');
    exit;
}

// Récupérer le plan actuel (via ProfileGuard / Plans)
$userPlan = Plans::getUserPlan($_SESSION['user_id']);
$planName = $userPlan['name'] ?? 'free';
$planLevel = $userPlan['position'] ?? 1;

// Keep session in sync
$_SESSION['plan']      = $planName;
$_SESSION['plan_level']= $planLevel;
$_SESSION['full_name'] = $currentUser['full_name'];

// Shorthand variables used by all dashboard pages
$userId    = (int)$_SESSION['user_id'];
$username  = $currentUser['username'];
$fullName  = $currentUser['full_name'] ?: $username;
$isPremium = ($planName === 'premium');

/**
 * Middleware: Vérifie si l'utilisateur possède au moins le niveau de plan requis
 * 1 = Free, 2 = Standard, 3 = Premium
 */
function checkPlan($requiredLevel) {
    if (!isset($_SESSION['plan_level']) || $_SESSION['plan_level'] < $requiredLevel) {
        header("Location: " . APP_URL . "/dashboard/upgrade.php");
        exit();
    }
}

// Chargement du profil – disponible sur toutes les pages du tableau de bord
$profile   = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]) ?: [];
