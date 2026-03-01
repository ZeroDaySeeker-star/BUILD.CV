<?php
/**
 * Modèle Premium : Creative Glass
 * Design : Glassmorphism, fond dégradé vibrant
 */
$name     = htmlspecialchars($profile['full_name'] ?? 'Votre Nom');
$title    = htmlspecialchars($profile['title'] ?? 'Directeur Créatif');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
$summary  = $profile['summary'] ?? '';
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
:root {
    --bg-grad: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
    --glass-bg: rgba(255, 255, 255, 0.45);
    --glass-border: rgba(255, 255, 255, 0.6);
    --text-main: #2d3748;
    --accent: #ed64a6;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Outfit', sans-serif; background: #ddd; color: var(--text-main); font-size: 13px; }
.cv-page { width: 21cm; min-height: 29.7cm; background: var(--bg-grad); margin: 0 auto; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
.glass-panel { background: var(--glass-bg); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid var(--glass-border); border-radius: 24px; padding: 40px; margin-bottom: 30px; box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1); }
.header { display: flex; align-items: center; gap: 30px; }
.photo { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
.name { font-size: 40px; font-weight: 800; letter-spacing: -1px; margin-bottom: 5px; background: linear-gradient(90deg, #ed64a6, #9f7aea); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.title { font-size: 16px; font-weight: 600; color: #4a5568; margin-bottom: 15px; }
.contact { display: flex; gap: 20px; font-size: 12px; font-weight: 600; flex-wrap: wrap; }
.contact span { background: rgba(255,255,255,0.6); padding: 5px 12px; border-radius: 20px; }
.grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
.section-title { font-size: 20px; font-weight: 800; margin-bottom: 20px; position: relative; padding-bottom: 10px; }
.section-title::after { content: ''; position: absolute; left: 0; bottom: 0; width: 40px; height: 4px; background: var(--accent); border-radius: 2px; }
.exp-item { margin-bottom: 25px; padding: 20px; background: rgba(255,255,255,0.3); border-radius: 16px; }
.exp-role { font-weight: 800; font-size: 15px; color: var(--accent); }
.exp-meta { font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 10px; }
.pill { display: inline-block; background: #fff; padding: 6px 12px; border-radius: 12px; font-size: 11px; font-weight: 600; margin: 0 5px 5px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
.summary { font-size: 14px; line-height: 1.6; }
</style>
</head>
<body>
<div class="cv-page">
    <div class="glass-panel header">
        <?php if($photo): ?><img src="<?= $photo ?>" class="photo"><?php endif; ?>
        <div>
            <div class="name"><?= $name ?></div>
            <div class="title"><?= $title ?></div>
            <div class="contact">
                <?php if($phone) echo "<span>📞 $phone</span>"; ?>
                <?php if($email) echo "<span>📧 $email</span>"; ?>
                <?php if($location) echo "<span>📍 $location</span>"; ?>
            </div>
        </div>
    </div>
    
    <div class="grid">
        <div class="main-col">
            <?php if($summary): ?>
            <div class="glass-panel">
                <div class="section-title">À Propos</div>
                <div class="summary"><?= strip_tags($summary) ?></div>
            </div>
            <?php endif; ?>

            <?php if($experience): ?>
            <div class="glass-panel">
                <div class="section-title">Expérience</div>
                <?php foreach($experience as $job): ?>
                <div class="exp-item">
                    <div class="exp-role"><?= htmlspecialchars($job['position']) ?></div>
                    <div class="exp-meta"><?= htmlspecialchars($job['company']) ?> | <?= $job['start_date'] ?> - <?= $job['end_date'] ?: 'Présent' ?></div>
                    <div style="font-size: 13px;"><?= nl2br(htmlspecialchars(strip_tags($job['description']))) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="side-col">
            <?php if($skills): ?>
            <div class="glass-panel">
                <div class="section-title">Compétences</div>
                <div>
                <?php foreach($skills as $s): ?>
                    <span class="pill"><?= htmlspecialchars($s['skill_name']) ?></span>
                <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if($education): ?>
            <div class="glass-panel">
                <div class="section-title">Formation</div>
                <?php foreach($education as $edu): ?>
                <div style="margin-bottom:15px">
                    <div style="font-weight:800;font-size:13px"><?= htmlspecialchars($edu['degree']) ?></div>
                    <div style="font-size:11px;color:#4a5568"><?= htmlspecialchars($edu['school']) ?> (<?= $edu['end_year'] ?>)</div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
