<?php
/**
 * Modèle Premium : Elegant Serif
 * Design : Style éditorial haut de gamme, couleurs douces (crème/beige), empattements
 */
$pageTitle = htmlspecialchars($profile['full_name'] ?? 'Portfolio') . " - Portfolio Éditorial";
$pEmail = htmlspecialchars($profile['email'] ?? '');
$pName = htmlspecialchars($profile['full_name'] ?? 'Nom');
$pTitle = htmlspecialchars($profile['title'] ?? 'Artisan of Digital Experiences');
$pSummary = strip_tags($profile['summary'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400&display=swap" rel="stylesheet">
<style>
:root { --bg: #f9f6f0; --text: #2a2a2a; --accent: #8b7355; border-color: #e3dec6; }
body { margin: 0; padding: 0; background: var(--bg); color: var(--text); font-family: 'Lato', sans-serif; }
.container { max-width: 1000px; margin: 0 auto; padding: 0 2rem; }
.header { text-align: center; padding: 5rem 0; border-bottom: 1px solid var(--border-color); }
.header h1 { font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 6vw, 5rem); margin: 0; font-weight: 400; letter-spacing: 2px; text-transform: uppercase; }
.header p { font-size: 1.2rem; margin-top: 1.5rem; color: var(--accent); font-style: italic; font-family: 'Playfair Display', serif; }
.summary { text-align: center; padding: 4rem 2rem; max-width: 700px; margin: 0 auto; font-size: 1.1rem; line-height: 1.8; color: #555; }
.projects-title { text-align: center; font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 400; margin: 4rem 0 3rem; color: var(--bg); background: var(--text); padding: 1rem 0; }
.grid { display: grid; grid-template-columns: 1fr; gap: 4rem; padding-bottom: 5rem; }
.proj-card { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; }
.proj-card:nth-child(even) { direction: rtl; }
.proj-card:nth-child(even) > div { direction: ltr; }
.proj-img { background: #e3dec6; height: 400px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-family: 'Playfair Display', serif; font-style: italic; }
.proj-info h3 { font-family: 'Playfair Display', serif; font-size: 2rem; margin: 0 0 1rem 0; font-weight: 400; }
.proj-info p { color: #555; line-height: 1.7; margin-bottom: 2rem; }
.proj-link { display: inline-block; padding: 0.8rem 2rem; border: 1px solid var(--text); color: var(--text); text-decoration: none; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; transition: 0.4s; }
.proj-link:hover { background: var(--text); color: var(--bg); }
.footer { border-top: 1px solid var(--border-color); padding: 4rem 0; text-align: center; }
.footer a { font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--text); text-decoration: none; font-style: italic; }
.footer a:hover { color: var(--accent); }
@media(max-width: 768px) { .proj-card { grid-template-columns: 1fr; } .proj-card:nth-child(even) { direction: ltr; } }
</style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1><?= htmlspecialchars($profile['full_name']) ?></h1>
            <p><?= htmlspecialchars($profile['title'] ?? 'Artisan of Digital Experiences') ?></p>
        </div>
    </header>

    <?php if($profile['summary']): ?>
    <div class="summary">
        <?= strip_tags($profile['summary']) ?>
    </div>
    <?php endif; ?>

    <h2 class="projects-title">Selected Works</h2>

    <div class="container grid">
        <?php foreach ($projects as $project): ?>
        <div class="proj-card">
            <div class="proj-img">Visual Representation</div>
            <div class="proj-info">
                <h3><?= htmlspecialchars($project['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                <?php if($project['link_url']): ?>
                <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" class="proj-link">Read the Case Study</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if($profile['email']): ?>
    <footer class="footer">
        <div class="container">
            <p style="text-transform:uppercase; letter-spacing:2px; font-size:0.8rem; margin-bottom:1rem; color:var(--accent);">Inquiries</p>
            <a href="mailto:<?= htmlspecialchars($profile['email']) ?>"><?= htmlspecialchars($profile['email']) ?></a>
        </div>
    </footer>
    <?php endif; ?>
</body>
</html>
