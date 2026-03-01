<?php
require_once __DIR__ . '/../includes/auth-check.php';

$userId = $_SESSION['user_id'];

// sections come from the global base tables
$education      = db()->fetchAll('SELECT * FROM education WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$experience     = db()->fetchAll('SELECT * FROM experience WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$skills         = db()->fetchAll('SELECT * FROM skills WHERE user_id = ? ORDER BY sort_order, id ASC', [$userId]) ?? [];
$projects       = db()->fetchAll('SELECT * FROM projects WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$languages      = db()->fetchAll('SELECT * FROM languages WHERE user_id = ? ORDER BY sort_order', [$userId]) ?? [];
$certifications = db()->fetchAll('SELECT * FROM certifications WHERE user_id = ? ORDER BY sort_order', [$userId]) ?? [];

// template may override profile
$cvTemplate = $profile['cv_template'] ?? 'minimal';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?= generateCsrfToken() ?>">
<script>
// Intercept fetch to automatically add CSRF token
const originalFetch = window.fetch;
window.fetch = async function(resource, config) {
    if (config && ['POST', 'PUT', 'DELETE'].includes(config.method?.toUpperCase())) {
        config.headers = {
            ...config.headers,
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        };
    }
    return originalFetch.call(window, resource, config);
};
</script>
<title>Créateur de CV – BUILD.CV</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/cv-builder.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" onclick="toggleSidebar()">☰</button>
                <div>
                    <h1>Créateur de CV</h1>
                    <p>Remplissez vos informations et regardez votre CV prendre vie</p>
                </div>
            </div>
            <div class="topbar-right">
                <span class="autosave-badge" id="autosaveBadge">● Enregistré</span>
                <?php if (($_SESSION['plan_level'] ?? 1) >= 3): ?>
                    <a href="<?= APP_URL ?>/api/export-word.php" class="topbar-btn topbar-btn-ghost" id="exportWordBtn" target="_blank">📄 Export Word</a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/dashboard/upgrade.php" class="topbar-btn topbar-btn-ghost" id="exportWordBtn" onclick="alert('L\'export Word est réservé aux abonnés Premium. Mettez à niveau votre abonnement !');">👑 Export Word</a>
                <?php endif; ?>
                <a href="<?= APP_URL ?>/api/generate-pdf.php" class="topbar-btn topbar-btn-ghost" id="downloadBtn" target="_blank">⬇ Télécharger le PDF</a>
                <button class="topbar-btn topbar-btn-primary" onclick="saveAll()">💾 Tout enregistrer</button>
            </div>
        </header>

        <div class="builder-layout">
            <!-- Panneau de formulaire / sections -->
            <div class="builder-form-panel">

                <!-- Onglets de section -->
                <div class="section-tabs">
                    <button class="section-tab active" onclick="showSection('personal')">👤 Infos perso</button>
                    <button class="section-tab" onclick="showSection('education')">🎓 Formation</button>
                    <button class="section-tab" onclick="showSection('experience')">💼 Expérience</button>
                    <button class="section-tab" onclick="showSection('skills')">⚡ Compétences</button>
                    <button class="section-tab" onclick="showSection('projects')">🚀 Projets</button>
                    <button class="section-tab" onclick="showSection('languages')">🌍 Langues</button>
                    <button class="section-tab" onclick="showSection('certifications')">📜 Certifs</button>
                </div>

                <!-- ── INFORMATIONS PERSONNELLES ───────────────── -->
                <div class="section-panel active" id="section-personal">
                    <div class="section-header">
                        <h3>Informations personnelles</h3>
                        <p>Vos coordonnées et informations professionnelles de base</p>
                    </div>
                    <form id="personalForm" class="cv-form">
                        <div class="photo-upload-area">
                            <div class="photo-preview" id="photoPreview">
                                <?php if ($profile && $profile['profile_photo']): ?>
                                    <img src="<?= UPLOAD_URL . $profile['profile_photo'] ?>" id="photoImg" alt="Photo" loading="lazy">
                                <?php else: ?>
                                    <div class="photo-placeholder" id="photoPlaceholder">
                                        <span>📷</span>
                                        <small>Cliquez pour télécharger</small>
                                    </div>
                                    <img id="photoImg" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:50%" loading="lazy">
                                <?php endif; ?>
                            </div>
                            <div class="photo-upload-info">
                                <strong>Photo de profil</strong>
                                <p>JPG, PNG ou WEBP. Max 5 Mo.</p>
                                <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none;">
                                <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('photoInput').click()">Télécharger une photo</button>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="fgroup">
                                <label>Nom complet *</label>
                                <input type="text" name="full_name" placeholder="Jean Dupont" value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>" required>
                            </div>
                            <div class="fgroup">
                                <label>Titre professionnel *</label>
                                <input type="text" name="title" placeholder="Développeur Web" value="<?= htmlspecialchars($profile['title'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="fgroup ai-field">
                            <label>Résumé professionnel</label>
                            <textarea name="summary" rows="5" placeholder="Parlez brièvement de vous..."><?= htmlspecialchars($profile['summary'] ?? '') ?></textarea>
                            <?php if (($_SESSION['plan_level'] ?? 1) >= 2): ?>
                                <div class="ai-btns">
                                    <button type="button" class="ai-btn" onclick="aiEnhance('summary', 'summary')">✨ Améliorer</button>
                                    <button type="button" class="ai-btn" style="background:#475569" onclick="aiEnhance('summary', 'tone_switch')">🎭 Changer le ton</button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="fgroup">
                            <label>Loisirs & Intérêts (Optionnel)</label>
                            <textarea name="hobbies" rows="3" placeholder="Voyages, Sport, Lecture..."><?= htmlspecialchars($profile['hobbies'] ?? '') ?></textarea>
                            <small>Séparez vos loisirs par des virgules ou mettez-les sous forme de liste.</small>
                        </div>

                        <div class="form-grid-2">
                            <div class="fgroup">
                                <label>E-mail</label>
                                <input type="email" name="email" placeholder="jean@exemple.com" value="<?= htmlspecialchars($profile['email'] ?? '') ?>">
                            </div>
                            <div class="fgroup">
                                <label>Téléphone</label>
                                <input type="tel" name="phone" placeholder="+33 6 12 34 56 78" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="fgroup">
                                <label>Localisation</label>
                                <input type="text" name="location" placeholder="Paris, France" value="<?= htmlspecialchars($profile['location'] ?? '') ?>">
                            </div>
                            <div class="fgroup">
                                <label>Site web</label>
                                <input type="url" name="website" placeholder="https://monsite.fr" value="<?= htmlspecialchars($profile['website'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="fgroup">
                                <label>URL LinkedIn</label>
                                <input type="url" name="linkedin" placeholder="https://linkedin.com/in/..." value="<?= htmlspecialchars($profile['linkedin'] ?? '') ?>">
                            </div>
                            <div class="fgroup">
                                <label>URL GitHub</label>
                                <input type="url" name="github" placeholder="https://github.com/..." value="<?= htmlspecialchars($profile['github'] ?? '') ?>">
                            </div>
                            <div class="fgroup">
                                <label>URL Instagram</label>
                                <input type="url" name="instagram" placeholder="https://instagram.com/..." value="<?= htmlspecialchars($profile['instagram'] ?? '') ?>">
                            </div>
                            <div class="fgroup">
                                <label>URL Twitter / X</label>
                                <input type="url" name="twitter" placeholder="https://twitter.com/..." value="<?= htmlspecialchars($profile['twitter'] ?? '') ?>">
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="savePersonal()">Enregistrer les infos perso</button>
                    </form>
                </div>

                <!-- ── FORMATION ──────────────────────────── -->
                <div class="section-panel" id="section-education">
                    <div class="section-header">
                        <h3>Formation</h3>
                        <p>Votre parcours académique</p>
                    </div>
                    <div id="educationList">
                        <?php foreach ($education as $edu): ?>
                        <div class="repeatable-item" data-id="<?= $edu['id'] ?>">
                            <div class="repeatable-header">
                                <strong><?= htmlspecialchars($edu['school'] ?? 'Formation') ?></strong>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem('education', <?= $edu['id'] ?>, this)">🗑</button>
                            </div>
                            <div class="form-grid-2">
                                <div class="fgroup"><label>École / Université</label>
                                    <input type="text" name="school" value="<?= htmlspecialchars($edu['school'] ?? '') ?>" placeholder="Université Paris">
                                </div>
                                <div class="fgroup"><label>Diplôme</label>
                                    <input type="text" name="degree" value="<?= htmlspecialchars($edu['degree'] ?? '') ?>" placeholder="Licence, Master...">
                                </div>
                            </div>
                            <div class="form-grid-3">
                                <div class="fgroup"><label>Domaine d'étude</label>
                                    <input type="text" name="field" value="<?= htmlspecialchars($edu['field'] ?? '') ?>" placeholder="Informatique">
                                </div>
                                <div class="fgroup"><label>Année de début</label>
                                    <input type="text" name="start_year" value="<?= htmlspecialchars($edu['start_year'] ?? '') ?>" placeholder="2018">
                                </div>
                                <div class="fgroup"><label>Année de fin</label>
                                    <input type="text" name="end_year" value="<?= htmlspecialchars($edu['end_year'] ?? '') ?>" placeholder="2022">
                                </div>
                            </div>
                            <div class="fgroup">
                                <label>Description (optionnel)</label>
                                <textarea name="description" rows="2" placeholder="Activités clés, mention, honeurs..."><?= htmlspecialchars($edu['description'] ?? '') ?></textarea>
                            </div>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="saveRepeatable('education', this)">Enregistrer</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-ghost" onclick="addEducation()">+ Ajouter une formation</button>
                </div>

                <!-- ── EXPÉRIENCE ─────────────────────────── -->
                <div class="section-panel" id="section-experience">
                    <div class="section-header">
                        <h3>Expérience professionnelle</h3>
                        <p>Votre historique professionnel</p>
                    </div>
                    <div id="experienceList">
                        <?php foreach ($experience as $exp): ?>
                        <div class="repeatable-item" data-id="<?= $exp['id'] ?>">
                            <div class="repeatable-header">
                                <strong><?= htmlspecialchars($exp['position'] ?? 'Expérience') ?></strong>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem('experience', <?= $exp['id'] ?>, this)">🗑</button>
                            </div>
                            <div class="form-grid-2">
                                <div class="fgroup"><label>Entreprise</label>
                                    <input type="text" name="company" value="<?= htmlspecialchars($exp['company'] ?? '') ?>" placeholder="Google">
                                </div>
                                <div class="fgroup"><label>Poste / Titre</label>
                                    <input type="text" name="position" value="<?= htmlspecialchars($exp['position'] ?? '') ?>" placeholder="Ingénieur logiciel">
                                </div>
                            </div>
                            <div class="form-grid-2">
                                <div class="fgroup"><label>Date de début</label>
                                    <input type="text" name="start_date" value="<?= htmlspecialchars($exp['start_date'] ?? '') ?>" placeholder="Jan. 2022">
                                </div>
                                <div class="fgroup"><label>Date de fin</label>
                                    <input type="text" name="end_date" value="<?= htmlspecialchars($exp['end_date'] ?? '') ?>" placeholder="Aujourd'hui">
                                </div>
                            </div>
                            <div class="fgroup ai-field">
                                <label>Description</label>
                                <textarea name="description" rows="3" placeholder="Décrivez vos responsabilités..."><?= htmlspecialchars($exp['description'] ?? '') ?></textarea>
                                <?php if (($_SESSION['plan_level'] ?? 1) >= 2): ?>
                                    <div class="ai-btns">
                                        <button type="button" class="ai-btn" onclick="aiEnhanceRepeatable('experience', this)">✨ Améliorer</button>
                                        <button type="button" class="ai-btn" style="background:#475569" onclick="aiEnhanceRepeatable('tone_switch', this)">🎭 Changer le ton</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="saveRepeatable('experience', this)">Enregistrer</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-ghost" onclick="addExperience()">+ Ajouter une expérience</button>
                </div>

                <!-- ── COMPÉTENCES ─────────────────────────────── -->
                <div class="section-panel" id="section-skills">
                    <div class="section-header">
                        <h3>Compétences</h3>
                        <p>Compétences techniques et interpersonnelles avec niveaux de maîtrise</p>
                    </div>
                    <div id="skillsList">
                        <?php foreach ($skills as $skill): ?>
                        <div class="skill-item" data-id="<?= $skill['id'] ?>">
                            <div class="skill-row">
                                <input type="text" name="skill_name" value="<?= htmlspecialchars($skill['skill_name']) ?>" placeholder="JavaScript" class="skill-name-input">
                                <div class="skill-level-wrap">
                                    <input type="range" name="skill_level" min="0" max="100" value="<?= $skill['skill_level'] ?>" class="skill-slider" oninput="updateSkillLabel(this)">
                                    <span class="skill-level-label"><?= $skill['skill_level'] ?>%</span>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem('skills', <?= $skill['id'] ?>, this)">🗑</button>
                                <button type="button" class="btn btn-ghost btn-sm" onclick="saveSkill(this)">✓</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-ghost" onclick="addSkill()">+ Ajouter une compétence</button>
                </div>

                <!-- ── PROJETS ───────────────────────────── -->
                <div class="section-panel" id="section-projects">
                    <div class="section-header">
                        <h3>Projets</h3>
                        <p>Mettez en valeur vos meilleures réalisations</p>
                    </div>
                    <div id="projectsList">
                        <?php foreach ($projects as $proj): ?>
                        <div class="repeatable-item" data-id="<?= $proj['id'] ?>">
                            <div class="repeatable-header">
                                <strong><?= htmlspecialchars($proj['title'] ?? 'Projet') ?></strong>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem('projects', <?= $proj['id'] ?>, this)">🗑</button>
                            </div>
                            <div class="form-grid-2">
                                <div class="fgroup"><label>Titre du projet</label>
                                    <input type="text" name="title" value="<?= htmlspecialchars($proj['title'] ?? '') ?>" placeholder="Application e-commerce">
                                </div>
                                <div class="fgroup"><label>Technologies</label>
                                    <input type="text" name="technologies" value="<?= htmlspecialchars($proj['technologies'] ?? '') ?>" placeholder="React, Node.js, MongoDB">
                                </div>
                            </div>
                            <div class="fgroup ai-field">
                                <label>Description</label>
                                <textarea name="description" rows="3" placeholder="À quoi sert ce projet ?"><?= htmlspecialchars($proj['description'] ?? '') ?></textarea>
                                <?php if (($_SESSION['plan_level'] ?? 1) >= 2): ?>
                                    <div class="ai-btns">
                                        <button type="button" class="ai-btn" onclick="aiEnhanceRepeatable('project', this)">✨ Améliorer</button>
                                        <button type="button" class="ai-btn" style="background:#475569" onclick="aiEnhanceRepeatable('tone_switch', this)">🎭 Changer le ton</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-grid-2">
                                <div class="fgroup"><label>URL en ligne</label>
                                    <input type="url" name="project_url" value="<?= htmlspecialchars($proj['project_url'] ?? '') ?>" placeholder="https://...">
                                </div>
                                <div class="fgroup"><label>URL GitHub</label>
                                    <input type="url" name="github_url" value="<?= htmlspecialchars($proj['github_url'] ?? '') ?>" placeholder="https://github.com/...">
                                </div>
                            </div>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="saveRepeatable('projects', this)">Enregistrer</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-ghost" onclick="addProject()">+ Ajouter un projet</button>
                </div>

                <!-- ── LANGUES ──────────────────────────── -->
                <div class="section-panel" id="section-languages">
                    <div class="section-header">
                        <h3>Langues</h3>
                        <p>Langues que vous parlez</p>
                    </div>
                    <div id="languagesList">
                        <?php foreach ($languages as $lang): ?>
                        <div class="language-item" data-id="<?= $lang['id'] ?>">
                            <div class="skill-row">
                                <input type="text" name="language_name" value="<?= htmlspecialchars($lang['language_name']) ?>" placeholder="Français" class="skill-name-input">
                                <select name="proficiency" class="lang-select">
                                    <?php foreach (['Débutant','Intermédiaire','Courant','Natif'] as $lvl): ?>
                                        <option value="<?= $lvl ?>" <?= $lang['proficiency'] === $lvl ? 'selected' : '' ?>><?= $lvl ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem('languages', <?= $lang['id'] ?>, this)">🗑</button>
                                <button type="button" class="btn btn-ghost btn-sm" onclick="saveLanguage(this)">✓</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-ghost" onclick="addLanguage()">+ Ajouter une langue</button>
                </div>

                <!-- ── CERTIFICATIONS ─────────────────────── -->
                <div class="section-panel" id="section-certifications">
                    <div class="section-header">
                        <h3>Certifications</h3>
                        <p>Certifications professionnelles et formations (optionnel)</p>
                    </div>
                    <div id="certificationsList">
                        <?php foreach ($certifications as $cert): ?>
                        <div class="repeatable-item" data-id="<?= $cert['id'] ?>">
                            <div class="repeatable-header">
                                <strong><?= htmlspecialchars($cert['cert_name'] ?? 'Certification') ?></strong>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem('certifications', <?= $cert['id'] ?>, this)">🗑</button>
                            </div>
                            <div class="form-grid-2">
                                <div class="fgroup"><label>Nom de la certification</label>
                                    <input type="text" name="cert_name" value="<?= htmlspecialchars($cert['cert_name'] ?? '') ?>" placeholder="AWS Certified Developer">
                                </div>
                                <div class="fgroup"><label>Organisme émetteur</label>
                                    <input type="text" name="issuer" value="<?= htmlspecialchars($cert['issuer'] ?? '') ?>" placeholder="Amazon Web Services">
                                </div>
                            </div>
                            <div class="form-grid-2">
                                <div class="fgroup"><label>Date d'obtention</label>
                                    <input type="text" name="issue_date" value="<?= htmlspecialchars($cert['issue_date'] ?? '') ?>" placeholder="Mars 2023">
                                </div>
                                <div class="fgroup"><label>URL du certificat</label>
                                    <input type="url" name="cert_url" value="<?= htmlspecialchars($cert['cert_url'] ?? '') ?>" placeholder="https://...">
                                </div>
                            </div>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="saveRepeatable('certifications', this)">Enregistrer</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-ghost" onclick="addCertification()">+ Ajouter une certification</button>
                </div>

            </div><!-- end builder-form-panel -->

            <!-- ── PANNEAU D'APERÇU EN DIRECT ─────────────────────── -->
            <div class="builder-preview-panel">
                <div class="preview-header">
                    <span>👁 Aperçu en direct</span>
                    <div style="display:flex;gap:0.5rem;align-items:center;">
                        <span style="font-size:0.75rem;color:var(--text-muted)">Modèle :</span>
                        <span style="font-size:0.8rem;font-weight:600;text-transform:capitalize;color:var(--primary)"><?= $cvTemplate ?></span>
                        <a href="<?= APP_URL ?>/dashboard/templates.php" class="btn btn-xs-ghost btn-xs">Changer</a>
                    </div>
                </div>
                <div class="preview-frame-wrap">
                    <iframe id="previewFrame" src="<?= APP_URL ?>/api/cv-preview.php" frameborder="0" class="preview-frame"></iframe>
                </div>
            </div>

        </div><!-- end builder-layout -->
    </div><!-- end main-content -->
</div>

<!-- Modal d'amélioration IA -->
<div class="modal-overlay" id="aiModal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <h3>✨ Amélioration par l'IA</h3>
            <button class="modal-close" onclick="closeAiModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="ai-loading" id="aiLoading">
                <div class="ai-spinner"></div>
                <p>Amélioration de votre texte par l'IA...</p>
            </div>
            <div id="aiResult" style="display:none;">
                <p style="margin-bottom:0.75rem;font-size:0.85rem;color:var(--text-muted)">Version améliorée par l'IA :</p>
                <div class="ai-result-box" id="aiResultText"></div>
                <div style="display:flex;gap:0.75rem;margin-top:1rem;">
                    <button class="btn btn-primary" onclick="applyAiResult()">✓ Utiliser ce texte</button>
                    <button class="btn btn-ghost" onclick="closeAiModal()">Annuler</button>
                    <button class="btn btn-ghost" onclick="regenerateAi()">↺ Régénérer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
const APP_URL = '<?= APP_URL ?>';
let currentAiTarget = null;
let currentAiType = null;

// ── Onglets de section ──────────────────────
function showSection(id) {
    document.querySelectorAll('.section-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.section-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('section-' + id).classList.add('active');
    event.target.classList.add('active');
}

// Gérer l'ancre de l'URL
if (window.location.hash) {
    const sec = window.location.hash.replace('#', '');
    const btn = document.querySelector(`.section-tab[onclick*="${sec}"]`);
    if (btn) btn.click();
}

// ── Sauvegarde automatique ──────────────────────────
let autosaveTimer;
document.addEventListener('input', () => {
    clearTimeout(autosaveTimer);
    document.getElementById('autosaveBadge').textContent = '● Enregistrement...';
    autosaveTimer = setTimeout(saveAll, 30000);
});

function saveAll() {
    savePersonal();
    updatePreview();
}

// ── Informations personnelles ─────────────────────
function savePersonal() {
    const form = document.getElementById('personalForm');
    const data = new FormData(form);
    const photoInput = document.getElementById('photoInput');
    if (photoInput.files[0]) data.append('photo', photoInput.files[0]);

    fetch(APP_URL + '/api/save-cv.php', {
        method: 'POST',
        body: data
    }).then(async r => {
        if (!r.ok) {
            console.error('HTTP Error:', r.status);
        }
        try {
            return await r.json();
        } catch (e) {
            const text = await r.clone().text();
            console.error('Invalid JSON response:', text);
            throw e;
        }
    }).then(res => {
        if (res.success) {
            showToast('Infos personnelles enregistrées !', 'success');
            document.getElementById('autosaveBadge').textContent = '● Enregistré';
            updatePreview();
        } else {
            showToast(res.error || 'Échec de l\'enregistrement', 'error');
        }
    }).catch(e => {
        console.error('Save error:', e);
        showToast('Erreur réseau', 'error');
    });
}

// Aperçu de la photo
document.getElementById('photoInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        const img = document.getElementById('photoImg');
        img.src = e.target.result;
        img.style.display = 'block';
        const ph = document.getElementById('photoPlaceholder');
        if (ph) ph.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

// ── Éléments répétables ──────────────────
function saveRepeatable(type, btn) {
    const item = btn.closest('.repeatable-item');
    const id   = item.dataset.id;
    const data = {};
    item.querySelectorAll('input,textarea,select').forEach(f => {
        if (f.name) data[f.name] = f.value;
    });
    data.id   = id;
    data.type = type;

    fetch(APP_URL + '/api/save-section.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    }).then(async r => {
        if (!r.ok) console.error('HTTP Error in save-section:', r.status);
        try { return await r.json(); } 
        catch (e) { console.error('Invalid JSON from save-section:', await r.clone().text()); throw e; }
    }).then(res => {
        if (res.success) {
            showToast('Enregistré !', 'success');
            if (res.id) item.dataset.id = res.id;
            const title = item.querySelector('.repeatable-header strong');
            if (title) title.textContent = data.company || data.school || data.title || data.cert_name || 'Entrée';
            updatePreview();
        } else showToast(res.error || 'Erreur', 'error');
    }).catch(e => {
        console.error('Save section error:', e);
        showToast('Erreur réseau', 'error');
    });
}

function deleteItem(type, id, btn) {
    if (!confirm('Supprimer cette entrée ?')) return;
    const payload = {type, id};
    fetch(APP_URL + '/api/delete-section.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    }).then(r => r.json()).then(res => {
        if (res.success) {
            btn.closest('.repeatable-item, .skill-item, .language-item').remove();
            showToast('Supprimé !', 'success');
            updatePreview();
        }
    });
}

// ── Compétences ────────────────────────────
function updateSkillLabel(el) {
    el.nextElementSibling.textContent = el.value + '%';
}

function saveSkill(btn) {
    const item = btn.closest('.skill-item');
    const id   = item.dataset.id;
    const data = {
        id, type: 'skills',
        skill_name: item.querySelector('[name=skill_name]').value,
        skill_level: item.querySelector('[name=skill_level]').value
    };
    fetch(APP_URL + '/api/save-section.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    }).then(r => r.json()).then(res => {
        if (res.success) {
            showToast('Compétence enregistrée !', 'success');
            if (res.id) item.dataset.id = res.id;
            updatePreview();
        }
    });
}

function saveLanguage(btn) {
    const item = btn.closest('.language-item');
    const id   = item.dataset.id;
    const data = {
        id, type: 'languages',
        language_name: item.querySelector('[name=language_name]').value,
        proficiency: item.querySelector('[name=proficiency]').value
    };
    fetch(APP_URL + '/api/save-section.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    }).then(r => r.json()).then(res => {
        if (res.success) {
            showToast('Langue enregistrée !', 'success');
            if (res.id) item.dataset.id = res.id;
            updatePreview();
        }
    });
}

// ── Ajouter de nouveaux éléments ──────────────────── 
function makeRepeatableItem(type, innerHtml) {
    const div = document.createElement('div');
    div.className = 'repeatable-item';
    div.dataset.id = 'new';
    div.innerHTML = innerHtml;
    return div;
}

function addEducation() {
    const list = document.getElementById('educationList');
    const item = makeRepeatableItem('education', `
        <div class="repeatable-header"><strong>Nouvelle formation</strong>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.repeatable-item').remove()">🗑</button></div>
        <div class="form-grid-2">
            <div class="fgroup"><label>École</label><input type="text" name="school" placeholder="Nom de l'université"></div>
            <div class="fgroup"><label>Diplôme</label><input type="text" name="degree" placeholder="Licence, Master..."></div>
        </div>
        <div class="form-grid-3">
            <div class="fgroup"><label>Domaine</label><input type="text" name="field" placeholder="Informatique"></div>
            <div class="fgroup"><label>Début</label><input type="text" name="start_year" placeholder="2018"></div>
            <div class="fgroup"><label>Fin</label><input type="text" name="end_year" placeholder="2022"></div>
        </div>
        <div class="fgroup"><label>Description</label><textarea name="description" rows="2"></textarea></div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="saveRepeatable('education', this)">Enregistrer</button>
    `);
    list.appendChild(item);
    item.scrollIntoView({behavior:'smooth'});
}

function addExperience() {
    const list = document.getElementById('experienceList');
    const item = makeRepeatableItem('experience', `
        <div class="repeatable-header"><strong>Nouvelle expérience</strong>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.repeatable-item').remove()">🗑</button></div>
        <div class="form-grid-2">
            <div class="fgroup"><label>Entreprise</label><input type="text" name="company" placeholder="Nom de l'entreprise"></div>
            <div class="fgroup"><label>Poste</label><input type="text" name="position" placeholder="Votre poste"></div>
        </div>
        <div class="form-grid-2">
            <div class="fgroup"><label>Date de début</label><input type="text" name="start_date" placeholder="Jan. 2022"></div>
            <div class="fgroup"><label>Date de fin</label><input type="text" name="end_date" placeholder="Aujourd'hui"></div>
        </div>
        <div class="fgroup ai-field">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Vos responsabilités..."></textarea>
            <?php if (($_SESSION['plan_level'] ?? 1) >= 2): ?>
                <div class="ai-btns">
                    <button type="button" class="ai-btn" onclick="aiEnhanceRepeatable('experience', this)">✨ Améliorer</button>
                    <button type="button" class="ai-btn" style="background:#475569" onclick="aiEnhanceRepeatable('tone_switch', this)">🎭 Changer le ton</button>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="saveRepeatable('experience', this)">Enregistrer</button>
    `);
    list.appendChild(item);
    item.scrollIntoView({behavior:'smooth'});
}

function addSkill() {
    const list = document.getElementById('skillsList');
    const div = document.createElement('div');
    div.className = 'skill-item';
    div.dataset.id = 'new';
    div.innerHTML = `<div class="skill-row">
        <input type="text" name="skill_name" placeholder="Nom de la compétence" class="skill-name-input">
        <div class="skill-level-wrap">
            <input type="range" name="skill_level" min="0" max="100" value="75" class="skill-slider" oninput="updateSkillLabel(this)">
            <span class="skill-level-label">75%</span>
        </div>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.skill-item').remove()">🗑</button>
        <button type="button" class="btn btn-ghost btn-sm" onclick="saveSkill(this)">✓</button>
    </div>`;
    list.appendChild(div);
    div.querySelector('input[type=text]').focus();
}

function addProject() {
    const list = document.getElementById('projectsList');
    const item = makeRepeatableItem('projects', `
        <div class="repeatable-header"><strong>Nouveau projet</strong>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.repeatable-item').remove()">🗑</button></div>
        <div class="form-grid-2">
            <div class="fgroup"><label>Titre du projet</label><input type="text" name="title" placeholder="Mon Projet"></div>
            <div class="fgroup"><label>Technologies</label><input type="text" name="technologies" placeholder="React, Node.js"></div>
        </div>
        <div class="fgroup ai-field">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="À quoi sert ce projet ?"></textarea>
            <?php if (($_SESSION['plan_level'] ?? 1) >= 2): ?>
                <div class="ai-btns">
                    <button type="button" class="ai-btn" onclick="aiEnhanceRepeatable('project', this)">✨ Améliorer</button>
                    <button type="button" class="ai-btn" style="background:#475569" onclick="aiEnhanceRepeatable('tone_switch', this)">🎭 Changer le ton</button>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-grid-2">
            <div class="fgroup"><label>URL en ligne</label><input type="url" name="project_url" placeholder="https://..."></div>
            <div class="fgroup"><label>URL GitHub</label><input type="url" name="github_url" placeholder="https://github.com/..."></div>
        </div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="saveRepeatable('projects', this)">Enregistrer</button>
    `);
    list.appendChild(item);
    item.scrollIntoView({behavior:'smooth'});
}

function addLanguage() {
    const list = document.getElementById('languagesList');
    const div = document.createElement('div');
    div.className = 'language-item';
    div.dataset.id = 'new';
    div.innerHTML = `<div class="skill-row">
        <input type="text" name="language_name" placeholder="Langue" class="skill-name-input">
        <select name="proficiency" class="lang-select">
            <option>Débutant</option><option>Intermédiaire</option><option selected>Courant</option><option>Natif</option>
        </select>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.language-item').remove()">🗑</button>
        <button type="button" class="btn btn-ghost btn-sm" onclick="saveLanguage(this)">✓</button>
    </div>`;
    list.appendChild(div);
    div.querySelector('input').focus();
}

function addCertification() {
    const list = document.getElementById('certificationsList');
    const item = makeRepeatableItem('certifications', `
        <div class="repeatable-header"><strong>Nouvelle certification</strong>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.repeatable-item').remove()">🗑</button></div>
        <div class="form-grid-2">
            <div class="fgroup"><label>Nom</label><input type="text" name="cert_name" placeholder="AWS Certified Developer"></div>
            <div class="fgroup"><label>Organisme</label><input type="text" name="issuer" placeholder="Amazon Web Services"></div>
        </div>
        <div class="form-grid-2">
            <div class="fgroup"><label>Date d'obtention</label><input type="text" name="issue_date" placeholder="Mars 2023"></div>
            <div class="fgroup"><label>URL</label><input type="url" name="cert_url" placeholder="https://..."></div>
        </div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="saveRepeatable('certifications', this)">Enregistrer</button>
    `);
    list.appendChild(item);
    item.scrollIntoView({behavior:'smooth'});
}

// ── Amélioration IA ────────────────────
const userPlanLevel = <?= $_SESSION['plan_level'] ?? 1 ?>;

function aiEnhance(fieldName, type) {
    if (userPlanLevel < 2) {
        alert('✨ L\'intelligence artificielle est réservée aux abonnés Premium. Mettez à niveau votre abonnement pour débloquer cette fonctionnalité magique !');
        return;
    }
    
    const field = document.querySelector(`[name="${fieldName}"]`);
    if (!field) return;
    if (!field.value.trim()) {
        showToast('Veuillez d\'abord rédiger quelques mots pour que l\'IA puisse les améliorer.', 'info');
        field.focus();
        return;
    }
    currentAiTarget = field;
    currentAiType   = type;
    openAiModal(field.value, type);
}

function aiEnhanceRepeatable(type, btn) {
    if (userPlanLevel < 2) {
        alert('✨ L\'intelligence artificielle est réservée aux abonnés Premium. Mettez à niveau votre abonnement pour débloquer cette fonctionnalité magique !');
        return;
    }
    const item = btn.closest('.repeatable-item');
    const field = item.querySelector('[name="description"]');
    if (!field.value.trim()) {
        showToast('Veuillez d\'abord rédiger quelques mots pour que l\'IA puisse les améliorer.', 'info');
        field.focus();
        return;
    }
    currentAiTarget = field;
    currentAiType = type;
    openAiModal(field.value, type);
}

function openAiModal(text, type) {
    document.getElementById('aiModal').style.display = 'flex';
    document.getElementById('aiLoading').style.display = 'block';
    document.getElementById('aiResult').style.display = 'none';
    callAiApi(text, type);
}

function callAiApi(text, type) {
    fetch(APP_URL + '/api/ai-enhance.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({text, type})
    }).then(r => r.json()).then(res => {
        document.getElementById('aiLoading').style.display = 'none';
        document.getElementById('aiResult').style.display = 'block';
        document.getElementById('aiResultText').textContent = res.enhanced || res.error || 'Aucun résultat';
    }).catch(() => {
        document.getElementById('aiLoading').style.display = 'none';
        document.getElementById('aiResult').style.display = 'block';
        document.getElementById('aiResultText').textContent = 'Impossible de se connecter au service IA. Vérifiez votre clé API.';
    });
}

function applyAiResult() {
    if (currentAiTarget) {
        currentAiTarget.value = document.getElementById('aiResultText').textContent;
    }
    closeAiModal();
}

function regenerateAi() {
    if (!currentAiTarget) return;
    document.getElementById('aiLoading').style.display = 'block';
    document.getElementById('aiResult').style.display = 'none';
    callAiApi(currentAiTarget.value, currentAiType);
}

function closeAiModal() {
    document.getElementById('aiModal').style.display = 'none';
}

// ── Aperçu en direct ──────────────────────
function updatePreview() {
    const frame = document.getElementById('previewFrame');
    let url = APP_URL + '/api/cv-preview.php';
    url += '?t=' + Date.now();
    frame.src = url;
}

// ── Notifications toast ─────────────────────────────
function showToast(msg, type = 'info') {
    const c = document.getElementById('toastContainer');
    const t = document.createElement('div');
    t.className = `toast toast-${type}`;
    t.textContent = msg;
    c.appendChild(t);
    setTimeout(() => t.remove(), 3500);
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}
</script>
</body>
</html>
