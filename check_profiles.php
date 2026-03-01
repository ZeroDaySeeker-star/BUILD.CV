<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
$cols = db()->fetchAll("DESCRIBE profiles");
echo "<pre>"; print_r($cols); echo "</pre>";
