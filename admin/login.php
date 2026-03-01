<?php
require_once __DIR__ . '/config/auth.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: ' . ADMIN_URL . '/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyAdminCsrf();
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            $admin = db()->fetchOne("SELECT * FROM admins WHERE username = ? OR email = ?", [$username, $username]);
            
            if ($admin && $admin['is_active'] && password_verify($password, $admin['password_hash'])) {
                // Login success
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_role'] = $admin['role'];
                
                // Update last login
                db()->query("UPDATE admins SET last_login = NOW() WHERE id = ?", [$admin['id']]);
                
                logAdminAction("LOGIN_SUCCESS");
                
                header('Location: ' . ADMIN_URL . '/index.php');
                exit;
            } else {
                $error = "Identifiants incorrects ou compte inactif.";
                // Log failed attempt but without admin_id since we don't know who they are yet
                // We'd need a separate log or just use standard error logs
                error_log("Failed admin login attempt for: $username IP: " . ($_SERVER['REMOTE_ADDR']??''));
            }
        } catch (Exception $e) {
            $error = "Erreur système. Veuillez réessayer.";
            error_log("Admin login error: " . $e->getMessage());
        }
    }
}

$csrf_token = generateAdminCsrf();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Connexion | BUILD.CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --surface: #1e293b;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --error: #ef4444;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        
        .login-card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.5px;
        }
        
        .logo span { color: var(--primary); }
        .logo-subtitle { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 2px; }
        
        .form-group { margin-bottom: 1.5rem; }
        
        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }
        
        input {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: rgba(0,0,0,0.2);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: white;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        .btn {
            width: 100%;
            padding: 0.875rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .btn:hover { background-color: var(--primary-hover); }
        
        .error-alert {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--error);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                BUILD<span>.</span>CV<br>
                <div class="logo-subtitle">Administration</div>
            </div>
            
            <?php if ($error): ?>
                <div class="error-alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                
                <div class="form-group">
                    <label for="username">Nom d'utilisateur ou Email</label>
                    <input type="text" id="username" name="username" required autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Se connecter</button>
            </form>
        </div>
    </div>

</body>
</html>
