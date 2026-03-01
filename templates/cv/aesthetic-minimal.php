<?php
/**
 * Modèle Premium : Aesthetic Minimal
 * Design : Ultra épuré, typographie fine, beaucoup d'espace blanc
 */
$name     = htmlspecialchars($profile['full_name'] ?? 'Nom');
$title    = htmlspecialchars($profile['title'] ?? 'Professionnel');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
$summary  = $profile['summary'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400&display=swap" rel="stylesheet">
<style>
:root { --text: #1a1a1a; --light: #888; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Inter', sans-serif; background: #eee; color: var(--text); font-size: 10px; font-weight: 400; }
.cv-page { width: 21cm; min-height: 29.7cm; background: #fff; margin: 0 auto; padding: 8cm 3cm 3cm 3cm; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.header { position: absolute; top: 3cm; left: 3cm; right: 3cm; }
.name { font-size: 40px; font-weight: 200; letter-spacing: -2px; margin-bottom: 5px; }
.title { font-size: 14px; font-weight: 300; color: var(--light); letter-spacing: 2px; text-transform: lowercase; }
.contact { position: absolute; top: 3.5cm; right: 3cm; text-align: right; color: var(--light); line-height: 1.8; }
.section { margin-bottom: 40px; display: flex; }
.sec-title { width: 120px; flex-shrink: 0; font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: var(--light); padding-top: 3px; }
.sec-content { flex: 1; border-top: 1px solid #f0f0f0; padding-top: 20px; }
.item { margin-bottom: 30px; }
.item-title { font-size: 14px; font-weight: 400; margin-bottom: 2px; }
.item-meta { font-size: 10px; color: var(--light); margin-bottom: 15px; }
.item-desc { line-height: 1.8; color: #444; }
.skills-list { display: flex; flex-wrap: wrap; gap: 10px; }
.skills-list span { border: 1px solid #e0e0e0; padding: 5px 15px; border-radius: 40px; }
</style>
</head>
<body>
<div class="cv-page">
    <div class="header">
        <div class="name"><?= strtolower($name) ?>.</div>
        <div class="title"><?= $title ?></div>
    </div>
    <div class="contact">
        <?php if($email) echo "<div>$email</div>"; ?>
        <?php if($phone) echo "<div>$phone</div>"; ?>
        <?php if($location) echo "<div>$location</div>"; ?>
    </div>

    <?php if ($summary): ?>
    <div class="section">
        <div class="sec-title">profil</div>
        <div class="sec-content" style="font-size: 12px; line-height: 2; color: #444;">
            <?= strip_tags($summary) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($experience): ?>
    <div class="section">
        <div class="sec-title">expérience</div>
        <div class="sec-content">
            <?php foreach ($experience as $job): ?>
            <div class="item">
                <div class="item-title"><?= htmlspecialchars($job['position']) ?></div>
                <div class="item-meta"><?= htmlspecialchars($job['company']) ?> &nbsp;&middot;&nbsp; <?= $job['start_date'] ?> — <?= $job['end_date'] ?: 'présent' ?></div>
                <div class="item-desc"><?= nl2br(htmlspecialchars(strip_tags($job['description']))) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($education): ?>
    <div class="section">
        <div class="sec-title">formation</div>
        <div class="sec-content">
            <?php foreach ($education as $edu): ?>
            <div class="item" style="margin-bottom:15px">
                <div class="item-title"><?= htmlspecialchars($edu['degree']) ?></div>
                <div class="item-meta"><?= htmlspecialchars($edu['school']) ?> &nbsp;&middot;&nbsp; <?= $edu['end_year'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($skills): ?>
    <div class="section" style="margin-bottom:0;">
        <div class="sec-title">compétences</div>
        <div class="sec-content skills-list">
            <?php foreach ($skills as $s): ?>
                <span><?= strtolower(htmlspecialchars($s['skill_name'])) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
