<?php
// Modèle de portfolio : Gallery – Galerie visuelle avec grid layout élégant
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
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --primary: #ff6b6b;
    --secondary: #4ecdc4;
    --text: #2d3436;
    --text-light: #636e72;
    --bg: #ffffff;
    --bg-light: #f5f6fa;
    --radius: 16px;
}
html { scroll-behavior: smooth; }
body {
    font-family: 'Outfit', sans-serif;
    background: var(--bg-light);
    color: var(--text);
    line-height: 1.6;
}

/* Navigation */
nav {
    position: sticky;
    top: 0;
    z-index: 50;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid#e9ecef;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.nav-brand {
    font-weight: 900;
    font-size: 1.3rem;
    background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-decoration: none;
}
.nav-links {
    display: flex;
    gap: 2rem;
    list-style: none;
}
.nav-links a {
    text-decoration: none;
    color: var(--text-light);
    font-weight: 500;
    font-size: 0.9rem;
    transition: color 0.3s;
}
.nav-links a:hover { color: var(--primary); }

/* Header */
.header {
    background: white;
    padding: 60px 2rem;
    text-align: center;
    border-bottom: 1px solid #e9ecef;
}
.header-photo {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 30px;
    border: 5px solid var(--secondary);
    box-shadow: 0 10px 40px rgba(78, 205, 196, 0.2);
}
.header h1 {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 10px;
}
.header .title {
    font-size: 1.2rem;
    color: var(--primary);
    margin-bottom: 15px;
    font-weight: 600;
}
.header-bio {
    max-width: 600px;
    margin: 0 auto 30px;
    color: var(--text-light);
    line-height: 1.8;
    font-size: 1.05rem;
}
.header-contact {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.contact-link {
    display: inline-block;
    padding: 10px 20px;
    background: var(--primary);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.2);
}
.contact-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.3);
}
.contact-link.secondary {
    background: var(--secondary);
    box-shadow: 0 4px 15px rgba(78, 205, 196, 0.2);
}
.contact-link.secondary:hover {
    box-shadow: 0 6px 20px rgba(78, 205, 196, 0.3);
}

/* Main Content */
main {
    max-width: 1400px;
    margin: 0 auto;
    padding: 60px 2rem;
}

/* Section */
.section-title {
    font-size: 2rem;
    font-weight: 900;
    margin-bottom: 40px;
    margin-top: 60px;
    position: relative;
    display: inline-block;
}
.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    border-radius: 2px;
}

/* Gallery Grid */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-top: 40px;
}
.gallery-card {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
    cursor: pointer;
}
.gallery-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}
.card-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #ff6b6b20, #4ecdc420);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #e9ecef;
}
.card-content {
    padding: 25px;
}
.card-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text);
}
.card-desc {
    color: var(--text-light);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 15px;
}
.card-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.tag {
    background: var(--primary);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}
.tag.secondary {
    background: var(--secondary);
}

/* About Section */
.about {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    align-items: center;
    margin-top: 40px;
    padding: 40px;
    background: white;
    border-radius: var(--radius);
}
.about-text h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: var(--primary);
}
.about-text p {
    color: var(--text-light);
    line-height: 1.8;
    margin-bottom: 15px;
}
.stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 30px;
}
.stat {
    text-align: center;
}
.stat-number {
    font-size: 2rem;
    font-weight: 900;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.stat-label {
    color: var(--text-light);
    font-size: 0.9rem;
    margin-top: 5px;
}

/* Footer */
footer {
    background: white;
    border-top: 1px solid #e9ecef;
    padding: 40px 2rem;
    text-align: center;
    color: var(--text-light);
}
.footer-content {
    max-width: 800px;
    margin: 0 auto;
}
.footer-socials {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}
.social-link {
    display: inline-block;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--bg-light);
    color: var(--primary);
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    transition: all 0.3s;
}
.social-link:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

@media (max-width: 768px) {
    nav { padding: 1rem; }
    .header { padding: 40px 1rem; }
    .header h1 { font-size: 1.8rem; }
    main { padding: 40px 1rem; }
    .gallery-grid { grid-template-columns: 1fr; }
    .about { grid-template-columns: 1fr; }
    .section-title { font-size: 1.5rem; }
}
</style>
</head>
<body>

<!-- Navigation -->
<nav>
    <a href="#" class="nav-brand"><?= strtoupper(substr($name, 0, 2)); ?></a>
    <ul class="nav-links">
        <?php if (!empty($projects)): ?><li><a href="#projects">Projets</a></li><?php endif; ?>
        <?php if (!empty($experience)): ?><li><a href="#experience">Expérience</a></li><?php endif; ?>
        <?php if (!empty($skills)): ?><li><a href="#skills">Compétences</a></li><?php endif; ?>
        <li><a href="#about">À propos</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
</nav>

<!-- Header -->
<div class="header">
    <?php if ($photo): ?>
    <img src="<?= $photo ?>" class="header-photo" alt="<?= $name ?>">
    <?php endif; ?>
    <h1><?= $name ?></h1>
    <?php if ($title): ?><div class="title"><?= $title ?></div><?php endif; ?>
    <?php if ($summary): ?><div class="header-bio"><?= $summary ?></div><?php endif; ?>
    <div class="header-contact">
        <?php if ($email): ?><a href="mailto:<?= $email ?>" class="contact-link">✉ Email</a><?php endif; ?>
        <?php if ($website): ?><a href="<?= $website ?>" target="_blank" class="contact-link secondary">🌐 Website</a><?php endif; ?>
        <?php if ($linkedin): ?><a href="<?= $linkedin ?>" target="_blank" class="contact-link">in LinkedIn</a><?php endif; ?>
        <?php if ($github): ?><a href="<?= $github ?>" target="_blank" class="contact-link secondary">⚙ GitHub</a><?php endif; ?>
    </div>
</div>

<!-- Main Content -->
<main>

    <!-- Projects Section -->
    <?php
    $galleryProjects = array_slice($projects ?? [], 0, 9);
    if ($galleryProjects):
        $projects = $galleryProjects; // keep $projects var locally for the loop
    ?>
    <h2 class="section-title" id="projects">Projets</h2>
    <div class="gallery-grid">
        <?php foreach ($projects as $project): ?>
        <div class="gallery-card">
            <div class="card-image">🚀</div>
            <div class="card-content">
                <div class="card-title"><?= htmlspecialchars($project['title'] ?? 'Projet') ?></div>
                <div class="card-desc"><?= substr($project['description'] ?? '', 0, 120) ?></div>
                <?php if ($project['technologies'] ?? ''): ?>
                <div class="card-tags">
                    <?php foreach (array_slice(explode(',', $project['technologies']), 0, 3) as $tech): ?>
                    <span class="tag"><?= htmlspecialchars(trim($tech)) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Experience Section -->
    <?php if (!empty($experience)): ?>
    <h2 class="section-title" id="experience">Expérience</h2>
    <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 40px;">
        <?php foreach ($experience as $exp): ?>
        <div style="background: white; padding: 25px; border-radius: var(--radius); box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 4px solid var(--primary);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                <div>
                    <h3 style="font-size: 1.2rem; color: var(--text); margin-bottom: 5px;"><?= htmlspecialchars($exp['position']) ?></h3>
                    <div style="font-weight: 600; color: var(--secondary);"><?= htmlspecialchars($exp['company']) ?></div>
                </div>
                <div style="font-size: 0.9rem; color: var(--text-light); background: var(--bg-light); padding: 5px 12px; border-radius: 20px;">
                    <?= htmlspecialchars($exp['start_date']) ?> – <?= htmlspecialchars($exp['end_date']) ?>
                </div>
            </div>
            <?php if (!empty($exp['description'])): ?>
            <div style="color: var(--text-light); line-height: 1.6; font-size: 0.95rem;">
                <?= $exp['description'] ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Education Section -->
    <?php if (!empty($education)): ?>
    <h2 class="section-title" id="education">Formation</h2>
    <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 40px;">
        <?php foreach ($education as $edu): ?>
        <div style="background: white; padding: 25px; border-radius: var(--radius); box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 4px solid var(--secondary);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                <div>
                    <h3 style="font-size: 1.2rem; color: var(--text); margin-bottom: 5px;"><?= htmlspecialchars($edu['degree']) ?><?= !empty($edu['field']) ? ' en ' . htmlspecialchars($edu['field']) : '' ?></h3>
                    <div style="font-weight: 600; color: var(--primary);"><?= htmlspecialchars($edu['school']) ?></div>
                </div>
                <div style="font-size: 0.9rem; color: var(--text-light); background: var(--bg-light); padding: 5px 12px; border-radius: 20px;">
                    <?= htmlspecialchars($edu['start_year']) ?> – <?= htmlspecialchars($edu['end_year']) ?>
                </div>
            </div>
            <?php if (!empty($edu['description'])): ?>
            <div style="color: var(--text-light); line-height: 1.6; font-size: 0.95rem;">
                <?= $edu['description'] ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Skills Section -->
    <?php if (!empty($skills)): ?>
    <h2 class="section-title" id="skills">Compétences</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 40px; background: white; padding: 30px; border-radius: var(--radius); box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <?php foreach ($skills as $skill): ?>
        <div style="display: flex; align-items: center; gap: 10px; background: var(--bg-light); padding: 10px 20px; border-radius: 30px; border: 1px solid #e9ecef;">
            <span style="font-weight: 600; color: var(--text);"><?= htmlspecialchars($skill['skill_name']) ?></span>
            <span style="color: var(--primary); font-size: 0.85rem; font-weight: 700;"><?= htmlspecialchars($skill['skill_level']) ?>%</span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-text">
            <h3>À Propos de Moi</h3>
            <p><?= $summary ?: "Professionnel créatif et passionné, avec expertise dans mon domaine. Toujours à la recherche de belles solutions et de collaborations significatives." ?></p>
            <div class="stats">
                <div class="stat">
                    <div class="stat-number"><?php
                        $count = count($projects ?? []);
                        echo $count;
                    ?></div>
                    <div class="stat-label">Projets</div>
                </div>
                <div class="stat">
                    <div class="stat-number">+<?php
                        $expCount = count($experience ?? []);
                        echo max(3, $expCount);
                    ?>k</div>
                    <div class="stat-label">Fois apprécié</div>
                </div>
                <div class="stat">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Dédication</div>
                </div>
                <div class="stat">
                    <div class="stat-number"><?php
                        $skillsCount = count($skills ?? []);
                        echo max(20, $skillsCount);
                    ?>+</div>
                    <div class="stat-label">Compétences</div>
                </div>
            </div>
        </div>
        <div>
            <h3 style="margin-bottom: 20px; color: var(--primary);">Informations de Contact</h3>
            <div style="display: flex; flex-direction: column; gap: 15px; color: var(--text-light);">
                <?php if ($email): ?><div>📧 <?= $email ?></div><?php endif; ?>
                <?php if ($phone): ?><div>📞 <?= $phone ?></div><?php endif; ?>
                <?php if ($location): ?><div>📍 <?= $location ?></div><?php endif; ?>
                <?php if ($website): ?><div>🔗 <a href="<?= $website ?>" target="_blank" style="color: var(--primary); text-decoration: none;"><?= parse_url($website, PHP_URL_HOST) ?: $website ?></a></div><?php endif; ?>
            </div>
        </div>
    </section>

</main>

<!-- Footer -->
<footer id="contact">
    <div class="footer-content">
        <p>© 2024 <?= $name ?>. Portfolio créé avec BUILD.CV</p>
        <div class="footer-socials">
            <?php if ($linkedin): ?><a href="<?= $linkedin ?>" class="social-link" title="LinkedIn">in</a><?php endif; ?>
            <?php if ($github): ?><a href="<?= $github ?>" class="social-link" title="GitHub">⚙</a><?php endif; ?>
            <?php if ($email): ?><a href="mailto:<?= $email ?>" class="social-link" title="Email">✉</a><?php endif; ?>
        </div>
        <p style="color: #bdbdbd; font-size: 0.85rem; margin-top: 20px;">Construit par BUILD.CV – Créateur de CV Moderne</p>
    </div>
</footer>

</body>
</html>
