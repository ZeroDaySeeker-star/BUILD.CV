<?php
// Includes SEO & Social Meta Tags for Portfolios
$metaTitle = htmlspecialchars(($profile['full_name'] ?? 'Portfolio') . ' – ' . ($profile['title'] ?? 'Profil Professionnel'));
$metaDesc  = htmlspecialchars(mb_substr(strip_tags($profile['summary'] ?? 'Découvrez mon portfolio professionnel créé avec ' . APP_NAME), 0, 160));
$metaUrl   = APP_URL . '/u/' . $user['username'];
$metaImage = !empty($profile['profile_photo']) ? UPLOAD_URL . $profile['profile_photo'] : APP_URL . '/assets/img/og-preview.png';
?>
<!-- SEO Meta Tags -->
<title><?= $metaTitle ?></title>
<meta name="description" content="<?= $metaDesc ?>">
<meta name="robots" content="index, follow">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?= $metaUrl ?>">
<meta property="og:title" content="<?= $metaTitle ?>">
<meta property="og:description" content="<?= $metaDesc ?>">
<meta property="og:image" content="<?= $metaImage ?>">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?= $metaUrl ?>">
<meta property="twitter:title" content="<?= $metaTitle ?>">
<meta property="twitter:description" content="<?= $metaDesc ?>">
<meta property="twitter:image" content="<?= $metaImage ?>">

<!-- Favicon -->
<link rel="icon" type="image/png" href="<?= APP_URL ?>/assets/img/favicon.png">
