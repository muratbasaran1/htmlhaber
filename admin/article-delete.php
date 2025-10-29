<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('/admin/articles.php', 'error', 'Geçersiz işlem.');
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    redirectWithMessage('/admin/articles.php', 'error', 'Oturum süresi doldu. Lütfen tekrar deneyin.');
}

$slug = trim($_POST['slug'] ?? '');

if ($slug === '') {
    redirectWithMessage('/admin/articles.php', 'error', 'Silinecek haber bulunamadı.');
}

$article = fetchArticleBySlug($slug);

if ($article === null) {
    redirectWithMessage('/admin/articles.php', 'error', 'Haber kaydı bulunamadı.');
}

deleteArticleBySlug($slug);
redirectWithMessage('/admin/articles.php', 'success', 'Haber başarıyla silindi.');
