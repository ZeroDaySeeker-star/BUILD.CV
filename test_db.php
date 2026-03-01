<?php
require 'c:\xampp\htdocs\BUILD.CV\config\database.php';
$p = db()->fetchAll('SELECT user_id, full_name, cv_template, portfolio_template FROM profiles');
echo json_encode($p, JSON_PRETTY_PRINT);
?>
