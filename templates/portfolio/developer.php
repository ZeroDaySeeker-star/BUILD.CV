<?php
// Modèle de portfolio : Développeur – Thème sombre style terminal
$name     = htmlspecialchars($profile['full_name'] ?? 'Développeur');
$title    = htmlspecialchars($profile['title'] ?? '');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
$website  = htmlspecialchars($profile['website'] ?? '');
$linkedin = htmlspecialchars($profile['linkedin'] ?? '');
$github   = htmlspecialchars($profile['github'] ?? '');
$summary  = htmlspecialchars($profile['summary'] ?? '');
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $name ?> – Portfolio Développeur</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --bg: #0d1117; --surface: #161b22; --surface-2: #1c2128;
    --border: rgba(255,255,255,0.08); --primary: #58a6ff;
    --green: #3fb950; --yellow: #d29922; --purple: #a371f7;
    --text: #cdd9e0; --text-muted: #8b949e;
    --font: 'Inter', sans-serif; --mono: 'JetBrains Mono', monospace;
    --radius: 8px;
}
html { scroll-behavior: smooth; }
body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.6; }

nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    background: rgba(13,17,23,0.92); backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 2rem; height: 58px;
}
.nav-brand { font-family: var(--mono); font-size: 1rem; color: var(--green); text-decoration: none; }
.nav-links  { display: flex; gap: 1.5rem; list-style: none; }
.nav-links a { color: var(--text-muted); text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: color 0.2s; }
.nav-links a:hover { color: var(--primary); }

/* Hero – terminal style */
.hero {
    min-height: 100vh; display: flex; align-items: center;
    padding: 80px 2rem 4rem;
    background: radial-gradient(ellipse at 50% 50%, rgba(88,166,255,0.05) 0%, transparent 60%);
}
.hero-inner { max-width: 860px; margin: 0 auto; }
.terminal-bar {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
    padding: 0.6rem 1rem; display: flex; align-items: center; gap: 0.5rem;
    margin-bottom: 2rem; font-family: var(--mono); font-size: 0.82rem; color: var(--text-muted);
}
.terminal-dots { display: flex; gap: 0.4rem; }
.dot { width: 12px; height: 12px; border-radius: 50%; }
.dot-red { background: #ff5f56; }
.dot-yellow { background: #febc2e; }
.dot-green { background: #28c840; }
.terminal-path { color: var(--green); }
.terminal-cmd  { color: var(--text); margin-left: 0.5rem; }
.hero-greeting { font-family: var(--mono); font-size: 1rem; color: var(--green); margin-bottom: 0.5rem; }
.hero h1 { font-size: clamp(2.2rem, 5vw, 4rem); font-weight: 800; letter-spacing: -1px; margin-bottom: 0.75rem; color: var(--text); }
.hero h1 .highlight { color: var(--primary); }
.hero-subtitle { font-family: var(--mono); font-size: 1.1rem; color: var(--yellow); margin-bottom: 1.5rem; }
.hero-summary { font-size: 1rem; color: var(--text-muted); max-width: 580px; line-height: 1.7; margin-bottom: 2rem; }
.hero-btns { display: flex; gap: 1rem; flex-wrap: wrap; }
.btn-primary { padding: 0.75rem 1.75rem; background: var(--primary); color: #0d1117; border-radius: var(--radius); font-weight: 700; text-decoration: none; font-size: 0.9rem; transition: all 0.2s; }
.btn-primary:hover { opacity: 0.9; transform: translateY(-2px); }
.btn-ghost { padding: 0.75rem 1.75rem; background: transparent; color: var(--text); border: 1px solid var(--border); border-radius: var(--radius); font-weight: 500; text-decoration: none; font-size: 0.9rem; transition: all 0.2s; }
.btn-ghost:hover { border-color: var(--primary); color: var(--primary); }

section { padding: 5rem 2rem; }
.section-inner { max-width: 900px; margin: 0 auto; }
.section-label { font-family: var(--mono); font-size: 0.75rem; color: var(--green); margin-bottom: 0.5rem; display: block; }
.section-title { font-size: clamp(1.6rem, 3vw, 2rem); font-weight: 800; color: var(--text); margin-bottom: 2.5rem; letter-spacing: -0.5px; }

/* About */
#about { background: var(--surface); }
.about-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 2rem; font-size: 0.95rem; color: var(--text-muted); line-height: 1.8; }

/* Skills */
.skills-grid { display: flex; flex-wrap: wrap; gap: 0.6rem; }
.skill-tag {
    display: flex; align-items: center; gap: 0.5rem;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 0.5rem 1rem;
    font-family: var(--mono); font-size: 0.82rem; color: var(--text);
    transition: all 0.2s;
}
.skill-tag:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-2px); }
.skill-dot { width: 8px; height: 8px; border-radius: 50%; }

/* Experience – diff style */
#experience { background: var(--surface); }
.exp-item { padding: 1.25rem; border: 1px solid var(--border); border-radius: var(--radius); margin-bottom: 1rem; background: var(--surface-2); }
.exp-header { display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.5rem; }
.exp-title { font-weight: 700; font-size: 1rem; color: var(--text); }
.exp-date  { font-family: var(--mono); font-size: 0.78rem; color: var(--text-muted); background: var(--surface); padding: 0.15rem 0.6rem; border-radius: 4px; }
.exp-company { color: var(--primary); font-size: 0.88rem; font-weight: 500; margin-bottom: 0.5rem; }
.exp-desc { font-size: 0.88rem; color: var(--text-muted); line-height: 1.65; }
.diff-plus  { color: var(--green); font-family: var(--mono); }

/* Projects – card grid */
.projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 1rem; }
.proj-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem; transition: all 0.25s; }
.proj-card:hover { border-color: var(--primary); transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.3); }
.proj-header { display: flex; align-items: flex-start; justify-content: space-between; }
.proj-icon { font-size: 1.5rem; }
.proj-links a { color: var(--text-muted); text-decoration: none; font-size: 1rem; margin-left: 0.5rem; transition: color 0.2s; }
.proj-links a:hover { color: var(--primary); }
.proj-name { font-weight: 700; font-size: 1rem; color: var(--text); }
.proj-desc { font-size: 0.85rem; color: var(--text-muted); line-height: 1.6; flex: 1; }
.proj-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.proj-tag { font-family: var(--mono); font-size: 0.72rem; color: var(--purple); background: rgba(163,113,247,0.1); padding: 0.15rem 0.5rem; border-radius: 4px; }

/* Contact */
#contact { background: var(--surface); }
.contact-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; }
.contact-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.25rem; text-align: center; text-decoration: none; color: var(--text); transition: all 0.2s; }
.contact-card:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-3px); }
.contact-card-icon { font-size: 1.5rem; margin-bottom: 0.5rem; display: block; }
.contact-card-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.2rem; }
.contact-card-value { font-size: 0.85rem; font-weight: 600; word-break: break-all; }

footer { background: var(--bg); border-top: 1px solid var(--border); text-align: center; padding: 1.5rem; font-family: var(--mono); font-size: 0.78rem; color: var(--text-muted); }
footer a { color: var(--primary); text-decoration: none; }

/* Skill level colors */
.color-0  { background: #58a6ff; }
.color-1  { background: #3fb950; }
.color-2  { background: #f78166; }
.color-3  { background: #c29922; }
.color-4  { background: #a371f7; }
.color-5  { background: #39d353; }

@media (max-width: 768px) { .nav-links { display: none; } section { padding: 3rem 1.25rem; } }
</style>
</head>
<body>

<nav>
    <a class="nav-brand" href="#">&lt;<?= $name ?> /&gt;</a>
    <ul class="nav-links">
        <li><a href="#about">// about</a></li>
        <li><a href="#skills">// skills</a></li>
        <li><a href="#experience">// experience</a></li>
        <?php if (!empty($projects)): ?><li><a href="#projects">// projects</a></li><?php endif; ?>
        <li><a href="#contact">// contact</a></li>
    </ul>
</nav>

<section class="hero" id="home">
    <div class="hero-inner">
        <div class="terminal-bar">
            <div class="terminal-dots">
                <span class="dot dot-red"></span>
                <span class="dot dot-yellow"></span>
                <span class="dot dot-green"></span>
            </div>
            <span class="terminal-path">~/portfolio</span>
            <span class="terminal-cmd">$ whoami</span>
        </div>
        <div class="hero-greeting">// Bonjour, Monde ! 👋</div>
        <h1>Je suis <span class="highlight"><?= $name ?></span></h1>
        <?php if ($title): ?><div class="hero-subtitle">&gt; <?= $title ?>_</div><?php endif; ?>
        <?php if ($summary): ?><p class="hero-summary"><?= $summary ?></p><?php endif; ?>
        <div class="hero-btns">
            <a href="#contact" class="btn-primary">Me contacter</a>
            <?php if ($github): ?><a href="<?= $github ?>" class="btn-ghost" target="_blank">⌘ GitHub</a><?php endif; ?>
            <?php if (!empty($projects)): ?><a href="#projects" class="btn-ghost">Voir les projets</a><?php endif; ?>
        </div>
    </div>
</section>

<section id="about">
    <div class="section-inner">
        <span class="section-label">// 01. about</span>
        <h2 class="section-title">À propos</h2>
        <div class="about-card">
            <p><?= $summary ?: 'Développeur passionné qui aime construire des choses et résoudre des problèmes grâce au code.' ?></p>
            <?php if ($location || $email): ?>
            <div style="margin-top:1rem;display:flex;gap:1.5rem;flex-wrap:wrap;font-family:var(--mono);font-size:0.82rem;">
                <?php if ($location): ?><span><span style="color:var(--green)">location</span>: "<?= $location ?>"</span><?php endif; ?>
                <?php if ($email): ?><span><span style="color:var(--green)">email</span>: "<?= $email ?>"</span><?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($skills): ?>
<section id="skills">
    <div class="section-inner">
        <span class="section-label">// 02. skills</span>
        <h2 class="section-title">Tech Stack</h2>
        <div class="skills-grid">
            <?php foreach ($skills as $i => $s): ?>
            <div class="skill-tag">
                <span class="skill-dot color-<?= $i % 6 ?>"></span>
                <?= htmlspecialchars($s['skill_name']) ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($experience): ?>
<section id="experience">
    <div class="section-inner">
        <span class="section-label">// 03. experience</span>
        <h2 class="section-title">Parcours professionnel</h2>
        <?php foreach ($experience as $exp): ?>
        <div class="exp-item">
            <div class="exp-header">
                <span class="exp-title"><span class="diff-plus">+ </span><?= htmlspecialchars($exp['position']) ?></span>
                <span class="exp-date"><?= htmlspecialchars($exp['start_date']) ?> → <?= htmlspecialchars($exp['end_date']) ?></span>
            </div>
            <div class="exp-company">@ <?= htmlspecialchars($exp['company']) ?></div>
            <?php if ($exp['description']): ?>
            <p class="exp-desc"><?= $exp['description'] ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($projects): ?>
<section id="projects">
    <div class="section-inner">
        <span class="section-label">// 04. projects</span>
        <h2 class="section-title">Travaux mis en avant</h2>
        <div class="projects-grid">
            <?php foreach ($projects as $p): ?>
            <div class="proj-card">
                <div class="proj-header">
                    <span class="proj-icon">📁</span>
                    <div class="proj-links">
                        <?php if ($p['github_url']): ?><a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank">⌘</a><?php endif; ?>
                        <?php if ($p['project_url']): ?><a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank">↗</a><?php endif; ?>
                    </div>
                </div>
                <div class="proj-name"><?= htmlspecialchars($p['title']) ?></div>
                <?php if ($p['description']): ?><p class="proj-desc"><?= $p['description'] ?></p><?php endif; ?>
                <?php if ($p['technologies']): ?>
                <div class="proj-tags">
                    <?php foreach (explode(',', $p['technologies']) as $tech): ?>
                    <span class="proj-tag"><?= htmlspecialchars(trim($tech)) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section id="contact">
    <div class="section-inner">
        <span class="section-label">// 05. contact</span>
        <h2 class="section-title">Prendre contact</h2>
        <p style="color:var(--text-muted);margin-bottom:1.5rem">Disponible pour des missions en freelance et des postes à temps plein.</p>
        <div class="contact-grid">
            <?php if ($email):    ?><a href="mailto:<?= $email ?>" class="contact-card"><span class="contact-card-icon">✉</span><div class="contact-card-label">E-mail</div><div class="contact-card-value"><?= $email ?></div></a><?php endif; ?>
            <?php if ($github):   ?><a href="<?= $github ?>" class="contact-card" target="_blank"><span class="contact-card-icon">⌘</span><div class="contact-card-label">GitHub</div><div class="contact-card-value">Voir le profil</div></a><?php endif; ?>
            <?php if ($linkedin): ?><a href="<?= $linkedin ?>" class="contact-card" target="_blank"><span class="contact-card-icon">in</span><div class="contact-card-label">LinkedIn</div><div class="contact-card-value">Se connecter</div></a><?php endif; ?>
            <?php if ($phone):    ?><a href="tel:<?= $phone ?>" class="contact-card"><span class="contact-card-icon">📞</span><div class="contact-card-label">Téléphone</div><div class="contact-card-value"><?= $phone ?></div></a><?php endif; ?>
        </div>
    </div>
</section>

<footer>
    <span style="color:var(--green)">&lt;</span>créé avec&nbsp;<a href="<?= APP_URL ?>">BUILD.CV</a>&nbsp;<span style="color:var(--green)">/&gt;</span> &nbsp;·&nbsp; <?= date('Y') ?>
</footer>
</body>
</html>
