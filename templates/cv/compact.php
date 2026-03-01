<?php
// Modèle de CV Compact – Une seule page optimisée, parfait pour débutants
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
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Roboto', sans-serif; color: #2c3e50; background: #fff; font-size: 11px; line-height: 1.5; }
@page { size: A4; margin: 0.5cm; }
.cv { max-width: 210mm; height: 297mm; padding: 12mm 10mm; }

/* Header */
.cv-header {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
    border-bottom: 2px solid #34495e;
    padding-bottom: 10px;
}
.cv-photo {
    width: 50px;
    height: 50px;
    border-radius: 4px;
    object-fit: cover;
    flex-shrink: 0;
}
.cv-header-info h1 {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 1px;
    color: #1a1a1a;
}
.cv-header-info p {
    font-size: 10px;
    color: #555;
    margin-bottom: 3px;
}
.cv-contact {
    font-size: 10px;
    color: #666;
    display: grid;
    grid-template-columns: auto auto;
    gap: 8px;
}

/* Sections */
.cv-section {
    margin-bottom: 10px;
}
.cv-section-title {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: #34495e;
    border-bottom: 1px solid #bdc3c7;
    padding-bottom: 3px;
    margin-bottom: 6px;
}

/* Items */
.cv-item {
    margin-bottom: 6px;
    page-break-inside: avoid;
}
.cv-item-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1px;
}
.cv-item-title {
    font-weight: 700;
    font-size: 11px;
}
.cv-item-date {
    font-size: 9px;
    color: #7f8c8d;
}
.cv-item-subtitle {
    font-size: 10px;
    color: #555;
    margin-bottom: 1px;
}
.cv-item-desc {
    font-size: 10px;
    color: #666;
    line-height: 1.4;
}

/* Skills */
.skills-inline {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    font-size: 10px;
}
.skill-tag {
    background: #ecf0f1;
    padding: 3px 8px;
    border-radius: 3px;
    color: #34495e;
}

/* Languages inline */
.lang-inline {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 10px;
}
.lang-item {
    display: flex;
    gap: 4px;
}
.lang-name {
    font-weight: 600;
}

/* Two-column layout for skills + languages */
.two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
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
            <p><?= $title ?: 'Professionnel' ?></p>
            <div class="cv-contact">
                <?php if ($email): ?><span><?= $email ?></span><?php endif; ?>
                <?php if ($phone): ?><span><?= $phone ?></span><?php endif; ?>
                <?php if ($location): ?><span colspan="2"><?= $location ?></span><?php endif; ?>
                <?php if ($website || $linkedin || $github): ?>
                <span style="grid-column: 1/-1;">
                    <?php if ($website): ?><a href="<?= $website ?>" style="color: #34495e; text-decoration: none;"><?= parse_url($website, PHP_URL_HOST) ?: 'Web' ?></a> &nbsp;<?php endif; ?>
                    <?php if ($linkedin): ?><a href="<?= $linkedin ?>" style="color: #34495e; text-decoration: none;">LinkedIn</a> &nbsp;<?php endif; ?>
                    <?php if ($github): ?><a href="<?= $github ?>" style="color: #34495e; text-decoration: none;">GitHub</a><?php endif; ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <?php if ($summary): ?>
    <div class="cv-section">
        <h2 class="cv-section-title">À propos</h2>
        <p style="font-size: 10px; line-height: 1.5; color: #555;">
            <?= $summary ?>
        </p>
    </div>
    <?php endif; ?>

    <!-- Experience + Education (two-column) -->
    <div class="two-col">
        <!-- Experience -->
        <?php
        $experience = array_slice($experience ?? [], 0, 3);
        if ($experience):
        ?>
        <div class="cv-section">
            <h2 class="cv-section-title">Expérience</h2>
            <?php foreach ($experience as $job): ?>
            <div class="cv-item">
                <div class="cv-item-header">
                    <span class="cv-item-title"><?= htmlspecialchars($job['position'] ?? '') ?></span>
                </div>
                <div class="cv-item-subtitle"><?= htmlspecialchars($job['company'] ?? '') ?></div>
                <div class="cv-item-date"><?= date('M Y', strtotime($job['start_date'])) ?> – <?= date('M Y', strtotime($job['end_date'])) ?></div>
                <div class="cv-item-desc"><?= $job['description'] ?? '' ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Education -->
        <?php
        $education = array_slice($education ?? [], 0, 2);
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
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Skills + Languages inline -->
    <div class="two-col">
        <!-- Skills -->
        <?php
        $skills = array_slice($skills ?? [], 0, 8);
        if ($skills):
        ?>
        <div class="cv-section">
            <h2 class="cv-section-title">Compétences</h2>
            <div class="skills-inline">
                <?php foreach ($skills as $skill): ?>
                <span class="skill-tag"><?= htmlspecialchars($skill['skill_name'] ?? '') ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Languages -->
        <?php
        $languages = array_slice($languages ?? [], 0, 4);
        if ($languages):
        ?>
        <div class="cv-section">
            <h2 class="cv-section-title">Langues</h2>
            <div class="lang-inline">
                <?php foreach ($languages as $lang): ?>
                <div class="lang-item">
                    <span class="lang-name"><?= htmlspecialchars($lang['language_name'] ?? '') ?></span>
                    <span>(<?= htmlspecialchars($lang['proficiency'] ?? '') ?>)</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
