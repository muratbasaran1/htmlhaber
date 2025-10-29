<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requireAuth();

$siteSettings = loadSiteSettings();
$editorialPicks = loadEditorialPicks();
$liveTicker = loadLiveTicker();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        redirectWithMessage('/admin/settings.php', 'error', 'Oturum süresi doldu.');
    }

    $siteSettings = [
        'site_name' => trim($_POST['site_name'] ?? ''),
        'site_tagline' => trim($_POST['site_tagline'] ?? ''),
        'contact_email' => trim($_POST['contact_email'] ?? ''),
        'logo_url' => trim($_POST['logo_url'] ?? ''),
        'favicon_url' => trim($_POST['favicon_url'] ?? ''),
        'primary_color' => trim($_POST['primary_color'] ?? ''),
        'secondary_color' => trim($_POST['secondary_color'] ?? ''),
        'footer_about' => trim($_POST['footer_about'] ?? ''),
        'footer_phone' => trim($_POST['footer_phone'] ?? ''),
        'footer_address' => trim($_POST['footer_address'] ?? ''),
        'terms_url' => trim($_POST['terms_url'] ?? ''),
        'privacy_url' => trim($_POST['privacy_url'] ?? ''),
        'facebook_url' => trim($_POST['facebook_url'] ?? ''),
        'twitter_url' => trim($_POST['twitter_url'] ?? ''),
        'instagram_url' => trim($_POST['instagram_url'] ?? ''),
        'youtube_url' => trim($_POST['youtube_url'] ?? ''),
    ];

    if ($siteSettings['site_name'] === '') {
        $errors[] = 'Site adı zorunludur.';
    }

    if ($siteSettings['site_tagline'] === '') {
        $errors[] = 'Site sloganı zorunludur.';
    }

    if ($siteSettings['contact_email'] !== '' && !filter_var($siteSettings['contact_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Geçerli bir iletişim e-postası giriniz.';
    }

    foreach (['logo_url', 'favicon_url', 'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'terms_url', 'privacy_url'] as $urlKey) {
        if ($siteSettings[$urlKey] !== '' && !filter_var($siteSettings[$urlKey], FILTER_VALIDATE_URL)) {
            $errors[] = 'Lütfen geçerli bir URL giriniz: ' . ucwords(str_replace('_', ' ', $urlKey));
        }
    }

    if ($siteSettings['footer_phone'] !== '' && !preg_match('/^[0-9+()\\s-]{6,}$/', $siteSettings['footer_phone'])) {
        $errors[] = 'Geçerli bir iletişim telefonu giriniz.';
    }

    foreach (['primary_color', 'secondary_color'] as $colorKey) {
        if ($siteSettings[$colorKey] === '') {
            $errors[] = 'Tema renkleri boş bırakılamaz.';
        } elseif (!preg_match('/^#[0-9A-Fa-f]{6}$/', $siteSettings[$colorKey])) {
            $errors[] = 'Tema renkleri hex formatında olmalıdır.';
        }
    }

    $editorialLines = preg_split('/\r?\n/', (string) ($_POST['editorial_picks'] ?? '')) ?: [];
    $tickerLines = preg_split('/\r?\n/', (string) ($_POST['live_ticker'] ?? '')) ?: [];

    $editorialEntries = [];
    foreach ($editorialLines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }

        [$title, $link] = array_map('trim', array_pad(explode('|', $line, 2), 2, ''));
        if ($title === '') {
            $errors[] = 'Manşet seçkisindeki her satır "Başlık|URL" formatında olmalıdır.';
            break;
        }

        if ($link === '') {
            $link = '#';
        }

        $editorialEntries[] = [
            'title' => $title,
            'link' => $link,
        ];
    }

    if (empty($errors)) {
        $tickerEntries = array_values(array_filter(array_map('trim', $tickerLines), static fn ($item) => $item !== ''));

        saveSiteSettings($siteSettings);
        saveEditorialPicks($editorialEntries);
        saveLiveTicker($tickerEntries);
        redirectWithMessage('/admin/settings.php', 'success', 'Ayarlar güncellendi.');
    }
}

$pageTitle = 'Ayarlar';
require __DIR__ . '/header.php';
?>
<section class="admin-section">
    <h1 class="admin-section__title">Site Ayarları</h1>
    <form method="post" class="admin-form admin-form--stacked">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <?php if (!empty($errors)): ?>
            <div class="admin-flash">
                <?php foreach ($errors as $error): ?>
                    <div class="admin-flash__item admin-flash__item--error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="form-grid">
            <div class="form-group">
                <label for="logo_url">Logo URL</label>
                <input type="url" id="logo_url" name="logo_url" value="<?php echo htmlspecialchars($siteSettings['logo_url'], ENT_QUOTES, 'UTF-8'); ?>">
                <p class="form-help">Şeffaf arka planlı bir SVG veya PNG önerilir.</p>
            </div>
            <div class="form-group">
                <label for="favicon_url">Favicon URL</label>
                <input type="url" id="favicon_url" name="favicon_url" value="<?php echo htmlspecialchars($siteSettings['favicon_url'], ENT_QUOTES, 'UTF-8'); ?>">
                <p class="form-help">Tarayıcı sekmesinde gösterilecek kare ikon (32x32 veya 64x64 piksel).</p>
            </div>
            <div class="form-group">
                <label for="site_name">Site Adı</label>
                <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($siteSettings['site_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="site_tagline">Site Sloganı</label>
                <input type="text" id="site_tagline" name="site_tagline" value="<?php echo htmlspecialchars($siteSettings['site_tagline'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_email">İletişim E-postası</label>
                <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($siteSettings['contact_email'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label for="primary_color">Birincil Renk</label>
                <input type="color" id="primary_color" name="primary_color" value="<?php echo htmlspecialchars($siteSettings['primary_color'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="secondary_color">İkincil Renk</label>
                <input type="color" id="secondary_color" name="secondary_color" value="<?php echo htmlspecialchars($siteSettings['secondary_color'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="footer_about">Altbilgi Metni</label>
            <textarea id="footer_about" name="footer_about" rows="3" required><?php echo htmlspecialchars($siteSettings['footer_about'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="footer_phone">İletişim Telefonu</label>
                <input type="text" id="footer_phone" name="footer_phone" value="<?php echo htmlspecialchars($siteSettings['footer_phone'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group form-group--full">
                <label for="footer_address">Adres</label>
                <textarea id="footer_address" name="footer_address" rows="3"><?php echo htmlspecialchars($siteSettings['footer_address'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="facebook_url">Facebook URL</label>
                <input type="url" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($siteSettings['facebook_url'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label for="twitter_url">X (Twitter) URL</label>
                <input type="url" id="twitter_url" name="twitter_url" value="<?php echo htmlspecialchars($siteSettings['twitter_url'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label for="instagram_url">Instagram URL</label>
                <input type="url" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($siteSettings['instagram_url'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label for="youtube_url">YouTube URL</label>
                <input type="url" id="youtube_url" name="youtube_url" value="<?php echo htmlspecialchars($siteSettings['youtube_url'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label for="terms_url">Kullanım Şartları URL</label>
                <input type="url" id="terms_url" name="terms_url" value="<?php echo htmlspecialchars($siteSettings['terms_url'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label for="privacy_url">Gizlilik Politikası URL</label>
                <input type="url" id="privacy_url" name="privacy_url" value="<?php echo htmlspecialchars($siteSettings['privacy_url'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="editorial_picks">Manşet Seçkisi (Her satır "Başlık|URL" formatında)</label>
            <textarea id="editorial_picks" name="editorial_picks" rows="6"><?php
                echo htmlspecialchars(implode("\n", array_map(static fn ($item) => $item['title'] . '|' . $item['link'], $editorialPicks)), ENT_QUOTES, 'UTF-8');
            ?></textarea>
        </div>
        <div class="form-group">
            <label for="live_ticker">Canlı Akış Mesajları (Her satır yeni mesaj)</label>
            <textarea id="live_ticker" name="live_ticker" rows="6"><?php echo htmlspecialchars(implode("\n", $liveTicker), ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn--primary">Ayarları Kaydet</button>
        </div>
    </form>
</section>
<?php require __DIR__ . '/footer.php'; ?>
