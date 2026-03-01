<?php
/**
 * Modèle Premium : Terminal Hacker
 * Design : Effet console de commande, texte vert sur fond noir, minimalisme brut
 */
$pageTitle = htmlspecialchars($profile['full_name'] ?? 'Portfolio') . " - ~/";
$pEmail = htmlspecialchars($profile['email'] ?? '');
$pName = htmlspecialchars($profile['full_name'] ?? 'user');
$pTitle = htmlspecialchars($profile['title'] ?? 'Root Administrator');
$pSummary = strip_tags($profile['summary'] ?? 'No data found.');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
:root { --bg: #000; --text: #0f0; --dim: #050; }
body { margin: 0; padding: 2rem; background: var(--bg); color: var(--text); font-family: 'JetBrains Mono', monospace; font-size: 14px; line-height: 1.6; }
::selection { background: var(--text); color: var(--bg); }
.cmd-prefix { color: #88ff88; font-weight: bold; }
.cmd-path { color: #5555ff; }
.blinking-cursor { display: inline-block; width: 10px; height: 1.2em; background: var(--text); vertical-align: bottom; animation: blink 1s step-end infinite; }
@keyframes blink { 0%, 100% {opacity:1;} 50% {opacity:0;} }
.container { max-width: 800px; margin: 0 auto; }
.block { margin-bottom: 2rem; }
.proj-list { list-style: none; padding: 0; margin: 0; }
.proj-item { margin-bottom: 1.5rem; border-left: 2px solid var(--dim); padding-left: 1rem; }
.proj-item h3 { margin: 0; font-size: 1rem; color: #fff; }
.proj-item h3::before { content: './'; color: var(--dim); }
.proj-desc { color: #888; }
a { color: #5555ff; text-decoration: none; }
a:hover { text-decoration: underline; background: #5555ff; color: #000; }
.ascii-art { white-space: pre; font-size: 10px; color: var(--dim); margin-bottom: 2rem; }
</style>
</head>
<body>
    <div class="container">
        <div class="ascii-art">
  ___ _   _ ___ _    ___     _____   __
 | _ ) | | |_ _| |  |   \   / __\ \ / /
 | _ \ |_| || || |__| |) | | (__ \ \ / 
 |___/\___/|___|____|___/   \___| \_/  
        </div>

        <div class="block">
            <span class="cmd-prefix">visitor@internet</span>:<span class="cmd-path">~</span>$ whoami<br>
            <?= $pName ?><br>
            <?= $pTitle ?>
        </div>

        <div class="block">
            <span class="cmd-prefix">visitor@internet</span>:<span class="cmd-path">~</span>$ cat intro.txt<br>
            <?= nl2br(htmlspecialchars($pSummary)) ?>
        </div>

        <div class="block">
            <span class="cmd-prefix">visitor@internet</span>:<span class="cmd-path">~/projects</span>$ ls -la<br>
            <ul class="proj-list">
                <?php foreach ($projects as $project): ?>
                <li class="proj-item">
                    <h3><?= htmlspecialchars($project['title']) ?></h3>
                    <div class="proj-desc"><?= nl2br(htmlspecialchars($project['description'])) ?></div>
                    <?php if(!empty($project['link_url'])): ?>
                    <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank">[Execute Link]</a>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if($pEmail): ?>
        <div class="block">
            <span class="cmd-prefix">visitor@internet</span>:<span class="cmd-path">~</span>$ mail -s "Contact" <?= $pEmail ?><br>
            <a href="mailto:<?= $pEmail ?>">[Send Message]</a>
        </div>
        <?php endif; ?>

        <div class="block">
            <span class="cmd-prefix">visitor@internet</span>:<span class="cmd-path">~</span>$ <span class="blinking-cursor"></span>
        </div>
    </div>
</body>
</html>
