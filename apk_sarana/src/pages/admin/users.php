<?php
require_once BASE_PATH . '/src/partials/layout.php';

route('GET', '/admin/users', function () {
    require_auth();
    global $_view_success, $_view_error;

    $conditions = [];
    $params     = [];

    if (get_param('status') !== '') {
        $conditions[] = "status = ?";
        $params[]     = get_param('status');
    }

    $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    $sql   = "SELECT * FROM users $where ORDER BY created_at DESC";
    $paged = paginate($sql, $params, 10);
    $users = $paged['data'];

    $success = $_view_success;
    $error   = $_view_error;
    $cur_id  = user_id();

    admin_layout_start('Manajemen Admin | SMKN 4 Bandung');
    ?>
    <div class="py-8 relative min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 relative z-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                    <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700"><svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg></div>
                    <span class="text-xl font-extrabold tracking-tight">Manajemen Akun Admin</span>
                </h2>
            </div>

            <?php if ($success): ?>
            <div class="mb-4 p-4 text-sm font-semibold text-emerald-800 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 flex items-center gap-2 shadow-sm"><div class="bg-emerald-100 dark:bg-emerald-800/50 p-1 rounded-full"><svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div><?= e($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="mb-4 p-4 text-sm font-semibold text-red-800 rounded-xl bg-red-50 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 flex items-center gap-2 shadow-sm"><div class="bg-red-100 dark:bg-red-800/50 p-1 rounded-full"><svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><?= e($error) ?></div>
            <?php endif; ?>

            <!-- Filter -->
            <div class="bg-white/95 dark:bg-slate-800/95 shadow-xl shadow-slate-200/50 dark:shadow-none sm:rounded-3xl p-6 ring-1 ring-smk-blue/5 dark:ring-white/10 transition-colors backdrop-blur-xl">
                <form action="<?= url('admin/users') ?>" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="space-y-1 w-full sm:w-1/3">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Status Akun</label>
                        <select name="status" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm py-2.5 px-3 transition-colors bg-slate-50 cursor-pointer dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" <?= get_param('status') == 'Menunggu' ? 'selected' : '' ?>>Menunggu Persetujuan</option>
                            <option value="Setujui" <?= get_param('status') == 'Setujui' ? 'selected' : '' ?>>Disetujui (Aktif)</option>
                            <option value="Proses" <?= get_param('status') == 'Proses' ? 'selected' : '' ?>>Diproses</option>
                            <option value="Ditolak" <?= get_param('status') == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                    </div>
                    <div><button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-700 dark:hover:bg-slate-600 transition-colors shadow-sm whitespace-nowrap">Terapkan Filter</button></div>
                    <?php if (get_param('status') !== ''): ?>
                    <div><a href="<?= url('admin/users') ?>" class="flex items-center justify-center w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:text-red-500 bg-slate-100 hover:bg-red-50 dark:bg-slate-900 dark:hover:bg-red-900/20 transition-colors">Reset</a></div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white/95 dark:bg-slate-800/95 shadow-xl shadow-slate-200/50 dark:shadow-none sm:rounded-3xl overflow-hidden ring-1 ring-slate-100 dark:ring-slate-700 transition-colors backdrop-blur-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600 dark:text-slate-400">
                        <thead class="text-xs tracking-wider text-slate-500 uppercase bg-slate-50 dark:bg-slate-900/80 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700 font-bold">
                            <tr>
                                <th class="px-6 py-4 hidden md:table-cell">Tanggal Registrasi</th>
                                <th class="px-6 py-4">Username Admin</th>
                                <th class="px-6 py-4">Kondisi / Status Akun</th>
                                <th class="px-6 py-4">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            <?php if (empty($users)): ?>
                            <tr><td colspan="4" class="px-6 py-16 text-center"><p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tidak ada data pengguna yang ditemukan.</p></td></tr>
                            <?php else: ?>
                            <?php foreach ($users as $usr):
                                $badgeClass = match($usr['status']) {
                                    'Setujui'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 ring-emerald-600/20',
                                    'Proses', 'Menunggu' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 ring-amber-600/20',
                                    'Ditolak'  => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400 ring-red-600/20',
                                    default    => 'bg-slate-100 text-slate-700 dark:bg-slate-500/10 dark:text-slate-400 ring-slate-600/20',
                                };
                                $isSelf = (int)$usr['id'] === (int)$cur_id;
                            ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                    <div class="font-medium text-slate-800 dark:text-slate-200"><?= format_date($usr['created_at'], 'd F Y') ?></div>
                                    <div class="text-xs text-slate-400 font-mono mt-0.5"><?= date('H:i', strtotime($usr['created_at'])) ?> WIB</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                    <?= e($usr['username']) ?>
                                    <?php if ($isSelf): ?>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">Anda</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $badgeClass ?> ring-1 ring-inset"><?= e($usr['status']) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <button type="button" <?= !$isSelf ? "onclick=\"document.getElementById('modal-user-{$usr['id']}').classList.remove('hidden')\"" : '' ?>
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-smk-blue transition-all shadow-sm <?= $isSelf ? 'opacity-50 cursor-not-allowed' : '' ?>">
                                        Persetujuan Akun
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($paged['last_page'] > 1): ?>
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/50">
                    <?= render_pagination($paged, get_param('status') ? ['status' => get_param('status')] : []) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <?php foreach ($users as $usr): $isSelf = (int)$usr['id'] === (int)$cur_id; if ($isSelf) continue; ?>
    <div id="modal-user-<?= $usr['id'] ?>" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 p-4 transition-opacity backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg w-full max-w-md max-h-[95vh] overflow-y-auto p-6 sm:p-8 ring-1 ring-slate-200 dark:ring-slate-700 transition-colors">
            <div class="flex justify-between items-center mb-6 border-b border-slate-100 dark:border-slate-700/80 pb-4">
                <h3 class="text-xl font-extrabold text-slate-800 dark:text-slate-100">Kelola Status Akun</h3>
                <button type="button" onclick="document.getElementById('modal-user-<?= $usr['id'] ?>').classList.add('hidden')" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 dark:bg-slate-900 dark:hover:bg-red-900/30 p-2 rounded-full transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="<?= url('admin/users/' . rawurlencode($usr['id'])) ?>" method="POST" class="text-left">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="mb-5 space-y-1.5">
                    <label class="block text-sm font-bold tracking-wide text-slate-700 dark:text-slate-300">Ubah Status Akun: <?= e($usr['username']) ?></label>
                    <select name="status" class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 shadow-sm sm:text-sm py-3 px-4 transition-colors bg-slate-50 cursor-pointer">
                        <option value="Menunggu" <?= $usr['status'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu Persetujuan</option>
                        <option value="Proses" <?= $usr['status'] == 'Proses' ? 'selected' : '' ?>>Diproses (Ditunda)</option>
                        <option value="Setujui" <?= $usr['status'] == 'Setujui' ? 'selected' : '' ?>>Setujui (Izinkan Login)</option>
                        <option value="Ditolak" <?= $usr['status'] == 'Ditolak' ? 'selected' : '' ?>>Tolak Akun</option>
                    </select>
                </div>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8 pb-2">
                    <button type="button" onclick="document.getElementById('modal-user-<?= $usr['id'] ?>').classList.add('hidden')" class="w-full sm:w-auto px-5 py-3 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Tutup Batal</button>
                    <button type="submit" class="group w-full sm:w-auto px-6 py-3 rounded-xl shadow-lg shadow-smk-blue/30 text-sm font-bold text-white bg-smk-blue hover:bg-blue-800 transition-all transform hover:-translate-y-0.5"><span class="z-10 relative">Simpan Perubahan</span></button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
    <?php
    admin_layout_end();
});

route('PUT', '/admin/users/{id}', function (string $id) {
    require_auth();
    csrf_abort();

    $cur_id = user_id();
    $stmt   = db()->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $target = $stmt->fetch();

    if (!$target) {
        session_flash('error', 'User tidak ditemukan.');
        redirect('/admin/users');
    }

    $new_status = post('status');
    $allowed    = ['Menunggu', 'Proses', 'Setujui', 'Ditolak'];
    if (!in_array($new_status, $allowed)) {
        session_flash('error', 'Status tidak valid.');
        redirect('/admin/users');
    }

    if ((int)$id === (int)$cur_id && $new_status !== 'Setujui') {
        session_flash('error', 'Anda tidak dapat mengubah status akun Anda sendiri menjadi tidak aktif.');
        redirect('/admin/users');
    }

    db()->prepare("UPDATE users SET status = ?, updated_at = NOW() WHERE id = ?")->execute([$new_status, $id]);
    session_flash('success', 'Status akun admin berhasil diperbarui.');
    redirect('/admin/users');
});
