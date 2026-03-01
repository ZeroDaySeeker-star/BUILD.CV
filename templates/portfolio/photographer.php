<?php
/**
 * Modèle Premium : Photographer
 * Design : Axé sur l'image, typographie fine, galeries horizontales
 */
$pageTitle = htmlspecialchars($profile['full_name']) . " - Photographie";
$bgImage = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?q=80&w=2000&auto=format&fit=crop';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
<style>
:root { --text: #fff; --bg: #111; }
body { margin: 0; padding: 0; background: var(--bg); color: var(--text); font-family: 'Montserrat', sans-serif; }
.hero { height: 100vh; background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.8)), url('<?= $bgImage ?>'); background-size: cover; background-position: center; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
.hero h1 { font-family: 'Cinzel', serif; font-size: clamp(3rem, 8vw, 6rem); margin: 0; letter-spacing: 5px; text-transform: uppercase; }
.hero p { font-size: 1.2rem; font-weight: 300; letter-spacing: 2px; text-transform: uppercase; margin-top: 1rem; color: #ccc; }
.scroll-indicator { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); font-size: 0.8rem; letter-spacing: 3px; text-transform: uppercase; animation: bounce 2s infinite; }
@keyframes bounce { 0%, 20%, 50%, 80%, 100% {transform: translateY(0) translateX(-50%);} 40% {transform: translateY(-10px) translateX(-50%);} 60% {transform: translateY(-5px) translateX(-50%);} }
.gallery-section { padding: 5rem 0; overflow-x: hidden; }
.gallery-title { text-align: center; font-family: 'Cinzel', serif; font-size: 2.5rem; margin-bottom: 3rem; letter-spacing: 3px; }
.horizontal-scroll { display: flex; gap: 2rem; padding: 0 5vw; overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: 2rem; cursor: grab; }
.horizontal-scroll::-webkit-scrollbar { height: 8px; }
.horizontal-scroll::-webkit-scrollbar-track { background: #222; }
.horizontal-scroll::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
.gallery-item { min-width: 400px; height: 500px; scroll-snap-align: center; position: relative; }
.gallery-img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); transition: filter 0.5s ease; cursor: pointer; }
.gallery-item:hover .gallery-img { filter: grayscale(0%); }
.gallery-info { position: absolute; bottom: 0; left: 0; right: 0; padding: 2rem; background: linear-gradient(transparent, rgba(0,0,0,0.9)); transform: translateY(20px); opacity: 0; transition: all 0.4s ease; }
.gallery-item:hover .gallery-info { transform: translateY(0); opacity: 1; }
.gallery-info h3 { margin: 0 0 0.5rem 0; font-family: 'Cinzel', serif; font-size: 1.5rem; letter-spacing: 1px; }
.gallery-info p { margin: 0; font-size: 0.9rem; color: #ccc; }
.footer { text-align: center; padding: 4rem 2rem; border-top: 1px solid #333; }
.footer a { color: #fff; text-decoration: none; border: 1px solid #fff; padding: 10px 30px; letter-spacing: 2px; text-transform: uppercase; transition: 0.3s; }
.footer a:hover { background: #fff; color: #000; }
@media(max-width: 768px) { .gallery-item { min-width: 80vw; height: 60vh; } }
</style>
</head>
<body>
    <header class="hero">
        <h1><?= htmlspecialchars($profile['full_name']) ?></h1>
        <p><?= htmlspecialchars($profile['title'] ?? 'Photographer & Visual Artist') ?></p>
        <div class="scroll-indicator">Scroll to discover</div>
    </header>

    <section class="gallery-section">
        <h2 class="gallery-title">Selected Works</h2>
        <div class="horizontal-scroll">
            <?php foreach ($projects as $project): ?>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1542038784456-1ea8e935640e?q=80&w=800&auto=format&fit=crop" class="gallery-img" alt="<?= htmlspecialchars($project['title']) ?>">
                <div class="gallery-info">
                    <h3><?= htmlspecialchars($project['title']) ?></h3>
                    <p><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>
                    <?php if($project['link_url']): ?>
                    <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" style="color:#fff; text-decoration:underline; font-size:0.8rem; margin-top:10px; display:inline-block;">View Full</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php if($profile['email']): ?>
    <footer class="footer">
        <p style="margin-bottom:2rem; font-family:'Cinzel', serif; font-size:1.5rem;">Ready to collaborate?</p>
        <a href="mailto:<?= htmlspecialchars($profile['email']) ?>">Get in Touch</a>
    </footer>
    <?php endif; ?>
</body>
</html>
