<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requireAuth();

$articles = loadArticles();
$categories = loadCategories();
$pageTitle = 'Haberler';
require __DIR__ . '/header.php';
?>
<section class="admin-section">
    <div class="admin-section__header">
        <div>
            <h1 class="admin-section__title">Haber İçerikleri</h1>
            <p>Haberleri düzenleyebilir, silebilir veya yeni içerik oluşturabilirsiniz.</p>
        </div>
        <a class="btn btn--primary" href="/admin/article-new.php">Yeni Haber Ekle</a>
    </div>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Kategori</th>
                    <th>Yayın Tarihi</th>
                    <th class="admin-table__actions">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></strong><br>
                            <small><?php echo htmlspecialchars($article['slug'], ENT_QUOTES, 'UTF-8'); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($categories[$article['category']] ?? $article['category'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(formatDateTime($article['published_at']), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="admin-table__actions">
                            <a class="btn btn--ghost" href="/admin/article-edit.php?slug=<?php echo urlencode($article['slug']); ?>">Düzenle</a>
                            <form method="post" action="/admin/article-delete.php" onsubmit="return confirm('Bu haberi silmek istediğinize emin misiniz?');">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="slug" value="<?php echo htmlspecialchars($article['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="btn btn--danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
