<?php
// Modèle de portfolio : Glass – Moderne, Vibrant, Glassmorphism
$name     = htmlspecialchars($profile['full_name'] ?? 'Nom Prénom');
$title    = htmlspecialchars($profile['title'] ?? 'UI/UX Designer');
$email    = htmlspecialchars($profile['email'] ?? '');
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $name ?></title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --text: #ffffff;
        --text-muted: rgba(255,255,255,0.7);
        --bg-grad: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
        --glass: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --accent: #818cf8;
        --accent-high: #c084fc;
        --font: 'Plus Jakarta Sans', sans-serif;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: var(--font); background: var(--bg-grad); color: var(--text); 
        line-height: 1.6; min-height: 100vh; overflow-x: hidden;
    }

    /* BACKGROUND ORBS */
    .orb { position: fixed; border-radius: 50%; filter: blur(80px); z-index: -1; opacity: 0.5; }
    .orb-1 { width: 400px; height: 400px; background: #4f46e5; top: -100px; right: -100px; }
    .orb-2 { width: 300px; height: 300px; background: #ec4899; bottom: 10%; left: -50px; }

    .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }

    /* GLASS CARD STYLE */
    .glass-card {
        background: var(--glass);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 2.5rem;
    }

    /* NAV */
    nav { padding: 2rem 0; display: flex; justify-content: space-between; align-items: center; }
    .nav-logo { font-weight: 800; font-size: 1.5rem; letter-spacing: -1px; }
    .nav-links { display: flex; gap: 2.5rem; list-style: none; }
    .nav-links a { text-decoration: none; color: var(--text-muted); font-size: 0.95rem; font-weight: 500; transition: 0.3s; }
    .nav-links a:hover { color: var(--text); }

    /* HERO */
    .hero { padding: 4rem 0 8rem; text-align: center; }
    .hero-glass { padding: 4rem 2rem; position: relative; overflow: hidden; }
    .hero h1 { font-size: 4rem; font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -2px; }
    .hero h1 span { background: linear-gradient(90deg, var(--accent), var(--accent-high)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .hero p { font-size: 1.15rem; color: var(--text-muted); max-width: 600px; margin: 0 auto 2.5rem; }

    /* GRID */
    .section-title { font-size: 2rem; font-weight: 800; margin-bottom: 3rem; text-align: center; }
    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2rem; }
    .project-card { transition: 0.3s; cursor: pointer; }
    .project-card:hover { transform: translateY(-10px); background: rgba(255,255,255,0.15); border-color: var(--accent); }
    .project-card h3 { font-size: 1.3rem; margin-bottom: 1rem; color: var(--accent); }
    .project-card p { font-size: 0.9rem; color: var(--text-muted); }

    /* SKILLS */
    .skills-flex { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; }
    .skill-pill { padding: 0.6rem 1.5rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 50px; font-weight: 600; font-size: 0.9rem; }

    /* CONTACT */
    .contact-glass { text-align: center; margin-top: 4rem; padding: 4rem; background: linear-gradient(135deg, rgba(129, 140, 248, 0.2), rgba(192, 132, 252, 0.2)); }
    .contact-btn {
        display: inline-block; padding: 1rem 2.5rem; background: white; color: #1e1b4b;
        border-radius: 50px; font-weight: 800; text-decoration: none; transition: 0.3s;
        box-shadow: 0 10px 40px rgba(129, 140, 248, 0.3);
    }
    .contact-btn:hover { transform: scale(1.05); box-shadow: 0 15px 50px rgba(129, 140, 248, 0.5); }

    footer { padding: 4rem 0; text-align: center; font-size: 0.9rem; color: var(--text-muted); }

    @media (max-width: 768px) {
        .hero h1 { font-size: 2.5rem; }
        .grid { grid-template-columns: 1fr; }
        .orb { display: none; }
    }
</style>
</head>
<body>

<div class="orb orb-1"></div>
<div class="orb orb-2"></div>

<div class="container">
    <nav>
        <div class="nav-logo"><?= $name ?></div>
        <ul class="nav-links">
            <li><a href="#projects">Travaux</a></li>
            <li><a href="#about">Savoir-faire</a></li>
        </ul>
    </nav>

    <header class="hero">
        <div class="hero-glass glass-card">
            <h1><?= !empty($title) ? $title : 'Expert en <span>Design Digital</span> et Innovation.' ?></h1>
            <p><?= $profile['summary'] ?? '' ?></p>
            <a href="#contact" class="contact-btn">Démarrer un projet</a>
        </div>
    </header>

    <?php if (!empty($projects)): ?>
    <section id="projects" style="margin-bottom: 8rem;">
        <h2 class="section-title">Projets Récents</h2>
        <div class="grid">
            <?php foreach ($projects as $p): ?>
            <div class="project-card glass-card">
                <h3><?= htmlspecialchars($p['title'] ?? $p['project_name'] ?? '') ?></h3>
                <div style="font-size:0.75rem; font-weight:700; color:var(--accent-high); margin-bottom:0.75rem; text-transform:uppercase;">
                    <?php 
                    $techs = explode(',', $p['technologies'] ?? '');
                    echo implode(' • ', array_map('trim', array_filter($techs))); 
                    ?>
                </div>
                <div style="font-size:0.9rem; color:var(--text-muted); margin-bottom:1.5rem;"><?= strip_tags($p['description'] ?? '', '<b><i><strong><em>') ?></div>
                <div style="display:flex; gap:1.5rem;">
                    <?php if (!empty($p['project_url'])): ?>
                        <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" style="font-size:0.8rem; font-weight:800; color:var(--accent); text-decoration:none;">DÉCOUVRIR →</a>
                    <?php endif; ?>
                    <?php if (!empty($p['github_url'])): ?>
                        <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" style="font-size:0.8rem; font-weight:700; color:var(--text-muted); text-decoration:none;">CODE ↗</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section id="about" style="margin-bottom: 8rem;">
        <h2 class="section-title">Compétences Clés</h2>
        <div class="skills-flex">
            <?php if (!empty($skills)): ?>
                <?php foreach ($skills as $s): ?>
                <div class="skill-pill"><?= htmlspecialchars($s['skill_name'] ?? '') ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <footer id="contact">
        <div class="contact-glass glass-card">
            <h2 style="margin-bottom: 1rem;">Envie de discuter ?</h2>
            <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;"><?= $email ?></div>
            <?php if (!empty($profile['phone'])): ?>
                <div style="font-size: 1.2rem; font-weight: 500; opacity: 0.8; margin-bottom: 2rem;"><?= htmlspecialchars($profile['phone']) ?></div>
            <?php endif; ?>
            <a href="mailto:<?= $email ?>" class="contact-btn">Envoyer un message</a>
        </div>
        <div style="margin-top: 4rem; opacity: 0.6;">
            © <?= date('Y') ?> – Design by <?= $name ?>
        </div>
    </footer>
</div>

</body>
</html>
