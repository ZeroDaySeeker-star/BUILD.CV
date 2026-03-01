<?php
// Modèle de portfolio : Architect – Minimal, Monochrome, Espaces négatifs
$name     = htmlspecialchars($profile['full_name'] ?? 'Mon Portfolio');
$title    = htmlspecialchars($profile['title'] ?? 'Architect');
$email    = htmlspecialchars($profile['email'] ?? '');
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $name ?></title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Montserrat:wght@200;400;700&display=swap" rel="stylesheet">
<style>
    :root {
        --bg: #ffffff;
        --text: #1a1a1a;
        --text-muted: #888888;
        --border: #eeeeee;
        --font-serif: 'Cormorant Garamond', serif;
        --font-sans: 'Montserrat', sans-serif;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: var(--font-sans); background: var(--bg); color: var(--text); line-height: 1.4; overflow-x: hidden; }

    /* LAYOUT */
    .container { max-width: 1400px; margin: 0 auto; padding: 0 4rem; }

    /* NAV */
    nav { padding: 3rem 0; display: flex; justify-content: space-between; align-items: baseline; }
    .brand { font-weight: 700; letter-spacing: 5px; font-size: 1.2rem; text-transform: uppercase; }
    .menu { display: flex; gap: 3rem; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 2px; }
    .menu a { text-decoration: none; color: inherit; font-weight: 400; transition: 0.3s; }
    .menu a:hover { opacity: 0.5; }

    /* HERO */
    .hero { padding: 10vh 0; border-bottom: 1px solid var(--border); }
    .hero h1 { 
        font-family: var(--font-serif); font-size: 8rem; font-weight: 400; 
        line-height: 1; letter-spacing: -3px; margin-bottom: 4rem; 
    }
    .hero-flex { display: flex; align-items: flex-end; justify-content: space-between; gap: 4rem; }
    .hero-meta { max-width: 400px; }
    .hero-meta h2 { font-weight: 200; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 4px; border-bottom: 1px solid var(--text); display: inline-block; padding-bottom: 10px; margin-bottom: 2rem; }
    .hero-meta p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.8; }

    /* PORTFOLIO GRID */
    .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1px; background: var(--border); border: 1px solid var(--border); }
    .grid-item { background: var(--bg); padding: 4rem; position: relative; overflow: hidden; height: 500px; display: flex; align-items: flex-end; }
    .grid-item span { font-size: 0.7rem; letter-spacing: 5px; text-transform: uppercase; color: var(--text-muted); position: absolute; top: 4rem; left: 4rem; }
    .grid-item h3 { font-family: var(--font-serif); font-size: 2.5rem; font-weight: 400; }

    /* SKILLS SECTION */
    .skills-section { padding: 8rem 0; display: grid; grid-template-columns: 1fr 2fr; gap: 4rem; }
    .skills-title { font-family: var(--font-serif); font-size: 3rem; }
    .skills-list { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
    .skill-name { text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; border-left: 2px solid var(--text); padding-left: 15px; }

    /* FOOTER */
    footer { padding: 6rem 0; text-align: center; border-top: 1px solid var(--border); margin-top: 4rem; }
    .footer-email { font-family: var(--font-serif); font-size: 3rem; font-style: italic; text-decoration: none; color: inherit; }

    @media (max-width: 1024px) {
        .container { padding: 0 2rem; }
        .hero h1 { font-size: 5rem; }
        .grid { grid-template-columns: 1fr; }
    }
</style>
</head>
<body>

<div class="container">
    <nav>
        <div class="brand"><?= $name ?></div>
        <div class="menu">
            <a href="#projects">Work</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </div>
    </nav>

    <section class="hero">
        <h1><?= !empty($title) ? $title : 'Building <br> Future Spaces.' ?></h1>
        <div class="hero-flex">
            <div class="hero-meta">
                <h2><?= $title ?></h2>
                <p><?= $profile['summary'] ?? '' ?></p>
            </div>
            <?php if ($photo): ?>
            <img src="<?= $photo ?>" style="width: 200px; height: 260px; object-fit: cover; filter: grayscale(1);" alt="Profile">
            <?php endif; ?>
        </div>
    </section>

    <?php if (!empty($projects)): ?>
    <section id="projects" class="grid">
        <?php foreach ($projects as $i => $p): ?>
        <div class="grid-item" style="flex-direction:column; align-items:flex-start; justify-content:space-between;">
            <span style="position:static; margin-bottom:2rem;">0<?= $i+1 ?></span>
            <div style="width:100%;">
                <h3><?= htmlspecialchars($p['title'] ?? $p['project_name'] ?? '') ?></h3>
                <p style="font-size:0.8rem; color:var(--text-muted); margin-top:1rem; line-height:1.6; max-width:80%;"><?= strip_tags($p['description'] ?? '', '<b><i><strong><em>') ?></p>
                <div style="margin-top:1.5rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
                    <?php 
                    $techs = explode(',', $p['technologies'] ?? '');
                    foreach ($techs as $t): if (trim($t)): ?>
                        <span style="position:static; font-size:0.6rem; letter-spacing:2px; border:1px solid var(--border); padding:2px 8px;"><?= htmlspecialchars(trim($t)) ?></span>
                    <?php endif; endforeach; ?>
                </div>
            </div>
            <div style="margin-top:2rem; display:flex; gap:2rem;">
                <?php if (!empty($p['project_url'])): ?>
                    <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" style="font-size:0.6rem; letter-spacing:2px; text-decoration:none; color:var(--text); font-weight:700; border-bottom:1px solid var(--text);">VIEW PROJECT</a>
                <?php endif; ?>
                <?php if (!empty($p['github_url'])): ?>
                    <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" style="font-size:0.6rem; letter-spacing:2px; text-decoration:none; color:var(--text-muted);">GITHUB</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <section id="about" class="skills-section">
        <div class="skills-title">Expertise <br> & Tools.</div>
        <div class="skills-list">
            <?php if (!empty($skills)): ?>
                <?php foreach ($skills as $s): ?>
                    <div class="skill-name"><?= htmlspecialchars($s['skill_name'] ?? '') ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <footer id="contact">
        <a href="mailto:<?= $email ?>" class="footer-email"><?= $email ?></a>
        <?php if (!empty($profile['phone'])): ?>
            <div style="font-family: var(--font-serif); font-size: 1.5rem; margin-top: 0.5rem; opacity: 0.7;"><?= htmlspecialchars($profile['phone']) ?></div>
        <?php endif; ?>
        <div style="margin-top: 2rem; font-size: 0.7rem; letter-spacing: 3px; color: var(--text-muted); text-transform: uppercase;">
            © <?= date('Y') ?> – <?= $name ?>
        </div>
    </footer>
</div>

</body>
</html>
