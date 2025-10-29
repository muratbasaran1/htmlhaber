<?php
$pageTitle = 'Son Dakika Haberler';
$metaDescription = 'Türkiye ve dünyadan en güncel son dakika haberleri, canlı anlatımlar ve özel dosyalar Haber Merkezi’nde.';
require __DIR__ . '/includes/header.php';

$featuredArticle = $articles[0];
$secondaryArticles = array_slice($articles, 1, 3);
$spotlightArticles = array_slice($articles, 4);
?>
<section class="hero">
    <div class="container hero-grid">
        <article class="hero__featured">
            <a href="/article.php?slug=<?php echo urlencode($featuredArticle['slug']); ?>" class="hero__image" aria-label="<?php echo htmlspecialchars($featuredArticle['title'], ENT_QUOTES, 'UTF-8'); ?>">
                <img src="<?php echo htmlspecialchars($featuredArticle['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($featuredArticle['title'], ENT_QUOTES, 'UTF-8'); ?>">
            </a>
            <div class="hero__content">
                <span class="badge badge--primary"><?php echo htmlspecialchars($categories[$featuredArticle['category']], ENT_QUOTES, 'UTF-8'); ?></span>
                <h1><a href="/article.php?slug=<?php echo urlencode($featuredArticle['slug']); ?>"><?php echo htmlspecialchars($featuredArticle['title'], ENT_QUOTES, 'UTF-8'); ?></a></h1>
                <p><?php echo htmlspecialchars($featuredArticle['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="meta">
                    <span><?php echo htmlspecialchars($featuredArticle['author'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <span><?php echo formatDateTime($featuredArticle['published_at']); ?></span>
                    <span><?php echo (int) $featuredArticle['reading_time']; ?> dk okuma</span>
                </div>
            </div>
        </article>
        <div class="hero__side">
            <h2>Manşetler</h2>
            <?php foreach ($secondaryArticles as $article): ?>
                <article class="headline">
                    <span class="badge"><?php echo htmlspecialchars($categories[$article['category']], ENT_QUOTES, 'UTF-8'); ?></span>
                    <h3><a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                    <p><?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="container split-layout">
    <div>
        <h2 class="section-title">Günün Öne Çıkanları</h2>
        <div class="card-grid">
            <?php foreach ($spotlightArticles as $article): ?>
                <article class="card">
                    <a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>" class="card__image" aria-label="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        <img src="<?php echo htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    </a>
                    <div class="card__content">
                        <span class="badge badge--outline"><?php echo htmlspecialchars($categories[$article['category']], ENT_QUOTES, 'UTF-8'); ?></span>
                        <h3><a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                        <p><?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="meta">
                            <span><?php echo htmlspecialchars($article['author'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span><?php echo formatDateTime($article['published_at']); ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    <aside class="sidebar">
        <section class="widget">
            <h3>Editörün Seçtikleri</h3>
            <ul class="list">
                <?php foreach ($editorialPicks as $pick): ?>
                    <li><a href="<?php echo htmlspecialchars($pick['link'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($pick['title'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section class="widget widget--dark">
            <h3>Podcast</h3>
            <p>Günün öne çıkan başlıklarını editörlerimizden dinleyin.</p>
            <button class="btn btn--light">Dinlemeye Başla</button>
        </section>
    </aside>
</section>
<section class="container category-highlights">
    <?php foreach (array_slice($categories, 0, 3, true) as $categorySlug => $categoryName): ?>
        <?php $categoryArticles = array_slice(getArticlesByCategory($articles, $categorySlug), 0, 3); ?>
        <div class="category-block">
            <div class="category-block__header">
                <h2><?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?></h2>
                <a class="link" href="/category.php?category=<?php echo urlencode($categorySlug); ?>">Tümünü Gör</a>
            </div>
            <div class="category-block__content">
                <?php foreach ($categoryArticles as $article): ?>
                    <article class="mini-card">
                        <a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>" class="mini-card__image" aria-label="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                            <img src="<?php echo htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        </a>
                        <div>
                            <h3><a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                            <span class="meta meta--compact"><?php echo formatDateTime($article['published_at']); ?> · <?php echo (int) $article['reading_time']; ?> dk</span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
