<?php

declare(strict_types=1);

function formatDateTime(string $isoString): string
{
    static $formatter = null;

    if ($formatter === null) {
        $formatter = new \IntlDateFormatter(
            'tr_TR',
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::SHORT,
            'Europe/Istanbul',
            \IntlDateFormatter::GREGORIAN,
            'd MMMM y HH:mm'
        );
    }

    $date = new \DateTime($isoString);
    return $formatter->format($date);
}

function formatRssDate(string $isoString): string
{
    $date = new \DateTime($isoString);
    $date->setTimezone(new \DateTimeZone('Europe/Istanbul'));

    return $date->format(DATE_RSS);
}

function getBaseUrl(): string
{
    $scheme = 'https';

    if (isset($_SERVER['REQUEST_SCHEME'])) {
        $scheme = $_SERVER['REQUEST_SCHEME'];
    } elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $scheme = 'https';
    } elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === '80') {
        $scheme = 'http';
    }

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    return $scheme . '://' . $host;
}

function getBasePath(): string
{
    static $basePath = null;

    if ($basePath !== null) {
        return $basePath;
    }

    $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $rootDir = realpath(__DIR__ . '/..');

    if ($docRoot !== '' && $rootDir !== false) {
        $normalizedDocRoot = rtrim(str_replace('\\', '/', $docRoot), '/');
        $normalizedRootDir = rtrim(str_replace('\\', '/', $rootDir), '/');

        if ($normalizedDocRoot !== '' && strpos($normalizedRootDir, $normalizedDocRoot) === 0) {
            $relative = substr($normalizedRootDir, strlen($normalizedDocRoot));
            if ($relative === false || $relative === '') {
                return $basePath = '';
            }

            return $basePath = '/' . ltrim($relative, '/');
        }
    }

    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    if ($scriptName !== '') {
        $scriptDir = str_replace('\\', '/', dirname($scriptName));
        if ($scriptDir === '\\' || $scriptDir === '/' || $scriptDir === '.') {
            return $basePath = '';
        }

        if (substr($scriptDir, -6) === '/admin') {
            $scriptDir = substr($scriptDir, 0, -6);
        }

        return $basePath = $scriptDir === '/' ? '' : $scriptDir;
    }

    return $basePath = '';
}

function assetUrl(string $path): string
{
    $normalizedPath = '/' . ltrim($path, '/');
    $basePath = rtrim(getBasePath(), '/');

    if ($basePath === '') {
        return $normalizedPath;
    }

    return $basePath . $normalizedPath;
}

function buildAbsoluteUrl(string $path): string
{
    $base = rtrim(getBaseUrl(), '/');
    $basePath = rtrim(getBasePath(), '/');
    $normalizedPath = '/' . ltrim($path, '/');

    $fullPath = $basePath === '' ? $normalizedPath : $basePath . $normalizedPath;

    return $base . $fullPath;
}

function findArticleBySlug(array $articles, string $slug): ?array
{
    foreach ($articles as $article) {
        if ($article['slug'] === $slug) {
            return $article;
        }
    }

    return null;
}

function getArticlesByCategory(array $articles, string $category): array
{
    return array_values(array_filter($articles, static function (array $article) use ($category): bool {
        return $article['category'] === $category;
    }));
}

function getRelatedArticles(array $articles, array $currentArticle, int $limit = 3): array
{
    $related = array_filter($articles, static function (array $article) use ($currentArticle): bool {
        if ($article['slug'] === $currentArticle['slug']) {
            return false;
        }

        return count(array_intersect($article['tags'], $currentArticle['tags'])) > 0
            || $article['category'] === $currentArticle['category'];
    });

    return array_slice(array_values($related), 0, $limit);
}

function searchArticles(array $articles, string $query): array
{
    $query = mb_strtolower(trim($query));

    if ($query === '') {
        return [];
    }

    return array_values(array_filter($articles, static function (array $article) use ($query): bool {
        $haystack = mb_strtolower($article['title'] . ' ' . $article['excerpt'] . ' ' . implode(' ', $article['tags']));
        return mb_strpos($haystack, $query) !== false;
    }));
}

function getArticlesByTag(array $articles, string $tag): array
{
    $needle = mb_strtolower(trim($tag));

    if ($needle === '') {
        return [];
    }

    return array_values(array_filter($articles, static function (array $article) use ($needle): bool {
        foreach ($article['tags'] as $articleTag) {
            if (mb_strtolower($articleTag) === $needle) {
                return true;
            }
        }

        return false;
    }));
}

function getAllTags(array $articles): array
{
    $tagSet = [];

    foreach ($articles as $article) {
        foreach ($article['tags'] as $tag) {
            $key = mb_strtolower($tag);
            $tagSet[$key] = $tag;
        }
    }

    ksort($tagSet, SORT_STRING | SORT_FLAG_CASE);

    return array_values($tagSet);
}

function getTagUsage(array $articles): array
{
    $usage = [];

    foreach ($articles as $article) {
        foreach ($article['tags'] as $tag) {
            $key = mb_strtolower($tag);

            if (!isset($usage[$key])) {
                $usage[$key] = [
                    'label' => $tag,
                    'count' => 0,
                ];
            }

            $usage[$key]['count']++;
        }
    }

    uasort($usage, static function (array $a, array $b): int {
        return $b['count'] <=> $a['count'] ?: strcasecmp($a['label'], $b['label']);
    });

    return array_values($usage);
}
