<?php
require_once __DIR__ . '/auth-check.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=premium_codes_export_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');

// Add BOM for Excel UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

fputcsv($output, ['ID', 'Code', 'Plan', 'Durée (Mois)', 'Max Utilisations', 'Utilisé', 'Statut', 'Date Expiration', 'Créé le', 'Utilisé Par (IDs & Emails)']);

$codes = db()->fetchAll("
    SELECT c.*, p.display_name as plan_name,
    (
        SELECT GROUP_CONCAT(CONCAT(u.id, ':', u.email) SEPARATOR ', ')
        FROM code_usages cu
        JOIN users u ON cu.user_id = u.id
        WHERE cu.code_id = c.id
    ) as used_by
    FROM premium_codes c 
    JOIN plans p ON c.plan_id = p.id 
    ORDER BY c.created_at DESC
");

foreach ($codes as $c) {
    fputcsv($output, [
        $c['id'],
        $c['code'],
        $c['plan_name'],
        $c['duration_months'],
        $c['max_uses'],
        $c['used_count'],
        $c['status'],
        $c['expires_at'] ?? 'Jamais',
        $c['created_at'],
        $c['used_by'] ?? 'Personne'
    ]);
}

fclose($output);
exit;
