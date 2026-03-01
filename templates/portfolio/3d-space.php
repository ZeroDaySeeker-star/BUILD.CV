<?php
/**
 * Modèle Premium : 3D Space
 * Design : Thème cosmique sombre, cartes flottantes avec effets de profondeur
 */
$pageTitle = htmlspecialchars($profile['full_name'] ?? 'Portfolio') . " - Portfolio";
$pEmail = htmlspecialchars($profile['email'] ?? '');
$pTitle = htmlspecialchars($profile['title'] ?? 'Digital Explorer');
$pSummary = htmlspecialchars($profile['summary'] ?? 'Exploring the intersection of design, technology, and infinite space.');
$pName = htmlspecialchars($profile['full_name'] ?? 'Nom');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&display=swap" rel="stylesheet">
<style>
:root { --bg: #050505; --text: #f0f0f0; --accent: #7c3aed; --card-bg: rgba(255,255,255,0.03); }
body { margin: 0; padding: 0; background: var(--bg); color: var(--text); font-family: 'Space Grotesk', sans-serif; overflow-x: hidden; }
/* Etoiles de fond simulées */
body::before { content:''; position:fixed; top:0; left:0; width:100vw; height:100vh; background-image: radial-gradient(circle at 15% 50%, rgba(124, 58, 237, 0.15) 0%, transparent 50%), radial-gradient(circle at 85% 30%, rgba(37, 99, 235, 0.15) 0%, transparent 50%); z-index:-1; }
.hero { min-height: 80vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem; }
.hero h1 { font-size: clamp(3rem, 8vw, 6rem); margin: 0; font-weight: 700; background: linear-gradient(135deg, #fff, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: -2px; }
.hero p { font-size: 1.5rem; color: #a1a1aa; max-width: 600px; margin-top: 1rem; }
.container { max-width: 1200px; margin: 0 auto; padding: 4rem 2rem; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; perspective: 1000px; }
.card { background: var(--card-bg); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 2rem; transition: transform 0.4s ease, box-shadow 0.4s ease; transform-style: preserve-3d; backdrop-filter: blur(10px); }
.card:hover { transform: translateY(-10px) rotateX(5deg) rotateY(-5deg); box-shadow: 20px 20px 50px rgba(0,0,0,0.5), inset 0 0 0 1px rgba(255,255,255,0.1); }
.card h3 { font-size: 1.5rem; margin-top: 0; color: #fff; }
.card p { color: #a1a1aa; line-height: 1.6; }
.nav { padding: 2rem; display: flex; justify-content: space-between; align-items: center; }
.contact-btn { background: var(--text); color: var(--bg); padding: 0.8rem 1.5rem; border-radius: 30px; text-decoration: none; font-weight: 700; transition: 0.3s; }
.contact-btn:hover { background: var(--accent); color: #fff; box-shadow: 0 0 20px var(--accent); }
</style>
</head>
<body>
    <nav class="nav">
        <div style="font-weight:700; font-size:1.2rem; letter-spacing:1px;"><?= $pName ?></div>
        <?php if($pEmail): ?><a href="mailto:<?= $pEmail ?>" class="contact-btn">Get in Contact</a><?php endif; ?>
    </nav>

    <header class="hero">
        <h1><?= $pTitle ?></h1>
        <p><?= $pSummary ?></p>
    </header>

    <main class="container">
        <h2 style="font-size:2.5rem; margin-bottom: 2rem; text-align: center;">Projects Nebula</h2>
        <div class="grid">
            <?php foreach ($projects as $project): ?>
            <div class="card">
                <h3><?= htmlspecialchars($project['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                <?php if($project['link_url']): ?>
                <a href="<?= htmlspecialchars($project['link_url']) ?>" style="color:var(--accent); text-decoration:none; display:inline-block; margin-top:1rem; font-weight:700;" target="_blank">Explore Launch →</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
