<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (isAuthenticated()) {
    header('Location: ' . assetUrl('admin/dashboard.php'));
} else {
    header('Location: ' . assetUrl('admin/login.php'));
}
exit;
