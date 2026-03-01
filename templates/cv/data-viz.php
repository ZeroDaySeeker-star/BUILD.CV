<?php
/**
 * Modèle Premium : Data Viz
 * Design : Grilles strictes, style dashboard/infographie
 */
$name     = htmlspecialchars($profile['full_name'] ?? 'Nom');
$title    = htmlspecialchars($profile['title'] ?? 'Data Analyst');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
$summary  = $profile['summary'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;600&display=swap" rel="stylesheet">
<style>
:root { --border: #e2e8f0; --text: #1e293b; --accent: #3b82f6; --bg: #f8fafc; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'IBM Plex Sans', sans-serif; background: #ddd; color: var(--text); font-size: 11px; }
.cv-page { width: 21cm; min-height: 29.7cm; background: #fff; margin: 0 auto; display: grid; grid-template-columns: 1fr 2fr; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
.sidebar { background: var(--bg); border-right: 1px solid var(--border); padding: 40px 30px; }
.main { padding: 40px; display: flex; flex-direction: column; gap: 30px; }
.header { border-bottom: 2px solid var(--accent); padding-bottom: 20px; margin-bottom: 20px; }
.name { font-size: 32px; font-weight: 600; text-transform: uppercase; letter-spacing: -1px; margin-bottom: 5px; }
.title { font-size: 14px; color: var(--accent); font-weight: 600; text-transform: uppercase; }
.box { border: 1px solid var(--border); background: #fff; padding: 20px; border-radius: 4px; }
.box-title { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 5px; }
.contact-item { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 10px; border-bottom: 1px dashed var(--border); padding-bottom: 4px; }
.contact-label { font-weight: 600; color: #64748b; }
.skill-bar { margin-bottom: 15px; }
.skill-name { display: flex; justify-content: space-between; margin-bottom: 4px; font-weight: 600; }
.bar-bg { height: 4px; background: var(--border); border-radius: 2px; overflow: hidden; }
.bar-fill { height: 100%; background: var(--accent); }
.exp-grid { display: grid; gap: 20px; }
.exp-item { border-left: 2px solid var(--accent); padding-left: 15px; }
.exp-role { font-weight: 600; font-size: 14px; color: var(--text); margin-bottom: 2px; }
.exp-meta { font-size: 10px; color: #64748b; margin-bottom: 8px; }
</style>
</head>
<body>
<div class="cv-page">
    <div class="sidebar">
        <div class="header">
            <div class="name"><?= $name ?></div>
            <div class="title"><?= $title ?></div>
        </div>
        
        <div class="box" style="margin-bottom:20px">
            <div class="box-title">Data Contact</div>
            <?php if($email): ?>
            <div class="contact-item"><span class="contact-label">EMAIL</span><span><?= $email ?></span></div>
            <?php endif; ?>
            <?php if($phone): ?>
            <div class="contact-item"><span class="contact-label">PHONE</span><span><?= $phone ?></span></div>
            <?php endif; ?>
            <?php if($location): ?>
            <div class="contact-item"><span class="contact-label">LOC</span><span><?= $location ?></span></div>
            <?php endif; ?>
        </div>

        <?php if($skills): ?>
        <div class="box">
            <div class="box-title">Metrics Compétences</div>
            <?php foreach($skills as $i => $s): $val = 100 - ($i * 10); if($val < 40) $val = 40; ?>
            <div class="skill-bar">
                <div class="skill-name"><span><?= htmlspecialchars($s['skill_name']) ?></span><span><?= $val ?>%</span></div>
                <div class="bar-bg"><div class="bar-fill" style="width:<?= $val ?>%"></div></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="main">
        <?php if($summary): ?>
        <div class="box">
            <div class="box-title">Executive Summary</div>
            <div style="line-height:1.6; text-align:justify;"><?= strip_tags($summary) ?></div>
        </div>
        <?php endif; ?>

        <?php if($experience): ?>
        <div class="box">
            <div class="box-title">Timeline Expériences</div>
            <div class="exp-grid">
                <?php foreach($experience as $job): ?>
                <div class="exp-item">
                    <div class="exp-role"><?= htmlspecialchars($job['position']) ?></div>
                    <div class="exp-meta"><?= htmlspecialchars($job['company']) ?> // <?= $job['start_date'] ?> - <?= $job['end_date'] ?: 'Present' ?></div>
                    <div style="color:#475569; line-height:1.5;"><?= nl2br(htmlspecialchars(strip_tags($job['description']))) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($education): ?>
        <div class="box">
            <div class="box-title">Dataset Formation</div>
            <div class="exp-grid">
                <?php foreach($education as $edu): ?>
                <div class="exp-item" style="border-left-color: #94a3b8;">
                    <div class="exp-role"><?= htmlspecialchars($edu['degree']) ?></div>
                    <div class="exp-meta"><?= htmlspecialchars($edu['school']) ?> // <?= $edu['end_year'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
