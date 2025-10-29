<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requireAuth();

$currentUsername = $_SESSION['admin_username'] ?? 'admin';
$users = listAdminUsers();
$userCount = getAdminUserCount();

$addErrors = [];
$passwordErrors = [];
$deleteErrors = [];

$newUserForm = [
    'username' => '',
];
$passwordForm = [
    'username' => $currentUsername,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        redirectWithMessage('/admin/users.php', 'error', 'Oturum süresi doldu.');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['password_confirm'] ?? '');

        $newUserForm['username'] = $username;

        if ($username === '') {
            $addErrors[] = 'Kullanıcı adı zorunludur.';
        } elseif (mb_strlen($username) < 3 || mb_strlen($username) > 32) {
            $addErrors[] = 'Kullanıcı adı 3 ile 32 karakter arasında olmalıdır.';
        } elseif (!preg_match('/^[a-z0-9_.-]+$/i', $username)) {
            $addErrors[] = 'Kullanıcı adı sadece harf, rakam, nokta, alt çizgi ve tire içerebilir.';
        } elseif (adminUsernameExists($username)) {
            $addErrors[] = 'Bu kullanıcı adı zaten kayıtlı.';
        }

        if ($password === '') {
            $addErrors[] = 'Parola zorunludur.';
        } elseif (mb_strlen($password) < 8) {
            $addErrors[] = 'Parola en az 8 karakter olmalıdır.';
        }

        if ($password !== $confirm) {
            $addErrors[] = 'Parola ve doğrulama eşleşmiyor.';
        }

        if (empty($addErrors)) {
            try {
                createAdminUser($username, $password);
                redirectWithMessage('/admin/users.php', 'success', 'Yeni yönetici hesabı oluşturuldu.');
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $addErrors[] = 'Bu kullanıcı adı zaten kayıtlı.';
                } else {
                    $addErrors[] = 'Kullanıcı kaydedilirken bir hata oluştu.';
                }
            } catch (Throwable $exception) {
                $addErrors[] = $exception->getMessage();
            }
        }
    } elseif ($action === 'password') {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['password_confirm'] ?? '');

        $passwordForm['username'] = $username;

        if ($username === '') {
            $passwordErrors[] = 'Parola güncellemek için kullanıcı seçmelisiniz.';
        } elseif (!adminUsernameExists($username)) {
            $passwordErrors[] = 'Seçilen kullanıcı bulunamadı.';
        }

        if ($password === '') {
            $passwordErrors[] = 'Yeni parola zorunludur.';
        } elseif (mb_strlen($password) < 8) {
            $passwordErrors[] = 'Parola en az 8 karakter olmalıdır.';
        }

        if ($password !== $confirm) {
            $passwordErrors[] = 'Parola ve doğrulama eşleşmiyor.';
        }

        if (empty($passwordErrors)) {
            try {
                updateAdminUserPassword($username, $password);
                redirectWithMessage('/admin/users.php', 'success', 'Parola başarıyla güncellendi.');
            } catch (Throwable $exception) {
                $passwordErrors[] = $exception->getMessage();
            }
        }
    } elseif ($action === 'delete') {
        $username = trim((string) ($_POST['username'] ?? ''));

        if ($username === '') {
            $deleteErrors[] = 'Silinecek kullanıcı seçilemedi.';
        } elseif ($username === $currentUsername) {
            $deleteErrors[] = 'Aktif oturumunuz açıkken kendi hesabınızı silemezsiniz.';
        } elseif (!adminUsernameExists($username)) {
            $deleteErrors[] = 'Kullanıcı bulunamadı.';
        } else {
            try {
                deleteAdminUser($username);
                redirectWithMessage('/admin/users.php', 'success', 'Yönetici hesabı silindi.');
            } catch (Throwable $exception) {
                $deleteErrors[] = $exception->getMessage();
            }
        }
    } else {
        redirectWithMessage('/admin/users.php', 'error', 'Geçersiz işlem isteği.');
    }

    $users = listAdminUsers();
    $userCount = getAdminUserCount();
}

$pageTitle = 'Yöneticiler';
require __DIR__ . '/header.php';
?>
<section class="admin-section">
    <h1 class="admin-section__title">Yönetici Hesapları</h1>
    <p>Güvenliği artırmak için birden fazla yönetici hesabı oluşturabilir, parolaları düzenli olarak güncelleyebilirsiniz.</p>
    <div class="admin-grid">
        <div class="admin-card">
            <h2>Yeni Yönetici Oluştur</h2>
            <p class="admin-card__description">Güçlü bir parola belirleyerek yeni bir yönetici hesabı ekleyin.</p>
            <?php if (!empty($addErrors)): ?>
                <div class="admin-flash">
                    <?php foreach ($addErrors as $error): ?>
                        <div class="admin-flash__item admin-flash__item--error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="post" class="admin-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label for="new-username">Kullanıcı Adı</label>
                    <input type="text" id="new-username" name="username" value="<?php echo htmlspecialchars($newUserForm['username'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="username" required>
                </div>
                <div class="form-group">
                    <label for="new-password">Parola</label>
                    <input type="password" id="new-password" name="password" autocomplete="new-password" required>
                </div>
                <div class="form-group">
                    <label for="new-password-confirm">Parola (Tekrar)</label>
                    <input type="password" id="new-password-confirm" name="password_confirm" autocomplete="new-password" required>
                </div>
                <button type="submit" class="btn btn--primary">Yönetici Ekle</button>
            </form>
        </div>
        <div class="admin-card">
            <h2>Parola Güncelle</h2>
            <p class="admin-card__description">Her yönetici için güçlü ve benzersiz parolalar kullanın.</p>
            <?php if (!empty($passwordErrors)): ?>
                <div class="admin-flash">
                    <?php foreach ($passwordErrors as $error): ?>
                        <div class="admin-flash__item admin-flash__item--error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="post" class="admin-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="action" value="password">
                <div class="form-group">
                    <label for="password-username">Kullanıcı Seçin</label>
                    <select id="password-username" name="username" required>
                        <option value="" disabled <?php echo $passwordForm['username'] === '' ? 'selected' : ''; ?>>Bir kullanıcı seçin</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $passwordForm['username'] === $user['username'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password-new">Yeni Parola</label>
                    <input type="password" id="password-new" name="password" autocomplete="new-password" required>
                </div>
                <div class="form-group">
                    <label for="password-confirm">Yeni Parola (Tekrar)</label>
                    <input type="password" id="password-confirm" name="password_confirm" autocomplete="new-password" required>
                </div>
                <button type="submit" class="btn btn--primary">Parolayı Güncelle</button>
            </form>
        </div>
    </div>
</section>
<section class="admin-section">
    <h2 class="admin-section__subtitle">Mevcut Yöneticiler</h2>
    <?php if (!empty($deleteErrors)): ?>
        <div class="admin-flash">
            <?php foreach ($deleteErrors as $error): ?>
                <div class="admin-flash__item admin-flash__item--error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="admin-card">
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Kullanıcı Adı</th>
                        <th scope="col">Oluşturulma Tarihi</th>
                        <th scope="col">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php if (!empty($user['created_at'])): ?>
                                    <?php echo htmlspecialchars(formatDateTime($user['created_at']), ENT_QUOTES, 'UTF-8'); ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="admin-table__actions">
                                    <?php $canDelete = $userCount > 1 && $user['username'] !== $currentUsername; ?>
                                    <form method="post">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <button type="submit" class="btn btn--danger" <?php echo $canDelete ? '' : 'disabled'; ?>>Sil</button>
                                    </form>
                                    <?php if (!$canDelete): ?>
                                        <span class="admin-table__hint">Kendi hesabınızı veya son kullanıcıyı silemezsiniz.</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="admin-card__description">En az bir yönetici hesabı sistemde kalmalıdır. Güvenlik için düzenli olarak parolaları güncellemeyi unutmayın.</p>
    </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
