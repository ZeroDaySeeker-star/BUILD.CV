<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = db();
echo "Syncing templates...\n";

// 1. CLEANUP EXISTING CVs and PORTFOLIOs to fix prefixes if needed
// Actually, let's just make sure we have entries for all files.

$cvFiles = glob(__DIR__ . '/../templates/cv/*.php');
$portfolioFiles = glob(__DIR__ . '/../templates/portfolio/*.php');

function syncType($db, $files, $type) {
    echo "Processing $type...\n";
    foreach ($files as $file) {
        $key = basename($file, '.php');
        
        // Skip some common non-template files if any (e.g. index.php)
        if ($key === 'index') continue;

        // Check if exists
        $exists = $db->fetchOne("SELECT id FROM templates WHERE template_key = ? AND template_type = ?", [$key, $type]);
        
        if (!$exists) {
            // Determine plan required based on naming usually or default to premium if it's the 10 "new" ones
            $plan = 'premium';
            // Basic ones can be free/standard
            $basicCVs = ['minimal', 'modern', 'professional', 'creative', 'compact', 'elegant', 'executive'];
            $basicPortfolios = ['minimal', 'developer', 'designer', 'dark', 'gallery', 'agency', 'architect'];
            
            if ($type === 'cv' && in_array($key, $basicCVs)) $plan = 'free';
            if ($type === 'portfolio' && in_array($key, $basicPortfolios)) $plan = 'free';
            
            $name = ucwords(str_replace(['-', '_'], ' ', $key));
            
            $db->query("INSERT INTO templates (template_key, template_name, template_type, plan_required, is_active) 
                        VALUES (?, ?, ?, ?, 1)", 
                        [$key, $name, $type, $plan]);
            echo "INSERTED: $key ($type) as $plan\n";
        } else {
            echo "EXISTS: $key ($type)\n";
        }
    }
}

syncType($db, $cvFiles, 'cv');
syncType($db, $portfolioFiles, 'portfolio');

// Specific cleanup: if there are portfolio_minimal etc keys from before, we might want to remove them or they are duplicates
// Let's check for old keys
$oldKeys = $db->fetchAll("SELECT id, template_key FROM templates WHERE template_key LIKE 'portfolio_%'");
foreach ($oldKeys as $old) {
    $realKey = str_replace('portfolio_', '', $old['template_key']);
    // If the real key already exists, delete the old one
    $check = $db->fetchOne("SELECT id FROM templates WHERE template_key = ? AND template_type = 'portfolio' AND id != ?", [$realKey, $old['id']]);
    if ($check) {
        $db->query("DELETE FROM templates WHERE id = ?", [$old['id']]);
        echo "DELETED DUPLICATE: " . $old['template_key'] . "\n";
    } else {
        // Just rename
        $db->query("UPDATE templates SET template_key = ? WHERE id = ?", [$realKey, $old['id']]);
        echo "RENAMED: " . $old['template_key'] . " -> $realKey\n";
    }
}

echo "Sync finished.\n";
