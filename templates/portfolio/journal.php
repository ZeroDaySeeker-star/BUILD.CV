<?php
// Modèle de portfolio : Journal – Chaleureux, Personnel, Texture Papier
$name     = htmlspecialchars($profile['full_name'] ?? 'Ma Plume');
$title    = htmlspecialchars($profile['title'] ?? 'Écrivain & Créateur');
$email    = htmlspecialchars($profile['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $name ?> | Journal</title>
<link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,400;0,600;1,400&family=Homemade+Apple&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --bg-paper: #fdfaf7;
        --text: #2c2927;
        --accent: #8b4513; /* Cuir / Encre */
        --border: #e8e2dc;
        --font-serif: 'Crimson Pro', serif;
        --font-hand: 'Homemade Apple', cursive;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: var(--font-serif); background: var(--bg-paper); color: var(--text); 
        line-height: 1.8; overflow-x: hidden;
        background-image: 
            linear-gradient(rgba(139,69,19,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(139,69,19,0.02) 1px, transparent 1px);
        background-size: 50px 50px;
    }

    .container { max-width: 800px; margin: 0 auto; padding: 4rem 2rem; }

    /* HEADER */
    header { text-align: center; margin-bottom: 6rem; position: relative; }
    .header-flower { position: absolute; top: -20px; right: -40px; font-size: 3rem; opacity: 0.1; color: var(--accent); }
    h1 { font-family: var(--font-hand); font-size: 2.5rem; color: var(--accent); margin-bottom: 1rem; }
    .subtitle { font-size: 1.1rem; text-transform: lowercase; font-style: italic; opacity: 0.7; }

    /* SECTIONS */
    section { margin-bottom: 5rem; }
    .section-title { 
        font-family: var(--font-hand); font-size: 1.5rem; margin-bottom: 2rem; 
        border-bottom: 2px solid var(--border); display: inline-block; padding-bottom: 0px; 
        color: var(--accent);
    }

    /* EXPERIENCE / PROJECTS */
    .entry { margin-bottom: 3rem; }
    .entry-head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem; }
    .entry-title { font-weight: 600; font-size: 1.3rem; }
    .entry-date { font-size: 0.9rem; font-style: italic; color: var(--accent); opacity: 0.8; }
    .entry-sub { font-weight: 400; opacity: 0.6; margin-bottom: 1rem; }
    .entry-desc { font-size: 1.1rem; text-align: justify; }

    /* SKILLS */
    .skill-list { list-style: none; display: flex; flex-wrap: wrap; gap: 1rem; }
    .skill-item { 
        padding: 0.5rem 1rem; background: rgba(139,69,19,0.05); 
        border: 1px dashed var(--accent); border-radius: 4px; font-size: 1rem;
    }

    /* FOOTER */
    footer { text-align: center; padding: 4rem 0; border-top: 1px double var(--border); }
    .footer-note { font-family: var(--font-hand); font-size: 1.2rem; color: var(--accent); margin-bottom: 2rem; }
    .social-links { display: flex; justify-content: center; gap: 2rem; }
    .social-links a { color: var(--text); font-size: 1.2rem; transition: 0.3s; }
    .social-links a:hover { color: var(--accent); transform: scale(1.1); }

    @media (max-width: 600px) {
        .container { padding: 2rem 1.5rem; }
        .entry-head { flex-direction: column; }
    }
</style>
</head>
<body>

<div class="container">
    <header>
        <div class="header-flower"><i class="fa-solid fa-feather"></i></div>
        <h1><?= $name ?></h1>
        <div class="subtitle"><?= $title ?></div>
    </header>

    <section id="about">
        <h2 class="section-title">Cher lecteur,</h2>
        <p class="entry-desc">
            <?= $profile['summary'] ?? '' ?>
        </p>
    </section>

    <?php if (!empty($projects)): ?>
    <section id="projects">
        <h2 class="section-title">Mes notes & travaux</h2>
        <?php foreach ($projects as $p): ?>
        <div class="entry">
            <div class="entry-head">
                <div class="entry-title"><?= htmlspecialchars($p['title'] ?? $p['project_name'] ?? '') ?></div>
                <div class="entry-date">
                    <?php if (!empty($p['project_url'])): ?>
                        <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" style="color:var(--accent); font-size:0.8rem;">Voir le projet</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="entry-sub">
                <?php 
                $techs = explode(',', $p['technologies'] ?? '');
                echo implode(' • ', array_map('trim', array_filter($techs))); 
                ?>
            </div>
            <div class="entry-desc"><?= $p['description'] ?? '' ?></div>
            <?php if (!empty($p['github_url'])): ?>
                <div style="margin-top:0.5rem;"><a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" style="font-size:0.8rem; color:var(--text-muted);">Source GitHub</a></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <?php if (!empty($skills)): ?>
    <section id="skills">
        <h2 class="section-title">Savoir-écrire</h2>
        <ul class="skill-list">
            <?php foreach ($skills as $s): ?>
                <li class="skill-item"><?= htmlspecialchars($s['skill_name'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <?php if (!empty($experience)): ?>
    <section id="experience">
        <h2 class="section-title">Chemin parcouru</h2>
        <?php foreach ($experience as $exp): ?>
        <div class="entry">
            <div class="entry-head">
                <div class="entry-title"><?= htmlspecialchars($exp['position']) ?></div>
                <div class="entry-date"><?= htmlspecialchars($exp['start_date']) ?> — <?= htmlspecialchars($exp['end_date']) ?></div>
            </div>
            <div class="entry-sub"><?= htmlspecialchars($exp['company']) ?></div>
            <div class="entry-desc"><?= $exp['description'] ?></div>
        </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <?php if (!empty($education)): ?>
    <section id="education">
        <h2 class="section-title">Apprentissages</h2>
        <?php foreach ($education as $edu): ?>
        <div class="entry">
            <div class="entry-head">
                <div class="entry-title"><?= htmlspecialchars($edu['school']) ?></div>
                <div class="entry-date"><?= htmlspecialchars($edu['start_year']) ?> — <?= htmlspecialchars($edu['end_year']) ?></div>
            </div>
            <div class="entry-sub"><?= htmlspecialchars($edu['degree']) ?> en <?= htmlspecialchars($edu['field']) ?></div>
            <div class="entry-desc"><?= $edu['description'] ?></div>
        </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <footer>
        <div class="footer-note">À bientôt !</div>
        <div style="font-size: 1.1rem; margin-bottom: 0.5rem;"><?= $email ?></div>
        <?php if (!empty($profile['phone'])): ?>
            <div style="font-size: 1rem; font-style: italic; opacity: 0.7; margin-bottom: 2rem;"><?= htmlspecialchars($profile['phone']) ?></div>
        <?php endif; ?>
        <div class="social-links">
             <?php if ($profile['linkedin'] ?? ''): ?><a href="<?= $profile['linkedin'] ?>"><i class="fa-brands fa-linkedin"></i></a><?php endif; ?>
             <?php if ($profile['github'] ?? ''): ?><a href="<?= $profile['github'] ?>"><i class="fa-brands fa-github"></i></a><?php endif; ?>
        </div>
        <div style="margin-top: 3rem; opacity: 0.4; font-size: 0.8rem;">
            © <?= date('Y') ?> – Journal de <?= $name ?>
        </div>
    </footer>
</div>

</body>
</html>
