<?php
/**
 * Modèle Premium : Gradient Mesh
 * Design : Fond dégradé fluide, cartes translucides (Glassmorphism ultime), typographie douce
 */
$pageTitle = htmlspecialchars($profile['full_name'] ?? 'Portfolio') . " - Gradient Mesh";
$photo = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
$pEmail = htmlspecialchars($profile['email'] ?? '');
$pName = htmlspecialchars($profile['full_name'] ?? 'Nom');
$pTitle = htmlspecialchars($profile['title'] ?? 'Visionary');
$pSummary = strip_tags($profile['summary'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
:root { --text: #fff; --glass-bg: rgba(255, 255, 255, 0.1); --glass-border: rgba(255, 255, 255, 0.2); }
body { margin: 0; padding: 0; font-family: 'Poppins', sans-serif; color: var(--text); min-height: 100vh; background: linear-gradient(45deg, #12c2e9, #c471ed, #f64f59); background-size: 400% 400%; animation: gradientBG 15s ease infinite; }
@keyframes gradientBG { 0% {background-position: 0% 50%;} 50% {background-position: 100% 50%;} 100% {background-position: 0% 50%;} }
.app-container { max-width: 1200px; margin: 0 auto; padding: 4rem 2rem; position: relative; z-index: 2; }
.glass-panel { background: var(--glass-bg); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 30px; padding: 3rem; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
.header-grid { display: grid; grid-template-columns: auto 1fr; gap: 3rem; align-items: center; margin-bottom: 4rem; }
.photo { width: 180px; height: 180px; border-radius: 50%; object-fit: cover; border: 4px solid rgba(255,255,255,0.3); }
.name { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 600; margin: 0 0 0.5rem 0; line-height: 1.1; }
.title { font-size: 1.2rem; font-weight: 300; opacity: 0.9; text-transform: uppercase; letter-spacing: 2px; }
.summary { margin-top: 1.5rem; font-size: 1.1rem; line-height: 1.8; opacity: 0.95; max-width: 700px; }
.proj-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; }
.proj-card { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 2rem; transition: transform 0.3s; }
.proj-card:hover { transform: translateY(-10px); background: rgba(255,255,255,0.15); }
.proj-card h3 { margin: 0 0 1rem 0; font-size: 1.5rem; font-weight: 600; }
.proj-card p { opacity: 0.8; line-height: 1.6; font-size: 0.95rem; margin-bottom: 1.5rem; }
.btn { display: inline-block; padding: 10px 25px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: #fff; text-decoration: none; border-radius: 50px; font-weight: 600; font-size: 0.9rem; transition: 0.3s; }
.btn:hover { background: #fff; color: #c471ed; }
@media(max-width: 768px) { .header-grid { grid-template-columns: 1fr; text-align: center; } .photo { margin: 0 auto; } }
</style>
</head>
<body>
    <div class="app-container">
        <div class="glass-panel header-grid">
            <?php if($photo): ?>
                <img src="<?= $photo ?>" class="photo" alt="Photo">
            <?php else: ?>
                <div class="photo" style="background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:3rem;">🌟</div>
            <?php endif; ?>
            
            <div>
                <h1 class="name"><?= htmlspecialchars($profile['full_name']) ?></h1>
                <div class="title"><?= htmlspecialchars($profile['title'] ?? 'Visionary') ?></div>
                <?php if($profile['summary']): ?>
                <p class="summary"><?= strip_tags($profile['summary']) ?></p>
                <?php endif; ?>
                
                <?php if($profile['email']): ?>
                <a href="mailto:<?= htmlspecialchars($profile['email']) ?>" class="btn" style="margin-top:2rem;">Start a Conversation</a>
                <?php endif; ?>
            </div>
        </div>

        <h2 style="font-size:2rem; font-weight:300; margin: 4rem 0 2rem 0; letter-spacing:1px; text-shadow: 0 2px 10px rgba(0,0,0,0.1);">Curated Projects</h2>
        
        <div class="proj-grid">
            <?php foreach ($projects as $project): ?>
            <div class="proj-card">
                <h3><?= htmlspecialchars($project['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                <?php if($project['link_url']): ?>
                <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" class="btn">View Project</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
