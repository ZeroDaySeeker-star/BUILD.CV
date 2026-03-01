<?php
/**
 * Modèle Premium : Tech Lead
 * Design : Terminal / IDE Sombre avec accents Monospace verts
 */
$name     = htmlspecialchars($profile['full_name'] ?? 'user@build.cv');
$title    = htmlspecialchars($profile['title'] ?? 'Senior Developer');
$email    = htmlspecialchars($profile['email'] ?? '');
$phone    = htmlspecialchars($profile['phone'] ?? '');
$location = htmlspecialchars($profile['location'] ?? '');
$website  = htmlspecialchars($profile['website'] ?? '');
$github   = htmlspecialchars($profile['github'] ?? '');
$summary  = $profile['summary'] ?? '';

$contacts = [];
if ($email) $contacts['email'] = $email;
if ($phone) $contacts['phone'] = $phone;
if ($location) $contacts['location'] = $location;
if ($website) $contacts['website'] = $website;
if ($github) $contacts['github'] = $github;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #1e1e1e;
    --text: #d4d4d4;
    --keyword: #569cd6;
    --string: #ce9178;
    --function: #dcdcaa;
    --comment: #6a9955;
    --class: #4ec9b0;
    --number: #b5cea8;
    --accent: #4af626;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Fira Code', monospace; background: #333; color: var(--text); font-size: 12px; line-height: 1.5; }
.cv-page { width: 21cm; min-height: 29.7cm; background: var(--bg); margin: 0 auto; padding: 50px; border: 1px solid #000; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
.line { display: flex; margin-bottom: 2px; }
.ln { width: 40px; color: #858585; user-select: none; text-align: right; margin-right: 20px; flex-shrink: 0; }
.code { flex: 1; word-wrap: break-word; }
.kw { color: var(--keyword); }
.str { color: var(--string); }
.fn { color: var(--function); }
.com { color: var(--comment); }
.cls { color: var(--class); }
.num { color: var(--number); }
h1 { font-size: 24px; color: var(--class); margin-bottom: 15px; font-weight: 700; }
.indent { padding-left: 30px; }
.indent-2 { padding-left: 60px; }
</style>
</head>
<body>
<div class="cv-page">
    <?php $l = 1; ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code com">// Profil Développeur généré automatiquement</div></div>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code"><span class="kw">class</span> <span class="cls"><?= str_replace(' ', '', $name) ?: 'Developer' ?></span> <span class="kw">extends</span> <span class="cls">Developer</span> {</div></div>
    
    <?php if($title): ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent"><span class="kw">public string</span> <span class="text">$role</span> = <span class="str">"<?= $title ?>"</span>;</div></div>
    <?php endif; ?>

    <?php if(!empty($contacts)): ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent"><span class="kw">public array</span> <span class="text">$contact</span> = [</div></div>
        <?php $cCount = count($contacts); $i = 0; foreach($contacts as $k => $v): $i++; ?>
        <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2"><span class="str">"<?= $k ?>"</span> => <span class="str">"<?= $v ?>"</span><?= $i < $cCount ? ',' : '' ?></div></div>
        <?php endforeach; ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent">];</div></div>
    <?php endif; ?>
    
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code"> </div></div>
    
    <?php if($summary): ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent"><span class="kw">public function</span> <span class="fn">getSummary</span>(): <span class="kw">string</span> {</div></div>
    <?php 
        $sumLines = explode("\n", strip_tags($summary));
        if (count($sumLines) > 1):
    ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2"><span class="kw">return</span> &lt;&lt;&lt;EOF</div></div>
    <?php foreach($sumLines as $sline): ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2"><span class="str"><?= htmlspecialchars(trim($sline)) ?></span></div></div>
    <?php endforeach; ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2">EOF;</div></div>
    <?php else: ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2"><span class="kw">return</span> <span class="str">"<?= htmlspecialchars(trim($sumLines[0])) ?>"</span>;</div></div>
    <?php endif; ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent">}</div></div>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code"> </div></div>
    <?php endif; ?>

    <?php if($skills): ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent"><span class="kw">public array</span> <span class="text">$skills</span> = [</div></div>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2">
        <?php foreach($skills as $s): ?>
        <span class="str">"<?= htmlspecialchars($s['skill_name']) ?>"</span>, 
        <?php endforeach; ?>
    </div></div>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent">];</div></div>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code"> </div></div>
    <?php endif; ?>

    <?php if($experience): ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent"><span class="kw">public function</span> <span class="fn">executeExperience</span>(): <span class="kw">void</span> {</div></div>
        <?php foreach($experience as $job): ?>
        <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2 com">// <?= $job['start_date'] ?> to <?= $job['end_date'] ?: 'Present' ?></div></div>
        <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2"><span class="text">Job</span>::<span class="fn">load</span>(<span class="str">"<?= htmlspecialchars($job['company']) ?>"</span>, <span class="str">"<?= htmlspecialchars($job['position']) ?>"</span>);</div></div>
        <?php if(!empty($job['description'])): ?>
        <?php foreach(explode("\n", strip_tags($job['description'])) as $dline): ?>
        <?php if(trim($dline) !== ''): ?>
        <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2 com">/* <?= htmlspecialchars(trim($dline)) ?> */</div></div>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        <div class="line"><div class="ln"><?= $l++ ?></div><div class="code"> </div></div>
        <?php endforeach; ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent">}</div></div>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code"> </div></div>
    <?php endif; ?>

    <?php if($education): ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent"><span class="kw">public function</span> <span class="fn">getEducation</span>(): <span class="kw">array</span> {</div></div>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2"><span class="kw">return</span> [</div></div>
        <?php foreach($education as $edu): ?>
        <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2">  [<span class="str">"degree"</span> => <span class="str">"<?= htmlspecialchars($edu['degree']) ?>"</span>, <span class="str">"school"</span> => <span class="str">"<?= htmlspecialchars($edu['school']) ?>"</span>],</div></div>
        <?php endforeach; ?>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent-2">];</div></div>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code indent">}</div></div>
    <?php endif; ?>
    
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code">}</div></div>
    <div class="line"><div class="ln"><?= $l++ ?></div><div class="code com">// EOF</div></div>
</div>
</body>
</html>
