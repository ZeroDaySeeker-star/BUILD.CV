<?php
/**
 * Modèle Premium : Marketing Pro
 * Design : Couleurs vives, formes géométriques, asymétrie
 */
$name     = htmlspecialchars($profile['full_name'] ?? 'Nom Prénom');
$title    = htmlspecialchars($profile['title'] ?? 'Marketing Pro');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$summary  = $profile['summary'] ?? '';
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;800&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #f0f4f8;
    --accent-1: #ff6b6b; /* Coral */
    --accent-2: #fca311; /* Yellow */
    --accent-3: #4ecdc4; /* Mint */
    --text-dark: #2b2d42;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Poppins', sans-serif; background: #ddd; color: var(--text-dark); font-size: 12px; }
.cv-page { width: 21cm; min-height: 29.7cm; background: var(--primary); margin: 0 auto; overflow: hidden; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
.shape-1 { position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: var(--accent-2); border-radius: 50%; opacity: 0.8; z-index: 1; }
.shape-2 { position: absolute; bottom: 100px; left: -150px; width: 300px; height: 600px; background: var(--accent-3); transform: rotate(45deg); opacity: 0.15; z-index: 1; }
.header { padding: 60px 50px 30px; position: relative; z-index: 2; display: flex; align-items: flex-end; justify-content: space-between; }
.name { font-size: 48px; font-weight: 800; line-height: 1; text-transform: uppercase; color: var(--accent-1); margin-bottom: 10px; }
.title { font-size: 18px; font-weight: 500; letter-spacing: 2px; text-transform: uppercase; }
.contact-pill { background: #fff; padding: 10px 25px; border-radius: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: inline-flex; gap: 20px; font-weight: 500; font-size: 11px; margin-left: 50px; position: relative; z-index: 2; }
.content { padding: 50px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; position: relative; z-index: 2; }
.section-title { font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px; display: flex; align-items: center; gap: 15px; }
.section-title::before { content: ''; display: block; width: 15px; height: 15px; background: var(--accent-1); border-radius: 50%; }
.item { margin-bottom: 30px; background: #fff; padding: 25px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border-left: 5px solid var(--accent-3); }
.item.edu { border-left-color: var(--accent-2); }
.item-title { font-weight: 800; font-size: 15px; margin-bottom: 5px; }
.item-meta { font-weight: 500; color: #8d99ae; font-size: 11px; margin-bottom: 10px; text-transform: uppercase; }
.item-desc { color: #5c677d; line-height: 1.6; }
.skills { display: flex; flex-wrap: wrap; gap: 10px; }
.skill-tag { background: var(--text-dark); color: #fff; padding: 8px 15px; border-radius: 8px; font-weight: 500; font-size: 11px; }
</style>
</head>
<body>
<div class="cv-page">
    <div class="shape-1"></div>
    <div class="shape-2"></div>
    
    <div class="header">
        <div>
            <div class="name"><?= $name ?></div>
            <div class="title"><?= $title ?></div>
        </div>
        <?php if($photo): ?><img src="<?= $photo ?>" style="width:140px;height:140px;border-radius:50%;object-fit:cover;border:5px solid #fff;box-shadow:0 10px 20px rgba(0,0,0,0.1);"><?php endif; ?>
    </div>
    
    <div class="contact-pill">
        <?php if($phone) echo "<span>$phone</span>"; ?>
        <?php if($email) echo "<span>$email</span>"; ?>
    </div>

    <div class="content">
        <div>
            <?php if ($summary): ?>
            <div class="section-title" style="margin-top:20px;">À Propos</div>
            <div style="font-size:13px; line-height:1.7; margin-bottom:40px; color:#5c677d; font-weight:500;">
                <?= strip_tags($summary) ?>
            </div>
            <?php endif; ?>

            <?php if ($experience): ?>
            <div class="section-title">Expériences</div>
            <?php foreach ($experience as $job): ?>
            <div class="item">
                <div class="item-title"><?= htmlspecialchars($job['position']) ?></div>
                <div class="item-meta"><?= htmlspecialchars($job['company']) ?> | <?= $job['start_date'] ?></div>
                <div class="item-desc"><?= nl2br(htmlspecialchars(strip_tags($job['description']))) ?></div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div>
            <?php if ($skills): ?>
            <div class="section-title" style="margin-top:20px;">Compétences</div>
            <div class="skills" style="margin-bottom:40px;">
                <?php foreach ($skills as $s): ?>
                <div class="skill-tag"><?= htmlspecialchars($s['skill_name']) ?></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($education): ?>
            <div class="section-title">Formation</div>
            <?php foreach ($education as $edu): ?>
            <div class="item edu">
                <div class="item-title"><?= htmlspecialchars($edu['degree']) ?></div>
                <div class="item-meta"><?= htmlspecialchars($edu['school']) ?> | <?= $edu['end_year'] ?></div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
