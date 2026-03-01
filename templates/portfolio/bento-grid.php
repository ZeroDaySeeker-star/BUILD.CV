<?php
/**
 * Modèle Premium : Bento Grid
 * Design : Inspiré d'Apple, grilles asymétriques, coins très arrondis, ombres douces
 */
$pageTitle = htmlspecialchars($profile['full_name'] ?? 'Portfolio') . " - Portfolio Bento";
$pEmail = htmlspecialchars($profile['email'] ?? '');
$pName = htmlspecialchars($profile['full_name'] ?? 'Nom');
$pTitle = htmlspecialchars($profile['title'] ?? 'Créateur & Designer');
$pSummary = strip_tags($profile['summary'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<style>
:root { --bg: #f5f5f7; --card: #ffffff; --text: #1d1d1f; --sub: #86868b; }
body { margin: 0; padding: 2rem; background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; }
.container { max-width: 1200px; margin: 0 auto; display: grid; gap: 1.5rem; grid-auto-rows: 300px; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
.bento-box { background: var(--card); border-radius: 36px; padding: 2.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.04); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; position: relative; }
.bento-box:hover { transform: scale(1.02); }
.hero-box { grid-column: 1 / -1; background: linear-gradient(135deg, #FF9A9E 0%, #FECFEF 100%); color: #fff; }
.hero-box h1 { font-size: clamp(2rem, 5vw, 4rem); font-weight: 800; margin: 0; letter-spacing: -1px; }
.hero-box p { font-size: 1.2rem; opacity: 0.9; max-width: 600px; }
.proj-box { grid-column: span 1; }
.proj-box-large { grid-column: span 2; }
.bento-box h3 { font-size: 1.5rem; margin: 0 0 0.5rem 0; font-weight: 600; }
.bento-box p { color: var(--sub); line-height: 1.5; margin: 0; }
.contact-box { background: #000; color: #fff; text-align: center; justify-content: center; align-items: center; }
.pill { display: inline-block; background: rgba(0,0,0,0.05); padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-top: 1rem; }
@media(max-width: 768px) { .proj-box-large, .hero-box { grid-column: 1 / -1; } }
</style>
</head>
<body>
    <div class="container">
        <div class="bento-box hero-box">
            <div>
                <h1><?= $pName ?></h1>
                <p><?= $pTitle ?></p>
            </div>
            <p style="font-size: 1.2rem; font-weight: 600; opacity: 0.9;"><?= htmlspecialchars(substr($pSummary, 0, 150)) ?><?= strlen($pSummary) > 150 ? '...' : '' ?></p>
        </div>

        <?php foreach ($projects as $index => $project): ?>
        <div class="bento-box <?= ($index % 3 == 0) ? 'proj-box-large' : 'proj-box' ?>">
            <div>
                <h3><?= htmlspecialchars($project['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
            </div>
            <?php if(!empty($project['link_url'])): ?>
            <div><a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" class="pill" style="color:var(--text); text-decoration:none;">Voir le projet</a></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <?php if($pEmail): ?>
        <div class="bento-box contact-box">
            <h3>Discutons ensemble</h3>
            <p style="color:#888;">Nouveau projet ?</p>
            <a href="mailto:<?= $pEmail ?>" style="background:#fff; color:#000; padding:15px 30px; border-radius:30px; text-decoration:none; font-weight:800; margin-top:2rem;">Envoyer un e-mail</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
