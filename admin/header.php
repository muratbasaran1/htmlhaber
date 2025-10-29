<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (!isset($pageTitle)) {
    $pageTitle = 'Yönetim Paneli';
}

$flashMessages = getFlashMessages();
$currentPath = currentAdminPath();
$navItems = [
    '/admin/dashboard.php' => 'Kontrol Paneli',
    '/admin/articles.php' => 'Haberler',
    '/admin/categories.php' => 'Kategoriler',
    '/admin/settings.php' => 'Ayarlar',
    '/admin/subscribers.php' => 'Aboneler',
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> | Haber Merkezi Yönetim</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(assetUrl('assets/css/admin.css'), ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body>
<header class="admin-header">
    <div class="container admin-header__inner">
        <div class="admin-brand">
            <span class="admin-brand__title">Haber Merkezi</span>
            <span class="admin-brand__subtitle">Yönetim Paneli</span>
        </div>
        <nav class="admin-nav" aria-label="Yönetim menüsü">
            <ul>
                <?php foreach ($navItems as $path => $label): ?>
                    <li class="<?php echo $currentPath === $path ? 'is-active' : ''; ?>">
                        <a href="<?php echo htmlspecialchars($path, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="admin-user">
            <span class="admin-user__name"><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'admin', ENT_QUOTES, 'UTF-8'); ?></span>
            <a class="btn btn--ghost" href="/admin/logout.php">Çıkış</a>
        </div>
    </div>
</header>
<main class="admin-main container">
    <?php if (!empty($flashMessages)): ?>
        <div class="admin-flash">
            <?php foreach ($flashMessages as $message): ?>
                <div class="admin-flash__item admin-flash__item--<?php echo htmlspecialchars($message['type'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
