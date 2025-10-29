<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requireAuth();

$subscribers = loadSubscribers();

$pageTitle = 'Aboneler';
require __DIR__ . '/header.php';
?>
<section class="admin-section">
    <div class="admin-section__header">
        <h1 class="admin-section__title">Bülten Aboneleri</h1>
        <p class="admin-section__subtitle">Haber bültenine kayıt olan kullanıcıların listesi.</p>
    </div>
    <?php if (empty($subscribers)): ?>
        <p>Henüz kayıtlı bir abone bulunmuyor.</p>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">E-posta</th>
                        <th scope="col">Kayıt Tarihi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $subscriber): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subscriber['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo isset($subscriber['subscribed_at']) ? htmlspecialchars(formatDateTime($subscriber['subscribed_at']), ENT_QUOTES, 'UTF-8') : '—'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/footer.php'; ?>
