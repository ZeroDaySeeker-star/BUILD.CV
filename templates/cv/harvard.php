<?php
/**
 * Modèle Premium : Harvard Academic
 * Design : Ultra classique, Times New Roman, marges larges
 */
$name     = htmlspecialchars($profile['full_name'] ?? 'Your Name');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
:root {
    --text: #000000;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: "Times New Roman", Times, serif; background: #ddd; color: var(--text); font-size: 11pt; line-height: 1.3; }
.cv-page { width: 21cm; min-height: 29.7cm; background: #fff; margin: 0 auto; padding: 2.5cm; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
.header { text-align: center; margin-bottom: 24pt; }
.name { font-size: 24pt; font-weight: bold; text-transform: uppercase; margin-bottom: 6pt; letter-spacing: 1px; }
.contact { font-size: 11pt; }
.contact span { margin: 0 5pt; }
.section { margin-bottom: 18pt; }
.section-title { font-size: 11pt; font-weight: bold; text-transform: uppercase; border-bottom: 1pt solid #000; padding-bottom: 2pt; margin-bottom: 10pt; }
.item { margin-bottom: 12pt; }
.item-header { display: flex; justify-content: space-between; font-weight: bold; }
.item-sub { display: flex; justify-content: space-between; font-style: italic; margin-bottom: 4pt; }
.item-desc { text-align: justify; }
.item-desc ul { padding-left: 20pt; }
.item-desc li { margin-bottom: 3pt; }
.skills-list { display: flex; flex-wrap: wrap; gap: 10pt; }
.skills-list li { list-style: none; }
</style>
</head>
<body>
<div class="cv-page">
    <div class="header">
        <div class="name"><?= $name ?></div>
        <div class="contact">
            <?php if($location) echo "<span>$location</span> |"; ?>
            <?php if($phone) echo "<span>$phone</span> |"; ?>
            <?php if($email) echo "<span>$email</span>"; ?>
        </div>
    </div>

    <?php if ($education): ?>
    <div class="section">
        <div class="section-title">Education</div>
        <?php foreach ($education as $edu): ?>
        <div class="item">
            <div class="item-header">
                <span><?= htmlspecialchars($edu['school']) ?></span>
                <span><?= $edu['end_year'] ?></span>
            </div>
            <div class="item-sub">
                <span><?= htmlspecialchars($edu['degree']) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($experience): ?>
    <div class="section">
        <div class="section-title">Experience</div>
        <?php foreach ($experience as $job): ?>
        <div class="item">
            <div class="item-header">
                <span><?= htmlspecialchars($job['company']) ?></span>
                <span><?= $job['start_date'] ?> – <?= $job['end_date'] ?: 'Present' ?></span>
            </div>
            <div class="item-sub">
                <span><?= htmlspecialchars($job['position']) ?></span>
            </div>
            <div class="item-desc">
                <?= nl2br(htmlspecialchars(strip_tags($job['description']))) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($skills): ?>
    <div class="section">
        <div class="section-title">Skills & Interests</div>
        <div class="item">
            <strong>Technical:</strong> 
            <?php 
                $sList = array_map(function($s){ return htmlspecialchars($s['skill_name']); }, $skills);
                echo implode(', ', $sList);
            ?>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
