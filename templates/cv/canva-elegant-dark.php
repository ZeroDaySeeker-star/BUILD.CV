<?php
/**
 * Modèle de CV : Canva Élégant Sombre (Premium)
 * Inspiré par : "CV homme et lettre de motivation responsable marketing élégant sombre et dégradé"
 */
$name     = htmlspecialchars($profile['full_name'] ?? 'Votre Nom');
$title    = htmlspecialchars($profile['title'] ?? 'Titre Professionnel');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
$website  = htmlspecialchars($profile['website'] ?? '');
$linkedin = htmlspecialchars($profile['linkedin'] ?? '');
$summary  = $profile['summary'] ?? '';
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;

// Initiales de secours
$initials = '';
if (!$photo && $name) {
    $parts = explode(' ', $name);
    foreach($parts as $p) {
        $initials .= strtoupper(substr($p, 0, 1));
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg-main: #23252a;
    --bg-glow: radial-gradient(circle at 80% 30%, rgba(93, 106, 150, 0.4) 0%, rgba(35, 37, 42, 1) 70%);
    --text-primary: #ffffff;
    --text-secondary: #cbd5e1;
    --border-color: rgba(255, 255, 255, 0.15);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Roboto', sans-serif;
    color: var(--text-secondary);
    background: #111;
    font-size: 11px;
    line-height: 1.5;
}

.cv-wrapper {
    width: 21cm;
    min-height: 29.7cm;
    background-color: var(--bg-main);
    background-image: var(--bg-glow);
    margin: 0 auto;
    display: flex;
    overflow: hidden;
    position: relative;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
}

/* Sidebar (Left) */
.sidebar {
    width: 32%;
    padding: 40px 30px;
    border-right: 1px solid var(--border-color);
    background: rgba(0, 0, 0, 0.1);
    z-index: 2;
}

.photo-area {
    width: 140px;
    height: 170px;
    margin: 0 auto 35px auto;
    background: #334155;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    /* Aspect ratio portrait as in the visual */
}

.photo-area img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: grayscale(20%) contrast(110%);
}

.photo-area .initials {
    font-size: 40px;
    color: white;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
}

.sidebar-section {
    margin-bottom: 35px;
}

.section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 15px;
}

.contact-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    word-break: break-all;
}
.contact-icon {
    width: 14px;
    height: 14px;
    fill: var(--text-primary);
    flex-shrink: 0;
}

.skills-list {
    list-style: none;
}
.skills-list li {
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}
.skills-list li::before {
    content: '•';
    color: var(--text-primary);
    font-weight: bold;
    margin-right: 8px;
    font-size: 14px;
}

/* Main Content (Right) */
.main-content {
    width: 68%;
    padding: 40px;
    z-index: 2;
}

.header {
    margin-bottom: 25px;
}

.header h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 38px;
    color: var(--text-primary);
    font-weight: 700;
    text-transform: uppercase;
    line-height: 1.1;
    margin-bottom: 5px;
}

.header h2 {
    font-size: 14px;
    font-weight: 400;
    text-transform: uppercase;
    color: var(--text-secondary);
    letter-spacing: 2px;
}

.summary {
    margin-bottom: 40px;
    text-align: justify;
    line-height: 1.6;
}

.main-section {
    margin-bottom: 35px;
}
.main-section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 8px;
}

.exp-item {
    margin-bottom: 20px;
}
.exp-item-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}
.exp-role {
    font-weight: 700;
    color: var(--text-primary);
    font-size: 12px;
}
.exp-dates {
    font-size: 10px;
    color: var(--text-secondary);
}
.exp-company {
    font-style: italic;
    margin-bottom: 6px;
    font-size: 11px;
}
.exp-desc {
    padding-left: 10px;
}
.exp-desc ul {
    list-style-type: none;
}
.exp-desc li {
    position: relative;
    padding-left: 12px;
    margin-bottom: 4px;
}
.exp-desc li::before {
    content: '▪';
    position: absolute;
    left: 0;
    color: var(--text-primary);
    font-size: 8px;
    top: 3px;
}

.edu-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.edu-item {
    margin-bottom: 10px;
}
.edu-dates {
    font-size: 10px;
    margin-bottom: 2px;
}
.edu-degree {
    font-weight: 700;
    color: var(--text-primary);
    font-size: 11px;
}
.edu-school {
    text-transform: uppercase;
    font-size: 10px;
}

/* Icons SVG definitions */
.svg-icons { display: none; }
</style>
</head>
<body>

<svg class="svg-icons">
    <symbol id="icon-phone" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></symbol>
    <symbol id="icon-email" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></symbol>
    <symbol id="icon-location" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></symbol>
    <symbol id="icon-link" viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></symbol>
</svg>

<div class="cv-wrapper">
    <!-- Colonne Gauche -->
    <div class="sidebar">
        <div class="photo-area">
            <?php if ($photo): ?>
                <img src="<?= $photo ?>" alt="Photo de profil">
            <?php else: ?>
                <div class="initials"><?= $initials ?></div>
            <?php endif; ?>
        </div>

        <div class="sidebar-section">
            <h3 class="section-title">Contact</h3>
            <div class="contact-list">
                <?php if ($phone): ?>
                <div class="contact-item">
                    <svg class="contact-icon"><use href="#icon-phone"></use></svg>
                    <span><?= $phone ?></span>
                </div>
                <?php endif; ?>
                <?php if ($email): ?>
                <div class="contact-item">
                    <svg class="contact-icon"><use href="#icon-email"></use></svg>
                    <span><?= $email ?></span>
                </div>
                <?php endif; ?>
                <?php if ($website): ?>
                <div class="contact-item">
                    <svg class="contact-icon"><use href="#icon-link"></use></svg>
                    <span><?= str_replace(['https://','http://'], '', $website) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($location): ?>
                <div class="contact-item">
                    <svg class="contact-icon"><use href="#icon-location"></use></svg>
                    <span><?= $location ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($skills): ?>
        <div class="sidebar-section">
            <h3 class="section-title">Compétences</h3>
            <ul class="skills-list">
                <?php foreach ($skills as $s): ?>
                    <li><?= htmlspecialchars($s['skill_name']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($languages): ?>
        <div class="sidebar-section">
            <h3 class="section-title">Langues</h3>
            <ul class="skills-list">
                <?php foreach ($languages as $l): ?>
                    <li><?= htmlspecialchars($l['language_name']) ?> <span style="opacity:0.6;font-size:10px;margin-left:5px">(<?= htmlspecialchars($l['proficiency']) ?>)</span></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <!-- Colonne Principale -->
    <div class="main-content">
        <div class="header">
            <h1><?= $name ?></h1>
            <h2><?= $title ?></h2>
        </div>

        <?php if ($summary): ?>
        <div class="summary">
            <?= nl2br(htmlspecialchars(strip_tags($summary))) ?>
        </div>
        <?php endif; ?>

        <?php if ($experience): ?>
        <div class="main-section">
            <h3 class="main-section-title">Expériences</h3>
            <?php foreach ($experience as $job): ?>
            <div class="exp-item">
                <div class="exp-item-header">
                    <div class="exp-role"><?= htmlspecialchars($job['position']) ?></div>
                    <div class="exp-dates"><?= $job['start_date'] ?> - <?= $job['end_date'] ?: 'Présent' ?></div>
                </div>
                <div class="exp-company"><?= htmlspecialchars($job['company']) ?></div>
                <div class="exp-desc">
                    <?= !empty($job['description']) ? nl2br(htmlspecialchars(strip_tags($job['description']))) : '' ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($education): ?>
        <div class="main-section">
            <h3 class="main-section-title">Formations</h3>
            <div class="edu-grid">
                <?php foreach ($education as $edu): ?>
                <div class="edu-item">
                    <div class="edu-dates"><?= $edu['start_year'] ?> - <?= $edu['end_year'] ?></div>
                    <div class="edu-degree"><?= htmlspecialchars($edu['degree']) ?></div>
                    <div class="edu-school"><?= htmlspecialchars($edu['school']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
