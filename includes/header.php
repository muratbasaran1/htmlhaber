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

$liveTickerMessages = $liveTicker ?? [];
if (!is_array($liveTickerMessages)) {
    $liveTickerMessages = [];
}

$liveTickerJson = json_encode($liveTickerMessages, JSON_UNESCAPED_UNICODE);
if ($liveTickerJson === false) {
    $liveTickerJson = '[]';
}

$liveTickerAttribute = htmlspecialchars($liveTickerJson, ENT_QUOTES, 'UTF-8');

if (!isset($pageTitle)) {
    $pageTitle = $siteName;
}

if (!isset($metaDescription)) {
    $metaDescription = $siteTagline;
}

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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(assetUrl('assets/css/style.css'), ENT_QUOTES, 'UTF-8'); ?>">
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
                <div class="top-bar__ticker" id="liveTicker" data-messages='<?php echo $liveTickerAttribute; ?>'></div>
            </div>
            <div class="top-bar__right">
                <form class="search-form" action="/search.php" method="get">
                    <input type="text" name="q" placeholder="Haber ara..." aria-label="Haber ara">
                    <button type="submit" class="btn btn--ghost">Ara</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="site-header__main">
            <a href="/index.php" class="brand">
                <span class="brand__name"><?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="brand__tagline"><?php echo htmlspecialchars($siteTagline, ENT_QUOTES, 'UTF-8'); ?></span>
            </a>
            <button class="nav-toggle" aria-label="Menüyü Aç/Kapat" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
            <nav class="site-nav" aria-label="Ana menü">
                <ul>
                    <?php foreach ($categories as $slug => $name): ?>
                        <li><a href="/category.php?category=<?php echo urlencode($slug); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>
<main class="site-main"<?php echo $mainAttributes; ?>>
