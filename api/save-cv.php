<?php
// API : Enregistrer les informations personnelles et la photo de profil
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autorisé']); exit;
}

$userId = $_SESSION['user_id'];

// Gérer le téléchargement de la photo
$photoFilename = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['photo'];
    $fileInfo = @getimagesize($file['tmp_name']);
    if ($fileInfo === false || !in_array($fileInfo['mime'], ALLOWED_IMAGE_TYPES) || !in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
        echo json_encode(['success' => false, 'error' => 'Type d\'image invalide ou fichier corrompu']); exit;
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        echo json_encode(['success' => false, 'error' => 'Image trop volumineuse']); exit;
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    // Force extension based on mime type to avoid fake extensions
    $mimeToExt = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $ext = $mimeToExt[$fileInfo['mime']] ?? 'jpg';
    
    $photoFilename = 'photo_' . $userId . '_' . time() . '.' . $ext;
    if (!is_dir(UPLOAD_DIR)) {
        if (!mkdir(UPLOAD_DIR, 0755, true)) {
            echo json_encode(['success' => false, 'error' => 'Impossible de créer le répertoire d\'upload']); exit;
        }
    }
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $photoFilename)) {
        echo json_encode(['success' => false, 'error' => 'Impossible de télécharger la photo']); exit;
    }
}

$fields = ['full_name','title','email','phone','location','website','linkedin','github','instagram', 'twitter', 'summary', 'hobbies'];
$data = [];
foreach ($fields as $f) {
    $val = trim($_POST[$f] ?? '');
    if ($val !== '' && in_array($f, ['website', 'linkedin', 'github'])) {
        if (!filter_var($val, FILTER_VALIDATE_URL)) {
            echo json_encode(['success' => false, 'error' => "URL invalide pour le champ $f"]); exit;
        }
    }
    if ($f === 'email' && $val !== '' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => "Email invalide"]); exit;
    }
    $data[$f] = strip_tags($val); // XSS prevention: strip HTML tags
}

// Construire la requête de mise à jour
$sets = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
$values = array_values($data);

if ($photoFilename) {
    $sets    .= ', profile_photo = ?';
    $values[] = $photoFilename;
}

$values[] = $userId;

$existing = db()->fetchOne('SELECT id FROM profiles WHERE user_id = ?', [$userId]);
if ($existing) {
    db()->query("UPDATE profiles SET $sets WHERE user_id = ?", $values);
} else {
    // Build INSERT with proper columns and values
    $insertCols = array_keys($data);
    $insertCols[] = 'user_id';
    $insertCols[] = 'cv_template';
    $insertCols[] = 'portfolio_template';
    
    $insertVals = array_values($data);
    $insertVals[] = $userId;
    $insertVals[] = 'minimal';
    $insertVals[] = 'portfolio_minimal';
    
    // Add profile_photo if uploaded
    if ($photoFilename) {
        $insertCols[] = 'profile_photo';
        $insertVals[] = $photoFilename;
    }
    
    $colStr = implode(',', $insertCols);
    $placeholders = implode(',', array_fill(0, count($insertVals), '?'));
    db()->query("INSERT INTO profiles ($colStr) VALUES ($placeholders)", $insertVals);
}

// Mettre à jour également users.full_name
if ($data['full_name']) {
    db()->query('UPDATE users SET full_name = ? WHERE id = ?', [$data['full_name'], $userId]);
    $_SESSION['full_name'] = $data['full_name'];
}

echo json_encode(['success' => true, 'photo' => $photoFilename]);
