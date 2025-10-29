<?php

declare(strict_types=1);

require_once __DIR__ . '/database.php';

function isApplicationInstalled(): bool
{
    return databaseTableExists('settings') && databaseTableExists('articles');
}

/**
 * @return array<string, string>
 */
function getDefaultSiteSettings(): array
{
    return [
        'site_name' => 'Haber Merkezi',
        'site_tagline' => 'Türkiye ve dünyadan en güvenilir haberler',
        'contact_email' => 'iletisim@habermerkezi.com',
        'logo_url' => 'https://dummyimage.com/240x80/003049/ffffff&text=Haber+Merkezi',
        'favicon_url' => 'https://dummyimage.com/64x64/003049/ffffff&text=HM',
        'primary_color' => '#d62828',
        'secondary_color' => '#003049',
        'footer_about' => 'Haber Merkezi, Türkiye ve dünyadan en güvenilir haberleri tarafsız bakış açısıyla sunmayı amaçlayan bağımsız bir haber platformudur.',
        'footer_phone' => '+90 212 555 00 00',
        'footer_address' => 'Büyükdere Cad. No: 1, Şişli / İstanbul',
        'terms_url' => 'https://www.habermerkezi.com/kullanim-sartlari',
        'privacy_url' => 'https://www.habermerkezi.com/gizlilik-politikasi',
        'facebook_url' => 'https://facebook.com',
        'twitter_url' => 'https://twitter.com',
        'instagram_url' => 'https://instagram.com',
        'youtube_url' => 'https://youtube.com',
    ];
}

/**
 * @return array<string, string>
 */
function getDefaultCategories(): array
{
    return [
        'gundem' => 'Gündem',
        'ekonomi' => 'Ekonomi',
        'dunya' => 'Dünya',
        'teknoloji' => 'Teknoloji',
        'saglik' => 'Sağlık',
        'spor' => 'Spor',
        'kultur' => 'Kültür & Sanat',
    ];
}

/**
 * @return array<int, array<string, mixed>>
 */
function getDefaultArticles(): array
{
    return [
        [
            'slug' => 'istanbulda-ulasimda-yeni-donem',
            'title' => 'İstanbul’da Ulaşımda Yeni Dönem Başlıyor',
            'excerpt' => 'Mega kentte ulaşım ağını güçlendirecek yeni projede açılış için geri sayım başladı.',
            'content' => [
                'Ulaştırma ve Altyapı Bakanlığı, İstanbul’da kent içi ulaşımı rahatlatacak yeni metro hattının açılış tarihini duyurdu. Yetkililer, hattın günlük 1,2 milyon yolcuya hizmet vereceğini belirtti.',
                'Uzmanlar, projenin özellikle sabah ve akşam trafiğini büyük ölçüde hafifleteceğini ifade ediyor. Vatandaşlar ise kısa sürede bitirilmesini bekledikleri projenin ekonomi ve çevre için önemli bir adım olduğuna dikkat çekti.',
                'Açılış töreninin önümüzdeki ay Cumhurbaşkanı ve çok sayıda davetlinin katılımıyla yapılması planlanıyor. Tören kapsamında yerli teknolojilerin tanıtılacağı bir sergi de düzenlenecek.'
            ],
            'category' => 'gundem',
            'author' => 'Elif Korkmaz',
            'source' => 'AA',
            'image' => 'https://images.unsplash.com/photo-1528909514045-2fa4ac7a08ba?auto=format&fit=crop&w=1400&q=80',
            'published_at' => '2025-05-16T08:15:00+03:00',
            'reading_time' => 4,
            'tags' => ['metro', 'ulaşım', 'altyapı']
        ],
        [
            'slug' => 'turkiyenin-yapay-zeka-stratejisi-aciklandi',
            'title' => 'Türkiye’nin Yapay Zekâ Stratejisi Açıklandı',
            'excerpt' => 'Kamu ve özel sektör temsilcilerinin katıldığı zirvede, yapay zekâ yatırımlarında yeni dönem resmen başladı.',
            'content' => [
                'Cumhurbaşkanlığı Dijital Dönüşüm Ofisi tarafından açıklanan strateji, Türkiye’nin 2030 yılına kadar yapay zekâ alanında bölgesel lider olmasını hedefliyor.',
                'Strateji belgesinde eğitim, sağlık, savunma ve tarım başta olmak üzere birçok sektörde yapay zekâ uygulamalarının yaygınlaştırılması öngörülüyor.',
                'Belge kapsamında yerli girişimlere 5 milyar TL’yi aşkın destek sağlanması ve uluslararası iş birliklerinin artırılması bekleniyor.'
            ],
            'category' => 'teknoloji',
            'author' => 'Selim Arslan',
            'source' => 'AA',
            'image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1400&q=80',
            'published_at' => '2025-05-15T11:45:00+03:00',
            'reading_time' => 5,
            'tags' => ['yapay zekâ', 'startuplar', 'teknoloji yatırımları']
        ],
        [
            'slug' => 'global-piyasalarda-enflasyon-baskisi',
            'title' => 'Global Piyasalarda Enflasyon Baskısı Sürüyor',
            'excerpt' => 'Dünya genelinde artan gıda ve enerji fiyatları, merkez bankalarını yeni adımlar atmaya zorluyor.',
            'content' => [
                'ABD ve Avrupa merkez bankalarının faiz artışlarına rağmen enflasyon baskısının sürdüğü belirtiliyor. Ekonomistler, enerji fiyatlarındaki oynaklığın en önemli risk olduğunu vurguladı.',
                'Uzmanlar, gelişmekte olan ülkelerin döviz rezervlerini güçlendirmesi ve mali disiplin politikalarına ağırlık vermesi gerektiğini ifade etti.',
                'Türkiye’de ise enflasyonun yılın ikinci yarısında kademeli olarak gerilemesi bekleniyor. Analistler, ihracat performansındaki artışın cari dengeye olumlu katkı sağlayacağını düşünüyor.'
            ],
            'category' => 'ekonomi',
            'author' => 'Merve Yılmaz',
            'source' => 'Bloomberg HT',
            'image' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1400&q=80',
            'published_at' => '2025-05-16T07:30:00+03:00',
            'reading_time' => 6,
            'tags' => ['ekonomi', 'enflasyon', 'piyasalar']
        ],
        [
            'slug' => 'avrupada-saglikta-yeni-dijital-atilim',
            'title' => 'Avrupa’da Sağlıkta Yeni Dijital Atılım',
            'excerpt' => 'Avrupa Birliği, dijital sağlık kayıtlarını üye ülkelerde ortak standartlara taşıyacak yeni programını duyurdu.',
            'content' => [
                'Program kapsamında hasta verilerinin güvenli şekilde paylaşılması için blok zinciri tabanlı çözümler test edilecek. Ayrıca yapay zekâ destekli teşhis uygulamaları da teşvik edilecek.',
                'Sağlık Bakanı Fahrettin Koca, Türkiye’nin de projeye gözlemci ülke olarak katılacağını ve deneyimlerden faydalanmak istediğini söyledi.',
                'Uzmanlar, dijital dönüşümün özellikle kırsal bölgelerde sağlık hizmetlerine erişimi artıracağını savunuyor.'
            ],
            'category' => 'saglik',
            'author' => 'Deniz Demir',
            'source' => 'Euronews',
            'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1400&q=80',
            'published_at' => '2025-05-14T09:05:00+03:00',
            'reading_time' => 3,
            'tags' => ['sağlık', 'dijital dönüşüm', 'avrupa birliği']
        ],
        [
            'slug' => 'milli-takim-euro-2028-hazirliklari',
            'title' => 'Milli Takım EURO 2028 Hazırlıklarını Hızlandırdı',
            'excerpt' => 'A Milli Futbol Takımı, EURO 2028 yolunda Antalya kampında yoğun tempoda çalışıyor.',
            'content' => [
                'Teknik direktör, genç oyunculara şans vererek takımın dinamizmini artırmayı hedefliyor. Kamp süresince üç hazırlık maçı yapılacağı açıklandı.',
                'Oyuncular, yeni antrenman tesislerinin modern teknolojilerle donatılmasının performanslarını artırdığını belirtti.',
                'Federasyon, taraftarların kamp sürecini dijital platformlardan canlı takip edebileceğini duyurdu.'
            ],
            'category' => 'spor',
            'author' => 'Ayşe Gür',
            'source' => 'TRT Spor',
            'image' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?auto=format&fit=crop&w=1400&q=80',
            'published_at' => '2025-05-13T18:20:00+03:00',
            'reading_time' => 3,
            'tags' => ['milli takım', 'futbol', 'euro 2028']
        ],
        [
            'slug' => 'uluslararasi-film-festivalinde-turk-damgasi',
            'title' => 'Uluslararası Film Festivalinde Türk Damgası',
            'excerpt' => 'Bu yıl 45’incisi düzenlenen festivalde, genç yönetmenlerin filmleri büyük ilgi gördü.',
            'content' => [
                'Festival kapsamında gösterilen beş Türk yapımı film, uluslararası basında geniş yer buldu. Yönetmenler, özgün hikâyeleri ve güçlü oyunculuklarıyla takdir topladı.',
                'Jüri üyeleri, Türkiye’den gelen yapımların teknik anlamda da büyük ilerleme kaydettiğini vurguladı. Film ekipleri, ortak yapım projeleri için yeni anlaşmalar yapmaya hazırlanıyor.',
                'Festivalin kapanış gecesinde Türk yönetmenlerden biri "En İyi Senaryo" ödülünü alarak büyük sevinç yaşadı.'
            ],
            'category' => 'kultur',
            'author' => 'Mustafa Kaplan',
            'source' => 'Anadolu Ajansı',
            'image' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1400&q=80',
            'published_at' => '2025-05-12T21:50:00+03:00',
            'reading_time' => 4,
            'tags' => ['sinema', 'festival', 'kültür sanat']
        ],
        [
            'slug' => 'anadoluda-yukselen-tarim-teknolojileri',
            'title' => 'Anadolu’da Yükselen Tarım Teknolojileri',
            'excerpt' => 'Yerel girişimler, tarımsal üretimi artırmak için sensör ve drone çözümleri geliştiriyor.',
            'content' => [
                'Konya ve Şanlıurfa’da faaliyet gösteren tarım teknoloji şirketleri, sulama ve gübrelemeyi optimize eden yapay zekâ destekli sistemleri tanıttı.',
                'Çiftçiler, dronelar sayesinde tarlaların anlık durumunu takip ederek verimlilikte %30’a varan artış yakalıyor. Proje kapsamında devlet desteklerinin de genişletilmesi bekleniyor.',
                'Uzmanlar, tarım teknolojilerinin kırsal kalkınma ve gıda güvenliği için kritik önem taşıdığını vurguluyor.'
            ],
            'category' => 'dunya',
            'author' => 'Gamze Yıldız',
            'source' => 'Reuters',
            'image' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=1400&q=80',
            'published_at' => '2025-05-11T14:10:00+03:00',
            'reading_time' => 5,
            'tags' => ['tarım', 'teknoloji', 'girişimcilik']
        ]
    ];
}

/**
 * @return array<int, array<string, string>>
 */
function getDefaultEditorialPicks(): array
{
    return [
        [
            'title' => 'Uzay Turizminde Yeni Hedefler',
            'link' => '#',
        ],
        [
            'title' => 'Sürdürülebilir Enerjide Türkiye’nin Rotası',
            'link' => '#',
        ],
        [
            'title' => 'Gençler İçin Dijital Okuryazarlık Programları',
            'link' => '#',
        ],
    ];
}

/**
 * @return array<int, string>
 */
function getDefaultLiveTicker(): array
{
    return [
        'Borsa İstanbul güne %1,2 yükselişle başladı.',
        'Dolar/TL 32,45 seviyesinde dengelendi.',
        'Avrupa Merkez Bankası faiz kararını bugün açıklayacak.',
        'Sağlık Bakanlığı aile hekimliği sisteminde yeni düzenleme yapıyor.',
    ];
}

/**
 * @param array<string, mixed> $row
 * @return array<string, mixed>
 */
function hydrateArticleRow(array $row): array
{
    $rawContent = $row['content'] ?? '[]';
    $content = json_decode((string) $rawContent, true);

    if (!is_array($content)) {
        $content = array_values(array_filter(
            array_map('trim', preg_split('/\r?\n+/u', (string) $rawContent) ?: []),
            static fn ($paragraph) => $paragraph !== ''
        ));
    }

    $publishedAt = $row['published_at'] ?? '';
    $publishedIso = $publishedAt;

    if (is_string($publishedAt) && $publishedAt !== '') {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $publishedAt, new DateTimeZone('Europe/Istanbul'));
        if ($date instanceof DateTimeImmutable) {
            $publishedIso = $date->format(DateTimeInterface::ATOM);
        }
    }

    if (!is_string($publishedIso) || $publishedIso === '') {
        $publishedIso = (new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul')))->format(DateTimeInterface::ATOM);
    }

    $tags = [];

    if (!empty($row['tag_list'])) {
        $tags = array_values(array_filter(
            array_map('trim', explode('|#|', (string) $row['tag_list'])),
            static fn ($tag) => $tag !== ''
        ));
    }

    return [
        'id' => (int) ($row['id'] ?? 0),
        'slug' => (string) ($row['slug'] ?? ''),
        'title' => (string) ($row['title'] ?? ''),
        'excerpt' => (string) ($row['excerpt'] ?? ''),
        'content' => $content,
        'category' => (string) ($row['category_slug'] ?? ''),
        'author' => (string) ($row['author'] ?? ''),
        'source' => (string) ($row['source'] ?? ''),
        'image' => (string) ($row['image'] ?? ''),
        'published_at' => $publishedIso,
        'reading_time' => (int) ($row['reading_time'] ?? 0),
        'tags' => $tags,
    ];
}

/**
 * @return array<string, string>
 */
function loadCategories(): array
{
    if (!isApplicationInstalled()) {
        return getDefaultCategories();
    }

    $pdo = getPdo();
    $stmt = $pdo->query('SELECT slug, name FROM categories ORDER BY sort_order ASC, name ASC');

    $categories = [];
    foreach ($stmt as $row) {
        $categories[$row['slug']] = $row['name'];
    }

    if (empty($categories)) {
        return getDefaultCategories();
    }

    return $categories;
}

/**
 * @return array<string, string>
 */
function loadSiteSettings(): array
{
    $defaults = getDefaultSiteSettings();

    if (!isApplicationInstalled()) {
        return $defaults;
    }

    $pdo = getPdo();
    $stmt = $pdo->query('SELECT `key`, `value` FROM settings');
    $settings = $defaults;

    foreach ($stmt as $row) {
        $key = (string) ($row['key'] ?? '');
        if ($key === '' || !array_key_exists($key, $defaults)) {
            continue;
        }

        $settings[$key] = (string) ($row['value'] ?? '');
    }

    return $settings;
}

/**
 * @return array<int, array<string, mixed>>
 */
function loadArticles(int $limit = 0, int $offset = 0): array
{
    if (!isApplicationInstalled()) {
        $articles = getDefaultArticles();
        usort($articles, static fn (array $a, array $b): int => strcmp($b['published_at'], $a['published_at']));

        return $limit > 0 ? array_slice($articles, $offset, $limit) : $articles;
    }

    $pdo = getPdo();
    $sql = 'SELECT a.id, a.slug, a.title, a.excerpt, a.content, a.category_slug, a.author, a.source, a.image, a.published_at, a.reading_time,
            GROUP_CONCAT(at.tag ORDER BY at.tag SEPARATOR "|#|") AS tag_list
        FROM articles a
        LEFT JOIN article_tags at ON at.article_id = a.id
        GROUP BY a.id
        ORDER BY a.published_at DESC';

    if ($limit > 0) {
        $sql .= ' LIMIT :limit OFFSET :offset';
    }

    $stmt = $pdo->prepare($sql);

    if ($limit > 0) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $stmt->execute();

    $articles = [];
    while ($row = $stmt->fetch()) {
        $articles[] = hydrateArticleRow($row);
    }

    return $articles;
}

/**
 * @return array<int, array<string, string>>
 */
function loadEditorialPicks(): array
{
    if (!isApplicationInstalled()) {
        return getDefaultEditorialPicks();
    }

    $pdo = getPdo();
    $stmt = $pdo->query('SELECT title, link FROM editorial_picks ORDER BY display_order ASC, id ASC');

    $picks = $stmt->fetchAll();

    if (empty($picks)) {
        return getDefaultEditorialPicks();
    }

    return array_map(static fn ($pick): array => [
        'title' => (string) ($pick['title'] ?? ''),
        'link' => (string) ($pick['link'] ?? '#'),
    ], $picks);
}

/**
 * @return array<int, string>
 */
function loadLiveTicker(): array
{
    if (!isApplicationInstalled()) {
        return getDefaultLiveTicker();
    }

    $pdo = getPdo();
    $stmt = $pdo->query('SELECT message FROM live_ticker ORDER BY display_order ASC, id DESC');
    $messages = [];

    foreach ($stmt as $row) {
        $message = trim((string) ($row['message'] ?? ''));
        if ($message !== '') {
            $messages[] = $message;
        }
    }

    if (empty($messages)) {
        return getDefaultLiveTicker();
    }

    return $messages;
}

/**
 * @return array<int, array<string, string>>
 */
function loadSubscribers(): array
{
    if (!isApplicationInstalled()) {
        return [];
    }

    $pdo = getPdo();
    $stmt = $pdo->query('SELECT email, subscribed_at FROM subscribers ORDER BY subscribed_at DESC');
    $subscribers = [];

    foreach ($stmt as $row) {
        $record = [
            'email' => (string) ($row['email'] ?? ''),
            'subscribed_at' => '',
        ];

        if (!empty($row['subscribed_at'])) {
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $row['subscribed_at'], new DateTimeZone('Europe/Istanbul'));
            if ($date instanceof DateTimeImmutable) {
                $record['subscribed_at'] = $date->format(DateTimeInterface::ATOM);
            }
        }

        $subscribers[] = $record;
    }

    return $subscribers;
}

/**
 * @param array<string, string> $settings
 */
function saveSiteSettings(array $settings): void
{
    if (!isApplicationInstalled()) {
        return;
    }

    $allowedKeys = array_keys(getDefaultSiteSettings());
    $pdo = getPdo();
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('REPLACE INTO settings (`key`, `value`) VALUES (:key, :value)');
        foreach ($settings as $key => $value) {
            if (!in_array($key, $allowedKeys, true)) {
                continue;
            }

            $stmt->execute([
                'key' => $key,
                'value' => (string) $value,
            ]);
        }

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

function createCategory(string $slug, string $name): void
{
    if (!isApplicationInstalled()) {
        return;
    }

    $pdo = getPdo();
    $sortOrder = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) FROM categories')->fetchColumn();
    $stmt = $pdo->prepare('INSERT INTO categories (slug, name, sort_order) VALUES (:slug, :name, :sort_order)');
    $stmt->execute([
        'slug' => $slug,
        'name' => $name,
        'sort_order' => $sortOrder + 1,
    ]);
}

function deleteCategory(string $slug): void
{
    if (!isApplicationInstalled()) {
        return;
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('DELETE FROM categories WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
}

/**
 * @param array<int, string> $orderedSlugs
 */
function reorderCategories(array $orderedSlugs): void
{
    if (!isApplicationInstalled()) {
        return;
    }

    $pdo = getPdo();
    $existingStmt = $pdo->query('SELECT slug FROM categories');
    $existingSlugs = $existingStmt !== false ? $existingStmt->fetchAll(PDO::FETCH_COLUMN) : [];

    if (empty($existingSlugs)) {
        return;
    }

    $filtered = array_values(array_unique(array_map(static function ($slug): string {
        return trim((string) $slug);
    }, $orderedSlugs)));

    $filtered = array_values(array_filter($filtered, static function (string $slug) use ($existingSlugs): bool {
        return $slug !== '' && in_array($slug, $existingSlugs, true);
    }));

    if (empty($filtered)) {
        return;
    }

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('UPDATE categories SET sort_order = :sort_order WHERE slug = :slug');
        $order = 1;

        foreach ($filtered as $slug) {
            $stmt->execute([
                'slug' => $slug,
                'sort_order' => $order++,
            ]);
        }

        $remaining = array_values(array_diff($existingSlugs, $filtered));

        foreach ($remaining as $slug) {
            $stmt->execute([
                'slug' => $slug,
                'sort_order' => $order++,
            ]);
        }

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

/**
 * @param array<string, mixed> $payload
 * @param array<int, string> $tags
 */
function createArticle(array $payload, array $tags): void
{
    if (!isApplicationInstalled()) {
        return;
    }

    $pdo = getPdo();
    $pdo->beginTransaction();

    try {
        /** @var DateTimeInterface $published */
        $published = $payload['published_at'];
        $contentJson = json_encode($payload['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($contentJson === false) {
            $contentJson = '[]';
        }

        $stmt = $pdo->prepare('INSERT INTO articles (slug, title, excerpt, content, category_slug, author, source, image, published_at, reading_time, created_at, updated_at)
            VALUES (:slug, :title, :excerpt, :content, :category, :author, :source, :image, :published_at, :reading_time, NOW(), NOW())');
        $stmt->execute([
            'slug' => $payload['slug'],
            'title' => $payload['title'],
            'excerpt' => $payload['excerpt'],
            'content' => $contentJson,
            'category' => $payload['category'],
            'author' => $payload['author'],
            'source' => $payload['source'],
            'image' => $payload['image'],
            'published_at' => $published->setTimezone(new DateTimeZone('Europe/Istanbul'))->format('Y-m-d H:i:s'),
            'reading_time' => $payload['reading_time'],
        ]);

        $articleId = (int) $pdo->lastInsertId();
        insertArticleTags($pdo, $articleId, $tags);

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

/**
 * @param PDO $pdo
 * @param int $articleId
 * @param array<int, string> $tags
 */
function insertArticleTags(PDO $pdo, int $articleId, array $tags): void
{
    $pdo->prepare('DELETE FROM article_tags WHERE article_id = :article_id')->execute(['article_id' => $articleId]);

    if (empty($tags)) {
        return;
    }

    $stmt = $pdo->prepare('INSERT INTO article_tags (article_id, tag) VALUES (:article_id, :tag)');
    foreach ($tags as $tag) {
        $normalized = trim($tag);
        if ($normalized === '') {
            continue;
        }

        $stmt->execute([
            'article_id' => $articleId,
            'tag' => $normalized,
        ]);
    }
}

/**
 * @param array<string, mixed> $payload
 * @param array<int, string> $tags
 */
function updateArticle(int $articleId, array $payload, array $tags): void
{
    if (!isApplicationInstalled()) {
        return;
    }

    $pdo = getPdo();
    $pdo->beginTransaction();

    try {
        /** @var DateTimeInterface $published */
        $published = $payload['published_at'];
        $contentJson = json_encode($payload['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($contentJson === false) {
            $contentJson = '[]';
        }

        $stmt = $pdo->prepare('UPDATE articles SET slug = :slug, title = :title, excerpt = :excerpt, content = :content, category_slug = :category,
            author = :author, source = :source, image = :image, published_at = :published_at, reading_time = :reading_time, updated_at = NOW()
            WHERE id = :id');
        $stmt->execute([
            'id' => $articleId,
            'slug' => $payload['slug'],
            'title' => $payload['title'],
            'excerpt' => $payload['excerpt'],
            'content' => $contentJson,
            'category' => $payload['category'],
            'author' => $payload['author'],
            'source' => $payload['source'],
            'image' => $payload['image'],
            'published_at' => $published->setTimezone(new DateTimeZone('Europe/Istanbul'))->format('Y-m-d H:i:s'),
            'reading_time' => $payload['reading_time'],
        ]);

        insertArticleTags($pdo, $articleId, $tags);

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

function deleteArticleBySlug(string $slug): void
{
    if (!isApplicationInstalled()) {
        return;
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('DELETE FROM articles WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
}

/**
 * @param array<int, array<string, string>> $picks
 */
function saveEditorialPicks(array $picks): void
{
    if (!isApplicationInstalled()) {
        return;
    }

    $pdo = getPdo();
    $pdo->beginTransaction();

    try {
        $pdo->exec('DELETE FROM editorial_picks');
        $stmt = $pdo->prepare('INSERT INTO editorial_picks (title, link, display_order) VALUES (:title, :link, :display_order)');

        foreach (array_values($picks) as $index => $pick) {
            $stmt->execute([
                'title' => $pick['title'],
                'link' => $pick['link'],
                'display_order' => $index + 1,
            ]);
        }

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

/**
 * @param array<int, string> $messages
 */
function saveLiveTicker(array $messages): void
{
    if (!isApplicationInstalled()) {
        return;
    }

    $pdo = getPdo();
    $pdo->beginTransaction();

    try {
        $pdo->exec('DELETE FROM live_ticker');
        $stmt = $pdo->prepare('INSERT INTO live_ticker (message, display_order, created_at) VALUES (:message, :display_order, NOW())');

        foreach (array_values($messages) as $index => $message) {
            $trimmed = trim($message);
            if ($trimmed === '') {
                continue;
            }

            $stmt->execute([
                'message' => $trimmed,
                'display_order' => $index + 1,
            ]);
        }

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

function addSubscriber(string $email): array
{
    $normalizedEmail = mb_strtolower(trim($email));

    if ($normalizedEmail === '' || !filter_var($normalizedEmail, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Geçerli bir e-posta adresi giriniz.');
    }

    if (!isApplicationInstalled()) {
        return [
            'email' => $normalizedEmail,
            'subscribed_at' => (new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul')))->format(DateTimeInterface::ATOM),
        ];
    }

    $pdo = getPdo();

    try {
        $stmt = $pdo->prepare('INSERT INTO subscribers (email, subscribed_at) VALUES (:email, NOW())');
        $stmt->execute(['email' => $normalizedEmail]);
    } catch (PDOException $exception) {
        if ($exception->getCode() === '23000') {
            throw new RuntimeException('Bu e-posta adresi zaten kayıtlı.');
        }

        throw $exception;
    }

    $date = new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul'));

    return [
        'email' => $normalizedEmail,
        'subscribed_at' => $date->format(DateTimeInterface::ATOM),
    ];
}

function getArticleCount(): int
{
    if (!isApplicationInstalled()) {
        return count(getDefaultArticles());
    }

    $pdo = getPdo();
    $count = $pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn();

    return (int) $count;
}

function getSubscriberCount(): int
{
    if (!isApplicationInstalled()) {
        return 0;
    }

    $pdo = getPdo();
    $count = $pdo->query('SELECT COUNT(*) FROM subscribers')->fetchColumn();

    return (int) $count;
}

function getLiveTickerCount(): int
{
    if (!isApplicationInstalled()) {
        return count(getDefaultLiveTicker());
    }

    $pdo = getPdo();
    $count = $pdo->query('SELECT COUNT(*) FROM live_ticker')->fetchColumn();

    return (int) $count;
}

function fetchArticleBySlug(string $slug): ?array
{
    if (!isApplicationInstalled()) {
        foreach (getDefaultArticles() as $index => $article) {
            if ($article['slug'] === $slug) {
                $article['id'] = $index + 1;
                return $article;
            }
        }

        return null;
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('SELECT a.id, a.slug, a.title, a.excerpt, a.content, a.category_slug, a.author, a.source, a.image, a.published_at, a.reading_time,
            GROUP_CONCAT(at.tag ORDER BY at.tag SEPARATOR "|#|") AS tag_list
        FROM articles a
        LEFT JOIN article_tags at ON at.article_id = a.id
        WHERE a.slug = :slug
        GROUP BY a.id');
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();

    if (!$row) {
        return null;
    }

    return hydrateArticleRow($row);
}

function fetchArticleForEditing(string $slug): ?array
{
    if (!isApplicationInstalled()) {
        $article = fetchArticleBySlug($slug);
        if (is_array($article)) {
            return $article;
        }

        return null;
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('SELECT a.id, a.slug, a.title, a.excerpt, a.content, a.category_slug, a.author, a.source, a.image, a.published_at, a.reading_time,
            GROUP_CONCAT(at.tag ORDER BY at.tag SEPARATOR "|#|") AS tag_list
        FROM articles a
        LEFT JOIN article_tags at ON at.article_id = a.id
        WHERE a.slug = :slug
        GROUP BY a.id');
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();

    if (!$row) {
        return null;
    }

    return hydrateArticleRow($row);
}

function fetchArticlesByCategory(string $category, int $limit = 0, int $offset = 0): array
{
    if (!isApplicationInstalled()) {
        $articles = array_values(array_filter(getDefaultArticles(), static fn ($article) => $article['category'] === $category));
        return $limit > 0 ? array_slice($articles, $offset, $limit) : $articles;
    }

    $pdo = getPdo();
    $sql = 'SELECT a.id, a.slug, a.title, a.excerpt, a.content, a.category_slug, a.author, a.source, a.image, a.published_at, a.reading_time,
            GROUP_CONCAT(at.tag ORDER BY at.tag SEPARATOR "|#|") AS tag_list
        FROM articles a
        LEFT JOIN article_tags at ON at.article_id = a.id
        WHERE a.category_slug = :category
        GROUP BY a.id
        ORDER BY a.published_at DESC';

    if ($limit > 0) {
        $sql .= ' LIMIT :limit OFFSET :offset';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':category', $category);
    if ($limit > 0) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $stmt->execute();

    $articles = [];
    while ($row = $stmt->fetch()) {
        $articles[] = hydrateArticleRow($row);
    }

    return $articles;
}

function fetchArticlesByTag(string $tag, int $limit = 0, int $offset = 0): array
{
    if (!isApplicationInstalled()) {
        $normalized = mb_strtolower($tag);
        $articles = array_values(array_filter(getDefaultArticles(), static function ($article) use ($normalized): bool {
            foreach ($article['tags'] as $articleTag) {
                if (mb_strtolower($articleTag) === $normalized) {
                    return true;
                }
            }

            return false;
        }));

        return $limit > 0 ? array_slice($articles, $offset, $limit) : $articles;
    }

    $pdo = getPdo();
    $sql = 'SELECT a.id, a.slug, a.title, a.excerpt, a.content, a.category_slug, a.author, a.source, a.image, a.published_at, a.reading_time,
            GROUP_CONCAT(at.tag ORDER BY at.tag SEPARATOR "|#|") AS tag_list
        FROM articles a
        INNER JOIN article_tags at ON at.article_id = a.id
        WHERE LOWER(at.tag) = LOWER(:tag)
        GROUP BY a.id
        ORDER BY a.published_at DESC';

    if ($limit > 0) {
        $sql .= ' LIMIT :limit OFFSET :offset';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':tag', $tag);
    if ($limit > 0) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $stmt->execute();

    $articles = [];
    while ($row = $stmt->fetch()) {
        $articles[] = hydrateArticleRow($row);
    }

    return $articles;
}

function searchArticlesFromDatabase(string $query): array
{
    $needle = mb_strtolower(trim($query));

    if ($needle === '') {
        return [];
    }

    if (!isApplicationInstalled()) {
        return array_values(array_filter(getDefaultArticles(), static function ($article) use ($needle): bool {
            $haystack = mb_strtolower($article['title'] . ' ' . $article['excerpt'] . ' ' . implode(' ', $article['tags']));
            return mb_strpos($haystack, $needle) !== false;
        }));
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('SELECT a.id, a.slug, a.title, a.excerpt, a.content, a.category_slug, a.author, a.source, a.image, a.published_at, a.reading_time,
            GROUP_CONCAT(at.tag ORDER BY at.tag SEPARATOR "|#|") AS tag_list
        FROM articles a
        LEFT JOIN article_tags at ON at.article_id = a.id
        WHERE LOWER(a.title) LIKE :query OR LOWER(a.excerpt) LIKE :query OR LOWER(a.content) LIKE :query
        GROUP BY a.id
        ORDER BY a.published_at DESC');
    $stmt->execute(['query' => '%' . $needle . '%']);

    $articles = [];
    while ($row = $stmt->fetch()) {
        $articles[] = hydrateArticleRow($row);
    }

    return $articles;
}

function fetchRelatedArticles(string $slug, int $limit = 3): array
{
    $current = fetchArticleBySlug($slug);

    if ($current === null) {
        return [];
    }

    if (!isApplicationInstalled()) {
        $articles = array_filter(getDefaultArticles(), static fn ($article) => $article['slug'] !== $slug);
        $related = array_filter($articles, static function ($article) use ($current): bool {
            return $article['category'] === $current['category'] || count(array_intersect($article['tags'], $current['tags'])) > 0;
        });

        return array_slice(array_values($related), 0, $limit);
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('SELECT DISTINCT a.id, a.slug, a.title, a.excerpt, a.content, a.category_slug, a.author, a.source, a.image, a.published_at, a.reading_time,
            GROUP_CONCAT(at2.tag ORDER BY at2.tag SEPARATOR "|#|") AS tag_list
        FROM articles a
        LEFT JOIN article_tags at ON at.article_id = a.id
        LEFT JOIN article_tags at2 ON at2.article_id = a.id
        WHERE a.slug <> :slug AND (a.category_slug = :category OR at.tag IN (SELECT tag FROM article_tags WHERE article_id = (SELECT id FROM articles WHERE slug = :slug LIMIT 1)))
        GROUP BY a.id
        ORDER BY a.published_at DESC
        LIMIT :limit');
    $stmt->bindValue(':slug', $slug);
    $stmt->bindValue(':category', $current['category']);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $articles = [];
    while ($row = $stmt->fetch()) {
        $articles[] = hydrateArticleRow($row);
    }

    return $articles;
}

function findAdminUser(string $username): ?array
{
    if (!isApplicationInstalled()) {
        if ($username === 'admin') {
            return [
                'username' => 'admin',
                'password_hash' => '$2y$12$yJ6oX0BjB2j5Xzv.TAtJr.76oxoa0wvHfZ/ubZDaJGFfw9xNPQubS',
            ];
        }

        return null;
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('SELECT username, password_hash FROM users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if (!$user) {
        return null;
    }

    return [
        'username' => (string) $user['username'],
        'password_hash' => (string) $user['password_hash'],
    ];
}

function adminUsernameExists(string $username): bool
{
    if (!isApplicationInstalled()) {
        return $username === 'admin';
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('SELECT 1 FROM users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);

    return (bool) $stmt->fetchColumn();
}

/**
 * @return array<int, array{username: string, created_at: string|null}>
 */
function listAdminUsers(): array
{
    if (!isApplicationInstalled()) {
        return [
            [
                'username' => 'admin',
                'created_at' => (new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul')))->format(DateTimeInterface::ATOM),
            ],
        ];
    }

    $pdo = getPdo();
    $stmt = $pdo->query('SELECT username, created_at FROM users ORDER BY username ASC');

    if ($stmt === false) {
        return [];
    }

    $users = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!is_array($row)) {
            continue;
        }

        $createdAt = null;

        if (!empty($row['created_at'])) {
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $row['created_at'], new DateTimeZone('Europe/Istanbul'));
            if ($date instanceof DateTimeImmutable) {
                $createdAt = $date->format(DateTimeInterface::ATOM);
            }
        }

        $users[] = [
            'username' => (string) $row['username'],
            'created_at' => $createdAt,
        ];
    }

    return $users;
}

function getAdminUserCount(): int
{
    if (!isApplicationInstalled()) {
        return 1;
    }

    $pdo = getPdo();
    $count = $pdo->query('SELECT COUNT(*) FROM users');

    if ($count === false) {
        return 0;
    }

    return (int) $count->fetchColumn();
}

function createAdminUser(string $username, string $password): void
{
    if (!isApplicationInstalled()) {
        throw new RuntimeException('Uygulama kurulumu tamamlanmadan kullanıcı oluşturulamaz.');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    if ($hash === false) {
        throw new RuntimeException('Parola güvenli şekilde oluşturulamadı.');
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)');
    $stmt->execute([
        'username' => $username,
        'password_hash' => $hash,
    ]);
}

function updateAdminUserPassword(string $username, string $password): void
{
    if (!isApplicationInstalled()) {
        throw new RuntimeException('Uygulama kurulumu tamamlanmadan parola güncellenemez.');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    if ($hash === false) {
        throw new RuntimeException('Parola güvenli şekilde oluşturulamadı.');
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('UPDATE users SET password_hash = :password_hash WHERE username = :username');
    $stmt->execute([
        'username' => $username,
        'password_hash' => $hash,
    ]);

    if ($stmt->rowCount() === 0) {
        throw new RuntimeException('Yönetici kullanıcısı bulunamadı.');
    }
}

function deleteAdminUser(string $username): void
{
    if (!isApplicationInstalled()) {
        throw new RuntimeException('Uygulama kurulumu tamamlanmadan kullanıcı silinemez.');
    }

    $pdo = getPdo();
    $pdo->beginTransaction();

    try {
        $usernamesStmt = $pdo->query('SELECT username FROM users FOR UPDATE');
        $usernames = $usernamesStmt !== false ? $usernamesStmt->fetchAll(PDO::FETCH_COLUMN) : [];

        if (!in_array($username, $usernames, true)) {
            throw new RuntimeException('Yönetici kullanıcısı bulunamadı.');
        }

        if (count($usernames) <= 1) {
            throw new RuntimeException('En az bir yönetici hesabı bulunmalıdır.');
        }

        $stmt = $pdo->prepare('DELETE FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);

        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('Yönetici kullanıcısı silinemedi.');
        }

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}
