<?php
// Modèle de CV Startup – Style Canva, Asymétrique, Moderne
// Version Finale avec toutes les sections et correctifs de variables

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

$accent_color = '#0f172a'; // Bleu Nuit
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
        --sidebar-text: #f8fafc;
        --text-main: #334155;
        --text-light: #94a3b8;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: 'Inter', sans-serif; 
        background: #f8fafc; 
        color: var(--text-main);
        display: flex;
        justify-content: center;
        padding: 0;
    }

    .cv-container {
        width: 210mm;
        min-height: 297mm;
        background: #fff;
        display: flex;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    @media print {
        body { background: #fff; padding: 0; }
        .cv-container { box-shadow: none; width: 100%; min-height: 100vh; }
        @page { size: A4; margin: 0; }
    }

    /* SIDEBAR */
    .sidebar {
        width: 32%;
        background-color: var(--accent);
        color: var(--sidebar-text);
        padding: 40px 25px;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
    }

    .photo-container {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }

    .photo-container img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,0.2);
    }

    .sidebar-section { margin-bottom: 30px; }
    
    .sidebar-title {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        margin-bottom: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.15);
        padding-bottom: 5px;
        color: #fff;
    }

    .contact-item {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 11.5px;
        font-weight: 300;
        align-items: flex-start;
    }
    
    .contact-item i { width: 14px; margin-top: 2px; text-align: center; opacity: 0.8; }
    .contact-item a { color: inherit; text-decoration: none; word-break: break-all; }

    .skills-list { list-style: none; }
    .skills-list li {
        font-size: 12px;
        margin-bottom: 6px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }
    .skills-list li::before {
        content: "•";
        color: rgba(255,255,255,0.5);
        font-size: 14px;
        line-height: 1;
    }

    .lang-item { margin-bottom: 10px; }
    .lang-name { font-size: 12px; display: flex; justify-content: space-between; margin-bottom: 3px; }
    .lang-bar {
        height: 4px;
        background: rgba(255,255,255,0.1);
        border-radius: 2px;
    }
    .lang-fill { height: 100%; background: #fff; border-radius: 2px; }

    /* CONTENT */
    .main-content {
        width: 68%;
        padding: 50px 40px;
        background: #fff;
    }

    .header-name {
        font-size: 38px;
        font-weight: 800;
        color: var(--accent);
        line-height: 1;
        text-transform: uppercase;
    }

    .header-title {
        font-size: 17px;
        color: var(--text-light);
        font-weight: 500;
        margin-top: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .divider {
        height: 4px;
        width: 50px;
        background: var(--accent);
        margin: 20px 0;
    }

    .summary {
        font-size: 13px;
        line-height: 1.6;
        color: #475569;
        margin-bottom: 35px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title i { font-size: 14px; }

    .timeline-item { margin-bottom: 20px; }
    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .timeline-role { font-size: 14px; font-weight: 700; color: var(--text-main); }
    .timeline-date { font-size: 11px; color: var(--text-light); font-weight: 600; }
    .timeline-sub { font-size: 12.5px; color: var(--accent); font-weight: 600; margin-bottom: 5px; }
    .timeline-desc { font-size: 12px; line-height: 1.5; color: #64748b; }
    .timeline-desc ul { padding-left: 15px; }

</style>
</head>
<body>

<div class="cv-container">
    <div class="sidebar">
        <?php if ($photo): ?>
        <div class="photo-container">
            <img src="<?= $photo ?>" alt="Photo">
        </div>
        <?php endif; ?>

        <div class="sidebar-section">
            <h3 class="sidebar-title">Contact</h3>
            <?php if ($email): ?>
            <div class="contact-item"><i class="fa-solid fa-envelope"></i><a href="mailto:<?= $email ?>"><?= $email ?></a></div>
            <?php endif; ?>
            <?php if ($phone): ?>
            <div class="contact-item"><i class="fa-solid fa-phone"></i><span><?= $phone ?></span></div>
            <?php endif; ?>
            <?php if ($location): ?>
            <div class="contact-item"><i class="fa-solid fa-location-dot"></i><span><?= $location ?></span></div>
            <?php endif; ?>
            <?php if ($website): ?>
            <div class="contact-item"><i class="fa-solid fa-globe"></i><span><?= str_replace(['https://','http://'], '', $website) ?></span></div>
            <?php endif; ?>
            <?php if ($linkedin): ?>
            <div class="contact-item"><i class="fa-brands fa-linkedin"></i><span>LinkedIn</span></div>
            <?php endif; ?>
            <?php if ($github): ?>
            <div class="contact-item"><i class="fa-brands fa-github"></i><span>GitHub</span></div>
            <?php endif; ?>
        </div>

        <?php if (!empty($skills)): ?>
        <div class="sidebar-section">
            <h3 class="sidebar-title">Compétences</h3>
            <ul class="skills-list">
                <?php foreach ($skills as $s): ?>
                    <li><?= htmlspecialchars($s['skill_name'] ?? '') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (!empty($languages)): ?>
        <div class="sidebar-section">
            <h3 class="sidebar-title">Langues</h3>
            <?php foreach ($languages as $l): ?>
            <div class="lang-item">
                <div class="lang-name">
                    <span><?= htmlspecialchars($l['language_name'] ?? '') ?></span>
                    <span style="opacity:0.6; font-size:10px;"><?= htmlspecialchars($l['proficiency'] ?? '') ?></span>
                </div>
                <div class="lang-bar">
                    <?php 
                    $lvl = ['Débutant'=>25,'Intermédiaire'=>50,'Avancé'=>75,'Bilingue'=>100,'Maternel'=>100];
                    $p = $lvl[$l['proficiency'] ?? ''] ?? 50;
                    ?>
                    <div class="lang-fill" style="width:<?= $p ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="main-content">
        <h1 class="header-name"><?= $name ?></h1>
        <?php if ($title): ?><h2 class="header-title"><?= $title ?></h2><?php endif; ?>
        <div class="divider"></div>
        <?php if ($summary): ?><div class="summary"><?= $summary ?></div><?php endif; ?>

        <!-- Experience -->
        <?php if (!empty($experience)): ?>
        <div class="section">
            <h2 class="section-title"><i class="fa-solid fa-briefcase"></i> Expérience Professionnelle</h2>
            <?php foreach ($experience as $exp): ?>
            <div class="timeline-item">
                <div class="timeline-header">
                    <div class="timeline-role"><?= htmlspecialchars($exp['position'] ?? $exp['title'] ?? 'Poste') ?></div>
                    <div class="timeline-date">
                        <?php 
                        $s = !empty($exp['start_date']) ? date('m/Y', strtotime($exp['start_date'])) : '';
                        $e = ($exp['is_current'] ?? false) ? 'Présent' : (!empty($exp['end_date']) ? date('m/Y', strtotime($exp['end_date'])) : '');
                        echo trim($s . ($s && $e ? ' - ' : '') . $e);
                        ?>
                    </div>
                </div>
                <div class="timeline-sub"><?= htmlspecialchars($exp['company'] ?? '') ?></div>
                <div class="timeline-desc"><?= $exp['description'] ?? '' ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Formation -->
        <?php if (!empty($education)): ?>
        <div class="section" style="margin-top:25px">
            <h2 class="section-title"><i class="fa-solid fa-graduation-cap"></i> Formation</h2>
            <?php foreach ($education as $edu): ?>
            <div class="timeline-item">
                <div class="timeline-header">
                    <div class="timeline-role"><?= htmlspecialchars($edu['degree'] ?? '') ?></div>
                    <div class="timeline-date">
                        <?php 
                        $sy = htmlspecialchars($edu['start_year'] ?? $edu['start_date'] ?? '');
                        $ey = ($edu['is_current'] ?? false) ? 'Présent' : htmlspecialchars($edu['end_year'] ?? $edu['end_date'] ?? '');
                        echo trim($sy . ($sy && $ey ? ' - ' : '') . $ey);
                        ?>
                    </div>
                </div>
                <div class="timeline-sub"><?= htmlspecialchars($edu['school'] ?? '') ?></div>
                <div class="timeline-desc"><?= $edu['description'] ?? '' ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Projets -->
        <?php if (!empty($projects)): ?>
        <div class="section" style="margin-top:25px">
            <h2 class="section-title"><i class="fa-solid fa-lightbulb"></i> Projets</h2>
            <?php foreach ($projects as $proj): ?>
            <div class="timeline-item">
                <div class="timeline-header">
                    <div class="timeline-role"><?= htmlspecialchars($proj['project_name'] ?? $proj['title'] ?? '') ?></div>
                    <div class="timeline-date"><?= !empty($proj['date']) ? date('m/Y', strtotime($proj['date'])) : '' ?></div>
                </div>
                <div class="timeline-desc"><?= $proj['description'] ?? '' ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
