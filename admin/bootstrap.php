<?php

declare(strict_types=1);

session_start();

date_default_timezone_set('Europe/Istanbul');

define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$12$yJ6oX0BjB2j5Xzv.TAtJr.76oxoa0wvHfZ/ubZDaJGFfw9xNPQubS');

require_once __DIR__ . '/../includes/storage.php';
require_once __DIR__ . '/../includes/helpers.php';

function isAuthenticated(): bool
{
    return isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true;
}

function requireAuth(): void
{
    if (!isAuthenticated()) {
        $redirect = $_SERVER['REQUEST_URI'] ?? '/admin/dashboard.php';
        header('Location: /admin/login.php?redirect=' . urlencode($redirect));
        exit;
    }
}

function attemptLogin(string $username, string $password): bool
{
    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_username'] = $username;
        regenerateCsrfToken();

        return true;
    }

    return false;
}

function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }

    session_destroy();
    session_start();
    regenerateCsrfToken();
}

function regenerateCsrfToken(): void
{
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function getCsrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        regenerateCsrfToken();
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool
{
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    return is_string($token) && $sessionToken !== '' && hash_equals($sessionToken, $token);
}

/**
 * @param array<string, string> $items
 */
function sanitizeStringArray(array $items): array
{
    $sanitized = [];

    foreach ($items as $key => $value) {
        $sanitized[$key] = trim((string) $value);
    }

    return $sanitized;
}

/**
 * @return array<int, array{type:string,message:string}>
 */
function getFlashMessages(): array
{
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);

    if (!is_array($messages)) {
        return [];
    }

    return array_values(array_filter($messages, static function ($message): bool {
        return is_array($message) && isset($message['type'], $message['message']);
    }));
}

function addFlashMessage(string $type, string $message): void
{
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }

    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message,
    ];
}

function redirectWithMessage(string $url, string $type, string $message): void
{
    addFlashMessage($type, $message);
    header('Location: ' . $url);
    exit;
}

function currentAdminPath(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $parsed = parse_url($uri);

    return $parsed['path'] ?? '/admin/dashboard.php';
}
