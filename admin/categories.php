<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requireAuth();

$categories = loadCategories();
$addErrors = [];
$reorderErrors = [];
$submittedOrder = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        redirectWithMessage('/admin/categories.php', 'error', 'Oturum süresi doldu.');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $slug = trim($_POST['slug'] ?? '');
        $name = trim($_POST['name'] ?? '');

        if ($slug === '' || $name === '') {
            $addErrors[] = 'Kategori adı ve slug zorunludur.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            $addErrors[] = 'Slug sadece küçük harf, rakam ve tire içermelidir.';
        } elseif (isset($categories[$slug])) {
            $addErrors[] = 'Bu slug zaten kullanılıyor.';
        } else {
            try {
                createCategory($slug, $name);
                redirectWithMessage('/admin/categories.php', 'success', 'Kategori başarıyla eklendi.');
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $addErrors[] = 'Bu slug zaten kullanılıyor.';
                } else {
                    $addErrors[] = 'Kategori kaydedilirken bir hata oluştu.';
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
    } elseif ($action === 'reorder') {
        $orderInput = $_POST['order'] ?? [];

        if (!is_array($orderInput) || $orderInput === []) {
            $reorderErrors[] = 'Sıralama verisi alınamadı.';
        } else {
            $submittedOrder = [];
            $knownSlugs = array_keys($categories);

            foreach ($knownSlugs as $slug) {
                if (!array_key_exists($slug, $orderInput)) {
                    $reorderErrors[] = 'Tüm kategoriler için sıra değeri girilmelidir.';
                    break;
                }

                $value = $orderInput[$slug];

                if (!is_scalar($value) || !preg_match('/^-?\d+$/', (string) $value)) {
                    $reorderErrors[] = 'Sıra değerleri sadece sayılardan oluşmalıdır.';
                    break;
                }

                $intValue = (int) $value;
                if ($intValue < 1) {
                    $reorderErrors[] = 'Sıra değerleri 1 veya daha büyük olmalıdır.';
                    break;
                }

                $submittedOrder[$slug] = $intValue;
            }

            if (empty($reorderErrors)) {
                $sequence = [];
                $fallback = 0;

                foreach ($categories as $slug => $_name) {
                    $fallback++;
                    $sequence[] = [
                        'slug' => $slug,
                        'position' => $submittedOrder[$slug] ?? $fallback,
                        'fallback' => $fallback,
                    ];
                }

                usort($sequence, static function (array $a, array $b): int {
                    if ($a['position'] === $b['position']) {
                        return $a['fallback'] <=> $b['fallback'];
                    }

                    return $a['position'] <=> $b['position'];
                });

                $orderedSlugs = array_column($sequence, 'slug');
                reorderCategories($orderedSlugs);

                redirectWithMessage('/admin/categories.php', 'success', 'Kategori sıralaması güncellendi.');
            }
        }
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
            <?php if (!empty($addErrors)): ?>
                <div class="admin-flash">
                    <?php foreach ($addErrors as $error): ?>
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
            <p class="admin-card__description">Menüdeki sıralamayı değiştirmek için numaraları güncelleyin ve kaydedin.</p>
            <?php if (!empty($reorderErrors)): ?>
                <div class="admin-flash">
                    <?php foreach ($reorderErrors as $error): ?>
                        <div class="admin-flash__item admin-flash__item--error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars(assetUrl('admin/categories.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-form admin-form--stacked admin-form--table">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="action" value="reorder">
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th scope="col">Sıra</th>
                                <th scope="col">Adı</th>
                                <th scope="col">Slug</th>
                                <th scope="col" class="admin-table__actions">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="4">Henüz kategori bulunmuyor.</td>
                                </tr>
                            <?php else: ?>
                                <?php $position = 1; $maxPosition = max(1, count($categories)); ?>
                                <?php foreach ($categories as $slug => $name): ?>
                                    <?php $displayOrder = $submittedOrder[$slug] ?? $position; ?>
                                    <tr>
                                        <td class="admin-table__order">
                                            <label class="sr-only" for="order-<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>">Sıra</label>
                                            <input
                                                id="order-<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>"
                                                class="admin-table__order-input"
                                                type="number"
                                                min="1"
                                                max="<?php echo $maxPosition; ?>"
                                                name="order[<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>]"
                                                value="<?php echo htmlspecialchars((string) $displayOrder, ENT_QUOTES, 'UTF-8'); ?>"
                                            >
                                        </td>
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
                                    <?php $position++; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (!empty($categories)): ?>
                    <div class="form-actions">
                        <button type="submit" class="btn btn--primary">Sıralamayı Kaydet</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
