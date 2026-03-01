<?php
require_once __DIR__ . '/config/auth.php';

if (isset($_SESSION['admin_id'])) {
    logAdminAction("LOGOUT");
    
    // Unset admin-specific session variables
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_role']);
    unset($_SESSION['admin_csrf_token']);
}

header('Location: ' . ADMIN_URL . '/login.php');
exit;
