<?php
require_once __DIR__ . '/articles.php';
require_once __DIR__ . '/helpers.php';

date_default_timezone_set('Europe/Istanbul');
setlocale(LC_TIME, 'tr_TR.UTF-8', 'tr_TR', 'turkish');

$mainAttributes = '';
$siteName = $siteSettings['site_name'] ?? 'Haber Merkezi';
$siteTagline = $siteSettings['site_tagline'] ?? 'Türkiye ve dünyadan en güvenilir haberler';
$primaryColor = $siteSettings['primary_color'] ?? '#d62828';
$secondaryColor = $siteSettings['secondary_color'] ?? '#003049';
$logoUrl = $siteSettings['logo_url'] ?? '';
$faviconUrl = $siteSettings['favicon_url'] ?? '';

if (!isset($pageTitle)) {
    $pageTitle = $siteName;
}

if (!isset($metaDescription)) {
    $metaDescription = $siteTagline;
}

$pageType = isset($pageType) && $pageType === 'article' ? 'article' : 'website';
$pageImage = $pageImage ?? ($logoUrl ?: '');
$canonicalUrl = buildAbsoluteUrl(getCurrentRelativeUrl());
$ogImage = ensureAbsoluteUrl($pageImage);
$twitterCard = $ogImage !== '' ? 'summary_large_image' : 'summary';

if (isset($homeFeedUrl)) {
    $resolvedFeedUrl = assetUrl($homeFeedUrl);
    $mainAttributes .= ' data-home-feed="' . htmlspecialchars($resolvedFeedUrl, ENT_QUOTES, 'UTF-8') . '"';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> | Haber Merkezi</title>
    <link rel="canonical" href="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="alternate" type="application/rss+xml" title="<?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?> RSS" href="<?php echo htmlspecialchars(buildAbsoluteUrl('rss.php'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="sitemap" type="application/xml" title="Site Haritası" href="<?php echo htmlspecialchars(buildAbsoluteUrl('sitemap.php'), ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:type" content="<?php echo htmlspecialchars($pageType, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <?php if ($ogImage !== ''): ?>
        <meta property="og:image" content="<?php echo htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="<?php echo htmlspecialchars($twitterCard, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <?php if ($ogImage !== ''): ?>
        <meta name="twitter:image" content="<?php echo htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(assetUrl('assets/css/style.css'), ENT_QUOTES, 'UTF-8'); ?>">
    <?php if ($faviconUrl !== ''): ?>
        <link rel="icon" href="<?php echo htmlspecialchars($faviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif; ?>
    <style>
        :root {
            --color-primary: <?php echo htmlspecialchars($primaryColor, ENT_QUOTES, 'UTF-8'); ?>;
            --color-secondary: <?php echo htmlspecialchars($secondaryColor, ENT_QUOTES, 'UTF-8'); ?>;
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="top-bar">
        <div class="container">
            <div class="top-bar__left">
                <span class="top-bar__time"><?php echo strftime('%e %B %Y, %A'); ?></span>
                <span class="top-bar__ticker-label">Canlı Akış</span>
                <div class="top-bar__ticker" id="liveTicker" data-messages='<?php echo json_encode($liveTicker, JSON_UNESCAPED_UNICODE); ?>'></div>
            </div>
            <div class="top-bar__right">
                <form class="search-form" action="<?php echo htmlspecialchars(assetUrl('search.php'), ENT_QUOTES, 'UTF-8'); ?>" method="get">
                    <input type="text" name="q" placeholder="Haber ara..." aria-label="Haber ara">
                    <button type="submit" class="btn btn--ghost">Ara</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="site-header__main">
            <?php $brandClass = 'brand' . ($logoUrl !== '' ? ' brand--has-logo' : ''); ?>
            <a href="<?php echo htmlspecialchars(assetUrl('index.php'), ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo htmlspecialchars($brandClass, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if ($logoUrl !== ''): ?>
                    <img class="brand__logo" src="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?> logosu">
                <?php endif; ?>
                <span class="brand__text">
                    <span class="brand__name"><?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="brand__tagline"><?php echo htmlspecialchars($siteTagline, ENT_QUOTES, 'UTF-8'); ?></span>
                </span>
            </a>
            <button class="nav-toggle" aria-label="Menüyü Aç/Kapat" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
            <nav class="site-nav" aria-label="Ana menü">
                <ul>
                    <?php foreach ($categories as $slug => $name): ?>
                        <li><a href="<?php echo htmlspecialchars(assetUrl('category.php') . '?category=' . urlencode($slug), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>
<main class="site-main"<?php echo $mainAttributes; ?>>
