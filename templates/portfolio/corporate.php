<?php
// Modèle de portfolio : Corporate – Professionnel, Stable, Confiance
$name     = htmlspecialchars($profile['full_name'] ?? 'Consultant');
$title    = htmlspecialchars($profile['title'] ?? 'Senior Advisor');
$email    = htmlspecialchars($profile['email'] ?? '');
$photo    = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $name ?> | Professional Portfolio</title>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --primary: #1e3a8a; /* Deep Navy */
        --secondary: #3b82f6; /* Blue */
        --bg: #f8fafc;
        --surface: #ffffff;
        --text: #0f172a;
        --text-muted: #64748b;
        --border: #e2e8f0;
        --radius: 8px;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Public Sans', sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; }

    .btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.75rem 1.5rem; border-radius: var(--radius);
        font-weight: 600; text-decoration: none; transition: 0.2s;
    }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #172554; }

    /* HEADER */
    header { background: var(--surface); border-bottom: 1px solid var(--border); padding: 1rem 0; sticky: top; z-index: 100; }
    .nav-container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; display: flex; justify-content: space-between; align-items: center; }
    .nav-logo { font-weight: 800; font-size: 1.3rem; color: var(--primary); }
    .nav-links { display: flex; gap: 2rem; list-style: none; font-size: 0.9rem; font-weight: 500; }
    .nav-links a { text-decoration: none; color: var(--text); }
    .nav-links a:hover { color: var(--secondary); }

    /* HERO */
    .hero { background: var(--surface); padding: 6rem 1.5rem; }
    .hero-container { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 4rem; align-items: center; }
    .hero-text h1 { font-size: 3rem; font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; color: var(--primary); }
    .hero-text p { font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2rem; max-width: 500px; }
    .hero-img { position: relative; }
    .hero-img img { width: 100%; border-radius: 20px; box-shadow: 20px 20px 0 var(--primary); }

    /* SECTIONS */
    section { padding: 5rem 1.5rem; }
    .section-container { max-width: 1100px; margin: 0 auto; }
    .section-title { font-size: 2rem; font-weight: 700; margin-bottom: 3rem; position: relative; padding-bottom: 1rem; }
    .section-title::after { content: ""; position: absolute; left: 0; bottom: 0; width: 60px; height: 4px; background: var(--secondary); }

    /* PROJECTS */
    .project-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; }
    .project-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 2rem; transition: 0.3s; }
    .project-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-color: var(--secondary); }
    .project-card h3 { font-size: 1.2rem; margin-bottom: 1rem; color: var(--primary); }
    .project-card p { font-size: 0.9rem; color: var(--text-muted); }

    /* SKILLS */
    .skills-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
    .skill-box { background: var(--surface); border: 1px solid var(--border); padding: 1.2rem; border-radius: var(--radius); display: flex; align-items: center; gap: 1rem; font-weight: 600; }
    .skill-box i { color: var(--secondary); }

    /* FOOTER */
    footer { background: var(--primary); color: white; padding: 4rem 1.5rem; text-align: center; }
    .footer-container { max-width: 1100px; margin: 0 auto; }
    .footer-title { font-size: 2rem; margin-bottom: 1rem; }
    .footer-email { font-size: 1.3rem; margin-bottom: 2rem; display: block; color: white; opacity: 0.8; text-decoration: none; }

    @media (max-width: 768px) {
        .hero-container { grid-template-columns: 1fr; text-align: center; }
        .hero-text h1 { font-size: 2.2rem; }
        .nav-links { display: none; }
    }
</style>
</head>
<body>

<header>
    <div class="nav-container">
        <div class="nav-logo"><?= $name ?></div>
        <ul class="nav-links">
            <li><a href="#about">About</a></li>
            <li><a href="#projects">Portfolio</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>
</header>

<section class="hero">
    <div class="hero-container">
        <div class="hero-text">
            <h1><?= $title ?: 'Driving Results <br> Through Expertise.' ?></h1>
            <p><?= $profile['summary'] ?? '' ?></p>
            <a href="#contact" class="btn btn-primary">Let's Connect</a>
        </div>
        <div class="hero-img">
            <?php if ($photo): ?>
            <img src="<?= $photo ?>" alt="Profile">
            <?php else: ?>
            <div style="width: 100%; aspect-ratio: 4/5; background: var(--primary); border-radius: 20px;"></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($projects)): ?>
<section id="projects" style="background: white;">
    <div class="section-container">
        <h2 class="section-title">Selected Works</h2>
        <div class="project-grid">
            <?php foreach ($projects as $p): ?>
            <div class="project-card">
                <h3><?= htmlspecialchars($p['title'] ?? $p['project_name'] ?? '') ?></h3>
                <div style="font-size:0.75rem; font-weight:700; color:var(--secondary); text-transform:uppercase; margin-bottom:0.75rem;">
                    <?php 
                    $techs = explode(',', $p['technologies'] ?? '');
                    echo implode(' • ', array_map('trim', array_filter($techs))); 
                    ?>
                </div>
                <div style="font-size:0.9rem; color:var(--text-muted); margin-bottom:1.5rem;"><?= strip_tags($p['description'] ?? '', '<b><i><strong><em>') ?></div>
                <div style="display:flex; gap:1.5rem;">
                    <?php if (!empty($p['project_url'])): ?>
                        <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" style="font-size:0.8rem; font-weight:700; text-decoration:none; color:var(--primary);">View Case Study ↗</a>
                    <?php endif; ?>
                    <?php if (!empty($p['github_url'])): ?>
                        <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" style="font-size:0.8rem; font-weight:700; text-decoration:none; color:var(--text-muted);">Repos ↗</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($skills)): ?>
<section id="skills">
    <div class="section-container">
        <h2 class="section-title">Core Competencies</h2>
        <div class="skills-grid">
            <?php foreach ($skills as $s): ?>
            <div class="skill-box">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= htmlspecialchars($s['skill_name'] ?? '') ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($experience)): ?>
<section id="experience">
    <div class="section-container">
        <h2 class="section-title">Professional Experience</h2>
        <div style="display: grid; gap: 2.5rem;">
            <?php foreach ($experience as $exp): ?>
            <div style="border-left: 3px solid var(--border); padding-left: 2rem; position: relative;">
                <div style="position: absolute; left: -9px; top: 0; width: 15px; height: 15px; border-radius: 50%; background: var(--secondary);"></div>
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem;">
                    <h3 style="color: var(--primary); font-size: 1.4rem;"><?= htmlspecialchars($exp['position']) ?></h3>
                    <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted);"><?= htmlspecialchars($exp['start_date']) ?> — <?= htmlspecialchars($exp['end_date']) ?></span>
                </div>
                <div style="font-weight: 700; color: var(--secondary); margin-bottom: 1rem;"><?= htmlspecialchars($exp['company']) ?></div>
                <div style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6;"><?= $exp['description'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($education)): ?>
<section id="education" style="background: white;">
    <div class="section-container">
        <h2 class="section-title">Academic Background</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
            <?php foreach ($education as $edu): ?>
            <div class="project-card">
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem;"><?= htmlspecialchars($edu['start_year']) ?> — <?= htmlspecialchars($edu['end_year']) ?></div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;"><?= htmlspecialchars($edu['school']) ?></h3>
                <div style="font-weight: 600; font-size: 0.9rem; margin-bottom: 1rem;"><?= htmlspecialchars($edu['degree']) ?> (<?= htmlspecialchars($edu['field']) ?>)</div>
                <div style="font-size: 0.85rem; color: var(--text-muted);"><?= $edu['description'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<footer id="contact">
    <div class="footer-container">
        <h2 class="footer-title">Ready for Your Next Step?</h2>
        <a href="mailto:<?= $email ?>" class="footer-email"><?= $email ?></a>
        <?php if (!empty($profile['phone'])): ?>
            <div style="font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.7; font-weight: 500;"><?= htmlspecialchars($profile['phone']) ?></div>
        <?php endif; ?>
        <div style="font-size: 0.8rem; margin-top: 3rem; opacity: 0.6;">
            © <?= date('Y') ?> <?= $name ?> | Independent Professional
        </div>
    </div>
</footer>

</body>
</html>
