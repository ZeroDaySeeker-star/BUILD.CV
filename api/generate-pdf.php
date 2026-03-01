<?php
// API : Générer et télécharger le CV en PDF via DomPDF
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/auth/login.php'); exit;
}

$autoload = __DIR__ . '/../vendor/autoload.php';

// Charger l'autoload Composer en haut de fichier (use statements doivent être globaux)
if (file_exists($autoload)) {
    require_once $autoload;
}

$userId         = $_SESSION['user_id'];
$profile        = db()->fetchOne('SELECT * FROM profiles WHERE user_id = ?', [$userId]) ?? [];
$education      = db()->fetchAll('SELECT * FROM education WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$experience     = db()->fetchAll('SELECT * FROM experience WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$skills         = db()->fetchAll('SELECT * FROM skills WHERE user_id = ? ORDER BY sort_order', [$userId]) ?? [];
$projects       = db()->fetchAll('SELECT * FROM projects WHERE user_id = ? ORDER BY sort_order, id DESC', [$userId]) ?? [];
$languages      = db()->fetchAll('SELECT * FROM languages WHERE user_id = ? ORDER BY sort_order', [$userId]) ?? [];
$certifications = db()->fetchAll('SELECT * FROM certifications WHERE user_id = ? ORDER BY sort_order', [$userId]) ?? [];

$template     = $profile['cv_template'] ?? 'minimal';
$templateFile = __DIR__ . '/../templates/cv/' . preg_replace('/[^a-z0-9_]/', '', $template) . '.php';
if (!file_exists($templateFile)) {
    $templateFile = __DIR__ . '/../templates/cv/minimal.php';
}

// Capturer le HTML du template CV
// Nous formatons les variables avant de les injecter
$profile['summary'] = parse_markdown_to_html($profile['summary'] ?? '');
foreach ($experience as &$exp) {
    if (!empty($exp['description'])) {
        $exp['description'] = parse_markdown_to_html($exp['description']);
    }
}
unset($exp);
foreach ($education as &$edu) {
    if (!empty($edu['description'])) {
        $edu['description'] = parse_markdown_to_html($edu['description']);
    }
}
unset($edu);
foreach ($projects as &$proj) {
    if (!empty($proj['description'])) {
        $proj['description'] = parse_markdown_to_html($proj['description']);
    }
}
unset($proj);

ob_start();
include $templateFile;
$html = ob_get_clean();

// Add Watermark if Free Plan (plan_level < 2)
if (($_SESSION['plan_level'] ?? 1) < 2) {
    $watermark = '<div style="position:fixed; bottom:10px; right:10px; font-size:12px; color:rgba(0,0,0,0.5); z-index:9999; background:rgba(255,255,255,0.8); padding:5px 10px; border-radius:4px;">Généré avec BUILD.CV - Passez Premium pour supprimer</div>';
    $html = str_replace('</body>', $watermark . '</body>', $html);
}

// Nom du fichier téléchargé
$filename = 'CV_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $profile['full_name'] ?? 'cv') . '_' . date('Y') . '.pdf';

// ── DomPDF (serveur) ──────────────────────────────────────
if (file_exists($autoload) && class_exists('Dompdf\Dompdf')) {

    // Corrections CSS compatibles DomPDF (pas de grid, pas de flex, pas de table-cell sur div)
    $dompdfCss = '<style id="dompdf-fix">
body, * { font-family: DejaVu Sans, Arial, sans-serif !important; }

/* Header : photo flottante + texte normal */
.cv-header { display: block !important; overflow: hidden !important; }
.cv-photo { float: left !important; width: 80px !important; height: 80px !important; margin-right: 20px !important; border-radius: 40px !important; }
.cv-header-text { display: block !important; overflow: hidden !important; }

/* Contacts en ligne */
.cv-contact { display: block !important; }
.cv-contact span { display: inline !important; margin-right: 12px !important; }

/* Ligne titre/date : titre à gauche, date à droite (float) */
.cv-item-header { display: block !important; overflow: hidden !important; }
.cv-item-title { display: block !important; float: left !important; }
.cv-item-date { display: block !important; float: right !important; font-size: 11px; color: #777; }

/* Grille compétences : 2 colonnes en float */
.skills-grid { display: block !important; overflow: hidden !important; }
.skill-item { float: left !important; width: 48% !important; margin-right: 2% !important; margin-bottom: 8px !important; }

/* Grille langues : 3 colonnes en float */
.lang-grid { display: block !important; overflow: hidden !important; }
.lang-item { float: left !important; width: 31% !important; margin-right: 2% !important; margin-bottom: 6px !important; }

/* --- Spécifique au modèle "Creative" (Sidebar à gauche) --- */
.cv { 
    display: block !important; 
    width: 100% !important; 
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
}
.sidebar { 
    display: block !important; 
    float: left !important; 
    width: 200px !important; 
    padding: 20px 15px 3000px 15px !important; 
    background: #1e1b4b !important; 
    color: white !important;
    margin-bottom: -3000px !important; 
}
.main { 
    display: block !important; 
    float: right !important;
    width: 480px !important; 
    padding: 20px 25px 3000px 25px !important; 
    background: #ffffff !important;
    margin-bottom: -3000px !important;
}

/* Fix Sidebar Items for Creative Template */
.item-header { display: block !important; overflow: hidden !important; }
.sidebar-top { display: block !important; text-align: center !important; }
.photo-container { display: block !important; margin: 0 auto 10px auto !important; width: 70px !important; height: 70px !important; }
.s-skill-name { display: block !important; overflow: hidden !important; }
.s-skill-name span:first-child { float: left !important; }
.s-skill-pct { float: right !important; }
.section-title { display: block !important; overflow: hidden !important; }

/* Clearfix */
.cv-section:after,
.cv-item:after,
.item-header:after { content: ""; display: table; clear: both; }
</style>';

    // Injecter juste avant </head>
    $html = str_replace('</head>', $dompdfCss . '</head>', $html);

    // Supprimer les imports Google Fonts (DomPDF les ignore ou ils ralentissent)
    $html = preg_replace('/<link[^>]+fonts\.googleapis\.com[^>]+>/i', '', $html);
    $html = preg_replace('/@import url\(["\']?https?:\/\/fonts\.googleapis\.com[^)]+\)["\']?;?/i', '', $html);
    $options = new \Dompdf\Options();
    $options->set('defaultFont', 'DejaVu Sans');
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('chroot', realpath(__DIR__ . '/../'));

    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Force le téléchargement du fichier
    $dompdf->stream($filename, ['Attachment' => true]);
    exit;
}

// ── Fallback : html2pdf.js côté client ────────────────────
$safeName = htmlspecialchars($profile['full_name'] ?? 'Mon CV');
$pdfName  = $filename;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Téléchargement CV – <?= $safeName ?></title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Inter, sans-serif; background: #0a0a0f; color: #f0f0f5; }
.dl-bar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 9999;
    height: 56px; background: #111118;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 1.5rem;
}
.dl-logo  { font-size: 1.1rem; font-weight: 800; color: #6366f1; }
.dl-status{ font-size: 0.83rem; color: #8b8ba7; }
.dl-actions { display: flex; align-items: center; gap: 0.75rem; }
.btn-back {
    color: #8b8ba7; text-decoration: none; font-size: 0.83rem;
    padding: 0.4rem 0.85rem; border: 1px solid rgba(255,255,255,0.1);
    border-radius: 6px;
}
.btn-dl {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: #6366f1; color: white; border: none;
    padding: 0.45rem 1.1rem; border-radius: 6px;
    font-weight: 600; cursor: pointer; font-size: 0.85rem;
}
.btn-dl:disabled { opacity: 0.5; cursor: not-allowed; }
.spinner {
    width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,0.2);
    border-top-color: white; border-radius: 50%;
    animation: spin 0.65s linear infinite; display: none;
}
@keyframes spin { to { transform: rotate(360deg); } }
.cv-wrapper { margin-top: 56px; display: flex; justify-content: center; padding: 2.5rem 1rem; }
.cv-page { width: 210mm; background: white; box-shadow: 0 8px 48px rgba(0,0,0,0.6); border-radius: 3px; overflow: hidden; }
</style>
</head>
<body>
<div class="dl-bar">
    <div style="display:flex;align-items:center;gap:1rem;">
        <span class="dl-logo">⚡ BUILD.CV</span>
        <span class="dl-status" id="status">Téléchargement dans 1 s…</span>
    </div>
    <div class="dl-actions">
        <a href="<?= APP_URL ?>/dashboard/cv-builder.php" class="btn-back">← Retour</a>
        <div class="spinner" id="spinner"></div>
        <button class="btn-dl" id="dlBtn" onclick="downloadPdf()">⬇ Télécharger le PDF</button>
    </div>
</div>
<div class="cv-wrapper">
    <div class="cv-page" id="cvPage"><?= $html ?></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
var FILENAME = '<?= addslashes($pdfName) ?>';
function downloadPdf() {
    var btn = document.getElementById('dlBtn'), spinner = document.getElementById('spinner'), status = document.getElementById('status');
    btn.disabled = true; spinner.style.display = 'block'; status.textContent = 'Génération…';
    html2pdf().set({
        margin: 0, filename: FILENAME,
        image: { type: 'jpeg', quality: 0.92 },
        html2canvas: { scale: 1.5, useCORS: false, allowTaint: true, logging: false, imageTimeout: 3000, backgroundColor: '#ffffff' },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    }).from(document.getElementById('cvPage')).save().then(function() {
        btn.disabled = false; spinner.style.display = 'none'; status.textContent = '✅ PDF téléchargé !';
        btn.textContent = '⬇ Télécharger à nouveau';
    });
}
window.addEventListener('load', function() { setTimeout(downloadPdf, 1000); });
</script>
</body>
</html>
