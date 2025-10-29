<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (isAuthenticated()) {
    header('Location: /admin/dashboard.php');
    exit;
}

$errors = [];
$username = '';
$siteSettings = loadSiteSettings();
$siteName = $siteSettings['site_name'] ?? 'Haber Merkezi';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($token)) {
        $errors[] = 'Oturum süresi doldu. Lütfen tekrar deneyin.';
    } elseif ($username === '' || $password === '') {
        $errors[] = 'Kullanıcı adı ve şifre zorunludur.';
    } elseif (!attemptLogin($username, $password)) {
        $errors[] = 'Geçersiz kullanıcı adı veya şifre.';
    }

    if (empty($errors) && isAuthenticated()) {
        $redirect = $_GET['redirect'] ?? '/admin/dashboard.php';
        header('Location: ' . $redirect);
        exit;
    }
}

$pageTitle = 'Yönetici Girişi';
$token = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> | <?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?> Yönetim</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-login-body">
    <div class="admin-login">
        <h1><?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?> Yönetim Girişi</h1>
        <?php if (!empty($errors)): ?>
            <div class="admin-flash">
                <?php foreach ($errors as $error): ?>
                    <div class="admin-flash__item admin-flash__item--error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group">
                <label for="username">Kullanıcı Adı</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn--primary btn--full">Giriş Yap</button>
        </form>
        <a class="admin-login__back" href="/index.php">Siteye dön</a>
    </div>
</body>
</html>
