<?php
/**
 * Modèle Premium : Minimal Notion
 * Design : Ultra-propre, noir et blanc strict, rappelle l'interface de Notion
 */
$pageTitle = htmlspecialchars($profile['full_name']) . " - Notion Style";
$photo = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root { --bg: #ffffff; --text: #37352f; --dim: #787774; --border: #e9e9e7; --cover: #f7f6f3; }
body { margin: 0; padding: 0; background: var(--bg); color: var(--text); font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif; line-height: 1.5; font-size: 16px; }
.cover { height: 30vh; background: var(--cover); border-bottom: 1px solid var(--border); }
.container { max-width: 900px; margin: 0 auto; padding: 0 96px 10vh 96px; margin-top: -60px; position: relative; }
.icon { font-size: 78px; margin-bottom: 10px; display: inline-block; }
.photo { width: 120px; height: 120px; border-radius: 10px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: 2px solid #fff; }
.title { font-size: 40px; font-weight: 700; margin: 0 0 10px 0; letter-spacing: -0.03em; }
.tag { font-size: 14px; background: rgba(227, 226, 224, 0.5); padding: 4px 8px; border-radius: 4px; color: var(--text); font-weight: 500; display: inline-block; margin-bottom: 20px; }
.section-title { font-size: 24px; font-weight: 600; margin: 40px 0 15px 0; padding-bottom: 5px; border-bottom: 1px solid var(--border); letter-spacing: -0.01em; }
.text-block { color: var(--text); margin-bottom: 24px; white-space: pre-wrap; }
.db-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
.db-table th { background: rgba(247, 246, 243, 0.5); font-weight: 500; color: var(--dim); text-align: left; padding: 10px; border-bottom: 1px solid var(--border); }
.db-table td { padding: 12px 10px; border-bottom: 1px solid var(--border); vertical-align: top; }
.db-name { font-weight: 500; display: flex; align-items: center; gap: 8px; }
.db-name::before { content: '📄'; }
.db-desc { color: var(--dim); font-size: 13px; }
.callout { background: rgba(241, 241, 239, 1); border-radius: 4px; padding: 16px; display: flex; gap: 12px; margin-top: 40px; }
.callout a { border-bottom: 1px solid rgba(55,53,47,0.4); color: inherit; text-decoration: none; font-weight: 500; transition: border-color 0.1s; display: inline-block; }
.callout a:hover { border-bottom-color: var(--text); }
@media(max-width: 768px) { .container { padding: 0 24px 10vh 24px; margin-top: -40px; } .icon, .photo { width: 80px; height: 80px;} .title { font-size: 32px; } }
</style>
</head>
<body>
    <div class="cover"></div>
    <div class="container">
        <?php if($photo): ?>
            <img src="<?= $photo ?>" class="photo" alt="<?= htmlspecialchars($profile['full_name']) ?>">
        <?php else: ?>
            <div class="icon">💼</div>
        <?php endif; ?>
        
        <h1 class="title"><?= htmlspecialchars($profile['full_name']) ?></h1>
        <div class="tag"><?= htmlspecialchars($profile['title'] ?? 'Workspace') ?></div>

        <?php if($profile['summary']): ?>
        <div class="section-title">À Propos</div>
        <div class="text-block"><?= strip_tags($profile['summary']) ?></div>
        <?php endif; ?>

        <?php if($projects): ?>
        <div class="section-title">Base de données Projets</div>
        <table class="db-table">
            <thead>
                <tr>
                    <th style="width: 30%">Nom</th>
                    <th style="width: 50%">Description</th>
                    <th style="width: 20%">Lien</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td class="db-name"><?= htmlspecialchars($project['title']) ?></td>
                    <td class="db-desc"><?= nl2br(htmlspecialchars($project['description'])) ?></td>
                    <td>
                        <?php if($project['link_url']): ?>
                        <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" style="color:var(--dim); text-decoration:none;">Ouvrir ↗</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <?php if($profile['email'] || $profile['phone']): ?>
        <div class="callout">
            <div style="font-size:20px">✉️</div>
            <div>
                <strong>Me contacter</strong><br>
                <?php if($profile['email']): ?><a href="mailto:<?= htmlspecialchars($profile['email']) ?>"><?= htmlspecialchars($profile['email']) ?></a><br><?php endif; ?>
                <?php if($profile['phone']): ?><span style="color:var(--dim)"><?= htmlspecialchars($profile['phone']) ?></span><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
