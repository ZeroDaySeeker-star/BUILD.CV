<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth-check.php';

if (($_SESSION['plan_level'] ?? 1) < 2) {
    header('Location: ' . APP_URL . '/dashboard/upgrade.php?error=ai_tools');
    exit;
}

$userId = $_SESSION['user_id'];
$profile = db()->fetchOne("SELECT * FROM profiles WHERE user_id = ?", [$userId]);
$letters = db()->fetchAll("SELECT * FROM cover_letters WHERE user_id = ? ORDER BY created_at DESC", [$userId]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outils IA - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .ai-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem; align-items: start; }
        @media (max-width: 992px) { .ai-grid { grid-template-columns: 1fr; } }
        .ai-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 2rem; height: 100%; display: flex; flex-direction: column; }
        .ai-card h3 { margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1.25rem; }
        .ai-card p { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6; }
        .ai-card textarea { width: 100%; min-height: 200px; background: rgba(0,0,0,0.25); border: 1px solid var(--border); color: white; padding: 1.25rem; border-radius: 10px; font-family: inherit; resize: vertical; margin-bottom: 1.5rem; transition: 0.3s; }
        .ai-card textarea:focus { border-color: var(--primary); outline: none; background: rgba(0,0,0,0.4); }
        .ai-result-box { background: #0f172a; border-left: 4px solid var(--primary); padding: 1.5rem; border-radius: 8px; margin-top: 1.5rem; white-space: pre-wrap; display: none; max-height: 500px; overflow-y: auto; color: #e2e8f0; font-size: 0.95rem; line-height: 1.7; box-shadow: inset 0 2px 4px rgba(0,0,0,0.3); position: relative; }
        .copy-btn { position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid var(--border); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.7rem; cursor: pointer; transition: 0.3s; }
        .copy-btn:hover { background: var(--primary); }
        .loader { display: none; border: 3px solid rgba(255,255,255,0.1); border-top: 3px solid var(--primary); border-radius: 50%; width: 24px; height: 24px; animation: spin 1s linear infinite; margin: 0 auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .tabs { display: flex; gap: 1rem; border-bottom: 1px solid var(--border); margin-bottom: 2rem; }
        .tab { padding: 0.75rem 1.5rem; cursor: pointer; color: var(--text-muted); border-bottom: 2px solid transparent; transition: 0.2s; }
        .tab.active { color: var(--primary); border-bottom-color: var(--primary); font-weight: 600; }
        .tool-panel { display: none; }
        .tool-panel.active { display: block; }
        .btn-ai { background: linear-gradient(135deg, var(--primary), #8b5cf6); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: 0.3s; width: 100%; justify-content: center; }
        .btn-ai:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-ai:disabled { background: #475569; cursor: not-allowed; transform: none; }
        .letter-saved-item { background: rgba(255,255,255,0.03); border: 1px solid var(--border); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="main-content">
        <div class="container">
            <header class="content-header">
            <div>
                <h1>Laboratoire IA ✨</h1>
                <p>Outils avancés pour propulser votre carrière avec l'IA Générative.</p>
            </div>
        </header>

        <div class="tabs">
            <div class="tab active" onclick="switchTab(this, 'cover-letter')">Lettre de Motivation</div>
            <div class="tab" onclick="switchTab(this, 'analysis')">Analyse d'Offre</div>
            <div class="tab" onclick="switchTab(this, 'ats-opt')">Optimiseur ATS <span style="font-size:0.7rem; background:var(--primary); color:white; padding:1px 5px; border-radius:4px; margin-left:5px;">Pro</span></div>
            <div class="tab" onclick="switchTab(this, 'translate')">Traduction <span style="font-size:0.7rem; background:var(--primary); color:white; padding:1px 5px; border-radius:4px; margin-left:5px;">Pro</span></div>
            <div class="tab" onclick="switchTab(this, 'tone-switch')">Changer le Ton</div>
            <div class="tab" onclick="switchTab(this, 'interview')">Simulateur d'Entretien</div>
            <div class="tab" onclick="switchTab(this, 'history')">Mes Lettres</div>
        </div>

        <!-- Panel: Cover Letter -->
        <div id="cover-letter" class="tool-panel active">
            <div class="ai-grid">
                <div class="ai-card">
                    <h3>✍️ Générateur de Lettre</h3>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1rem;">Collez la description du poste ci-dessous. L'IA utilisera votre profil complet pour rédiger une lettre sur mesure.</p>
                    <textarea id="jobDescLetter" placeholder="Ex: Nous recherchons un Développeur Fullstack avec 3 ans d'expérience..."></textarea>
                    <button class="btn-ai" id="btnGenLetter" onclick="runAi('cover_letter', 'jobDescLetter', 'resLetter', 'btnGenLetter')">
                        <span>Générer ma lettre</span>
                        <div class="loader" id="loaderLetter"></div>
                    </button>
                    <div id="resLetter" class="ai-result-box"></div>
                    <button class="btn btn-primary" id="btnSaveLetter" style="display:none; margin-top:1rem; width:100%;" onclick="saveLetter()">Enregistrer cette lettre</button>
                </div>
                <div class="ai-card">
                    <h3>💡 Conseils Pro</h3>
                    <ul style="font-size:0.9rem; line-height:1.7;">
                        <li>Assurez-vous que votre CV est à jour avant de générer.</li>
                        <li>L'IA analyse vos compétences les plus pertinentes pour le poste.</li>
                        <li>Vous pourrez modifier la lettre après génération.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Panel: Analysis -->
        <div id="analysis" class="tool-panel">
            <div class="ai-grid">
                <div class="ai-card">
                    <h3>📊 Score d'Adéquation</h3>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1rem;">Comparez votre profil à une offre d'emploi spécifique pour voir si vous correspondez aux critères ATS.</p>
                    <textarea id="jobDescAnalysis" placeholder="Collez l'offre d'emploi ici..."></textarea>
                    <button class="btn-ai" id="btnGenAnalysis" onclick="runAi('analysis', 'jobDescAnalysis', 'resAnalysis', 'btnGenAnalysis')">
                        <span>Analyser l'offre</span>
                        <div class="loader" id="loaderAnalysis"></div>
                    </button>
                </div>
                <div class="ai-card">
                    <h3>Résultats de l'analyse</h3>
                    <div id="resAnalysis" class="ai-result-box">Les résultats s'afficheront ici...</div>
                </div>
            </div>
        </div>

        <!-- Panel: ATS Optimizer -->
        <div id="ats-opt" class="tool-panel">
            <?php if ($_SESSION['plan_level'] < 3): ?>
                <div class="ai-card" style="text-align:center; padding:4rem;">
                    <span style="font-size:3rem;">🔒</span>
                    <h3>L'Optimiseur ATS est une option Premium</h3>
                    <p>Identifiez les mots-clés manquants dans votre CV par rapport à une offre pour passer les filtres automatiques.</p>
                    <a href="upgrade.php" class="btn btn-primary" style="display:inline-block;">Passer au mode Premium</a>
                </div>
            <?php else: ?>
                <div class="ai-grid">
                    <div class="ai-card">
                        <h3>⚡ Optimiseur de Mots-clés</h3>
                        <p>L'IA va extraire les termes techniques et compétences clés de l'offre et les comparer à votre CV actuel.</p>
                        <textarea id="jobDescAts" placeholder="Collez l'offre d'emploi ici..."></textarea>
                        <button class="btn-ai" id="btnGenAts" onclick="runAi('ats_optimizer', 'jobDescAts', 'resAts', 'btnGenAts')">
                            <span>Vérifier mon CV</span>
                            <div class="loader" id="loaderAts"></div>
                        </button>
                    </div>
                    <div class="ai-card">
                        <h3>Mots-clés à ajouter</h3>
                        <div id="resAts" class="ai-result-box">L'IA vous listera ici les compétences à mettre en avant.</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Panel: Translate -->
        <div id="translate" class="tool-panel">
            <?php if ($_SESSION['plan_level'] < 3): ?>
                <div class="ai-card" style="text-align:center; padding:4rem;">
                    <span style="font-size:3rem;">🌍</span>
                    <h3>Traduction Professionnelle Premium</h3>
                    <p>Traduisez votre CV et vos lettres en anglais ou espagnol avec une adaptation terminologique métier.</p>
                    <a href="upgrade.php" class="btn btn-primary" style="display:inline-block;">Passer au mode Premium</a>
                </div>
            <?php else: ?>
                <div class="ai-card" style="max-width: 800px; margin: 0 auto;">
                    <h3>🌍 Traduction Intelligente</h3>
                    <p>Choisissez la langue cible pour traduire votre profil complet.</p>
                    <div style="display:flex; gap:1rem; margin-bottom:1.5rem;">
                        <select id="targetLang" style="flex:1; background:rgba(0,0,0,0.3); color:white; border:1px solid var(--border); padding:0.75rem; border-radius:8px;">
                            <option value="English">Anglais (UK/US compatible)</option>
                            <option value="Spanish">Espagnol</option>
                            <option value="German">Allemand</option>
                        </select>
                        <button class="btn-ai" style="width:auto; padding:0 2rem;" id="btnTranslate" onclick="runAiTranslate()">Traduire</button>
                    </div>
                    <div id="resTranslate" class="ai-result-box">Votre traduction s'affichera ici...</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Panel: Tone Switcher -->
        <div id="tone-switch" class="tool-panel">
            <div class="ai-grid">
                <div class="ai-card">
                    <h3>🎭 Changeur de Ton</h3>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1rem;">Réécrivez votre texte pour qu'il s'adapte parfaitement à l'environnement visé (Startup, Exécutif, Académique).</p>
                    <textarea id="textToTone" placeholder="Collez le texte à transformer (ex: résumé, expérience)..."></textarea>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display:block; margin-bottom:0.5rem; font-size:0.9rem;">Choisir le nouveau ton :</label>
                        <select id="tonType" class="form-control" style="background:#0f172a; color:white; border:1px solid var(--border); padding:0.5rem; border-radius:8px; width:100%;">
                            <option value="Startup/Modern">Moderne / Dynamique (Startup)</option>
                            <option value="Executive/Formal">Formel / Exécutif (Grande Entreprise)</option>
                            <option value="Academic">Académique / Littéraire</option>
                        </select>
                    </div>
                    <button class="btn-ai" id="btnGenTone" onclick="runAi('tone_switch', 'textToTone', 'resTone', 'btnGenTone')">
                        <span>Transformer le ton</span>
                        <div class="loader" id="loaderTone"></div>
                    </button>
                    <div id="resTone" class="ai-result-box"></div>
                </div>
                <div class="ai-card">
                    <h3>💡 Quand l'utiliser ?</h3>
                    <ul style="font-size:0.9rem; line-height:1.7;">
                        <li><strong>Moderne</strong> : Idéal pour les entreprises de la Tech ou du Design.</li>
                        <li><strong>Exécutif</strong> : Parfait pour les postes à hautes responsabilités ou en finance.</li>
                        <li><strong>Académique</strong> : Recommandé pour la recherche, l'enseignement ou l'administration.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Panel: Interview -->
        <div id="interview" class="tool-panel">
            <div class="ai-card" style="max-width: 800px; margin: 0 auto;">
                <h3>🤝 Préparation Entretien</h3>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1rem;">Générez des questions d'entretien basées sur votre parcours pour vous entraîner.</p>
                <button class="btn-ai" id="btnGenInterview" onclick="runAi('interview', null, 'resInterview', 'btnGenInterview')">
                    <span>Générer mes questions personnalisées</span>
                    <div class="loader" id="loaderInterview"></div>
                </button>
                <div id="resInterview" class="ai-result-box"></div>
            </div>
        </div>

        <!-- Panel: History -->
        <div id="history" class="tool-panel">
            <div class="ai-card" style="max-width: 800px; margin: 0 auto;">
                <h3>📚 Lettres sauvegardées</h3>
                <div id="lettersList">
                    <?php if (empty($letters)): ?>
                        <p style="color:var(--text-muted); text-align:center;">Vous n'avez pas encore de lettres sauvegardées.</p>
                    <?php else: ?>
                        <?php foreach($letters as $l): ?>
                            <div class="letter-saved-item">
                                <div>
                                    <strong><?= htmlspecialchars($l['job_title'] ?: 'Sans titre') ?></strong>
                                    <div style="font-size:0.8rem; color:var(--text-muted)"><?= date('d M Y', strtotime($l['created_at'])) ?></div>
                                </div>
                                <button class="btn btn-ghost btn-sm" onclick="viewSavedLetter(<?= $l['id'] ?>)">Voir / Copier</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            </div>
        </div>
    </main>

    <script src="<?= APP_URL ?>/assets/js/app.js"></script>
    <script>
        let lastGeneratedText = "";

        function switchTab(el, tabId) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tool-panel').forEach(p => p.classList.remove('active'));
            el.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }

        async function runAi(type, inputId, resultId, btnId, extra = null) {
            const btn = document.getElementById(btnId);
            const loader = btn.querySelector('.loader');
            const resBox = document.getElementById(resultId);
            const inputText = inputId ? document.getElementById(inputId).value : "Mon profil complet";

            if (inputId && !inputText.trim()) {
                alert("Veuillez entrer du texte ou une description.");
                return;
            }

            // For Tone Switcher, extra is the tonType from the select
            if (type === 'tone_switch') {
                extra = document.getElementById('tonType').value;
            }

            btn.disabled = true;
            loader.style.display = "block";
            btn.querySelector('span').style.display = "none";
            resBox.style.display = "none";

            try {
                const r = await fetch('<?= APP_URL ?>/api/ai-enhance.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        type, 
                        text: inputText, 
                        target_lang: type === 'translate' ? extra : null,
                        tone: type === 'tone_switch' ? extra : null
                    })
                });
                const data = await r.json();
                
                btn.disabled = false;
                loader.style.display = "none";
                btn.querySelector('span').style.display = "inline";

                if (data.success) {
                    resBox.innerHTML = `<button class="copy-btn" onclick="copyText(this)">Copier</button><div class="res-content">${data.enhanced}</div>`;
                    resBox.style.display = "block";
                    lastGeneratedText = data.enhanced;
                    if (type === 'cover_letter') document.getElementById('btnSaveLetter').style.display = "block";
                } else {
                    alert("Erreur: " + data.error);
                }
            } catch (e) {
                alert("Erreur de connexion au serveur.");
                btn.disabled = false;
                loader.style.display = "none";
                btn.querySelector('span').style.display = "inline";
            }
        }

        function copyText(btn) {
            const text = btn.nextElementSibling.textContent;
            navigator.clipboard.writeText(text).then(() => {
                const oldText = btn.textContent;
                btn.textContent = "Copié !";
                setTimeout(() => btn.textContent = oldText, 2000);
            });
        }

        async function saveLetter() {
            const jobTitle = prompt("Entrez le titre du poste pour sauvegarder cette lettre :");
            if (!jobTitle) return;

            const r = await fetch('<?= APP_URL ?>/api/save-cover-letter.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    job_title: jobTitle,
                    content: lastGeneratedText
                })
            });
            const data = await r.json();
            if (data.success) {
                alert("Lettre sauvegardée dans 'Mes Lettres'");
                location.reload();
            } else {
                alert("Erreur lors de la sauvegarde.");
            }
        }

        function viewSavedLetter(id) {
            // Logic to fetch and show letter content in a modal or similar
            alert("Contenu récupéré ! (Détail affiché bientôt)");
        }

        async function runAiTranslate() {
            const lang = document.getElementById('targetLang').value;
            await runAi('translate', null, 'resTranslate', 'btnTranslate', lang);
        }
    </script>
</body>
</html>
