<?php
// Modèle de CV Neo-Retro – Audacieux, Cartouche, Couleurs Pastels

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

$accent_color = '#fee2e2'; // Rose pastel
$border_color = '#1e293b'; // Ardoise foncée
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --accent: <?= $accent_color ?>;
        --border: <?= $border_color ?>;
        --bg: #fff;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: 'Outfit', sans-serif; 
        background: #fefce8; /* Jaune très clair */
        color: var(--border);
        padding: 30px 0;
    }

    .cv-container {
        width: 210mm;
        min-height: 297mm;
        background: #fff;
        margin: 0 auto;
        padding: 50px;
        border: 4px solid var(--border);
        box-shadow: 12px 12px 0px var(--border);
    }

    @media print {
        body { background: #fff; padding: 0; }
        .cv-container { border: 4px solid var(--border); box-shadow: none; width: 100%; margin: 0; }
        @page { size: A4; margin: 0; }
    }

    /* HEADER */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--accent);
        border: 4px solid var(--border);
        padding: 30px;
        margin-bottom: 40px;
    }

    .header-name {
        font-size: 38px;
        font-weight: 800;
        text-transform: uppercase;
        line-height: 1;
    }

    .header-title {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 8px;
    }

    .photo {
        width: 100px;
        height: 100px;
        border: 4px solid var(--border);
        object-fit: cover;
    }

    /* CONTACT GRID */
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 40px;
    }

    .contact-box {
        border: 3px solid var(--border);
        padding: 10px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
    }

    /* SECTIONS */
    .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }

    .card {
        border: 3px solid var(--border);
        margin-bottom: 30px;
        background: #fff;
    }

    .card-header {
        background: var(--border);
        color: #fff;
        padding: 8px 15px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body { padding: 20px; }

    .item { margin-bottom: 20px; border-bottom: 1px dashed var(--border); padding-bottom: 15px; }
    .item:last-child { border: none; padding-bottom: 0; margin-bottom: 0; }

    .item-head { display: flex; justify-content: space-between; margin-bottom: 5px; }
    .item-title { font-weight: 800; font-size: 15px; }
    .item-date { font-size: 11px; background: var(--accent); padding: 2px 8px; border: 2px solid var(--border); font-weight: 800; }
    .item-sub { font-weight: 600; font-size: 13px; color: #64748b; margin-bottom: 8px; }
    .item-desc { font-size: 12.5px; line-height: 1.5; color: #475569; }

    .skill-list { display: flex; flex-wrap: wrap; gap: 8px; }
    .skill-item {
        background: #fff;
        border: 2px solid var(--border);
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .lang-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 12px;
        font-weight: 600;
    }

</style>
</head>
<body>

<div class="cv-container">
    <div class="header">
        <div>
            <h1 class="header-name"><?= $name ?></h1>
            <div class="header-title"><?= $title ?></div>
        </div>
        <?php if ($photo): ?>
        <img src="<?= $photo ?>" class="photo" alt="Photo">
        <?php endif; ?>
    </div>

    <div class="contact-grid">
        <div class="contact-box"><i class="fa-solid fa-envelope"></i> <?= $email ?></div>
        <div class="contact-box"><i class="fa-solid fa-phone"></i> <?= $phone ?></div>
        <div class="contact-box"><i class="fa-solid fa-location-dot"></i> <?= $location ?></div>
    </div>

    <div class="main-grid">
        <div class="left-col">
            <!-- Experience -->
            <?php if (!empty($experience)): ?>
            <div class="card">
                <div class="card-header"><i class="fa-solid fa-bolt"></i> Expériences Professionnelles</div>
                <div class="card-body">
                    <?php foreach ($experience as $exp): ?>
                    <div class="item">
                        <div class="item-head">
                            <div class="item-title"><?= htmlspecialchars($exp['position'] ?? $exp['title'] ?? '') ?></div>
                            <div class="item-date">
                                <?php 
                                $s = !empty($exp['start_date']) ? date('Y', strtotime($exp['start_date'])) : '';
                                $e = ($exp['is_current'] ?? false) ? 'NOW' : (!empty($exp['end_date']) ? date('Y', strtotime($exp['end_date'])) : '');
                                echo $s . ($s && $e ? '–' : '') . $e;
                                ?>
                            </div>
                        </div>
                        <div class="item-sub"><?= htmlspecialchars($exp['company'] ?? '') ?></div>
                        <div class="item-desc"><?= $exp['description'] ?? '' ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Projets -->
            <?php if (!empty($projects)): ?>
            <div class="card">
                <div class="card-header"><i class="fa-solid fa-star"></i> Projets</div>
                <div class="card-body">
                    <?php foreach ($projects as $p): ?>
                    <div class="item">
                        <div class="item-head">
                            <div class="item-title"><?= htmlspecialchars($p['project_name'] ?? $p['title'] ?? '') ?></div>
                        </div>
                        <div class="item-desc"><?= $p['description'] ?? '' ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="right-col">
            <!-- Profil -->
            <?php if ($summary): ?>
            <div class="card">
                <div class="card-header">Profil</div>
                <div class="card-body" style="font-size: 13px; line-height: 1.5;">
                    <?= $summary ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Formation -->
            <?php if (!empty($education)): ?>
            <div class="card">
                <div class="card-header">Etudes</div>
                <div class="card-body">
                    <?php foreach ($education as $edu): ?>
                    <div class="item">
                        <div class="item-title" style="font-size: 13px;"><?= htmlspecialchars($edu['degree'] ?? '') ?></div>
                        <div class="item-sub" style="font-size: 11px;"><?= htmlspecialchars($edu['school'] ?? '') ?></div>
                        <div class="item-date" style="font-size: 9px; margin-top: 5px; display: inline-block;">
                            <?= htmlspecialchars($edu['end_year'] ?? '') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Compétences -->
            <?php if (!empty($skills)): ?>
            <div class="card">
                <div class="card-header">Skills</div>
                <div class="card-body">
                    <div class="skill-list">
                        <?php foreach ($skills as $s): ?>
                        <span class="skill-item"><?= htmlspecialchars($s['skill_name'] ?? '') ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
