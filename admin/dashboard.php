<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requireAuth();

$recentArticles = loadArticles(4);
$categories = loadCategories();
$editorialPicks = loadEditorialPicks();
$liveTicker = loadLiveTicker();
$articleCount = getArticleCount();
$categoryCount = count($categories);
$editorialCount = count($editorialPicks);
$liveTickerCount = getLiveTickerCount();
$subscriberCount = getSubscriberCount();

$pageTitle = 'Kontrol Paneli';
require __DIR__ . '/header.php';
$articleUrl = assetUrl('article.php');
$articleEditUrl = assetUrl('admin/article-edit.php');
?>
<section class="admin-section">
    <h1 class="admin-section__title">Hoş geldiniz!</h1>
    <p>Bu panel üzerinden haber içeriklerini, kategorileri ve canlı akış mesajlarını yönetebilirsiniz.</p>
    <div class="admin-stats">
        <div class="admin-stat">
            <span class="admin-stat__label">Toplam Haber</span>
            <span class="admin-stat__value"><?php echo $articleCount; ?></span>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Kategoriler</span>
            <span class="admin-stat__value"><?php echo $categoryCount; ?></span>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Manşet Seçkisi</span>
            <span class="admin-stat__value"><?php echo $editorialCount; ?></span>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Canlı Akış</span>
            <span class="admin-stat__value"><?php echo $liveTickerCount; ?></span>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Bülten Aboneleri</span>
            <span class="admin-stat__value"><?php echo $subscriberCount; ?></span>
        </div>
    </div>
</section>
<section class="admin-section">
    <h2 class="admin-section__subtitle">Son Haberler</h2>
    <div class="admin-card-list">
        <?php foreach ($recentArticles as $article): ?>
            <article class="admin-card">
                <h3><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p class="admin-card__meta">
                    <?php echo htmlspecialchars($categories[$article['category']] ?? $article['category'], ENT_QUOTES, 'UTF-8'); ?> ·
                    <?php echo htmlspecialchars(formatDateTime($article['published_at']), ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <p><?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="admin-card__actions">
                    <a class="btn btn--ghost" href="<?php echo htmlspecialchars($articleUrl . '?slug=' . urlencode($article['slug']), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Görüntüle</a>
                    <a class="btn btn--primary" href="<?php echo htmlspecialchars($articleEditUrl . '?slug=' . urlencode($article['slug']), ENT_QUOTES, 'UTF-8'); ?>">Düzenle</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
