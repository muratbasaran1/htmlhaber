</main>
<footer class="site-footer">
    <?php $highlightedTags = array_slice(getTagUsage($articles), 0, 6); ?>
    <div class="container footer-grid">
        <div>
            <h3>Hakkımızda</h3>
            <p><?php echo htmlspecialchars($siteSettings['footer_about'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            <?php if (!empty($siteSettings['contact_email'])): ?>
                <a class="link" href="mailto:<?php echo htmlspecialchars($siteSettings['contact_email'], ENT_QUOTES, 'UTF-8'); ?>">Bize Ulaşın</a>
            <?php endif; ?>
        </div>
        <div>
            <h3>Kategoriler</h3>
            <ul>
                <?php foreach ($categories as $slug => $name): ?>
                    <li><a href="/category.php?category=<?php echo urlencode($slug); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h3>Popüler Etiketler</h3>
            <ul class="tag-list">
                <?php foreach ($highlightedTags as $tag): ?>
                    <li><a href="/tag.php?tag=<?php echo urlencode($tag['label']); ?>"><?php echo htmlspecialchars($tag['label'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo (int) $tag['count']; ?>)</a></li>
                <?php endforeach; ?>
            </ul>
            <a class="link" href="/tags.php">Tüm etiketleri görüntüle</a>
        </div>
        <div>
            <h3>Bültene Katılın</h3>
            <form class="newsletter-form" action="/subscribe.php" method="post" novalidate>
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
            <div class="footer-links">
                <a href="#">Kullanım Şartları</a>
                <a href="#">Gizlilik Politikası</a>
                <?php if (!empty($siteSettings['contact_email'])): ?>
                    <a href="mailto:<?php echo htmlspecialchars($siteSettings['contact_email'], ENT_QUOTES, 'UTF-8'); ?>">İletişim</a>
                <?php endif; ?>
                <a href="/rss.php">RSS</a>
                <a href="/admin/">Yönetim</a>
            </div>
        </div>
</footer>
<script src="<?php echo htmlspecialchars(assetUrl('assets/js/main.js'), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
</body>
</html>
