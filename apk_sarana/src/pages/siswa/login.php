<?php
require_once BASE_PATH . '/src/partials/layout.php';

// Show login form
route('GET', '/siswa/login', function () {
    global $_view_status, $_view_error;
    require_siswa_guest();
    $errors = validation_errors();
    $status = $_view_status;
    $error  = $_view_error;

    guest_layout_start('Login Siswa | SMKN 4 Bandung', 'Portal Siswa', 'Dibuat oleh SMKN 4 Bandung');
    ?>
    <?php if ($status): ?>
    <div class="mb-4 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-400 text-sm font-semibold"><?= e($status) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="mb-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 text-sm font-semibold"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= url('siswa/login') ?>">
        <?= csrf_field() ?>
        <div>
            <label for="nis" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor Induk Siswa (NIS)</label>
            <input id="nis" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20" type="text" name="nis" value="<?= old('nis') ?>" required autofocus placeholder="Contoh: 2023101">
            <?= error_msg('nis', $errors) ?>
        </div>
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kata Sandi</label>
            <input id="password" class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white px-3 py-2.5 text-sm shadow-sm focus:border-smk-blue focus:ring-2 focus:ring-smk-blue/20" type="password" name="password" required>
            <?= error_msg('password', $errors) ?>
        </div>
        
        <div class="flex flex-col mt-6 gap-3">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-smk-blue border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150 shadow-lg shadow-smk-blue/30">
                Masuk Sistem
            </button>
            <div class="text-center mt-2">
                <a class="underline text-sm text-gray-500 dark:text-gray-400 hover:text-smk-blue dark:hover:text-blue-400" href="<?= url('login') ?>">Bukan siswa? Masuk sebagai Admin</a>
            </div>
        </div>
    </form>
    <?php
    guest_layout_end();
});

// Handle login POST
route('POST', '/siswa/login', function () {
    require_siswa_guest();
    csrf_abort();

    $nis      = post('nis');
    $password = post('password');

    $errors = [];
    if (empty($nis)) $errors['nis'][] = 'NIS wajib diisi.';
    if (empty($password)) $errors['password'][] = 'Kata sandi wajib diisi.';

    if (empty($errors)) {
        $stmt = db()->prepare("SELECT * FROM siswas WHERE nis = ? LIMIT 1");
        $stmt->execute([$nis]);
        $siswa = $stmt->fetch();

        if (!$siswa || !verify_password($password, $siswa['password'])) {
            $errors['nis'][] = 'NIS atau kata sandi tidak cocok.';
        }
    }

    if (!empty($errors)) {
        flash_errors($errors);
        flash_old();
        redirect('/siswa/login');
    }

    session_start_safe();
    session_regenerate_id(true);
    $_SESSION['siswa'] = [
        'nis'   => $siswa['nis'],
        'nama'  => $siswa['nama'],
        'kelas' => $siswa['kelas']
    ];

    $intended = get_flash('intended');
    session_flash('success', 'Selamat datang, ' . $siswa['nama'] . '!');
    redirect($intended ?: '/');
});
