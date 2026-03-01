<?php
// Page d'accueil publique de BUILD.CV
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/dashboard/index.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= APP_NAME ?> – Créez votre CV et votre Portfolio</title>
<meta name="description" content="Créez un CV professionnel et un portfolio personnel en quelques minutes. Rédaction assistée par IA, beaux modèles, export PDF instantané.">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --primary: #6366f1; --primary-dark: #4f46e5;
    --purple: #8b5cf6; --pink: #ec4899;
    --bg: #06060c; --surface: rgba(255,255,255,0.04);
    --border: rgba(255,255,255,0.08);
    --text: #e8e8f0; --text-muted: rgba(255,255,255,0.5);
    --font: 'Inter', sans-serif;
    --glow: 0 0 60px rgba(99,102,241,0.25);
    --radius: 14px;
}
html { scroll-behavior: smooth; }
body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.6; overflow-x: hidden; }

/* NAV */
nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
    background: rgba(6,6,12,0.85);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 2rem; height: 62px;
}
.nav-brand {
    font-size: 1.3rem; font-weight: 800; letter-spacing: -0.5px;
    background: linear-gradient(135deg, var(--primary), var(--purple));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    text-decoration: none;
}
.nav-links  { display: flex; gap: 2rem; list-style: none; }
.nav-links a { color: var(--text-muted); text-decoration: none; font-size: 0.88rem; font-weight: 500; transition: color 0.2s; }
.nav-links a:hover { color: var(--text); }
.nav-cta { display: flex; gap: 0.75rem; align-items: center; }

/* BOUTONS */
.btn-primary {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: linear-gradient(135deg, var(--primary), var(--purple));
    color: white; padding: 0.65rem 1.5rem; border-radius: 8px;
    font-weight: 600; font-size: 0.9rem; text-decoration: none;
    transition: all 0.25s; border: none; cursor: pointer;
    box-shadow: 0 4px 20px rgba(99,102,241,0.3);
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(99,102,241,0.5); }
.btn-outline {
    display: inline-flex; align-items: center; gap: 0.4rem;
    color: var(--text); padding: 0.65rem 1.5rem; border-radius: 8px;
    font-weight: 500; font-size: 0.9rem; text-decoration: none;
    border: 1px solid var(--border); transition: all 0.2s;
}
.btn-outline:hover { border-color: rgba(99,102,241,0.5); color: white; }
.btn-lg { padding: 0.85rem 2rem; font-size: 1rem; }

/* HERO */
.hero {
    min-height: 100vh; display: flex; align-items: center; justify-content: center;
    padding: 80px 2rem 5rem; text-align: center; position: relative; overflow: hidden;
}
.hero-bg-glow {
    position: absolute; top: 20%; left: 50%; transform: translateX(-50%);
    width: 700px; height: 700px;
    background: radial-gradient(ellipse, rgba(99,102,241,0.12) 0%, transparent 70%);
    pointer-events: none;
}
.hero-content { position: relative; z-index: 1; max-width: 800px; }
.hero-badge {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25);
    color: #a5b4fc; padding: 0.35rem 1rem; border-radius: 20px;
    font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem;
}
.hero h1 {
    font-size: clamp(2.5rem, 7vw, 5rem); font-weight: 900;
    letter-spacing: -2px; line-height: 1.05; margin-bottom: 1.25rem;
}
.hero h1 .gradient-text {
    background: linear-gradient(135deg, #a5b4fc, var(--primary), var(--purple), var(--pink));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-size: 200%; animation: gradientShift 5s ease infinite;
}
@keyframes gradientShift { 0%,100% { background-position: 0% } 50% { background-position: 100% } }
.hero-sub {
    font-size: 1.15rem; color: var(--text-muted); max-width: 540px; margin: 0 auto 2.5rem;
    line-height: 1.7;
}
.hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.hero-social-proof {
    margin-top: 3rem; font-size: 0.82rem; color: var(--text-muted);
    display: flex; align-items: center; justify-content: center; gap: 0.75rem;
}
.avatar-stack { display: flex; }
.avatar-stack span {
    width: 28px; height: 28px; border-radius: 50%; border: 2px solid var(--bg);
    background: linear-gradient(135deg, var(--primary), var(--purple));
    color: white; font-size: 0.65rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    margin-left: -8px;
}
.avatar-stack span:first-child { margin-left: 0; }

/* FONCTIONNALITÉS */
.section { padding: 6rem 2rem; }
.section-inner { max-width: 1100px; margin: 0 auto; }
.section-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 3px; color: #a5b4fc; margin-bottom: 1rem; display: block; text-align: center; }
.section-title { font-size: clamp(1.8rem, 3.5vw, 2.5rem); font-weight: 800; letter-spacing: -1px; text-align: center; margin-bottom: 0.75rem; }
.section-desc { text-align: center; color: var(--text-muted); font-size: 1rem; max-width: 520px; margin: 0 auto 3rem; line-height: 1.7; }

.features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
.feature-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
    padding: 1.75rem; transition: all 0.3s; position: relative; overflow: hidden;
}
.feature-card::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(99,102,241,0.05), transparent);
    opacity: 0; transition: opacity 0.3s;
}
.feature-card:hover::before { opacity: 1; }
.feature-card:hover { border-color: rgba(99,102,241,0.3); transform: translateY(-4px); box-shadow: 0 20px 50px rgba(0,0,0,0.3); }
.feature-icon { font-size: 2rem; margin-bottom: 1rem; display: block; }
.feature-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; }
.feature-desc  { font-size: 0.88rem; color: var(--text-muted); line-height: 1.65; }

/* MODÈLES */
#templates { background: linear-gradient(180deg, #06060c, #0c0c18, #06060c); }
.templates-showcase { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
.tpl-card {
    border: 1px solid var(--border); border-radius: var(--radius);
    overflow: hidden; transition: all 0.3s; cursor: pointer;
    background: var(--surface);
}
.tpl-card:hover { border-color: rgba(99,102,241,0.4); transform: scale(1.02); box-shadow: 0 20px 50px rgba(0,0,0,0.4); }
.tpl-thumb { height: 180px; display: flex; align-items: center; justify-content: center; font-size: 3rem; }
.tpl-info  { padding: 1rem 1.1rem; }
.tpl-name  { font-weight: 700; margin-bottom: 0.25rem; }
.tpl-desc  { font-size: 0.8rem; color: var(--text-muted); }
.tpl-badge { display: inline-block; font-size: 0.7rem; font-weight: 700; padding: 0.1rem 0.5rem; border-radius: 10px; margin-top: 0.4rem; }
.tpl-badge.free    { background: rgba(255,255,255,0.07); color: var(--text-muted); }
.tpl-badge.premium { background: rgba(99,102,241,0.15); color: #a5b4fc; }

/* COMMENT ÇA MARCHE */
.steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; position: relative; }
.steps::before {
    content: ''; position: absolute; top: 28px; left: 12.5%; right: 12.5%;
    height: 1px; background: linear-gradient(90deg, transparent, var(--border), transparent);
}
.step { text-align: center; }
.step-num {
    width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--purple));
    color: white; font-weight: 800; font-size: 1.1rem;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; position: relative; z-index: 1;
    box-shadow: 0 8px 24px rgba(99,102,241,0.4);
}
.step-title { font-weight: 700; margin-bottom: 0.4rem; }
.step-desc  { font-size: 0.85rem; color: var(--text-muted); line-height: 1.6; }

/* TARIFS */
#pricing { }
.pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; max-width: 1000px; margin: 0 auto; align-items: stretch; }
.pricing-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
    padding: 2rem; display: flex; flex-direction: column; gap: 1rem;
}
.pricing-card.featured {
    border-color: var(--primary);
    background: rgba(99,102,241,0.05);
    box-shadow: 0 0 0 1px var(--primary), 0 30px 60px rgba(99,102,241,0.15);
}
.plan-name    { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); }
.plan-price   { font-size: 2.8rem; font-weight: 900; letter-spacing: -1.5px; }
.plan-period  { font-size: 0.9rem; color: var(--text-muted); }
.plan-features { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; flex: 1; }
.plan-features li { font-size: 0.88rem; display: flex; align-items: flex-start; gap: 0.5rem; }
.check { color: #10b981; flex-shrink: 0; }
.cross { color: #6b7280; flex-shrink: 0; }

/* CTA */
.cta-section {
    padding: 7rem 2rem; text-align: center;
    background: radial-gradient(ellipse at center, rgba(99,102,241,0.1) 0%, transparent 70%);
}
.cta-section h2 { font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; letter-spacing: -1px; margin-bottom: 1rem; }
.cta-section p  { color: var(--text-muted); font-size: 1.05rem; margin-bottom: 2.5rem; }

/* PIED DE PAGE */
footer {
    border-top: 1px solid var(--border); padding: 2.5rem 2rem;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
}
.footer-brand { font-weight: 800; font-size: 1.1rem; background: linear-gradient(135deg, var(--primary), var(--purple)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.footer-links { display: flex; gap: 1.5rem; }
.footer-links a { color: var(--text-muted); text-decoration: none; font-size: 0.85rem; }
.footer-copyright { color: var(--text-muted); font-size: 0.8rem; }

@media (max-width: 900px) {
    .features-grid  { grid-template-columns: 1fr 1fr; }
    .steps          { grid-template-columns: 1fr 1fr; }
    .templates-showcase { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 640px) {
    .nav-links { display: none; }
    .features-grid { grid-template-columns: 1fr; }
    .steps { grid-template-columns: 1fr 1fr; }
    .pricing-grid { grid-template-columns: 1fr; }
    .templates-showcase { grid-template-columns: 1fr; }
    .hero h1 { letter-spacing: -1px; }
    .steps::before { display: none; }
    footer { flex-direction: column; text-align: center; }
}
</style>
</head>
<body>

<!-- Navigation -->
<nav>
    <a class="nav-brand" href="#"><?= APP_NAME ?></a>
    <ul class="nav-links">
        <li><a href="#features">Fonctionnalités</a></li>
        <li><a href="#templates">Modèles</a></li>
        <li><a href="#how-it-works">Comment ça marche</a></li>
        <li><a href="#pricing">Tarifs</a></li>
    </ul>
    <div class="nav-cta">
        <a href="auth/login.php" class="btn-outline" style="padding:0.55rem 1.1rem;font-size:0.85rem">Connexion</a>
        <a href="auth/register.php" class="btn-primary" style="padding:0.55rem 1.2rem;font-size:0.85rem">Commencer gratuitement</a>
    </div>
</nav>

<!-- Hero -->
<section class="hero" id="home">
    <div class="hero-bg-glow"></div>
    <div class="hero-content">
        <div class="hero-badge">
            ✨ CV et portfolios professionnels assistés par IA
        </div>
        <h1>Votre carrière,<br><span class="gradient-text">Brillamment présentée</span></h1>
        <p class="hero-sub">Créez un CV et un portfolio percutants en quelques minutes. L'IA rédige pour vous, les modèles font le design — vous, vous décrochez le poste.</p>
        <div class="hero-cta">
            <a href="auth/register.php" class="btn-primary btn-lg">Créer mon CV gratuitement →</a>
            <a href="#how-it-works" class="btn-outline btn-lg">Comment ça marche</a>
        </div>
        <div class="hero-social-proof">
            <div class="avatar-stack">
                <span>J</span><span>A</span><span>M</span><span>S</span><span>+</span>
            </div>
            <span>Rejoignez des centaines de professionnels déjà recrutés</span>
        </div>
    </div>
</section>

<!-- Fonctionnalités -->
<section class="section" id="features">
    <div class="section-inner">
        <span class="section-label">Pourquoi BUILD.CV</span>
        <h2 class="section-title">Tout ce qu'il faut pour être recruté</h2>
        <p class="section-desc">De la rédaction au design en passant par l'hébergement — tout en un seul endroit.</p>
        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-icon">✍️</span>
                <div class="feature-title">Amélioration IA du contenu</div>
                <p class="feature-desc">Difficile de décrire vos expériences ? Notre IA reformule vos points en contenu professionnel convaincant, optimisé pour les ATS.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🎨</span>
                <div class="feature-title">Modèles élégants</div>
                <p class="feature-desc">Choisissez parmi les modèles Sobre, Professionnel et Créatif. Tous conçus par des professionnels, optimisés pour la lisibilité.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">📄</span>
                <div class="feature-title">Export PDF en un clic</div>
                <p class="feature-desc">Téléchargez votre CV en PDF parfait d'un seul clic. Aucun problème de mise en forme, toujours prêt à imprimer.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🌐</span>
                <div class="feature-title">Portfolio auto-généré</div>
                <p class="feature-desc">Vos données CV deviennent automatiquement un beau portfolio responsive. Partagez votre lien en un clic — aucune compétence en design requise.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">📊</span>
                <div class="feature-title">Statistiques de portfolio</div>
                <p class="feature-desc">Voyez exactement combien de recruteurs ont consulté votre portfolio, d'où ils viennent et comment l'engagement évolue dans le temps.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🔄</span>
                <div class="feature-title">Aperçu en temps réel</div>
                <p class="feature-desc">Au fur et à mesure que vous remplissez votre CV, regardez-le se mettre à jour en direct dans le panneau d'aperçu.</p>
            </div>
        </div>
    </div>
</section>

<!-- Modèles -->
<section class="section" id="templates">
    <div class="section-inner">
        <span class="section-label">Modèles</span>
        <h2 class="section-title">Des designs qui font la différence</h2>
        <p class="section-desc">Choisissez un style de CV et un thème de portfolio. Changez à tout moment en un clic.</p>
        <div class="templates-showcase">
            <div class="tpl-card">
                <div class="tpl-thumb" style="background:linear-gradient(135deg,#f5f5f5,#fff)">
                    <span>📄</span>
                </div>
                <div class="tpl-info">
                    <div class="tpl-name">Sobre</div>
                    <div class="tpl-desc">Une colonne, noir et blanc, intemporel et compatible ATS.</div>
                    <span class="tpl-badge free">Gratuit</span>
                </div>
            </div>
            <div class="tpl-card">
                <div class="tpl-thumb" style="background:linear-gradient(135deg,#1e3a5f,#2a4a7f)">
                    <span>📋</span>
                </div>
                <div class="tpl-info">
                    <div class="tpl-name">Professionnel</div>
                    <div class="tpl-desc">Deux colonnes bleu marine avec une barre latérale structurée.</div>
                    <span class="tpl-badge free">Gratuit</span>
                </div>
            </div>
            <div class="tpl-card">
                <div class="tpl-thumb" style="background:linear-gradient(135deg,#3730a3,#6366f1)">
                    <span>✨</span>
                </div>
                <div class="tpl-info">
                    <div class="tpl-name">Créatif</div>
                    <div class="tpl-desc">Accents indigo modernes. Parfait pour les designers et développeurs.</div>
                    <span class="tpl-badge premium">Premium</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comment ça marche -->
<section class="section" id="how-it-works">
    <div class="section-inner">
        <span class="section-label">Comment ça marche</span>
        <h2 class="section-title">Prêt en moins de 5 minutes</h2>
        <p class="section-desc">Aucune compétence en design ou en technique requise.</p>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-title">Créez un compte</div>
                <p class="step-desc">Inscription en quelques secondes — aucune carte bancaire requise.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-title">Remplissez votre CV</div>
                <p class="step-desc">Ajoutez vos expériences, compétences et résumé. Laissez l'IA vous aider.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-title">Choisissez un modèle</div>
                <p class="step-desc">Sélectionnez parmi nos modèles de CV et de portfolio soigneusement conçus.</p>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-title">Exportez et partagez</div>
                <p class="step-desc">Téléchargez votre PDF et partagez votre lien portfolio avec les recruteurs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Tarifs -->
<section class="section" id="pricing">
    <div class="section-inner">
        <span class="section-label">Tarifs</span>
        <h2 class="section-title">Abonnement Simple & Transparent</h2>
        <p class="section-desc">Passez au Premium pour débloquer tout le potentiel de votre profil. Paiement sécurisé et manuel via TMoney ou Flooz.</p>
        <div class="pricing-grid">
            <div class="pricing-card">
                <div class="plan-name">Gratuit</div>
                <div><span class="plan-price">0 F CFA</span></div>
                <ul class="plan-features">
                    <li><span class="check">✓</span> 1 CV professionnel</li>
                    <li><span class="check">✓</span> 1 Portfolio en ligne</li>
                    <li><span class="check">✓</span> Modèles basiques uniques</li>
                    <li><span class="check">✓</span> Téléchargement PDF avec filigrane</li>
                    <li><span class="cross">✗</span> Pas d'Amélioration IA</li>
                </ul>
                <a href="auth/register.php" class="btn-outline" style="display:block;text-align:center;padding:0.75rem;border-radius:8px">Créer mon compte Gratuit</a>
            </div>

            <div class="pricing-card" style="border-color: rgba(16, 185, 129, 0.3);">
                <div class="plan-name" style="color:#10b981">Standard 🚀</div>
                <div><span class="plan-price">3000 F CFA</span><span class="plan-period"> / mois</span></div>
                <ul class="plan-features">
                    <li><span class="check">✓</span> Tout ce qui est inclus dans le Gratuit</li>
                    <li><span class="check">✓</span> Accès à 5 modèles</li>
                    <li><span class="check">✓</span> Export PDF HD sans filigrane</li>
                    <li><span class="check">✓</span> Statistiques de base</li>
                    <li><span class="cross">✗</span> Pas d'Assistance IA illimitée</li>
                </ul>
                <div style="background:rgba(16,185,129,0.1); border-radius:8px; padding:1rem; margin-top:0.5rem; text-align:center;">
                    <p style="font-size:0.85rem; color:#10b981; margin-bottom:0.5rem; font-weight:600;">Comment s'abonner ?</p>
                    <p style="font-size:0.8rem; line-height:1.5;">Paiement manuel via TMoney ou Flooz depuis votre telephone.</p>
                </div>
                <a href="auth/register.php" class="btn-primary" style="display:block;text-align:center;padding:0.75rem;border-radius:8px; margin-top: 0.5rem; background: #10b981;">Passer au Standard</a>
            </div>

            <div class="pricing-card featured">
                <div class="plan-name" style="color:#a5b4fc">Premium ⭐</div>
                <div><span class="plan-price">5000 F CFA</span><span class="plan-period"> / mois</span></div>
                <ul class="plan-features">
                    <li><span class="check">✓</span> Tout ce qui est inclus dans le Standard</li>
                    <li><span class="check">✓</span> Assistance Rédaction IA illimitée</li>
                    <li><span class="check">✓</span> Accès à 100% des modèles Premium</li>
                    <li><span class="check">✓</span> Export Word (.docx)</li>
                    <li><span class="check">✓</span> Support prioritaire</li>
                </ul>
                <div style="background:rgba(99,102,241,0.1); border-radius:8px; padding:1rem; margin-top:0.5rem; text-align:center;">
                    <p style="font-size:0.85rem; color:#a5b4fc; margin-bottom:0.5rem; font-weight:600;">Comment s'abonner ?</p>
                    <p style="font-size:0.8rem; line-height:1.5;">Paiement manuel via TMoney ou Flooz depuis votre telephone.</p>
                </div>
                <a href="auth/register.php" class="btn-primary" style="display:block;text-align:center;padding:0.75rem;border-radius:8px; margin-top: 0.5rem;">Devenir Premium</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA final -->
<section class="cta-section">
    <h2>Prêt à décrocher<br>le poste de vos rêves ?</h2>
    <p>Rejoignez des centaines de professionnels qui ont boosté leur carrière avec BUILD.CV</p>
    <a href="auth/register.php" class="btn-primary btn-lg">Créer mon CV gratuitement →</a>
</section>

<!-- Pied de page -->
<footer>
    <div class="footer-brand"><?= APP_NAME ?></div>
    <div class="footer-links">
        <a href="#features">Fonctionnalités</a>
        <a href="#pricing">Tarifs</a>
        <a href="<?= APP_URL ?>/auth/login.php">Connexion</a>
        <a href="<?= APP_URL ?>/auth/register.php">Inscription</a>
    </div>
    <div class="footer-copyright">© <?= date('Y') ?> <?= APP_NAME ?>. Tous droits réservés.</div>
</footer>

</body>
</html>
