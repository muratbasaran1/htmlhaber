<?php

declare(strict_types=1);

require_once __DIR__ . '/storage.php';

$categories = loadCategories();
$articles = loadArticles();
$editorialPicks = loadEditorialPicks();
$liveTicker = loadLiveTicker();
