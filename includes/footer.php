</main>
<footer class="site-footer">
    <?php $highlightedTags = array_slice(getTagUsage($articles), 0, 6); ?>
    <div class="container footer-grid">
        <div>
            <h3>Hakkımızda</h3>
            <p>Haber Merkezi, Türkiye ve dünyadan en güvenilir haberleri tarafsız bakış açısıyla sunmayı amaçlayan bağımsız bir haber platformudur.</p>
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
        </div>
    </div>
        <div class="container footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> Haber Merkezi. Tüm hakları saklıdır.</span>
            <div class="footer-links">
                <a href="#">Kullanım Şartları</a>
                <a href="#">Gizlilik Politikası</a>
                <a href="#">İletişim</a>
                <a href="/rss.php">RSS</a>
                <a href="/admin/">Yönetim</a>
            </div>
        </div>
</footer>
<script src="<?php echo htmlspecialchars(assetUrl('assets/js/main.js'), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
</body>
</html>
