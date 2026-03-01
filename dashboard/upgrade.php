<?php
// Tableau de bord : page de mise à niveau / tarification
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';

$pageTitle = 'Passer au Premium';
$activePage = 'upgrade';
include __DIR__ . '/../includes/head.php';
?>
<div class="dashboard-layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="page-content upgrade-page">
            <?php if (isset($_GET['error']) && $_GET['error'] === 'ai_tools'): ?>
            <div style="background:rgba(79, 70, 229, 0.1); border:1px solid var(--primary); color:var(--text); padding:1rem; border-radius:12px; margin-bottom:2rem; display:flex; align-items:center; gap:1rem;">
                <span style="font-size:1.5rem;">✨</span>
                <div>
                    <h4 style="margin:0; font-size:1rem;">Le Laboratoire IA est réservé aux membres Privilégiés</h4>
                    <p style="margin:0.25rem 0 0; font-size:0.85rem; color:var(--text-muted);">Passez au plan Standard ou Premium pour débloquer la génération de lettres et l'analyse d'offres.</p>
                </div>
            </div>
            <?php endif; ?>

            <div class="upgrade-hero">
                <span class="upgrade-hero-badge">⚡ Passer au Premium</span>
                <h1>Libérez tout votre potentiel</h1>
                <p>Accédez à tous les modèles, aux fonctionnalités IA, aux domaines personnalisés et aux statistiques avancées.</p>
            </div>

            <div class="pricing-grid">
                <!-- Gratuit -->
                <div class="pricing-card <?php echo ($planLevel == 1) ? 'current-plan-card' : ''; ?>">
                    <div class="pricing-badge free">Gratuit</div>
                    <div class="pricing-price">
                        <span class="price-amount">0 FCFA</span>
                        <span class="price-period">/ à vie</span>
                    </div>
                    <p class="pricing-desc">Idéal pour créer votre premier profil professionnel basique.</p>
                    <ul class="pricing-features">
                        <li class="feat-yes">✓ 1 Profil utilisateur</li>
                        <li class="feat-yes">✓ 1 CV (Modèles basiques)</li>
                        <li class="feat-yes">✓ 1 Portfolio simple</li>
                        <li class="feat-yes">✓ Export PDF (avec filigrane)</li>
                        <li class="feat-no">✗ Pas de personnalisation couleur</li>
                        <li class="feat-no">✗ Modèles Premium</li>
                        <li class="feat-no">✗ Domaine personnalisé</li>
                        <li class="feat-no">✗ Support prioritaire</li>
                    </ul>
                    <?php if ($planLevel == 1): ?>
                    <div class="pricing-action current-plan">✓ Votre offre actuelle</div>
                    <?php else: ?>
                    <a href="#" class="btn-secondary pricing-action disabled" style="cursor:not-allowed;">Plan inférieur</a>
                    <?php endif; ?>
                </div>

                <!-- Standard -->
                <div class="pricing-card <?php echo ($planLevel == 2) ? 'current-plan-card' : 'standard-card'; ?>">
                    <div class="pricing-badge standard">Standard 🚀</div>
                    <div class="pricing-price">
                        <span class="price-amount">3000 FCFA</span>
                        <span class="price-period">/ 30 jours</span>
                    </div>
                    <p class="pricing-desc">Pour les professionnels cherchant plus de personnalisation.</p>
                    <ul class="pricing-features">
                        <li class="feat-yes">✓ 1 Profil utilisateur</li>
                        <li class="feat-yes">✓ Jusqu'à 3 CVs (5 modèles)</li>
                        <li class="feat-yes">✓ Portfolio personnalisé (URL, couleurs)</li>
                        <li class="feat-yes">✓ Export PDF HD (sans filigrane)</li>
                        <li class="feat-yes">✓ Statistiques de base</li>
                        <li class="feat-yes">✓ Sauvegarde automatique</li>
                        <li class="feat-yes">✓ Support par email</li>
                        <li class="feat-yes">✓ Accès au Laboratoire IA ✨</li>
                        <li class="feat-no">✗ Création illimitée</li>
                        <li class="feat-no">✗ Domaine personnalisé (votre-nom.com)</li>
                    </ul>
                    <?php if ($planLevel == 2): ?>
                        <div class="pricing-action current-plan">✓ Votre offre actuelle</div>
                    <?php elseif ($planLevel > 2): ?>
                        <a href="#" class="btn-secondary pricing-action disabled" style="cursor:not-allowed;">Plan inférieur</a>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/dashboard/redeem.php" class="btn-primary pricing-action">
                            Utiliser un code Standard
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Premium -->
                <div class="pricing-card premium-card <?php echo ($planLevel == 3) ? 'current-plan-card' : ''; ?>">
                    <div class="pricing-badge premium">Premium ⭐</div>
                    <div class="pricing-price">
                        <span class="price-amount">5000 FCFA</span>
                        <span class="price-period">/ 30 jours</span>
                    </div>
                    <p class="pricing-desc">L'outil ultime avec toutes les fonctionnalités débloquées.</p>
                    <ul class="pricing-features">
                        <li class="feat-yes">✓ Tout ce qui est inclus dans Standard</li>
                        <li class="feat-yes">✓ Profils, CVs et Portfolios illimités</li>
                        <li class="feat-yes">✓ Accès au Laboratoire IA ✨</li>
                        <li class="feat-yes">✓ Tous les modèles (inclus les Premium)</li>
                        <li class="feat-yes">✓ Export Word & PDF optimisé ATS</li>
                        <li class="feat-yes">✓ Portfolio avancé (Animations, Blog, SEO)</li>
                        <li class="feat-yes">✓ Connexion domaine personnalisé</li>
                        <li class="feat-yes">✓ Statistiques avancées de trafic</li>
                        <li class="feat-yes">✓ Support prioritaire 24/7</li>
                        <li class="feat-yes">✓ Accès anticipé aux nouveautés</li>
                    </ul>
                    <?php if ($planLevel == 3): ?>
                    <div class="pricing-action current-plan">✓ Abonnement actif</div>
                    <?php else: ?>
                    <a href="<?= APP_URL ?>/dashboard/redeem.php" class="btn-primary pricing-action premium-btn">
                        Utiliser un code Premium
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="upgrade-faq">
                <h2>Foire Aux Questions</h2>
                <div class="faq-grid">
                    <div class="faq-item">
                        <h4>Puis-je annuler à tout moment ?</h4>
                        <p>Oui. Annulez à tout moment et conservez l'accès Premium jusqu'à la fin de votre période de facturation.</p>
                    </div>
                    <div class="faq-item">
                        <h4>Mes données sont-elles en sécurité si je rétrograde ?</h4>
                        <p>Absolument. Toutes vos données CV sont conservées. Vous perdrez simplement l'accès aux fonctionnalités Premium.</p>
                    </div>
                    <div class="faq-item">
                        <h4>Proposez-vous des remboursements ?</h4>
                        <p>Oui, nous offrons une garantie satisfait ou remboursé de 7 jours si vous n'êtes pas satisfait.</p>
                    </div>
                    <div class="faq-item">
                        <h4>Y a-t-il une réduction étudiant ?</h4>
                        <p>Oui – contactez-nous avec votre e-mail étudiant pour bénéficier de 50 % de réduction.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function goToRedeem() {
    window.location.href = '<?= APP_URL ?>/dashboard/redeem.php';
}
</script>

<style>
.upgrade-page { max-width: 1000px; margin: 0 auto; }

.upgrade-hero { text-align: center; margin-bottom: 3rem; }
.upgrade-hero-badge {
    display: inline-block; background: linear-gradient(135deg, var(--primary), var(--purple));
    color: white; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700;
    margin-bottom: 1rem;
}
.upgrade-hero h1 { font-size: 2.2rem; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 0.5rem; }
.upgrade-hero p  { color: var(--text-muted); font-size: 1rem; max-width: 550px; margin: 0 auto; }

.pricing-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 3rem; align-items: stretch;
}

.pricing-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg);
    padding: 2rem; display: flex; flex-direction: column; gap: 1rem; position: relative;
    transition: transform 0.2s, box-shadow 0.2s;
}
.pricing-card:hover { border-color: rgba(255,255,255,0.1); box-shadow: 0 10px 25px rgba(0,0,0,0.2); transform: translateY(-3px); }

.current-plan-card {
    border-color: rgba(255,255,255,0.15); border-width: 2px;
}

.standard-card {
    border-color: rgba(16, 185, 129, 0.3); /* green tint for Standard */
}
.standard-card:hover {
    border-color: var(--success);
    box-shadow: 0 0 0 1px var(--success), 0 20px 50px rgba(16,185,129,0.1);
}

.premium-card {
    border-color: var(--primary);
    box-shadow: 0 0 0 1px var(--primary), 0 20px 50px rgba(99,102,241,0.15);
}

.pricing-badge { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
.pricing-badge.premium { color: var(--primary); }
.pricing-badge.standard { color: var(--success); }
.pricing-badge.free    { color: var(--text-muted); }

.pricing-price { display: flex; align-items: baseline; gap: 0.3rem; }
.price-amount  { font-size: 2.3rem; font-weight: 800; letter-spacing: -1px; }
.price-period  { color: var(--text-muted); font-size: 0.85rem; }

.pricing-desc { color: var(--text-muted); font-size: 0.88rem; line-height: 1.6; min-height: 48px; }

.pricing-features { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; flex: 1; margin-bottom: 1.5rem; }
.pricing-features li { font-size: 0.85rem; display: flex; gap: 6px; align-items: flex-start; }
.feat-yes { color: var(--text); }
.feat-no  { color: var(--text-muted); opacity: 0.5; }

.pricing-action {
    display: block; text-align: center; padding: 0.75rem;
    border-radius: 8px; font-weight: 600; font-size: 0.9rem; text-decoration: none;
    margin-top: auto;
}
.current-plan { background: var(--surface-2); color: var(--text-muted); border-radius: 8px; cursor: default; }
.disabled { opacity: 0.5; cursor: not-allowed; }

.upgrade-faq h2 { font-size: 1.4rem; font-weight: 700; margin-bottom: 1.5rem; }
.faq-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
.faq-item { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 1.25rem; }
.faq-item h4 { font-size: 0.92rem; font-weight: 600; margin-bottom: 0.4rem; }
.faq-item p  { color: var(--text-muted); font-size: 0.85rem; line-height: 1.6; }

@media (max-width: 900px) {
    .pricing-grid { grid-template-columns: 1fr; }
    .faq-grid { grid-template-columns: 1fr; }
}
</style>
<?php include __DIR__ . '/../includes/foot.php'; ?>
