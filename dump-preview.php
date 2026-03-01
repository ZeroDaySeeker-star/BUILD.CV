<?php
session_start();
$_SESSION['user_id'] = 1; // Assuming user ID 1
ob_start();
require __DIR__ . '/api/cv-preview.php';
$html = ob_get_clean();
file_put_contents(__DIR__ . '/preview-dump.html', $html);
echo "Dumped.";
