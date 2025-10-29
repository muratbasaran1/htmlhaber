<?php

declare(strict_types=1);
?>
    </main>
    <footer class="admin-footer">
        <div class="container">
            <span>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(($siteSettings['site_name'] ?? 'Haber Merkezi') . ' Yönetim Paneli', ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="/index.php" class="admin-footer__link">Siteye dön</a>
        </div>
    </footer>
</body>
</html>
