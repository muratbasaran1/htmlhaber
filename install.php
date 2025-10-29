<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/storage.php';
require_once __DIR__ . '/includes/helpers.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $serverPdo = rawPdoWithoutDatabase();
        $serverPdo->exec(sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci', DB_NAME));

        $pdo = getPdo();
        $pdo->exec('SET NAMES utf8mb4');

        $schemaStatements = [
            'CREATE TABLE IF NOT EXISTS settings (
                `key` VARCHAR(64) NOT NULL PRIMARY KEY,
                `value` TEXT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            'CREATE TABLE IF NOT EXISTS categories (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                slug VARCHAR(120) NOT NULL UNIQUE,
                name VARCHAR(160) NOT NULL,
                sort_order INT UNSIGNED NOT NULL DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            'CREATE TABLE IF NOT EXISTS articles (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                slug VARCHAR(160) NOT NULL UNIQUE,
                title VARCHAR(255) NOT NULL,
                excerpt TEXT NOT NULL,
                content LONGTEXT NOT NULL,
                category_slug VARCHAR(120) NOT NULL,
                author VARCHAR(160) NOT NULL,
                source VARCHAR(160) NOT NULL,
                image TEXT NOT NULL,
                published_at DATETIME NOT NULL,
                reading_time INT UNSIGNED NOT NULL DEFAULT 4,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_articles_category FOREIGN KEY (category_slug) REFERENCES categories (slug) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            'CREATE TABLE IF NOT EXISTS article_tags (
                article_id INT UNSIGNED NOT NULL,
                tag VARCHAR(160) NOT NULL,
                PRIMARY KEY(article_id, tag),
                CONSTRAINT fk_article_tags_article FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            'CREATE TABLE IF NOT EXISTS editorial_picks (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                link TEXT NOT NULL,
                display_order INT UNSIGNED NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            'CREATE TABLE IF NOT EXISTS live_ticker (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                message TEXT NOT NULL,
                display_order INT UNSIGNED NOT NULL DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            'CREATE TABLE IF NOT EXISTS subscribers (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                subscribed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            'CREATE TABLE IF NOT EXISTS users (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(120) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        ];

        foreach ($schemaStatements as $statement) {
            $pdo->exec($statement);
        }

        // Seed settings
        $settingsStmt = $pdo->prepare('REPLACE INTO settings (`key`, `value`) VALUES (:key, :value)');
        foreach (getDefaultSiteSettings() as $key => $value) {
            $settingsStmt->execute([
                'key' => $key,
                'value' => $value,
            ]);
        }

        // Seed categories
        $categoryCount = (int) $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
        if ($categoryCount === 0) {
            $categoryStmt = $pdo->prepare('INSERT INTO categories (slug, name, sort_order) VALUES (:slug, :name, :sort_order)');
            $order = 1;
            foreach (getDefaultCategories() as $slug => $name) {
                $categoryStmt->execute([
                    'slug' => $slug,
                    'name' => $name,
                    'sort_order' => $order++,
                ]);
            }
        }

        // Seed articles and tags
        $articleCount = (int) $pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn();
        if ($articleCount === 0) {
            $articleStmt = $pdo->prepare('INSERT INTO articles (slug, title, excerpt, content, category_slug, author, source, image, published_at, reading_time)
                VALUES (:slug, :title, :excerpt, :content, :category_slug, :author, :source, :image, :published_at, :reading_time)');
            $tagStmt = $pdo->prepare('INSERT INTO article_tags (article_id, tag) VALUES (:article_id, :tag)');

            foreach (getDefaultArticles() as $article) {
                $contentJson = json_encode($article['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($contentJson === false) {
                    $contentJson = '[]';
                }

                $publishedAt = (new DateTimeImmutable($article['published_at']))
                    ->setTimezone(new DateTimeZone('Europe/Istanbul'))
                    ->format('Y-m-d H:i:s');

                $articleStmt->execute([
                    'slug' => $article['slug'],
                    'title' => $article['title'],
                    'excerpt' => $article['excerpt'],
                    'content' => $contentJson,
                    'category_slug' => $article['category'],
                    'author' => $article['author'],
                    'source' => $article['source'],
                    'image' => $article['image'],
                    'published_at' => $publishedAt,
                    'reading_time' => $article['reading_time'],
                ]);

                $articleId = (int) $pdo->lastInsertId();
                foreach ($article['tags'] as $tag) {
                    $tagStmt->execute([
                        'article_id' => $articleId,
                        'tag' => $tag,
                    ]);
                }
            }
        }

        // Seed editorial picks
        $editorialCount = (int) $pdo->query('SELECT COUNT(*) FROM editorial_picks')->fetchColumn();
        if ($editorialCount === 0) {
            $editorialStmt = $pdo->prepare('INSERT INTO editorial_picks (title, link, display_order) VALUES (:title, :link, :display_order)');
            foreach (getDefaultEditorialPicks() as $index => $pick) {
                $editorialStmt->execute([
                    'title' => $pick['title'],
                    'link' => $pick['link'],
                    'display_order' => $index + 1,
                ]);
            }
        }

        // Seed live ticker
        $tickerCount = (int) $pdo->query('SELECT COUNT(*) FROM live_ticker')->fetchColumn();
        if ($tickerCount === 0) {
            $tickerStmt = $pdo->prepare('INSERT INTO live_ticker (message, display_order) VALUES (:message, :display_order)');
            foreach (getDefaultLiveTicker() as $index => $message) {
                $tickerStmt->execute([
                    'message' => $message,
                    'display_order' => $index + 1,
                ]);
            }
        }

        // Seed admin user
        $userCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        if ($userCount === 0) {
            $userStmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)');
            $userStmt->execute([
                'username' => 'admin',
                'password_hash' => '$2y$12$yJ6oX0BjB2j5Xzv.TAtJr.76oxoa0wvHfZ/ubZDaJGFfw9xNPQubS',
            ]);
        }

        $success = true;
    } catch (Throwable $exception) {
        $errors[] = $exception->getMessage();
    }
}

$isInstalled = isApplicationInstalled();

?><!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haber Merkezi Kurulum</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #0b1f3a;
            background: #f5f7fb;
        }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .installer {
            background: #fff;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(12, 28, 58, 0.1);
            width: min(520px, 92vw);
        }
        h1 {
            margin-top: 0;
            font-size: 1.8rem;
        }
        p {
            color: #4b5b78;
            line-height: 1.6;
        }
        .status {
            padding: 1rem 1.4rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .status--success {
            background: rgba(34, 197, 94, 0.12);
            color: #15803d;
        }
        .status--error {
            background: rgba(220, 38, 38, 0.12);
            color: #b91c1c;
        }
        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        button, a.button {
            flex: 1;
            border: none;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-weight: 600;
            cursor: pointer;
            background: #1f6feb;
            color: #fff;
            text-align: center;
            text-decoration: none;
        }
        button[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .button--secondary {
            background: rgba(15, 23, 42, 0.08);
            color: #0b1f3a;
        }
        ul {
            padding-left: 1.2rem;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="installer">
        <h1>Haber Merkezi Kurulum</h1>
        <p>MySQL veritabanı bağlantısı otomatik yapılandırılır ve örnek içeriklerle birlikte yönetim paneli hazır hale getirilir.</p>
        <?php if (!empty($errors)): ?>
            <div class="status status--error">
                Kurulum sırasında bir hata oluştu:
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success || $isInstalled): ?>
            <div class="status status--success">
                Kurulum tamamlandı. Yönetim paneline <strong>admin / admin123</strong> bilgileriyle giriş yapabilirsiniz.
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="actions">
                <button type="submit" <?php echo $success || $isInstalled ? 'disabled' : ''; ?>><?php echo $isInstalled ? 'Kurulum Tamamlandı' : 'Kurulumu Başlat'; ?></button>
                <a class="button button--secondary" href="/index.php">Siteyi Görüntüle</a>
            </div>
        </form>
    </div>
</body>
</html>
