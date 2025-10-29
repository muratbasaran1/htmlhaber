<?php
require __DIR__ . '/includes/articles.php';
require __DIR__ . '/includes/helpers.php';

$slug = $_GET['slug'] ?? '';
$article = fetchArticleBySlug($slug);

if ($article === null) {
    http_response_code(404);
    $pageTitle = 'Haber bulunamadı';
    $metaDescription = 'Aradığınız haber yayından kaldırılmış olabilir.';
    require __DIR__ . '/includes/header.php';
    ?>
    <section class="container">
        <div class="not-found">
            <h1>Üzgünüz</h1>
            <p>Aradığınız haber yayından kaldırılmış veya taşınmış olabilir.</p>
            <a class="btn" href="/index.php">Anasayfaya dön</a>
        </div>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    return;
}

$pageTitle = $article['title'];
$metaDescription = $article['excerpt'];
require __DIR__ . '/includes/header.php';

$relatedArticles = fetchRelatedArticles($article['slug']);
?>
<section class="container article-layout">
    <article class="article">
        <div class="article__meta">
            <span class="badge badge--primary"><?php echo htmlspecialchars(resolveCategoryName($categories, $article['category']), ENT_QUOTES, 'UTF-8'); ?></span>
            <h1><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <div class="meta">
                <span><?php echo htmlspecialchars($article['author'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span><?php echo formatDateTime($article['published_at']); ?></span>
                <span><?php echo htmlspecialchars($article['source'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span><?php echo (int) $article['reading_time']; ?> dk okuma</span>
            </div>
        </div>
        <figure class="article__image">
            <img src="<?php echo htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
        </figure>
        <div class="article__content">
            <?php foreach ($article['content'] as $paragraph): ?>
                <p><?php echo htmlspecialchars($paragraph, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
        <div class="article__tags">
            <?php foreach ($article['tags'] as $tag): ?>
                <a class="badge badge--outline" href="/tag.php?tag=<?php echo urlencode($tag); ?>"><?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endforeach; ?>
        </div>
    </article>
    <aside class="sidebar">
        <section class="widget">
            <h3>İlgili Haberler</h3>
            <ul class="list list--dense">
                <?php foreach ($relatedArticles as $related): ?>
                    <li>
                        <a href="/article.php?slug=<?php echo urlencode($related['slug']); ?>">
                            <?php echo htmlspecialchars($related['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section class="widget widget--dark">
            <h3>Canlı Yayın</h3>
            <p>Stüdyomuzdan canlı yayınla gündemin nabzını tutuyoruz.</p>
            <button class="btn btn--light">Yayını İzle</button>
        </section>
    </aside>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
