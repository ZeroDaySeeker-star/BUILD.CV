<?php
// Modèle de CV Créatif – barre latérale accentée et typographie moderne
$name     = htmlspecialchars($profile['full_name'] ?? 'Votre nom');
$title    = htmlspecialchars($profile['title'] ?? 'Titre professionnel');
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
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Inter', 'Arial', sans-serif; background: #fff; font-size: 12px; line-height: 1.55; }
:root { --accent: #6366f1; --accent-light: #eef2ff; --dark: #1e1b4b; }
.cv { display: grid; grid-template-columns: 200px 1fr; min-height: 100vh; }

/* Left sidebar */
.sidebar {
    background: var(--dark);
    padding: 30px 18px;
    display: flex; flex-direction: column; gap: 22px;
}
.sidebar-top { text-align: center; }
.photo-container {
    margin: 0 auto 12px;
    width: 80px; height: 80px;
    border-radius: 50%;
    border: 3px solid var(--accent);
    overflow: hidden; display: flex; align-items: center; justify-content: center;
    background: rgba(99,102,241,0.2);
}
.photo-container img { width: 100%; height: 100%; object-fit: cover; }
.photo-initial { font-size: 28px; font-weight: 800; color: var(--accent); }
.sidebar-name { color: white; font-size: 14px; font-weight: 700; line-height: 1.3; }
.sidebar-title { color: rgba(255,255,255,0.5); font-size: 9.5px; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 4px; }

.sidebar-section { }
.sidebar-section-label {
    font-size: 8px; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase;
    color: var(--accent); border-bottom: 1px solid rgba(99,102,241,0.3);
    padding-bottom: 5px; margin-bottom: 8px;
}
.contact-item { color: rgba(255,255,255,0.7); font-size: 10.5px; margin-bottom: 5px; word-break: break-all; }
.contact-item a { color: rgba(255,255,255,0.7); text-decoration: none; }

.s-skill { margin-bottom: 8px; }
.s-skill-name { color: rgba(255,255,255,0.85); font-size: 10.5px; margin-bottom: 3px; display: flex; justify-content: space-between; }
.s-skill-pct { color: var(--accent); font-size: 9.5px; }
.s-skill-bar { height: 3px; background: rgba(255,255,255,0.1); border-radius: 2px; }
.s-skill-fill { height: 100%; background: linear-gradient(90deg, var(--accent), #a5b4fc); border-radius: 2px; }

.s-lang { display: flex; justify-content: space-between; font-size: 10.5px; margin-bottom: 5px; }
.s-lang-name  { color: rgba(255,255,255,0.8); }
.s-lang-level { color: rgba(255,255,255,0.4); font-size: 9.5px; }

/* Right main */
.main { padding: 28px 26px; }

/* Top accent bar */
.top-bar { height: 5px; background: linear-gradient(90deg, var(--accent), #a5b4fc); border-radius: 0 0 3px 3px; margin-bottom: 20px; }

.main-name  { font-size: 22px; font-weight: 800; color: var(--dark); letter-spacing: -0.5px; }
.main-title {
    display: inline-block; background: var(--accent-light); color: var(--accent);
    font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;
    padding: 2px 10px; border-radius: 20px; margin-top: 6px;
}

.section { margin-bottom: 18px; }
.section-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 9.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 2px; color: var(--accent); margin-bottom: 10px;
}
.section-title::after { content: ''; flex: 1; height: 1px; background: #e0e7ff; }

.item { margin-bottom: 12px; padding-left: 10px; border-left: 2px solid #e0e7ff; }
.item-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 6px; }
.item-title { font-weight: 700; font-size: 12.5px; color: var(--dark); }
.item-date  { font-size: 10px; color: var(--accent); background: var(--accent-light); padding: 1px 7px; border-radius: 10px; white-space: nowrap; flex-shrink: 0; }
.item-sub   { font-size: 11px; color: #6b7280; margin-top: 2px; font-weight: 500; }
.item-desc  { font-size: 11.5px; color: #4b5563; margin-top: 5px; line-height: 1.55; }

/* Summary */
.summary-text {
    font-size: 12px; color: #4b5563; line-height: 1.7;
    background: var(--accent-light); padding: 10px 14px; border-radius: 8px;
    border-left: 3px solid var(--accent);
}

/* Skills chips */
.skills-chips { display: flex; flex-wrap: wrap; gap: 6px; }
.skill-chip {
    font-size: 10.5px; font-weight: 500; background: var(--accent-light);
    color: var(--dark); padding: 3px 10px; border-radius: 20px;
    border: 1px solid #c7d2fe;
}

.proj-item { margin-bottom: 12px; padding: 10px; background: #fafafa; border-radius: 8px; border: 1px solid #f0f0f0; }
.proj-title { font-weight: 700; font-size: 12.5px; color: var(--dark); }
.proj-tech  { font-size: 10px; color: var(--accent); margin: 2px 0; }
.proj-desc  { font-size: 11.5px; color: #4b5563; margin-top: 3px; }
.proj-link  { font-size: 10.5px; color: var(--accent); margin-top: 4px; text-decoration: none; }

.cert-item   { padding: 6px 0; border-bottom: 1px dashed #e5e7eb; }
.cert-name   { font-weight: 600; font-size: 12px; color: var(--dark); }
.cert-issuer { font-size: 10.5px; color: #6b7280; }
</style>
</head>
<body>
<div class="cv">
    <div class="sidebar">
        <div class="sidebar-top">
            <div class="photo-container">
                <?php if ($photo): ?>
                    <img src="<?= $photo ?>" alt="Photo">
                <?php else: ?>
                    <span class="photo-initial"><?= strtoupper(substr($name, 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div class="sidebar-name"><?= $name ?></div>
            <?php if ($title): ?><div class="sidebar-title"><?= $title ?></div><?php endif; ?>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-label">Contact</div>
            <?php if ($email):    ?><div class="contact-item">✉ <?= $email ?></div><?php endif; ?>
            <?php if ($phone):    ?><div class="contact-item">☎ <?= $phone ?></div><?php endif; ?>
            <?php if ($location): ?><div class="contact-item">📍 <?= $location ?></div><?php endif; ?>
            <?php if ($website):  ?><div class="contact-item"><a href="<?= $website ?>">🌐 Site web</a></div><?php endif; ?>
            <?php if ($linkedin): ?><div class="contact-item"><a href="<?= $linkedin ?>">in LinkedIn</a></div><?php endif; ?>
            <?php if ($github):   ?><div class="contact-item"><a href="<?= $github ?>">⌘ GitHub</a></div><?php endif; ?>
        </div>

        <?php if ($skills): ?>
        <div class="sidebar-section">
            <div class="sidebar-section-label">Compétences</div>
            <?php foreach ($skills as $s): ?>
            <div class="s-skill">
                <div class="s-skill-name">
                    <span><?= htmlspecialchars($s['skill_name']) ?></span>
                    <span class="s-skill-pct"><?= $s['skill_level'] ?>%</span>
                </div>
                <div class="s-skill-bar"><div class="s-skill-fill" style="width:<?= $s['skill_level'] ?>%"></div></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($languages): ?>
        <div class="sidebar-section">
            <div class="sidebar-section-label">Langues</div>
            <?php foreach ($languages as $l): ?>
            <div class="s-lang">
                <span class="s-lang-name"><?= htmlspecialchars($l['language_name']) ?></span>
                <span class="s-lang-level"><?= htmlspecialchars($l['proficiency']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($certifications): ?>
        <div class="sidebar-section">
            <div class="sidebar-section-label">Certifications</div>
            <?php foreach ($certifications as $c): ?>
            <div style="margin-bottom:7px;">
                <div style="color:rgba(255,255,255,0.8);font-size:10.5px;font-weight:600"><?= htmlspecialchars($c['cert_name']) ?></div>
                <div style="color:rgba(255,255,255,0.45);font-size:9.5px"><?= htmlspecialchars($c['issuer']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="main">
        <div class="top-bar"></div>
        <div class="main-name"><?= $name ?></div>
        <?php if ($title): ?><div class="main-title"><?= $title ?></div><?php endif; ?>

        <?php if ($summary): ?>
        <div class="section" style="margin-top:16px">
            <div class="section-title">À propos</div>
            <p class="summary-text"><?= $summary ?></p>
        </div>
        <?php endif; ?>

        <?php if ($experience): ?>
        <div class="section">
            <div class="section-title">Expérience</div>
            <?php foreach ($experience as $exp): ?>
            <div class="item">
                <div class="item-header">
                    <span class="item-title"><?= htmlspecialchars($exp['position']) ?></span>
                    <span class="item-date"><?= htmlspecialchars($exp['start_date']) ?> – <?= htmlspecialchars($exp['end_date']) ?></span>
                </div>
                <div class="item-sub"><?= htmlspecialchars($exp['company']) ?></div>
                <?php if ($exp['description']): ?>
                <div class="item-desc"><?= $exp['description'] ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($education): ?>
        <div class="section">
            <div class="section-title">Formation</div>
            <?php foreach ($education as $edu): ?>
            <div class="item">
                <div class="item-header">
                    <span class="item-title"><?= htmlspecialchars($edu['degree']) ?><?= $edu['field'] ? ' – ' . htmlspecialchars($edu['field']) : '' ?></span>
                    <span class="item-date"><?= htmlspecialchars($edu['start_year']) ?> – <?= htmlspecialchars($edu['end_year']) ?></span>
                </div>
                <div class="item-sub"><?= htmlspecialchars($edu['school']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($projects): ?>
        <div class="section">
            <div class="section-title">Projets</div>
            <?php foreach ($projects as $p): ?>
            <div class="proj-item">
                <div class="proj-title"><?= htmlspecialchars($p['title'] ?? '') ?></div>
                <?php if (!empty($p['technologies'])): ?><div class="proj-tech">⚡ <?= htmlspecialchars($p['technologies']) ?></div><?php endif; ?>
                <?php if (!empty($p['description'])): ?><div class="proj-desc"><?= $p['description'] ?></div><?php endif; ?>
                <?php if (!empty($p['link_url'])): ?><a class="proj-link" href="<?= htmlspecialchars($p['link_url']) ?>">🔗 Voir le projet</a><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
