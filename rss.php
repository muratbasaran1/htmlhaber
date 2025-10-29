<?php
require __DIR__ . '/includes/articles.php';
require __DIR__ . '/includes/helpers.php';

header('Content-Type: application/rss+xml; charset=UTF-8');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<rss version="2.0">
    <channel>
        <title>Haber Merkezi</title>
        <link><?php echo htmlspecialchars(buildAbsoluteUrl('/index.php'), ENT_NOQUOTES, 'UTF-8'); ?></link>
        <description>Türkiye ve dünyadan son dakika haberleri, canlı gelişmeler ve derin analizler.</description>
        <language>tr-TR</language>
        <lastBuildDate><?php echo htmlspecialchars(formatRssDate((new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul')))->format(DATE_ATOM)), ENT_NOQUOTES, 'UTF-8'); ?></lastBuildDate>
        <?php foreach ($articles as $article): ?>
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
