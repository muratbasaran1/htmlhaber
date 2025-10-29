<?php
require __DIR__ . '/includes/articles.php';
require __DIR__ . '/includes/helpers.php';

$tag = $_GET['tag'] ?? '';
$tagArticles = fetchArticlesByTag($tag);

if ($tagArticles === []) {
    http_response_code(404);
    $pageTitle = 'Etiket bulunamadı';
    $metaDescription = 'Aradığınız etiket için kayıtlı haber bulunamadı.';
    require __DIR__ . '/includes/header.php';
    ?>
    <section class="container">
        <div class="not-found">
            <h1>Etiket bulunamadı</h1>
            <p>"<?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>" etiketiyle eşleşen haber bulunamadı.</p>
            <a class="btn" href="/index.php">Anasayfaya dön</a>
        </div>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    return;
}

$tagTitle = htmlspecialchars($tag, ENT_QUOTES, 'UTF-8');
$pageTitle = $tagTitle . ' etiketiyle ilgili haberler';
$metaDescription = $tagTitle . ' etiketiyle arşivlenmiş son haberleri inceleyin.';

require __DIR__ . '/includes/header.php';
?>
<section class="container">
    <header class="section-header">
        <h1 class="section-title">"<?php echo $tagTitle; ?>" Etiketi</h1>
        <p><?php echo count($tagArticles); ?> haber bulundu.</p>
    </header>
    <div class="card-grid">
        <?php foreach ($tagArticles as $article): ?>
            <article class="card card--horizontal">
                <a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>" class="card__image" aria-label="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    <img src="<?php echo htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                </a>
                <div class="card__content">
                    <span class="badge badge--outline"><?php echo htmlspecialchars($categories[$article['category']], ENT_QUOTES, 'UTF-8'); ?></span>
                    <h2><a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></a></h2>
                    <p><?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="meta">
                        <span><?php echo htmlspecialchars($article['author'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span><?php echo formatDateTime($article['published_at']); ?></span>
                        <span><?php echo (int) $article['reading_time']; ?> dk</span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php';
