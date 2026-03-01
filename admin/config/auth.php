<?php
// BUILD.CV Admin - Auth Middleware & Helpers
require_once __DIR__ . '/config.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require an authenticated admin to access the page
 */
function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
}

/**
 * Require a specific role (e.g., 'super_admin')
 */
function requireRole($role) {
    requireAdmin();
    if (($_SESSION['admin_role'] ?? '') !== $role) {
        http_response_code(403);
        die("Accès refusé. Privilèges insuffisants.");
    }
}

/**
 * Generate a CSRF token
 */
function generateAdminCsrf() {
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['admin_csrf_token'];
}

/**
 * Verify a CSRF token from POST payload
 */
function verifyAdminCsrf() {
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['admin_csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die("Erreur CSRF : Token invalide ou expiré.");
    }
}

/**
 * Log an administrative action
 */
function logAdminAction($action, $tableName = null, $recordId = null, $details = null) {
    if (!isset($_SESSION['admin_id'])) return;
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $detailsJson = $details ? json_encode($details) : null;
    
    try {
        db()->query(
            "INSERT INTO admin_logs (admin_id, action, table_name, record_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)",
            [$_SESSION['admin_id'], $action, $tableName, $recordId, $detailsJson, $ip]
        );
    } catch (Exception $e) {
        // Silently fail logging rather than breaking the app
        error_log("Admin log failure: " . $e->getMessage());
    }
}
