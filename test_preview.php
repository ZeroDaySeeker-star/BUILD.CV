<?php
$_SESSION = ['user_id' => 8];
ob_start();
require 'c:\xampp\htdocs\BUILD.CV\config\config.php';
require 'c:\xampp\htdocs\BUILD.CV\config\database.php';
$userId = 8;
$profile = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]) ?? [];
$template = $profile['cv_template'] ?? 'minimal';
$templateFile = 'c:\xampp\htdocs\BUILD.CV\templates\cv\\' . preg_replace('/[^a-z0-9_-]/', '', $template) . '.php';
if (!file_exists($templateFile)) $templateFile = 'c:\xampp\htdocs\BUILD.CV\templates\cv\minimal.php';

echo "FILE LOADED: " . $templateFile . "\n";
// include $templateFile; // We just need to know if the path is right!
?>
