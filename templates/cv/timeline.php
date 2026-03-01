<?php
// Modèle de CV Timeline – Moderne, Visuel, Connecté

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

$accent_color = '#6366f1'; // Indigo
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --accent: <?= $accent_color ?>;
        --accent-light: rgba(99, 102, 241, 0.1);
        --text-main: #1f2937;
        --text-light: #6b7280;
        --bg: #fdfdfd;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: 'Inter', sans-serif; 
        background: #f3f4f6; 
        color: var(--text-main);
        padding: 20px 0;
        line-height: 1.5;
    }

    .cv-container {
        width: 210mm;
        min-height: 297mm;
        background: #ffffff;
        margin: 0 auto;
        padding: 60px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        position: relative;
    }

    @media print {
        body { background: #fff; padding: 0; }
        .cv-container { box-shadow: none; width: 100%; padding: 40px; }
        @page { size: A4; margin: 0; }
    }

    /* HEADER */
    header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 50px;
    }

    .header-info h1 {
        font-size: 44px;
        font-weight: 800;
        letter-spacing: -1.5px;
        color: var(--text-main);
        line-height: 0.9;
    }

    .header-info p {
        font-size: 18px;
        color: var(--accent);
        font-weight: 600;
        margin-top: 10px;
    }

    .header-contact {
        text-align: right;
        font-size: 12px;
        color: var(--text-light);
    }
    .header-contact div { margin-bottom: 5px; }

    /* LAYOUT COLLS */
    .layout-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 50px; }

    /* TIMELINE SYSTEM */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: "";
        position: absolute;
        left: 0;
        top: 10px;
        bottom: 0;
        width: 2px;
        background: var(--accent-light);
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--accent);
        margin-bottom: 30px;
        position: relative;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 35px;
    }
    .timeline-item::before {
        content: "";
        position: absolute;
        left: -33px;
        top: 6px;
        width: 8px;
        height: 8px;
        background: #fff;
        border: 2px solid var(--accent);
        border-radius: 50%;
        z-index: 2;
    }

    .item-date {
        font-size: 11px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .item-title { font-size: 16px; font-weight: 700; margin-bottom: 2px; }
    .item-sub { font-size: 13px; font-weight: 500; color: var(--text-light); margin-bottom: 10px; }
    .item-desc { font-size: 13px; color: #4b5563; }

    /* SIDEBAR */
    .sidebar-section { margin-bottom: 40px; }
    .sidebar-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sidebar-title::after { content: ""; flex: 1; height: 1px; background: var(--accent-light); }

    .skill-tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .skill-tag {
        font-size: 11px;
        padding: 4px 10px;
        background: var(--accent-light);
        color: var(--accent);
        border-radius: 10px;
        font-weight: 500;
    }

    .lang-item { margin-bottom: 12px; }
    .lang-info { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px; }
    .lang-dots { display: flex; gap: 4px; }
    .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent-light); }
    .dot.active { background: var(--accent); }

</style>
</head>
<body>

<div class="cv-container">
    <header>
        <div class="header-info">
            <h1><?= $name ?></h1>
            <p><?= $title ?></p>
        </div>
        <div class="header-contact">
            <?php if ($email): ?><div><?= $email ?> <i class="fa-solid fa-envelope"></i></div><?php endif; ?>
            <?php if ($phone): ?><div><?= $phone ?> <i class="fa-solid fa-phone"></i></div><?php endif; ?>
            <?php if ($location): ?><div><?= $location ?> <i class="fa-solid fa-location-dot"></i></div><?php endif; ?>
            <?php if ($website): ?><div><?= str_replace(['https://','http://'], '', $website) ?> <i class="fa-solid fa-globe"></i></div><?php endif; ?>
        </div>
    </header>

    <div class="layout-grid">
        <div class="main-timeline">
            <!-- Expérience -->
            <?php if (!empty($experience)): ?>
            <div class="section">
                <h2 class="section-title">Expérience</h2>
                <div class="timeline">
                    <?php foreach ($experience as $exp): ?>
                    <div class="timeline-item">
                        <div class="item-date">
                            <?php 
                            $s = !empty($exp['start_date']) ? date('M Y', strtotime($exp['start_date'])) : '';
                            $e = ($exp['is_current'] ?? false) ? 'Présent' : (!empty($exp['end_date']) ? date('M Y', strtotime($exp['end_date'])) : '');
                            echo $s . ($s && $e ? ' — ' : '') . $e;
                            ?>
                        </div>
                        <div class="item-title"><?= htmlspecialchars($exp['position'] ?? $exp['title'] ?? '') ?></div>
                        <div class="item-sub"><?= htmlspecialchars($exp['company'] ?? '') ?></div>
                        <div class="item-desc"><?= $exp['description'] ?? '' ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Formation -->
            <?php if (!empty($education)): ?>
            <div class="section" style="margin-top: 20px;">
                <h2 class="section-title">Formation</h2>
                <div class="timeline">
                    <?php foreach ($education as $edu): ?>
                    <div class="timeline-item">
                        <div class="item-date">
                            <?= htmlspecialchars($edu['start_year'] ?? '') ?> — <?= ($edu['is_current'] ?? false) ? 'Présent' : htmlspecialchars($edu['end_year'] ?? '') ?>
                        </div>
                        <div class="item-title"><?= htmlspecialchars($edu['degree'] ?? '') ?></div>
                        <div class="item-sub"><?= htmlspecialchars($edu['school'] ?? '') ?></div>
                        <div class="item-desc"><?= $edu['description'] ?? '' ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="sidebar">
            <?php if ($summary): ?>
            <div class="sidebar-section">
                <h3 class="sidebar-title">Profil</h3>
                <div style="font-size: 13px; line-height: 1.6; color: var(--text-light);">
                    <?= $summary ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($skills)): ?>
            <div class="sidebar-section">
                <h3 class="sidebar-title">Compétences</h3>
                <div class="skill-tags">
                    <?php foreach ($skills as $s): ?>
                    <span class="skill-tag"><?= htmlspecialchars($s['skill_name'] ?? '') ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($languages)): ?>
            <div class="sidebar-section">
                <h3 class="sidebar-title">Langues</h3>
                <?php foreach ($languages as $l): ?>
                <div class="lang-item">
                    <div class="lang-info">
                        <span><?= htmlspecialchars($l['language_name'] ?? '') ?></span>
                        <span><?= htmlspecialchars($l['proficiency'] ?? 'B1') ?></span>
                    </div>
                    <div class="lang-dots">
                        <?php 
                        $lvl = ['Débutant'=>1,'Intermédiaire'=>2,'Avancé'=>3,'Bilingue'=>4,'Maternel'=>5];
                        $val = $lvl[$l['proficiency'] ?? ''] ?? 3;
                        for($i=1; $i<=5; $i++): ?>
                        <div class="dot <?= $i <= $val ? 'active' : '' ?>"></div>
                        <?php endfor; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Projets (Optionnel) -->
            <?php if (!empty($projects)): ?>
            <div class="sidebar-section">
                <h3 class="sidebar-title">Projets</h3>
                <?php foreach ($projects as $p): ?>
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 12px; font-weight: 700;"><?= htmlspecialchars($p['project_name'] ?? $p['title'] ?? '') ?></div>
                    <div style="font-size: 11px; color: var(--text-light);"><?= $p['description'] ?? '' ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
