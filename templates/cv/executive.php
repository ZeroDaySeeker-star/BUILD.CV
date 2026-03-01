<?php
// Modèle de CV Executive – Distingué, Structuré, Typographie Serif

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

$accent_color = '#1e293b'; // Ardoise foncée
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --accent: <?= $accent_color ?>;
        --text-main: #1e293b;
        --text-light: #64748b;
        --border: #e2e8f0;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: 'Inter', sans-serif; 
        background: #f1f5f9; 
        color: var(--text-main);
        padding: 40px 0;
    }

    .cv-container {
        width: 210mm;
        min-height: 297mm;
        background: #fff;
        margin: 0 auto;
        padding: 50px 60px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    @media print {
        body { background: #fff; padding: 0; }
        .cv-container { box-shadow: none; width: 100%; padding: 30px; }
        @page { size: A4; margin: 0; }
    }

    /* HEADER */
    header {
        text-align: center;
        margin-bottom: 40px;
        border-bottom: 2px solid var(--accent);
        padding-bottom: 30px;
    }

    .header-name {
        font-family: 'Libre Baskerville', serif;
        font-size: 36px;
        font-weight: 700;
        letter-spacing: -0.5px;
        color: var(--accent);
        margin-bottom: 5px;
    }

    .header-title {
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 3px;
        color: var(--text-light);
        font-weight: 500;
    }

    .contact-bar {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
        font-size: 11px;
        color: var(--text-light);
    }
    .contact-item { display: flex; align-items: center; gap: 6px; }
    .contact-item i { color: var(--accent); font-size: 13px; }

    /* LAYOUT */
    .summary {
        font-style: italic;
        text-align: center;
        max-width: 85%;
        margin: 0 auto 40px;
        line-height: 1.7;
        color: var(--text-light);
        font-size: 14px;
    }

    .section { margin-bottom: 35px; }

    .section-title {
        font-family: 'Libre Baskerville', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .section-title::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .experience-item, .education-item { margin-bottom: 25px; }

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 4px;
    }

    .item-title { font-weight: 700; font-size: 15px; }
    .item-date { font-size: 12px; color: var(--text-light); font-weight: 600; font-style: italic; }
    .item-sub { font-weight: 600; color: var(--accent); font-size: 13px; margin-bottom: 8px; }
    .item-desc { font-size: 13px; line-height: 1.6; color: #475569; }

    /* TWO COLUMNS BOTTOM */
    .bottom-cols { display: flex; gap: 40px; margin-top: 10px; }
    .col-left { flex: 1.5; }
    .col-right { flex: 1; }

    .skill-tags { display: flex; flex-wrap: wrap; gap: 8px; }
    .skill-tag {
        font-size: 11px;
        padding: 4px 10px;
        background: #f1f5f9;
        color: var(--accent);
        border: 1px solid var(--border);
        border-radius: 4px;
    }

    .lang-item {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px dotted var(--border);
    }
    .lang-level { color: var(--text-light); font-style: italic; }

</style>
</head>
<body>

<div class="cv-container">
    <header>
        <h1 class="header-name"><?= $name ?></h1>
        <div class="header-title"><?= $title ?></div>
        
        <div class="contact-bar">
            <?php if ($email): ?>
            <div class="contact-item"><i class="fa-solid fa-envelope"></i> <?= $email ?></div>
            <?php endif; ?>
            <?php if ($phone): ?>
            <div class="contact-item"><i class="fa-solid fa-phone"></i> <?= $phone ?></div>
            <?php endif; ?>
            <?php if ($location): ?>
            <div class="contact-item"><i class="fa-solid fa-location-dot"></i> <?= $location ?></div>
            <?php endif; ?>
            <?php if ($linkedin): ?>
            <div class="contact-item"><i class="fa-brands fa-linkedin"></i> LinkedIn</div>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($summary): ?>
    <div class="summary"><?= $summary ?></div>
    <?php endif; ?>

    <!-- Expériences -->
    <?php if (!empty($experience)): ?>
    <div class="section">
        <h2 class="section-title">Expériences Professionnelles</h2>
        <?php foreach ($experience as $exp): ?>
        <div class="experience-item">
            <div class="item-header">
                <div class="item-title"><?= htmlspecialchars($exp['position'] ?? $exp['title'] ?? 'Poste') ?></div>
                <div class="item-date">
                    <?php 
                    $s = !empty($exp['start_date']) ? date('M Y', strtotime($exp['start_date'])) : '';
                    $e = ($exp['is_current'] ?? false) ? 'Présent' : (!empty($exp['end_date']) ? date('M Y', strtotime($exp['end_date'])) : '');
                    echo $s . ($s && $e ? ' — ' : '') . $e;
                    ?>
                </div>
            </div>
            <div class="item-sub"><?= htmlspecialchars($exp['company'] ?? '') ?></div>
            <div class="item-desc"><?= $exp['description'] ?? '' ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="bottom-cols">
        <div class="col-left">
            <!-- Formation -->
            <?php if (!empty($education)): ?>
            <div class="section">
                <h2 class="section-title">Formation</h2>
                <?php foreach ($education as $edu): ?>
                <div class="education-item">
                    <div class="item-header">
                        <div class="item-title"><?= htmlspecialchars($edu['degree'] ?? '') ?></div>
                        <div class="item-date">
                            <?= htmlspecialchars($edu['start_year'] ?? '') ?> — <?= ($edu['is_current'] ?? false) ? 'Présent' : htmlspecialchars($edu['end_year'] ?? '') ?>
                        </div>
                    </div>
                    <div class="item-sub"><?= htmlspecialchars($edu['school'] ?? '') ?></div>
                    <div class="item-desc"><?= $edu['description'] ?? '' ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-right">
            <!-- Compétences -->
            <?php if (!empty($skills)): ?>
            <div class="section">
                <h2 class="section-title">Savoir-faire</h2>
                <div class="skill-tags">
                    <?php foreach ($skills as $s): ?>
                    <span class="skill-tag"><?= htmlspecialchars($s['skill_name'] ?? '') ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Langues -->
            <?php if (!empty($languages)): ?>
            <div class="section">
                <h2 class="section-title">Langues</h2>
                <?php foreach ($languages as $l): ?>
                <div class="lang-item">
                    <span><?= htmlspecialchars($l['language_name'] ?? '') ?></span>
                    <span class="lang-level"><?= htmlspecialchars($l['proficiency'] ?? '') ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

</body>
</html>
