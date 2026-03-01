<?php
// Include global DB configuration first to load APP_URL
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// BUILD.CV Admin - Configuration Constants
define('ADMIN_PATH', __DIR__ . '/..');
define('ADMIN_ASSETS', APP_URL . '/admin/assets');
define('ADMIN_URL', APP_URL . '/admin');

// Ensure error logging is set up for admin
if (!is_dir(ADMIN_PATH . '/logs')) {
    mkdir(ADMIN_PATH . '/logs', 0755, true);
}
ini_set('error_log', ADMIN_PATH . '/logs/error.log');
