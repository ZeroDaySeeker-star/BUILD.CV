<?php
// Modèle de CV Moderne – Design épuré avec header accentué
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
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Poppins', sans-serif; color: #1a1a1a; background: #ffffff; font-size: 12px; line-height: 1.6; }
.cv { max-width: 850px; margin: 0 auto; padding: 50px 45px; }

/* Header */
.cv-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    border-radius: 12px;
    margin-bottom: 35px;
    display: flex;
    gap: 25px;
    align-items: center;
}
.cv-photo {
    width: 100px;
    height: 100px;
    border-radius: 12px;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.2);
    flex-shrink: 0;
}
.cv-header-info h1 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 2px;
}
.cv-header-info .title {
    font-size: 14px;
    opacity: 0.95;
    margin-bottom: 10px;
}
.cv-contact {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 11px;
    opacity: 0.9;
}
.cv-contact span {
    display: flex;
    align-items: center;
    gap: 5px;
}
.cv-contact a {
    color: white;
    text-decoration: none;
}

/* Summary */
.cv-summary-section {
    margin-bottom: 32px;
    padding: 20px;
    background: #f8f9ff;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}
.cv-summary-section p {
    font-size: 13px;
    line-height: 1.68;
    color: #333;
}

/* Sections */
.cv-section {
    margin-bottom: 28px;
}
.cv-section-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #667eea;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

/* Items */
.cv-item {
    margin-bottom: 16px;
    padding-bottom: 10px;
}
.cv-item-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
}
.cv-item-title {
    font-weight: 700;
    font-size: 13px;
    color: #1a1a1a;
}
.cv-item-date {
    font-size: 11px;
    color: #999;
    white-space: nowrap;
    margin-left: 10px;
}
.cv-item-subtitle {
    font-size: 12px;
    color: #666;
    margin-top: 2px;
}
.cv-item-desc {
    font-size: 12px;
    color: #555;
    margin-top: 6px;
    line-height: 1.6;
}

/* Skills */
.skills-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
.skill-item {
    background: #f8f9ff;
    padding: 10px 12px;
    border-radius: 6px;
    border-left: 3px solid #667eea;
    font-size: 12px;
    font-weight: 500;
    color: #667eea;
}

/* Languages */
.lang-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
.lang-item {
    background: #f8f9ff;
    padding: 10px;
    border-radius: 6px;
    font-size: 12px;
}
.lang-name {
    font-weight: 600;
    color: #1a1a1a;
}
.lang-level {
    color: #999;
    font-size: 11px;
    margin-top: 2px;
}

/* Certifications */
.cert-item {
    margin-bottom: 10px;
    font-size: 12px;
}
.cert-name {
    font-weight: 600;
    color: #1a1a1a;
}
.cert-issuer {
    color: #666;
    font-size: 11px;
}
</style>
</head>
<body>
<div class="cv">

    <!-- Header -->
    <div class="cv-header">
        <?php if ($photo): ?>
        <img src="<?= $photo ?>" class="cv-photo" alt="Photo">
        <?php endif; ?>
        <div class="cv-header-info">
            <h1><?= $name ?></h1>
            <div class="title"><?= $title ?: 'Professionnel' ?></div>
            <div class="cv-contact">
                <?php if ($email): ?><span><?= $email ?></span><?php endif; ?>
                <?php if ($phone): ?><span><?= $phone ?></span><?php endif; ?>
                <?php if ($location): ?><span><?= $location ?></span><?php endif; ?>
                <?php if ($website): ?><span><a href="<?= $website ?>" target="_blank"><?= parse_url($website, PHP_URL_HOST) ?: $website ?></a></span><?php endif; ?>
                <?php if ($linkedin): ?><span><a href="<?= $linkedin ?>" target="_blank">LinkedIn</a></span><?php endif; ?>
                <?php if ($github): ?><span><a href="<?= $github ?>" target="_blank">GitHub</a></span><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <?php if ($summary): ?>
    <div class="cv-summary-section">
        <p><?= $summary ?></p>
    </div>
    <?php endif; ?>

    <!-- Experience -->
    <?php
    if ($experience):
    ?>
    <div class="cv-section">
        <h2 class="cv-section-title">Expérience</h2>
        <?php foreach ($experience as $job): ?>
        <div class="cv-item">
            <div class="cv-item-header">
                <span class="cv-item-title"><?= htmlspecialchars($job['position'] ?? '') ?></span>
                <span class="cv-item-date"><?= date('M Y', strtotime($job['start_date'])) ?> – <?= date('M Y', strtotime($job['end_date'])) ?></span>
            </div>
            <div class="cv-item-subtitle"><?= htmlspecialchars($job['company'] ?? '') ?><?= ($job['location'] ?? '') ? ' • ' . htmlspecialchars($job['location']) : '' ?></div>
            <div class="cv-item-desc"><?= $job['description'] ?? '' ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Education -->
    <?php
    if ($education):
    ?>
    <div class="cv-section">
        <h2 class="cv-section-title">Formation</h2>
        <?php foreach ($education as $edu): ?>
        <div class="cv-item">
            <div class="cv-item-header">
                <span class="cv-item-title"><?= htmlspecialchars($edu['degree'] ?? '') ?></span>
                <span class="cv-item-date"><?= htmlspecialchars($edu['end_year']) ?></span>
            </div>
            <div class="cv-item-subtitle"><?= htmlspecialchars($edu['school'] ?? '') ?></div>
            <?php if ($edu['field'] ?? ''): ?>
            <div class="cv-item-desc">Domaine: <?= htmlspecialchars($edu['field']) ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Skills -->
    <?php
    if ($skills):
    ?>
    <div class="cv-section">
        <h2 class="cv-section-title">Compétences</h2>
        <div class="skills-grid">
            <?php foreach ($skills as $skill): ?>
            <div class="skill-item"><?= htmlspecialchars($skill['skill_name'] ?? '') ?></div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Languages -->
    <?php
    if ($languages):
    ?>
    <div class="cv-section">
        <h2 class="cv-section-title">Langues</h2>
        <div class="lang-grid">
            <?php foreach ($languages as $lang): ?>
            <div class="lang-item">
                <div class="lang-name"><?= htmlspecialchars($lang['language_name'] ?? '') ?></div>
                <div class="lang-level"><?= htmlspecialchars($lang['proficiency'] ?? '') ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Certifications -->
    <?php
    if (!empty($certifications)): // Use $certifications instead of $certs
        $certs = $certifications;
    ?>
    <div class="cv-section">
        <h2 class="cv-section-title">Certifications</h2>
        <?php foreach ($certs as $cert): ?>
        <div class="cert-item">
            <div class="cert-name"><?= htmlspecialchars($cert['cert_name'] ?? '') ?></div>
            <div class="cert-issuer"><?= htmlspecialchars($cert['issuer'] ?? '') ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
