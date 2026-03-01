<?php
require_once __DIR__ . '/config/auth.php';
requireAdmin();

// Generic CRUD Controller
$table = $_GET['table'] ?? 'users';
$action = $_GET['action'] ?? 'list';
$pageTitle = "Gestion : " . ucfirst($table);
$activeMenu = $table;

// Allowed tables mapping (Table Name => Primary Key, Display Columns)
$allowedTables = [
    'users'         => ['pk' => 'id', 'cols' => ['id', 'username', 'email', 'plan', 'is_admin', 'created_at']],
    'plans'         => ['pk' => 'id', 'cols' => ['id', 'name', 'display_name', 'price_monthly', 'position', 'is_active']],
    'templates'     => ['pk' => 'id', 'cols' => ['id', 'template_name', 'template_type', 'plan_required', 'position', 'is_active']],
    'subscriptions' => ['pk' => 'id', 'cols' => ['id', 'user_id', 'plan_id', 'status', 'start_date', 'end_date']],
    'admins'        => ['pk' => 'id', 'cols' => ['id', 'username', 'email', 'role', 'is_active'], 'role_required' => 'super_admin'],
    'admin_logs'    => ['pk' => 'id', 'cols' => ['id', 'admin_id', 'action', 'table_name', 'created_at'], 'role_required' => 'super_admin']
];

if (!isset($allowedTables[$table])) {
    die("Table non autorisée.");
}

$tableConfig = $allowedTables[$table];
$pk = $tableConfig['pk'];

// Role checking for sensitive tables
if (isset($tableConfig['role_required'])) {
    requireRole($tableConfig['role_required']);
}

$db = db();
$error = '';

// --- HANDLE DELETE ---
if ($action === 'delete') {
    $id = $_GET['id'] ?? null;
    $csrf = $_GET['csrf'] ?? '';
    
    if ($id && hash_equals(generateAdminCsrf(), $csrf)) {
        try {
            // Prevent deleting self if in admins table
            if ($table === 'admins' && $id == $_SESSION['admin_id']) {
                $_SESSION['flash_error'] = "Vous ne pouvez pas supprimer votre propre compte.";
            } else {
                $db->query("DELETE FROM {$table} WHERE {$pk} = ?", [$id]);
                logAdminAction("DELETE", $table, $id);
                $_SESSION['flash_success'] = "Enregistrement supprimé avec succès.";
            }
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Erreur lors de la suppression : " . $e->getMessage();
        }
    } else {
        $_SESSION['flash_error'] = "Jeton de sécurité invalide.";
    }
    header("Location: crud.php?table={$table}");
    exit;
}

// --- HANDLE EXPORT CSV ---
if ($action === 'export') {
    $search = $_GET['search'] ?? '';
    $query = "SELECT * FROM {$table}";
    $params = [];
    
    if ($search) {
        $searchCols = [];
        foreach($tableConfig['cols'] as $col) {
            $searchCols[] = "$col LIKE ?";
            $params[] = "%$search%";
        }
        $query .= " WHERE " . implode(' OR ', $searchCols);
    }
    
    $records = $db->fetchAll($query, $params);
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='. $table . '_export_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    
    // Output headers if rows exist
    if (count($records) > 0) {
        fputcsv($output, array_keys($records[0]));
        foreach ($records as $row) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
    exit;
}

// --- HANDLE LISTING (Pagination, Search, Sort) ---
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

$sortCol = $_GET['sort'] ?? $pk;
if (!in_array($sortCol, $tableConfig['cols'])) $sortCol = $pk;

$sortDir = strtoupper($_GET['dir'] ?? 'DESC');
if ($sortDir !== 'ASC') $sortDir = 'DESC';

// Build Query
$whereClause = "";
$params = [];

if ($search) {
    $searchCols = [];
    foreach($tableConfig['cols'] as $col) {
        $searchCols[] = "$col LIKE ?";
        $params[] = "%$search%";
    }
    $whereClause = "WHERE " . implode(' OR ', $searchCols);
}

// Get Total Count
$countQuery = "SELECT COUNT(*) as c FROM {$table} {$whereClause}";
$totalRecords = $db->fetchOne($countQuery, $params)['c'] ?? 0;
$totalPages = ceil($totalRecords / $perPage);

// Get Records
$query = "SELECT " . implode(', ', $tableConfig['cols']) . " FROM {$table} {$whereClause} ORDER BY {$sortCol} {$sortDir} LIMIT {$perPage} OFFSET {$offset}";
$records = $db->fetchAll($query, $params);

// Generate Sort Link
function sortLink($col) {
    global $table, $search, $sortCol, $sortDir;
    $newDir = ($col === $sortCol && $sortDir === 'ASC') ? 'DESC' : 'ASC';
    $icon = '';
    if ($col === $sortCol) {
        $icon = $sortDir === 'ASC' ? ' ↑' : ' ↓';
    }
    return "<a href='crud.php?table={$table}&search=" . urlencode($search) . "&sort={$col}&dir={$newDir}'>{$col}{$icon}</a>";
}

ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Liste : <?= ucfirst($table) ?> (<?= $totalRecords ?>)</h2>
        <div style="display:flex; gap:10px;">
            <a href="crud.php?table=<?= $table ?>&action=export&search=<?= urlencode($search) ?>" class="btn">📥 Export CSV</a>
            <a href="edit.php?table=<?= $table ?>" class="btn btn-primary">➕ Ajouter</a>
        </div>
    </div>
    <div class="card-body">
        
        <!-- Toolbar -->
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <form method="GET" action="crud.php" style="display:flex; gap:10px; flex:1; max-width: 400px;">
                <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Chercher</button>
                <?php if($search): ?>
                    <a href="crud.php?table=<?= $table ?>" class="btn">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <?php foreach($tableConfig['cols'] as $col): ?>
                            <th><?= sortLink($col) ?></th>
                        <?php endforeach; ?>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr><td colspan="<?= count($tableConfig['cols']) + 1 ?>" class="text-center py-4">Aucun enregistrement trouvé.</td></tr>
                    <?php else: ?>
                        <?php foreach($records as $row): ?>
                        <tr>
                            <?php foreach($tableConfig['cols'] as $col): ?>
                                <td>
                                    <?php
                                        // Simple formatting heuristics
                                        $val = $row[$col];
                                        if (is_null($val)) echo '-';
                                        elseif ($col === 'is_active') echo $val ? '<span class="badge badge-success">Oui</span>' : '<span class="badge badge-error">Non</span>';
                                        elseif (strpos($col, '_at') !== false || strpos($col, '_date') !== false) {
                                            echo date('d/m/Y H:i', strtotime($val ?? 'now'));
                                        }
                                        else echo htmlspecialchars(mb_strimwidth((string)$val, 0, 50, '...'));
                                    ?>
                                </td>
                            <?php endforeach; ?>
                            <td style="text-align:right; white-space:nowrap;">
                                <a href="edit.php?table=<?= $table ?>&id=<?= $row[$pk] ?>" class="btn btn-sm">Modifier</a>
                                <a href="crud.php?table=<?= $table ?>&action=delete&id=<?= $row[$pk] ?>&csrf=<?= generateAdminCsrf() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?');">Supprimer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div style="margin-top:20px; display:flex; gap:5px; justify-content:center;">
            <?php for($i=1; $i<=$totalPages; $i++): ?>
                <a href="crud.php?table=<?= $table ?>&search=<?= urlencode($search) ?>&sort=<?= urlencode($sortCol) ?>&dir=<?= $sortDir ?>&page=<?= $i ?>" 
                   class="btn btn-sm <?= $i === $page ? 'btn-primary' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/views/layouts/main.php';
?>
