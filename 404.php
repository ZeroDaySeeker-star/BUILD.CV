<?php 
http_response_code(404);
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Introuvable – BUILD.CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #09090b; --text: #f8fafc; --text-muted: #94a3b8; --primary: #4f46e5; }
        body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; align-items: center; justify-content: center; min-height: 100vh; text-align: center; }
        .error-container { max-width: 500px; padding: 2rem; }
        .error-code { font-size: 8rem; font-weight: 800; color: var(--primary); line-height: 1; letter-spacing:-2px; margin-bottom: 1rem; }
        h1 { font-size: 1.5rem; margin-bottom: 1rem; }
        p { color: var(--text-muted); margin-bottom: 2rem; line-height: 1.6; }
        .btn { display: inline-block; background: var(--primary); color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 8px; font-weight: 600; transition: background 0.3s; }
        .btn:hover { background: #4338ca; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1>Oups ! Cette page s'est perdue.</h1>
        <p>Il semblerait que le lien que vous avez suivi soit cassé, ou que la page ait été supprimée. Ne vous inquiétez pas, votre CV est toujours en sécurité.</p>
        <a href="<?= APP_URL ?>" class="btn">Retour à l'accueil</a>
    </div>
</body>
</html>
