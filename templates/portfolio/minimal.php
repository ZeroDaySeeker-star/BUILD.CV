<?php
// Modèle de portfolio : Sobre – Mise en page professionnelle blanche et propre
$name     = htmlspecialchars($profile['full_name'] ?? 'Mon Portfolio');
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
<title><?= $name ?><?= $title ? ' – ' . $title : '' ?></title>
<meta name="description" content="<?= substr($summary, 0, 160) ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --primary: #6366f1; --primary-dark: #4f46e5;
    --text: #111827; --text-muted: #6b7280;
    --bg: #ffffff; --surface: #f9fafb; --border: #e5e7eb;
    --radius: 12px; --font: 'Inter', sans-serif;
}
html { scroll-behavior: smooth; }
body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.6; }

/* Nav */
nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    background: rgba(255,255,255,0.92); backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 2rem; height: 60px;
}
.nav-brand { font-weight: 800; font-size: 1.1rem; color: var(--text); text-decoration: none; }
.nav-links  { display: flex; gap: 2rem; list-style: none; }
.nav-links a { text-decoration: none; color: var(--text-muted); font-size: 0.88rem; font-weight: 500; transition: color 0.2s; }
.nav-links a:hover { color: var(--primary); }

/* Hero */
.hero {
    min-height: 100vh; display: flex; align-items: center; justify-content: center;
    padding: 80px 2rem 4rem; text-align: center;
    background: linear-gradient(135deg, #f0f0ff 0%, #fff 60%, #f0fff8 100%);
}
.hero-photo {
    width: 120px; height: 120px; border-radius: 50%; object-fit: cover;
    border: 4px solid var(--primary); margin: 0 auto 1.5rem; display: block;
    box-shadow: 0 12px 40px rgba(99,102,241,0.2);
}
.hero-initial {
    width: 120px; height: 120px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #a5b4fc);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; font-weight: 800; color: white;
    margin: 0 auto 1.5rem; box-shadow: 0 12px 40px rgba(99,102,241,0.3);
}
.hero-badge {
    display: inline-block; background: rgba(99,102,241,0.1);
    color: var(--primary); font-size: 0.82rem; font-weight: 600;
    padding: 0.3rem 1rem; border-radius: 20px; margin-bottom: 1rem;
    border: 1px solid rgba(99,102,241,0.2);
}
.hero h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 800; letter-spacing: -1px; margin-bottom: 0.75rem; }
.hero p { font-size: 1.05rem; color: var(--text-muted); max-width: 540px; margin: 0 auto 2rem; line-height: 1.7; }
.hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

.btn-primary {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: var(--primary); color: white; padding: 0.75rem 1.75rem;
    border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem;
    transition: all 0.2s; box-shadow: 0 4px 15px rgba(99,102,241,0.3);
}
.btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,0.4); }

.btn-outline {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: white; color: var(--text); padding: 0.75rem 1.75rem;
    border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 0.95rem;
    border: 1.5px solid var(--border); transition: all 0.2s;
}
.btn-outline:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-2px); }

/* Sections */
section { padding: 5rem 2rem; }
.section-inner { max-width: 900px; margin: 0 auto; }
.section-label { font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 3px; color: var(--primary); margin-bottom: 0.75rem; display: block; }
.section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; letter-spacing: -0.5px; margin-bottom: 3rem; }

/* About */
#about { background: var(--surface); }
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start; }
.about-text p { font-size: 1rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 1rem; }
.about-details { display: flex; flex-direction: column; gap: 0.75rem; }
.about-detail { display: flex; align-items: center; gap: 0.75rem; font-size: 0.9rem; }
.about-detail-icon { width: 36px; height: 36px; background: rgba(99,102,241,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.about-detail-text strong { display: block; font-weight: 600; font-size: 0.88rem; }
.about-detail-text span { color: var(--text-muted); font-size: 0.82rem; }

/* Skills */
.skills-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
.skill-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
    padding: 1.1rem 1.25rem; transition: all 0.25s;
}
.skill-card:hover { border-color: var(--primary); transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
.skill-name { font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; }
.skill-bar-bg { height: 5px; background: var(--border); border-radius: 3px; overflow: hidden; }
.skill-bar-fill { height: 100%; background: linear-gradient(90deg, var(--primary), #a5b4fc); border-radius: 3px; transition: width 1s ease; }
.skill-pct { font-size: 0.75rem; font-weight: 600; color: var(--primary); margin-top: 0.3rem; }

/* Experience */
#experience { background: var(--surface); }
.timeline { position: relative; }
.timeline::before { content: ''; position: absolute; left: 16px; top: 0; bottom: 0; width: 2px; background: var(--border); }
.timeline-item { display: flex; gap: 2rem; padding: 0 0 2rem 0; position: relative; }
.timeline-dot {
    width: 34px; height: 34px; background: var(--primary); border-radius: 50%;
    display: flex; align-items: center; justify-content: center; color: white;
    font-size: 0.9rem; flex-shrink: 0; position: relative; z-index: 1;
    box-shadow: 0 0 0 4px rgba(99,102,241,0.15);
}
.timeline-content { flex: 1; padding-top: 4px; }
.timeline-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.25rem; }
.timeline-title { font-weight: 700; font-size: 1rem; }
.timeline-date { font-size: 0.8rem; color: var(--text-muted); background: var(--border); padding: 0.2rem 0.75rem; border-radius: 20px; white-space: nowrap; }
.timeline-company { font-size: 0.9rem; color: var(--primary); font-weight: 500; margin-bottom: 0.5rem; }
.timeline-desc { font-size: 0.9rem; color: var(--text-muted); line-height: 1.7; }

/* Projects */
.projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
.project-card {
    background: white; border: 1px solid var(--border); border-radius: var(--radius);
    overflow: hidden; transition: all 0.25s; display: flex; flex-direction: column;
}
.project-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(0,0,0,0.1); border-color: var(--primary); }
.project-card-top { height: 8px; background: linear-gradient(90deg, var(--primary), #a5b4fc); }
.project-card-body { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
.project-title { font-weight: 700; font-size: 1rem; margin-bottom: 0.5rem; }
.project-tech { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.75rem; }
.tech-tag { font-size: 0.72rem; font-weight: 600; background: rgba(99,102,241,0.08); color: var(--primary); padding: 0.15rem 0.6rem; border-radius: 20px; border: 1px solid rgba(99,102,241,0.15); }
.project-desc { font-size: 0.88rem; color: var(--text-muted); line-height: 1.6; flex: 1; }
.project-links { display: flex; gap: 0.75rem; margin-top: 1rem; }
.project-link { font-size: 0.82rem; font-weight: 500; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 0.3rem; }
.project-link:hover { text-decoration: underline; }

/* Contact */
#contact { background: linear-gradient(135deg, #0f0f1a, #1a1040); color: white; text-align: center; }
#contact .section-title { color: white; }
#contact .section-label { color: #a5b4fc; }
.contact-desc { color: rgba(255,255,255,0.6); font-size: 1rem; max-width: 480px; margin: 0 auto 2.5rem; line-height: 1.7; }
.contact-links { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.contact-link {
    display: flex; align-items: center; gap: 0.5rem;
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
    color: white; padding: 0.7rem 1.4rem; border-radius: 8px; text-decoration: none;
    font-size: 0.9rem; font-weight: 500; transition: all 0.2s;
}
.contact-link:hover { background: rgba(255,255,255,0.15); transform: translateY(-2px); }

/* Footer */
footer { background: #0a0a0f; color: rgba(255,255,255,0.4); text-align: center; padding: 1.5rem; font-size: 0.8rem; }
footer a { color: rgba(255,255,255,0.6); text-decoration: none; }

/* Empty state */
.empty-state { text-align: center; padding: 3rem; color: var(--text-muted); font-size: 0.9rem; }

/* Responsive */
@media (max-width: 768px) {
    .about-grid { grid-template-columns: 1fr; }
    .nav-links { display: none; }
    section { padding: 3.5rem 1.25rem; }
}
</style>
</head>
<body>

<!-- Navigation -->
<nav>
    <a class="nav-brand" href="#"><?= $name ?></a>
    <ul class="nav-links">
        <li><a href="#about">À propos</a></li>
        <li><a href="#skills">Compétences</a></li>
        <li><a href="#experience">Expérience</a></li>
        <?php if (!empty($projects)): ?><li><a href="#projects">Projets</a></li><?php endif; ?>
        <li><a href="#contact">Contact</a></li>
    </ul>
</nav>

<!-- Hero -->
<section class="hero" id="home">
    <div>
        <?php if ($photo): ?>
            <img src="<?= $photo ?>" class="hero-photo" alt="<?= $name ?>">
        <?php else: ?>
            <div class="hero-initial"><?= strtoupper(substr($name, 0, 1)) ?></div>
        <?php endif; ?>
        <?php if ($title): ?><span class="hero-badge"><?= $title ?></span><?php endif; ?>
        <h1><?= $name ?></h1>
        <?php if ($summary): ?><p><?= $summary ?></p><?php endif; ?>
        <div class="hero-cta">
            <a href="#contact" class="btn-primary">Me contacter →</a>
            <?php if (!empty($projects)): ?><a href="#projects" class="btn-outline">Voir mes travaux</a><?php endif; ?>
        </div>
    </div>
</section>

<!-- About -->
<section id="about">
    <div class="section-inner">
        <span class="section-label">À propos</span>
        <h2 class="section-title">Qui suis-je ?</h2>
        <div class="about-grid">
            <div class="about-text">
                <p><?= $summary ?: 'Bienvenue sur mon portfolio ! Je suis passionné(e) par mon domaine et impatient(e) de partager mes réalisations avec vous.' ?></p>
                <?php if ($education): ?>
                <p style="margin-top:1rem">
                    <?php $edu = $education[0]; ?>
                    Études en <?= htmlspecialchars($edu['degree']) ?> <?= $edu['field'] ? 'de ' . htmlspecialchars($edu['field']) : '' ?>
                    à <?= htmlspecialchars($edu['school']) ?>.
                </p>
                <?php endif; ?>
            </div>
            <div class="about-details">
                <?php if ($location): ?>
                <div class="about-detail">
                    <div class="about-detail-icon">📍</div>
                    <div class="about-detail-text"><strong>Localisation</strong><span><?= $location ?></span></div>
                </div>
                <?php endif; ?>
                <?php if ($email): ?>
                <div class="about-detail">
                    <div class="about-detail-icon">✉️</div>
                    <div class="about-detail-text"><strong>E-mail</strong><span><?= $email ?></span></div>
                </div>
                <?php endif; ?>
                <?php if ($phone): ?>
                <div class="about-detail">
                    <div class="about-detail-icon">📞</div>
                    <div class="about-detail-text"><strong>Téléphone</strong><span><?= $phone ?></span></div>
                </div>
                <?php endif; ?>
                <?php if ($languages): ?>
                <div class="about-detail">
                    <div class="about-detail-icon">🌍</div>
                    <div class="about-detail-text">
                        <strong>Langues</strong>
                        <span><?= implode(', ', array_map(fn($l) => htmlspecialchars($l['language_name']), $languages)) ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Skills -->
<?php if ($skills): ?>
<section id="skills">
    <div class="section-inner">
        <span class="section-label">Expertise</span>
        <h2 class="section-title">Mes compétences</h2>
        <div class="skills-grid">
            <?php foreach ($skills as $s): ?>
            <div class="skill-card">
                <div class="skill-name"><?= htmlspecialchars($s['skill_name']) ?></div>
                <div class="skill-bar-bg">
                    <div class="skill-bar-fill" style="width:<?= $s['skill_level'] ?>%"></div>
                </div>
                <div class="skill-pct"><?= $s['skill_level'] ?>%</div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Experience -->
<?php if ($experience): ?>
<section id="experience">
    <div class="section-inner">
        <span class="section-label">Carrière</span>
        <h2 class="section-title">Expérience professionnelle</h2>
        <div class="timeline">
            <?php foreach ($experience as $exp): ?>
            <div class="timeline-item">
                <div class="timeline-dot">💼</div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <span class="timeline-title"><?= htmlspecialchars($exp['position']) ?></span>
                        <span class="timeline-date"><?= htmlspecialchars($exp['start_date']) ?> – <?= htmlspecialchars($exp['end_date']) ?></span>
                    </div>
                    <div class="timeline-company"><?= htmlspecialchars($exp['company']) ?></div>
                    <?php if ($exp['description']): ?>
                    <p class="timeline-desc"><?= $exp['description'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Projects -->
<?php if ($projects): ?>
<section id="projects">
    <div class="section-inner">
        <span class="section-label">Réalisations</span>
        <h2 class="section-title">Projets mis en avant</h2>
        <div class="projects-grid">
            <?php foreach ($projects as $p): ?>
            <div class="project-card">
                <div class="project-card-top"></div>
                <div class="project-card-body">
                    <div class="project-title"><?= htmlspecialchars($p['title']) ?></div>
                    <?php if ($p['technologies']): ?>
                    <div class="project-tech">
                        <?php foreach (explode(',', $p['technologies']) as $tech): ?>
                        <span class="tech-tag"><?= htmlspecialchars(trim($tech)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($p['description']): ?>
                    <p class="project-desc"><?= $p['description'] ?></p>
                    <?php endif; ?>
                    <?php if ($p['project_url'] || $p['github_url']): ?>
                    <div class="project-links">
                        <?php if ($p['project_url']): ?><a href="<?= htmlspecialchars($p['project_url']) ?>" class="project-link" target="_blank">🔗 Démo en ligne</a><?php endif; ?>
                        <?php if ($p['github_url']): ?><a href="<?= htmlspecialchars($p['github_url']) ?>" class="project-link" target="_blank">⌘ GitHub</a><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact -->
<section id="contact">
    <div class="section-inner">
        <span class="section-label">Prendre contact</span>
        <h2 class="section-title">Me contacter</h2>
        
        <?php if (($planLevel ?? 1) >= 3): ?>
            <div style="max-width: 600px; margin: 0 auto; text-align: left; background: rgba(255,255,255,0.05); padding: 2.5rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">
                <form id="portfolio-contact-form" onsubmit="submitContactForm(event)">
                    <input type="hidden" name="profile_id" value="<?= $profile['id'] ?>">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label style="display:block; font-size:0.8rem; margin-bottom:0.5rem; color:rgba(255,255,255,0.6);">Votre Nom</label>
                            <input type="text" name="name" required style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:rgba(0,0,0,0.2); color:white;">
                        </div>
                        <div class="form-group">
                            <label style="display:block; font-size:0.8rem; margin-bottom:0.5rem; color:rgba(255,255,255,0.6);">Votre E-mail</label>
                            <input type="email" name="email" required style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:rgba(0,0,0,0.2); color:white;">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.8rem; margin-bottom:0.5rem; color:rgba(255,255,255,0.6);">Sujet</label>
                        <input type="text" name="subject" required style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:rgba(0,0,0,0.2); color:white;">
                    </div>
                    <div class="form-group" style="margin-bottom:1.5rem;">
                        <label style="display:block; font-size:0.8rem; margin-bottom:0.5rem; color:rgba(255,255,255,0.6);">Message</label>
                        <textarea name="message" required style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid rgba(255,255,255,0.1); background:rgba(0,0,0,0.2); color:white; min-height:120px;"></textarea>
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">Envoyer le message 🚀</button>
                    <div id="contact-feedback" style="margin-top:1rem; font-size:0.9rem; text-align:center;"></div>
                </form>
            </div>
        <?php else: ?>
            <p class="contact-desc">Je suis ouvert(e) à de nouvelles opportunités et collaborations. N'hésitez pas à me contacter !</p>
            <div class="contact-links">
                <?php if ($email):    ?><a href="mailto:<?= $email ?>" class="contact-link">✉ <?= $email ?></a><?php endif; ?>
                <?php if ($phone):    ?><a href="tel:<?= $phone ?>" class="contact-link">📞 <?= $phone ?></a><?php endif; ?>
                <?php if ($linkedin): ?><a href="<?= $linkedin ?>" class="contact-link" target="_blank">in LinkedIn</a><?php endif; ?>
                <?php if ($github):   ?><a href="<?= $github ?>" class="contact-link" target="_blank">⌘ GitHub</a><?php endif; ?>
                <?php if ($website):  ?><a href="<?= $website ?>" class="contact-link" target="_blank">🌐 Site web</a><?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<footer>
    Créé avec <a href="<?= APP_URL ?>">BUILD.CV</a> · <?= date('Y') ?>
</footer>

<script>
async function submitContactForm(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button');
    const feedback = document.getElementById('contact-feedback');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    btn.disabled = true;
    btn.innerText = 'Envoi en cours...';
    feedback.innerHTML = '';

    try {
        const resp = await fetch('<?= APP_URL ?>/api/portfolio-contact.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await resp.json();
        
        if (result.success) {
            feedback.innerHTML = '<span style="color:#10b981">✅ ' + result.message + '</span>';
            form.reset();
        } else {
            feedback.innerHTML = '<span style="color:#ef4444">⚠️ ' + (result.error || 'Erreur') + '</span>';
        }
    } catch (err) {
        feedback.innerHTML = '<span style="color:#ef4444">⚠️ Erreur de connexion au serveur.</span>';
    } finally {
        btn.disabled = false;
        btn.innerText = 'Envoyer le message 🚀';
    }
}

// Animate skill bars on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.querySelectorAll('.skill-bar-fill').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => { bar.style.width = width; }, 100);
            });
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('#skills .section-inner').forEach(el => observer.observe(el));
</script>
</body>
</html>
