<?php
/**
 * Modèle Premium : CEO Luxury
 * Design : Bleu marine profond avec accents dorés
 */
$name     = htmlspecialchars($profile['full_name'] ?? 'Votre Nom');
$title    = htmlspecialchars($profile['title'] ?? 'Directeur Général');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
$summary  = $profile['summary'] ?? '';
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;

$initials = '';
if (!$photo && $name) {
    $parts = explode(' ', $name);
    foreach($parts as $p) $initials .= strtoupper(substr($p, 0, 1));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #0a192f; /* Deep Navy */
    --gold: #d4af37; /* Luxury Gold */
    --light-bg: #f8f9fa;
    --text-dark: #333333;
    --text-light: #ffffff;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Lato', sans-serif; background: #ddd; font-size: 13px; line-height: 1.6; color: var(--text-dark); }
.cv-page { width: 21cm; min-height: 29.7cm; background: #fff; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
.header { background: var(--primary); padding: 50px; text-align: center; color: var(--text-light); border-bottom: 5px solid var(--gold); }
.name { font-family: 'Playfair Display', serif; font-size: 42px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px; color: var(--gold); }
.job-title { font-size: 16px; letter-spacing: 3px; text-transform: uppercase; opacity: 0.9; }
.contact-bar { background: var(--gold); color: var(--primary); padding: 15px 50px; display: flex; justify-content: center; gap: 30px; font-weight: 700; font-size: 12px; }
.content { padding: 50px; display: flex; gap: 40px; }
.col-main { flex: 2; }
.col-side { flex: 1; }
.section-title { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--primary); border-bottom: 1px solid var(--gold); padding-bottom: 10px; margin-bottom: 25px; text-transform: uppercase; }
.summary { font-style: italic; font-size: 14px; margin-bottom: 30px; line-height: 1.8; }
.exp-item { margin-bottom: 25px; }
.exp-role { font-weight: 700; font-size: 15px; color: var(--primary); }
.exp-meta { font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 700; }
.exp-desc { font-size: 13px; }
.skill-item { margin-bottom: 10px; font-weight: 700; color: var(--primary); border-left: 3px solid var(--gold); padding-left: 10px; }
</style>
</head>
<body>
<div class="cv-page">
    <div class="header">
        <div class="name"><?= $name ?></div>
        <div class="job-title"><?= $title ?></div>
    </div>
    <div class="contact-bar">
        <?php if($phone) echo "<span>$phone</span>"; ?>
        <?php if($email) echo "<span>$email</span>"; ?>
        <?php if($location) echo "<span>$location</span>"; ?>
    </div>
    <div class="content">
        <div class="col-main">
            <?php if ($summary): ?>
            <div class="section-title">Profil Executif</div>
            <div class="summary"><?= strip_tags($summary) ?></div>
            <?php endif; ?>

            <?php if ($experience): ?>
            <div class="section-title">Expérience Professionnelle</div>
            <?php foreach ($experience as $job): ?>
            <div class="exp-item">
                <div class="exp-role"><?= htmlspecialchars($job['position']) ?></div>
                <div class="exp-meta"><?= htmlspecialchars($job['company']) ?> | <?= $job['start_date'] ?> - <?= $job['end_date'] ?: 'Présent' ?></div>
                <div class="exp-desc"><?= nl2br(htmlspecialchars(strip_tags($job['description']))) ?></div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="col-side">
            <?php if ($education): ?>
            <div class="section-title">Formation</div>
            <?php foreach ($education as $edu): ?>
            <div class="exp-item">
                <div class="exp-role" style="font-size:13px;"><?= htmlspecialchars($edu['degree']) ?></div>
                <div class="exp-meta" style="font-size:11px;"><?= htmlspecialchars($edu['school']) ?><br><?= $edu['start_year'] ?> - <?= $edu['end_year'] ?></div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($skills): ?>
            <div class="section-title" style="margin-top:40px;">Expertise</div>
            <?php foreach ($skills as $s): ?>
                <div class="skill-item"><?= htmlspecialchars($s['skill_name']) ?></div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
