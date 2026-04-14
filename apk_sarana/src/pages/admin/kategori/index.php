<?php
require_once BASE_PATH . '/src/partials/layout.php';

route('GET', '/admin/kategori', function () {
    require_auth();
    global $_view_success, $_view_error;

    $paged   = paginate("SELECT * FROM kategoris ORDER BY ket_kategori ASC", [], 10);
    $kategoris = $paged['data'];

    $success = $_view_success;
    $error   = $_view_error;

    admin_layout_start('Manajemen Kategori | SMKN 4 Bandung');
    ?>
    <div class="py-8 relative min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 relative z-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                    <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700"><svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                    <span class="text-xl font-extrabold tracking-tight">Manajemen Kategori Fasilitas</span>
                </h2>
                <a href="<?= url('admin/kategori/create') ?>" class="group inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400 transition-all group-hover:w-2"></div>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Kategori
                </a>
            </div>

            <?php if ($success): ?>
            <div class="p-4 text-sm font-semibold text-emerald-800 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 flex items-center gap-2 shadow-sm"><div class="bg-emerald-100 dark:bg-emerald-800/50 p-1 rounded-full"><svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div><?= e($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="p-4 text-sm font-semibold text-rose-800 rounded-xl bg-rose-50 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800 flex items-center gap-2 shadow-sm"><div class="bg-rose-100 dark:bg-rose-800/50 p-1 rounded-full"><svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div><?= e($error) ?></div>
            <?php endif; ?>

            <div class="bg-white/95 dark:bg-slate-800/95 shadow-xl shadow-slate-200/50 dark:shadow-none sm:rounded-3xl ring-1 ring-indigo-600/5 dark:ring-white/10 overflow-hidden transition-colors backdrop-blur-xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/50">
                        <thead class="bg-slate-50/80 dark:bg-slate-900/80 backdrop-blur-sm">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-20 hidden sm:table-cell">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nama Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            <?php if (empty($kategoris)): ?>
                            <tr><td colspan="3" class="px-6 py-12 text-center text-slate-500 font-medium">Data kategori belum tersedia.</td></tr>
                            <?php else: ?>
                            <?php foreach ($kategoris as $idx => $kat): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-slate-500 dark:text-slate-400 hidden sm:table-cell"><?= ($paged['from'] + $idx) ?></td>
                                <td class="px-6 py-5 whitespace-nowrap"><div class="text-sm font-bold text-slate-800 dark:text-white"><?= e($kat['ket_kategori']) ?></div></td>
                                <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?= url('admin/kategori/' . rawurlencode($kat['id_kategori']) . '/edit') ?>" class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors" title="Edit"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                        <form action="<?= url('admin/kategori/' . rawurlencode($kat['id_kategori'])) ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors" title="Hapus"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($paged['last_page'] > 1): ?>
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700/80">
                    <?= render_pagination($paged) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    admin_layout_end();
});
