<?php
/**
 * Modèle Premium : Neon Cyber
 * Design : Thème sombre, accents néon cyan et rose, bordures lumineuses
 */
$name     = htmlspecialchars($profile['full_name'] ?? 'Nom');
$title    = htmlspecialchars($profile['title'] ?? 'Cyber Expert');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$summary  = $profile['summary'] ?? '';
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg-dark: #0a0a0c;
    --neon-cyan: #0ff;
    --neon-pink: #f0f;
    --text-main: #e2e8f0;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Rajdhani', sans-serif; background: #222; color: var(--text-main); font-size: 14px; }
.cv-page { width: 21cm; min-height: 29.7cm; background: var(--bg-dark); margin: 0 auto; padding: 40px; position: relative; box-shadow: 0 0 50px rgba(0,255,255,0.1); border: 1px solid #111; overflow: hidden; }
.cv-page::before { content: ''; position: absolute; top:0; left:0; right:0; height: 5px; background: linear-gradient(90deg, var(--neon-cyan), var(--neon-pink)); }
.glitch-wrapper { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(0,255,255,0.2); padding-bottom: 30px; margin-bottom: 30px; }
.name { font-size: 48px; font-weight: 700; color: #fff; text-shadow: 0 0 10px var(--neon-cyan); text-transform: uppercase; letter-spacing: 2px; }
.title { font-size: 20px; color: var(--neon-pink); text-shadow: 0 0 5px var(--neon-pink); text-transform: uppercase; letter-spacing: 5px; }
.contact { text-align: right; border-right: 2px solid var(--neon-cyan); padding-right: 15px; }
.contact div { margin-bottom: 5px; letter-spacing: 1px; }
.grid { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }
.section-title { font-size: 22px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 20px; display: inline-block; border-bottom: 2px solid var(--neon-pink); padding-bottom: 5px; text-shadow: 0 0 8px rgba(255,0,255,0.5); }
.box { background: rgba(255,255,255,0.02); border: 1px solid rgba(0,255,255,0.1); padding: 20px; border-radius: 4px; margin-bottom: 20px; position: relative; }
.box::before { content: ''; position: absolute; top:-1px; left:-1px; width: 10px; height: 10px; border-top: 2px solid var(--neon-cyan); border-left: 2px solid var(--neon-cyan); }
.box::after { content: ''; position: absolute; bottom:-1px; right:-1px; width: 10px; height: 10px; border-bottom: 2px solid var(--neon-pink); border-right: 2px solid var(--neon-pink); }
.item-title { font-size: 18px; font-weight: 700; color: var(--neon-cyan); margin-bottom: 2px; }
.item-meta { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
.item-desc { color: #ccc; line-height: 1.6; }
.skill { display: inline-block; border: 1px solid var(--neon-cyan); color: var(--neon-cyan); padding: 4px 12px; margin: 0 5px 5px 0; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 0 5px rgba(0,255,255,0.2) inset; }
</style>
</head>
<body>
<div class="cv-page">
    <div class="glitch-wrapper">
        <div>
            <div class="name"><?= $name ?></div>
            <div class="title">SYS.<?= $title ?></div>
        </div>
        <div class="contact">
            <?php if($email) echo "<div>$email</div>"; ?>
            <?php if($phone) echo "<div>$phone</div>"; ?>
        </div>
    </div>

    <div class="grid">
        <div>
            <?php if ($summary): ?>
            <div class="section-title">PROFILE.DAT</div>
            <div class="box"><div class="item-desc"><?= strip_tags($summary) ?></div></div>
            <?php endif; ?>

            <?php if ($experience): ?>
            <div class="section-title">EXPERIENCE.EXE</div>
            <?php foreach ($experience as $job): ?>
            <div class="box">
                <div class="item-title"><?= htmlspecialchars($job['position']) ?></div>
                <div class="item-meta"><?= htmlspecialchars($job['company']) ?> | [<?= $job['start_date'] ?> : <?= $job['end_date'] ?: 'NULL' ?>]</div>
                <div class="item-desc"><?= nl2br(htmlspecialchars(strip_tags($job['description']))) ?></div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div>
            <?php if ($skills): ?>
            <div class="section-title">SKILLS.LOG</div>
            <div class="box" style="padding-top:25px;">
                <?php foreach ($skills as $s): ?>
                <div class="skill"><?= htmlspecialchars($s['skill_name']) ?></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($education): ?>
            <div class="section-title">EDUCATION.DB</div>
            <?php foreach ($education as $edu): ?>
            <div class="box" style="border-color: rgba(255,0,255,0.2);">
                <div class="item-title" style="color:var(--neon-pink);font-size:16px;"><?= htmlspecialchars($edu['degree']) ?></div>
                <div class="item-meta"><?= htmlspecialchars($edu['school']) ?> | [<?= $edu['end_year'] ?>]</div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
