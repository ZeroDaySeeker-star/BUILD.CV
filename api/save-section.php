<?php
// API : Enregistrer/mettre à jour un élément de section répétable
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorisé']); exit;
}

$userId = $_SESSION['user_id'];
$body = json_decode(file_get_contents('php://input'), true);
if (!$body) { echo json_encode(['success' => false, 'error' => 'Entrée invalide']); exit; }

$type = $body['type'] ?? '';
$id   = $body['id'] ?? 'new';

// NOTE: Single-profile architecture — all tables are keyed by user_id directly.
// Legacy cv_id and portfolio_id parameters are accepted but ignored; we always use user_id.
// This was the source of the FK violation bugs (cv_projects FK to cvs table which no longer exists).

$allowedTypes = ['education','experience','skills','projects','languages','certifications'];
if (!in_array($type, $allowedTypes)) {
    echo json_encode(['success' => false, 'error' => 'Type inconnu']); exit;
}

// Sanitize an array of fields from the body
function sanitizeFieldsArray($body, $fields) {
    $vals = [];
    foreach ($fields as $f) {
        $val = trim($body[$f] ?? '');
        if ($val !== '' && in_array($f, ['project_url', 'github_url', 'cert_url'])) {
            if (!filter_var($val, FILTER_VALIDATE_URL)) {
                echo json_encode(['success' => false, 'error' => "URL invalide pour $f"]); exit;
            }
        }
        $vals[] = strip_tags($val);
    }
    return $vals;
}

// Audit helper
function log_audit($action, $entityType, $entityId, $details = []) {
    global $userId;
    $ip = substr($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '', 0, 45);
    $detailsJson = json_encode($details, JSON_UNESCAPED_UNICODE);
    try {
        db()->query('INSERT INTO audit_logs (user_id, action, entity_type, entity_id, details, ip_address) VALUES (?,?,?,?,?,?)', [$userId, $action, $entityType, $entityId, $detailsJson, $ip]);
    } catch (Exception $e) {
        // don't block main flow if audit fails
    }
}

$newId = null;
$performedAction = null;
$performedEntityId = null;

switch ($type) {
    case 'education':
        $fields = ['school','degree','field','start_year','end_year','description'];
        $vals   = sanitizeFieldsArray($body, $fields);
        if ($id === 'new' || !is_numeric($id)) {
            db()->query("INSERT INTO education (user_id,school,degree,field,start_year,end_year,description) VALUES (?,?,?,?,?,?,?)",
                array_merge([$userId], $vals));
            $newId = db()->lastInsertId();
            $performedAction = 'create';
            $performedEntityId = $newId;
        } else {
            db()->query("UPDATE education SET school=?,degree=?,field=?,start_year=?,end_year=?,description=? WHERE id=? AND user_id=?",
                array_merge($vals, [$id, $userId]));
            $performedAction = 'update';
            $performedEntityId = $id;
        }
        break;

    case 'experience':
        $fields = ['company','position','start_date','end_date','description'];
        $vals   = sanitizeFieldsArray($body, $fields);
        if ($id === 'new' || !is_numeric($id)) {
            db()->query("INSERT INTO experience (user_id,company,position,start_date,end_date,description) VALUES (?,?,?,?,?,?)",
                array_merge([$userId], $vals));
            $newId = db()->lastInsertId();
            $performedAction = 'create';
            $performedEntityId = $newId;
        } else {
            db()->query("UPDATE experience SET company=?,position=?,start_date=?,end_date=?,description=? WHERE id=? AND user_id=?",
                array_merge($vals, [$id, $userId]));
            $performedAction = 'update';
            $performedEntityId = $id;
        }
        break;

    case 'skills':
        $name  = strip_tags(trim($body['skill_name'] ?? ''));
        $level = (int)($body['skill_level'] ?? 75);
        if ($id === 'new' || !is_numeric($id)) {
            db()->query("INSERT INTO skills (user_id,skill_name,skill_level) VALUES (?,?,?)", [$userId, $name, $level]);
            $newId = db()->lastInsertId();
            $performedAction = 'create';
            $performedEntityId = $newId;
        } else {
            db()->query("UPDATE skills SET skill_name=?,skill_level=? WHERE id=? AND user_id=?", [$name, $level, $id, $userId]);
            $performedAction = 'update';
            $performedEntityId = $id;
        }
        break;

    case 'projects':
        $fields = ['title','description','link_url'];
        $vals   = sanitizeFieldsArray($body, $fields);
        if ($id === 'new' || !is_numeric($id)) {
            db()->query("INSERT INTO projects (user_id,title,description,link_url) VALUES (?,?,?,?)",
                array_merge([$userId], $vals));
            $newId = db()->lastInsertId();
            $performedAction = 'create';
            $performedEntityId = $newId;
        } else {
            db()->query("UPDATE projects SET title=?,description=?,link_url=? WHERE id=? AND user_id=?",
                array_merge($vals, [$id, $userId]));
            $performedAction = 'update';
            $performedEntityId = $id;
        }
        break;

    case 'languages':
        $name  = strip_tags(trim($body['language_name'] ?? ''));
        $prof  = $body['proficiency'] ?? 'Fluent';
        // Validate proficiency level against allowed values
        $allowedProfs = ['Basic', 'Conversational', 'Fluent', 'Native', 'Débutant', 'Intermédiaire', 'Courant', 'Natif'];
        if (!in_array($prof, $allowedProfs)) {
            $prof = 'Fluent';
        }
        if ($id === 'new' || !is_numeric($id)) {
            db()->query("INSERT INTO languages (user_id,language_name,proficiency) VALUES (?,?,?)", [$userId, $name, $prof]);
            $newId = db()->lastInsertId();
            $performedAction = 'create';
            $performedEntityId = $newId;
        } else {
            db()->query("UPDATE languages SET language_name=?,proficiency=? WHERE id=? AND user_id=?", [$name, $prof, $id, $userId]);
            $performedAction = 'update';
            $performedEntityId = $id;
        }
        break;

    case 'certifications':
        $fields = ['cert_name','issuer','issue_date','cert_url'];
        $vals   = sanitizeFieldsArray($body, $fields);
        if ($id === 'new' || !is_numeric($id)) {
            db()->query("INSERT INTO certifications (user_id,cert_name,issuer,issue_date,cert_url) VALUES (?,?,?,?,?)",
                array_merge([$userId], $vals));
            $newId = db()->lastInsertId();
            $performedAction = 'create';
            $performedEntityId = $newId;
        } else {
            db()->query("UPDATE certifications SET cert_name=?,issuer=?,issue_date=?,cert_url=? WHERE id=? AND user_id=?",
                array_merge($vals, [$id, $userId]));
            $performedAction = 'update';
            $performedEntityId = $id;
        }
        break;
}

// Record audit if we performed an action
if ($performedAction && $performedEntityId) {
    $actionName = ($performedAction === 'create' ? 'create_' : 'update_') . $type;
    log_audit($actionName, $type, $performedEntityId, ['payload' => $body]);
}

echo json_encode(['success' => true, 'id' => $newId ?: $id]);
