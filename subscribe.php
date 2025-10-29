<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/storage.php';
require_once __DIR__ . '/includes/helpers.php';

date_default_timezone_set('Europe/Istanbul');
$siteSettings = loadSiteSettings();
$siteName = $siteSettings['site_name'] ?? 'Haber Merkezi';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'status' => 'error',
        'message' => 'Yalnızca POST isteği kabul edilir.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$email = (string)($_POST['email'] ?? '');

try {
    $record = addSubscriber($email);
    $response = [
        'status' => 'success',
        'message' => 'Bülten aboneliğiniz başarıyla oluşturuldu.',
        'data' => $record,
    ];
    $statusCode = 201;
} catch (InvalidArgumentException $exception) {
    $response = [
        'status' => 'error',
        'message' => $exception->getMessage(),
    ];
    $statusCode = 422;
} catch (RuntimeException $exception) {
    $response = [
        'status' => 'error',
        'message' => $exception->getMessage(),
    ];
    $statusCode = 409;
} catch (Throwable $exception) {
    $response = [
        'status' => 'error',
        'message' => 'Beklenmeyen bir hata oluştu.',
    ];
    $statusCode = 500;
}

$acceptHeader = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');
$isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
$wantsJson = $isAjax || strpos($acceptHeader, 'application/json') !== false;

if ($wantsJson) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code($statusCode);
?><!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bülten Aboneliği | <?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(assetUrl('assets/css/style.css'), ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body class="standalone">
    <main class="standalone-main">
        <article class="standalone-card">
            <h1><?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?> Bülteni</h1>
            <p><?php echo htmlspecialchars($response['message'], ENT_QUOTES, 'UTF-8'); ?></p>
            <a class="btn" href="<?php echo htmlspecialchars(assetUrl('index.php'), ENT_QUOTES, 'UTF-8'); ?>">Anasayfaya Dön</a>
        </article>
    </main>
</body>
</html>
