<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
$db = db();
$templates = $db->fetchAll("SELECT template_key, template_type FROM templates");
echo json_encode($templates);
