<?php
/**
 * Modèle Premium : Retro Brutalism
 * Design : Neo-brutalisme, bords sharply carrés, ombres nettes, jaune/noir
 */
$pageTitle = htmlspecialchars($profile['full_name'] ?? 'Portfolio') . " - Neo Brutalist";
$pEmail = htmlspecialchars($profile['email'] ?? '');
$pName = htmlspecialchars($profile['full_name'] ?? 'Nom');
$pTitle = htmlspecialchars($profile['title'] ?? 'CREATIVE');
$pSummary = htmlspecialchars(strip_tags($profile['summary'] ?? 'Warning: contents inside are excessively brutal.'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=IBM+Plex+Mono&display=swap" rel="stylesheet">
<style>
:root { --bg: #fdf2d0; --text: #000; --accent: #ff4747; --border: 4px solid #000; }
body { margin: 0; padding: 0; background: var(--bg); color: var(--text); font-family: 'IBM Plex Mono', monospace; font-size: 16px; }
.nav { display: flex; justify-content: space-between; padding: 2rem; border-bottom: var(--border); background: #fff; }
.logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 2rem; text-transform: uppercase; }
.btn { border: var(--border); background: var(--accent); color: #fff; font-weight: bold; padding: 0.5rem 1.5rem; text-decoration: none; box-shadow: 4px 4px 0 #000; transition: transform 0.1s, box-shadow 0.1s; display: inline-block; }
.btn:active { transform: translate(4px, 4px); box-shadow: 0 0 0 #000; }
.hero { display: grid; grid-template-columns: 1fr; gap: 2rem; padding: 4rem 2rem; border-bottom: var(--border); }
.hero h1 { font-family: 'Syne', sans-serif; font-size: clamp(3rem, 10vw, 8rem); font-weight: 800; line-height: 0.9; margin: 0; text-transform: uppercase; }
.hero p { font-size: 1.5rem; max-width: 600px; background: #fff; border: var(--border); padding: 1.5rem; box-shadow: 8px 8px 0 #000; }
.marquee { border-bottom: var(--border); padding: 1rem 0; background: #fff; overflow: hidden; white-space: nowrap; font-weight: bold; font-size: 1.2rem; text-transform: uppercase; }
.marquee span { display: inline-block; animation: scroll 15s linear infinite; }
@keyframes scroll { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem; padding: 4rem 2rem; }
.card { border: var(--border); background: #fff; box-shadow: 8px 8px 0 #000; padding: 2rem; display: flex; flex-direction: column; }
.card h3 { font-family: 'Syne', sans-serif; font-size: 2rem; margin: 0 0 1rem 0; border-bottom: 2px dashed #000; padding-bottom: 1rem; }
.card p { flex: 1; margin-bottom: 2rem; }
.card .btn { background: #fff; color: #000; text-align: center; }
.card .btn:hover { background: #000; color: #fff; }
</style>
</head>
<body>
    <nav class="nav">
        <div class="logo"><?= $pName ?></div>
        <?php if($pEmail): ?><a href="mailto:<?= $pEmail ?>" class="btn">HIRE ME</a><?php endif; ?>
    </nav>

    <header class="hero">
        <h1><?= $pTitle ?></h1>
        <p><?= $pSummary ?></p>
    </header>

    <div class="marquee">
        <span>* AVAILABLE FOR WORK * CREATIVE DIR * 100% BRUTAL * WEBSITES * ART * INTERFACES * </span>
    </div>

    <main class="grid">
        <?php foreach ($projects as $project): ?>
        <div class="card">
            <h3><?= htmlspecialchars($project['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
            <?php if($project['link_url']): ?>
            <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" class="btn">VIEW SH*T</a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </main>
</body>
</html>
