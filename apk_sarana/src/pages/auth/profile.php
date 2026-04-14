<?php
require_once BASE_PATH . '/src/partials/layout.php';

// Profile edit page
route('GET', '/profile', function () {
    require_auth();
    global $_view_status;
    $errors_profile  = $_SESSION['_errors_profile']  ?? [];
    $errors_password = $_SESSION['_errors_password'] ?? [];
    $errors_delete   = $_SESSION['_errors_delete']   ?? [];
    unset($_SESSION['_errors_profile'], $_SESSION['_errors_password'], $_SESSION['_errors_delete']);

    $status = $_view_status;
    $user = auth();

    admin_layout_start('Profil | SMKN 4 Bandung');
    ?>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <?php if ($status === 'profile-updated'): ?>
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 text-sm font-semibold">Profil berhasil diperbarui.</div>
            <?php elseif ($status === 'password-updated'): ?>
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 text-sm font-semibold">Password berhasil diperbarui.</div>
            <?php endif; ?>

            <!-- Update Profile -->
            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Informasi Profil</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Perbarui informasi nama pengguna akun Anda.</p>
                    <form method="POST" action="<?= url('profile') ?>" class="mt-6 space-y-4">
                        <?= csrf_field() ?>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Username</label>
                            <input type="text" name="username" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm" value="<?= e($user['username'] ?? '') ?>" required>
                            <?= error_msg('username', $errors_profile) ?>
                        </div>
                        <div class="flex items-center gap-4">
                            <button type="submit" class="px-4 py-2 bg-smk-blue text-white text-sm font-bold rounded-lg hover:bg-blue-800 transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Perbarui Password</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
                    <form method="POST" action="<?= url('profile/password') ?>" class="mt-6 space-y-4">
                        <?= csrf_field() ?>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password Saat Ini</label>
                            <input type="password" name="current_password" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm" required>
                            <?= error_msg('current_password', $errors_password) ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password Baru</label>
                            <input type="password" name="password" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm" required>
                            <?= error_msg('password', $errors_password) ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm" required>
                            <?= error_msg('password_confirmation', $errors_password) ?>
                        </div>
                        <div>
                            <button type="submit" class="px-4 py-2 bg-smk-blue text-white text-sm font-bold rounded-lg hover:bg-blue-800 transition">Perbarui Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Hapus Akun</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Setelah akun Anda dihapus, semua data dan sumber dayanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi yang ingin Anda simpan.</p>
                    <div class="mt-6">
                        <button onclick="document.getElementById('delete-modal').classList.remove('hidden')" class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition">Hapus Akun</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg w-full max-w-md p-6 sm:p-8 ring-1 ring-slate-200 dark:ring-slate-700">
            <h3 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 mb-4">Konfirmasi Hapus Akun</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan. Masukkan password Anda untuk mengkonfirmasi.</p>
            <form method="POST" action="<?= url('profile/delete') ?>">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">Password</label>
                    <input type="password" name="password" class="block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm" required>
                    <?= error_msg('password', $errors_delete) ?>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')" class="px-5 py-2 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-sm font-bold text-white bg-red-600 hover:bg-red-700 transition">Hapus Akun</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    admin_layout_end();
});

// Update profile username
route('POST', '/profile', function () {
    require_auth();
    csrf_abort();

    $user = auth();
    $data = ['username' => post('username')];
    $rules = ['username' => 'required|string|max:255|unique:users,username,' . $user['id'] . ',id'];
    $errors = validate($data, $rules);

    if (!empty($errors)) {
        $_SESSION['_errors_profile'] = $errors;
        session_flash('status', 'error');
        redirect('/profile');
    }

    db()->prepare("UPDATE users SET username = ?, updated_at = NOW() WHERE id = ?")
       ->execute([$data['username'], $user['id']]);

    // Refresh session
    $stmt = db()->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user['id']]);
    $refreshed = $stmt->fetch();
    $_SESSION['user']['username'] = $refreshed['username'];

    session_flash('status', 'profile-updated');
    redirect('/profile');
});

// Update password
route('POST', '/profile/password', function () {
    require_auth();
    csrf_abort();

    $user = auth();
    $data = [
        'current_password'      => post('current_password'),
        'password'              => post('password'),
        'password_confirmation' => post('password_confirmation'),
    ];

    $errors = [];
    if (!verify_password($data['current_password'], $user['password'])) {
        $errors['current_password'] = ['Password saat ini tidak sesuai.'];
    }
    if (empty($data['password']) || strlen($data['password']) < 8) {
        $errors['password'][] = 'Password baru minimal 8 karakter.';
    }
    if ($data['password'] !== $data['password_confirmation']) {
        $errors['password_confirmation'] = ['Konfirmasi password tidak cocok.'];
    }

    if (!empty($errors)) {
        $_SESSION['_errors_password'] = $errors;
        redirect('/profile');
    }

    db()->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?")
       ->execute([hash_password($data['password']), $user['id']]);

    // Update session password hash
    $_SESSION['user']['password'] = hash_password($data['password']);

    session_flash('status', 'password-updated');
    redirect('/profile');
});

// Delete account
route('POST', '/profile/delete', function () {
    require_auth();
    csrf_abort();

    $user = auth();
    $password = post('password');

    if (!verify_password($password, $user['password'])) {
        $_SESSION['_errors_delete'] = ['password' => ['Password salah.']];
        redirect('/profile');
    }

    db()->prepare("DELETE FROM users WHERE id = ?")->execute([$user['id']]);

    session_start_safe();
    session_unset();
    session_destroy();

    redirect('/');
});
