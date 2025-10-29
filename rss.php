<?php
require __DIR__ . '/includes/articles.php';
require __DIR__ . '/includes/helpers.php';

header('Content-Type: application/rss+xml; charset=UTF-8');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<rss version="2.0">
    <channel>
        <title><?php echo htmlspecialchars($siteSettings['site_name'] ?? 'Haber Merkezi', ENT_NOQUOTES, 'UTF-8'); ?></title>
        <link><?php echo htmlspecialchars(buildAbsoluteUrl('/index.php'), ENT_NOQUOTES, 'UTF-8'); ?></link>
        <description><?php echo htmlspecialchars($siteSettings['site_tagline'] ?? '', ENT_NOQUOTES, 'UTF-8'); ?></description>
        <language>tr-TR</language>
        <lastBuildDate><?php echo htmlspecialchars(formatRssDate((new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul')))->format(DATE_ATOM)), ENT_NOQUOTES, 'UTF-8'); ?></lastBuildDate>
        <?php foreach (array_slice($articles, 0, 20) as $article): ?>
            <item>
                <title><?php echo htmlspecialchars($article['title'], ENT_NOQUOTES, 'UTF-8'); ?></title>
                <link><?php echo htmlspecialchars(buildAbsoluteUrl('/article.php?slug=' . urlencode($article['slug'])), ENT_NOQUOTES, 'UTF-8'); ?></link>
                <guid isPermaLink="true"><?php echo htmlspecialchars(buildAbsoluteUrl('/article.php?slug=' . urlencode($article['slug'])), ENT_NOQUOTES, 'UTF-8'); ?></guid>
                <pubDate><?php echo htmlspecialchars(formatRssDate($article['published_at']), ENT_NOQUOTES, 'UTF-8'); ?></pubDate>
                <description><![CDATA[<?php echo $article['excerpt']; ?>]]></description>
                <category><?php echo htmlspecialchars($categories[$article['category']], ENT_NOQUOTES, 'UTF-8'); ?></category>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>
