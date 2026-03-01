<?php
/**
 * Modèle Premium : Neo Pop
 * Design : Style Pop-art / Comic book, couleurs primaires vives, bordures noires épaisses, ombres décalées
 */
$pageTitle = htmlspecialchars($profile['full_name'] ?? 'Portfolio') . " - Neo Pop";
$pEmail = htmlspecialchars($profile['email'] ?? '');
$pName = htmlspecialchars($profile['full_name'] ?? 'Nom');
$pTitle = htmlspecialchars($profile['title'] ?? 'SUPER HERO!');
$pSummary = strip_tags($profile['summary'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Bangers&family=Comic+Neue:wght@700&display=swap" rel="stylesheet">
<style>
:root { --bg: #fff100; --text: #000; --blue: #00ebff; --pink: #ff007f; --border: 6px solid #000; --shadow: 10px 10px 0px #000; }
body { margin: 0; padding: 0; background: var(--bg); color: var(--text); font-family: 'Comic Neue', cursive; background-image: radial-gradient(#000 15%, transparent 16%), radial-gradient(#000 15%, transparent 16%); background-size: 20px 20px; background-position: 0 0, 10px 10px; background-color: var(--bg); }
.wrapper { max-width: 1100px; margin: 40px auto; background: #fff; border: var(--border); box-shadow: var(--shadow); padding: 40px; position: relative; }
.header { text-align: center; border-bottom: var(--border); padding-bottom: 30px; margin-bottom: 40px; position: relative; }
.name { font-family: 'Bangers', cursive; font-size: 5rem; letter-spacing: 3px; color: var(--pink); text-shadow: 4px 4px 0 #000; margin: 0; transform: rotate(-2deg); }
.title { background: var(--blue); border: var(--border); display: inline-block; padding: 10px 30px; font-size: 1.5rem; transform: rotate(2deg); margin-top: -15px; position: relative; z-index: 2; box-shadow: 5px 5px 0 #000; }
.summary-box { background: #fff; border: var(--border); padding: 20px; font-size: 1.2rem; line-height: 1.5; box-shadow: var(--shadow); margin-bottom: 50px; border-radius: 20px; position: relative; }
.summary-box::after { content:''; position:absolute; bottom:-25px; left:50px; border-width: 25px 25px 0 0; border-style:solid; border-color: #000 transparent transparent transparent; }
.summary-box::before { content:''; position:absolute; bottom:-12px; left:55px; border-width: 15px 15px 0 0; border-style:solid; border-color: #fff transparent transparent transparent; z-index:2; }
.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; }
.card { background: var(--blue); border: var(--border); box-shadow: var(--shadow); border-radius: 10px; overflow: hidden; transform: rotate(-1deg); transition: transform 0.2s; }
.card:nth-child(even) { background: var(--pink); transform: rotate(1deg); }
.card:hover { transform: scale(1.05) rotate(0deg); }
.card-content { background: #fff; padding: 20px; margin-top: 20px; border-top: var(--border); min-height: 150px; display: flex; flex-direction: column; }
.card h3 { font-family: 'Bangers', cursive; font-size: 2rem; margin: 0; letter-spacing: 1px; }
.card-top { padding: 15px; font-family: 'Bangers', cursive; font-size: 1.5rem; color: #fff; text-shadow: 2px 2px 0 #000; text-align: center; }
.card p { flex: 1; font-size: 1.1rem; }
.btn { display: inline-block; background: var(--bg); border: var(--border); box-shadow: 4px 4px 0 #000; color: #000; font-family: 'Bangers', cursive; font-size: 1.2rem; text-decoration: none; padding: 5px 15px; text-transform: uppercase; margin-top: 15px; text-align: center; }
.btn:active { transform: translate(4px, 4px); box-shadow: none; }
.contact { text-align: center; margin-top: 50px; border-top: var(--border); padding-top: 40px; }
.contact a { font-family: 'Bangers', cursive; font-size: 3rem; color: var(--pink); text-shadow: 3px 3px 0 #000; text-decoration: none; }
.contact a:hover { color: var(--blue); }
</style>
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <h1 class="name"><?= $pName ?></h1>
            <div class="title"><?= $pTitle ?></div>
        </header>

        <?php if($pSummary): ?>
        <div class="summary-box">
            "<?= htmlspecialchars($pSummary) ?>"
        </div>
        <?php endif; ?>

        <div class="grid">
            <?php foreach ($projects as $project): ?>
            <div class="card">
                <div class="card-top">BOOM!</div>
                <div class="card-content">
                    <h3><?= htmlspecialchars($project['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                    <?php if(!empty($project['link_url'])): ?>
                    <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" class="btn">KAPOW! (View)</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if($pEmail): ?>
        <div class="contact">
            <a href="mailto:<?= $pEmail ?>">HIRE ME NOW!</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
