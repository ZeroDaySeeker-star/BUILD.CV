<?php
require_once __DIR__ . '/config/auth.php';
requireAdmin();

$table = $_GET['table'] ?? null;
$id = $_GET['id'] ?? null; // If null, it's an INSERT (Add)
$isEditing = !empty($id);

// Configs for generating generic forms
$formConfigs = [
    'users' => [
        'title' => 'Utilisateur',
        'fields' => [
            'username' => ['type' => 'text', 'label' => 'Nom d\'utilisateur', 'required' => true],
            'email' => ['type' => 'email', 'label' => 'Adresse Email', 'required' => true],
            'plan' => ['type' => 'select', 'label' => 'Plan', 'options' => ['free' => 'Gratuit', 'premium' => 'Premium']],
            'is_admin' => ['type' => 'select', 'label' => 'Admin (User)', 'options' => [1 => 'Oui', 0 => 'Non']]
        ]
    ],
    'plans' => [
        'title' => 'Abonnement (Plan)',
        'fields' => [
            'name' => ['type' => 'text', 'label' => 'Clé (ex: premium)', 'required' => true],
            'display_name' => ['type' => 'text', 'label' => 'Nom Affiché', 'required' => true],
            'description' => ['type' => 'textarea', 'label' => 'Description'],
            'cv_limit' => ['type' => 'number', 'label' => 'Limite CV'],
            'portfolio_limit' => ['type' => 'number', 'label' => 'Limite Portfolio'],
            'price_monthly' => ['type' => 'number', 'label' => 'Prix Mensuel'],
            'price_yearly' => ['type' => 'number', 'label' => 'Prix Annuel'],
            'position' => ['type' => 'number', 'label' => 'Position (Ordre)'],
            'is_active' => ['type' => 'select', 'label' => 'Statut', 'options' => [1 => 'Oui', 0 => 'Non']]
        ]
    ],
    'templates' => [
        'title' => 'Modèle',
        'fields' => [
            'template_key' => ['type' => 'text', 'label' => 'Dossier/Clé', 'required' => true],
            'template_name' => ['type' => 'text', 'label' => 'Nom Affiché', 'required' => true],
            'template_type' => ['type' => 'select', 'label' => 'Type', 'options' => ['cv' => 'CV', 'portfolio' => 'Portfolio']],
            'plan_required' => ['type' => 'select', 'label' => 'Plan Requis', 'options' => ['free' => 'Gratuit', 'standard' => 'Standard', 'premium' => 'Premium']],
            'description' => ['type' => 'textarea', 'label' => 'Description'],
            'position' => ['type' => 'number', 'label' => 'Position (Ordre)'],
            'is_active' => ['type' => 'select', 'label' => 'Actif', 'options' => [1 => 'Oui', 0 => 'Non']]
        ]
    ],
    'admins' => [
        'title' => 'Administrateur',
        'role_required' => 'super_admin',
        'fields' => [
            'username' => ['type' => 'text', 'label' => 'Nom d\'utilisateur', 'required' => true],
            'email' => ['type' => 'email', 'label' => 'Email', 'required' => true],
            'password' => ['type' => 'password', 'label' => 'Mot de passe (laisser vide pour ne pas modifier)', 'required' => false],
            'role' => ['type' => 'select', 'label' => 'Rôle', 'options' => ['admin' => 'Administrateur', 'super_admin' => 'Super Administrateur']],
            'is_active' => ['type' => 'select', 'label' => 'Actif', 'options' => [1 => 'Oui', 0 => 'Non']]
        ]
    ]
];

if (!$table || !isset($formConfigs[$table])) {
    die("Table non autorisée ou non configurée pour l'édition dynamique.");
}

$config = $formConfigs[$table];

if (isset($config['role_required'])) {
    requireRole($config['role_required']);
}

$pageTitle = ($isEditing ? "Modifier " : "Ajouter ") . $config['title'];
$activeMenu = $table;
$db = db();
$record = [];

// Fetch existing record
if ($isEditing) {
    $record = $db->fetchOne("SELECT * FROM {$table} WHERE id = ?", [$id]);
    if (!$record) die("Enregistrement introuvable.");
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyAdminCsrf();
    
    $data = [];
    $errors = [];
    
    // Validate inputs based on config
    foreach ($config['fields'] as $key => $fieldConfig) {
        $val = trim($_POST[$key] ?? '');
        
        // Special case for password hashing in admins table
        if ($key === 'password' && $table === 'admins') {
            if (!empty($val)) {
                $data['password_hash'] = password_hash($val, PASSWORD_DEFAULT);
            } elseif (!$isEditing && $fieldConfig['required']) {
                $errors[] = "Le mot de passe est obligatoire.";
            }
            continue;
        }
        
        if (empty($val) && !empty($fieldConfig['required']) && $val !== '0') {
            $errors[] = "Le champ {$fieldConfig['label']} est requis.";
        }
        
        if (!empty($val) || $val === '0') {
            $data[$key] = $val;
        }
    }
    
    if (empty($errors)) {
        try {
            if ($isEditing) {
                // UPDATE
                $setParts = [];
                $params = [];
                foreach ($data as $k => $v) {
                    $setParts[] = "`$k` = ?";
                    $params[] = $v;
                }
                $params[] = $id; // For WHERE id = ?
                
                if (!empty($setParts)) {
                    $db->query("UPDATE {$table} SET " . implode(', ', $setParts) . " WHERE id = ?", $params);
                    logAdminAction("UPDATE", $table, $id, $data);
                }
                
                $_SESSION['flash_success'] = "Enregistrement mis à jour avec succès.";
            } else {
                // INSERT
                $cols = array_keys($data);
                $placeholders = array_fill(0, count($cols), '?');
                $params = array_values($data);
                
                $db->query(
                    "INSERT INTO {$table} (`" . implode('`, `', $cols) . "`) VALUES (" . implode(', ', $placeholders) . ")",
                    $params
                );
                
                $newId = $db->getConnection()->lastInsertId();
                logAdminAction("CREATE", $table, $newId, $data);
                $_SESSION['flash_success'] = "Nouvel enregistrement créé avec succès.";
            }
            
            header("Location: crud.php?table={$table}");
            exit;
            
        } catch (Exception $e) {
            $errors[] = "Erreur de base de données : " . $e->getMessage();
        }
    }
}

ob_start();
?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title"><?= htmlspecialchars($pageTitle) ?></h2>
        <a href="crud.php?table=<?= $table ?>" class="btn btn-sm">← Retour</a>
    </div>
    <div class="card-body">
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="padding-left: 20px;">
                    <?php foreach($errors as $err) echo "<li>" . htmlspecialchars($err) . "</li>"; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= generateAdminCsrf() ?>">
            
            <?php foreach ($config['fields'] as $key => $fieldConfig): ?>
                <?php 
                    $value = $_POST[$key] ?? ($record[$key] ?? '');
                    if ($key === 'password') $value = ''; // Never echo password hashes
                ?>
                <div class="form-group">
                    <label><?= htmlspecialchars($fieldConfig['label']) ?> <?= !empty($fieldConfig['required']) ? '<span style="color:var(--error)">*</span>' : '' ?></label>
                    
                    <?php if ($fieldConfig['type'] === 'select'): ?>
                        <select name="<?= $key ?>" class="form-control" <?= !empty($fieldConfig['required']) ? 'required' : '' ?>>
                            <?php foreach ($fieldConfig['options'] as $val => $label): ?>
                                <option value="<?= $val ?>" <?= (string)$val === (string)$value ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                    <?php elseif ($fieldConfig['type'] === 'textarea'): ?>
                        <textarea name="<?= $key ?>" class="form-control" rows="5" <?= !empty($fieldConfig['required']) ? 'required' : '' ?>><?= htmlspecialchars($value) ?></textarea>
                        
                    <?php else: ?>
                        <input type="<?= $fieldConfig['type'] ?>" name="<?= $key ?>" class="form-control" 
                               value="<?= htmlspecialchars((string)$value) ?>" 
                               <?= !empty($fieldConfig['required']) && ($key !== 'password' || !$isEditing) ? 'required' : '' ?>>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <div style="margin-top: 2rem; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary"><?= $isEditing ? 'Mettre à jour' : 'Créer' ?></button>
                <a href="crud.php?table=<?= $table ?>" class="btn">Annuler</a>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/views/layouts/main.php';
?>
