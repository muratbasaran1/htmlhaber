<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/storage.php';
require_once __DIR__ . '/includes/helpers.php';

$articles = loadArticles();
$categories = loadCategories();
$tags = getAllTags($articles);

$latestArticleDate = null;
$categoryLastMod = [];
$tagLastMod = [];
$articlePublishedDates = [];

foreach ($articles as $index => $article) {
    $publishedAt = $article['published_at'] ?? '';
    $publishedDate = null;

    if (is_string($publishedAt) && $publishedAt !== '') {
        try {
            $publishedDate = new DateTimeImmutable($publishedAt);
        } catch (Exception $exception) {
            $publishedDate = DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                $publishedAt,
                new DateTimeZone('Europe/Istanbul')
            ) ?: null;
        }
    }

    $articlePublishedDates[$index] = $publishedDate;

    if ($publishedDate instanceof DateTimeImmutable) {
        if ($latestArticleDate === null || $publishedDate > $latestArticleDate) {
            $latestArticleDate = $publishedDate;
        }

        $categoryKey = $article['category'] ?? '';
        if ($categoryKey !== '') {
            if (!isset($categoryLastMod[$categoryKey]) || $publishedDate > $categoryLastMod[$categoryKey]) {
                $categoryLastMod[$categoryKey] = $publishedDate;
            }
        }

        foreach ($article['tags'] as $tag) {
            $tagKey = mb_strtolower($tag);
            if (!isset($tagLastMod[$tagKey]) || $publishedDate > $tagLastMod[$tagKey]) {
                $tagLastMod[$tagKey] = $publishedDate;
            }
        }
    }
}

if ($latestArticleDate === null) {
    $latestArticleDate = new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul'));
}

$formatLastMod = static function (?DateTimeImmutable $date): ?string {
    if (!$date instanceof DateTimeImmutable) {
        return null;
    }

    return $date
        ->setTimezone(new DateTimeZone('UTC'))
        ->format('Y-m-d\TH:i:s\Z');
};

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

$urlset = $dom->createElement('urlset');
$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$dom->appendChild($urlset);

$appendUrl = static function (
    string $path,
    ?DateTimeImmutable $lastModified,
    string $changefreq,
    string $priority
) use ($dom, $urlset, $formatLastMod): void {
    $url = $dom->createElement('url');
    $loc = $dom->createElement('loc', buildAbsoluteUrl($path));
    $url->appendChild($loc);

    $lastModifiedFormatted = $formatLastMod($lastModified);
    if ($lastModifiedFormatted !== null) {
        $url->appendChild($dom->createElement('lastmod', $lastModifiedFormatted));
    }

    if ($changefreq !== '') {
        $url->appendChild($dom->createElement('changefreq', $changefreq));
    }

    if ($priority !== '') {
        $url->appendChild($dom->createElement('priority', $priority));
    }

    $urlset->appendChild($url);
};

$appendUrl('/', $latestArticleDate, 'hourly', '1.0');
$appendUrl('rss.php', $latestArticleDate, 'hourly', '0.9');
$appendUrl('tags.php', $latestArticleDate, 'daily', '0.6');
$appendUrl('subscribe.php', new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul')), 'monthly', '0.4');

foreach ($categories as $slug => $label) {
    $appendUrl(
        'category.php?category=' . urlencode((string) $slug),
        $categoryLastMod[$slug] ?? $latestArticleDate,
        'daily',
        '0.8'
    );
}

foreach ($tags as $tag) {
    $tagKey = mb_strtolower($tag);
    $appendUrl(
        'tag.php?tag=' . urlencode($tag),
        $tagLastMod[$tagKey] ?? $latestArticleDate,
        'weekly',
        '0.6'
    );
}

foreach ($articles as $index => $article) {
    $publishedDate = $articlePublishedDates[$index] ?? null;

    $appendUrl(
        'article.php?slug=' . urlencode((string) $article['slug']),
        $publishedDate ?? $latestArticleDate,
        'daily',
        '0.9'
    );
}

header('Content-Type: application/xml; charset=UTF-8');
echo $dom->saveXML();
exit;
