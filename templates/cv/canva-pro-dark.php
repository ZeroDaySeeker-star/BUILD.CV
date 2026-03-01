<?php
// Modèle de CV Canva Pro Dark – Design ultra-moderne, sombre et élégant
// Inspiré par les designs Canva Premium
$name     = htmlspecialchars($profile['full_name'] ?? 'Votre nom');
$title    = htmlspecialchars($profile['title'] ?? 'Titre professionnel');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
$website  = htmlspecialchars($profile['website'] ?? '');
$linkedin = htmlspecialchars($profile['linkedin'] ?? '');
$github   = htmlspecialchars($profile['github'] ?? '');
$summary  = $profile['summary'] ?? '';
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;

// Extraire les initiales si pas de photo
$initials = '';
if (!$photo && $name) {
    $parts = explode(' ', $name);
    foreach($parts as $p) $initials .= strtoupper(substr($p, 0, 1));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
<style>
:root {
    --bg-gradient: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    --accent: #6366f1;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --sidebar-bg: rgba(0, 0, 0, 0.2);
    --border-color: rgba(255, 255, 255, 0.1);
}

* { margin: 0; padding: 0; box-sizing: border-box; }
body { 
    font-family: 'Inter', sans-serif; 
    color: var(--text-main); 
    background: #0f172a; 
    font-size: 13px; 
    line-height: 1.6; 
}

.cv-container {
    max-width: 900px;
    margin: 0 auto;
    background: var(--bg-gradient);
    min-height: 1120px; /* A4 Ratio approx */
    display: flex;
    box-shadow: 0 40px 80px rgba(0,0,0,0.5);
}

/* Sidebar */
.sidebar {
    width: 320px;
    background: var(--sidebar-bg);
    padding: 50px 35px;
    display: flex;
    flex-direction: column;
    gap: 40px;
    border-right: 1px solid var(--border-color);
}

.profile-area {
    text-align: center;
}

.photo-frame {
    width: 160px;
    height: 160px;
    margin: 0 auto 25px;
    border-radius: 24px;
    overflow: hidden;
    border: 4px solid var(--accent);
    background: #334155;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

.photo-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-frame .initials {
    font-size: 48px;
    font-weight: 800;
    color: var(--text-main);
    font-family: 'Montserrat', sans-serif;
}

.contact-section h3, .sidebar-section h3 {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--accent);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.contact-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 12px;
}

.contact-icon {
    color: var(--accent);
    font-size: 14px;
    width: 20px;
    text-align: center;
}

.contact-text {
    color: var(--text-light);
    word-break: break-all;
}

.skill-tag {
    display: inline-block;
    background: rgba(99, 102, 241, 0.1);
    border: 1px solid rgba(99, 102, 241, 0.2);
    padding: 6px 12px;
    border-radius: 6px;
    margin-right: 8px;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-main);
}

.lang-item {
    margin-bottom: 12px;
}

.lang-name {
    font-weight: 600;
    margin-bottom: 4px;
    display: block;
}

.lang-bar-bg {
    height: 4px;
    background: rgba(255,255,255,0.1);
    border-radius: 2px;
    overflow: hidden;
}

.lang-bar-fill {
    height: 100%;
    background: var(--accent);
}

/* Main Content */
.main-content {
    flex: 1;
    padding: 60px 50px;
}

.header-main {
    margin-bottom: 50px;
}

.header-main h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 42px;
    font-weight: 800;
    line-height: 1.1;
    text-transform: uppercase;
    letter-spacing: -1px;
    margin-bottom: 10px;
}

.header-main .job-title {
    font-size: 18px;
    color: var(--accent);
    font-weight: 500;
    letter-spacing: 1px;
}

.section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border-color);
}

.summary-text {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.8;
    margin-bottom: 45px;
}

.experience-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 35px;
}

.experience-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 5px;
    width: 10px;
    height: 10px;
    background: var(--accent);
    border-radius: 50%;
    box-shadow: 0 0 0 5px rgba(99, 102, 241, 0.15);
}

.experience-item::after {
    content: '';
    position: absolute;
    left: 4.5px;
    top: 20px;
    bottom: -30px;
    width: 1px;
    background: var(--border-color);
}

.experience-item:last-child::after { display: none; }

.exp-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 5px;
}

.exp-role {
    font-size: 16px;
    font-weight: 700;
}

.exp-date {
    font-size: 11px;
    color: var(--accent);
    font-weight: 600;
    text-transform: uppercase;
}

.exp-company {
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 10px;
    font-size: 13px;
}

.exp-desc {
    color: var(--text-muted);
    font-size: 12.5px;
}

.education-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.edu-item h4 {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 4px;
}

.edu-school {
    font-size: 12px;
    color: var(--accent);
    margin-bottom: 5px;
}

.edu-year {
    font-size: 11px;
    color: var(--text-muted);
}
</style>
</head>
<body>
<div class="cv-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-area">
            <div class="photo-frame">
                <?php if ($photo): ?>
                    <img src="<?= $photo ?>" alt="Photo">
                <?php else: ?>
                    <div class="initials"><?= $initials ?></div>
                <?php endif; ?>
            </div>
            <div class="contact-section">
                <h3>Contact</h3>
                <div class="contact-list">
                    <?php if ($phone): ?>
                    <div class="contact-item">
                        <span class="contact-icon">📞</span>
                        <span class="contact-text"><?= $phone ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($email): ?>
                    <div class="contact-item">
                        <span class="contact-icon">✉️</span>
                        <span class="contact-text"><?= $email ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($location): ?>
                    <div class="contact-item">
                        <span class="contact-icon">📍</span>
                        <span class="contact-text"><?= $location ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($linkedin): ?>
                    <div class="contact-item">
                        <span class="contact-icon">🔗</span>
                        <span class="contact-text">linkedin.com/in/...</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($skills): ?>
        <div class="sidebar-section">
            <h3>Compétences</h3>
            <div class="skills-list">
                <?php foreach ($skills as $skill): ?>
                    <span class="skill-tag"><?= htmlspecialchars($skill['skill_name']) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($languages): ?>
        <div class="sidebar-section">
            <h3>Langues</h3>
            <?php foreach ($languages as $lang): 
                $lvl = 50;
                if ($lang['proficiency'] === 'Natif') $lvl = 100;
                elseif ($lang['proficiency'] === 'Courant') $lvl = 85;
                elseif ($lang['proficiency'] === 'Intermédiaire') $lvl = 65;
                else $lvl = 40;
            ?>
            <div class="lang-item">
                <span class="lang-name"><?= htmlspecialchars($lang['language_name']) ?></span>
                <div class="lang-bar-bg"><div class="lang-bar-fill" style="width: <?= $lvl ?>%"></div></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-main">
            <h1><?= $name ?></h1>
            <div class="job-title"><?= $title ?: 'Expert Professionnel' ?></div>
        </div>

        <?php if ($summary): ?>
        <div class="section-title">Profil</div>
        <div class="summary-text"><?= nl2br(htmlspecialchars($summary)) ?></div>
        <?php endif; ?>

        <?php if ($experience): ?>
        <div class="section-title">Expérience</div>
        <div class="experience-list">
            <?php foreach ($experience as $job): ?>
            <div class="experience-item">
                <div class="exp-header">
                    <div class="exp-role"><?= htmlspecialchars($job['position']) ?></div>
                    <div class="exp-date"><?= $job['start_date'] ?> — <?= $job['end_date'] ?></div>
                </div>
                <div class="exp-company"><?= htmlspecialchars($job['company']) ?></div>
                <div class="exp-desc"><?= nl2br(htmlspecialchars($job['description'])) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($education): ?>
        <div class="section-title" style="margin-top:20px">Formation</div>
        <div class="education-grid">
            <?php foreach ($education as $edu): ?>
            <div class="edu-item">
                <h4><?= htmlspecialchars($edu['degree']) ?></h4>
                <div class="edu-school"><?= htmlspecialchars($edu['school']) ?></div>
                <div class="edu-year"><?= htmlspecialchars($edu['end_year']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
