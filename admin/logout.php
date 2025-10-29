<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

logout();
addFlashMessage('success', 'Başarıyla çıkış yaptınız.');
header('Location: ' . assetUrl('admin/login.php'));
exit;
