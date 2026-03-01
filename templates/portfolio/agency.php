<?php
// Modèle de portfolio : Agency – Audacieux, Visuel, Professionnel
$name     = htmlspecialchars($profile['full_name'] ?? 'Mon Portfolio');
$title    = htmlspecialchars($profile['title'] ?? 'Digital Creator');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
$website  = htmlspecialchars($profile['website'] ?? '');
$linkedin = htmlspecialchars($profile['linkedin'] ?? '');
$github   = htmlspecialchars($profile['github'] ?? '');
$summary  = htmlspecialchars($profile['summary'] ?? '');
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $name ?> | Portfolio</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
        --bg: #050505;
        --surface: #0f0f0f;
        --accent: #ff3e3e; /* Rouge Agency */
        --text: #ffffff;
        --text-muted: #a0a0a0;
        --border: rgba(255,255,255,0.1);
        --font-main: 'Space Grotesk', sans-serif;
        --font-heading: 'Outfit', sans-serif;
    }
    html { scroll-behavior: smooth; }
    body { 
        font-family: var(--font-main); 
        background: var(--bg); 
        color: var(--text);
        line-height: 1.6;
    }

    /* NAV */
    nav {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1.5rem 4rem; position: fixed; width: 100%; top: 0; z-index: 1000;
        background: rgba(5,5,5,0.8); backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border);
    }
    .logo { font-family: var(--font-heading); font-weight: 800; font-size: 1.5rem; letter-spacing: -1px; }
    .nav-links { display: flex; gap: 3rem; }
    .nav-links a { color: var(--text-muted); text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: color 0.3s; }
    .nav-links a:hover { color: var(--accent); }

    /* HERO */
    .hero {
        min-height: 100vh; display: flex; flex-direction: column; justify-content: center;
        padding: 120px 4rem 4rem; position: relative; overflow: hidden;
    }
    .hero-bg {
        position: absolute; top: -10%; right: -10%; width: 50vw; height: 50vh;
        background: radial-gradient(circle, rgba(255,62,62,0.1) 0%, transparent 70%);
        filter: blur(80px); z-index: -1;
    }
    .hero h1 {
        font-family: var(--font-heading); font-size: clamp(3rem, 10vw, 8rem); line-height: 0.95;
        font-weight: 800; text-transform: uppercase; margin-bottom: 3rem;
        letter-spacing: -0.05em; word-break: break-word;
    }
    .hero h1 span { color: var(--accent); }
    .hero-meta { display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem; align-items: start; border-top: 1px solid var(--border); padding-top: 3rem; }
    .hero-title { font-size: 1.8rem; color: var(--text); font-weight: 600; line-height: 1.2; }
    .hero-summary { font-size: 1rem; color: var(--text-muted); line-height: 1.8; }
    
    /* SECTION */
    section { padding: 8rem 4rem; }
    .section-head { margin-bottom: 4rem; }
    .section-label { color: var(--accent); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 4px; margin-bottom: 1rem; display: block; }
    .section-title { font-family: var(--font-heading); font-size: 4rem; font-weight: 800; letter-spacing: -2px; }

    /* GRID */
    .project-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 4rem; }
    .project-card { position: relative; text-decoration: none; color: inherit; }
    .project-img {
        aspect-ratio: 16/10; border: 1px solid var(--border);
        background: var(--surface); overflow: hidden; margin-bottom: 1.5rem;
        position: relative;
    }
    .project-img::after {
        content: ""; position: absolute; inset: 0;
        background: linear-gradient(0deg, var(--bg) 0%, transparent 50%);
        opacity: 0.5;
    }
    .project-card h3 { font-family: var(--font-heading); font-size: 2rem; margin-bottom: 0.5rem; }
    .project-tags { display: flex; gap: 1rem; color: var(--text-muted); font-size: 0.9rem; }

    /* SKILLS */
    .skills-flex { display: flex; flex-wrap: wrap; gap: 1.5rem; }
    .skill-tag {
        font-size: 2rem; font-weight: 600; padding: 1rem 2rem;
        border: 1px solid var(--border); transition: 0.3s;
    }
    .skill-tag:hover { background: var(--accent); border-color: var(--accent); color: var(--bg); }

    /* FOOTER */
    footer {
        padding: 4rem; border-top: 1px solid var(--border); text-align: center;
        background: var(--surface);
    }
    .footer-links { display: flex; justify-content: center; gap: 3rem; margin-bottom: 2rem; }
    .footer-links a { color: var(--text); font-size: 2rem; transition: transform 0.3s; }
    .footer-links a:hover { transform: translateY(-5px); color: var(--accent); }

    @media (max-width: 768px) {
        .hero, section, nav { padding: 1.5rem; }
        .hero h1 { font-size: 20vw; }
        .project-grid { grid-template-columns: 1fr; }
        .hero-meta { flex-direction: column; gap: 2rem; }
        .nav-links { display: none; }
    }
</style>
</head>
<body>

<nav>
    <div class="logo"><?= $name ?><span>.</span></div>
    <div class="nav-links">
        <a href="#projects">Travaux</a>
        <a href="#about">À propos</a>
        <a href="#contact">Contact</a>
    </div>
</nav>

<section class="hero">
    <div class="hero-bg"></div>
    <h1><?= $name ?><span>.</span></h1>
    <div class="hero-meta">
        <div class="hero-title"><?= $title ?></div>
        <div class="hero-summary">
            <?= $summary ?>
        </div>
    </div>
</section>

<?php if (!empty($projects)): ?>
<section id="projects">
    <div class="section-head">
        <span class="section-label">Sélection</span>
        <h2 class="section-title">Projets Marquants</h2>
    </div>
    <div class="project-grid">
        <?php foreach ($projects as $p): ?>
        <div class="project-card">
            <div class="project-img">
                <?php if (!empty($p['project_url'])): ?>
                    <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" style="position:absolute; inset:0; z-index:10;"></a>
                <?php endif; ?>
            </div>
            <h3><?= htmlspecialchars($p['title'] ?? $p['project_name'] ?? '') ?></h3>
            <p style="color:var(--text-muted); font-size:0.95rem; margin-bottom:1rem; line-height:1.6;"><?= strip_tags($p['description'] ?? '', '<b><i><strong><em>') ?></p>
            <div class="project-tags">
                <?php 
                $techs = explode(',', $p['technologies'] ?? '');
                foreach ($techs as $t): if (trim($t)): ?>
                    <span><?= htmlspecialchars(trim($t)) ?></span>
                <?php endif; endforeach; ?>
            </div>
            <div style="margin-top:1.5rem; display:flex; gap:1.5rem;">
                <?php if (!empty($p['project_url'])): ?>
                    <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" style="color:var(--accent); font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; text-decoration:none;">Voir Live ↗</a>
                <?php endif; ?>
                <?php if (!empty($p['github_url'])): ?>
                    <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" style="color:var(--text); font-weight:600; font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; text-decoration:none;">GitHub ↗</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($skills)): ?>
<section id="about">
    <div class="section-head">
        <span class="section-label">Compétences</span>
        <h2 class="section-title">Savoir-Faire</h2>
    </div>
    <div class="skills-flex">
        <?php foreach ($skills as $s): ?>
            <div class="skill-tag"><?= htmlspecialchars($s['skill_name'] ?? '') ?></div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section id="contact" style="background: var(--accent); color: var(--bg);">
    <div class="section-head">
        <span class="section-label" style="color: var(--bg);">Collab</span>
        <h2 class="section-title" style="color: var(--bg);">Travaillons Ensemble</h2>
    </div>
    <div style="font-size: 3rem; font-weight: 800; line-height: 1; word-break: break-all;"><?= $email ?></div>
    <?php if ($phone): ?>
        <div style="font-size: 2rem; font-weight: 600; margin-top: 1rem; opacity: 0.8;"><?= $phone ?></div>
    <?php endif; ?>
</section>

<footer>
    <div class="footer-links">
        <?php if ($linkedin): ?><a href="<?= $linkedin ?>"><i class="fa-brands fa-linkedin"></i></a><?php endif; ?>
        <?php if ($github): ?><a href="<?= $github ?>"><i class="fa-brands fa-github"></i></a><?php endif; ?>
    </div>
    <div style="color: var(--text-muted); font-size: 0.8rem;">© <?= date('Y') ?> <?= $name ?>. Built with Build.CV.</div>
</footer>

</body>
</html>
