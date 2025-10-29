<?php
require __DIR__ . '/includes/articles.php';
require __DIR__ . '/includes/helpers.php';

$tagUsage = getTagUsage($articles);
$allTags = getAllTags($articles);

$pageTitle = 'Etiket Arşivi';
$metaDescription = 'Haber Merkezi etiket arşivinden ilgilendiğiniz konuları keşfedin.';

require __DIR__ . '/includes/header.php';
$tagBaseUrl = assetUrl('tag.php');
?>
<section class="container">
    <header class="section-header">
        <h1 class="section-title">Etiket Arşivi</h1>
        <p><?php echo count($allTags); ?> etiket listeleniyor.</p>
    </header>
    <div class="tag-cloud">
        <?php foreach ($tagUsage as $tag): ?>
            <a class="tag-chip" href="<?php echo htmlspecialchars($tagBaseUrl . '?tag=' . urlencode($tag['label']), ENT_QUOTES, 'UTF-8'); ?>">
                <span class="tag-chip__label"><?php echo htmlspecialchars($tag['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="tag-chip__count"><?php echo (int) $tag['count']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php';
