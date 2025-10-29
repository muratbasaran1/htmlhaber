<?php

declare(strict_types=1);

function getIstanbulTimeZone(): \DateTimeZone
{
    static $timezone = null;

    if (!$timezone instanceof \DateTimeZone) {
        $timezone = new \DateTimeZone('Europe/Istanbul');
    }

    return $timezone;
}

function createIstanbulDateTime(string $isoString): \DateTimeImmutable
{
    $timezone = getIstanbulTimeZone();

    try {
        $date = new \DateTimeImmutable($isoString, $timezone);
    } catch (\Throwable $exception) {
        $date = new \DateTimeImmutable('now', $timezone);
    }

    return $date->setTimezone($timezone);
}

function formatDateTime(string $isoString): string
{
    static $formatter = null;
    static $intlAvailable = null;

    if ($intlAvailable === null) {
        $intlAvailable = class_exists('\\IntlDateFormatter');
    }

    $date = createIstanbulDateTime($isoString);

    if ($intlAvailable) {
        if (!$formatter instanceof \IntlDateFormatter) {
            try {
                $formatter = new \IntlDateFormatter(
                    'tr_TR',
                    \IntlDateFormatter::LONG,
                    \IntlDateFormatter::SHORT,
                    'Europe/Istanbul',
                    \IntlDateFormatter::GREGORIAN,
                    'd MMMM y HH:mm'
                );
            } catch (\Throwable $exception) {
                $intlAvailable = false;
                $formatter = null;
            }
        }

        if ($formatter instanceof \IntlDateFormatter) {
            $formatted = $formatter->format($date);
            if ($formatted !== false) {
                return $formatted;
            }
        }
    }

    return $date->format('d.m.Y H:i');
}

function formatShortTime(string $isoString): string
{
    static $formatter = null;
    static $intlAvailable = null;

    if ($intlAvailable === null) {
        $intlAvailable = class_exists('\\IntlDateFormatter');
    }

    $date = createIstanbulDateTime($isoString);

    if ($intlAvailable) {
        if (!$formatter instanceof \IntlDateFormatter) {
            try {
                $formatter = new \IntlDateFormatter(
                    'tr_TR',
                    \IntlDateFormatter::NONE,
                    \IntlDateFormatter::SHORT,
                    'Europe/Istanbul',
                    \IntlDateFormatter::GREGORIAN,
                    'HH:mm'
                );
            } catch (\Throwable $exception) {
                $intlAvailable = false;
                $formatter = null;
            }
        }

        if ($formatter instanceof \IntlDateFormatter) {
            $formatted = $formatter->format($date);
            if ($formatted !== false) {
                return $formatted;
            }
        }
    }

    return $date->format('H:i');
}

function formatRssDate(string $isoString): string
{
    $date = createIstanbulDateTime($isoString);

    return $date->format(DATE_RSS);
}

function getBaseUrl(): string
{
    $scheme = 'http';

    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $forwarded = strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']);
        if ($forwarded !== '') {
            $scheme = $forwarded === 'https' ? 'https' : 'http';
        }
    }

    if (isset($_SERVER['REQUEST_SCHEME'])) {
        $requestScheme = strtolower((string) $_SERVER['REQUEST_SCHEME']);
        if ($requestScheme === 'https') {
            $scheme = 'https';
        }
    }

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
        $scheme = 'https';
    } elseif (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443') {
        $scheme = 'https';
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

function getCurrentRelativeUrl(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $parsed = parse_url($uri);

    $path = isset($parsed['path']) && $parsed['path'] !== '' ? (string) $parsed['path'] : '/';
    $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
    $basePath = rtrim(getBasePath(), '/');

    if ($basePath !== '' && strpos($path, $basePath) === 0) {
        $relative = substr($path, strlen($basePath));
        if ($relative === false || $relative === '') {
            $path = '/';
        } else {
            $path = '/' . ltrim($relative, '/');
        }
    }

    if ($path === '') {
        $path = '/';
    } elseif ($path[0] !== '/') {
        $path = '/' . $path;
    }

    return $path . $query;
}

function ensureAbsoluteUrl(string $url): string
{
    if ($url === '') {
        return '';
    }

    if (preg_match('~^https?://~i', $url) === 1) {
        return $url;
    }

    return buildAbsoluteUrl($url);
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
