<?php
require 'c:/xampp/htdocs/BUILD.CV/config/config.php';
require 'c:/xampp/htdocs/BUILD.CV/config/database.php';

$res = db()->fetchAll("SELECT id, description FROM experience ORDER BY id DESC LIMIT 5");
$content = "";
foreach($res as $row) {
    $content .= "ID: " . $row['id'] . "\n";
    $content .= "Raw: " . $row['description'] . "\n";
    $content .= "Parsed: " . parse_markdown_to_html($row['description']) . "\n";
    $content .= "---------------------------\n";
}
file_put_contents('c:/xampp/htdocs/BUILD.CV/db-dump.txt', $content);
echo "Dumped.";
