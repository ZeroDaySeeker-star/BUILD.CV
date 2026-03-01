<?php
// Modèle de CV Elegant – Editorial, Chic, Typographie Playfair

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

$accent_color = '#b45309'; // Ambre / Or vieilli
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --accent: <?= $accent_color ?>;
        --text-dark: #1a1a1a;
        --text-soft: #57534e;
        --bg-cream: #fffcf2;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: 'Lora', serif; 
        background: #f5f5f5; 
        color: var(--text-dark);
        padding: 40px 0;
    }

    .cv-container {
        width: 210mm;
        min-height: 297mm;
        background: var(--bg-cream);
        margin: 0 auto;
        padding: 70px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    @media print {
        body { background: #fff; padding: 0; }
        .cv-container { box-shadow: none; width: 100%; border: none; padding: 40px; }
        @page { size: A4; margin: 0; }
    }

    /* TOP HEADER */
    header {
        text-align: center;
        margin-bottom: 60px;
    }

    .photo-frame {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        padding: 5px;
        border: 1px solid var(--accent);
        border-radius: 50%;
    }
    .photo-frame img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .header-name {
        font-family: 'Playfair Display', serif;
        font-size: 48px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        line-height: 1;
        margin-bottom: 10px;
    }

    .header-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-style: italic;
        color: var(--accent);
        letter-spacing: 1px;
    }

    /* CONTACT INFO */
    .contact-info {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 25px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-soft);
        border-top: 1px solid #e7e5e4;
        border-bottom: 1px solid #e7e5e4;
        padding: 12px 0;
    }

    /* CONTENT GRID */
    .main-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 60px; margin-top: 50px; }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 25px;
        border-left: 3px solid var(--accent);
        padding-left: 15px;
        text-transform: capitalize;
    }

    .summary {
        font-size: 14px;
        line-height: 1.8;
        color: var(--text-soft);
        text-align: justify;
        margin-bottom: 40px;
        font-style: italic;
    }

    .item { margin-bottom: 30px; }
    .item-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .item-title { font-size: 16px; font-weight: 700; font-family: 'Playfair Display', serif; }
    .item-date { font-size: 11px; color: var(--accent); font-weight: 600; text-transform: uppercase; }
    .item-sub { font-size: 13px; color: var(--text-soft); font-weight: 500; margin-bottom: 10px; }
    .item-desc { font-size: 13px; line-height: 1.6; color: var(--text-soft); }

    .skills-group { margin-bottom: 20px; }
    .skills-title { font-size: 13px; font-weight: 700; margin-bottom: 10px; color: var(--accent); }
    .skill-list { list-style: none; font-size: 12.5px; line-height: 2; }
    .skill-list li::before { content: "— "; color: var(--accent); }

</style>
</head>
<body>

<div class="cv-container">
    <header>
        <?php if ($photo): ?>
        <div class="photo-frame">
            <img src="<?= $photo ?>" alt="Photo">
        </div>
        <?php endif; ?>
        <h1 class="header-name"><?= $name ?></h1>
        <div class="header-title"><?= $title ?></div>
        
        <div class="contact-info">
            <?php if ($phone): ?><span><?= $phone ?></span><?php endif; ?>
            <?php if ($email): ?><span><?= $email ?></span><?php endif; ?>
            <?php if ($location): ?><span><?= $location ?></span><?php endif; ?>
        </div>
    </header>

    <?php if ($summary): ?>
    <div class="summary">
        <?= $summary ?>
    </div>
    <?php endif; ?>

    <div class="main-grid">
        <aside>
            <!-- Formation -->
            <?php if (!empty($education)): ?>
            <div class="section">
                <h2 class="section-title">Parcours</h2>
                <?php foreach ($education as $edu): ?>
                <div class="item">
                    <div class="item-date"><?= htmlspecialchars($edu['end_year'] ?? '') ?></div>
                    <div class="item-title"><?= htmlspecialchars($edu['degree'] ?? '') ?></div>
                    <div class="item-sub"><?= htmlspecialchars($edu['school'] ?? '') ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Compétences -->
            <?php if (!empty($skills)): ?>
            <div class="section">
                <h2 class="section-title">Expertise</h2>
                <ul class="skill-list">
                    <?php foreach ($skills as $s): ?>
                    <li><?= htmlspecialchars($s['skill_name'] ?? '') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Langues -->
            <?php if (!empty($languages)): ?>
            <div class="section">
                <h2 class="section-title">Langues</h2>
                <ul class="skill-list">
                    <?php foreach ($languages as $l): ?>
                    <li><?= htmlspecialchars($l['language_name'] ?? '') ?> (<?= htmlspecialchars($l['proficiency'] ?? '') ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </aside>

        <main>
            <!-- Experience -->
            <?php if (!empty($experience)): ?>
            <div class="section">
                <h2 class="section-title">Expérience Professionnelle</h2>
                <?php foreach ($experience as $exp): ?>
                <div class="item">
                    <div class="item-header">
                        <div class="item-title"><?= htmlspecialchars($exp['position'] ?? $exp['title'] ?? '') ?></div>
                        <div class="item-date">
                            <?php 
                            $s = !empty($exp['start_date']) ? date('Y', strtotime($exp['start_date'])) : '';
                            $e = ($exp['is_current'] ?? false) ? 'Present' : (!empty($exp['end_date']) ? date('Y', strtotime($exp['end_date'])) : '');
                            echo $s . ($s && $e ? ' – ' : '') . $e;
                            ?>
                        </div>
                    </div>
                    <div class="item-sub"><?= htmlspecialchars($exp['company'] ?? '') ?></div>
                    <div class="item-desc"><?= $exp['description'] ?? '' ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Projets -->
            <?php if (!empty($projects)): ?>
            <div class="section">
                <h2 class="section-title">Projets Marquants</h2>
                <?php foreach ($projects as $p): ?>
                <div class="item">
                    <div class="item-title"><?= htmlspecialchars($p['project_name'] ?? $p['title'] ?? '') ?></div>
                    <div class="item-desc"><?= $p['description'] ?? '' ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

</body>
</html>
