<?php
// Modèle de portfolio : Dark Mode – Design moderne sombre avec animations subtiles
$name     = htmlspecialchars($profile['full_name'] ?? 'Mon Portfolio');
$title    = htmlspecialchars($profile['title'] ?? '');
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
<title><?= $name ?><?= $title ? ' – ' . $title : '' ?></title>
<meta name="description" content="<?= substr($summary, 0, 160) ?>">
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --primary: #00d9ff;
    --primary-dark: #00a8cc;
    --accent: #ff006e;
    --text: #e0e0e0;
    --text-muted: #a0a0a0;
    --bg: #0a0e27;
    --bg-secondary: #13192b;
    --radius: 8px;
    --font: 'Poppins', sans-serif;
    --font-mono: 'Space Mono', monospace;
}
html { scroll-behavior: smooth; }
body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    line-height: 1.6;
    overflow-x: hidden;
}

/* Navigation */
nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
    background: rgba(10, 14, 39, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0, 217, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 3rem;
    height: 70px;
}
.nav-brand {
    font-family: var(--font-mono);
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--primary);
    text-decoration: none;
}
.nav-links {
    display: flex;
    gap: 2.5rem;
    list-style: none;
}
.nav-links a {
    text-decoration: none;
    color: var(--text-muted);
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.3s;
    position: relative;
}
.nav-links a::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--primary);
    transition: width 0.3s;
}
.nav-links a:hover { color: var(--primary); }
.nav-links a:hover::after { width: 100%; }

/* Hero */
.hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 100px 3rem 60px;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(0,217,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.hero-content {
    text-align: center;
    max-width: 700px;
    position: relative;
    z-index: 1;
}
.hero-photo {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 3px solid var(--primary);
    object-fit: cover;
    margin: 0 auto 40px;
    box-shadow: 0 0 30px rgba(0, 217, 255, 0.3);
}
.hero-content h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #00d9ff, #00a8cc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-content .title {
    font-size: 1.3rem;
    color: var(--primary);
    margin-bottom: 20px;
    letter-spacing: 0.5px;
}
.hero-summary {
    font-size: 1rem;
    color: var(--text-muted);
    line-height: 1.8;
    max-width: 500px;
    margin: 0 auto 40px;
}
.hero-links {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}
.hero-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
    padding: 10px 20px;
    border: 2px solid var(--primary);
    border-radius: 4px;
}
.hero-link:hover {
    color: var(--bg);
    background: var(--primary);
}

/* Section */
.section {
    padding: 80px 3rem;
    max-width: 1200px;
    margin: 0 auto;
}
.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 50px;
    text-align: center;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* Projects */
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}
.project-card {
    background: var(--bg-secondary);
    border: 1px solid rgba(0, 217, 255, 0.2);
    border-radius: var(--radius);
    padding: 30px;
    transition: all 0.3s;
    cursor: pointer;
}
.project-card:hover {
    border-color: var(--primary);
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 217, 255, 0.2);
}
.project-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 10px;
}
.project-desc {
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 15px;
}
.project-tech {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.tech-badge {
    background: rgba(0, 217, 255, 0.1);
    color: var(--primary);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid rgba(0, 217, 255, 0.2);
}

/* Timeline */
.timeline {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: rgba(0, 217, 255, 0.2);
}
.timeline-item {
    position: relative;
    margin-bottom: 40px;
    padding-left: 20px;
}
.timeline-dot {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--primary);
    box-shadow: 0 0 10px var(--primary);
}
.timeline-date {
    color: var(--primary);
    font-size: 0.9rem;
    font-family: var(--font-mono);
    margin-bottom: 5px;
}
.timeline-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 5px;
}
.timeline-company {
    font-size: 1rem;
    color: var(--text-muted);
    font-weight: 500;
    margin-bottom: 15px;
}
.timeline-desc {
    color: var(--text-muted);
    line-height: 1.6;
    font-size: 0.95rem;
}

/* Skills */
.skills-container {
    max-width: 800px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}
.skill-tag {
    background: var(--bg-secondary);
    border: 1px solid rgba(0, 217, 255, 0.3);
    color: var(--text);
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 500;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
}
.skill-tag:hover {
    border-color: var(--primary);
    box-shadow: 0 0 15px rgba(0, 217, 255, 0.2);
}
.skill-level {
    color: var(--primary);
    font-family: var(--font-mono);
    font-size: 0.85rem;
}

/* Footer */
footer {
    background: var(--bg-secondary);
    border-top: 1px solid rgba(0, 217, 255, 0.1);
    padding: 40px 3rem;
    text-align: center;
    color: var(--text-muted);
}
.footer-content {
    max-width: 600px;
    margin: 0 auto;
}
.footer-links {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.footer-links a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: opacity 0.3s;
}
.footer-links a:hover { opacity: 0.7; }

@media (max-width: 768px) {
    nav { padding: 0 1.5rem; }
    .hero { padding: 80px 1.5rem 40px; }
    .hero-content h1 { font-size: 2rem; }
    .section { padding: 60px 1.5rem; }
    .section-title { font-size: 1.8rem; }
}
</style>
</head>
<body>

<!-- Navigation -->
<nav>
    <a href="#" class="nav-brand"><?= $name ?></a>
    <ul class="nav-links">
        <?php if (!empty($experience) || !empty($education)): ?><li><a href="#experience">Parcours</a></li><?php endif; ?>
        <?php if (!empty($skills)): ?><li><a href="#skills">Compétences</a></li><?php endif; ?>
        <?php if (!empty($projects)): ?><li><a href="#projects">Projets</a></li><?php endif; ?>
        <li><a href="#contact">Contact</a></li>
        <?php if ($linkedin): ?><li><a href="<?= htmlspecialchars($linkedin) ?>" target="_blank">LinkedIn</a></li><?php endif; ?>
        <?php if ($github): ?><li><a href="<?= htmlspecialchars($github) ?>" target="_blank">GitHub</a></li><?php endif; ?>
    </ul>
</nav>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-content">
        <?php if ($photo): ?>
        <img src="<?= $photo ?>" class="hero-photo" alt="<?= $name ?>">
        <?php endif; ?>
        <h1><?= $name ?></h1>
        <?php if ($title): ?><div class="title"><?= $title ?></div><?php endif; ?>
        <?php if ($summary): ?><p class="hero-summary"><?= $summary ?></p><?php endif; ?>
        <div class="hero-links">
            <?php if ($email): ?><a href="mailto:<?= $email ?>" class="hero-link">✉ Email</a><?php endif; ?>
            <?php if ($website): ?><a href="<?= $website ?>" target="_blank" class="hero-link">🔗 Web</a><?php endif; ?>
            <?php if ($linkedin): ?><a href="<?= $linkedin ?>" target="_blank" class="hero-link">in LinkedIn</a><?php endif; ?>
        </div>
    </div>
</div>

<!-- Experience & Education -->
<?php if (!empty($experience) || !empty($education)): ?>
<section id="experience" class="section">
    <h2 class="section-title">Parcours</h2>
    
    <?php if (!empty($experience)): ?>
    <h3 style="text-align:center; color:white; margin-bottom:30px; font-size:1.8rem;">Expérience Professionnelle</h3>
    <div class="timeline" style="margin-bottom: 60px;">
        <?php foreach ($experience as $exp): ?>
        <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-date"><?= htmlspecialchars($exp['start_date']) ?> – <?= htmlspecialchars($exp['end_date']) ?></div>
            <h3 class="timeline-title"><?= htmlspecialchars($exp['position']) ?></h3>
            <div class="timeline-company"><?= htmlspecialchars($exp['company']) ?></div>
            <?php if (!empty($exp['description'])): ?>
            <div class="timeline-desc"><?= $exp['description'] ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($education)): ?>
    <h3 style="text-align:center; color:white; margin-bottom:30px; font-size:1.8rem;">Formation</h3>
    <div class="timeline">
        <?php foreach ($education as $edu): ?>
        <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-date"><?= htmlspecialchars($edu['start_year']) ?> – <?= htmlspecialchars($edu['end_year']) ?></div>
            <h3 class="timeline-title"><?= htmlspecialchars($edu['degree']) ?><?= !empty($edu['field']) ? ' en ' . htmlspecialchars($edu['field']) : '' ?></h3>
            <div class="timeline-company"><?= htmlspecialchars($edu['school']) ?></div>
            <?php if (!empty($edu['description'])): ?>
            <div class="timeline-desc"><?= $edu['description'] ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- Skills -->
<?php if (!empty($skills)): ?>
<section id="skills" class="section">
    <h2 class="section-title">Compétences</h2>
    <div class="skills-container">
        <?php foreach ($skills as $skill): ?>
        <div class="skill-tag">
            <?= htmlspecialchars($skill['skill_name']) ?>
            <span class="skill-level"><?= htmlspecialchars($skill['skill_level']) ?>%</span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Projects Section -->
<?php
$galleryProjects = array_slice($projects ?? [], 0, 6);
if ($galleryProjects):
    $projectsLoop = $galleryProjects; // keep $projects var locally for the loop
?>
<section id="projects" class="section">
    <h2 class="section-title">Projets</h2>
    <div class="projects-grid">
        <?php foreach ($projectsLoop as $project): ?>
        <div class="project-card">
            <div class="project-title"><?= htmlspecialchars($project['title'] ?? 'Projet') ?></div>
            <div class="project-desc"><?= $project['description'] ?? '' ?></div>
            <?php if ($project['technologies'] ?? ''): ?>
            <div class="project-tech">
                <?php foreach (explode(',', $project['technologies']) as $tech): ?>
                <span class="tech-badge"><?= htmlspecialchars(trim($tech)) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Footer -->
<footer id="contact">
    <div class="footer-content">
        <h3 style="color: var(--primary); margin-bottom: 20px;">Restons en contact</h3>
        <div class="footer-links">
            <?php if ($email): ?><a href="mailto:<?= $email ?>">Email</a><?php endif; ?>
            <?php if ($location): ?><span style="color: var(--text-muted);"><?= $location ?></span><?php endif; ?>
            <?php if ($phone): ?><span style="color: var(--text-muted);"><?= $phone ?></span><?php endif; ?>
        </div>
        <p style="color: var(--text-muted); font-size: 0.9rem;">© 2024 <?= $name ?>. Construit avec BUILD.CV</p>
    </div>
</footer>

</body>
</html>
