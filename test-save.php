<?php
require_once __DIR__ . '/config/config.php';
$_SESSION['user_id'] = 1; // Assuming user 1 exists

// Generate CSRF token
$token = generateCsrfToken();

$ch = curl_init('http://localhost/BUILD.CV/api/save-cv.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'full_name' => 'Test Name',
    'title' => 'Dev',
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-CSRF-Token: ' . $token,
    'Cookie: PHPSESSID=' . session_id()
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpcode\n";
echo "Response:\n$response\n";
if ($error) echo "cURL Error: $error\n";
