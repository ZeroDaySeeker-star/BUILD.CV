<?php
// Modèle de CV Professionnel – deux colonnes bleu marine / blanc
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Arial', sans-serif; color: #2d3748; background: #fff; font-size: 12.5px; line-height: 1.5; }
.cv { display: grid; grid-template-columns: 210px 1fr; min-height: 100vh; }

/* Left sidebar */
.cv-sidebar {
    background: #1e3a5f;
    color: #e2e8f0;
    padding: 32px 20px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.cv-photo-wrap { text-align: center; }
.cv-photo {
    width: 90px; height: 90px; border-radius: 50%; object-fit: cover;
    border: 3px solid rgba(255,255,255,0.3); margin: 0 auto 10px;
    display: block;
}
.cv-photo-initial {
    width: 90px; height: 90px; border-radius: 50%; background: rgba(255,255,255,0.15);
    border: 2px solid rgba(255,255,255,0.3); margin: 0 auto 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 700; color: white;
}
.cv-name { font-size: 16px; font-weight: 700; color: white; line-height: 1.3; }
.cv-job  { font-size: 11px; color: rgba(255,255,255,0.6); margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }

.sidebar-section-title {
    font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;
    color: #7fb3e0; border-bottom: 1px solid rgba(255,255,255,0.15);
    padding-bottom: 5px; margin-bottom: 10px;
}

.contact-list { list-style: none; display: flex; flex-direction: column; gap: 6px; }
.contact-list li { font-size: 11px; color: rgba(255,255,255,0.8); word-break: break-all; }
.contact-list li a { color: rgba(255,255,255,0.7); text-decoration: none; }

.sidebar-skill { margin-bottom: 8px; }
.sidebar-skill-name { font-size: 11px; margin-bottom: 3px; color: rgba(255,255,255,0.85); }
.sidebar-skill-bar { height: 4px; background: rgba(255,255,255,0.15); border-radius: 2px; }
.sidebar-skill-fill { height: 100%; background: #7fb3e0; border-radius: 2px; }

.lang-item { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px; }
.lang-name { color: rgba(255,255,255,0.85); }
.lang-level { color: rgba(255,255,255,0.5); font-size: 10px; }

/* Right main area */
.cv-main { padding: 32px 28px; }
.cv-main-header { border-bottom: 3px solid #1e3a5f; padding-bottom: 14px; margin-bottom: 22px; }
.cv-main-header h1 { font-size: 24px; font-weight: 700; color: #1e3a5f; letter-spacing: -0.5px; }
.cv-main-header .cv-title { font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 1px; margin-top: 3px; }

.section { margin-bottom: 20px; }
.section-title {
    font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;
    color: #1e3a5f; border-left: 3px solid #1e3a5f;
    padding-left: 8px; margin-bottom: 12px;
}
.item { margin-bottom: 14px; }
.item-header { display: flex; justify-content: space-between; align-items: baseline; }
.item-title  { font-weight: 700; font-size: 13px; color: #2d3748; }
.item-date   { font-size: 10.5px; color: #718096; white-space: nowrap; background: #f7fafc; padding: 1px 6px; border-radius: 3px; }
.item-sub    { font-size: 11.5px; color: #4a5568; margin-top: 1px; }
.item-desc   { font-size: 11.5px; color: #4a5568; margin-top: 5px; line-height: 1.55; }

.project-title { font-weight: 700; font-size: 13px; color: #2d3748; }
.project-tech  { font-size: 10.5px; color: #718096; margin: 2px 0; }
.project-desc  { font-size: 11.5px; color: #4a5568; margin-top: 3px; }
.project-link  { font-size: 10.5px; color: #2b6cb0; margin-top: 2px; }

.cert-name   { font-weight: 600; font-size: 12.5px; }
.cert-issuer { font-size: 11px; color: #718096; }
</style>
</head>
<body>
<div class="cv">
    <!-- Sidebar -->
    <div class="cv-sidebar">
        <div class="cv-photo-wrap">
            <?php if ($photo): ?>
                <img src="<?= $photo ?>" class="cv-photo" alt="Photo">
            <?php else: ?>
                <div class="cv-photo-initial"><?= strtoupper(substr($name, 0, 1)) ?></div>
            <?php endif; ?>
            <div class="cv-name"><?= $name ?></div>
            <?php if ($title): ?><div class="cv-job"><?= $title ?></div><?php endif; ?>
        </div>

        <!-- Contact -->
        <div>
            <div class="sidebar-section-title">Contact</div>
            <ul class="contact-list">
                <?php if ($email):    ?><li>✉ <?= $email ?></li><?php endif; ?>
                <?php if ($phone):    ?><li>☎ <?= $phone ?></li><?php endif; ?>
                <?php if ($location): ?><li>📍 <?= $location ?></li><?php endif; ?>
                <?php if ($website):  ?><li><a href="<?= $website ?>">🌐 Site web</a></li><?php endif; ?>
                <?php if ($linkedin): ?><li><a href="<?= $linkedin ?>">in LinkedIn</a></li><?php endif; ?>
                <?php if ($github):   ?><li><a href="<?= $github ?>">⌘ GitHub</a></li><?php endif; ?>
            </ul>
        </div>

        <!-- Skills -->
        <?php if ($skills): ?>
        <div>
            <div class="sidebar-section-title">Compétences</div>
            <?php foreach ($skills as $s): ?>
            <div class="sidebar-skill">
                <div class="sidebar-skill-name"><?= htmlspecialchars($s['skill_name']) ?></div>
                <div class="sidebar-skill-bar">
                    <div class="sidebar-skill-fill" style="width:<?= $s['skill_level'] ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Languages -->
        <?php if ($languages): ?>
        <div>
            <div class="sidebar-section-title">Langues</div>
            <?php foreach ($languages as $l): ?>
            <div class="lang-item">
                <span class="lang-name"><?= htmlspecialchars($l['language_name']) ?></span>
                <span class="lang-level"><?= htmlspecialchars($l['proficiency']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Certifications -->
        <?php if ($certifications): ?>
        <div>
            <div class="sidebar-section-title">Certifications</div>
            <?php foreach ($certifications as $c): ?>
            <div style="margin-bottom:8px;">
                <div class="cert-name" style="color:rgba(255,255,255,0.85);font-size:11px;"><?= htmlspecialchars($c['cert_name']) ?></div>
                <div class="cert-issuer" style="color:rgba(255,255,255,0.5);font-size:10px;"><?= htmlspecialchars($c['issuer']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="cv-main">
        <div class="cv-main-header">
            <h1><?= $name ?></h1>
            <?php if ($title): ?><div class="cv-title"><?= $title ?></div><?php endif; ?>
        </div>

        <?php if ($summary): ?>
        <div class="section">
            <div class="section-title">Résumé professionnel</div>
            <p style="font-size:12.5px;color:#4a5568;line-height:1.65"><?= $summary ?></p>
        </div>
        <?php endif; ?>

        <?php if ($experience): ?>
        <div class="section">
            <div class="section-title">Expérience professionnelle</div>
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
                <?php if ($edu['description']): ?><div class="item-desc"><?= $edu['description'] ?></div><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($projects): ?>
        <div class="section">
            <div class="section-title">Projets</div>
            <?php foreach ($projects as $p): ?>
            <div class="item">
                <div class="project-title"><?= htmlspecialchars($p['title'] ?? '') ?></div>
                <?php if (!empty($p['technologies'])): ?><div class="project-tech">🔧 <?= htmlspecialchars($p['technologies']) ?></div><?php endif; ?>
                <?php if (!empty($p['description'])): ?><div class="project-desc"><?= $p['description'] ?></div><?php endif; ?>
                <?php if (!empty($p['link_url'])): ?><div class="project-link">🔗 <a href="<?= htmlspecialchars($p['link_url']) ?>"><?= htmlspecialchars($p['link_url']) ?></a></div><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
