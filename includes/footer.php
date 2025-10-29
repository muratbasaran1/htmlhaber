</main>
<footer class="site-footer">
    <?php $highlightedTags = array_slice(getTagUsage($articles), 0, 6); ?>
    <div class="container footer-grid">
        <div>
            <h3>Hakkımızda</h3>
            <p><?php echo htmlspecialchars($siteSettings['footer_about'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            <?php
            $contactEmail = $siteSettings['contact_email'] ?? '';
            $contactPhone = $siteSettings['footer_phone'] ?? '';
            $contactAddress = $siteSettings['footer_address'] ?? '';
            ?>
            <?php if ($contactEmail !== '' || $contactPhone !== '' || $contactAddress !== ''): ?>
                <div class="footer-contact">
                    <?php if ($contactEmail !== ''): ?>
                        <div class="footer-contact__item">
                            <span class="footer-contact__label">E-posta</span>
                            <a class="link" href="mailto:<?php echo htmlspecialchars($contactEmail, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($contactEmail, ENT_QUOTES, 'UTF-8'); ?></a>
                        </div>
                    <?php endif; ?>
                    <?php if ($contactPhone !== ''): ?>
                        <div class="footer-contact__item">
                            <span class="footer-contact__label">Telefon</span>
                            <a class="link" href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $contactPhone), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($contactPhone, ENT_QUOTES, 'UTF-8'); ?></a>
                        </div>
                    <?php endif; ?>
                    <?php if ($contactAddress !== ''): ?>
                        <div class="footer-contact__item">
                            <span class="footer-contact__label">Adres</span>
                            <span><?php echo nl2br(htmlspecialchars($contactAddress, ENT_QUOTES, 'UTF-8')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <h3>Kategoriler</h3>
            <ul>
                <?php foreach ($categories as $slug => $name): ?>
                    <li><a href="<?php echo htmlspecialchars(assetUrl('category.php') . '?category=' . urlencode($slug), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h3>Popüler Etiketler</h3>
            <ul class="tag-list">
                <?php foreach ($highlightedTags as $tag): ?>
                    <li><a href="<?php echo htmlspecialchars(assetUrl('tag.php') . '?tag=' . urlencode($tag['label']), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($tag['label'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo (int) $tag['count']; ?>)</a></li>
                <?php endforeach; ?>
            </ul>
            <a class="link" href="<?php echo htmlspecialchars(assetUrl('tags.php'), ENT_QUOTES, 'UTF-8'); ?>">Tüm etiketleri görüntüle</a>
        </div>
        <div>
            <h3>Bültene Katılın</h3>
            <form class="newsletter-form" action="<?php echo htmlspecialchars(assetUrl('subscribe.php'), ENT_QUOTES, 'UTF-8'); ?>" method="post" novalidate>
                <label for="newsletter-email" class="sr-only">E-posta</label>
                <input id="newsletter-email" name="email" type="email" placeholder="E-posta adresiniz" required>
                <button type="submit" class="btn">Abone Ol</button>
                <p class="form-feedback" role="status" aria-live="polite"></p>
            </form>
            <div class="social-links">
                <?php if (!empty($siteSettings['facebook_url'])): ?>
                    <a href="<?php echo htmlspecialchars($siteSettings['facebook_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Facebook</a>
                <?php endif; ?>
                <?php if (!empty($siteSettings['twitter_url'])): ?>
                    <a href="<?php echo htmlspecialchars($siteSettings['twitter_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">X</a>
                <?php endif; ?>
                <?php if (!empty($siteSettings['instagram_url'])): ?>
                    <a href="<?php echo htmlspecialchars($siteSettings['instagram_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Instagram</a>
                <?php endif; ?>
                <?php if (!empty($siteSettings['youtube_url'])): ?>
                    <a href="<?php echo htmlspecialchars($siteSettings['youtube_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">YouTube</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
        <div class="container footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteSettings['site_name'] ?? 'Haber Merkezi', ENT_QUOTES, 'UTF-8'); ?>. Tüm hakları saklıdır.</span>
            <?php
            $termsUrl = $siteSettings['terms_url'] ?? '';
            $privacyUrl = $siteSettings['privacy_url'] ?? '';
            $termsHref = $termsUrl !== '' ? ensureAbsoluteUrl($termsUrl) : '';
            $privacyHref = $privacyUrl !== '' ? ensureAbsoluteUrl($privacyUrl) : '';
            ?>
            <div class="footer-links">
                <?php if ($termsHref !== ''): ?>
                    <a href="<?php echo htmlspecialchars($termsHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Kullanım Şartları</a>
                <?php endif; ?>
                <?php if ($privacyHref !== ''): ?>
                    <a href="<?php echo htmlspecialchars($privacyHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Gizlilik Politikası</a>
                <?php endif; ?>
                <?php if (!empty($siteSettings['contact_email'])): ?>
                    <a href="mailto:<?php echo htmlspecialchars($siteSettings['contact_email'], ENT_QUOTES, 'UTF-8'); ?>">İletişim</a>
                <?php endif; ?>
                <a href="<?php echo htmlspecialchars(assetUrl('rss.php'), ENT_QUOTES, 'UTF-8'); ?>">RSS</a>
                <a href="<?php echo htmlspecialchars(assetUrl('sitemap.php'), ENT_QUOTES, 'UTF-8'); ?>">Site Haritası</a>
                <a href="<?php echo htmlspecialchars(assetUrl('admin/index.php'), ENT_QUOTES, 'UTF-8'); ?>">Yönetim</a>
            </div>
        </div>
</footer>
<script src="<?php echo htmlspecialchars(assetUrl('assets/js/main.js'), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
</body>
</html>
