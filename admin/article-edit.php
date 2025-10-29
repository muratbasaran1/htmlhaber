<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requireAuth();

$slug = $_GET['slug'] ?? '';
$categories = loadCategories();
$article = fetchArticleForEditing($slug);

if ($article === null) {
    redirectWithMessage('/admin/articles.php', 'error', 'Aradığınız haber bulunamadı.');
}

$errors = [];
$values = [
    'title' => $article['title'],
    'slug' => $article['slug'],
    'excerpt' => $article['excerpt'],
    'content' => implode("\n\n", $article['content']),
    'category' => $article['category'],
    'author' => $article['author'],
    'source' => $article['source'],
    'image' => $article['image'],
    'published_at' => (new DateTimeImmutable($article['published_at']))->setTimezone(new DateTimeZone('Europe/Istanbul'))->format('Y-m-d\TH:i'),
    'reading_time' => (string) $article['reading_time'],
    'tags' => implode(', ', $article['tags']),
];
$articleId = (int) ($article['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Oturum süresi doldu. Lütfen formu tekrar gönderin.';
    }

    $values = [
        'title' => trim($_POST['title'] ?? ''),
        'slug' => trim($_POST['slug'] ?? ''),
        'excerpt' => trim($_POST['excerpt'] ?? ''),
        'content' => trim($_POST['content'] ?? ''),
        'category' => trim($_POST['category'] ?? ''),
        'author' => trim($_POST['author'] ?? ''),
        'source' => trim($_POST['source'] ?? ''),
        'image' => trim($_POST['image'] ?? ''),
        'published_at' => trim($_POST['published_at'] ?? ''),
        'reading_time' => trim($_POST['reading_time'] ?? ''),
        'tags' => trim($_POST['tags'] ?? ''),
    ];

    if ($values['title'] === '') {
        $errors[] = 'Başlık zorunludur.';
    }

    if ($values['slug'] === '') {
        $errors[] = 'Kalıcı bağlantı (slug) zorunludur.';
    }

    if (!preg_match('/^[a-z0-9-]+$/', $values['slug'])) {
        $errors[] = 'Slug sadece küçük harf, rakam ve tire içermelidir.';
    }

    if ($values['excerpt'] === '') {
        $errors[] = 'Özet alanı boş bırakılamaz.';
    }

    if ($values['content'] === '') {
        $errors[] = 'İçerik metni gereklidir.';
    }

    if ($values['category'] === '' || !array_key_exists($values['category'], $categories)) {
        $errors[] = 'Geçerli bir kategori seçmelisiniz.';
    }

    if ($values['author'] === '') {
        $errors[] = 'Yazar bilgisi gereklidir.';
    }

    if ($values['source'] === '') {
        $errors[] = 'Kaynak alanı gereklidir.';
    }

    if ($values['image'] === '') {
        $errors[] = 'Kapak görseli bağlantısı zorunludur.';
    }

    if ($values['published_at'] === '') {
        $errors[] = 'Yayın tarihi gereklidir.';
    }

    $readingTime = (int) $values['reading_time'];
    if ($readingTime <= 0) {
        $errors[] = 'Okuma süresi pozitif bir değer olmalıdır.';
    }

    $existing = fetchArticleBySlug($values['slug']);
    if ($existing !== null && (int) ($existing['id'] ?? 0) !== $articleId) {
        $errors[] = 'Bu slug başka bir haber tarafından kullanılıyor.';
    }

    if (empty($errors)) {
        $contentParts = array_values(array_filter(array_map('trim', preg_split('/\r?\n+/u', $values['content'])), static fn ($paragraph) => $paragraph !== ''));
        $tags = array_values(array_filter(array_map('trim', explode(',', $values['tags'])), static fn ($tag) => $tag !== ''));

        $date = DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $values['published_at'], new DateTimeZone('Europe/Istanbul'));
        if ($date === false) {
            $errors[] = 'Geçerli bir yayın tarihi girin.';
        } else {
            try {
                updateArticle($articleId, [
                    'slug' => $values['slug'],
                    'title' => $values['title'],
                    'excerpt' => $values['excerpt'],
                    'content' => $contentParts,
                    'category' => $values['category'],
                    'author' => $values['author'],
                    'source' => $values['source'],
                    'image' => $values['image'],
                    'published_at' => $date,
                    'reading_time' => $readingTime,
                ], $tags);
                redirectWithMessage('/admin/articles.php', 'success', 'Haber güncellendi.');
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $errors[] = 'Bu slug başka bir haber tarafından kullanılıyor.';
                } else {
                    $errors[] = 'Haber kaydedilirken bir hata oluştu.';
                }
            }
        }
    }
}

$pageTitle = 'Haber Düzenle';
require __DIR__ . '/header.php';
?>
<section class="admin-section">
    <h1 class="admin-section__title">Haber Bilgilerini Güncelle</h1>
    <form method="post" class="admin-form admin-form--two-columns">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <?php if (!empty($errors)): ?>
            <div class="admin-flash">
                <?php foreach ($errors as $error): ?>
                    <div class="admin-flash__item admin-flash__item--error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="title">Başlık</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($values['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($values['slug'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group form-group--full">
            <label for="excerpt">Özet</label>
            <textarea id="excerpt" name="excerpt" rows="3" required><?php echo htmlspecialchars($values['excerpt'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
        <div class="form-group form-group--full">
            <label for="content">İçerik (Her paragrafı yeni satıra yazın)</label>
            <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($values['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
        <div class="form-group">
            <label for="category">Kategori</label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $slugOption => $label): ?>
                    <option value="<?php echo htmlspecialchars($slugOption, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $values['category'] === $slugOption ? 'selected' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="reading_time">Okuma Süresi (dakika)</label>
            <input type="number" id="reading_time" name="reading_time" min="1" value="<?php echo htmlspecialchars($values['reading_time'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group">
            <label for="author">Yazar</label>
            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($values['author'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group">
            <label for="source">Kaynak</label>
            <input type="text" id="source" name="source" value="<?php echo htmlspecialchars($values['source'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Kapak Görseli URL</label>
            <input type="url" id="image" name="image" value="<?php echo htmlspecialchars($values['image'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group">
            <label for="published_at">Yayın Tarihi</label>
            <input type="datetime-local" id="published_at" name="published_at" value="<?php echo htmlspecialchars($values['published_at'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group form-group--full">
            <label for="tags">Etiketler (virgülle ayırın)</label>
            <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($values['tags'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="form-actions">
            <a class="btn btn--ghost" href="/admin/articles.php">İptal</a>
            <button type="submit" class="btn btn--primary">Güncelle</button>
        </div>
    </form>
</section>
<?php require __DIR__ . '/footer.php'; ?>
