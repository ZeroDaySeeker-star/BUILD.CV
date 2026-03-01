<?php
require_once __DIR__ . '/config/auth.php';
requireRole('super_admin'); // Only super admins can backup DB

$db = db();

// In our setup, auth.php -> config.php loads .env variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? $_ENV['DB_PASS'] ?? '';
$name = $_ENV['DB_NAME'] ?? 'buildcv';

$filename = "backup_{$name}_" . date("Y-m-d_H-i-s") . ".sql";

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"{$filename}\"");

// Output SQL dump
$out = fopen('php://output', 'w');

fwrite($out, "-- BUILD.CV Database Backup\n");
fwrite($out, "-- Created: " . date("Y-m-d H:i:s") . "\n\n");
fwrite($out, "SET FOREIGN_KEY_CHECKS=0;\n\n");

$tables = $db->fetchAll("SHOW TABLES");
foreach($tables as $t) {
    $tableName = array_values($t)[0];
    
    // Create Table Schema
    $create = $db->fetchOne("SHOW CREATE TABLE `{$tableName}`");
    fwrite($out, "-- Table structure for table `{$tableName}`\n");
    fwrite($out, "DROP TABLE IF EXISTS `{$tableName}`;\n");
    fwrite($out, $create['Create Table'] . ";\n\n");
    
    // Insert Data
    $rows = $db->fetchAll("SELECT * FROM `{$tableName}`");
    if (count($rows) > 0) {
        fwrite($out, "-- Dumping data for table `{$tableName}`\n");
        $cols = array_keys($rows[0]);
        $colsSql = "`" . implode("`, `", $cols) . "`";
        
        foreach($rows as $row) {
            $vals = [];
            foreach($row as $val) {
                if (is_null($val)) $vals[] = "NULL";
                else $vals[] = "'" . addslashes((string)$val) . "'";
            }
            $valsSql = implode(", ", $vals);
            fwrite($out, "INSERT INTO `{$tableName}` ({$colsSql}) VALUES ({$valsSql});\n");
        }
        fwrite($out, "\n");
    }
}

fwrite($out, "SET FOREIGN_KEY_CHECKS=1;\n");
fclose($out);

logAdminAction("DB_BACKUP");
exit;
