<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/storage.php';
require_once __DIR__ . '/../includes/helpers.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$articles = loadArticles(16);
$categories = loadCategories();
$liveTicker = loadLiveTicker();

$latestArticles = $articles;
$agendaArticles = fetchArticlesByCategory('gundem', 8);

$latestPayload = array_map(static function (array $article) use ($categories): array {
    return [
        'slug' => $article['slug'],
        'title' => $article['title'],
        'excerpt' => $article['excerpt'],
        'category' => $article['category'],
        'category_name' => $categories[$article['category']] ?? $article['category'],
        'published_at' => $article['published_at'],
    ];
}, $latestArticles);

$agendaPayload = array_map(static function (array $article): array {
    return [
        'slug' => $article['slug'],
        'title' => $article['title'],
        'published_at' => $article['published_at'],
    ];
}, $agendaArticles);

$response = [
    'generated_at' => (new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul')))->format(DATE_ATOM),
    'latest' => $latestPayload,
    'live' => array_slice($liveTicker, 0, 12),
    'agenda' => $agendaPayload,
];

$encoded = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

if ($encoded === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Veri oluşturulamadı.'], JSON_UNESCAPED_UNICODE);
    return;
}

echo $encoded;
