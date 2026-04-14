<?php
require_once BASE_PATH . '/src/partials/layout.php';

route('GET', '/register', function () {
    global $_view_status;
    require_guest();
    $errors = validation_errors();
    $status = $_view_status;

    guest_layout_start('Daftar Akun Admin | SMKN 4 Bandung');
    ?>
    <?php if ($status): ?>
    <div class="mb-4 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-400 text-sm font-semibold"><?= e($status) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= url('register') ?>">
        <?= csrf_field() ?>
        <div>
            <label for="username" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Username</label>
            <input id="username" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20" type="text" name="username" value="<?= old('username') ?>" required autofocus autocomplete="username">
            <?= error_msg('username', $errors) ?>
        </div>
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
            <input id="password" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20" type="password" name="password" required autocomplete="new-password">
            <?= error_msg('password', $errors) ?>
        </div>
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Konfirmasi Password</label>
            <input id="password_confirmation" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20" type="password" name="password_confirmation" required autocomplete="new-password">
            <?= error_msg('password_confirmation', $errors) ?>
        </div>
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" href="<?= url('login') ?>">Sudah punya akun?</a>
            <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-smk-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none transition ease-in-out duration-150">
                Daftar
            </button>
        </div>
    </form>
    <?php
    guest_layout_end();
});

route('POST', '/register', function () {
    require_guest();
    csrf_abort();

    $data = [
        'username'              => post('username'),
        'password'              => post('password'),
        'password_confirmation' => post('password_confirmation'),
    ];

    $rules = [
        'username' => 'required|string|max:255|unique:users,username',
        'password' => 'required|min:8|confirmed',
    ];

    $errors = validate($data, $rules);

    if (!empty($errors)) {
        flash_errors($errors);
        flash_old();
        redirect('/register');
    }

    db()->prepare("INSERT INTO users (username, password, status, created_at, updated_at) VALUES (?, ?, 'Menunggu', NOW(), NOW())")
       ->execute([$data['username'], hash_password($data['password'])]);

    clear_old();
    session_flash('status', 'Pendaftaran berhasil. Akun Anda saat ini sedang menunggu persetujuan dari Admin.');
    redirect('/login');
});
