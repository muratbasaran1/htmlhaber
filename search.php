<?php
require __DIR__ . '/includes/articles.php';
require __DIR__ . '/includes/helpers.php';

$query = $_GET['q'] ?? '';
$results = searchArticlesFromDatabase($query);

$pageTitle = 'Arama Sonuçları';
$metaDescription = 'Haber arama sonuçları';

require __DIR__ . '/includes/header.php';
?>
<section class="container">
    <header class="page-header">
        <h1>Arama Sonuçları</h1>
        <?php if (trim($query) !== ''): ?>
            <p><strong>“<?php echo htmlspecialchars($query, ENT_QUOTES, 'UTF-8'); ?>”</strong> için <?php echo count($results); ?> sonuç bulundu.</p>
        <?php else: ?>
            <p>Aramak istediğiniz kelimeyi yazın.</p>
        <?php endif; ?>
    </header>
    <?php if (count($results) > 0): ?>
        <div class="card-grid card-grid--3">
            <?php foreach ($results as $article): ?>
                <article class="card card--vertical">
                    <a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>" class="card__image" aria-label="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        <img src="<?php echo htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    </a>
                    <div class="card__content">
                        <span class="badge badge--outline"><?php echo htmlspecialchars($categories[$article['category']], ENT_QUOTES, 'UTF-8'); ?></span>
                        <h2><a href="/article.php?slug=<?php echo urlencode($article['slug']); ?>"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></a></h2>
                        <p><?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="not-found">
            <p>Aramanızla eşleşen sonuç bulunamadı.</p>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
