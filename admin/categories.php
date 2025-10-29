<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requireAuth();

$categories = loadCategories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        redirectWithMessage('/admin/categories.php', 'error', 'Oturum süresi doldu.');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $slug = trim($_POST['slug'] ?? '');
        $name = trim($_POST['name'] ?? '');

        if ($slug === '' || $name === '') {
            $errors[] = 'Kategori adı ve slug zorunludur.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            $errors[] = 'Slug sadece küçük harf, rakam ve tire içermelidir.';
        } elseif (isset($categories[$slug])) {
            $errors[] = 'Bu slug zaten kullanılıyor.';
        } else {
            try {
                createCategory($slug, $name);
                redirectWithMessage('/admin/categories.php', 'success', 'Kategori başarıyla eklendi.');
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $errors[] = 'Bu slug zaten kullanılıyor.';
                } else {
                    $errors[] = 'Kategori kaydedilirken bir hata oluştu.';
                }
            }
        }
    } elseif ($action === 'delete') {
        $slug = trim($_POST['slug'] ?? '');
        if ($slug === '') {
            redirectWithMessage('/admin/categories.php', 'error', 'Silinecek kategori bulunamadı.');
        }

        $linkedArticles = fetchArticlesByCategory($slug, 1);
        if (!empty($linkedArticles)) {
            redirectWithMessage('/admin/categories.php', 'error', 'Bu kategori haberler tarafından kullanılıyor. Önce haberleri güncelleyin.');
        }

        if (!isset($categories[$slug])) {
            redirectWithMessage('/admin/categories.php', 'error', 'Kategori bulunamadı.');
        }

        deleteCategory($slug);
        redirectWithMessage('/admin/categories.php', 'success', 'Kategori silindi.');
    }
}

$pageTitle = 'Kategoriler';
require __DIR__ . '/header.php';
?>
<section class="admin-section">
    <h1 class="admin-section__title">Kategorileri Yönet</h1>
    <div class="admin-grid">
        <div class="admin-card">
            <h2>Yeni Kategori Ekle</h2>
            <?php if (!empty($errors)): ?>
                <div class="admin-flash">
                    <?php foreach ($errors as $error): ?>
                        <div class="admin-flash__item admin-flash__item--error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="post" class="admin-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="category-name">Kategori Adı</label>
                    <input type="text" id="category-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="category-slug">Slug</label>
                    <input type="text" id="category-slug" name="slug" required>
                </div>
                <button type="submit" class="btn btn--primary">Ekle</button>
            </form>
        </div>
        <div class="admin-card">
            <h2>Mevcut Kategoriler</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Adı</th>
                        <th>Slug</th>
                        <th class="admin-table__actions">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $slug => $name): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><code><?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?></code></td>
                            <td class="admin-table__actions">
                                <form method="post" action="<?php echo htmlspecialchars(assetUrl('admin/categories.php'), ENT_QUOTES, 'UTF-8'); ?>" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" class="btn btn--danger">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
