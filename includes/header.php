<?php
require_once __DIR__ . '/articles.php';
require_once __DIR__ . '/helpers.php';

date_default_timezone_set('Europe/Istanbul');
setlocale(LC_TIME, 'tr_TR.UTF-8', 'tr_TR', 'turkish');

if (!isset($pageTitle)) {
    $pageTitle = 'Haber Merkezi';
}

if (!isset($metaDescription)) {
    $metaDescription = 'Türkiye ve dünyadan son dakika haberleri, canlı gelişmeler ve derin analizler.';
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
    <link rel="stylesheet" href="/assets/css/style.css">
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
                <form class="search-form" action="/search.php" method="get">
                    <input type="text" name="q" placeholder="Haber ara..." aria-label="Haber ara">
                    <button type="submit" class="btn btn--ghost">Ara</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="site-header__main">
            <a href="/index.php" class="brand">Haber Merkezi</a>
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
<main class="site-main">
