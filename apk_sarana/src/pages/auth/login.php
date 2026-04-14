<?php
require_once BASE_PATH . '/src/partials/layout.php';

// Show login form
route('GET', '/login', function () {
    global $_view_status, $_view_error;
    require_guest();
    $errors = validation_errors();
    $status = $_view_status;
    $error  = $_view_error;

    guest_layout_start('Login Admin | SMKN 4 Bandung', 'Portal Admin', 'Dibuat oleh SMKN 4 Bandung');
    ?>
    <?php if ($status): ?>
    <div class="mb-4 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-400 text-sm font-semibold"><?= e($status) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="mb-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 text-sm font-semibold"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= url('login') ?>">
        <?= csrf_field() ?>
        <div>
            <label for="username" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Username</label>
            <input id="username" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20" type="text" name="username" value="<?= old('username') ?>" required autofocus autocomplete="username">
            <?= error_msg('username', $errors) ?>
        </div>
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
            <input id="password" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20" type="password" name="password" required autocomplete="current-password">
            <?= error_msg('password', $errors) ?>
        </div>
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
            </label>
        </div>
        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" href="<?= url('register') ?>">Daftar akun admin</a>
            <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-smk-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150">
                Masuk
            </button>
        </div>
    </form>
    <?php
    guest_layout_end();
});

// Handle login POST
route('POST', '/login', function () {
    require_guest();
    csrf_abort();

    $username = post('username');
    $password = post('password');

    $errors = [];
    if (empty($username)) $errors['username'][] = 'Username wajib diisi.';
    if (empty($password)) $errors['password'][] = 'Password wajib diisi.';

    if (empty($errors)) {
        $stmt = db()->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !verify_password($password, $user['password'])) {
            $errors['username'][] = 'Username atau password salah.';
        } elseif ($user['status'] !== 'Setujui') {
            session_flash('error', 'Akun Anda belum disetujui atau telah ditolak oleh Administrator.');
            flash_old();
            redirect('/login');
        }
    }

    if (!empty($errors)) {
        flash_errors($errors);
        flash_old();
        redirect('/login');
    }

    session_start_safe();
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'       => $user['id'],
        'username' => $user['username'],
        'status'   => $user['status'],
        'password' => $user['password'], // needed for current_password validation
    ];

    $intended = get_flash('intended');
    redirect($intended ?: '/admin/dashboard');
});
