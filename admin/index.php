<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (isAuthenticated()) {
    header('Location: /admin/dashboard.php');
} else {
    header('Location: /admin/login.php');
}
exit;
