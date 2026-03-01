<?php
// Vérification d'authentification pour les pages d'administration
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: ' . APP_URL . '/admin/login.php');
    exit;
}
