<?php
require __DIR__ . '/includes/articles.php';
require __DIR__ . '/includes/helpers.php';

$categorySlug = $_GET['category'] ?? '';

if (!array_key_exists($categorySlug, $categories)) {
    http_response_code(404);
    $pageTitle = 'Kategori bulunamadı';
    $metaDescription = 'Görüntülemek istediğiniz kategori yayından kaldırılmış olabilir.';
    require __DIR__ . '/includes/header.php';
    ?>
    <section class="container">
        <div class="not-found">
            <h1>Hata 404</h1>
            <p>Üzgünüz, aradığınız kategoriye ulaşılamıyor.</p>
            <a class="btn" href="/index.php">Anasayfaya dön</a>
        </div>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    return;
}

$categoryName = $categories[$categorySlug];
$categoryArticles = fetchArticlesByCategory($categorySlug);
$pageTitle = $categoryName . ' Haberleri';
$metaDescription = $categoryName . ' kategorisinden en güncel gelişmeler.';

require __DIR__ . '/includes/header.php';
?>
<section class="container">
    <header class="page-header">
        <h1><?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?></p>
    </header>
    <div class="card-grid card-grid--3">
        <?php foreach ($categoryArticles as $article): ?>
            <article class="card card--vertical">
                <a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>" class="card__image" aria-label="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    <img src="<?php echo htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                </a>
                <div class="card__content">
                    <span class="meta meta--compact"><?php echo formatDateTime($article['published_at']); ?> · <?php echo (int) $article['reading_time']; ?> dk</span>
                    <h2><a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></a></h2>
                    <p><?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
