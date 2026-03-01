<?php
// Modèle de CV : Sobre
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
body { font-family: 'Georgia', serif; color: #222; background: #fff; font-size: 13px; line-height: 1.5; }
.cv { max-width: 820px; margin: 0 auto; padding: 40px 48px; }

/* Header */
.cv-header { border-bottom: 2px solid #222; padding-bottom: 18px; margin-bottom: 22px; display: flex; align-items: center; gap: 20px; }
.cv-photo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.cv-header-text h1 { font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
.cv-header-text .title { font-size: 13px; color: #555; margin-top: 2px; text-transform: uppercase; letter-spacing: 1px; }
.cv-contact { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 8px; font-size: 11.5px; color: #555; }
.cv-contact span { display: flex; align-items: center; gap: 4px; }
.cv-contact a { color: #555; text-decoration: none; }

/* Section */
.cv-section { margin-bottom: 20px; }
.cv-section-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;
    color: #333; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin-bottom: 12px;
}
.cv-summary { font-size: 12.5px; color: #444; line-height: 1.65; }

/* Education / Experience */
.cv-item { margin-bottom: 14px; }
.cv-item-header { display: flex; justify-content: space-between; align-items: baseline; }
.cv-item-title { font-weight: 700; font-size: 13px; }
.cv-item-date  { font-size: 11px; color: #777; white-space: nowrap; }
.cv-item-subtitle { font-size: 12px; color: #555; margin-top: 1px; }
.cv-item-desc { font-size: 12px; color: #444; margin-top: 5px; line-height: 1.55; }

/* Skills */
.skills-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
.skill-item { display: flex; flex-direction: column; gap: 3px; }
.skill-name { font-size: 12px; font-weight: 500; display: flex; justify-content: space-between; }
.skill-bar { height: 4px; background: #eee; border-radius: 2px; overflow: hidden; }
.skill-fill { height: 100%; background: #222; border-radius: 2px; }

/* Projects */
.project-item { margin-bottom: 14px; }
.project-title { font-weight: 700; font-size: 13px; }
.project-tech  { font-size: 11px; color: #777; margin-top: 2px; }
.project-desc  { font-size: 12px; color: #444; margin-top: 4px; }
.project-link  { font-size: 11px; color: #0066cc; margin-top: 3px; }

/* Languages / Certs */
.lang-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.lang-item  { font-size: 12px; }
.lang-name  { font-weight: 600; }
.lang-level { color: #777; font-size: 11px; }

.cert-item { margin-bottom: 8px; }
.cert-name { font-weight: 600; font-size: 12.5px; }
.cert-issuer { font-size: 12px; color: #555; }
</style>
</head>
<body>
<div class="cv">

    <!-- Header -->
    <div class="cv-header">
        <?php if (!empty($profile['profile_photo'])): ?>
        <img src="<?= htmlspecialchars(UPLOAD_URL . $profile['profile_photo']) ?>" alt="Photo de profil" class="cv-photo">
        <?php endif; ?>
        <div class="cv-header-text">
            <h1><?= htmlspecialchars($profile['full_name'] ?? 'Votre nom') ?></h1>
            <?php if ($title): ?><div class="title"><?= $title ?></div><?php endif; ?>
            <div class="cv-contact">
                <?php if ($email): ?><span>✉ <?= $email ?></span><?php endif; ?>
                <?php if ($phone): ?><span>☎ <?= $phone ?></span><?php endif; ?>
                <?php if ($location): ?><span>📍 <?= $location ?></span><?php endif; ?>
                <?php if ($website): ?><span><a href="<?= $website ?>"><?= parse_url($website, PHP_URL_HOST) ?: $website ?></a></span><?php endif; ?>
                <?php if ($linkedin): ?><span><a href="<?= $linkedin ?>">LinkedIn</a></span><?php endif; ?>
                <?php if ($github): ?><span><a href="<?= $github ?>">GitHub</a></span><?php endif; ?>
                <?php if (!empty($profile['instagram'])): ?><span><a href="<?= htmlspecialchars($profile['instagram']) ?>">Instagram</a></span><?php endif; ?>
                <?php if (!empty($profile['twitter'])): ?><span><a href="<?= htmlspecialchars($profile['twitter']) ?>">Twitter</a></span><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <?php if ($summary): ?>
    <div class="cv-section">
        <div class="cv-section-title">Résumé professionnel</div>
        <p class="cv-summary"><?= $summary ?></p>
    </div>
    <?php endif; ?>

    <!-- Experience -->
    <?php if ($experience): ?>
    <div class="cv-section">
        <div class="cv-section-title">Expérience professionnelle</div>
        <?php foreach ($experience as $exp): ?>
        <div class="cv-item">
            <div class="cv-item-header">
                <span class="cv-item-title"><?= htmlspecialchars($exp['position']) ?></span>
                <span class="cv-item-date"><?= htmlspecialchars($exp['start_date']) ?> – <?= htmlspecialchars($exp['end_date']) ?></span>
            </div>
            <div class="cv-item-subtitle"><?= htmlspecialchars($exp['company']) ?></div>
            <?php if ($exp['description']): ?>
            <div class="cv-item-desc"><?= $exp['description'] ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Education -->
    <?php if ($education): ?>
    <div class="cv-section">
        <div class="cv-section-title">Formation</div>
        <?php foreach ($education as $edu): ?>
        <div class="cv-item">
            <div class="cv-item-header">
                <span class="cv-item-title"><?= htmlspecialchars($edu['degree']) ?><?= $edu['field'] ? ' en ' . htmlspecialchars($edu['field']) : '' ?></span>
                <span class="cv-item-date"><?= htmlspecialchars($edu['start_year']) ?> – <?= htmlspecialchars($edu['end_year']) ?></span>
            </div>
            <div class="cv-item-subtitle"><?= htmlspecialchars($edu['school']) ?></div>
            <?php if ($edu['description']): ?>
            <div class="cv-item-desc"><?= $edu['description'] ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Skills -->
    <?php if ($skills): ?>
    <div class="cv-section">
        <div class="cv-section-title">Compétences</div>
        <div class="skills-grid">
            <?php foreach ($skills as $s): ?>
            <div class="skill-item">
                <div class="skill-name">
                    <span><?= htmlspecialchars($s['skill_name']) ?></span>
                    <span style="color:#999"><?= $s['skill_level'] ?>%</span>
                </div>
                <div class="skill-bar"><div class="skill-fill" style="width:<?= $s['skill_level'] ?>%"></div></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Projects -->
    <?php if ($projects): ?>
    <div class="cv-section">
        <div class="cv-section-title">Projets</div>
        <?php foreach ($projects as $p): ?>
        <div class="project-item">
            <div class="project-title"><?= htmlspecialchars($p['title'] ?? '') ?></div>
            <?php if (!empty($p['technologies'])): ?>
            <div class="project-tech">Tech : <?= htmlspecialchars($p['technologies']) ?></div>
            <?php endif; ?>
            <?php if (!empty($p['description'])): ?>
            <div class="project-desc"><?= $p['description'] ?></div>
            <?php endif; ?>
            <?php if (!empty($p['link_url'])): ?>
            <div class="project-link"><a href="<?= htmlspecialchars($p['link_url']) ?>"><?= htmlspecialchars($p['link_url']) ?></a></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Languages & Certifications -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
        <?php if ($languages): ?>
        <div class="cv-section">
            <div class="cv-section-title">Langues</div>
            <div class="lang-grid">
                <?php foreach ($languages as $l): ?>
                <div class="lang-item">
                    <div class="lang-name"><?= htmlspecialchars($l['language_name']) ?></div>
                    <div class="lang-level"><?= htmlspecialchars($l['proficiency']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($certifications): ?>
        <div class="cv-section">
            <div class="cv-section-title">Certifications</div>
            <?php foreach ($certifications as $c): ?>
            <div class="cert-item">
                <div class="cert-name"><?= htmlspecialchars($c['cert_name']) ?></div>
                <div class="cert-issuer"><?= htmlspecialchars($c['issuer']) ?> · <?= htmlspecialchars($c['issue_date']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Hobbies -->
    <?php if (!empty($profile['hobbies'])): ?>
    <div class="cv-section" style="margin-top: 20px;">
        <div class="cv-section-title">Centres d'intérêt</div>
        <div class="cv-summary"><?= $profile['hobbies'] ?></div>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
