<?php
// Modèle de portfolio : Cyber – Futuriste, Néo-Punk, Data-Core
$name     = htmlspecialchars($profile['full_name'] ?? 'USER_CORE');
$title    = htmlspecialchars($profile['title'] ?? 'Techno-Wizard');
$email    = htmlspecialchars($profile['email'] ?? '010101@cyber.net');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SYS_LOG | <?= $name ?></title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Syncopate:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --bg: #0a0a0c;
        --neon-blue: #00f3ff;
        --neon-pink: #ff00ff;
        --neon-green: #39ff14;
        --text: #e0e0e0;
        --surface: #121217;
        --font-mono: 'Space Mono', monospace;
        --font-heading: 'Syncopate', sans-serif;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: var(--font-mono); background: var(--bg); color: var(--text); 
        line-height: 1.5; overflow-x: hidden;
    }

    /* SCANLINES */
    body::before {
        content: " "; position: fixed; top: 0; left: 0; bottom: 0; right: 0;
        background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
        z-index: 2000; background-size: 100% 2px, 3px 100%; pointer-events: none;
    }

    /* GLITCH EFFECT */
    .glitch { position: relative; display: inline-block; color: white; }
    .glitch::before, .glitch::after {
        content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    }
    .glitch::before { left: 2px; text-shadow: -2px 0 var(--neon-blue); clip: rect(44px, 450px, 56px, 0); animation: glitch-anim 5s infinite linear alternate-reverse; }
    .glitch::after { left: -2px; text-shadow: -2px 0 var(--neon-pink); clip: rect(24px, 450px, 90px, 0); animation: glitch-anim2 1s infinite linear alternate-reverse; }

    @keyframes glitch-anim { 0% { clip: rect(31px, 9999px, 94px, 0); } 20% { clip: rect(62px, 9999px, 42px, 0); } 40% { clip: rect(10px, 9999px, 13px, 0); } 100% { clip: rect(67px, 9999px, 51px, 0); } }
    @keyframes glitch-anim2 { 0% { clip: rect(65px, 9999px, 100px, 0); } 100% { clip: rect(12px, 9999px, 11px, 0); } }

    /* LAYOUT */
    .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 10; }

    /* HEADER */
    header { padding: 4rem 0; border-bottom: 1px solid var(--neon-blue); box-shadow: 0 10px 30px rgba(0,243,255,0.1); }
    .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; font-size: 0.8rem; color: var(--neon-blue); }
    .header-main h1 { font-family: var(--font-heading); font-size: 5rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: -5px; }
    .header-main p { font-size: 1.2rem; color: var(--neon-pink); text-transform: uppercase; letter-spacing: 5px; }

    /* SECTIONS */
    section { padding: 6rem 0; border-bottom: 1px dashed rgba(0,243,255,0.2); }
    .section-title { font-family: var(--font-heading); font-size: 1.2rem; color: var(--neon-blue); margin-bottom: 3rem; display: flex; align-items: center; gap: 1rem; }
    .section-title::before { content: ">"; }
    .section-title::after { content: ""; flex: 1; height: 1px; background: var(--neon-blue); }

    /* DATA GRID */
    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; }
    .data-card {
        background: var(--surface); border: 1px solid rgba(0,243,255,0.3); padding: 2rem;
        position: relative; transition: 0.3s;
    }
    .data-card:hover { border-color: var(--neon-pink); box-shadow: 0 0 20px rgba(255,0,255,0.2); transform: translateY(-5px); }
    .data-card::before { content: ""; position: absolute; top: -5px; right: -5px; width: 10px; height: 10px; background: var(--neon-blue); }
    .data-card h3 { color: var(--neon-pink); margin-bottom: 1rem; font-size: 1.2rem; text-transform: uppercase; }
    .data-card p { font-size: 0.85rem; color: var(--text-muted); }

    /* SKILLS */
    .skills-hex { display: flex; flex-wrap: wrap; gap: 1rem; }
    .hex { 
        padding: 0.5rem 1.5rem; border: 1px solid var(--neon-green); color: var(--neon-green); 
        font-size: 0.8rem; text-transform: uppercase; clip-path: polygon(10% 0, 90% 0, 100% 50%, 90% 100%, 10% 100%, 0% 50%);
        background: rgba(57, 255, 20, 0.05); cursor: crosshair;
    }
    .hex:hover { background: var(--neon-green); color: var(--bg); }

    /* FOOTER */
    footer { padding: 4rem 0; text-align: center; }
    .terminal-input { background: none; border: none; font-family: var(--font-mono); color: var(--neon-blue); font-size: 1.2rem; width: 300px; text-align: center; }
    .terminal-input:focus { outline: none; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .header-main h1 { font-size: 3rem; letter-spacing: -2px; }
        .grid { grid-template-columns: 1fr; }
    }
</style>
</head>
<body>

<div class="container">
    <header>
        <div class="header-top">
            <div>STATUS: ONLINE</div>
            <div>CORE_V.2.0.6</div>
            <div>LOC: <?= strtoupper(htmlspecialchars($profile['location'] ?? 'REMOTE')) ?></div>
        </div>
        <div class="header-main">
            <h1 class="glitch" data-text="<?= $name ?>"><?= $name ?></h1>
            <p><?= $title ?></p>
        </div>
    </header>

    <section id="about">
        <h2 class="section-title">MISSION_OBJECTIVE</h2>
        <div style="font-size: 1rem; line-height: 2; color: var(--neon-blue);">
            <?= $profile['summary'] ?? 'SYSTEM_SUMMARY_NOT_FOUND' ?>
        </div>
    </section>

    <?php if (!empty($projects)): ?>
    <section id="projects">
        <h2 class="section-title">DEPLOYED_MODULES</h2>
        <div class="grid">
            <?php foreach ($projects as $p): ?>
            <div class="data-card">
                <h3><?= htmlspecialchars($p['title'] ?? $p['project_name'] ?? 'MODULE_X') ?></h3>
                <p><?= strip_tags($p['description'] ?? '', '<b><i><strong><em>') ?></p>
                <div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <?php 
                    $techs = explode(',', $p['technologies'] ?? '');
                    foreach ($techs as $t): if (trim($t)): ?>
                        <span style="font-size: 0.6rem; color: var(--neon-green); border: 1px solid var(--neon-green); padding: 2px 5px;">[<?= htmlspecialchars(trim($t)) ?>]</span>
                    <?php endif; endforeach; ?>
                </div>
                <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                    <?php if (!empty($p['project_url'])): ?>
                        <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" style="font-size: 0.7rem; color: var(--neon-blue); text-decoration: none;">// VIEW_LIVE</a>
                    <?php endif; ?>
                    <?php if (!empty($p['github_url'])): ?>
                        <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" style="font-size: 0.7rem; color: var(--neon-pink); text-decoration: none;">// GITHUB</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section id="skills">
        <h2 class="section-title">CORE_CAPABILITIES</h2>
        <div class="skills-hex">
            <?php if (!empty($skills)): ?>
                <?php foreach ($skills as $s): ?>
                    <div class="hex"><?= htmlspecialchars($s['skill_name'] ?? '') ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <footer id="contact">
        <div style="margin-bottom: 2rem;">
            <span style="color: var(--neon-pink);">> CONNECTING_TO: </span>
            <input type="text" class="terminal-input" value="<?= $email ?>" readonly>
        </div>
        <?php if (!empty($profile['phone'])): ?>
        <div style="margin-bottom: 2rem;">
            <span style="color: var(--neon-blue);">> CALL_SERVICE: </span>
            <span style="color: var(--text); font-family: 'JetBrains Mono', monospace;"><?= htmlspecialchars($profile['phone']) ?></span>
        </div>
        <?php endif; ?>
        <div style="font-size: 0.6rem; color: var(--text-muted); opacity: 0.5;">
             LOG_OUT: <?= date('d.m.Y_H:i') ?> | SYSTEM_RESERVED
        </div>
    </footer>
</div>

</body>
</html>
