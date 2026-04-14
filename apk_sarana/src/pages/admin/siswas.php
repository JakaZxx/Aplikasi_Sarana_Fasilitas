<?php
require_once BASE_PATH . '/src/partials/layout.php';

route('GET', '/admin/siswas', function () {
    require_auth();
    global $_view_success, $_view_error;

    $kelas = get_param('kelas');
    $conditions = [];
    $params = [];

    if ($kelas !== '') {
        $conditions[] = "kelas = ?";
        $params[] = $kelas;
    }

    $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    $sql = "SELECT * FROM siswas $where ORDER BY nis ASC";
    $paged = paginate($sql, $params, 10);
    $siswas = $paged['data'];

    $unique_kelas = db()->query("SELECT DISTINCT kelas FROM siswas ORDER BY kelas ASC")->fetchAll(PDO::FETCH_COLUMN);

    $success = $_view_success;
    $error   = $_view_error;

    admin_layout_start('Manajemen Siswa | SMKN 4 Bandung');
    ?>
    <div class="py-8 relative min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 relative z-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-4">
                <h2 class="font-bold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                    <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700"><svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg></div>
                    <span class="text-xl font-extrabold tracking-tight">Manajemen Akun Siswa</span>
                </h2>
                <button onclick="document.getElementById('modal-create').classList.remove('hidden')" class="px-5 py-2.5 bg-smk-blue text-white font-bold rounded-xl shadow-lg shadow-smk-blue/30 hover:bg-blue-800 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Tambah Siswa Baru
                </button>
            </div>

            <?php if ($success): ?>
            <div class="mb-4 p-4 text-sm font-semibold text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200"><?= e($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="mb-4 p-4 text-sm font-semibold text-red-800 rounded-xl bg-red-50 border border-red-200"><?= e($error) ?></div>
            <?php endif; ?>

            <!-- Filter -->
            <div class="bg-white dark:bg-slate-800 shadow-xl sm:rounded-3xl p-6 ring-1 ring-slate-100 dark:ring-slate-700">
                <form action="<?= url('admin/siswas') ?>" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="space-y-1 w-full sm:w-1/3">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Saring Kelas</label>
                        <select name="kelas" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 py-2.5 px-3 dark:text-white">
                            <option value="">Semua Kelas</option>
                            <?php foreach ($unique_kelas as $k): if (trim($k) === '') continue; ?>
                            <option value="<?= e($k) ?>" <?= get_param('kelas') === $k ? 'selected' : '' ?>><?= e($k) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div><button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-slate-800 dark:bg-slate-700">Terapkan</button></div>
                    <?php if (get_param('kelas') !== ''): ?>
                    <div><a href="<?= url('admin/siswas') ?>" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 bg-slate-100 dark:bg-slate-900">Reset</a></div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-slate-800 shadow-xl sm:rounded-3xl overflow-hidden ring-1 ring-slate-100 dark:ring-slate-700">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600 dark:text-slate-400">
                        <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold">
                            <tr>
                                <th class="px-6 py-4">Nomor Induk Siswa (NIS)</th>
                                <th class="px-6 py-4">Nama Lengkap</th>
                                <th class="px-6 py-4">Kelas / Jurusan</th>
                                <th class="px-6 py-4 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <?php if (empty($siswas)): ?>
                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Tidak ada data siswa ditemukan.</td></tr>
                            <?php else: ?>
                            <?php foreach ($siswas as $sw): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200"><?= e($sw['nis']) ?></td>
                                <td class="px-6 py-4 font-medium"><?= e($sw['nama']) ?></td>
                                <td class="px-6 py-4"><?= e($sw['kelas']) ?></td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button onclick="document.getElementById('modal-edit-<?= $sw['nis'] ?>').classList.remove('hidden')" class="px-3 py-1.5 bg-sky-100 text-sky-700 font-bold rounded-lg hover:bg-sky-200 transition-colors">Edit</button>
                                    <button onclick="document.getElementById('modal-del-<?= $sw['nis'] ?>').classList.remove('hidden')" class="px-3 py-1.5 bg-red-100 text-red-700 font-bold rounded-lg hover:bg-red-200 transition-colors">Hapus</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($paged['last_page'] > 1): ?>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                    <?= render_pagination($paged, get_param('kelas') ? ['kelas' => get_param('kelas')] : []) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md p-6">
            <h3 class="text-xl font-bold mb-4">Tambah Akun Siswa Baru</h3>
            <form action="<?= url('admin/siswas') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">NIS</label>
                        <input type="text" name="nis" required class="w-full rounded-xl border border-slate-200 p-2.5 pb-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" required class="w-full rounded-xl border border-slate-200 p-2.5 pb-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Kelas</label>
                        <input type="text" name="kelas" required class="w-full rounded-xl border border-slate-200 p-2.5 pb-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Kata Sandi</label>
                        <input type="password" name="password" required class="w-full rounded-xl border border-slate-200 p-2.5 pb-2">
                        <p class="text-[10px] text-gray-500 mt-1">Disarankan menggunakan NIS siswa bersangkutan agar mudah diingat.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')" class="px-5 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-white bg-smk-blue font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals Edit & Delete -->
    <?php foreach ($siswas as $sw): ?>
    <div id="modal-edit-<?= $sw['nis'] ?>" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md p-6">
            <h3 class="text-xl font-bold mb-4">Edit Data Siswa: <?= e($sw['nis']) ?></h3>
            <form action="<?= url('admin/siswas/' . $sw['nis']) ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= e($sw['nama']) ?>" required class="w-full rounded-xl border border-slate-200 p-2.5 pb-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Kelas</label>
                        <input type="text" name="kelas" value="<?= e($sw['kelas']) ?>" required class="w-full rounded-xl border border-slate-200 p-2.5 pb-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="w-full rounded-xl border border-slate-200 p-2.5 pb-2">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modal-edit-<?= $sw['nis'] ?>').classList.add('hidden')" class="px-5 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-white bg-smk-blue font-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    <div id="modal-del-<?= $sw['nis'] ?>" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-sm p-6 text-center">
            <h3 class="text-xl font-bold mb-2">Hapus Akun Siswa?</h3>
            <p class="text-sm text-slate-500 mb-6">Penghapusan akun <?= e($sw['nis']) ?> akan bersifat permanen. Apakah Anda yakin?</p>
            <form action="<?= url('admin/siswas/' . $sw['nis']) ?>" method="POST" class="flex justify-center gap-3">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="button" onclick="document.getElementById('modal-del-<?= $sw['nis'] ?>').classList.add('hidden')" class="px-5 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-white bg-red-600 hover:bg-red-700 font-bold">Ya, Hapus</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>

    <?php
    admin_layout_end();
});

// Create
route('POST', '/admin/siswas', function () {
    require_auth();
    csrf_abort();
    
    $nis   = trim(post('nis'));
    $nama  = trim(post('nama'));
    $kelas = trim(post('kelas'));
    $pass  = post('password');

    if (empty($nis) || empty($nama) || empty($kelas) || empty($pass)) {
        session_flash('error', 'Semua kolom wajib diisi.');
        redirect('/admin/siswas');
    }

    $pdo = db();
    $stmt = $pdo->prepare("SELECT nis FROM siswas WHERE nis = ?");
    $stmt->execute([$nis]);
    if ($stmt->fetch()) {
        session_flash('error', 'NIS sudah terdaftar dalam sistem.');
        redirect('/admin/siswas');
    }

    $hashed = password_hash($pass, PASSWORD_BCRYPT);
    $pdo->prepare("INSERT INTO siswas (nis, nama, kelas, password, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())")
        ->execute([$nis, $nama, $kelas, $hashed]);

    session_flash('success', 'Akun siswa baru berhasil ditambahkan.');
    redirect('/admin/siswas');
});

// Update
route('PUT', '/admin/siswas/{nis}', function (string $nis) {
    require_auth();
    csrf_abort();

    $nama  = trim(post('nama'));
    $kelas = trim(post('kelas'));
    $pass  = post('password');

    if (empty($nama) || empty($kelas)) {
        session_flash('error', 'Nama dan Kelas tidak boleh kosong.');
        redirect('/admin/siswas');
    }

    $pdo = db();
    if (!empty($pass)) {
        $hashed = password_hash($pass, PASSWORD_BCRYPT);
        $pdo->prepare("UPDATE siswas SET nama = ?, kelas = ?, password = ?, updated_at = NOW() WHERE nis = ?")
            ->execute([$nama, $kelas, $hashed, $nis]);
        session_flash('success', 'Data siswa beserta kata sandinya berhasil diperbarui.');
    } else {
        $pdo->prepare("UPDATE siswas SET nama = ?, kelas = ?, updated_at = NOW() WHERE nis = ?")
            ->execute([$nama, $kelas, $nis]);
        session_flash('success', 'Data siswa berhasil diperbarui.');
    }

    redirect('/admin/siswas');
});

// Delete
route('DELETE', '/admin/siswas/{nis}', function (string $nis) {
    require_auth();
    csrf_abort();

    $pdo = db();
    // Validate if child records exist in input_aspirasis
    $stmt = $pdo->prepare("SELECT id_pelaporan FROM input_aspirasis WHERE nis = ? LIMIT 1");
    $stmt->execute([$nis]);
    if ($stmt->fetch()) {
        session_flash('error', 'Tidak dapat menghapus siswa karena siswa tersebut memiliki riwayat pengaduan. Hapus aduan terlebih dahulu jika Anda bersikeras.');
        redirect('/admin/siswas');
    }

    $pdo->prepare("DELETE FROM siswas WHERE nis = ?")->execute([$nis]);
    session_flash('success', 'Data siswa berhasil dihapus.');
    redirect('/admin/siswas');
});
