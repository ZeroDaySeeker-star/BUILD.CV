<?php
/**
 * Modèle Premium : Creative Split
 * Design : 50/50, texte statique à gauche, projets défilants à droite
 */
$pageTitle = htmlspecialchars($profile['full_name'] ?? 'Portfolio') . " - Split Screen";
$pEmail = htmlspecialchars($profile['email'] ?? '');
$pName = htmlspecialchars($profile['full_name'] ?? 'Nom');
$pTitle = htmlspecialchars($profile['title'] ?? 'Portfolio');
$pSummary = strip_tags($profile['summary'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;600;900&display=swap" rel="stylesheet">
<style>
:root { --bg-left: #171717; --bg-right: #f5f5f5; --text-left: #fff; --text-right: #111; --accent: #ff3b30; }
body { margin: 0; padding: 0; font-family: 'Outfit', sans-serif; overflow: hidden; }
.split-container { display: flex; height: 100vh; width: 100vw; }
.left { flex: 1; background: var(--bg-left); color: var(--text-left); padding: 5rem 4rem; display: flex; flex-direction: column; justify-content: center; }
.right { flex: 1; background: var(--bg-right); color: var(--text-right); padding: 5rem 4rem; overflow-y: auto; scroll-behavior: smooth; }
.name { font-size: clamp(3rem, 5vw, 6rem); font-weight: 900; line-height: 1; margin: 0 0 1rem 0; letter-spacing: -2px; }
.title { font-size: 1.5rem; font-weight: 300; color: #a3a3a3; margin-bottom: 2rem; }
.summary { font-size: 1.1rem; line-height: 1.6; max-width: 500px; color: #d4d4d4; margin-bottom: 3rem; }
.contact-btn { display: inline-flex; align-items: center; gap: 10px; background: var(--accent); color: #fff; padding: 1rem 2rem; text-decoration: none; font-weight: 600; border-radius: 50px; width: fit-content; transition: 0.3s; }
.contact-btn:hover { background: #fff; color: var(--bg-left); }
.proj-card { background: #fff; border-radius: 20px; padding: 3rem; margin-bottom: 3rem; box-shadow: 0 20px 40px rgba(0,0,0,0.05); transition: transform 0.3s; }
.proj-card:hover { transform: translateY(-5px); }
.proj-card h3 { font-size: 2rem; font-weight: 900; margin: 0 0 1rem 0; letter-spacing: -1px; }
.proj-card p { font-size: 1.1rem; color: #555; line-height: 1.6; margin-bottom: 2rem; }
.view-btn { border: 2px solid var(--text-right); padding: 10px 20px; color: var(--text-right); text-decoration: none; font-weight: 600; border-radius: 30px; transition: 0.3s; }
.view-btn:hover { background: var(--text-right); color: #fff; }
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: var(--bg-right); }
::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
@media(max-width: 900px) { .split-container { flex-direction: column; overflow-y: auto; height: auto; } .left { padding: 4rem 2rem; min-height: 80vh; } .right { padding: 4rem 2rem; overflow-y: visible; } body { overflow: auto; } }
</style>
</head>
<body>
    <div class="split-container">
        <div class="left">
            <h1 class="name"><?= htmlspecialchars($profile['full_name']) ?></h1>
            <div class="title"><?= htmlspecialchars($profile['title'] ?? 'Portfolio') ?></div>
            
            <?php if($profile['summary']): ?>
            <div class="summary">
                <?= strip_tags($profile['summary']) ?>
            </div>
            <?php endif; ?>

            <?php if($profile['email']): ?>
            <a href="mailto:<?= htmlspecialchars($profile['email']) ?>" class="contact-btn">
                Contact Me ↗
            </a>
            <?php endif; ?>
        </div>
        
        <div class="right">
            <h2 style="font-size:2.5rem; margin:0 0 3rem 0; font-weight:900;">Featured Work.</h2>
            <?php foreach ($projects as $project): ?>
            <div class="proj-card">
                <h3><?= htmlspecialchars($project['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                <?php if($project['link_url']): ?>
                <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" class="view-btn">Explore</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
