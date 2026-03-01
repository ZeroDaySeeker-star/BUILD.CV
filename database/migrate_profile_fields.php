<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = db();
echo "Migration: Adding missing profile fields...\n";

try {
    $db->query("ALTER TABLE profiles ADD COLUMN instagram VARCHAR(255) NULL AFTER github");
    echo "Added: instagram\n";
} catch (Exception $e) { echo "Skip: instagram (maybe exists)\n"; }

try {
    $db->query("ALTER TABLE profiles ADD COLUMN twitter VARCHAR(255) NULL AFTER instagram");
    echo "Added: twitter\n";
} catch (Exception $e) { echo "Skip: twitter (maybe exists)\n"; }

try {
    $db->query("ALTER TABLE profiles ADD COLUMN hobbies TEXT NULL AFTER summary");
    echo "Added: hobbies\n";
} catch (Exception $e) { echo "Skip: hobbies (maybe exists)\n"; }

echo "Migration finished.\n";
